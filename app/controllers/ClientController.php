<?php
/**
 * Controller dei clienti.
 * Gestisce visualizzazione, creazione, modifica ed eliminazione clienti.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Core\AiClient;
use App\Models\ClienteInsights;
use App\Models\Client;
use App\Models\SchedaAnalisi;
use App\Models\User;

class ClientController
{
    private $model;

    public function __construct()
    {
        Auth::require();
        $this->model = new Client();
    }

    public function index()
    {
        $clients = $this->model->getAllWithLastVisit();
        require_once __DIR__ . '/../views/clients.php';
    }

    public function show($id)
    {
        $id = (int) $id;
        $client = $this->model->getById($id);
        if (!$client) {
            header('Location: clients.php');
            exit;
        }

        $schedaAnalisi = new SchedaAnalisi();
        $visite = $schedaAnalisi->getVisiteByClient($id);

        $insights = new ClienteInsights();
        $localSummary = $insights->buildSummary($id);

        $generatedByAi = $_SESSION['ai_generated_summary'][$id] ?? null;
        $aiSummary = $generatedByAi ?: $localSummary;
        $aiSource = $generatedByAi ? 'api' : 'local';

        $aiMessage = $_SESSION['ai_flash_message'] ?? null;
        $aiError = $_SESSION['ai_flash_error'] ?? null;
        unset($_SESSION['ai_flash_message'], $_SESSION['ai_flash_error']);

        $aiClient = new AiClient();
        $aiCanGenerate = $aiClient->isConfigured();
        $aiConfigHint = $aiClient->getConfigHint();

        require_once __DIR__ . '/../views/client_view.php';
    }

    public function generateAiSummary($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            header('Location: clients.php');
            exit;
        }

        $client = $this->model->getById($id);
        if (!$client) {
            header('Location: clients.php');
            exit;
        }

        $insights = new ClienteInsights();
        $localSummary = $insights->buildSummary($id);

        try {
            $aiClient = new AiClient();
            if (!$aiClient->isConfigured()) {
                throw new \RuntimeException($aiClient->getConfigHint());
            }

            $context = $insights->buildAiContext($id, 12);
            $generated = $aiClient->generateClientTrendText($context);

            $_SESSION['ai_generated_summary'][$id] = $generated;
            $_SESSION['ai_flash_message'] = 'Andamento cliente generato con AI.';
        } catch (\Throwable $e) {
            // Se la API non risponde, torna al riepilogo locale senza bloccare la pagina.
            $_SESSION['ai_generated_summary'][$id] = $localSummary;
            $_SESSION['ai_flash_error'] = 'Impossibile generare via API: ' . $e->getMessage();
        }

        header('Location: clients.php?action=show&id=' . $id);
        exit;
    }

    public function create()
    {
        $this->model->create($_POST);
        header('Location: clients.php');
        exit;
    }

    public function edit($id)
    {
        $clientForm = $this->model->getById($id);
        if (!$clientForm) {
            header('Location: clients.php');
            exit;
        }

        require_once __DIR__ . '/../views/client_view.php';
    }

    public function update()
    {
        $id = (int) ($_POST['cliente_id'] ?? 0);
        if ($id <= 0) {
            header('Location: clients.php');
            exit;
        }

        $this->model->update($id, $_POST);
        header('Location: clients.php?action=show&id=' . $id);
        exit;
    }

    public function delete()
    {
        $id              = (int) ($_POST['cliente_id'] ?? 0);
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$id) {
            header('Location: clients.php');
            exit;
        }

        // Controlla la password dell'utente loggato in modo sicuro (hash).
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        if (!$user || !$userModel->verifyPassword($user, $confirmPassword)) {
            // Password sbagliata: annulla l'operazione e mostra errore.
            $_SESSION['delete_error'] = 'Password non corretta. Eliminazione annullata.';
            header('Location: clients.php');
            exit;
        }

        $this->model->delete($id);
        header('Location: clients.php');
        exit;
    }
}

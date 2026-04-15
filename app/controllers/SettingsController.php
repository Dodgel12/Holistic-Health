<?php
/**
 * Controller impostazioni applicative.
 * Controller impostazioni.
 * Gestisce domande, cataloghi e azioni amministrative.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\CatalogoConfig;
use App\Models\Client;
use App\Models\DomandaImpostazione;
use App\Models\User;

class SettingsController
{
    private $domandeModel;
    private $clientModel;
    private $catalogoModel;

    public function __construct()
    {
        Auth::require();
        $this->domandeModel = new DomandaImpostazione();
        $this->clientModel = new Client();
        $this->catalogoModel = new CatalogoConfig();
    }

    public function index()
    {
        $domande = $this->domandeModel->getAll();
        $clients = $this->clientModel->getAll();
        $alimenti = $this->catalogoModel->getAlimenti();
        $integratori = $this->catalogoModel->getIntegratori();
        $farmaci = $this->catalogoModel->getFarmaci();
        $settingsMessage = $_SESSION['settings_message'] ?? null;
        unset($_SESSION['settings_message']);

        require_once __DIR__ . '/../views/settings.php';
    }

    public function createQuestion()
    {
        $testo = trim($_POST['testo'] ?? '');
        if ($testo === '') {
            $_SESSION['settings_message'] = 'Inserisci un testo valido per la domanda.';
            header('Location: settings.php');
            exit;
        }

        $this->domandeModel->create($testo);
        $_SESSION['settings_message'] = 'Domanda aggiunta correttamente.';
        header('Location: settings.php');
        exit;
    }

    public function updateQuestion()
    {
        $id = (int) ($_POST['domanda_id'] ?? 0);
        $testo = trim($_POST['testo'] ?? '');

        if ($id <= 0 || $testo === '') {
            $_SESSION['settings_message'] = 'Impossibile aggiornare la domanda.';
            header('Location: settings.php');
            exit;
        }

        $this->domandeModel->update($id, $testo);
        $_SESSION['settings_message'] = 'Domanda aggiornata.';
        header('Location: settings.php');
        exit;
    }

    public function deleteQuestion()
    {
        $id = (int) ($_POST['domanda_id'] ?? 0);
        if ($id > 0) {
            $this->domandeModel->delete($id);
            $_SESSION['settings_message'] = 'Domanda eliminata.';
        }

        header('Location: settings.php');
        exit;
    }

    public function deleteClient()
    {
        $id = (int) ($_POST['cliente_id'] ?? 0);
        $confirmPassword = trim((string) ($_POST['confirm_password'] ?? ''));

        if ($id <= 0 || $confirmPassword === '') {
            $_SESSION['settings_message'] = 'Password di conferma obbligatoria.';
            header('Location: settings.php');
            exit;
        }

        $userModel = new User();
        $user = $userModel->getById((int) ($_SESSION['user_id'] ?? 0));
        if (!$user || !$userModel->verifyPassword($user, $confirmPassword)) {
            $_SESSION['settings_message'] = 'Password non corretta. Paziente non eliminato.';
            header('Location: settings.php');
            exit;
        }

        $this->clientModel->delete($id);
        $_SESSION['settings_message'] = 'Paziente eliminato correttamente.';
        header('Location: settings.php');
        exit;
    }

    public function createCatalogItem()
    {
        $categoria = $_POST['categoria'] ?? '';
        $nome = trim($_POST['nome'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');

        if ($nome === '') {
            $_SESSION['settings_message'] = 'Inserisci un nome valido per la configurazione.';
            header('Location: settings.php');
            exit;
        }

        $this->catalogoModel->createItem($categoria, $nome, $descrizione);
        $_SESSION['settings_message'] = 'Elemento di configurazione aggiunto.';
        header('Location: settings.php');
        exit;
    }

    public function deleteCatalogItem()
    {
        $categoria = $_POST['categoria'] ?? '';
        $id = (int) ($_POST['item_id'] ?? 0);
        if ($id > 0) {
            $this->catalogoModel->deleteItem($categoria, $id);
            $_SESSION['settings_message'] = 'Elemento di configurazione eliminato.';
        }

        header('Location: settings.php');
        exit;
    }
}

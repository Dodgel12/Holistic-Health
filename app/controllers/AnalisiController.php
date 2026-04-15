<?php
/**
 * Controller analisi/visite.
 * Gestisce creazione, dettaglio e storico visite.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;
use App\Models\SchedaAnalisi;
use App\Models\SchedaAnamnestica;
use App\Models\User;

class AnalisiController
{
    private $model;

    public function __construct()
    {
        Auth::require();
        $this->model = new SchedaAnalisi();
    }

    public function index()
    {
        header('Location: clients.php');
        exit;
    }

    public function create($clientId)
    {
        $schedaAnamnestica = new SchedaAnamnestica();
        if (!$schedaAnamnestica->hasAnamnesis($clientId)) {
             // Se manca l'anamnesi, manda prima a compilare quella.
             header('Location: anamnesis.php?action=create&clientId=' . $clientId);
             exit;
        }

        $client = (new Client())->getById($clientId);
        require_once __DIR__ . '/../views/analysis_form.php';
    }

    public function store()
    {
        $clienteId = (int) $_POST['cliente_id'];
        $dataAnalisi = $_POST['data_analisi'] ?? date('Y-m-d');
        $tipoVisita = $_POST['tipo_visita'] ?? 'anamnestica';

        $visitaId  = $this->model->createVisita($clienteId, $dataAnalisi, $_POST['note'] ?? '', $tipoVisita);
        if ($tipoVisita === 'fisica') {
            $this->model->saveFisica($visitaId, $_POST);
        }

        header('Location: visits.php?action=history&clientId=' . $clienteId);
        exit;
    }

    public function edit($id)
    {
        $visita = $this->model->getVisitaById($id);
        if (!$visita) {
            header('Location: clients.php');
            exit;
        }

        $client = (new Client())->getById($visita['cliente_id']);
        require_once __DIR__ . '/../views/analysis_form.php';
    }

    public function update()
    {
        $visitaId = (int) ($_POST['visita_id'] ?? 0);
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $dataAnalisi = $_POST['data_analisi'] ?? date('Y-m-d');
        $tipoVisita = $_POST['tipo_visita'] ?? 'anamnestica';

        if ($visitaId <= 0 || $clienteId <= 0) {
            header('Location: clients.php');
            exit;
        }

        $this->model->updateVisita($visitaId, $clienteId, $dataAnalisi, $_POST['note'] ?? '', $tipoVisita);
        if ($tipoVisita === 'fisica') {
            $this->model->saveFisica($visitaId, $_POST);
        } else {
            $this->model->deleteFisicaByVisita($visitaId);
        }

        header('Location: visits.php?action=show&id=' . $visitaId);
        exit;
    }

    public function show($id)
    {
        $visita = $this->model->getVisitaById($id);
        if (!$visita) {
            header('Location: clients.php');
            exit;
        }

        $visita['anamnesi_snapshot'] = $this->model->getLatestAnamnesiSnapshotUntilVisita(
            (int) $visita['cliente_id'],
            $visita['data_analisi'],
            (int) $visita['visita_id']
        );
        $visita['fisica_snapshot'] = $this->model->getLatestFisicaSnapshotUntilVisita(
            (int) $visita['cliente_id'],
            $visita['data_analisi'],
            (int) $visita['visita_id']
        );

        require_once __DIR__ . '/../views/analysis_view.php';
    }

    public function history($clientId)
    {
        $client = (new Client())->getById($clientId);
        $visite = $this->model->getVisiteByClientWithFisica($clientId);
        $visitsMessage = $_SESSION['visits_message'] ?? null;
        unset($_SESSION['visits_message']);
        require_once __DIR__ . '/../views/visit_history.php';
    }

    public function delete()
    {
        $visitaId = (int) ($_POST['visita_id'] ?? 0);
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($visitaId <= 0 || $clienteId <= 0 || $confirmPassword === '') {
            $_SESSION['visits_message'] = 'Dati mancanti: impossibile eliminare la visita.';
            header('Location: visits.php?action=history&clientId=' . $clienteId);
            exit;
        }

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id'] ?? 0);
        if (!$user || !$userModel->verifyPassword($user, $confirmPassword)) {
            $_SESSION['visits_message'] = 'Password non corretta. Visita non eliminata.';
            header('Location: visits.php?action=history&clientId=' . $clienteId);
            exit;
        }

        $this->model->deleteVisita($visitaId, $clienteId);
        $_SESSION['visits_message'] = 'Visita eliminata correttamente.';
        header('Location: visits.php?action=history&clientId=' . $clienteId);
        exit;
    }
}

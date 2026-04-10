<?php
/**
 * Controller delle analisi / visite.
 * Gestisce creazione, visualizzazione e storico visite.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;
use App\Models\SchedaAnalisi;
use App\Models\SchedaAnamnestica;
use App\Models\Questionario;

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
             // Forza l'anamnesi se non esiste
             header('Location: anamnesis.php?action=create&clientId=' . $clientId);
             exit;
        }

        $client = (new Client())->getById($clientId);
        require_once __DIR__ . '/../views/analysis_form.php';
    }

    public function store()
    {
        $clienteId = (int) $_POST['cliente_id'];
        $visitaId  = $this->model->createVisita($clienteId, date('Y-m-d'), $_POST['note'] ?? '');
        $this->model->saveFisica($visitaId, $_POST);
        header('Location: visits.php?action=history&clientId=' . $clienteId);
        exit;
    }

    public function show($id)
    {
        $visita = $this->model->getVisitaById($id);
        require_once __DIR__ . '/../views/analysis_view.php';
    }

    public function history($clientId)
    {
        $client = (new Client())->getById($clientId);
        $visite = $this->model->getVisiteByClientWithFisica($clientId);
        require_once __DIR__ . '/../views/visit_history.php';
    }
}

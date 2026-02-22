<?php
/**
 * Controller delle analisi.
 * Coordina la creazione delle schede di analisi,
 * il caricamento dei questionari e il salvataggio
 * delle risposte.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;
use App\Models\SchedaAnalisi;
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
        $client = (new Client())->getById($clientId);
        $questionari = (new Questionario())->getActive();
        require_once __DIR__ . '/../views/analysis_form.php';
    }

    public function store()
    {
        $clienteId = $_POST['cliente_id'];
        $visitaId = $this->model->createVisita($clienteId, date('Y-m-d'), $_POST['note'] ?? '');
        $this->model->saveFisica($visitaId, $_POST);
        header('Location: clients.php?action=show&id=' . $clienteId);
        exit;
    }

    public function show($id)
    {
        $visita = $this->model->getVisitaById($id);
        require_once __DIR__ . '/../views/analysis_view.php';
    }
}

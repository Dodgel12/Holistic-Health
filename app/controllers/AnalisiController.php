<?php
/**
 * Controller delle analisi.
 * Coordina la creazione delle schede di analisi,
 * il caricamento dei questionari e il salvataggio
 * delle risposte.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\SchedaAnalisi;
use App\Models\SchedaAnamnestica;
use App\Models\Client;
use App\Models\Questionario;
use App\Models\Risposta;

class AnalisiController {
    private $analisiModel;
    private $clientModel;

    public function __construct() {
        $this->analisiModel = new SchedaAnalisi();
        $this->clientModel = new Client();
        // Inizializzazione degli altri modelli necessari
    }

    public function index() {
        Auth::requireLogin();
        // Mostra l'elenco delle analisi o la dashboard
        require_once __DIR__ . '/../views/analysis.php';
    }

    public function create($clientId) {
        Auth::requireLogin();
        $client = $this->clientModel->getById($clientId);
        $questionari = (new Questionario())->getActive();
        
        $data = [
            'client' => $client,
            'questionari' => $questionari,
            'title' => 'Nuova Analisi'
        ];
        extract($data);
        require_once __DIR__ . '/../views/analysis_form.php';
    }

    public function store() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = $_POST['cliente_id'];
            $visitaId = $this->analisiModel->createVisita($clientId, date('Y-m-d'), $_POST['note'] ?? '');
            
            // Salvataggio Risposte Questionario
            if (isset($_POST['risposte'])) {
                $rispostaModel = new Risposta();
                foreach ($_POST['risposte'] as $domandaId => $risposta) {
                    $rispostaModel->save($visitaId, $domandaId, $risposta);
                }
            }

            // Salvataggio Dati Fisici
            if (isset($_POST['massa_grassa'])) {
                $this->analisiModel->saveFisica($visitaId, $_POST);
            }

            header('Location: analysis.php?action=show&id=' . $visitaId);
            exit;
        }
    }

    public function show($id) {
        Auth::requireLogin();
        $visita = $this->analisiModel->getVisitaById($id);
        $anamnesiModel = new SchedaAnamnestica();
        $anamnesi = $anamnesiModel->getByVisitaId($id);
        
        $data = [
            'visita' => $visita, 
            'anamnesi' => $anamnesi,
            'title' => 'Dettaglio Analisi'
        ];
        extract($data);
        require_once __DIR__ . '/../views/analysis_view.php';
    }
}

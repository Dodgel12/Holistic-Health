<?php
/**
 * Controller visita anamnestica.
 * Gestisce compilazione e salvataggio dei dati anamnestici.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;
use App\Models\DomandaImpostazione;
use App\Models\SchedaAnalisi;
use App\Models\SchedaAnamnestica;

class AnamnesisController
{
    public function __construct()
    {
        Auth::require();
    }

    public function create($clientId)
    {
        $clientId = (int) $clientId;
        $client = (new Client())->getById($clientId);
        $latestPhysical = (new SchedaAnalisi())->getLatestFisicaByClient($clientId);
        $anamnesiModel = new SchedaAnamnestica();
        $latestAnamnesi = $anamnesiModel->getLatestByClient($clientId);
        $domande = (new DomandaImpostazione())->getAll();

        $prefill = [];
        $prefillQuestionAnswers = [];

        if (!empty($latestAnamnesi)) {
            $prefill = [
                'alimentazione' => $latestAnamnesi['stile_vita']['alimentazione'] ?? '',
                'attivita_fisica_tipo' => $latestAnamnesi['stile_vita']['attivita_fisica_tipo'] ?? '',
                'attivita_fisica_frequenza' => $latestAnamnesi['stile_vita']['attivita_fisica_frequenza'] ?? '',
                'stile_vita_descrizione' => $latestAnamnesi['stile_vita']['descrizione'] ?? '',
                'allergie' => (string) ($latestAnamnesi['personale']['allergie'] ?? '0'),
                'allergie_dettagli' => $latestAnamnesi['personale']['allergie_dettagli'] ?? '',
                'patologie' => (string) ($latestAnamnesi['personale']['patologie'] ?? '0'),
                'patologie_dettagli' => $latestAnamnesi['personale']['patologie_dettagli'] ?? '',
                'interventi_chirurgici' => $latestAnamnesi['personale']['interventi_chirurgici'] ?? '',
                'alcol' => (string) ($latestAnamnesi['personale']['alcol'] ?? '0'),
                'fumo' => (string) ($latestAnamnesi['personale']['fumo'] ?? '0'),
                'farmaci_correnti' => $latestAnamnesi['personale']['farmaci_correnti'] ?? '',
                'livello_stress' => (string) ($latestAnamnesi['psico_fisico']['livello_stress'] ?? '5'),
                'concentrazione' => (string) ($latestAnamnesi['psico_fisico']['concentrazione'] ?? '5'),
                'umore' => $latestAnamnesi['psico_fisico']['umore'] ?? '',
                'ansia' => (string) ($latestAnamnesi['psico_fisico']['ansia'] ?? '0'),
                'motivazione' => $latestAnamnesi['psico_fisico']['motivazione'] ?? '',
                'ore_sonno' => (string) ($latestAnamnesi['sonno']['ore_sonno'] ?? ''),
                'risvegli_notturni' => (string) ($latestAnamnesi['sonno']['risvegli_notturni'] ?? ''),
                'qualita_percepita' => $latestAnamnesi['sonno']['qualita_percepita'] ?? '',
                'difficolta_addormentarsi' => (string) ($latestAnamnesi['sonno']['difficolta_addormentarsi'] ?? '0'),
                'osservazioni_finali' => $latestAnamnesi['osservazioni_finali'] ?? ''
            ];

            $answerByText = [];
            foreach (($latestAnamnesi['risposte_domande'] ?? []) as $r) {
                $key = strtolower(trim((string) ($r['domanda'] ?? '')));
                if ($key !== '') {
                    $answerByText[$key] = (string) ($r['risposta'] ?? '');
                }
            }

            foreach ($domande as $d) {
                $textKey = strtolower(trim((string) ($d['testo'] ?? '')));
                if ($textKey !== '' && array_key_exists($textKey, $answerByText)) {
                    $prefillQuestionAnswers[(int) $d['domanda_id']] = $answerByText[$textKey];
                }
            }
        }

        require_once __DIR__ . '/../views/anamnesis_form.php';
    }

    public function store()
    {
        $clienteId = (int) $_POST['cliente_id'];

        // Crea la visita base nel registro visite.
        $schedaAnalisi = new SchedaAnalisi();
        $visitaId = $schedaAnalisi->createVisita(
            $clienteId,
            date('Y-m-d'),
            'Visita anamnestica',
            'anamnestica'
        );

        // Salva anamnesi e dati collegati.
        $schedaAnamnestica = new SchedaAnamnestica();
        $schedaAnamnestica->create($visitaId, $_POST);

        header('Location: clients.php?action=show&id=' . $clienteId);
        exit;
    }
}

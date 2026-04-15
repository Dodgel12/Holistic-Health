<?php
/**
 * Controller piano terapeutico.
 * Gestisce creazione, modifica e cancellazione dei piani.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\CatalogoConfig;
use App\Models\Client;
use App\Models\PianoTerapeutico;

class TerapiaController
{
    private $pianoModel;
    private $catalogoModel;

    public function __construct()
    {
        Auth::require();
        $this->pianoModel = new PianoTerapeutico();
        $this->catalogoModel = new CatalogoConfig();
    }

    public function index($clientId)
    {
        $clientId = (int) $clientId;
        $client = (new Client())->getById($clientId);
        if (!$client) {
            header('Location: clients.php');
            exit;
        }

        $plans = $this->pianoModel->getByClient($clientId);
        $activePlanId = (int) ($_GET['planId'] ?? 0);

        if ($activePlanId <= 0 && !empty($plans)) {
            $activePlanId = (int) $plans[0]['piano_id'];
        }

        $activePlan = $activePlanId > 0 ? $this->pianoModel->getById($activePlanId) : null;
        $selectedCatalog = $activePlan ? $this->pianoModel->getSelectedCatalogIds($activePlan['piano_id']) : [
            'alimenti' => [],
            'integratori' => [],
            'farmaci' => []
        ];

        $alimenti = $this->catalogoModel->getAlimenti();
        $integratori = $this->catalogoModel->getIntegratori();
        $farmaci = $this->catalogoModel->getFarmaci();

        require_once __DIR__ . '/../views/therapy_plan.php';
    }

    public function create()
    {
        $clientId = (int) ($_POST['cliente_id'] ?? 0);
        if ($clientId <= 0) {
            header('Location: clients.php');
            exit;
        }

        $payload = [
            'cliente_id' => $clientId,
            'titolo' => trim($_POST['titolo'] ?? 'Piano terapeutico'),
            'obiettivi' => trim($_POST['obiettivi'] ?? ''),
            'note' => trim($_POST['note'] ?? ''),
            'stato' => $_POST['stato'] ?? 'Attivo',
            'data_inizio' => $_POST['data_inizio'] ?? date('Y-m-d'),
            'data_fine' => $_POST['data_fine'] ?? null
        ];

        $pianoId = $this->pianoModel->create($payload);
        $this->pianoModel->syncLinks(
            $pianoId,
            $_POST['alimenti'] ?? [],
            $_POST['integratori'] ?? [],
            $_POST['farmaci'] ?? []
        );

        header('Location: therapy.php?clientId=' . $clientId . '&planId=' . $pianoId);
        exit;
    }

    public function update()
    {
        $pianoId = (int) ($_POST['piano_id'] ?? 0);
        $clientId = (int) ($_POST['cliente_id'] ?? 0);
        if ($pianoId <= 0 || $clientId <= 0) {
            header('Location: clients.php');
            exit;
        }

        $payload = [
            'titolo' => trim($_POST['titolo'] ?? 'Piano terapeutico'),
            'obiettivi' => trim($_POST['obiettivi'] ?? ''),
            'note' => trim($_POST['note'] ?? ''),
            'stato' => $_POST['stato'] ?? 'Attivo',
            'data_inizio' => $_POST['data_inizio'] ?? date('Y-m-d'),
            'data_fine' => $_POST['data_fine'] ?? null
        ];

        $this->pianoModel->update($pianoId, $payload);
        $this->pianoModel->syncLinks(
            $pianoId,
            $_POST['alimenti'] ?? [],
            $_POST['integratori'] ?? [],
            $_POST['farmaci'] ?? []
        );

        header('Location: therapy.php?clientId=' . $clientId . '&planId=' . $pianoId);
        exit;
    }

    public function delete()
    {
        $pianoId = (int) ($_POST['piano_id'] ?? 0);
        $clientId = (int) ($_POST['cliente_id'] ?? 0);
        if ($pianoId > 0) {
            $this->pianoModel->delete($pianoId);
        }

        header('Location: therapy.php?clientId=' . $clientId);
        exit;
    }
}

<?php
/**
 * Controller della visita anamnestica.
 * Gestisce la prima visita del cliente con raccolta
 * di dati anamnestici completi.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Client;
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
        $client = (new Client())->getById($clientId);
        require_once __DIR__ . '/../views/anamnesis_form.php';
    }

    public function store()
    {
        $clienteId = (int) $_POST['cliente_id'];

        // Crea la visita base
        $schedaAnalisi = new SchedaAnalisi();
        $visitaId = $schedaAnalisi->createVisita(
            $clienteId,
            date('Y-m-d'),
            'Visita anamnestica'
        );

        // Salva la scheda anamnestica e le tabelle correlate
        $schedaAnamnestica = new SchedaAnamnestica();
        $schedaAnamnestica->create($visitaId, $_POST);

        header('Location: clients.php?action=show&id=' . $clienteId);
        exit;
    }
}

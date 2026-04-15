<?php
/**
 * Controller dashboard.
 * Carica dati, statistiche e appuntamenti da mostrare in home.
 */
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;

class DashboardController
{
    public function __construct()
    {
        Auth::require();
    }

    public function index()
    {
        $db = Database::getInstance();

        // Numeri principali della dashboard.
        $totalClienti = $db->query("SELECT COUNT(*) as n FROM clienti")->fetch()['n'];

        $today = date('Y-m-d');
        $appuntamentiOggi = $db->query(
            "SELECT COUNT(*) as n FROM appuntamenti WHERE data = :data",
            ['data' => $today]
        )->fetch()['n'];

        $meseInizio = date('Y-m-01');
        $meseFine   = date('Y-m-t');
        $visiteMese = $db->query(
            "SELECT COUNT(*) as n FROM visite WHERE data_analisi BETWEEN :inizio AND :fine",
            ['inizio' => $meseInizio, 'fine' => $meseFine]
        )->fetch()['n'];

        // Giorni con appuntamenti nel mese corrente (usati nel calendario).
        $appuntamentiMese = $db->query(
            "SELECT DISTINCT data FROM appuntamenti WHERE data BETWEEN :inizio AND :fine",
            ['inizio' => $meseInizio, 'fine' => $meseFine]
        )->fetchAll(\PDO::FETCH_COLUMN);

        // Prossimi appuntamenti da mostrare in alto (max 5).
        $appuntamentiProssimi = $db->query(
            "SELECT a.*, c.nome, c.cognome 
             FROM appuntamenti a 
             JOIN clienti c ON a.cliente_id = c.cliente_id 
             WHERE a.data >= :oggi 
             ORDER BY a.data, a.ora_inizio 
             LIMIT 5",
            ['oggi' => $today]
        )->fetchAll();

        require_once __DIR__ . '/../views/dashboard.php';
    }
}

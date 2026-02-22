<?php
/**
 * Controller della dashboard.
 * Recupera i dati principali (clienti, analisi,
 * appuntamenti) e li passa alla vista dashboard.
 */
namespace App\Controllers;

use App\Core\Auth;

class DashboardController
{
    public function index()
    {
        Auth::require();
        require_once __DIR__ . '/../views/dashboard.php';
    }
}

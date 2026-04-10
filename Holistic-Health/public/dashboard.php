<?php
/**
 * Pagina della dashboard.
 * Mostra all'utente una panoramica dello stato
 * dell'applicazione e delle attività recenti.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\DashboardController;

$controller = new DashboardController();
$controller->index();

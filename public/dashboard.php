<?php
/**
 * Router dashboard.
 * Apre la panoramica con statistiche e prossime attivita'.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\DashboardController;

$controller = new DashboardController();
$controller->index();

<?php
/**
 * Pagina delle analisi.
 * Gestisce la creazione e visualizzazione
 * delle schede di analisi dei clienti.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AnalisiController;

$controller = new AnalisiController();

$action = $_GET['action'] ?? 'index';

if ($action == 'create') {
    $controller->create($_GET['clientId']);
} elseif ($action == 'store') {
    $controller->store();
} elseif ($action == 'show') {
    $controller->show($_GET['id']);
} else {
    $controller->index();
}

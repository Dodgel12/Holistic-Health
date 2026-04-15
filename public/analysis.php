<?php
/**
 * Router analisi fisiche.
 * Smista le azioni su creazione, salvataggio e dettaglio visita.
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

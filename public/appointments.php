<?php
/**
 * Pagina degli appuntamenti.
 * Gestisce la visualizzazione e la gestione
 * degli appuntamenti programmati.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AppuntamentoController;

$controller = new AppuntamentoController();

$action = $_GET['action'] ?? 'index';

if ($action == 'create') {
    $controller->store();
} elseif ($action == 'delete') {
    $controller->delete($_GET['id']);
} else {
    $controller->index();
}

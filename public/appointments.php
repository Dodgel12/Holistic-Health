<?php
/**
 * Router appuntamenti.
 * Gestisce elenco, creazione, eliminazione e cambio stato.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AppuntamentoController;

$controller = new AppuntamentoController();

$action = $_GET['action'] ?? 'index';

if ($action == 'create') {
    $controller->store();
} elseif ($action == 'delete') {
    $controller->delete($_GET['id']);
} elseif ($action == 'status') {
    $controller->updateStatus();
} else {
    $controller->index();
}

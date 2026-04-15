<?php
/**
 * Router visite.
 * Gestisce storico, dettaglio, modifica e cancellazione.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AnalisiController;

$controller = new AnalisiController();

$action   = $_GET['action'] ?? 'index';
$clientId = (int) ($_GET['clientId'] ?? 0);
$id       = (int) ($_GET['id'] ?? 0);

switch ($action) {
    case 'history':
        $controller->history($clientId);
        break;
    case 'show':
        $controller->show($id);
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        header('Location: clients.php');
        exit;
}

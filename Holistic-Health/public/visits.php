<?php
/**
 * Router principale per le visite.
 * Gestisce storico e dettaglio singola visita.
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
    default:
        header('Location: clients.php');
        exit;
}

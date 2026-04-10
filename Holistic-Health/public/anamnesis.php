<?php
/**
 * Router principale per la visita anamnestica.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AnamnesisController;

$controller = new AnamnesisController();

$action   = $_GET['action'] ?? 'index';
$clientId = (int) ($_GET['clientId'] ?? 0);

switch ($action) {
    case 'create':
        $controller->create($clientId);
        break;
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        }
        break;
    default:
        header('Location: clients.php');
        exit;
}

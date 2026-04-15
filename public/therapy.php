<?php
/**
 * Router piano terapeutico del paziente.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\TerapiaController;

$controller = new TerapiaController();
$action = $_GET['action'] ?? 'index';
$clientId = (int) ($_GET['clientId'] ?? $_POST['cliente_id'] ?? 0);

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        }
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        }
        break;
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->delete();
        }
        break;
    default:
        $controller->index($clientId);
        break;
}

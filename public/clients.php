<?php
/**
 * Router principali per i clienti.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\ClientController;

$controller = new ClientController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'new':
        require_once __DIR__ . '/../app/views/client_view.php';
        break;
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } else {
            header('Location: clients.php');
            exit;
        }
        break;
    case 'show':
        $controller->show((int) $_GET['id']);
        break;
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->delete();
        } else {
            header('Location: clients.php');
            exit;
        }
        break;
    default:
        $controller->index();
        break;
}

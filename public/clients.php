<?php
/**
 * Router clienti.
 * Smista lista, dettaglio, CRUD e azioni AI.
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
    case 'generate_ai_summary':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->generateAiSummary((int) ($_GET['id'] ?? 0));
        } else {
            header('Location: clients.php');
            exit;
        }
        break;
    case 'edit':
        $controller->edit((int) $_GET['id']);
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            header('Location: clients.php');
            exit;
        }
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

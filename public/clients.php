<?php
/**
 * Pagina dei clienti.
 * Gestisce la visualizzazione, creazione e modifica
 * dei dati dei clienti.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\ClientController;

$controller = new ClientController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'new':
        $data = ['title' => 'Nuovo Cliente'];
        require_once __DIR__ . '/../app/views/client_view.php'; 
        break;
    case 'create':
        $controller->create();
        break;
    case 'show':
        $controller->show($_GET['id']);
        break;
    default:
        $controller->index();
        break;
}

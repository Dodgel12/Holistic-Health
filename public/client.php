<?php
/**
 * Pagina del cliente.
 * Visualizza i dettagli di un cliente specifico
 * e le sue informazioni correlate.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\ClientController;

// Reindirizza alla lista clienti se non specificato un ID

$controller = new ClientController();

if (isset($_GET['id'])) {
    $controller->show($_GET['id']);
} else {
    // Reindirizza alla lista clienti
    header('Location: clients.php');
}

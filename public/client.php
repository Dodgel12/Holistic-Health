<?php
/**
 * Router dettaglio cliente.
 * Mostra la cartella del paziente selezionato.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\ClientController;

// Se non arriva un ID, torna alla lista clienti.

$controller = new ClientController();

if (isset($_GET['id'])) {
    $controller->show($_GET['id']);
} else {
    // Fallback: torna alla lista clienti.
    header('Location: clients.php');
}

<?php
/**
 * Pagina di login.
 * Gestisce l'autenticazione dell'utente
 * e l'accesso al sistema.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\ClientController;

$controller = new ClientController();
$controller->login();

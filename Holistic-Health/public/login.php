<?php
/**
 * Pagina di login.
 * Gestisce l'autenticazione dell'utente
 * e l'accesso al sistema.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AuthController;

$controller = new AuthController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->index();
}

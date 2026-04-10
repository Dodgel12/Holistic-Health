<?php
/**
 * Pagina di logout.
 * Termina la sessione dell'utente
 * e lo reindirizza alla pagina di login.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Core\Auth;

Auth::logout();
header('Location: login.php');
exit;

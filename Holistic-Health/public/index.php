<?php
/**
 * Punto di ingresso dell'applicazione.
 * Gestisce il reindirizzamento automatico
 * verso la dashboard o la pagina di login.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Core\Auth;

if (Auth::check()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;

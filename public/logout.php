<?php
/**
 * Router logout.
 * Chiude la sessione e rimanda al login.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Core\Auth;

Auth::logout();
header('Location: login.php');
exit;

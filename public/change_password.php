<?php
/**
 * Router cambio password obbligatorio al primo accesso.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AuthController;

$controller = new AuthController();
$action = $_GET['action'] ?? 'index';

if ($action === 'update') {
    $controller->updatePassword();
} else {
    $controller->showChangePassword();
}

<?php
require_once __DIR__ . '/../app/config/init.php';

use App\Controllers\AuthController;

$controller = new AuthController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->forgotPassword();
} else {
    $controller->showForgotPassword();
}

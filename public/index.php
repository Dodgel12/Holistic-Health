<?php
/**
 * Entry point dell'app.
 * Se sei loggato vai in dashboard, altrimenti in login.
 */
require_once __DIR__ . '/../app/config/init.php';

use App\Core\Auth;

if (Auth::check()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;

<?php
// app/controllers/AuthController.php
namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;

class AuthController
{
    public function index()
    {
        if (Auth::check()) {
            header('Location: dashboard.php');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Credenziali non valide.";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            header('Location: login.php');
            exit;
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

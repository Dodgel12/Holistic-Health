<?php
// Controller autenticazione: login, logout, cambio e recupero password.
namespace App\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
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
                $_SESSION['must_change_password'] = (int) ($user['must_change_password'] ?? 0) === 1;
                $_SESSION['must_setup_security_question'] = !$userModel->hasSecurityQuestion($user);

                if (Auth::mustSetupSecurityQuestion()) {
                    header('Location: security_question.php');
                    exit;
                }

                if (Auth::mustChangePassword()) {
                    header('Location: change_password.php');
                    exit;
                }

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

    public function showChangePassword()
    {
        if (!Auth::check()) {
            header('Location: login.php');
            exit;
        }

        require_once __DIR__ . '/../views/auth/change_password.php';
    }

    public function updatePassword()
    {
        if (!Auth::check()) {
            header('Location: login.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: change_password.php');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        if (!$user || !$userModel->verifyPassword($user, $currentPassword)) {
            $error = 'La password attuale non è corretta.';
            require_once __DIR__ . '/../views/auth/change_password.php';
            return;
        }

        if (strlen($newPassword) < 8) {
            $error = 'La nuova password deve contenere almeno 8 caratteri.';
            require_once __DIR__ . '/../views/auth/change_password.php';
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $error = 'La conferma password non coincide.';
            require_once __DIR__ . '/../views/auth/change_password.php';
            return;
        }

        if ($newPassword === $currentPassword) {
            $error = 'La nuova password deve essere diversa da quella attuale.';
            require_once __DIR__ . '/../views/auth/change_password.php';
            return;
        }

        $userModel->updatePassword($_SESSION['user_id'], $newPassword);
        $_SESSION['must_change_password'] = false;

        header('Location: dashboard.php');
        exit;
    }

    public function showForgotPassword()
    {
        $userModel = new User();
        $user = $userModel->getFirstUser();

        if (!$user) {
            $error = 'Nessun utente disponibile per il recupero password.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        if (!$userModel->hasSecurityQuestion($user)) {
            $error = 'Per l\'utente non e\' stata configurata una domanda di sicurezza.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        $securityQuestion = $user['security_question'];
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: forgot_password.php');
            exit;
        }

        $userModel = new User();
        $user = $userModel->getFirstUser();

        if (!$user) {
            $error = 'Nessun utente disponibile per il recupero password.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        if (!$userModel->hasSecurityQuestion($user)) {
            $error = 'Per l\'utente non e\' stata configurata una domanda di sicurezza.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        $securityQuestion = $user['security_question'];

        $securityAnswer = trim($_POST['security_answer'] ?? '');
        if ($securityAnswer === '') {
            $error = 'Inserisci la risposta alla domanda di sicurezza.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        if (!$userModel->verifySecurityAnswer($user, $securityAnswer)) {
            $error = 'Risposta sbagliata.';
            require_once __DIR__ . '/../views/auth/forgot_password.php';
            return;
        }

        $resetModel = new PasswordReset();
        $token = $resetModel->createToken((int) $user['user_id']);
        $resetLink = 'reset_password.php?token=' . urlencode($token);

        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }

    public function showSecurityQuestionSetup()
    {
        if (!Auth::check()) {
            header('Location: login.php');
            exit;
        }

        require_once __DIR__ . '/../views/auth/security_question.php';
    }

    public function saveSecurityQuestionSetup()
    {
        if (!Auth::check()) {
            header('Location: login.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: security_question.php');
            exit;
        }

        $question = trim($_POST['security_question'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');

        if ($question === '' || $answer === '') {
            $error = 'Inserisci sia la domanda sia la risposta personale.';
            require_once __DIR__ . '/../views/auth/security_question.php';
            return;
        }

        $userModel = new User();
        $ok = $userModel->setSecurityQuestion((int) $_SESSION['user_id'], $question, $answer);
        if (!$ok) {
            $error = 'Impossibile salvare la domanda di sicurezza.';
            require_once __DIR__ . '/../views/auth/security_question.php';
            return;
        }

        $_SESSION['must_setup_security_question'] = false;

        if (Auth::mustChangePassword()) {
            header('Location: change_password.php');
            exit;
        }

        header('Location: dashboard.php');
        exit;
    }

    public function showResetPassword()
    {
        $token = $_GET['token'] ?? '';
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: forgot_password.php');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (strlen($newPassword) < 8) {
            $error = 'La nuova password deve contenere almeno 8 caratteri.';
            require_once __DIR__ . '/../views/auth/reset_password.php';
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $error = 'La conferma password non coincide.';
            require_once __DIR__ . '/../views/auth/reset_password.php';
            return;
        }

        $resetModel = new PasswordReset();
        $tokenRow = $resetModel->resolveValidToken($token);

        if (!$tokenRow) {
            $error = 'Token non valido o scaduto.';
            require_once __DIR__ . '/../views/auth/reset_password.php';
            return;
        }

        $userModel = new User();
        $userModel->updatePassword((int) $tokenRow['user_id'], $newPassword);
        $resetModel->markUsed((int) $tokenRow['token_id']);

        $success = 'Password aggiornata. Ora puoi accedere con le nuove credenziali.';
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }
}

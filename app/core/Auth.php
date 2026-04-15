<?php
/**
 * Gestione autenticazione e sessione.
 * Decide anche i redirect obbligatori sulle pagine protette.
 */
namespace App\Core;

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function mustChangePassword()
    {
        return !empty($_SESSION['must_change_password']);
    }

    public static function mustSetupSecurityQuestion()
    {
        return !empty($_SESSION['must_setup_security_question']);
    }

    public static function require()
    {
        if (!self::check()) {
            header('Location: login.php');
            exit;
        }

        if (self::mustSetupSecurityQuestion()) {
            $currentPage = basename($_SERVER['PHP_SELF'] ?? '');
            $allowedPages = ['security_question.php', 'logout.php'];

            if (!in_array($currentPage, $allowedPages, true)) {
                header('Location: security_question.php');
                exit;
            }
        }

        if (self::mustChangePassword()) {
            $currentPage = basename($_SERVER['PHP_SELF'] ?? '');
            $allowedPages = ['change_password.php', 'security_question.php', 'logout.php'];

            if (!in_array($currentPage, $allowedPages, true)) {
                header('Location: change_password.php');
                exit;
            }
        }
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
    }
}

<?php
/**
 * Classe Auth.
 * Gestisce l'autenticazione dell'utente,
 * il controllo delle sessioni e la protezione
 * delle pagine riservate.
 */
namespace App\Core;

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function require()
    {
        if (!self::check()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
    }
}

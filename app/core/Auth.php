<?php
/**
 * Classe Auth.
 * Gestisce l'autenticazione dell'utente,
 * il controllo delle sessioni e la protezione
 * delle pagine riservate.
 */
namespace App\Core;

class Auth {
    public static function login($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['username'];
        // Ruolo non più gestito, utente unico
        // $_SESSION['user_role'] = $user['ruolo'];
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!self::check()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'role' => 'Naturopata' // Ruolo fisso
        ];
    }
    
    public static function requireLogin() {
        if (!self::check()) {
            header('Location: login.php');
            exit;
        }
    }
}

<?php
/**
 * Classe Database.
 * Gestisce la connessione al database MySQL
 * tramite PDO e fornisce un'interfaccia sicura
 * per l'esecuzione delle query.
 */
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = [
            'host' => 'localhost',
            'dbname' => 'gestionale_studio',
            'user' => 'root',
            'password' => ''
        ];

        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connessione al database fallita: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Evita clonazione
    private function __clone() {}

    // Evita deserializzazione
    public function __wakeup() {
        throw new \Exception("Impossibile deserializzare un singleton.");
    }
}

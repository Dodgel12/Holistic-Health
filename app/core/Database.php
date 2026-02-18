<?php
/**
 * Classe Database.
 * Gestisce la connessione al database MySQL
 * tramite PDO e fornisce un'interfaccia sicura
 * per l'esecuzione delle query.
 * Esegue automaticamente schema.sql ad ogni avvio
 * per garantire che le tabelle esistano.
 */
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host     = 'localhost';
        $dbname   = 'gestionale_studio';
        $user     = 'root';
        $password = 'root';

        try {
            // Prima connessione senza dbname per poter creare il database se non esiste
            $pdoInit = new PDO(
                "mysql:host={$host};charset=utf8mb4",
                $user,
                $password
            );
            $pdoInit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Esegue schema.sql ad ogni avvio (usa CREATE TABLE IF NOT EXISTS)
            $schemaPath = __DIR__ . '/../../sql/schema.sql';
            if (file_exists($schemaPath)) {
                $sql = file_get_contents($schemaPath);
                // Esegui ogni statement separatamente
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
                    if ($statement !== '') {
                        $pdoInit->exec($statement);
                    }
                }
            }


            // Connessione definitiva al database
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $password);
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

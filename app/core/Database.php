<?php
/**
 * Gestisce la connessione MySQL con PDO.
 * Al primo avvio controlla anche lo schema del database.
 */
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../app/config/database.php';
        $host = $config['host'];
        $dbname = $config['dbname'];
        $user = $config['username'];
        $password = $config['password'];

        try {
            // Prima connessione senza dbname: cosi' possiamo creare DB/tabelle se mancano.
            $pdoInit = new PDO(
                "mysql:host={$host};charset=utf8mb4",
                $user,
                $password
                );
            $pdoInit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Esegue schema.sql ad avvio: crea solo quello che non c'e'.
            $schemaPath = __DIR__ . '/../../sql/schema.sql';
            if (file_exists($schemaPath)) {
                $sql = file_get_contents($schemaPath);
                // Esegue ogni statement SQL separato.
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
                    if ($statement !== '') {
                        $pdoInit->exec($statement);
                    }
                }
            }


            // Connessione finale al database applicativo.
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        }
        catch (PDOException $e) {
            die("Connessione al database fallita: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function hasColumn($tableName, $columnName)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS cnt
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name
               AND COLUMN_NAME = :column_name"
        );
        $stmt->execute([
            'table_name' => $tableName,
            'column_name' => $columnName
        ]);

        $row = $stmt->fetch();
        return (int) ($row['cnt'] ?? 0) > 0;
    }

    public function hasTable($tableName)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS cnt
             FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name"
        );
        $stmt->execute(['table_name' => $tableName]);
        $row = $stmt->fetch();
        return (int) ($row['cnt'] ?? 0) > 0;
    }

    // Blocca la clonazione del singleton.
    private function __clone()
    {
    }

    // Blocca anche la deserializzazione del singleton.
    public function __wakeup()
    {
        throw new \Exception("Impossibile deserializzare un singleton.");
    }
}

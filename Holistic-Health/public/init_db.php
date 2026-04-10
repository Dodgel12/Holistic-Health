<?php
// Prima bisogna configurare i parametri per il collegamento al DB
$config = require __DIR__ . '/../app/config/database.php';

try {
    // 1. Connessione al server MySQL
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.\n";

    // 2. Legge lo schema SQL
    $schemaFile = __DIR__ . '/../sql/schema.sql';
    if (!file_exists($schemaFile)) {
        die("Schema file not found at $schemaFile");
    }
    $sql = file_get_contents($schemaFile);

    // 3. Esegue il file schema.sql
    $pdo->exec($sql);
    echo "Database structure imported successfully.\n";

    // 4. Create a default user for testing
    // Re-connect to the specific database now that it should exist
    $dsn_db = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo_db = new PDO($dsn_db, $config['username'], $config['password']);
    $pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = 'admin';
    $password = 'password123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user exists
    $stmt = $pdo_db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() == 0) {
        $insert = $pdo_db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $insert->execute([$username, $hashed_password]);
        echo "Default user created: User: $username, Pass: $password\n";
    }
    else {
        echo "Default user already exists.\n";
    }

    echo "Initialization complete.";

}
catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

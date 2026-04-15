<?php
// Carica i parametri di connessione al database.
$config = require __DIR__ . '/../app/config/database.php';

try {
    // 1) Si collega al server MySQL.
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connessione al server MySQL riuscita.\n";

    // 2) Legge lo schema SQL.
    $schemaFile = __DIR__ . '/../sql/schema.sql';
    if (!file_exists($schemaFile)) {
        die("File schema non trovato in: $schemaFile");
    }
    $sql = file_get_contents($schemaFile);

    // 3) Esegue schema.sql.
    $pdo->exec($sql);
    echo "Struttura database importata con successo.\n";

    // 4) Crea l'utente admin iniziale se serve.
    // Si ricollega al DB specifico ora che esiste.
    $dsn_db = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo_db = new PDO($dsn_db, $config['username'], $config['password']);
    $pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = 'admin';
    $password = 'password123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Se l'utente non esiste lo crea.
    // Se esiste ma hash mancante, sistema la password in modo sicuro.
    $stmt = $pdo_db->prepare("SELECT user_id, password_hash FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingUser) {
        $insert = $pdo_db->prepare("INSERT INTO users (username, password_hash, must_change_password) VALUES (?, ?, ?)");
        $insert->execute([$username, $hashed_password, 1]);
        echo "Utente predefinito creato: User: $username, Pass: $password\n";
    } else {
        $existingHash = trim((string) ($existingUser['password_hash'] ?? ''));
        if ($existingHash === '') {
            $update = $pdo_db->prepare("UPDATE users SET password_hash = ?, must_change_password = ? WHERE user_id = ?");
            $update->execute([$hashed_password, 1, (int) $existingUser['user_id']]);
            echo "Utente predefinito aggiornato (password hash mancante).\n";
        } else {
            echo "Utente predefinito già presente.\n";
        }
    }

    echo "Inizializzazione completata.";

}
catch (PDOException $e) {
    die("Errore DB: " . $e->getMessage());
}

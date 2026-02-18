<?php
// app/config/init.php

// Report degli errori
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Avvio sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Caricamento automatico classi (Autoloader)
spl_autoload_register(function ($class) {
    // Mappatura prefisso namespace
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../';

    // La classe usa il prefisso del namespace?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Ottieni il nome relativo della classe
    $relative_class = substr($class, $len);

    // Sostituisci il prefisso del namespace con la directory base, sostituisci i separatori
    // di namespace con i separatori di directory, aggiungi .php
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Correzione per differenza maiuscole/minuscole (es. Core/Database.php vs core/Database.php)
    // Le cartelle sono minuscole nel file system (app/core) ma i Namespace sono PascalCase (App\Core).
    // map App\Core -> app/core
    // map App\Models -> app/models
    // map App\Controllers -> app/controllers
    
    $file = str_replace('App/Core', 'app/core', $file);
    $file = str_replace('App/Models', 'app/models', $file);
    $file = str_replace('App/Controllers', 'app/controllers', $file);
    
    // Fallback per sistemi case-sensitive se la mappatura sopra non è sufficiente
    // But for now, let's try to match the folder names 'core', 'models', 'controllers'
    
    // Alternativa più semplice:
    // $file = $base_dir . strtolower(str_replace('\\', '/', $relative_class)) . '.php'; 
    // MA i nomi delle Classi sono PascalCase (Client.php), le cartelle sono minuscole (models/Client.php).
    // Quindi SOLO la parte del namespace deve essere convertita in minuscolo.
    // App\Models\Client -> app/models/Client.php
    
    $parts = explode('\\', $relative_class);
    // L'ultima parte è il nome della classe (mantiene maiuscole/minuscole)
    $className = array_pop($parts);
    // Il resto sono cartelle (minuscolo)
    $path = strtolower(implode('/', $parts));
    
    $file = $base_dir . $path . '/' . $className . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

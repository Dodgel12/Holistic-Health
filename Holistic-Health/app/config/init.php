<?php
session_start();

define('BASE_PATH', dirname(dirname(__DIR__)));

spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    if ($parts[0] !== 'App') return;

    array_shift($parts); // Remove 'App'
    $subdir = strtolower(array_shift($parts)); // core, models, controllers
    $classFile = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

    $file = BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $classFile;

    if (file_exists($file)) {
        require $file;
    }
});

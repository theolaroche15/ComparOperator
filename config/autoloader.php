<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function loadClass(string $classname): void
{
    $paths = [
        __DIR__ . '/../classes/admin' . $classname . '.php',
        __DIR__ . '/../classes/data' . $classname . '.php',
        __DIR__ . '/../classes/manager/' . $classname . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    throw new Exception("Class not found: $classname");
}

spl_autoload_register('loadClass');

session_start();

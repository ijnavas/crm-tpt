<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    // Solo clases del namespace App\
    if (!str_starts_with($class, 'App\\')) {
        return;
    }

    // Convertir App\Controllers\FooController → app/Controllers/FooController.php
    $relative = substr($class, 4); // quitar 'App\'
    $relative = str_replace('\\', '/', $relative);
    $file = BASE_PATH . '/app/' . $relative . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
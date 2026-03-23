<?php
declare(strict_types=1);

use App\Core\Session;

// Autoloader manual — carga cualquier clase App\ sin depender de composer dump-autoload
spl_autoload_register(function (string $class): void {
    if (!str_starts_with($class, 'App\\')) return;
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (file_exists($file)) require_once $file;
});

date_default_timezone_set(env('APP_TIMEZONE', 'Europe/Madrid'));

if (!is_dir(storage_path('sessions'))) {
    mkdir(storage_path('sessions'), 0775, true);
}

if (!is_dir(storage_path('logs'))) {
    mkdir(storage_path('logs'), 0775, true);
}

Session::start();
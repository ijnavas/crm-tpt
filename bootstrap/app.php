<?php
declare(strict_types=1);

use App\Core\Session;

date_default_timezone_set(env('APP_TIMEZONE', 'Europe/Madrid'));

if (!is_dir(storage_path('sessions'))) {
    mkdir(storage_path('sessions'), 0775, true);
}

if (!is_dir(storage_path('logs'))) {
    mkdir(storage_path('logs'), 0775, true);
}

Session::start();

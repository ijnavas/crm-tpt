<?php
declare(strict_types=1);


use App\Core\App;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/bootstrap/helpers.php';
require BASE_PATH . '/bootstrap/app.php';

$app = new App();
$app->run();

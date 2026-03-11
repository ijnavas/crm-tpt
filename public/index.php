<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\Core\App;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/bootstrap/helpers.php';
require BASE_PATH . '/bootstrap/app.php';

$app = new App();
$app->run();

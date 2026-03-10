<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/bootstrap/helpers.php';
require BASE_PATH . '/bootstrap/app.php';

use App\Core\Session;

$count = (int) Session::get('test_count', 0);
$count++;
Session::put('test_count', $count);

echo "<pre>";
echo "Sesión OK\n";
echo "Contador: " . $count . "\n";
echo "Session save path: " . session_save_path() . "\n";
echo "Session id: " . session_id() . "\n";
echo "</pre>";
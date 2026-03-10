<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/bootstrap/helpers.php';
require BASE_PATH . '/bootstrap/app.php';

use App\Core\Database;

try {
    $db = Database::connection();
    echo "<pre>Conexión OK\n";

    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll();

    print_r($tables);
    echo "</pre>";
} catch (\Throwable $e) {
    echo "<pre>ERROR:\n" . $e->getMessage() . "</pre>";
}
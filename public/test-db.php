<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/bootstrap/helpers.php';
require BASE_PATH . '/bootstrap/app.php';

use App\Core\Database;

$email = 'nacho@manadi.es';
$password = 'Admin123!';

try {
    $db = Database::connection();

    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND status = 'activo' LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    echo "<pre>";
    echo "Usuario encontrado: ";
    var_dump((bool)$user);

    if ($user) {
        echo "Password verify: ";
        var_dump(password_verify($password, $user['password_hash']));
        echo "\nHash:\n" . $user['password_hash'] . "\n";
    }
    echo "</pre>";
} catch (\Throwable $e) {
    echo "<pre>ERROR:\n" . $e->getMessage() . "</pre>";
}
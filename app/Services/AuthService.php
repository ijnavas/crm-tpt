<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Core\Database;

final class AuthService
{
    public function attempt(string $email, string $password): bool
    {
        $db = Database::connection();

        $stmt = $db->prepare('
            SELECT *
            FROM users
            WHERE email = :email
              AND status = :status
            LIMIT 1
        ');

        $stmt->execute([
            'email' => $email,
            'status' => 'activo',
        ]);

        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        Auth::login($user);
        return true;
    }
}

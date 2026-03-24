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
            SELECT u.*, r.name AS role_name, u.dashboard
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.email = :email
              AND u.status = :status
            LIMIT 1
        ');

        $stmt->execute([
            'email'  => $email,
            'status' => 'activo',
        ]);

        $user = $stmt->fetch();

        if (!$user) return false;
        if (!password_verify($password, $user['password_hash'])) return false;

        Auth::login($user);

        // Registrar acceso
        try {
            $logRepo = new \App\Repositories\AccessLogRepository();
            $logRepo->log([
                'user_id'    => $user['id'],
                'user_name'  => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
                'action'     => 'login',
                'path'       => '/login',
                'ip'         => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]);
        } catch (\Throwable $e) {}

        return true;
    }
}
<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findById(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id'         => $id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
        ]);
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id, 'hash' => $hash]);
    }

    public function updateAvatar(int $id, string $path): void
    {
        $stmt = $this->db->prepare("UPDATE users SET avatar = :avatar, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id, 'avatar' => $path]);
    }

    public function getSetting(string $key): ?string
    {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE key_name = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : null;
    }

    public function setSetting(string $key, string $value): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO settings (key_name, value) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE value = :value2, updated_at = NOW()
        ");
        $stmt->execute(['key' => $key, 'value' => $value, 'value2' => $value]);
    }

    public function isAdmin(int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT r.name FROM users u JOIN roles r ON r.id = u.role_id WHERE u.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        return $row && $row['name'] === 'admin';
    }
}

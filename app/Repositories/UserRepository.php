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

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT u.*, r.name AS role_name
            FROM users u
            JOIN roles r ON r.id = u.role_id
            ORDER BY u.first_name ASC
        ");
        return $stmt->fetchAll();
    }

    public function getRoles(): array
    {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, first_name, last_name, email, password_hash, status, dashboard)
            VALUES (:role_id, :first_name, :last_name, :email, :password_hash, :status, :dashboard)
        ");
        $stmt->execute([
            'role_id'       => $data['role_id'],
            'first_name'    => ucfirst(strtolower(trim($data['first_name']))),
            'last_name'     => ucfirst(strtolower(trim($data['last_name'] ?? ''))),
            'email'         => strtolower(trim($data['email'])),
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'status'        => $data['status'] ?? 'activo',
            'dashboard'     => $data['dashboard'] ?? 'default',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                role_id    = :role_id,
                status     = :status,
                dashboard  = :dashboard,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id'         => $id,
            'first_name' => ucfirst(strtolower(trim($data['first_name']))),
            'last_name'  => ucfirst(strtolower(trim($data['last_name'] ?? ''))),
            'email'      => strtolower(trim($data['email'])),
            'role_id'    => $data['role_id'],
            'status'     => $data['status'] ?? 'activo',
            'dashboard'  => $data['dashboard'] ?? 'default',
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

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare("
            UPDATE users SET
                status = IF(status = 'activo', 'inactivo', 'activo'),
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
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
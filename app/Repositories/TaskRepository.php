<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class TaskRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM tasks
            ORDER BY
                CASE WHEN due_date IS NULL THEN 1 ELSE 0 END,
                due_date ASC,
                id DESC
        ");

        return $stmt->fetchAll();
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM tasks
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (
                entity_type,
                entity_id,
                title,
                description,
                priority,
                status,
                due_date,
                assigned_user_id,
                created_by
            ) VALUES (
                :entity_type,
                :entity_id,
                :title,
                :description,
                :priority,
                :status,
                :due_date,
                :assigned_user_id,
                :created_by
            )
        ");

        $stmt->execute([
            'entity_type' => $data['entity_type'] ?? 'company',
            'entity_id' => (int) ($data['entity_id'] ?? 0),
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'] ?? 'pendiente',
            'due_date' => !empty($data['due_date']) ? $data['due_date'] : null,
            'assigned_user_id' => Auth::id(),
            'created_by' => Auth::id(),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function complete(int $id): void
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET status = 'completada',
                completed_at = NOW()
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);
    }
}
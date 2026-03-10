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

    public function paginate(array $filters): array
    {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY due_date ASC LIMIT 100");
        $rows = $stmt->fetchAll();

        return [
            'data' => $rows,
            'pagination' => ['total' => count($rows), 'page' => 1, 'per_page' => 100],
        ];
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (
                entity_type, entity_id, assigned_user_id, title, description, type,
                priority, status, due_date, created_by
            ) VALUES (
                :entity_type, :entity_id, :assigned_user_id, :title, :description, :type,
                :priority, :status, :due_date, :created_by
            )
        ");

        $stmt->execute([
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'],
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'] ?? 'seguimiento',
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'] ?? 'pendiente',
            'due_date' => $data['due_date'],
            'created_by' => Auth::id(),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET
                entity_type = :entity_type,
                entity_id = :entity_id,
                assigned_user_id = :assigned_user_id,
                title = :title,
                description = :description,
                type = :type,
                priority = :priority,
                status = :status,
                due_date = :due_date
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'],
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'] ?? 'seguimiento',
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'] ?? 'pendiente',
            'due_date' => $data['due_date'],
        ]);
    }

    public function complete(int $id, ?string $result = null): void
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET status = 'completada',
                completed_at = NOW(),
                result = :result
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'result' => $result,
        ]);
    }
}
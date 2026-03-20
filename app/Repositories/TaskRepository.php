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

    public function getAll(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'pendiente') {
                $where[] = "status IN ('pendiente','en_curso')";
            } elseif ($filters['status'] === 'vencida') {
                $where[] = "status <> 'completada' AND due_date IS NOT NULL AND due_date < NOW()";
            } else {
                $where[] = 'status = :status';
                $params['status'] = $filters['status'];
            }
        }

        if (!empty($filters['period']) && $filters['period'] === 'week') {
            $where[] = 'YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->db->prepare("
            SELECT * FROM tasks $sqlWhere
            ORDER BY
                CASE WHEN due_date IS NULL THEN 1 ELSE 0 END,
                due_date ASC,
                id DESC
        ");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
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
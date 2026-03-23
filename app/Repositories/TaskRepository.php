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
        $where  = [];
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

        if (!empty($filters['type'])) {
            $where[] = 'type = :type';
            $params['type'] = $filters['type'];
        }

        if (!empty($filters['priority'])) {
            $where[] = 'priority = :priority';
            $params['priority'] = $filters['priority'];
        }

        if (!empty($filters['entity_type'])) {
            $where[] = 'entity_type = :entity_type';
            $params['entity_type'] = $filters['entity_type'];
        }

        if (!empty($filters['period']) && $filters['period'] === 'week') {
            $where[] = 'YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare("
            SELECT t.*,
                   CASE t.entity_type
                       WHEN 'company' THEN (SELECT name FROM companies WHERE id = t.entity_id)
                       WHEN 'lead'    THEN (SELECT full_name FROM leads WHERE id = t.entity_id)
                       WHEN 'contact' THEN (SELECT full_name FROM company_contacts WHERE id = t.entity_id)
                   END AS entity_name
            FROM tasks t
            $sqlWhere
            ORDER BY
                CASE WHEN t.due_date IS NULL THEN 1 ELSE 0 END,
                t.due_date ASC,
                t.id DESC
        ");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getGroupedByEntity(): array
    {
        $stmt = $this->db->query("
            SELECT t.*,
                   CASE t.entity_type
                       WHEN 'company' THEN (SELECT name FROM companies WHERE id = t.entity_id)
                       WHEN 'lead'    THEN (SELECT full_name FROM leads WHERE id = t.entity_id)
                       WHEN 'contact' THEN (SELECT full_name FROM company_contacts WHERE id = t.entity_id)
                   END AS entity_name
            FROM tasks t
            WHERE t.status <> 'completada'
            ORDER BY t.entity_type, t.entity_id, t.due_date ASC
        ");
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['entity_type'] . '_' . $row['entity_id'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'entity_type' => $row['entity_type'],
                    'entity_id'   => $row['entity_id'],
                    'entity_name' => $row['entity_name'] ?? '—',
                    'tasks'       => [],
                ];
            }
            $grouped[$key]['tasks'][] = $row;
        }
        return array_values($grouped);
    }

    public function getUpcoming(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   CASE t.entity_type
                       WHEN 'company' THEN (SELECT name FROM companies WHERE id = t.entity_id)
                       WHEN 'lead'    THEN (SELECT full_name FROM leads WHERE id = t.entity_id)
                       WHEN 'contact' THEN (SELECT full_name FROM company_contacts WHERE id = t.entity_id)
                   END AS entity_name
            FROM tasks t
            WHERE t.status <> 'completada'
            ORDER BY CASE WHEN t.due_date IS NULL THEN 1 ELSE 0 END, t.due_date ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getReminders(): array
    {
        $stmt = $this->db->query("
            SELECT t.*,
                   CASE t.entity_type
                       WHEN 'company' THEN (SELECT name FROM companies WHERE id = t.entity_id)
                       WHEN 'lead'    THEN (SELECT full_name FROM leads WHERE id = t.entity_id)
                       WHEN 'contact' THEN (SELECT full_name FROM company_contacts WHERE id = t.entity_id)
                   END AS entity_name
            FROM tasks t
            WHERE t.status <> 'completada'
              AND t.reminder_at IS NOT NULL
              AND t.reminder_at <= DATE_ADD(NOW(), INTERVAL 24 HOUR)
              AND t.reminder_at >= NOW()
            ORDER BY t.reminder_at ASC
        ");
        return $stmt->fetchAll();
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   CASE t.entity_type
                       WHEN 'company' THEN (SELECT name FROM companies WHERE id = t.entity_id)
                       WHEN 'lead'    THEN (SELECT full_name FROM leads WHERE id = t.entity_id)
                       WHEN 'contact' THEN (SELECT full_name FROM company_contacts WHERE id = t.entity_id)
                   END AS entity_name
            FROM tasks t WHERE t.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (
                entity_type, entity_id, title, type,
                priority, status, start_date, due_date,
                notes, reminder_at, assigned_user_id, created_by
            ) VALUES (
                :entity_type, :entity_id, :title, :type,
                :priority, :status, :start_date, :due_date,
                :notes, :reminder_at, :assigned_user_id, :created_by
            )
        ");

        $stmt->execute([
            'entity_type'      => $data['entity_type'] ?? 'company',
            'entity_id'        => (int) ($data['entity_id'] ?? 0),
            'title'            => $data['title'] ?? '',
            'type'             => $data['type'] ?? 'llamada',
            'priority'         => $data['priority'] ?? 'media',
            'status'           => $data['status'] ?? 'pendiente',
            'start_date'       => !empty($data['start_date']) ? $data['start_date'] : null,
            'due_date'         => !empty($data['due_date']) ? $data['due_date'] : null,
            'notes'            => $data['notes'] ?? null,
            'reminder_at'      => !empty($data['reminder_at']) ? $data['reminder_at'] : null,
            'assigned_user_id' => Auth::id(),
            'created_by'       => Auth::id(),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET
                entity_type      = :entity_type,
                entity_id        = :entity_id,
                title            = :title,
                type             = :type,
                priority         = :priority,
                status           = :status,
                start_date       = :start_date,
                due_date         = :due_date,
                notes            = :notes,
                reminder_at      = :reminder_at
            WHERE id = :id
        ");

        $stmt->execute([
            'id'          => $id,
            'entity_type' => $data['entity_type'] ?? 'company',
            'entity_id'   => (int) ($data['entity_id'] ?? 0),
            'title'       => $data['title'] ?? '',
            'type'        => $data['type'] ?? 'llamada',
            'priority'    => $data['priority'] ?? 'media',
            'status'      => $data['status'] ?? 'pendiente',
            'start_date'  => !empty($data['start_date']) ? $data['start_date'] : null,
            'due_date'    => !empty($data['due_date']) ? $data['due_date'] : null,
            'notes'       => $data['notes'] ?? null,
            'reminder_at' => !empty($data['reminder_at']) ? $data['reminder_at'] : null,
        ]);
    }

    public function complete(int $id): void
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET status = 'completada', completed_at = NOW() WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class DashboardRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getKpis(): array
    {
        return [
            'new_leads' => (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'nuevo'")->fetchColumn(),
            'pending_leads' => (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status IN ('pendiente_contacto','en_seguimiento')")->fetchColumn(),
            'today_tasks' => (int) $this->db->query("SELECT COUNT(*) FROM tasks WHERE DATE(due_date) = CURDATE()")->fetchColumn(),
            'overdue_tasks' => (int) $this->db->query("SELECT COUNT(*) FROM tasks WHERE due_date < NOW() AND status <> 'completada'")->fetchColumn(),
        ];
    }

    public function getRecentLeads(int $limit = 5): array
    {
        $stmt = $this->db->prepare('
            SELECT id, company_name, full_name, status, priority, next_contact_at, created_at
            FROM leads
            ORDER BY created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTodayTasks(int $limit = 5): array
    {
        $stmt = $this->db->prepare('
            SELECT id, title, priority, due_date, status
            FROM tasks
            WHERE DATE(due_date) = CURDATE()
            ORDER BY due_date ASC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRecentActivity(int $limit = 8): array
    {
        $stmt = $this->db->prepare('
            SELECT id, entity_type, entity_id, action, description, created_at
            FROM activity_logs
            ORDER BY created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

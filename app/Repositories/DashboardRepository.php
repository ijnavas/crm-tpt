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
            'leads_today' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM leads
                WHERE DATE(created_at) = CURDATE()
            ")->fetchColumn(),

            'leads_week' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM leads
                WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
            ")->fetchColumn(),

            'active_companies' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM companies
                WHERE status IN ('prospecto', 'activa')
            ")->fetchColumn(),

            'pending_tasks' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM tasks
                WHERE status IN ('pendiente', 'en_curso')
            ")->fetchColumn(),

            'overdue_tasks' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM tasks
                WHERE status <> 'completada'
                  AND due_date IS NOT NULL
                  AND due_date < NOW()
            ")->fetchColumn(),

            'converted_leads' => (int) $this->db->query("
                SELECT COUNT(*)
                FROM leads
                WHERE status = 'convertido'
            ")->fetchColumn(),
        ];
    }

    public function getRecentLeads(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT id, company_name, full_name, status, priority, next_contact_at, created_at
            FROM leads
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getUpcomingTasks(int $limit = 8): array
    {
        $stmt = $this->db->prepare("
            SELECT id, title, entity_type, entity_id, priority, status, due_date
            FROM tasks
            WHERE status <> 'completada'
            ORDER BY
                CASE WHEN due_date IS NULL THEN 1 ELSE 0 END,
                due_date ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRecentCompanies(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, sector, status, created_at
            FROM companies
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRecentActivity(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT id, entity_type, entity_id, action, description, created_at
            FROM activity_logs
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
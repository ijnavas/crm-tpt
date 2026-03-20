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
        $leads_week = (int) $this->db->query("
            SELECT COUNT(*) FROM leads
            WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
        ")->fetchColumn();

        $leads_week_prev = (int) $this->db->query("
            SELECT COUNT(*) FROM leads
            WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) - 1
        ")->fetchColumn();

        $contacts_week = (int) $this->db->query("
            SELECT COUNT(*) FROM company_contacts
            WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
        ")->fetchColumn();

        $contacts_week_prev = (int) $this->db->query("
            SELECT COUNT(*) FROM company_contacts
            WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) - 1
        ")->fetchColumn();

        $pending_tasks = (int) $this->db->query("
            SELECT COUNT(*) FROM tasks WHERE status IN ('pendiente', 'en_curso')
        ")->fetchColumn();

        $completed_tasks = (int) $this->db->query("
            SELECT COUNT(*) FROM tasks WHERE status = 'completada'
        ")->fetchColumn();

        return [
            'leads_today'      => (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()")->fetchColumn(),
            'leads_week'       => $leads_week,
            'leads_week_prev'  => $leads_week_prev,
            'contacts_week'    => $contacts_week,
            'contacts_week_prev' => $contacts_week_prev,
            'active_companies' => (int) $this->db->query("SELECT COUNT(*) FROM companies WHERE status IN ('prospecto', 'activa')")->fetchColumn(),
            'pending_tasks'    => $pending_tasks,
            'completed_tasks'  => $completed_tasks,
            'overdue_tasks'    => (int) $this->db->query("SELECT COUNT(*) FROM tasks WHERE status <> 'completada' AND due_date IS NOT NULL AND due_date < NOW()")->fetchColumn(),
            'converted_leads'  => (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'convertido'")->fetchColumn(),
        ];
    }

    public function getLeadsPerDay(): array
    {
        $stmt = $this->db->query("
            SELECT
                DAYOFWEEK(created_at) as dow,
                DAYNAME(created_at) as day_name,
                DATE(created_at) as day_date,
                COUNT(*) as total
            FROM leads
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(created_at), DAYOFWEEK(created_at), DAYNAME(created_at)
            ORDER BY day_date ASC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Rellenar los 7 días aunque no haya datos
        $days = [];
        $labels_es = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dow  = (int) date('w', strtotime($date));
            $days[$date] = ['label' => $labels_es[$dow], 'total' => 0];
        }
        foreach ($rows as $row) {
            if (isset($days[$row['day_date']])) {
                $days[$row['day_date']]['total'] = (int) $row['total'];
            }
        }

        return array_values($days);
    }

    public function getRecentLeads(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT id, company_name, full_name, status, priority, next_contact_at, created_at
            FROM leads ORDER BY created_at DESC LIMIT :limit
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
            ORDER BY CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC
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
            FROM companies ORDER BY created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRecentActivity(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT id, entity_type, entity_id, action, description, created_at
            FROM activity_logs ORDER BY created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
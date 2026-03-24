<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class StatsRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function getConversionBySource(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT source,
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                    SUM(CASE WHEN status = 'no_interesado' THEN 1 ELSE 0 END) AS lost,
                    ROUND(SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) AS rate
                FROM leads
                WHERE source IS NOT NULL AND source != ''
                GROUP BY source ORDER BY total DESC
            ");
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public function getConversionByService(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT service_interest,
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                    ROUND(SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) AS rate
                FROM leads
                WHERE service_interest IS NOT NULL AND service_interest != ''
                GROUP BY service_interest ORDER BY total DESC
            ");
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public function getStatusDistribution(): array
    {
        try {
            $stmt = $this->db->query("SELECT status, COUNT(*) AS total FROM leads GROUP BY status ORDER BY total DESC");
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public function getConversionByUser(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                    COUNT(l.id) AS total,
                    SUM(CASE WHEN l.status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                    ROUND(SUM(CASE WHEN l.status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(l.id), 1) AS rate
                FROM leads l
                LEFT JOIN users u ON u.id = l.assigned_user_id
                WHERE u.id IS NOT NULL
                GROUP BY l.assigned_user_id, user_name ORDER BY total DESC
            ");
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public function getLeadsPerMonth(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') AS month,
                    DATE_FORMAT(created_at, '%b %Y') AS month_label,
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) AS converted
                FROM leads
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC
            ");
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public function getAvgTimeToConvert(): string
    {
        try {
            $stmt = $this->db->query("
                SELECT ROUND(AVG(DATEDIFF(h.created_at, l.created_at)), 0) AS avg_days
                FROM leads l
                JOIN lead_status_history h ON h.lead_id = l.id AND h.new_status = 'convertido'
                WHERE l.status = 'convertido'
            ");
            $row = $stmt->fetch();
            return !empty($row['avg_days']) ? $row['avg_days'] . ' días' : '—';
        } catch (\Throwable $e) { return '—'; }
    }

    public function getGlobalKpis(): array
    {
        try {
            $total     = (int) $this->db->query("SELECT COUNT(*) FROM leads")->fetchColumn();
            $converted = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'convertido'")->fetchColumn();
            $active    = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status NOT IN ('convertido','no_interesado')")->fetchColumn();
            $lost      = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'no_interesado'")->fetchColumn();
            return [
                'total'     => $total,
                'converted' => $converted,
                'active'    => $active,
                'lost'      => $lost,
                'rate'      => $total > 0 ? round($converted * 100 / $total, 1) : 0,
                'avg_time'  => $this->getAvgTimeToConvert(),
            ];
        } catch (\Throwable $e) {
            return ['total'=>0,'converted'=>0,'active'=>0,'lost'=>0,'rate'=>0,'avg_time'=>'—'];
        }
    }
}
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
        $stmt = $this->db->query("
            SELECT
                source,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                SUM(CASE WHEN status = 'no_interesado' THEN 1 ELSE 0 END) AS lost,
                ROUND(SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) AS rate
            FROM leads
            WHERE source IS NOT NULL AND source != ''
            GROUP BY source
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    public function getConversionByService(): array
    {
        $stmt = $this->db->query("
            SELECT
                service_interest,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                ROUND(SUM(CASE WHEN status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) AS rate
            FROM leads
            WHERE service_interest IS NOT NULL AND service_interest != ''
            GROUP BY service_interest
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    public function getStatusDistribution(): array
    {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) AS total
            FROM leads
            GROUP BY status
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    public function getConversionByUser(): array
    {
        $stmt = $this->db->query("
            SELECT
                CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                COUNT(l.id) AS total,
                SUM(CASE WHEN l.status = 'convertido' THEN 1 ELSE 0 END) AS converted,
                ROUND(SUM(CASE WHEN l.status = 'convertido' THEN 1 ELSE 0 END) * 100.0 / COUNT(l.id), 1) AS rate
            FROM leads l
            LEFT JOIN users u ON u.id = l.assigned_user_id
            WHERE u.id IS NOT NULL
            GROUP BY l.assigned_user_id, user_name
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    public function getLeadsPerMonth(): array
    {
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
    }

    public function getAvgTimeToConvert(): string
    {
        $stmt = $this->db->query("
            SELECT ROUND(AVG(DATEDIFF(
                (SELECT MIN(created_at) FROM lead_status_history WHERE lead_id = l.id AND to_status = 'convertido'),
                l.created_at
            )), 0) AS avg_days
            FROM leads l
            WHERE l.status = 'convertido'
        ");
        $row = $stmt->fetch();
        return $row['avg_days'] ? $row['avg_days'] . ' días' : '—';
    }

    public function getGlobalKpis(): array
    {
        $total     = (int) $this->db->query("SELECT COUNT(*) FROM leads")->fetchColumn();
        $converted = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'convertido'")->fetchColumn();
        $active    = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status NOT IN ('convertido','no_interesado')")->fetchColumn();
        $lost      = (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'no_interesado'")->fetchColumn();

        return [
            'total'      => $total,
            'converted'  => $converted,
            'active'     => $active,
            'lost'       => $lost,
            'rate'       => $total > 0 ? round($converted * 100 / $total, 1) : 0,
            'avg_time'   => $this->getAvgTimeToConvert(),
        ];
    }
}
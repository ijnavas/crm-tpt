<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class AccessLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function log(array $data): void
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO access_logs (user_id, user_name, action, path, ip, user_agent)
                VALUES (:user_id, :user_name, :action, :path, :ip, :user_agent)
            ");
            $stmt->execute([
                'user_id'    => $data['user_id'] ?? null,
                'user_name'  => $data['user_name'] ?? null,
                'action'     => $data['action'] ?? 'visit',
                'path'       => $data['path'] ?? null,
                'ip'         => $data['ip'] ?? null,
                'user_agent' => isset($data['user_agent']) ? substr($data['user_agent'], 0, 255) : null,
            ]);
        } catch (\Throwable $e) {
            // Silencioso — no interrumpir la app si falla el log
        }
    }

    public function getAll(array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['user_id'])) {
            $where[] = 'al.user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }

        if (!empty($filters['action'])) {
            $where[] = 'al.action = :action';
            $params['action'] = $filters['action'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(al.created_at) >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(al.created_at) <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare("
            SELECT al.*
            FROM access_logs al
            $sqlWhere
            ORDER BY al.created_at DESC
            LIMIT 500
        ");
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUsers(): array
    {
        $stmt = $this->db->query("
            SELECT DISTINCT user_id, user_name FROM access_logs
            WHERE user_id IS NOT NULL ORDER BY user_name ASC
        ");
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        return [
            'logins_today'  => (int) $this->db->query("SELECT COUNT(*) FROM access_logs WHERE action='login' AND DATE(created_at)=CURDATE()")->fetchColumn(),
            'active_users'  => (int) $this->db->query("SELECT COUNT(DISTINCT user_id) FROM access_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)")->fetchColumn(),
            'total_today'   => (int) $this->db->query("SELECT COUNT(*) FROM access_logs WHERE DATE(created_at)=CURDATE()")->fetchColumn(),
        ];
    }
}
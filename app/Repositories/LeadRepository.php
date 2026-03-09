<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class LeadRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function paginate(array $filters): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(company_name LIKE :q OR full_name LIKE :q OR email LIKE :q OR phone LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $where[] = 'priority = :priority';
            $params['priority'] = $filters['priority'];
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare("SELECT * FROM leads $sqlWhere ORDER BY created_at DESC LIMIT 50");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return [
            'data' => $rows,
            'pagination' => [
                'total' => count($rows),
                'page' => 1,
                'per_page' => 50,
            ],
        ];
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM leads WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO leads (
                full_name, first_name, last_name, company_name, job_title, phone, mobile, email,
                website, city, province, source, channel, campaign, service_interest,
                status, priority, temperature, next_action, notes_internal,
                assigned_user_id, created_by
            ) VALUES (
                :full_name, :first_name, :last_name, :company_name, :job_title, :phone, :mobile, :email,
                :website, :city, :province, :source, :channel, :campaign, :service_interest,
                :status, :priority, :temperature, :next_action, :notes_internal,
                :assigned_user_id, :created_by
            )
        ');

        $stmt->execute([
            'full_name' => trim((string)($data['full_name'] ?? (($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')))),
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'phone' => $data['phone'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'source' => $data['source'] ?? 'web',
            'channel' => $data['channel'] ?? null,
            'campaign' => $data['campaign'] ?? null,
            'service_interest' => $data['service_interest'] ?? null,
            'status' => $data['status'] ?? 'nuevo',
            'priority' => $data['priority'] ?? 'media',
            'temperature' => $data['temperature'] ?? null,
            'next_action' => $data['next_action'] ?? null,
            'notes_internal' => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
            'created_by' => Auth::id(),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('
            UPDATE leads SET
                full_name = :full_name,
                first_name = :first_name,
                last_name = :last_name,
                company_name = :company_name,
                job_title = :job_title,
                phone = :phone,
                mobile = :mobile,
                email = :email,
                website = :website,
                city = :city,
                province = :province,
                source = :source,
                channel = :channel,
                campaign = :campaign,
                service_interest = :service_interest,
                status = :status,
                priority = :priority,
                temperature = :temperature,
                next_action = :next_action,
                notes_internal = :notes_internal,
                assigned_user_id = :assigned_user_id
            WHERE id = :id
        ');

        $stmt->execute([
            'id' => $id,
            'full_name' => trim((string)($data['full_name'] ?? (($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')))),
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'phone' => $data['phone'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'source' => $data['source'] ?? 'web',
            'channel' => $data['channel'] ?? null,
            'campaign' => $data['campaign'] ?? null,
            'service_interest' => $data['service_interest'] ?? null,
            'status' => $data['status'] ?? 'nuevo',
            'priority' => $data['priority'] ?? 'media',
            'temperature' => $data['temperature'] ?? null,
            'next_action' => $data['next_action'] ?? null,
            'notes_internal' => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE leads SET status = :status WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }

    public function insertNote(int $leadId, string $note): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO lead_notes (lead_id, user_id, note)
            VALUES (:lead_id, :user_id, :note)
        ');
        $stmt->execute([
            'lead_id' => $leadId,
            'user_id' => Auth::id(),
            'note' => $note,
        ]);
    }

    public function getNotes(int $leadId): array
    {
        $stmt = $this->db->prepare('
            SELECT ln.*, u.first_name, u.last_name
            FROM lead_notes ln
            INNER JOIN users u ON u.id = ln.user_id
            WHERE ln.lead_id = :lead_id
            ORDER BY ln.created_at DESC
        ');
        $stmt->execute(['lead_id' => $leadId]);
        return $stmt->fetchAll();
    }

    public function getTimeline(int $leadId): array
    {
        $stmt = $this->db->prepare('
            SELECT *
            FROM activity_logs
            WHERE entity_type = :entity_type
              AND entity_id = :entity_id
            ORDER BY created_at DESC
        ');
        $stmt->execute([
            'entity_type' => 'lead',
            'entity_id' => $leadId,
        ]);
        return $stmt->fetchAll();
    }

    public function logActivity(string $entityType, int $entityId, string $action, string $description): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO activity_logs (entity_type, entity_id, user_id, action, description)
            VALUES (:entity_type, :entity_id, :user_id, :action, :description)
        ');
        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}

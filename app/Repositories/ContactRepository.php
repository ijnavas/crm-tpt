<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class ContactRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function paginate(array $filters): array
    {
        $stmt = $this->db->query("
            SELECT cc.*, c.name AS company_name
            FROM company_contacts cc
            INNER JOIN companies c ON c.id = cc.company_id
            ORDER BY cc.created_at DESC
            LIMIT 50
        ");

        $rows = $stmt->fetchAll();

        return [
            'data' => $rows,
            'pagination' => ['total' => count($rows), 'page' => 1, 'per_page' => 50],
        ];
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT cc.*, c.name AS company_name
            FROM company_contacts cc
            LEFT JOIN companies c ON c.id = cc.company_id
            WHERE cc.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $stmt = $this->db->prepare("
            INSERT INTO company_contacts (
                company_id, first_name, last_name, full_name, job_title, department,
                phone, mobile, email, preferred_channel, contact_schedule,
                decision_level, is_primary, status, notes_internal,
                assigned_user_id, created_by
            ) VALUES (
                :company_id, :first_name, :last_name, :full_name, :job_title, :department,
                :phone, :mobile, :email, :preferred_channel, :contact_schedule,
                :decision_level, :is_primary, :status, :notes_internal,
                :assigned_user_id, :created_by
            )
        ");

        $stmt->execute([
            'company_id'       => $data['company_id'],
            'first_name'       => $data['first_name'] ?? '',
            'last_name'        => $data['last_name'] ?? null,
            'full_name'        => $fullName,
            'job_title'        => $data['job_title'] ?? null,
            'department'       => $data['department'] ?? null,
            'phone'            => $data['phone'] ?? null,
            'mobile'           => $data['mobile'] ?? null,
            'email'            => $data['email'] ?? null,
            'preferred_channel'=> $data['preferred_channel'] ?? null,
            'contact_schedule' => $data['contact_schedule'] ?? null,
            'decision_level'   => $data['decision_level'] ?? null,
            'is_primary'       => !empty($data['is_primary']) ? 1 : 0,
            'status'           => $data['status'] ?? 'activo',
            'notes_internal'   => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
            'created_by'       => Auth::id(),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $stmt = $this->db->prepare("
            UPDATE company_contacts SET
                company_id = :company_id,
                first_name = :first_name,
                last_name = :last_name,
                full_name = :full_name,
                job_title = :job_title,
                department = :department,
                phone = :phone,
                mobile = :mobile,
                email = :email,
                preferred_channel = :preferred_channel,
                contact_schedule = :contact_schedule,
                decision_level = :decision_level,
                is_primary = :is_primary,
                status = :status,
                notes_internal = :notes_internal,
                assigned_user_id = :assigned_user_id
            WHERE id = :id
        ");

        $stmt->execute([
            'id'               => $id,
            'company_id'       => $data['company_id'],
            'first_name'       => $data['first_name'] ?? '',
            'last_name'        => $data['last_name'] ?? null,
            'full_name'        => $fullName,
            'job_title'        => $data['job_title'] ?? null,
            'department'       => $data['department'] ?? null,
            'phone'            => $data['phone'] ?? null,
            'mobile'           => $data['mobile'] ?? null,
            'email'            => $data['email'] ?? null,
            'preferred_channel'=> $data['preferred_channel'] ?? null,
            'contact_schedule' => $data['contact_schedule'] ?? null,
            'decision_level'   => $data['decision_level'] ?? null,
            'is_primary'       => !empty($data['is_primary']) ? 1 : 0,
            'status'           => $data['status'] ?? 'activo',
            'notes_internal'   => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
        ]);
    }

    public function getCompanies(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM companies ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getTasks(int $contactId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks
            WHERE entity_type = 'contact' AND entity_id = :entity_id
            ORDER BY due_date ASC
        ");
        $stmt->execute(['entity_id' => $contactId]);
        return $stmt->fetchAll();
    }

    public function getTimeline(int $contactId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM activity_logs
            WHERE entity_type = 'contact' AND entity_id = :entity_id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['entity_id' => $contactId]);
        return $stmt->fetchAll();
    }
}
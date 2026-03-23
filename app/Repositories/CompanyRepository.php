<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class CompanyRepository
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

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'activa_prospecto') {
                $where[] = "status IN ('activa', 'prospecto')";
            } else {
                $where[] = 'status = :status';
                $params['status'] = $filters['status'];
            }
        }

        if (!empty($filters['period']) && $filters['period'] === 'week') {
            $where[] = 'YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)';
        }

        if (!empty($filters['sector'])) {
            $where[] = 'sector = :sector';
            $params['sector'] = $filters['sector'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(name LIKE :q OR email LIKE :q OR city LIKE :q OR sector LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->db->prepare("SELECT * FROM companies $sqlWhere ORDER BY created_at DESC LIMIT 50");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return [
            'data' => $rows,
            'pagination' => ['total' => count($rows), 'page' => 1, 'per_page' => 50],
        ];
    }

    public function getSectors(): array
    {
        $stmt = $this->db->query("
            SELECT DISTINCT sector FROM companies
            WHERE sector IS NOT NULL AND sector != ''
            ORDER BY sector ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO companies (
                name, legal_name, tax_id, company_type, sector, activity,
                phone, email, website, address, city, province, postal_code, country,
                status, source, notes_internal, assigned_user_id, created_by
            ) VALUES (
                :name, :legal_name, :tax_id, :company_type, :sector, :activity,
                :phone, :email, :website, :address, :city, :province, :postal_code, :country,
                :status, :source, :notes_internal, :assigned_user_id, :created_by
            )
        ");

        $stmt->execute([
            'name' => $data['name'] ?? '',
            'legal_name' => $data['legal_name'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'company_type' => $data['company_type'] ?? null,
            'sector' => $data['sector'] ?? null,
            'activity' => $data['activity'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'status' => $data['status'] ?? 'prospecto',
            'source' => $data['source'] ?? null,
            'notes_internal' => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
            'created_by' => Auth::id(),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE companies SET
                name = :name,
                legal_name = :legal_name,
                tax_id = :tax_id,
                company_type = :company_type,
                sector = :sector,
                activity = :activity,
                phone = :phone,
                email = :email,
                website = :website,
                address = :address,
                city = :city,
                province = :province,
                postal_code = :postal_code,
                country = :country,
                status = :status,
                source = :source,
                notes_internal = :notes_internal,
                assigned_user_id = :assigned_user_id
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'] ?? '',
            'legal_name' => $data['legal_name'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'company_type' => $data['company_type'] ?? null,
            'sector' => $data['sector'] ?? null,
            'activity' => $data['activity'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'status' => $data['status'] ?? 'prospecto',
            'source' => $data['source'] ?? null,
            'notes_internal' => $data['notes_internal'] ?? null,
            'assigned_user_id' => $data['assigned_user_id'] ?? Auth::id(),
        ]);
    }

    public function getContacts(int $companyId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM company_contacts WHERE company_id = :company_id ORDER BY is_primary DESC, created_at DESC");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function getTasks(int $companyId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE entity_type = 'company' AND entity_id = :entity_id ORDER BY due_date ASC");
        $stmt->execute(['entity_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function getTimeline(int $companyId): array
    {
        // Activity logs de la empresa
        $stmt = $this->db->prepare("
            SELECT
                al.id, al.action, al.description, al.created_at,
                'activity' AS event_type,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name
            FROM activity_logs al
            LEFT JOIN users u ON u.id = al.user_id
            WHERE al.entity_type = 'company' AND al.entity_id = :id
        ");
        $stmt->execute(['id' => $companyId]);
        $activities = $stmt->fetchAll();

        // Tareas completadas o creadas
        $stmt2 = $this->db->prepare("
            SELECT
                t.id, t.title AS description, t.status, t.type,
                t.created_at, t.completed_at, t.due_date, t.notes,
                'task' AS event_type,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name
            FROM tasks t
            LEFT JOIN users u ON u.id = t.assigned_user_id
            WHERE t.entity_type = 'company' AND t.entity_id = :id
        ");
        $stmt2->execute(['id' => $companyId]);
        $tasks = $stmt2->fetchAll();

        // Contactos añadidos
        $stmt3 = $this->db->prepare("
            SELECT
                cc.id, cc.full_name AS description, cc.job_title,
                cc.created_at, 'contact' AS event_type,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name
            FROM company_contacts cc
            LEFT JOIN users u ON u.id = cc.created_by
            WHERE cc.company_id = :id
        ");
        $stmt3->execute(['id' => $companyId]);
        $contacts = $stmt3->fetchAll();

        // Unir y ordenar por fecha desc
        $all = array_merge($activities, $tasks, $contacts);
        usort($all, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
        return $all;
    }

    public function logActivity(string $entityType, int $entityId, string $action, string $description): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO activity_logs (entity_type, entity_id, user_id, action, description)
            VALUES (:entity_type, :entity_id, :user_id, :action, :description)
        ");

        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
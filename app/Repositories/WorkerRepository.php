<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class WorkerRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function paginate(array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['availability'])) {
            $where[] = 'availability = :availability';
            $params['availability'] = $filters['availability'];
        }

        if (!empty($filters['disability_type'])) {
            $where[] = 'disability_type LIKE :disability_type';
            $params['disability_type'] = '%' . $filters['disability_type'] . '%';
        }

        if (!empty($filters['q'])) {
            $where[] = '(full_name LIKE :q OR dni LIKE :q OR email LIKE :q OR phone LIKE :q OR skills LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->db->prepare("
            SELECT * FROM workers $sqlWhere
            ORDER BY full_name ASC LIMIT 100
        ");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM workers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO workers (
                first_name, last_name, full_name, dni, birth_date,
                disability_type, disability_degree, phone, mobile, email,
                address, city, province, postal_code,
                availability, schedule_type, driving_license,
                skills, notes, status, created_by
            ) VALUES (
                :first_name, :last_name, :full_name, :dni, :birth_date,
                :disability_type, :disability_degree, :phone, :mobile, :email,
                :address, :city, :province, :postal_code,
                :availability, :schedule_type, :driving_license,
                :skills, :notes, :status, :created_by
            )
        ");
        $full = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $stmt->execute([
            'first_name'        => ucfirst(strtolower(trim($data['first_name'] ?? ''))),
            'last_name'         => ucfirst(strtolower(trim($data['last_name'] ?? ''))),
            'full_name'         => ucwords(strtolower($full)),
            'dni'               => strtoupper(trim($data['dni'] ?? '')) ?: null,
            'birth_date'        => $data['birth_date'] ?: null,
            'disability_type'   => $data['disability_type'] ?? null,
            'disability_degree' => !empty($data['disability_degree']) ? (int)$data['disability_degree'] : null,
            'phone'             => $data['phone'] ?? null,
            'mobile'            => $data['mobile'] ?? null,
            'email'             => strtolower(trim($data['email'] ?? '')) ?: null,
            'address'           => $data['address'] ?? null,
            'city'              => ucfirst(strtolower(trim($data['city'] ?? ''))) ?: null,
            'province'          => ucfirst(strtolower(trim($data['province'] ?? ''))) ?: null,
            'postal_code'       => $data['postal_code'] ?? null,
            'availability'      => $data['availability'] ?? null,
            'schedule_type'     => $data['schedule_type'] ?? null,
            'driving_license'   => !empty($data['driving_license']) ? 1 : 0,
            'skills'            => $data['skills'] ?? null,
            'notes'             => $data['notes'] ?? null,
            'status'            => $data['status'] ?? 'activo',
            'created_by'        => Auth::id(),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE workers SET
                first_name = :first_name, last_name = :last_name, full_name = :full_name,
                dni = :dni, birth_date = :birth_date,
                disability_type = :disability_type, disability_degree = :disability_degree,
                phone = :phone, mobile = :mobile, email = :email,
                address = :address, city = :city, province = :province, postal_code = :postal_code,
                availability = :availability, schedule_type = :schedule_type,
                driving_license = :driving_license, skills = :skills,
                notes = :notes, status = :status
            WHERE id = :id
        ");
        $full = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $stmt->execute([
            'id'                => $id,
            'first_name'        => ucfirst(strtolower(trim($data['first_name'] ?? ''))),
            'last_name'         => ucfirst(strtolower(trim($data['last_name'] ?? ''))),
            'full_name'         => ucwords(strtolower($full)),
            'dni'               => strtoupper(trim($data['dni'] ?? '')) ?: null,
            'birth_date'        => $data['birth_date'] ?: null,
            'disability_type'   => $data['disability_type'] ?? null,
            'disability_degree' => !empty($data['disability_degree']) ? (int)$data['disability_degree'] : null,
            'phone'             => $data['phone'] ?? null,
            'mobile'            => $data['mobile'] ?? null,
            'email'             => strtolower(trim($data['email'] ?? '')) ?: null,
            'address'           => $data['address'] ?? null,
            'city'              => ucfirst(strtolower(trim($data['city'] ?? ''))) ?: null,
            'province'          => ucfirst(strtolower(trim($data['province'] ?? ''))) ?: null,
            'postal_code'       => $data['postal_code'] ?? null,
            'availability'      => $data['availability'] ?? null,
            'schedule_type'     => $data['schedule_type'] ?? null,
            'driving_license'   => !empty($data['driving_license']) ? 1 : 0,
            'skills'            => $data['skills'] ?? null,
            'notes'             => $data['notes'] ?? null,
            'status'            => $data['status'] ?? 'activo',
        ]);
    }

    public function getAssignments(int $workerId): array
    {
        $stmt = $this->db->prepare("
            SELECT wa.*,
                CASE wa.entity_type
                    WHEN 'company'  THEN (SELECT name FROM companies WHERE id = wa.entity_id)
                    WHEN 'contract' THEN (SELECT title FROM contracts WHERE id = wa.entity_id)
                    WHEN 'lead'     THEN (SELECT full_name FROM leads WHERE id = wa.entity_id)
                    WHEN 'task'     THEN (SELECT title FROM tasks WHERE id = wa.entity_id)
                END AS entity_name
            FROM worker_assignments wa
            WHERE wa.worker_id = :id
            ORDER BY wa.start_date DESC
        ");
        $stmt->execute(['id' => $workerId]);
        return $stmt->fetchAll();
    }

    public function addAssignment(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO worker_assignments (worker_id, entity_type, entity_id, start_date, end_date, notes, created_by)
            VALUES (:worker_id, :entity_type, :entity_id, :start_date, :end_date, :notes, :created_by)
        ");
        $stmt->execute([
            'worker_id'   => $data['worker_id'],
            'entity_type' => $data['entity_type'],
            'entity_id'   => $data['entity_id'],
            'start_date'  => $data['start_date'] ?: null,
            'end_date'    => $data['end_date'] ?: null,
            'notes'       => $data['notes'] ?? null,
            'created_by'  => Auth::id(),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function deleteAssignment(int $id): void
    {
        $this->db->prepare("DELETE FROM worker_assignments WHERE id = :id")->execute(['id' => $id]);
    }

    public function getByEntity(string $type, int $entityId): array
    {
        $stmt = $this->db->prepare("
            SELECT wa.*, w.full_name, w.dni, w.disability_type, w.disability_degree, w.status AS worker_status
            FROM worker_assignments wa
            JOIN workers w ON w.id = wa.worker_id
            WHERE wa.entity_type = :type AND wa.entity_id = :id
            ORDER BY wa.start_date DESC
        ");
        $stmt->execute(['type' => $type, 'id' => $entityId]);
        return $stmt->fetchAll();
    }

    public function getKpis(): array
    {
        return [
            'total'      => (int) $this->db->query("SELECT COUNT(*) FROM workers")->fetchColumn(),
            'active'     => (int) $this->db->query("SELECT COUNT(*) FROM workers WHERE status = 'activo'")->fetchColumn(),
            'assigned'   => (int) $this->db->query("SELECT COUNT(DISTINCT worker_id) FROM worker_assignments WHERE (end_date IS NULL OR end_date >= CURDATE())")->fetchColumn(),
            'available'  => (int) $this->db->query("SELECT COUNT(*) FROM workers WHERE status = 'activo' AND id NOT IN (SELECT DISTINCT worker_id FROM worker_assignments WHERE end_date IS NULL OR end_date >= CURDATE())")->fetchColumn(),
        ];
    }
}

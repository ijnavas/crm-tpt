<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class ContractRepository
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
            $where[] = 'c.status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['company_id'])) {
            $where[] = 'c.company_id = :company_id';
            $params['company_id'] = $filters['company_id'];
        }

        if (!empty($filters['service_type'])) {
            $where[] = 'c.service_type = :service_type';
            $params['service_type'] = $filters['service_type'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(c.title LIKE :q OR co.name LIKE :q OR c.service_type LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare("
            SELECT c.*, co.name AS company_name
            FROM contracts c
            JOIN companies co ON co.id = c.company_id
            $sqlWhere
            ORDER BY c.created_at DESC
            LIMIT 100
        ");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, co.name AS company_name
            FROM contracts c
            JOIN companies co ON co.id = c.company_id
            WHERE c.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function getByCompany(int $companyId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM contracts
            WHERE company_id = :company_id
            ORDER BY start_date DESC
        ");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function insert(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO contracts (
                company_id, title, service_type, description,
                status, start_date, end_date, renewable,
                amount, workers_assigned, document_url, notes,
                assigned_user_id, created_by
            ) VALUES (
                :company_id, :title, :service_type, :description,
                :status, :start_date, :end_date, :renewable,
                :amount, :workers_assigned, :document_url, :notes,
                :assigned_user_id, :created_by
            )
        ");
        $stmt->execute([
            'company_id'       => $data['company_id'],
            'title'            => $data['title'],
            'service_type'     => $data['service_type'],
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'] ?? 'activo',
            'start_date'       => $data['start_date'],
            'end_date'         => !empty($data['end_date']) ? $data['end_date'] : null,
            'renewable'        => !empty($data['renewable']) ? 1 : 0,
            'amount'           => !empty($data['amount']) ? $data['amount'] : null,
            'workers_assigned' => (int) ($data['workers_assigned'] ?? 0),
            'document_url'     => $data['document_url'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'assigned_user_id' => Auth::id(),
            'created_by'       => Auth::id(),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE contracts SET
                company_id       = :company_id,
                title            = :title,
                service_type     = :service_type,
                description      = :description,
                status           = :status,
                start_date       = :start_date,
                end_date         = :end_date,
                renewable        = :renewable,
                amount           = :amount,
                workers_assigned = :workers_assigned,
                document_url     = :document_url,
                notes            = :notes
            WHERE id = :id
        ");
        $stmt->execute([
            'id'               => $id,
            'company_id'       => $data['company_id'],
            'title'            => $data['title'],
            'service_type'     => $data['service_type'],
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'] ?? 'activo',
            'start_date'       => $data['start_date'],
            'end_date'         => !empty($data['end_date']) ? $data['end_date'] : null,
            'renewable'        => !empty($data['renewable']) ? 1 : 0,
            'amount'           => !empty($data['amount']) ? $data['amount'] : null,
            'workers_assigned' => (int) ($data['workers_assigned'] ?? 0),
            'document_url'     => $data['document_url'] ?? null,
            'notes'            => $data['notes'] ?? null,
        ]);
    }

    public function getServiceTypes(): array
    {
        $stmt = $this->db->query("
            SELECT DISTINCT service_type FROM contracts
            WHERE service_type IS NOT NULL AND service_type != ''
            ORDER BY service_type ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getKpis(): array
    {
        return [
            'total_active'   => (int) $this->db->query("SELECT COUNT(*) FROM contracts WHERE status = 'activo'")->fetchColumn(),
            'expiring_soon'  => (int) $this->db->query("SELECT COUNT(*) FROM contracts WHERE status = 'activo' AND end_date IS NOT NULL AND end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetchColumn(),
            'total_workers'  => (int) $this->db->query("SELECT SUM(workers_assigned) FROM contracts WHERE status = 'activo'")->fetchColumn(),
            'total_amount'   => (float) $this->db->query("SELECT SUM(amount) FROM contracts WHERE status = 'activo'")->fetchColumn(),
        ];
    }
}

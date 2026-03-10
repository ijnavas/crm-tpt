<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CompanyRepository;

final class CompanyService
{
    private CompanyRepository $repo;

    public function __construct()
    {
        $this->repo = new CompanyRepository();
    }

    public function paginateCompanies(array $filters): array
    {
        return $this->repo->paginate($filters);
    }

    public function getCompanyById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function getCompanyDetail(int $id): array
    {
        return [
            'company' => $this->repo->find($id),
            'contacts' => $this->repo->getContacts($id),
            'tasks' => $this->repo->getTasks($id),
            'timeline' => $this->repo->getTimeline($id),
        ];
    }

    public function createCompany(array $data): int
    {
        $id = $this->repo->insert($data);
        $this->repo->logActivity('company', $id, 'created', 'Empresa creada');
        return $id;
    }

    public function updateCompany(int $id, array $data): void
    {
        $this->repo->update($id, $data);
        $this->repo->logActivity('company', $id, 'updated', 'Empresa actualizada');
    }

    public function getFormCatalogs(): array
    {
        return [
            'statuses' => ['prospecto', 'activa', 'inactiva', 'bloqueada'],
            'sectors' => ['salud', 'hosteleria', 'industria', 'servicios', 'retail', 'otros'],
        ];
    }
}
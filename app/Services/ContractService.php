<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContractRepository;
use App\Repositories\CompanyRepository;

final class ContractService
{
    private ContractRepository $repo;

    public function __construct()
    {
        $this->repo = new ContractRepository();
    }

    public function paginate(array $filters = []): array
    {
        return $this->repo->paginate($filters);
    }

    public function getById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function getByCompany(int $companyId): array
    {
        return $this->repo->getByCompany($companyId);
    }

    public function create(array $data): int
    {
        return $this->repo->insert($data);
    }

    public function update(int $id, array $data): void
    {
        $this->repo->update($id, $data);
    }

    public function getKpis(): array
    {
        return $this->repo->getKpis();
    }

    public function getFormCatalogs(): array
    {
        $companyRepo = new CompanyRepository();
        return [
            'statuses' => [
                'activo'     => 'Activo',
                'renovado'   => 'Renovado',
                'pausado'    => 'Pausado',
                'finalizado' => 'Finalizado',
                'cancelado'  => 'Cancelado',
            ],
            'service_types' => [
                'limpieza'            => 'Limpieza',
                'mantenimiento'       => 'Mantenimiento',
                'jardineria'          => 'Jardinería',
                'logistica'           => 'Logística',
                'administracion'      => 'Administración',
                'atencion_al_cliente' => 'Atención al cliente',
                'produccion'          => 'Producción',
                'hosteleria'          => 'Hostelería',
                'otro'                => 'Otro',
            ],
            'companies' => $companyRepo->paginate([])['data'],
        ];
    }
}

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



    public function update(int $id, array $data): void
    {
        if (!empty($_FILES['document']['name'])) {
            $data['document_url'] = $this->uploadDocument($_FILES['document'], $id);
        }
        $this->repo->update($id, $data);
    }

    public function create(array $data): int
    {
        $id = $this->repo->insert($data);
        if (!empty($_FILES['document']['name'])) {
            $url = $this->uploadDocument($_FILES['document'], $id);
            $data['document_url'] = $url;
            $this->repo->update($id, array_merge($this->repo->find($id), ['document_url' => $url]));
        }
        return $id;
    }

    private function uploadDocument(array $file, int $contractId): string
    {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) throw new \RuntimeException('Formato no permitido. Usa PDF, DOC, DOCX o imagen.');
        if ($file['size'] > 10 * 1024 * 1024) throw new \RuntimeException('Máximo 10MB');

        $dir = BASE_PATH . '/public/assets/docs/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $name = 'contract_' . $contractId . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
            throw new \RuntimeException('No se pudo guardar el archivo. Verifica permisos del directorio public/assets/docs/');
        }

        return '/assets/docs/' . $name;
    }

    public function getFilterCatalogs(): array
    {
        return [
            'companies'     => $this->repo->getCompaniesWithContracts(),
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
            'statuses' => [
                'activo'     => 'Activo',
                'renovado'   => 'Renovado',
                'pausado'    => 'Pausado',
                'finalizado' => 'Finalizado',
                'cancelado'  => 'Cancelado',
            ],
        ];
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
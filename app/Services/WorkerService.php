<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\WorkerRepository;

final class WorkerService
{
    private WorkerRepository $repo;

    public function __construct()
    {
        $this->repo = new WorkerRepository();
    }

    public function paginate(array $filters = []): array { return $this->repo->paginate($filters); }
    public function getById(int $id): array              { return $this->repo->find($id); }
    public function create(array $data): int             { return $this->repo->insert($data); }
    public function update(int $id, array $data): void   { $this->repo->update($id, $data); }
    public function getAssignments(int $id): array       { return $this->repo->getAssignments($id); }
    public function addAssignment(array $data): int      { return $this->repo->addAssignment($data); }
    public function deleteAssignment(int $id): void      { $this->repo->deleteAssignment($id); }
    public function getByEntity(string $type, int $id): array { return $this->repo->getByEntity($type, $id); }
    public function getKpis(): array                     { return $this->repo->getKpis(); }

    public function getCatalogs(): array
    {
        return [
            'statuses' => [
                'activo'          => 'Activo',
                'baja_temporal'   => 'Baja temporal',
                'baja_definitiva' => 'Baja definitiva',
                'en_formacion'    => 'En formación',
            ],
            'availability' => [
                'manana'        => 'Mañana',
                'tarde'         => 'Tarde',
                'completa'      => 'Jornada completa',
                'fines_semana'  => 'Fines de semana',
                'flexible'      => 'Flexible',
            ],
            'schedule_types' => [
                'completa'  => 'Jornada completa',
                'parcial'   => 'Jornada parcial',
                'flexible'  => 'Flexible',
            ],
            'disability_types' => [
                'fisica'         => 'Física',
                'psiquica'       => 'Psíquica',
                'sensorial'      => 'Sensorial',
                'intelectual'    => 'Intelectual',
                'organica'       => 'Orgánica',
                'multiple'       => 'Múltiple',
            ],
            'entity_types' => [
                'company'  => 'Empresa',
                'contract' => 'Contrato',
                'lead'     => 'Lead',
                'task'     => 'Tarea',
            ],
        ];
    }
}

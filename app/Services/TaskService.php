<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaskRepository;

final class TaskService
{
    private TaskRepository $repo;

    public function __construct()
    {
        $this->repo = new TaskRepository();
    }

    public function getAll(array $filters = []): array
    {
        return $this->repo->getAll($filters);
    }

    public function getGroupedByEntity(): array
    {
        return $this->repo->getGroupedByEntity();
    }

    public function getUpcoming(int $limit = 5): array
    {
        return $this->repo->getUpcoming($limit);
    }

    public function getAllForCalendar(): array
    {
        return $this->repo->getAllForCalendar();
    }

    public function getReminders(): array
    {
        return $this->repo->getReminders();
    }

    public function getById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function create(array $data): int
    {
        return $this->repo->insert($data);
    }

    public function update(int $id, array $data): void
    {
        $this->repo->update($id, $data);
    }

    public function complete(int $id): void
    {
        $this->repo->complete($id);
    }

    public function getFormCatalogs(): array
    {
        return [
            'types' => [
                'llamada'   => 'Llamada',
                'email'     => 'Email',
                'visita'    => 'Visita comercial',
                'propuesta' => 'Propuesta / Presupuesto',
                'reunion'   => 'Reunión',
                'seguimiento' => 'Seguimiento',
                'otro'      => 'Otro',
            ],
            'priorities' => [
                'baja'    => 'Baja',
                'media'   => 'Media',
                'alta'    => 'Alta',
                'urgente' => 'Urgente',
            ],
            'statuses' => [
                'pendiente'  => 'Pendiente',
                'en_curso'   => 'En curso',
                'completada' => 'Completada',
            ],
            'entity_types' => [
                'company' => 'Empresa',
                'lead'    => 'Lead',
                'contact' => 'Contacto',
            ],
        ];
    }
}
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

    public function paginateTasks(array $filters): array
    {
        return $this->repo->paginate($filters);
    }

    public function getTaskById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function createTask(array $data): int
    {
        return $this->repo->insert($data);
    }

    public function updateTask(int $id, array $data): void
    {
        $this->repo->update($id, $data);
    }

    public function completeTask(int $id, array $data): void
    {
        $this->repo->complete($id, $data['result'] ?? null);
    }

    public function getFormCatalogs(): array
    {
        return [
            'types' => ['llamada', 'email', 'reunion', 'seguimiento', 'documentacion'],
            'priorities' => ['baja', 'media', 'alta', 'urgente'],
            'statuses' => ['pendiente', 'en_curso', 'completada', 'pospuesta'],
        ];
    }
}
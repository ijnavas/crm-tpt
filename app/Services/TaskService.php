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

    public function getById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function create(array $data): int
    {
        return $this->repo->insert($data);
    }

    public function complete(int $id): void
    {
        $this->repo->complete($id);
    }
}
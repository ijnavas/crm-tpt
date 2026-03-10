<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Services\TaskService;

final class TaskController extends Controller
{
    private TaskService $service;

    public function __construct()
    {
        $this->service = new TaskService();
    }

    private function guard(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
    }

    public function index(): void
    {
        $this->guard();

        $filters = Request::all();
        $result = $this->service->paginateTasks($filters);

        $this->view('tasks/index', [
            'title' => 'Tareas',
            'tasks' => $result['data'],
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->guard();

        $this->view('tasks/create', [
            'title' => 'Nueva tarea',
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function store(): void
    {
        $this->guard();

        $id = $this->service->createTask(Request::all());
        $this->redirect('/tasks/' . $id);
    }

    public function show(string $id): void
    {
        $this->guard();

        $this->view('tasks/show', [
            'title' => 'Ficha tarea',
            'task' => $this->service->getTaskById((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();

        $this->view('tasks/edit', [
            'title' => 'Editar tarea',
            'task' => $this->service->getTaskById((int) $id),
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();

        $this->service->updateTask((int) $id, Request::all());
        $this->redirect('/tasks/' . $id);
    }

    public function complete(string $id): void
    {
        $this->guard();

        $this->service->completeTask((int) $id, Request::all());
        $this->redirect('/tasks/' . $id);
    }
public function create(): void
{
    $this->guard();

    $entityType = $_GET['entity_type'] ?? null;
    $entityId = $_GET['entity_id'] ?? null;

    $this->view('tasks/create', [
        'title' => 'Nueva tarea',
        'entityType' => $entityType,
        'entityId' => $entityId
    ]);
}

}
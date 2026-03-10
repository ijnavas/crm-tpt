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

        $tasks = $this->service->getAll();

        $this->view('tasks/index', [
            'title' => 'Tareas',
            'tasks' => $tasks
        ]);
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

    public function store(): void
    {
        $this->guard();

        $this->service->create(Request::all());
        $this->redirect('/tasks');
    }

    public function show(string $id): void
    {
        $this->guard();

        $task = $this->service->getById((int) $id);

        $this->view('tasks/show', [
            'title' => 'Ficha tarea',
            'task' => $task
        ]);
    }

    public function complete(string $id): void
    {
        $this->guard();

        $this->service->complete((int) $id);
        $this->redirect('/tasks');
    }
}
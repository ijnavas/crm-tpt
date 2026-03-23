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
        if (!Auth::check()) $this->redirect('/login');
    }

    public function index(): void
    {
        $this->guard();
        $filters  = Request::all();
        $view     = $filters['view'] ?? 'list';

        $this->view('tasks/index', [
            'title'    => 'Tareas',
            'tasks'    => $this->service->getAll($filters),
            'grouped'  => $this->service->getGroupedByEntity(),
            'filters'  => $filters,
            'catalogs' => $this->service->getFormCatalogs(),
            'view'     => $view,
            'reminders' => $this->service->getReminders(),
        ]);
    }

    public function create(): void
    {
        $this->guard();
        $this->view('tasks/create', [
            'title'      => 'Nueva tarea',
            'catalogs'   => $this->service->getFormCatalogs(),
            'entityType' => $_GET['entity_type'] ?? null,
            'entityId'   => $_GET['entity_id'] ?? null,
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
        $this->view('tasks/show', [
            'title' => 'Tarea',
            'task'  => $this->service->getById((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();
        $this->view('tasks/edit', [
            'title'    => 'Editar tarea',
            'task'     => $this->service->getById((int) $id),
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();
        $this->service->update((int) $id, Request::all());
        $this->redirect('/tasks');
    }

    public function complete(string $id): void
    {
        $this->guard();
        $this->service->complete((int) $id);

        $ref = $_SERVER['HTTP_REFERER'] ?? '/tasks';
        $this->redirect($ref);
    }
}
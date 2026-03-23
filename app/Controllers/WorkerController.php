<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\WorkerService;

final class WorkerController extends Controller
{
    private WorkerService $service;

    public function __construct()
    {
        $this->service = new WorkerService();
    }

    private function guard(): void
    {
        if (!Auth::check()) $this->redirect('/login');
    }

    public function index(): void
    {
        $this->guard();
        $filters = Request::all();
        $this->view('workers/index', [
            'title'    => 'Trabajadores',
            'workers'  => $this->service->paginate($filters),
            'filters'  => $filters,
            'kpis'     => $this->service->getKpis(),
            'catalogs' => $this->service->getCatalogs(),
        ]);
    }

    public function create(): void
    {
        $this->guard();
        $this->view('workers/create', [
            'title'    => 'Nuevo trabajador',
            'catalogs' => $this->service->getCatalogs(),
        ]);
    }

    public function store(): void
    {
        $this->guard();
        $id = $this->service->create(Request::all());
        Session::flash('success', 'Trabajador creado correctamente');
        $this->redirect('/workers/' . $id);
    }

    public function show(string $id): void
    {
        $this->guard();
        $worker = $this->service->getById((int) $id);
        if (empty($worker)) $this->redirect('/workers');
        $this->view('workers/show', [
            'title'       => 'Ficha trabajador',
            'worker'      => $worker,
            'assignments' => $this->service->getAssignments((int) $id),
            'catalogs'    => $this->service->getCatalogs(),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();
        $worker = $this->service->getById((int) $id);
        if (empty($worker)) $this->redirect('/workers');
        $this->view('workers/edit', [
            'title'    => 'Editar trabajador',
            'worker'   => $worker,
            'catalogs' => $this->service->getCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();
        $this->service->update((int) $id, Request::all());
        Session::flash('success', 'Trabajador actualizado correctamente');
        $this->redirect('/workers/' . $id);
    }

    public function addAssignment(string $id): void
    {
        $this->guard();
        $data = Request::all();
        $data['worker_id'] = (int) $id;
        $this->service->addAssignment($data);
        Session::flash('success', 'Asignación añadida correctamente');
        $this->redirect('/workers/' . $id);
    }

    public function deleteAssignment(string $id, string $assignId): void
    {
        $this->guard();
        $this->service->deleteAssignment((int) $assignId);
        Session::flash('success', 'Asignación eliminada');
        $this->redirect('/workers/' . $id);
    }
}

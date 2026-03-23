<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\ContractService;

final class ContractController extends Controller
{
    private ContractService $service;

    public function __construct()
    {
        $this->service = new ContractService();
    }

    private function guard(): void
    {
        if (!Auth::check()) $this->redirect('/login');
    }

    public function index(): void
    {
        $this->guard();
        $filters = Request::all();
        $this->view('contracts/index', [
            'title'     => 'Contratos',
            'contracts' => $this->service->paginate($filters),
            'filters'   => $filters,
            'kpis'      => $this->service->getKpis(),
            'catalogs'  => $this->service->getFormCatalogs(),
        ]);
    }

    public function create(): void
    {
        $this->guard();
        $this->view('contracts/create', [
            'title'    => 'Nuevo contrato',
            'catalogs' => $this->service->getFormCatalogs(),
            'company_id' => $_GET['company_id'] ?? null,
        ]);
    }

    public function store(): void
    {
        $this->guard();
        $id = $this->service->create(Request::all());
        Session::flash('success', 'Contrato creado correctamente');
        $this->redirect('/contracts/' . $id);
    }

    public function show(string $id): void
    {
        $this->guard();
        $contract = $this->service->getById((int) $id);
        if (empty($contract)) $this->redirect('/contracts');
        $this->view('contracts/show', [
            'title'    => 'Contrato',
            'contract' => $contract,
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();
        $contract = $this->service->getById((int) $id);
        if (empty($contract)) $this->redirect('/contracts');
        $this->view('contracts/edit', [
            'title'    => 'Editar contrato',
            'contract' => $contract,
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();
        $this->service->update((int) $id, Request::all());
        Session::flash('success', 'Contrato actualizado correctamente');
        $this->redirect('/contracts/' . $id);
    }
}

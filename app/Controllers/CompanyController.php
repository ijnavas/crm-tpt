<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Services\CompanyService;

final class CompanyController extends Controller
{
    private CompanyService $service;

    public function __construct()
    {
        $this->service = new CompanyService();
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
        $result = $this->service->paginateCompanies($filters);

        $this->view('companies/index', [
            'title' => 'Empresas',
            'companies' => $result['data'],
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->guard();

        $this->view('companies/create', [
            'title' => 'Nueva empresa',
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function store(): void
    {
        $this->guard();

        $id = $this->service->createCompany(Request::all());
        $this->redirect('/companies/' . $id);
    }

    public function show(string $id): void
    {
        $this->guard();

        $this->view('companies/show', [
            'title' => 'Ficha empresa',
            'companyDetail' => $this->service->getCompanyDetail((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();

        $this->view('companies/edit', [
            'title' => 'Editar empresa',
            'company' => $this->service->getCompanyById((int) $id),
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();

        $this->service->updateCompany((int) $id, Request::all());
        $this->redirect('/companies/' . $id);
    }
}
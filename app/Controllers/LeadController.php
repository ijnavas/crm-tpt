<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Services\LeadService;

final class LeadController extends Controller
{
    private LeadService $service;

    public function __construct()
    {
        $this->service = new LeadService();
    }

    public function index(): void
    {
        $this->guard();

        $filters = Request::all();
        $result = $this->service->paginateLeads($filters);

        $this->view('leads/index', [
            'title' => 'Leads',
            'leads' => $result['data'],
            'filters' => $filters,
            'pagination' => $result['pagination'],
        ]);
    }

    public function create(): void
    {
        $this->guard();

        $this->view('leads/create', [
            'title' => 'Nuevo lead',
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function store(): void
    {
        $this->guard();

        $leadId = $this->service->createLead(Request::all());
        $this->redirect('/leads/' . $leadId);
    }

    public function show(string $id): void
    {
        $this->guard();

        $this->view('leads/show', [
            'title' => 'Ficha lead',
            'leadDetail' => $this->service->getLeadDetail((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();

        $this->view('leads/edit', [
            'title' => 'Editar lead',
            'lead' => $this->service->getLeadById((int) $id),
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();

        $this->service->updateLead((int) $id, Request::all());
        $this->redirect('/leads/' . $id);
    }

    public function storeNote(string $id): void
    {
        $this->guard();

        $note = trim((string) Request::input('note'));
        if ($note !== '') {
            $this->service->addNote((int) $id, $note);
        }

        $this->redirect('/leads/' . $id);
    }

    public function updateStatus(string $id): void
    {
        $this->guard();

        $status = (string) Request::input('status');
        $comment = Request::input('comment');

        $this->service->updateStatus((int) $id, $status, is_string($comment) ? $comment : null);
        $this->redirect('/leads/' . $id);
    }

    private function guard(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
    }
public function convertForm(string $id): void
{
    $this->guard();

    $lead = $this->service->getLeadById((int) $id);

    $this->view('leads/convert', [
        'title' => 'Convertir lead',
        'lead' => $lead,
    ]);
}

public function convert(string $id): void
{
    $this->guard();

    $result = $this->service->convertLead((int) $id, Request::all());

    $this->redirect('/companies/' . $result['company_id']);
}
}

<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Services\ContactService;

final class ContactController extends Controller
{
    private ContactService $service;

    public function __construct()
    {
        $this->service = new ContactService();
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
        $result = $this->service->paginateContacts($filters);

        $this->view('contacts/index', [
            'title' => 'Contactos',
            'contacts' => $result['data'],
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->guard();

        $this->view('contacts/create', [
            'title' => 'Nuevo contacto',
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function store(): void
    {
        $this->guard();

        $id = $this->service->createContact(Request::all());
        $this->redirect('/contacts/' . $id);
    }

    public function show(string $id): void
    {
        $this->guard();

        $this->view('contacts/show', [
            'title' => 'Ficha contacto',
            'contact' => $this->service->getContactDetail((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $this->guard();

        $this->view('contacts/edit', [
            'title' => 'Editar contacto',
            'contact' => $this->service->getContactById((int) $id),
            'catalogs' => $this->service->getFormCatalogs(),
        ]);
    }

    public function update(string $id): void
    {
        $this->guard();

        $this->service->updateContact((int) $id, Request::all());
        $this->redirect('/contacts/' . $id);
    }
}
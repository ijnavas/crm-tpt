<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContactRepository;

final class ContactService
{
    private ContactRepository $repo;

    public function __construct()
    {
        $this->repo = new ContactRepository();
    }

    public function paginateContacts(array $filters): array
    {
        return $this->repo->paginate($filters);
    }

    public function getContactById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function getContactDetail(int $id): array
    {
        return $this->repo->find($id);
    }

    public function createContact(array $data): int
    {
        return $this->repo->insert($data);
    }

    public function updateContact(int $id, array $data): void
    {
        $this->repo->update($id, $data);
    }

    public function getFormCatalogs(): array
    {
        return [
            'decision_levels' => ['decisor', 'influenciador', 'tecnico', 'administrativo'],
            'statuses' => ['activo', 'inactivo', 'sin_respuesta'],
            'channels' => ['telefono', 'email', 'whatsapp'],
            'companies' => $this->repo->getCompanies(),
        ];
    }
}
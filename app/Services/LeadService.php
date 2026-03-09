<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\LeadRepository;

final class LeadService
{
    private LeadRepository $repo;

    public function __construct()
    {
        $this->repo = new LeadRepository();
    }

    public function paginateLeads(array $filters): array
    {
        return $this->repo->paginate($filters);
    }

    public function getLeadById(int $id): array
    {
        return $this->repo->find($id);
    }

    public function getLeadDetail(int $id): array
    {
        return [
            'lead' => $this->repo->find($id),
            'notes' => $this->repo->getNotes($id),
            'timeline' => $this->repo->getTimeline($id),
        ];
    }

    public function createLead(array $data): int
    {
        $leadId = $this->repo->insert($data);
        $this->repo->logActivity('lead', $leadId, 'created', 'Lead creado');
        return $leadId;
    }

    public function updateLead(int $id, array $data): void
    {
        $this->repo->update($id, $data);
        $this->repo->logActivity('lead', $id, 'updated', 'Lead actualizado');
    }

    public function addNote(int $id, string $note): void
    {
        $this->repo->insertNote($id, $note);
        $this->repo->logActivity('lead', $id, 'note_added', 'Nota añadida');
    }

    public function updateStatus(int $id, string $status, ?string $comment = null): void
    {
        $this->repo->updateStatus($id, $status);
        $this->repo->logActivity('lead', $id, 'status_changed', $comment ?: 'Estado actualizado');
    }

    public function getFormCatalogs(): array
    {
        return [
            'statuses' => ['nuevo', 'pendiente_contacto', 'en_seguimiento', 'cualificado', 'interesado', 'no_interesado', 'convertido'],
            'priorities' => ['baja', 'media', 'alta', 'urgente'],
            'sources' => ['web', 'telefono', 'whatsapp', 'referencia', 'visita', 'campaña'],
        ];
    }
}

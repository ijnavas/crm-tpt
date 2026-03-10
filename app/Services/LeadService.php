<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\LeadRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ContactRepository;
use App\Core\Auth;
use App\Core\Database;

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
public function convertLead(int $leadId, array $data): array
{
    $lead = $this->repo->find($leadId);

    if (!$lead) {
        throw new \RuntimeException('Lead no encontrado');
    }

    if (($lead['status'] ?? '') === 'convertido') {
        throw new \RuntimeException('Este lead ya fue convertido');
    }

    $db = Database::connection();
    $db->beginTransaction();

    try {
        $companyRepo = new CompanyRepository();
        $contactRepo = new ContactRepository();

        $companyId = $companyRepo->insert([
            'name' => $data['company_name'] ?? ($lead['company_name'] ?: $lead['full_name']),
            'legal_name' => $data['legal_name'] ?? $lead['company_name'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'company_type' => $data['company_type'] ?? null,
            'sector' => $data['sector'] ?? null,
            'activity' => $data['activity'] ?? null,
            'phone' => $data['phone'] ?? $lead['phone'] ?? null,
            'email' => $data['email'] ?? $lead['email'] ?? null,
            'website' => $data['website'] ?? $lead['website'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? $lead['city'] ?? null,
            'province' => $data['province'] ?? $lead['province'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? 'España',
            'status' => 'prospecto',
            'source' => $lead['source'] ?? null,
            'notes_internal' => 'Empresa creada automáticamente desde lead #' . $leadId,
            'assigned_user_id' => $lead['assigned_user_id'] ?? Auth::id(),
        ]);

        $contactId = $contactRepo->insert([
            'company_id' => $companyId,
            'first_name' => $data['first_name'] ?? ($lead['first_name'] ?? $lead['full_name']),
            'last_name' => $data['last_name'] ?? ($lead['last_name'] ?? null),
            'job_title' => $data['job_title'] ?? ($lead['job_title'] ?? null),
            'phone' => $data['phone'] ?? ($lead['phone'] ?? null),
            'mobile' => $data['mobile'] ?? ($lead['mobile'] ?? null),
            'email' => $data['email'] ?? ($lead['email'] ?? null),
            'preferred_channel' => 'email',
            'contact_schedule' => null,
            'decision_level' => 'decisor',
            'is_primary' => 1,
            'status' => 'activo',
            'notes_internal' => 'Contacto creado automáticamente desde lead #' . $leadId,
            'assigned_user_id' => $lead['assigned_user_id'] ?? Auth::id(),
        ]);

        $this->repo->markAsConverted($leadId, $companyId, $contactId);

        $this->repo->storeConversion($leadId, $companyId, $contactId);

        $this->repo->logActivity('lead', $leadId, 'converted', 'Lead convertido a empresa');
        $companyRepo->logActivity('company', $companyId, 'created_from_lead', 'Empresa creada desde lead #' . $leadId);

        $db->commit();

        return [
            'company_id' => $companyId,
            'contact_id' => $contactId,
        ];
    } catch (\Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}


}

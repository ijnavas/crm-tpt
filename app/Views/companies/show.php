<?php
$company  = $companyDetail['company'];
$contacts = $companyDetail['contacts'];
$tasks    = $companyDetail['tasks'];
$timeline = $companyDetail['timeline'];

$statusColors = [
    'activa'    => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'prospecto' => ['bg'=>'#fefce8','color'=>'#a16207'],
    'inactiva'  => ['bg'=>'#f9fafb','color'=>'#6b7280'],
    'bloqueada' => ['bg'=>'#fef2f2','color'=>'#b91c1c'],
];
$st = $statusColors[$company['status'] ?? 'prospecto'] ?? $statusColors['prospecto'];

$typeIcons = [
    'llamada'=>'📞','email'=>'📧','visita'=>'🚗',
    'propuesta'=>'📄','reunion'=>'👥','seguimiento'=>'🔄','otro'=>'📌'
];
?>
<style>
/* ── Layout ficha ── */
.company-layout{display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start}
.company-main{min-width:0}

/* ── Header card ── */
.company-hero{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.company-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}
.company-hero-left{display:flex;align-items:center;gap:16px}
.company-initial{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#2f80ed22,#2f80ed55);color:var(--primary);font-size:24px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.company-name{font-size:22px;font-weight:700;color:var(--text-main);margin:0 0 4px;letter-spacing:-.3px}
.company-status-pill{display:inline-flex;align-items:center;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600}
.company-hero-actions{display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap}
.company-meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.company-meta-item{display:flex;flex-direction:column;gap:3px}
.company-meta-label{font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.4px}
.company-meta-value{font-size:13px;color:var(--text-main);font-weight:500}

/* ── Sección card ── */
.section-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow-soft)}
.section-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border-soft)}
.section-head h3{margin:0;font-size:14px;font-weight:700}
.section-empty{padding:24px 20px;text-align:center;font-size:13px;color:var(--text-light)}

/* ── Contactos ── */
.contact-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border-soft)}
.contact-row:last-child{border-bottom:none}
.contact-avatar{width:36px;height:36px;border-radius:50%;background:#e8f9f1;color:#27ae60;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.contact-info{flex:1;min-width:0}
.contact-name{font-size:13px;font-weight:600}
.contact-sub{font-size:11px;color:var(--text-soft);margin-top:1px}

/* ── Tareas ── */
.task-row-s{display:flex;align-items:center;gap:10px;padding:11px 20px;border-bottom:1px solid var(--border-soft)}
.task-row-s:last-child{border-bottom:none}
.task-icon{width:30px;height:30px;border-radius:7px;background:var(--bg-muted);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.task-info{flex:1;min-width:0}
.task-title-s{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.task-meta-s{font-size:11px;color:var(--text-soft);margin-top:2px}

/* ── Timeline ── */
.timeline{padding:0 20px 8px}
.timeline-item{display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--border-soft);position:relative}
.timeline-item:last-child{border-bottom:none}
.timeline-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:2px}
.tl-activity{background:#eff6ff}
.tl-task{background:#fdf4ff}
.tl-contact{background:#f0fdf4}
.timeline-body{flex:1;min-width:0}
.timeline-title{font-size:13px;font-weight:600;color:var(--text-main);margin-bottom:3px}
.timeline-desc{font-size:12px;color:var(--text-soft);line-height:1.4}
.timeline-time{font-size:11px;color:var(--text-light);margin-top:4px;display:flex;align-items:center;gap:6px}
.timeline-user{font-size:11px;font-weight:600;color:var(--text-soft)}

/* Responsive */
@media(max-width:1000px){
  .company-layout{grid-template-columns:1fr}
  .company-meta-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
  .company-hero-top{flex-direction:column}
  .company-meta-grid{grid-template-columns:1fr}
}
</style>

<!-- Header -->
<div class="company-hero">
    <div class="company-hero-top">
        <div class="company-hero-left">
            <div class="company-initial"><?= strtoupper(substr($company['name'] ?? 'E', 0, 1)) ?></div>
            <div>
                <h1 class="company-name"><?= htmlspecialchars(ucfirst(strtolower($company['name'] ?? ''))) ?></h1>
                <span class="company-status-pill" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>">
                    <?= ucfirst($company['status'] ?? 'prospecto') ?>
                </span>
            </div>
        </div>
        <div class="company-hero-actions">
            <a href="/tasks/create?entity_type=company&entity_id=<?= $company['id'] ?>" class="btn btn-primary">+ Tarea</a>
            <a href="/contacts/create?company_id=<?= $company['id'] ?>" class="btn btn-secondary">+ Contacto</a>
            <a href="/companies/<?= $company['id'] ?>/edit" class="btn btn-secondary">✏️ Editar</a>
            <a href="/companies" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="company-meta-grid">
        <div class="company-meta-item">
            <span class="company-meta-label">Email</span>
            <span class="company-meta-value">
                <?= !empty($company['email']) ? '<a href="mailto:'.htmlspecialchars($company['email']).'">'.htmlspecialchars($company['email']).'</a>' : '—' ?>
            </span>
        </div>
        <div class="company-meta-item">
            <span class="company-meta-label">Teléfono</span>
            <span class="company-meta-value"><?= htmlspecialchars($company['phone'] ?? '—') ?></span>
        </div>
        <div class="company-meta-item">
            <span class="company-meta-label">Sector</span>
            <span class="company-meta-value"><?= htmlspecialchars(ucfirst(strtolower($company['sector'] ?? '—'))) ?></span>
        </div>
        <div class="company-meta-item">
            <span class="company-meta-label">Ciudad</span>
            <span class="company-meta-value"><?= htmlspecialchars(ucfirst(strtolower($company['city'] ?? '—'))) ?></span>
        </div>
        <div class="company-meta-item">
            <span class="company-meta-label">Provincia</span>
            <span class="company-meta-value"><?= htmlspecialchars(ucfirst(strtolower($company['province'] ?? '—'))) ?></span>
        </div>
        <div class="company-meta-item">
            <span class="company-meta-label">CIF</span>
            <span class="company-meta-value"><?= htmlspecialchars($company['tax_id'] ?? '—') ?></span>
        </div>
    </div>
</div>

<div class="company-layout">

    <!-- Columna principal -->
    <div class="company-main">

        <!-- Tareas -->
        <div class="section-card">
            <div class="section-head">
                <h3>Tareas (<?= count($tasks) ?>)</h3>
                <a href="/tasks/create?entity_type=company&entity_id=<?= $company['id'] ?>" class="btn-sm">+ Nueva</a>
            </div>
            <?php if (empty($tasks)): ?>
                <div class="section-empty">No hay tareas para esta empresa</div>
            <?php else: ?>
                <?php foreach ($tasks as $task):
                    $overdue = !empty($task['due_date']) && strtotime($task['due_date']) < time() && $task['status'] !== 'completada';
                ?>
                <div class="task-row-s" style="<?= $overdue ? 'background:#fff8f8' : '' ?>">
                    <div class="task-icon"><?= $typeIcons[$task['type'] ?? 'otro'] ?? '📌' ?></div>
                    <div class="task-info">
                        <div class="task-title-s"><?= htmlspecialchars($task['title']) ?></div>
                        <div class="task-meta-s">
                            <span class="lead-status-badge <?= $task['status'] === 'completada' ? 'ls-interesado' : ($overdue ? 'ls-no_interesado' : 'ls-pendiente_contacto') ?>">
                                <?= ucfirst($task['status']) ?>
                            </span>
                            · <?= ucfirst($task['priority'] ?? 'media') ?>
                            <?php if (!empty($task['due_date'])): ?>
                                · 📅 <?= date('d/m/y', strtotime($task['due_date'])) ?>
                            <?php endif; ?>
                            <?php if (!empty($task['notes'])): ?>
                                · <span title="<?= htmlspecialchars($task['notes']) ?>">📝</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="display:flex;gap:6px;flex-shrink:0">
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn-sm">Editar</a>
                        <?php if ($task['status'] !== 'completada'): ?>
                        <form method="POST" action="/tasks/<?= $task['id'] ?>/complete" style="display:inline">
                            <button class="btn-sm" style="color:var(--success)">✓</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Contratos -->
        <div class="section-card">
            <div class="section-head">
                <h3>Contratos (<?= count($companyDetail['contracts'] ?? []) ?>)</h3>
                <a href="/contracts/create?company_id=<?= $company['id'] ?>" class="btn-sm">+ Nuevo</a>
            </div>
            <?php if (empty($companyDetail['contracts'])): ?>
                <div class="section-empty">No hay contratos con esta empresa</div>
            <?php else: ?>
                <?php foreach ($companyDetail['contracts'] as $ct): ?>
                <div class="task-row-s">
                    <div class="task-icon">📋</div>
                    <div class="task-info">
                        <div class="task-title-s">
                            <a href="/contracts/<?= $ct['id'] ?>" style="color:var(--text-main)">
                                <?= htmlspecialchars(ucfirst(strtolower($ct['title']))) ?>
                            </a>
                        </div>
                        <div class="task-meta-s">
                            <?= htmlspecialchars(ucfirst(str_replace('_',' ',$ct['service_type']))) ?>
                            · <?= $ct['workers_assigned'] ?> trabajador<?= $ct['workers_assigned'] != 1 ? 'es' : '' ?>
                            <?php if (!$empty($ct['end_date'])): ?>
                                · hasta <?= date('d/m/y', strtotime($ct['end_date'])) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="lead-status-badge <?= $ct['status'] === 'activo' ? 'ls-interesado' : 'ls-no_interesado' ?>"><?= ucfirst($ct['status']) ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    <!-- Contactos -->
        <div class="section-card">
            <div class="section-head">
                <h3>Contactos (<?= count($contacts) ?>)</h3>
                <a href="/contacts/create?company_id=<?= $company['id'] ?>" class="btn-sm">+ Nuevo</a>
            </div>
            <?php if (empty($contacts)): ?>
                <div class="section-empty">No hay contactos registrados</div>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                <div class="contact-row">
                    <div class="contact-avatar"><?= strtoupper(substr($contact['full_name'] ?? 'C', 0, 1)) ?></div>
                    <div class="contact-info">
                        <div class="contact-name">
                            <a href="/contacts/<?= $contact['id'] ?>" style="color:var(--text-main)">
                                <?= htmlspecialchars(ucwords(strtolower($contact['full_name'] ?? ''))) ?>
                            </a>
                            <?php if (!empty($contact['is_primary'])): ?>
                                <span class="lead-status-badge ls-interesado" style="font-size:10px;margin-left:4px">Principal</span>
                            <?php endif; ?>
                        </div>
                        <div class="contact-sub">
                            <?= htmlspecialchars(ucfirst(strtolower($contact['job_title'] ?? ''))) ?>
                            <?php if (!empty($contact['email'])): ?> · <?= htmlspecialchars($contact['email']) ?><?php endif; ?>
                            <?php if (!empty($contact['phone'])): ?> · <?= htmlspecialchars($contact['phone']) ?><?php endif; ?>
                        </div>
                    </div>
                    <a href="/contacts/<?= $contact['id'] ?>/edit" class="btn-sm">Editar</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Timeline lateral -->
    <div>
        <div class="section-card">
            <div class="section-head">
                <h3>Historial de actividad</h3>
                <span style="font-size:11px;color:var(--text-soft)"><?= count($timeline) ?> eventos</span>
            </div>

            <?php if (empty($timeline)): ?>
                <div class="section-empty">Sin actividad registrada</div>
            <?php else: ?>
            <div class="timeline">
                <?php foreach ($timeline as $item):
                    $type = $item['event_type'] ?? 'activity';
                    $icon = match($type) {
                        'task'    => $typeIcons[$item['type'] ?? 'otro'] ?? '📌',
                        'contact' => '👤',
                        default   => match($item['action'] ?? '') {
                            'created'          => '🏢',
                            'updated'          => '✏️',
                            'created_from_lead'=> '🔄',
                            default            => '📋',
                        }
                    };
                    $dotClass = match($type) {
                        'task'    => 'tl-task',
                        'contact' => 'tl-contact',
                        default   => 'tl-activity',
                    };
                    $title = match($type) {
                        'task'    => ucfirst($item['type'] ?? 'Tarea') . ': ' . ($item['description'] ?? ''),
                        'contact' => 'Contacto añadido: ' . ($item['description'] ?? ''),
                        default   => $item['description'] ?? '',
                    };
                    $date = $item['completed_at'] ?? $item['created_at'] ?? '';
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $dotClass ?>"><?= $icon ?></div>
                    <div class="timeline-body">
                        <div class="timeline-title"><?= htmlspecialchars(ucfirst(strtolower($title))) ?></div>
                        <?php if ($type === 'task' && !empty($item['notes'])): ?>
                            <div class="timeline-desc"><?= htmlspecialchars($item['notes']) ?></div>
                        <?php endif; ?>
                        <?php if ($type === 'contact' && !empty($item['job_title'])): ?>
                            <div class="timeline-desc"><?= htmlspecialchars(ucfirst(strtolower($item['job_title']))) ?></div>
                        <?php endif; ?>
                        <div class="timeline-time">
                            <?php if (!empty($date)): ?>
                                <span><?= date('d/m/Y H:i', strtotime($date)) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['user_name'])): ?>
                                · <span class="timeline-user"><?= htmlspecialchars($item['user_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php
$lead     = $leadDetail['lead'];
$notes         = $leadDetail['notes'];
$timeline      = $leadDetail['timeline'];
$tasks         = $leadDetail['tasks'] ?? [];
$statusHistory = $leadDetail['statusHistory'] ?? [];

$statusColors = [
    'nuevo'              => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
    'pendiente_contacto' => ['bg'=>'#fefce8','color'=>'#a16207'],
    'en_seguimiento'     => ['bg'=>'#faf5ff','color'=>'#7e22ce'],
    'cualificado'        => ['bg'=>'#f0fdfa','color'=>'#0f766e'],
    'interesado'         => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'no_interesado'      => ['bg'=>'#f9fafb','color'=>'#6b7280'],
    'convertido'         => ['bg'=>'#f0fdf4','color'=>'#15803d'],
];
$priorityColors = ['urgente'=>'#ef4444','alta'=>'#f97316','media'=>'#eab308','baja'=>'#9ca3af'];
$sc = $statusColors[$lead['status'] ?? 'nuevo'] ?? $statusColors['nuevo'];
$typeIcons = ['llamada'=>'📞','email'=>'📧','visita'=>'🚗','propuesta'=>'📄','reunion'=>'👥','seguimiento'=>'🔄','otro'=>'📌'];
?>
<style>
.lead-layout{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start}
.lead-main{min-width:0}

/* Hero */
.lead-hero{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.lead-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.lead-hero-left{display:flex;align-items:center;gap:16px}
.lead-initial{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#6366f122,#6366f155);color:#6366f1;font-size:22px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.lead-company{font-size:20px;font-weight:700;color:var(--text-main);margin:0 0 6px;letter-spacing:-.3px}
.lead-badges{display:flex;gap:6px;flex-wrap:wrap}
.lead-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600}
.lead-priority-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:#f9fafb;color:var(--text-main)}
.lead-hero-actions{display:flex;gap:8px;flex-wrap:wrap;flex-shrink:0}
.lead-meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.lead-meta-item{display:flex;flex-direction:column;gap:3px}
.lead-meta-label{font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.4px}
.lead-meta-value{font-size:13px;color:var(--text-main);font-weight:500}

/* Cambiar estado */
.status-bar{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.status-bar h3{margin:0 0 12px;font-size:13px;font-weight:700;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px}
.status-steps{display:flex;gap:6px;flex-wrap:wrap}
.status-step{height:32px;padding:0 14px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all .15s;border:1.5px solid transparent}
.status-step:hover{filter:brightness(.95)}
.status-step.current{border-color:currentColor}

/* Secciones */
.ls-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow-soft)}
.ls-head{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border-soft)}
.ls-head h3{margin:0;font-size:14px;font-weight:700}
.ls-empty{padding:24px 20px;text-align:center;font-size:13px;color:var(--text-light)}

/* Notas */
.note-form{padding:16px 20px;border-bottom:1px solid var(--border-soft)}
.note-form textarea{width:100%;border:1px solid var(--border);border-radius:10px;padding:10px 13px;font-size:13px;resize:vertical;outline:none;font-family:inherit}
.note-form textarea:focus{border-color:var(--primary)}
.note-form-actions{display:flex;justify-content:flex-end;margin-top:8px}
.note-item{display:flex;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border-soft)}
.note-item:last-child{border-bottom:none}
.note-avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#2f80ed,#6ab0ff);color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.note-body{flex:1;min-width:0}
.note-meta{font-size:11px;color:var(--text-soft);margin-bottom:4px}
.note-text{font-size:13px;color:var(--text-main);line-height:1.5}

/* Tareas */
.task-row-l{display:flex;align-items:center;gap:10px;padding:12px 20px;border-bottom:1px solid var(--border-soft)}
.task-row-l:last-child{border-bottom:none}

/* Timeline */
.tl-item{display:flex;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border-soft)}
.tl-item:last-child{border-bottom:none}
.tl-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;margin-top:2px}
.tl-body{flex:1;min-width:0}
.tl-title{font-size:13px;font-weight:600;color:var(--text-main)}
.tl-time{font-size:11px;color:var(--text-light);margin-top:3px}

@media(max-width:1000px){.lead-layout{grid-template-columns:1fr}}
@media(max-width:600px){.lead-meta-grid{grid-template-columns:repeat(2,1fr)}.lead-hero-top{flex-direction:column}}
</style>

<!-- Hero -->
<div class="lead-hero">
    <div class="lead-hero-top">
        <div class="lead-hero-left">
            <div class="lead-initial"><?= strtoupper(substr($lead['company_name'] ?: $lead['full_name'], 0, 1)) ?></div>
            <div>
                <h1 class="lead-company"><?= htmlspecialchars(ucfirst(strtolower($lead['company_name'] ?: $lead['full_name']))) ?></h1>
                <div class="lead-badges">
                    <span class="lead-badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>">
                        <?= ucfirst(str_replace('_', ' ', $lead['status'] ?? 'nuevo')) ?>
                    </span>
                    <span class="lead-priority-badge">
                        <span style="width:7px;height:7px;border-radius:50%;background:<?= $priorityColors[$lead['priority'] ?? 'media'] ?>;display:inline-block"></span>
                        <?= ucfirst($lead['priority'] ?? 'media') ?>
                    </span>
                    <?php if (!empty($lead['temperature'])): ?>
                    <span class="lead-badge" style="background:#fff8e8;color:#92400e">
                        <?= ucfirst($lead['temperature']) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="lead-hero-actions">
            <a href="/tasks/create?entity_type=lead&entity_id=<?= $lead['id'] ?>" class="btn btn-secondary">+ Tarea</a>
            <a href="/leads/<?= $lead['id'] ?>/edit" class="btn btn-secondary">✏️ Editar</a>
            <?php if (($lead['status'] ?? '') !== 'convertido'): ?>
            <a href="/leads/<?= $lead['id'] ?>/convert" class="btn btn-primary">🔄 Convertir</a>
            <?php else: ?>
            <?php if (!empty($lead['converted_to_company_id'])): ?>
            <a href="/companies/<?= $lead['converted_to_company_id'] ?>" class="btn btn-secondary">🏢 Ver empresa</a>
            <?php endif; ?>
            <?php endif; ?>
            <a href="/leads" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="lead-meta-grid">
        <div class="lead-meta-item">
            <span class="lead-meta-label">Contacto</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucwords(strtolower($lead['full_name'] ?? '—'))) ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Email</span>
            <span class="lead-meta-value">
                <?= !empty($lead['email']) ? '<a href="mailto:'.htmlspecialchars($lead['email']).'">'.htmlspecialchars($lead['email']).'</a>' : '—' ?>
            </span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Teléfono</span>
            <span class="lead-meta-value">
                <?= !empty($lead['phone']) ? '<a href="tel:'.htmlspecialchars($lead['phone']).'">'.htmlspecialchars($lead['phone']).'</a>' : '—' ?>
            </span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Cargo</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucfirst(strtolower($lead['job_title'] ?? '—'))) ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Origen</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $lead['source'] ?? '—'))) ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Servicio de interés</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $lead['service_interest'] ?? '—'))) ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Próxima acción</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucfirst(strtolower($lead['next_action'] ?? '—'))) ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Alta</span>
            <span class="lead-meta-value"><?= !empty($lead['created_at']) ? date('d/m/Y', strtotime($lead['created_at'])) : '—' ?></span>
        </div>
        <div class="lead-meta-item">
            <span class="lead-meta-label">Ciudad</span>
            <span class="lead-meta-value"><?= htmlspecialchars(ucfirst(strtolower($lead['city'] ?? '—'))) ?></span>
        </div>
    </div>
</div>

<!-- Cambiar estado rápido -->
<?php if (($lead['status'] ?? '') !== 'convertido'): ?>
<div class="status-bar">
    <h3>Cambiar estado</h3>
    <div class="status-steps">
        <?php
        $statuses = [
            'nuevo'              => ['label'=>'Nuevo',              'bg'=>'#eff6ff','color'=>'#1d4ed8'],
            'pendiente_contacto' => ['label'=>'Pendiente contacto', 'bg'=>'#fefce8','color'=>'#a16207'],
            'en_seguimiento'     => ['label'=>'En seguimiento',     'bg'=>'#faf5ff','color'=>'#7e22ce'],
            'cualificado'        => ['label'=>'Cualificado',        'bg'=>'#f0fdfa','color'=>'#0f766e'],
            'interesado'         => ['label'=>'Interesado',         'bg'=>'#f0fdf4','color'=>'#15803d'],
            'no_interesado'      => ['label'=>'No interesado',      'bg'=>'#f9fafb','color'=>'#6b7280'],
        ];
        foreach ($statuses as $val => $s):
            $isCurrent = ($lead['status'] ?? 'nuevo') === $val;
        ?>
        <form method="POST" action="/leads/<?= $lead['id'] ?>/status" style="display:inline">
            <input type="hidden" name="status" value="<?= $val ?>">
            <button class="status-step <?= $isCurrent ? 'current' : '' ?>"
                    style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>"
                    <?= $isCurrent ? 'disabled' : '' ?>>
                <?= $s['label'] ?>
            </button>
        </form>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="lead-layout">
    <div class="lead-main">

        <!-- Notas -->
        <div class="ls-card">
            <div class="ls-head">
                <h3>Notas (<?= count($notes) ?>)</h3>
            </div>
            <div class="note-form">
                <form method="POST" action="/leads/<?= $lead['id'] ?>/notes">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                            <textarea name="note" rows="2" placeholder="Añadir una nota..."></textarea>
                    <div class="note-form-actions">
                        <button type="submit" class="btn btn-primary" style="height:34px;font-size:13px">Guardar nota</button>
                    </div>
                </form>
            </div>
            <?php if (empty($notes)): ?>
                <div class="ls-empty">Sin notas todavía</div>
            <?php else: ?>
                <?php
                $currentUserId = \App\Core\Auth::id();
                foreach ($notes as $note):
                    $isOwner = ($note['user_id'] ?? null) == $currentUserId;
                ?>
                <div class="note-item" id="note-<?= $note['id'] ?>">
                    <div class="note-avatar"><?= strtoupper(substr($note['first_name'] ?? 'U', 0, 1)) ?></div>
                    <div class="note-body" style="flex:1">
                        <div class="note-meta" style="display:flex;align-items:center;justify-content:space-between">
                            <span>
                                <strong><?= htmlspecialchars(trim(($note['first_name'] ?? '') . ' ' . ($note['last_name'] ?? ''))) ?></strong>
                                · <?= !empty($note['created_at']) ? date('d/m/Y H:i', strtotime($note['created_at'])) : '' ?>
                            </span>
                            <?php if ($isOwner): ?>
                            <span style="display:flex;gap:6px">
                                <button onclick="editNote(<?= $note['id'] ?>)" class="btn-sm" style="font-size:11px">✏️</button>
                                <form method="POST" action="/lead-note/<?= $note['id'] ?>/delete" style="display:inline" onsubmit="return confirm('¿Eliminar nota?')">
                                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                                    <button class="btn-sm" style="font-size:11px;color:var(--danger)">🗑</button>
                                </form>
                            </span>
                            <?php endif; ?>
                        </div>
                        <!-- Vista normal -->
                        <div class="note-text" id="note-text-<?= $note['id'] ?>"><?= nl2br(htmlspecialchars($note['note'])) ?></div>
                        <!-- Formulario edición (oculto) -->
                        <form method="POST" action="/lead-note/<?= $note['id'] ?>/update"
                              id="note-form-<?= $note['id'] ?>" style="display:none;margin-top:8px">
                            <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                            <textarea name="note" rows="2" style="width:100%;border:1px solid var(--primary);border-radius:8px;padding:8px;font-size:13px;font-family:inherit"><?= htmlspecialchars($note['note']) ?></textarea>
                            <div style="display:flex;gap:6px;margin-top:6px">
                                <button type="submit" class="btn btn-primary" style="height:30px;font-size:12px">Guardar</button>
                                <button type="button" onclick="cancelEdit(<?= $note['id'] ?>)" class="btn btn-secondary" style="height:30px;font-size:12px">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Tareas -->
        <div class="ls-card">
            <div class="ls-head">
                <h3>Tareas (<?= count($tasks) ?>)</h3>
                <a href="/tasks/create?entity_type=lead&entity_id=<?= $lead['id'] ?>" class="btn-sm">+ Nueva</a>
            </div>
            <?php if (empty($tasks)): ?>
                <div class="ls-empty">No hay tareas para este lead</div>
            <?php else: ?>
                <?php foreach ($tasks as $task):
                    $overdue = !empty($task['due_date']) && strtotime($task['due_date']) < time() && $task['status'] !== 'completada';
                ?>
                <div class="task-row-l" style="<?= $overdue ? 'background:#fff8f8' : '' ?>">
                    <div style="width:30px;height:30px;border-radius:7px;background:var(--bg-muted);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0">
                        <?= $typeIcons[$task['type'] ?? 'otro'] ?? '📌' ?>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600"><?= htmlspecialchars($task['title']) ?></div>
                        <div style="font-size:11px;color:var(--text-soft);margin-top:2px">
                            <span class="lead-status-badge <?= $task['status'] === 'completada' ? 'ls-interesado' : ($overdue ? 'ls-no_interesado' : 'ls-pendiente_contacto') ?>">
                                <?= ucfirst($task['status']) ?>
                            </span>
                            <?php if (!empty($task['due_date'])): ?>
                                · 📅 <?= date('d/m/y', strtotime($task['due_date'])) ?>
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

    </div>

        <!-- Historial de estados -->
        <?php if (!empty($statusHistory)): ?>
        <div class="ls-card">
            <div class="ls-head">
                <h3>Historial de estados (<?= count($statusHistory) ?>)</h3>
            </div>
            <?php
            $statusLabels = [
                'nuevo'=>'Nuevo','pendiente_contacto'=>'Pendiente contacto',
                'en_seguimiento'=>'En seguimiento','cualificado'=>'Cualificado',
                'interesado'=>'Interesado','no_interesado'=>'No interesado','convertido'=>'Convertido'
            ];
            $statusBg = [
                'nuevo'=>'#eff6ff','pendiente_contacto'=>'#fefce8','en_seguimiento'=>'#faf5ff',
                'cualificado'=>'#f0fdfa','interesado'=>'#f0fdf4','no_interesado'=>'#f9fafb','convertido'=>'#f0fdf4'
            ];
            $statusColor = [
                'nuevo'=>'#1d4ed8','pendiente_contacto'=>'#a16207','en_seguimiento'=>'#7e22ce',
                'cualificado'=>'#0f766e','interesado'=>'#15803d','no_interesado'=>'#6b7280','convertido'=>'#15803d'
            ];
            foreach ($statusHistory as $h):
                $to = $h['to_status'] ?? '';
                $from = $h['from_status'] ?? null;
            ?>
            <div class="note-item">
                <div class="note-avatar" style="background:#f1f5f9;color:var(--text-soft);font-size:13px">→</div>
                <div class="note-body">
                    <div class="note-meta">
                        <strong><?= htmlspecialchars($h['user_name'] ?? 'Sistema') ?></strong>
                        · <?= !empty($h['created_at']) ? date('d/m/Y H:i', strtotime($h['created_at'])) : '' ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap">
                        <?php if ($from): ?>
                        <span class="lead-status-badge" style="background:<?= $statusBg[$from] ?? '#f9fafb' ?>;color:<?= $statusColor[$from] ?? '#6b7280' ?>"><?= $statusLabels[$from] ?? ucfirst($from) ?></span>
                        <span style="color:var(--text-soft);font-size:12px">→</span>
                        <?php endif; ?>
                        <span class="lead-status-badge" style="background:<?= $statusBg[$to] ?? '#f9fafb' ?>;color:<?= $statusColor[$to] ?? '#6b7280' ?>"><?= $statusLabels[$to] ?? ucfirst($to) ?></span>
                    </div>
                    <?php if (!empty($h['comment'])): ?>
                    <div class="note-text" style="margin-top:4px"><?= htmlspecialchars($h['comment']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    <!-- Timeline lateral -->
    <div>
        <?php if (!empty($lead['notes_internal'])): ?>
        <div class="ls-card" style="margin-bottom:16px">
            <div class="ls-head"><h3>Notas internas</h3></div>
            <div style="padding:14px 20px;font-size:13px;color:var(--text-main);line-height:1.6">
                <?= nl2br(htmlspecialchars($lead['notes_internal'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="ls-card">
            <div class="ls-head">
                <h3>Historial</h3>
                <span style="font-size:11px;color:var(--text-soft)"><?= count($timeline) ?> eventos</span>
            </div>
            <?php if (empty($timeline)): ?>
                <div class="ls-empty">Sin actividad registrada</div>
            <?php else: ?>
                <?php foreach ($timeline as $item): ?>
                <div class="tl-item">
                    <div class="tl-dot" style="background:#eff6ff;font-size:13px">
                        <?php
                        echo match($item['action'] ?? '') {
                            'created'       => '🌱',
                            'updated'       => '✏️',
                            'status_change' => '🔄',
                            'note_added'    => '📝',
                            default         => '📋',
                        };
                        ?>
                    </div>
                    <div class="tl-body">
                        <div class="tl-title"><?= htmlspecialchars(ucfirst($item['description'] ?? '')) ?></div>
                        <div class="tl-time">
                            <?= !empty($item['created_at']) ? date('d/m/Y H:i', strtotime($item['created_at'])) : '' ?>
                            <?php if (!empty($item['user_name'])): ?>
                                · <strong><?= htmlspecialchars($item['user_name']) ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function editNote(id) {
    document.getElementById('note-text-' + id).style.display = 'none';
    document.getElementById('note-form-' + id).style.display = 'block';
}
function cancelEdit(id) {
    document.getElementById('note-text-' + id).style.display = 'block';
    document.getElementById('note-form-' + id).style.display = 'none';
}
</script>
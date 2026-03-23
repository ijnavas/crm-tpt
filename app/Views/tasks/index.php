<?php
$typeIcons = [
    'llamada'    => '📞',
    'email'      => '📧',
    'visita'     => '🚗',
    'propuesta'  => '📄',
    'reunion'    => '👥',
    'seguimiento'=> '🔄',
    'otro'       => '📌',
];
$priorityClass = ['urgente'=>'lp-urgente','alta'=>'lp-alta','media'=>'lp-media','baja'=>'lp-baja'];
$statusClass   = ['pendiente'=>'ls-pendiente_contacto','en_curso'=>'ls-en_seguimiento','completada'=>'ls-interesado'];
?>
<style>
/* ── Tareas ── */
.tasks-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.tasks-view-tabs{display:flex;gap:6px}
.tasks-tab{height:34px;padding:0 14px;border:1px solid var(--border);border-radius:8px;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:var(--text-soft);text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.tasks-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}
.tasks-tab:hover:not(.active){background:var(--bg-muted)}

/* Recordatorios */
.reminder-bar{display:flex;align-items:center;gap:8px;background:#fff8e8;border:1px solid #fcd34d;border-radius:10px;padding:10px 16px;margin-bottom:14px;font-size:13px;color:#92400e}
.reminder-bar strong{font-weight:700}

/* Lista */
.task-row{display:flex;align-items:center;gap:12px;padding:13px 18px;border-bottom:1px solid var(--border-soft);transition:background .1s}
.task-row:last-child{border-bottom:none}
.task-row:hover{background:#f7f9fc}
.task-row.overdue{background:#fff8f8}
.task-type-icon{width:34px;height:34px;border-radius:8px;background:var(--bg-muted);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.task-main{flex:1;min-width:0}
.task-title{font-size:13px;font-weight:600;color:var(--text-main);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.task-meta{font-size:11px;color:var(--text-soft);margin-top:2px;display:flex;align-items:center;gap:8px}
.task-dates{display:flex;flex-direction:column;align-items:flex-end;font-size:11px;color:var(--text-soft);white-space:nowrap;flex-shrink:0}
.task-dates.overdue{color:var(--danger);font-weight:600}
.task-actions{display:flex;gap:6px;flex-shrink:0}

/* Agrupada */
.entity-group{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:14px}
.entity-group-head{display:flex;align-items:center;justify-content:space-between;padding:13px 18px;background:#fafbfc;border-bottom:1px solid var(--border-soft);cursor:pointer;user-select:none}
.entity-group-head:hover{background:#f0f4f8}
.entity-group-title{font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px}
.entity-type-pill{font-size:10px;font-weight:600;padding:2px 7px;border-radius:999px;background:#eff6ff;color:#1d4ed8}
.entity-type-pill.lead{background:#fdf4ff;color:#7e22ce}
.entity-type-pill.contact{background:#f0fdf4;color:#15803d}
.entity-group-count{font-size:11px;color:var(--text-soft)}
.entity-group-body{display:none}
.entity-group-body.open{display:block}

@media(max-width:600px){
  .task-dates{display:none}
  .tasks-toolbar{flex-direction:column;align-items:stretch}
}
</style>

<section class="page-header">
    <div>
        <h1>Tareas</h1>
        <p>Seguimiento comercial</p>
    </div>
    <a href="/tasks/create" class="btn btn-primary">+ Nueva tarea</a>
</section>

<!-- Recordatorios próximos 24h -->
<?php if (!empty($reminders)): ?>
<div class="reminder-bar">
    🔔 <strong><?= count($reminders) ?> recordatorio<?= count($reminders) > 1 ? 's' : '' ?></strong> en las próximas 24h:
    <?php foreach ($reminders as $r): ?>
        <span>· <?= htmlspecialchars($r['title']) ?> (<?= date('H:i', strtotime($r['reminder_at'])) ?>h)</span>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Toolbar con filtros y tabs de vista -->
<div class="tasks-toolbar">
    <form method="GET" action="/tasks" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">

        <select name="type" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <?php foreach ($catalogs['types'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['type'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach ($catalogs['statuses'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['status'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="priority" class="lf-select" onchange="this.form.submit()">
            <option value="">Todas las prioridades</option>
            <?php foreach ($catalogs['priorities'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['priority'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($filters['type']) || !empty($filters['status']) || !empty($filters['priority'])): ?>
            <a href="/tasks?view=<?= $view ?>" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="tasks-view-tabs">
        <a href="/tasks?view=list<?= !empty($filters['status']) ? '&status='.$filters['status'] : '' ?>"
           class="tasks-tab <?= $view !== 'grouped' ? 'active' : '' ?>">☰ Lista</a>
        <a href="/tasks?view=grouped"
           class="tasks-tab <?= $view === 'grouped' ? 'active' : '' ?>">⊞ Por empresa</a>
    </div>
</div>

<?php if ($view === 'grouped'): ?>
<!-- VISTA AGRUPADA -->
<?php if (empty($grouped)): ?>
    <div style="text-align:center;padding:48px;color:var(--text-light);font-size:14px">No hay tareas pendientes</div>
<?php else: ?>
    <?php foreach ($grouped as $group): ?>
    <div class="entity-group">
        <div class="entity-group-head" onclick="toggleGroup(this)">
            <div class="entity-group-title">
                <span class="entity-type-pill <?= $group['entity_type'] ?>">
                    <?= ['company'=>'Empresa','lead'=>'Lead','contact'=>'Contacto'][$group['entity_type']] ?? $group['entity_type'] ?>
                </span>
                <?= htmlspecialchars(ucfirst(strtolower($group['entity_name']))) ?>
            </div>
            <span class="entity-group-count"><?= count($group['tasks']) ?> tarea<?= count($group['tasks']) > 1 ? 's' : '' ?> · <span class="toggle-arrow">▼</span></span>
        </div>
        <div class="entity-group-body open">
            <?php foreach ($group['tasks'] as $task):
                $overdue = !empty($task['due_date']) && strtotime($task['due_date']) < time();
            ?>
            <div class="task-row <?= $overdue ? 'overdue' : '' ?>">
                <div class="task-type-icon"><?= $typeIcons[$task['type'] ?? 'otro'] ?? '📌' ?></div>
                <div class="task-main">
                    <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                    <div class="task-meta">
                        <span class="lead-status-badge <?= $statusClass[$task['status']] ?? 'ls-nuevo' ?>">
                            <?= ucfirst($task['status'] ?? '') ?>
                        </span>
                        <span class="lead-priority-dot <?= $priorityClass[$task['priority'] ?? 'media'] ?>"></span>
                        <?= ucfirst($task['priority'] ?? '') ?>
                        <?php if (!empty($task['notes'])): ?>
                            · <span title="<?= htmlspecialchars($task['notes']) ?>">📝</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="task-dates <?= $overdue ? 'overdue' : '' ?>">
                    <?php if (!empty($task['due_date'])): ?>
                        <span>📅 <?= date('d/m/y H:i', strtotime($task['due_date'])) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($task['reminder_at'])): ?>
                        <span>🔔 <?= date('d/m/y H:i', strtotime($task['reminder_at'])) ?></span>
                    <?php endif; ?>
                </div>
                <div class="task-actions">
                    <a href="/tasks/<?= $task['id'] ?>/edit" class="btn-sm">Editar</a>
                    <?php if ($task['status'] !== 'completada'): ?>
                    <form method="POST" action="/tasks/<?= $task['id'] ?>/complete" style="display:inline">
                        <button class="btn-sm" style="color:var(--success)">✓</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php else: ?>
<!-- VISTA LISTA -->
<section class="card" style="padding:0;overflow:hidden">
    <?php if (empty($tasks)): ?>
        <div style="text-align:center;padding:48px;color:var(--text-light);font-size:14px">No hay tareas con estos filtros</div>
    <?php else: ?>
        <?php foreach ($tasks as $task):
            $overdue = !empty($task['due_date']) && strtotime($task['due_date']) < time() && $task['status'] !== 'completada';
        ?>
        <div class="task-row <?= $overdue ? 'overdue' : '' ?>">
            <div class="task-type-icon"><?= $typeIcons[$task['type'] ?? 'otro'] ?? '📌' ?></div>
            <div class="task-main">
                <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                <div class="task-meta">
                    <span><?= htmlspecialchars(ucfirst(strtolower($task['entity_name'] ?? '—'))) ?></span>
                    ·
                    <span class="lead-status-badge <?= $statusClass[$task['status']] ?? 'ls-nuevo' ?>">
                        <?= ucfirst($task['status'] ?? '') ?>
                    </span>
                    <span class="lead-priority-dot <?= $priorityClass[$task['priority'] ?? 'media'] ?>"></span>
                    <?= ucfirst($task['priority'] ?? '') ?>
                </div>
            </div>
            <div class="task-dates <?= $overdue ? 'overdue' : '' ?>">
                <?php if (!empty($task['due_date'])): ?>
                    <span>📅 <?= date('d/m/y', strtotime($task['due_date'])) ?></span>
                <?php endif; ?>
                <?php if (!empty($task['reminder_at'])): ?>
                    <span>🔔 <?= date('d/m H:i', strtotime($task['reminder_at'])) ?></span>
                <?php endif; ?>
            </div>
            <div class="task-actions">
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
</section>
<?php endif; ?>

<script>
function toggleGroup(head) {
    const body  = head.nextElementSibling;
    const arrow = head.querySelector('.toggle-arrow');
    const open  = body.classList.toggle('open');
    arrow.textContent = open ? '▼' : '▶';
}
</script>
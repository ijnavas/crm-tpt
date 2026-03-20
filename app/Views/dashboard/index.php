<?php
$kpis = $dashboard['kpis'];
$leads = $dashboard['recentLeads'];
$tasks = $dashboard['upcomingTasks'];
$companies = $dashboard['recentCompanies'];
$activity = $dashboard['recentActivity'];

function statusBadge(string $status): string {
    $map = [
        'nuevo'              => ['label' => 'Nuevo',              'class' => 'badge-blue'],
        'pendiente_contacto' => ['label' => 'Pend. contacto',     'class' => 'badge-yellow'],
        'en_seguimiento'     => ['label' => 'Seguimiento',        'class' => 'badge-purple'],
        'cualificado'        => ['label' => 'Cualificado',        'class' => 'badge-teal'],
        'interesado'         => ['label' => 'Interesado',         'class' => 'badge-green'],
        'no_interesado'      => ['label' => 'No interesado',      'class' => 'badge-gray'],
        'convertido'         => ['label' => 'Convertido',         'class' => 'badge-green'],
        'activa'             => ['label' => 'Activa',             'class' => 'badge-green'],
        'prospecto'          => ['label' => 'Prospecto',          'class' => 'badge-yellow'],
        'inactiva'           => ['label' => 'Inactiva',           'class' => 'badge-gray'],
        'pendiente'          => ['label' => 'Pendiente',          'class' => 'badge-yellow'],
        'en_curso'           => ['label' => 'En curso',           'class' => 'badge-blue'],
        'completada'         => ['label' => 'Completada',         'class' => 'badge-green'],
    ];
    $b = $map[$status] ?? ['label' => ucfirst(str_replace('_',' ',$status)), 'class' => 'badge-gray'];
    return '<span class="db-badge ' . $b['class'] . '">' . $b['label'] . '</span>';
}

function priorityDot(string $p): string {
    $map = ['urgente'=>'dot-red','alta'=>'dot-orange','media'=>'dot-yellow','baja'=>'dot-gray'];
    return '<span class="db-dot ' . ($map[$p] ?? 'dot-gray') . '"></span>';
}

function timeAgo(string $dt): string {
    $diff = time() - strtotime($dt);
    if ($diff < 60)     return 'Ahora';
    if ($diff < 3600)   return floor($diff/60) . 'm';
    if ($diff < 86400)  return floor($diff/3600) . 'h';
    if ($diff < 604800) return floor($diff/86400) . 'd';
    return date('d/m', strtotime($dt));
}

function entityIcon(string $type): string {
    return $type === 'company' ? '🏢' : ($type === 'lead' ? '👤' : ($type === 'contact' ? '📋' : '✓'));
}
?>


<style>
.db-wrap{padding:0}
.db-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.db-title{margin:0 0 4px;font-size:24px;font-weight:700;letter-spacing:-.4px}
.db-subtitle{margin:0;font-size:12px;color:var(--text-soft);text-transform:capitalize}
.db-btn-new{display:inline-flex;align-items:center;height:38px;padding:0 18px;background:var(--primary);color:#fff;border-radius:11px;font-size:13px;font-weight:600;white-space:nowrap;flex-shrink:0;text-decoration:none}
.db-btn-new:hover{background:var(--primary-dark);color:#fff}
.db-kpis{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:20px}
.db-kpi{position:relative;background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 16px 14px;text-decoration:none;color:inherit;overflow:hidden;transition:transform .15s,box-shadow .15s;display:block}
.db-kpi:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(15,39,71,.09);color:inherit}
.db-kpi--alert{border-color:#fca5a5!important;background:#fff8f8}
.db-kpi--alert .db-kpi-value{color:var(--danger)}
.db-kpi-label{font-size:11px;font-weight:600;color:var(--text-soft);margin-bottom:8px}
.db-kpi-value{font-size:32px;font-weight:800;letter-spacing:-1px;line-height:1}
.db-kpi-bar{position:absolute;bottom:0;left:0;right:0;height:3px}
.db-kpi-bar--blue{background:var(--primary)}
.db-kpi-bar--teal{background:#0d9488}
.db-kpi-bar--yellow{background:#f59e0b}
.db-kpi-bar--red{background:var(--danger)}
.db-kpi-bar--green{background:var(--success)}
.db-grid{display:grid;grid-template-columns:1.6fr 1fr;grid-template-rows:auto auto;gap:20px;align-items:start}
.db-panel--leads{grid-row:1/3;align-self:start}
.db-panel{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
.db-panel-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px 14px;border-bottom:1px solid var(--border-soft)}
.db-panel-title{font-size:13px;font-weight:700}
.db-panel-link{font-size:11px;color:var(--primary);font-weight:500}
.db-empty{padding:28px 18px;text-align:center;font-size:13px;color:var(--text-light)}
.db-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
.db-table{width:100%;border-collapse:collapse;min-width:420px}
.db-table thead th{padding:9px 18px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);background:#fafbfc;border-bottom:1px solid var(--border-soft);white-space:nowrap}
.db-table tbody tr{cursor:pointer;transition:background .1s}
.db-table tbody tr:hover{background:#f7f9fc}
.db-table tbody td{padding:12px 18px;border-bottom:1px solid var(--border-soft);font-size:13px;vertical-align:middle}
.db-table tbody tr:last-child td{border-bottom:none}
.db-lead-name{font-weight:600;font-size:13px}
.db-lead-sub{font-size:11px;color:var(--text-soft);margin-top:2px}
.db-muted{color:var(--text-soft);font-size:11px;white-space:nowrap}
.db-badge{display:inline-flex;align-items:center;padding:3px 8px;border-radius:999px;font-size:10px;font-weight:600;white-space:nowrap}
.badge-blue{background:#eff6ff;color:#1d4ed8}
.badge-green{background:#f0fdf4;color:#15803d}
.badge-yellow{background:#fefce8;color:#a16207}
.badge-red{background:#fef2f2;color:#b91c1c}
.badge-purple{background:#faf5ff;color:#7e22ce}
.badge-teal{background:#f0fdfa;color:#0f766e}
.badge-gray{background:#f9fafb;color:#6b7280}
.db-dot{display:inline-block;width:7px;height:7px;border-radius:50%;margin-right:5px;flex-shrink:0;vertical-align:middle}
.dot-red{background:#ef4444}
.dot-orange{background:#f97316}
.dot-yellow{background:#eab308}
.dot-gray{background:#9ca3af}
.db-task-list{list-style:none;padding:0;margin:0}
.db-task{display:flex;align-items:center;justify-content:space-between;padding:11px 18px;border-bottom:1px solid var(--border-soft);gap:10px;transition:background .1s;cursor:pointer}
.db-task:last-child{border-bottom:none}
.db-task:hover{background:#f7f9fc}
.db-task--overdue{background:#fff8f8}
.db-task-left{display:flex;align-items:center;gap:8px;min-width:0}
.db-task-title{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px}
.db-task-meta{font-size:10px;color:var(--text-soft);margin-top:2px}
.db-task-date{font-size:11px;font-weight:600;color:var(--text-soft);white-space:nowrap;flex-shrink:0}
.db-task-date--overdue{color:var(--danger)}
.db-company-list{list-style:none;padding:0;margin:0}
.db-company{display:flex;align-items:center;gap:10px;padding:11px 18px;border-bottom:1px solid var(--border-soft);cursor:pointer;transition:background .1s}
.db-company:last-child{border-bottom:none}
.db-company:hover{background:#f7f9fc}
.db-company-avatar{width:34px;height:34px;border-radius:9px;background:#eff6ff;color:var(--primary);font-weight:800;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.db-company-info{flex:1;min-width:0}
.db-company-name{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.db-company-meta{font-size:11px;color:var(--text-soft);margin-top:1px;text-transform:capitalize}
.db-activity-list{list-style:none;padding:0;margin:0}
.db-activity{display:flex;align-items:flex-start;gap:10px;padding:10px 18px;border-bottom:1px solid var(--border-soft)}
.db-activity:last-child{border-bottom:none}
.db-activity-icon{width:28px;height:28px;border-radius:7px;background:var(--bg-muted);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.db-activity-desc{font-size:12px;line-height:1.4}
.db-activity-time{font-size:10px;color:var(--text-light);margin-top:3px}
@media(max-width:1300px){.db-kpis{grid-template-columns:repeat(3,1fr)}}
@media(max-width:900px){.db-grid{grid-template-columns:1fr 1fr}.db-panel--leads{grid-row:auto;grid-column:1/-1}}
@media(max-width:600px){.db-kpis{grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:14px}.db-kpi{padding:14px 14px 12px}.db-kpi-value{font-size:26px}.db-grid{grid-template-columns:1fr}.db-panel--leads{grid-column:auto}.db-title{font-size:20px}.db-task-title{max-width:130px}}
@media(max-width:400px){.db-kpi-value{font-size:22px}}
</style>

<div class="db-wrap">

    <!-- Header -->
    <div class="db-header">
        <div>
            <h1 class="db-title">Dashboard</h1>
            <p class="db-subtitle"><?= date('l, j \d\e F \d\e Y') ?></p>
        </div>
        <a href="/leads/create" class="db-btn-new">+ Nuevo lead</a>
    </div>

    <!-- KPIs -->
    <div class="db-kpis">

        <a href="/leads?period=today" class="db-kpi">
            <div class="db-kpi-label">Leads hoy</div>
            <div class="db-kpi-value"><?= $kpis['leads_today'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--blue"></div>
        </a>

        <a href="/leads?period=week" class="db-kpi">
            <div class="db-kpi-label">Esta semana</div>
            <div class="db-kpi-value"><?= $kpis['leads_week'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--blue"></div>
        </a>

        <a href="/companies?status=activa" class="db-kpi">
            <div class="db-kpi-label">Empresas activas</div>
            <div class="db-kpi-value"><?= $kpis['active_companies'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--teal"></div>
        </a>

        <a href="/tasks?status=pendiente" class="db-kpi">
            <div class="db-kpi-label">Tareas pendientes</div>
            <div class="db-kpi-value"><?= $kpis['pending_tasks'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--yellow"></div>
        </a>

        <a href="/tasks?status=vencida" class="db-kpi <?= $kpis['overdue_tasks'] > 0 ? 'db-kpi--alert' : '' ?>">
            <div class="db-kpi-label">Vencidas</div>
            <div class="db-kpi-value"><?= $kpis['overdue_tasks'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--red"></div>
        </a>

        <a href="/leads?status=convertido" class="db-kpi">
            <div class="db-kpi-label">Convertidos</div>
            <div class="db-kpi-value"><?= $kpis['converted_leads'] ?></div>
            <div class="db-kpi-bar db-kpi-bar--green"></div>
        </a>

    </div>

    <!-- Grid principal -->
    <div class="db-grid">

        <!-- Últimos leads -->
        <div class="db-panel db-panel--leads">
            <div class="db-panel-head">
                <span class="db-panel-title">Últimos leads</span>
                <a href="/leads" class="db-panel-link">Ver todos →</a>
            </div>
            <?php if (empty($leads)): ?>
                <div class="db-empty">Sin leads recientes</div>
            <?php else: ?>
            <div class="db-table-wrap"><table class="db-table">
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Alta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                    <tr onclick="location='./leads/<?= $lead['id'] ?>'" style="cursor:pointer">
                        <td>
                            <div class="db-lead-name"><?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?></div>
                            <?php if ($lead['company_name'] && $lead['full_name']): ?>
                            <div class="db-lead-sub"><?= htmlspecialchars($lead['full_name']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?= statusBadge($lead['status'] ?? 'nuevo') ?></td>
                        <td><?= priorityDot($lead['priority'] ?? 'media') ?> <?= ucfirst($lead['priority'] ?? 'media') ?></td>
                        <td class="db-muted"><?= date('d/m/y', strtotime($lead['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Tareas pendientes -->
        <div class="db-panel">
            <div class="db-panel-head">
                <span class="db-panel-title">Próximas tareas</span>
                <a href="/tasks" class="db-panel-link">Ver todas →</a>
            </div>
            <?php if (empty($tasks)): ?>
                <div class="db-empty">Sin tareas pendientes</div>
            <?php else: ?>
            <ul class="db-task-list">
                <?php foreach ($tasks as $t):
                    $overdue = !empty($t['due_date']) && strtotime($t['due_date']) < time() && $t['status'] !== 'completada';
                ?>
                <li class="db-task <?= $overdue ? 'db-task--overdue' : '' ?>">
                    <div class="db-task-left">
                        <?= priorityDot($t['priority'] ?? 'media') ?>
                        <div>
                            <div class="db-task-title"><?= htmlspecialchars($t['title']) ?></div>
                            <div class="db-task-meta"><?= ucfirst($t['entity_type'] ?? '') ?> #<?= $t['entity_id'] ?></div>
                        </div>
                    </div>
                    <div class="db-task-date <?= $overdue ? 'db-task-date--overdue' : '' ?>">
                        <?= !empty($t['due_date']) ? date('d/m', strtotime($t['due_date'])) : '—' ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Empresas recientes -->
        <div class="db-panel">
            <div class="db-panel-head">
                <span class="db-panel-title">Empresas recientes</span>
                <a href="/companies" class="db-panel-link">Ver todas →</a>
            </div>
            <?php if (empty($companies)): ?>
                <div class="db-empty">Sin empresas recientes</div>
            <?php else: ?>
            <ul class="db-company-list">
                <?php foreach ($companies as $co): ?>
                <li class="db-company" onclick="location='./companies/<?= $co['id'] ?>'" style="cursor:pointer">
                    <div class="db-company-avatar"><?= strtoupper(substr($co['name'], 0, 1)) ?></div>
                    <div class="db-company-info">
                        <div class="db-company-name"><?= htmlspecialchars($co['name']) ?></div>
                        <div class="db-company-meta"><?= htmlspecialchars($co['sector'] ?? '—') ?></div>
                    </div>
                    <?= statusBadge($co['status'] ?? 'prospecto') ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Actividad reciente -->
        <div class="db-panel">
            <div class="db-panel-head">
                <span class="db-panel-title">Actividad reciente</span>
            </div>
            <?php if (empty($activity)): ?>
                <div class="db-empty">Sin actividad reciente</div>
            <?php else: ?>
            <ul class="db-activity-list">
                <?php foreach ($activity as $a): ?>
                <li class="db-activity">
                    <div class="db-activity-icon"><?= entityIcon($a['entity_type'] ?? '') ?></div>
                    <div class="db-activity-body">
                        <div class="db-activity-desc"><?= htmlspecialchars($a['description'] ?? '') ?></div>
                        <div class="db-activity-time"><?= timeAgo($a['created_at']) ?></div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

    </div><!-- /db-grid -->

</div><!-- /db-wrap -->
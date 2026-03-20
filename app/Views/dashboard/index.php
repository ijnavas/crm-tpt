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
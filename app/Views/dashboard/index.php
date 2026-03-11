<section class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Resumen general del CRM</p>
    </div>
</section>

<section class="kpi-grid">

    <div class="card">
        <h3>Leads hoy</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['leads_today'] ?></p>
    </div>

    <div class="card">
        <h3>Leads semana</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['leads_week'] ?></p>
    </div>

    <div class="card">
        <h3>Empresas activas</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['active_companies'] ?></p>
    </div>

    <div class="card">
        <h3>Tareas pendientes</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['pending_tasks'] ?></p>
    </div>

    <div class="card">
        <h3>Tareas vencidas</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['overdue_tasks'] ?></p>
    </div>

    <div class="card">
        <h3>Leads convertidos</h3>
        <p class="kpi-number"><?= $dashboard['kpis']['converted_leads'] ?></p>
    </div>

</section>

<section class="dashboard-columns">

    <div class="card">
        <h2>Últimos leads</h2>

        <?php if (empty($dashboard['recentLeads'])): ?>
            <p>No hay leads recientes.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Próximo contacto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dashboard['recentLeads'] as $lead): ?>
                        <tr>
                            <td>
                                <a href="/leads/<?= $lead['id'] ?>">
                                    <?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($lead['status'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($lead['priority'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($lead['next_contact_at'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Próximas tareas</h2>

        <?php if (empty($dashboard['upcomingTasks'])): ?>
            <p>No hay tareas.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tarea</th>
                        <th>Entidad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dashboard['upcomingTasks'] as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title'] ?? '-') ?></td>
                            <td><?= htmlspecialchars(($task['entity_type'] ?? '-') . ' #' . ($task['entity_id'] ?? '-')) ?></td>
                            <td><?= htmlspecialchars($task['status'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($task['due_date'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</section>

<section class="dashboard-columns">

    <div class="card">
        <h2>Empresas recientes</h2>

        <?php if (empty($dashboard['recentCompanies'])): ?>
            <p>No hay empresas recientes.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Sector</th>
                        <th>Estado</th>
                        <th>Alta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dashboard['recentCompanies'] as $company): ?>
                        <tr>
                            <td>
                                <a href="/companies/<?= $company['id'] ?>">
                                    <?= htmlspecialchars($company['name'] ?? '-') ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($company['sector'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($company['status'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($company['created_at'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Actividad reciente</h2>

        <?php if (empty($dashboard['recentActivity'])): ?>
            <p>No hay actividad reciente.</p>
        <?php else: ?>
            <ul class="activity-list">
                <?php foreach ($dashboard['recentActivity'] as $item): ?>
                    <li>
                        <strong><?= htmlspecialchars($item['description'] ?? '') ?></strong>
                        <br>
                        <small><?= htmlspecialchars($item['created_at'] ?? '') ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</section>
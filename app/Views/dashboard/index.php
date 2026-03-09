<section class="page-header">
    <h1>Dashboard</h1>
    <p>Resumen general del CRM</p>
</section>

<section class="kpi-grid">
    <div class="card">Leads nuevos: <?= $dashboard['kpis']['new_leads'] ?></div>
    <div class="card">Pendientes: <?= $dashboard['kpis']['pending_leads'] ?></div>
    <div class="card">Tareas hoy: <?= $dashboard['kpis']['today_tasks'] ?></div>
    <div class="card">Vencidas: <?= $dashboard['kpis']['overdue_tasks'] ?></div>
</section>

<section class="two-columns">
    <div class="card">
        <h2>Leads recientes</h2>
        <ul>
            <?php foreach ($dashboard['recentLeads'] as $lead): ?>
                <li>
                    <a href="/leads/<?= $lead['id'] ?>">
                        <?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card">
        <h2>Tareas de hoy</h2>
        <ul>
            <?php foreach ($dashboard['todayTasks'] as $task): ?>
                <li><?= htmlspecialchars($task['title']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="card">
    <h2>Actividad reciente</h2>
    <ul>
        <?php foreach ($dashboard['recentActivity'] as $activity): ?>
            <li><?= htmlspecialchars($activity['description']) ?> · <?= htmlspecialchars($activity['created_at']) ?></li>
        <?php endforeach; ?>
    </ul>
</section>

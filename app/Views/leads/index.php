<section class="page-header page-header-flex">
    <div>
        <h1>Leads</h1>
        <p>Gestión de entradas comerciales</p>
    </div>
    <a href="/leads/create" class="btn btn-primary">+ Nuevo lead</a>
</section>

<?php require app_path('Views/leads/partials/filters.php'); ?>

<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Contacto</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leads)): ?>
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-light)">No hay leads con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($leads as $lead): ?>
                <tr>
                    <td>
                        <a href="/leads/<?= $lead['id'] ?>" style="font-weight:600;color:var(--text-main)">
                            <?= htmlspecialchars(ucfirst(strtolower($lead['company_name'] ?? '-'))) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars(ucwords(strtolower($lead['full_name']))) ?></td>
                    <td>
                        <?php $st = $lead['status'] ?? 'nuevo'; ?>
                        <span class="lead-status-badge ls-<?= $st ?>">
                            <?= ucfirst(str_replace('_', ' ', $st)) ?>
                        </span>
                    </td>
                    <td>
                        <?php $pr = $lead['priority'] ?? 'media'; ?>
                        <span class="lead-priority-dot lp-<?= $pr ?>"></span>
                        <?= ucfirst($pr) ?>
                    </td>
                    <td style="color:var(--text-soft);font-size:12px">
                        <?= $lead['created_at'] ? date('d/m/y', strtotime($lead['created_at'])) : '-' ?>
                    </td>
                    <td>
                        <a href="/leads/<?= $lead['id'] ?>" class="btn-sm">Ver</a>
                        <a href="/leads/<?= $lead['id'] ?>/edit" class="btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<section class="page-header page-header-flex">
    <div>
        <h1>Leads</h1>
        <p>Gestión de entradas comerciales</p>
    </div>
    <a href="/leads/create" class="btn btn-primary">Nuevo lead</a>
</section>

<?php require app_path('Views/leads/partials/filters.php'); ?>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Contacto</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leads as $lead): ?>
                <tr>
                    <td>#<?= $lead['id'] ?></td>
                    <td><?= htmlspecialchars($lead['company_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($lead['full_name']) ?></td>
                    <td><?= htmlspecialchars($lead['status']) ?></td>
                    <td><?= htmlspecialchars($lead['priority']) ?></td>
                    <td>
                        <a href="/leads/<?= $lead['id'] ?>">Ver</a>
                        <a href="/leads/<?= $lead['id'] ?>/edit">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

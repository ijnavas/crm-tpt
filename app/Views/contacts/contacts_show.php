<?php $c = $contact; ?>

<section class="page-header">
    <div>
        <h1><?= htmlspecialchars($c['full_name'] ?? 'Contacto') ?></h1>
        <p><?= htmlspecialchars($c['job_title'] ?? '') ?><?= !empty($c['company_name']) ? ' · ' . htmlspecialchars($c['company_name']) : '' ?></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="/tasks/create?entity_type=contact&entity_id=<?= $c['id'] ?>" class="btn btn-primary">+ Nueva tarea</a>
        <a href="/contacts/<?= $c['id'] ?>/edit" class="btn btn-secondary">Editar</a>
        <a href="/contacts" class="btn btn-secondary">Volver</a>
    </div>
</section>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- Datos del contacto -->
    <div class="card">
        <h2>Datos del contacto</h2>
        <table class="table">
            <tr><td><strong>Nombre completo</strong></td><td><?= htmlspecialchars($c['full_name'] ?? '-') ?></td></tr>
            <tr><td><strong>Empresa</strong></td><td>
                <?php if (!empty($c['company_id'])): ?>
                    <a href="/companies/<?= $c['company_id'] ?>"><?= htmlspecialchars($c['company_name'] ?? '-') ?></a>
                <?php else: ?>-<?php endif; ?>
            </td></tr>
            <tr><td><strong>Cargo</strong></td><td><?= htmlspecialchars($c['job_title'] ?? '-') ?></td></tr>
            <tr><td><strong>Departamento</strong></td><td><?= htmlspecialchars($c['department'] ?? '-') ?></td></tr>
            <tr><td><strong>Nivel decisión</strong></td><td><?= htmlspecialchars($c['decision_level'] ?? '-') ?></td></tr>
            <tr><td><strong>Estado</strong></td><td><?= htmlspecialchars($c['status'] ?? '-') ?></td></tr>
            <tr><td><strong>Contacto principal</strong></td><td><?= !empty($c['is_primary']) ? 'Sí' : 'No' ?></td></tr>
        </table>
    </div>

    <!-- Datos de contacto -->
    <div class="card">
        <h2>Información de contacto</h2>
        <table class="table">
            <tr><td><strong>Email</strong></td><td>
                <?= !empty($c['email']) ? '<a href="mailto:' . htmlspecialchars($c['email']) . '">' . htmlspecialchars($c['email']) . '</a>' : '-' ?>
            </td></tr>
            <tr><td><strong>Teléfono</strong></td><td><?= htmlspecialchars($c['phone'] ?? '-') ?></td></tr>
            <tr><td><strong>Móvil</strong></td><td><?= htmlspecialchars($c['mobile'] ?? '-') ?></td></tr>
            <tr><td><strong>Canal preferido</strong></td><td><?= htmlspecialchars($c['preferred_channel'] ?? '-') ?></td></tr>
            <tr><td><strong>Horario contacto</strong></td><td><?= htmlspecialchars($c['contact_schedule'] ?? '-') ?></td></tr>
            <tr><td><strong>Alta</strong></td><td><?= htmlspecialchars($c['created_at'] ?? '-') ?></td></tr>
        </table>
    </div>

</div>

<!-- Notas internas -->
<?php if (!empty($c['notes_internal'])): ?>
<div class="card">
    <h2>Notas internas</h2>
    <p style="margin:0;color:var(--text-main);line-height:1.6;"><?= nl2br(htmlspecialchars($c['notes_internal'])) ?></p>
</div>
<?php endif; ?>

<!-- Tareas -->
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <h2 style="margin:0;">Tareas</h2>
        <a href="/tasks/create?entity_type=contact&entity_id=<?= $c['id'] ?>" class="btn-sm">+ Nueva</a>
    </div>

    <?php if (empty($contact['tasks'])): ?>
        <p style="color:var(--text-soft);">No hay tareas para este contacto.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>Tarea</th><th>Estado</th><th>Prioridad</th><th>Fecha</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($contact['tasks'] as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td><?= htmlspecialchars($task['priority'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($task['due_date'] ?? '-') ?></td>
                    <td>
                        <?php if ($task['status'] !== 'completada'): ?>
                        <form method="POST" action="/tasks/<?= $task['id'] ?>/complete">
                            <button class="btn-sm">Completar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Historial -->
<div class="card">
    <h2>Historial de actividad</h2>

    <?php if (empty($contact['timeline'])): ?>
        <p style="color:var(--text-soft);">No hay actividad registrada.</p>
    <?php else: ?>
        <ul class="activity-list">
            <?php foreach ($contact['timeline'] as $item): ?>
            <li>
                <strong><?= htmlspecialchars($item['description'] ?? '') ?></strong>
                <br><small><?= htmlspecialchars($item['created_at'] ?? '') ?></small>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

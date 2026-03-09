<?php $lead = $leadDetail['lead']; ?>

<section class="page-header page-header-flex">
    <div>
        <h1><?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?></h1>
        <p><?= htmlspecialchars($lead['status']) ?> · <?= htmlspecialchars($lead['priority']) ?></p>
    </div>
    <a href="/leads/<?= $lead['id'] ?>/edit" class="btn btn-secondary">Editar</a>
</section>

<section class="card">
    <h2>Datos</h2>
    <p><strong>Contacto:</strong> <?= htmlspecialchars($lead['full_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($lead['email'] ?? '-') ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($lead['phone'] ?? '-') ?></p>
    <p><strong>Origen:</strong> <?= htmlspecialchars($lead['source'] ?? '-') ?></p>
</section>

<section class="card">
    <h2>Añadir nota</h2>
    <form action="/leads/<?= $lead['id'] ?>/notes" method="POST">
        <textarea name="note" rows="4" required></textarea>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar nota</button>
        </div>
    </form>
</section>

<?php require app_path('Views/leads/partials/notes.php'); ?>
<?php require app_path('Views/leads/partials/timeline.php'); ?>

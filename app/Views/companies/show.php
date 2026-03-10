<?php $company = $companyDetail['company']; ?>

<section class="page-header">
    <div>
        <h1><?= htmlspecialchars($company['name'] ?? 'Empresa') ?></h1>
        <p><?= htmlspecialchars($company['status'] ?? '') ?></p>
    </div>
    <a href="/companies" class="btn btn-secondary">Volver</a>
</section>

<section class="card">
    <h2>Datos generales</h2>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($company['name'] ?? '-') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($company['email'] ?? '-') ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($company['phone'] ?? '-') ?></p>
    <p><strong>Sector:</strong> <?= htmlspecialchars($company['sector'] ?? '-') ?></p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($company['status'] ?? '-') ?></p>
</section>

<section class="card">
    <h2>Contactos</h2>
    <?php if (empty($companyDetail['contacts'])): ?>
        <p>No hay contactos.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($companyDetail['contacts'] as $contact): ?>
                <li><?= htmlspecialchars($contact['full_name'] ?? '') ?> - <?= htmlspecialchars($contact['email'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section class="card">
    <h2>Tareas</h2>
    <?php if (empty($companyDetail['tasks'])): ?>
        <p>No hay tareas.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($companyDetail['tasks'] as $task): ?>
                <li><?= htmlspecialchars($task['title'] ?? '') ?> - <?= htmlspecialchars($task['status'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section class="card">
    <h2>Historial</h2>
    <?php if (empty($companyDetail['timeline'])): ?>
        <p>No hay historial.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($companyDetail['timeline'] as $item): ?>
                <li><?= htmlspecialchars($item['description'] ?? '') ?> · <?= htmlspecialchars($item['created_at'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
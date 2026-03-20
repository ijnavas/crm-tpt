<section class="page-header">
    <div>
        <h1>Empresas</h1>
        <p>Base de cuentas y clientes potenciales</p>
    </div>
    <a href="/companies/create" class="btn btn-primary">Nueva empresa</a>
</section>

<?php if (!empty($filters['status']) || !empty($filters['period'])): ?>
<div style="display:flex;align-items:center;gap:10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 16px;margin-bottom:16px;font-size:13px;color:#1d4ed8">
    🔍 Mostrando: <?php
        if (($filters['status'] ?? '') === 'activa_prospecto') echo 'empresas activas y prospectos';
        elseif (!empty($filters['status'])) echo 'estado: ' . htmlspecialchars($filters['status']);
        elseif (($filters['period'] ?? '') === 'week') echo 'empresas de esta semana';
    ?>
    &nbsp;·&nbsp; <a href="/companies" style="color:#1d4ed8;font-weight:600">Limpiar filtro</a>
</div>
<?php endif; ?>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Sector</th>
                <th>Estado</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td>#<?= $company['id'] ?></td>
                    <td><?= htmlspecialchars($company['name']) ?></td>
                    <td><?= htmlspecialchars($company['sector'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($company['status']) ?></td>
                    <td><?= htmlspecialchars($company['email'] ?? '-') ?></td>
                    <td>
                        <a href="/companies/<?= $company['id'] ?>">Ver</a>
                        <a href="/companies/<?= $company['id'] ?>/edit">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
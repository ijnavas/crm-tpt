<section class="page-header">
    <div>
        <h1>Empresas</h1>
        <p>Base de cuentas y clientes potenciales</p>
    </div>
    <a href="/companies/create" class="btn btn-primary">Nueva empresa</a>
</section>

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
<section class="page-header">
    <div>
        <h1>Contactos</h1>
        <p>Interlocutores de empresas</p>
    </div>
    <a href="/contacts/create" class="btn btn-primary">Nuevo contacto</a>
</section>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Cargo</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td>#<?= $contact['id'] ?></td>
                    <td><?= htmlspecialchars($contact['full_name']) ?></td>
                    <td><?= htmlspecialchars($contact['company_name']) ?></td>
                    <td><?= htmlspecialchars($contact['job_title'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($contact['email'] ?? '-') ?></td>
                    <td>
                        <a href="/contacts/<?= $contact['id'] ?>">Ver</a>
                        <a href="/contacts/<?= $contact['id'] ?>/edit">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
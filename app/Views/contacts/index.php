<section class="page-header">
    <div>
        <h1>Contactos</h1>
        <p>Interlocutores de empresas</p>
    </div>
    <a href="/contacts/create" class="btn btn-primary">+ Nuevo contacto</a>
</section>

<!-- Filtros en una línea -->
<div class="leads-filters">
    <form method="GET" action="/contacts">
        <div class="lf-search-wrap">
            <span class="lf-search-icon">🔍</span>
            <input type="text" name="q" class="lf-input"
                placeholder="Buscar nombre, empresa o email..."
                value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
        </div>

        <select name="status" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach (['activo' => 'Activo', 'inactivo' => 'Inactivo', 'sin_respuesta' => 'Sin respuesta'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['status'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="job_title" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los cargos</option>
            <?php foreach ($jobTitles as $jt): ?>
                <option value="<?= htmlspecialchars($jt) ?>" <?= (($filters['job_title'] ?? '') === $jt) ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst(strtolower($jt))) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="lf-btn-search">Buscar</button>

        <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($filters['period']) || !empty($filters['job_title'])): ?>
            <a href="/contacts" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($filters['period'])): ?>
    <div class="lf-active-filter">
        🔍 Mostrando:
        <?php if ($filters['period'] === 'week') echo 'contactos de esta semana'; ?>
        · <a href="/contacts">Limpiar</a>
    </div>
    <?php endif; ?>
</div>

<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Cargo</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contacts)): ?>
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-light)">No hay contactos con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td style="font-weight:600"><?= htmlspecialchars(ucwords(strtolower($contact['full_name']))) ?></td>
                    <td><?= htmlspecialchars(ucfirst(strtolower($contact['company_name'] ?? '-'))) ?></td>
                    <td><?= htmlspecialchars(ucfirst(strtolower($contact['job_title'] ?? '-'))) ?></td>
                    <td><?= htmlspecialchars($contact['email'] ?? '-') ?></td>
                    <td>
                        <?php $st = $contact['status'] ?? 'activo'; ?>
                        <span class="lead-status-badge <?= $st === 'activo' ? 'ls-interesado' : ($st === 'inactivo' ? 'ls-no_interesado' : 'ls-pendiente_contacto') ?>">
                            <?= ucfirst(str_replace('_', ' ', $st)) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/contacts/<?= $contact['id'] ?>" class="btn-sm">Ver</a>
                        <a href="/contacts/<?= $contact['id'] ?>/edit" class="btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<section class="page-header">
    <div>
        <h1>Empresas</h1>
        <p>Base de cuentas y clientes potenciales</p>
    </div>
    <a href="/companies/create" class="btn btn-primary">+ Nueva empresa</a>
</section>

<!-- Filtros en una línea -->
<div class="leads-filters">
    <form method="GET" action="/companies">
        <div class="lf-search-wrap">
            <span class="lf-search-icon">🔍</span>
            <input type="text" name="q" class="lf-input"
                placeholder="Buscar empresa, email, ciudad..."
                value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
        </div>

        <select name="status" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach ([
                'prospecto' => 'Prospecto',
                'activa'    => 'Activa',
                'inactiva'  => 'Inactiva',
                'bloqueada' => 'Bloqueada',
                'activa_prospecto' => 'Activas y prospectos',
            ] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['status'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="sector" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los sectores</option>
            <?php foreach ($sectors as $sector): ?>
                <option value="<?= htmlspecialchars($sector) ?>" <?= (($filters['sector'] ?? '') === $sector) ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst(strtolower($sector))) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="lf-btn-search">Buscar</button>

        <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($filters['sector']) || !empty($filters['period'])): ?>
            <a href="/companies" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($filters['status']) || !empty($filters['period']) || !empty($filters['sector'])): ?>
    <div class="lf-active-filter">
        🔍 Mostrando:
        <?php
            if (($filters['status'] ?? '') === 'activa_prospecto') echo 'empresas activas y prospectos';
            elseif (!empty($filters['status'])) echo ucfirst($filters['status']);
            elseif (($filters['period'] ?? '') === 'week') echo 'empresas de esta semana';
            if (!empty($filters['sector'])) echo (!empty($filters['status']) || !empty($filters['period']) ? ' · ' : '') . 'sector: ' . ucfirst(strtolower($filters['sector']));
        ?>
        · <a href="/companies">Limpiar</a>
    </div>
    <?php endif; ?>
</div>

<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Sector</th>
                <th>Estado</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($companies)): ?>
            <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-light)">No hay empresas con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td>
                        <a href="/companies/<?= $company['id'] ?>" style="font-weight:600;color:var(--text-main)">
                            <?= htmlspecialchars(ucfirst(strtolower($company['name']))) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars(ucfirst(strtolower($company['sector'] ?? '-'))) ?></td>
                    <td>
                        <?php $st = $company['status'] ?? 'prospecto'; ?>
                        <span class="lead-status-badge <?= match($st) {
                            'activa'    => 'ls-interesado',
                            'prospecto' => 'ls-pendiente_contacto',
                            'inactiva'  => 'ls-no_interesado',
                            'bloqueada' => 'ls-no_interesado',
                            default     => 'ls-nuevo'
                        } ?>">
                            <?= ucfirst($st) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($company['email'] ?? '-') ?></td>
                    <td>
                        <a href="/companies/<?= $company['id'] ?>" class="btn-sm">Ver</a>
                        <a href="/companies/<?= $company['id'] ?>/edit" class="btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
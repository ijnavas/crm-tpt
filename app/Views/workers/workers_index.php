<?php
$statusColors = [
    'activo'          => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'baja_temporal'   => ['bg'=>'#fefce8','color'=>'#a16207'],
    'baja_definitiva' => ['bg'=>'#fef2f2','color'=>'#b91c1c'],
    'en_formacion'    => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
];
?>
<style>
.wk-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.wk-kpi{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;position:relative;overflow:hidden}
.wk-kpi-label{font-size:11px;font-weight:600;color:var(--text-soft);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.wk-kpi-value{font-size:32px;font-weight:800;color:var(--text-main);letter-spacing:-1px;line-height:1}
.wk-kpi-bar{position:absolute;bottom:0;left:0;right:0;height:3px}
.wk-kpi-bar--green{background:var(--success)}
.wk-kpi-bar--blue{background:var(--primary)}
.wk-kpi-bar--yellow{background:#f59e0b}
@media(max-width:768px){.wk-kpis{grid-template-columns:repeat(2,1fr)}}
</style>

<section class="page-header">
    <div>
        <h1>Trabajadores</h1>
        <p>Gestión de trabajadores con discapacidad</p>
    </div>
    <a href="/workers/create" class="btn btn-primary">+ Nuevo trabajador</a>
</section>

<!-- KPIs -->
<div class="wk-kpis">
    <div class="wk-kpi">
        <div class="wk-kpi-label">Total trabajadores</div>
        <div class="wk-kpi-value"><?= $kpis['total'] ?></div>
        <div class="wk-kpi-bar wk-kpi-bar--blue"></div>
    </div>
    <div class="wk-kpi">
        <div class="wk-kpi-label">Activos</div>
        <div class="wk-kpi-value"><?= $kpis['active'] ?></div>
        <div class="wk-kpi-bar wk-kpi-bar--green"></div>
    </div>
    <div class="wk-kpi">
        <div class="wk-kpi-label">Asignados</div>
        <div class="wk-kpi-value"><?= $kpis['assigned'] ?></div>
        <div class="wk-kpi-bar wk-kpi-bar--blue"></div>
    </div>
    <div class="wk-kpi">
        <div class="wk-kpi-label">Disponibles</div>
        <div class="wk-kpi-value"><?= $kpis['available'] ?></div>
        <div class="wk-kpi-bar wk-kpi-bar--green"></div>
    </div>
</div>

<!-- Filtros -->
<div class="leads-filters" style="margin-bottom:16px">
    <form method="GET" action="/workers">
        <div class="lf-search-wrap">
            <span class="lf-search-icon">🔍</span>
            <input type="text" name="q" class="lf-input"
                   placeholder="Buscar nombre, DNI, habilidades..."
                   value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
        </div>
        <select name="status" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach ($catalogs['statuses'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['status'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="availability" class="lf-select" onchange="this.form.submit()">
            <option value="">Toda disponibilidad</option>
            <?php foreach ($catalogs['availability'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['availability'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="lf-btn-search">Buscar</button>
        <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($filters['availability'])): ?>
            <a href="/workers" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabla -->
<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Trabajador</th>
                <th>DNI</th>
                <th>Discapacidad</th>
                <th>Disponibilidad</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($workers)): ?>
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-light)">No hay trabajadores con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($workers as $w):
                $sc = $statusColors[$w['status']] ?? $statusColors['activo'];
            ?>
            <tr>
                <td>
                    <a href="/workers/<?= $w['id'] ?>" style="font-weight:600;color:var(--text-main)">
                        <?= htmlspecialchars($w['full_name']) ?>
                    </a>
                    <?php if (!empty($w['driving_license'])): ?>
                        <span title="Carnet de conducir" style="margin-left:4px">🚗</span>
                    <?php endif; ?>
                </td>
                <td style="font-size:12px;color:var(--text-soft)"><?= htmlspecialchars($w['dni'] ?? '—') ?></td>
                <td>
                    <?php if (!empty($w['disability_type'])): ?>
                        <span style="font-size:12px"><?= htmlspecialchars(ucfirst($w['disability_type'])) ?></span>
                        <?php if (!empty($w['disability_degree'])): ?>
                            <span style="font-size:11px;color:var(--text-soft)"> · <?= $w['disability_degree'] ?>%</span>
                        <?php endif; ?>
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><?= !empty($w['availability']) ? htmlspecialchars(ucfirst(str_replace('_',' ',$w['availability']))) : '—' ?></td>
                <td>
                    <span class="lead-status-badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>">
                        <?= ucfirst(str_replace('_',' ',$w['status'])) ?>
                    </span>
                </td>
                <td>
                    <a href="/workers/<?= $w['id'] ?>" class="btn-sm">Ver</a>
                    <a href="/workers/<?= $w['id'] ?>/edit" class="btn-sm">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
$statusColors = [
    'activo'     => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'renovado'   => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
    'pausado'    => ['bg'=>'#fefce8','color'=>'#a16207'],
    'finalizado' => ['bg'=>'#f9fafb','color'=>'#6b7280'],
    'cancelado'  => ['bg'=>'#fef2f2','color'=>'#b91c1c'],
];
?>
<style>
.ct-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.ct-kpi{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;position:relative;overflow:hidden}
.ct-kpi-label{font-size:11px;font-weight:600;color:var(--text-soft);margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.ct-kpi-value{font-size:28px;font-weight:800;color:var(--text-main);letter-spacing:-1px;line-height:1}
.ct-kpi-sub{font-size:11px;color:var(--text-soft);margin-top:6px}
.ct-kpi--warn .ct-kpi-value{color:var(--danger)}
.ct-kpi-bar{position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--primary)}
.ct-kpi-bar--green{background:var(--success)}
.ct-kpi-bar--yellow{background:#f59e0b}
.ct-kpi-bar--red{background:var(--danger)}
@media(max-width:768px){.ct-kpis{grid-template-columns:repeat(2,1fr)}}
</style>

<section class="page-header">
    <div>
        <h1>Contratos</h1>
        <p>Acuerdos de servicio con empresas clientes</p>
    </div>
    <a href="/contracts/create" class="btn btn-primary">+ Nuevo contrato</a>
</section>

<!-- KPIs -->
<div class="ct-kpis">
    <div class="ct-kpi">
        <div class="ct-kpi-label">Contratos activos</div>
        <div class="ct-kpi-value"><?= $kpis['total_active'] ?></div>
        <div class="ct-kpi-bar ct-kpi-bar--green"></div>
    </div>
    <div class="ct-kpi <?= $kpis['expiring_soon'] > 0 ? 'ct-kpi--warn' : '' ?>">
        <div class="ct-kpi-label">Vencen en 30 días</div>
        <div class="ct-kpi-value"><?= $kpis['expiring_soon'] ?></div>
        <div class="ct-kpi-sub"><?= $kpis['expiring_soon'] > 0 ? 'Requieren atención' : 'Sin vencimientos' ?></div>
        <div class="ct-kpi-bar ct-kpi-bar--<?= $kpis['expiring_soon'] > 0 ? 'red' : 'green' ?>"></div>
    </div>
    <div class="ct-kpi">
        <div class="ct-kpi-label">Trabajadores asignados</div>
        <div class="ct-kpi-value"><?= $kpis['total_workers'] ?></div>
        <div class="ct-kpi-bar"></div>
    </div>
    <div class="ct-kpi">
        <div class="ct-kpi-label">Importe total activo</div>
        <div class="ct-kpi-value"><?= $kpis['total_amount'] > 0 ? number_format($kpis['total_amount'], 0, ',', '.') . '€' : '—' ?></div>
        <div class="ct-kpi-bar ct-kpi-bar--green"></div>
    </div>
</div>

<!-- Filtros -->
<div class="leads-filters" style="margin-bottom:16px">
    <form method="GET" action="/contracts">
        <div class="lf-search-wrap">
            <span class="lf-search-icon">🔍</span>
            <input type="text" name="q" class="lf-input"
                placeholder="Buscar contrato o empresa..."
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

        <select name="service_type" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los servicios</option>
            <?php foreach ($catalogs['service_types'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['service_type'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="lf-btn-search">Buscar</button>
        <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($filters['service_type'])): ?>
            <a href="/contracts" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabla -->
<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Contrato</th>
                <th>Empresa</th>
                <th>Servicio</th>
                <th>Trabajadores</th>
                <th>Importe</th>
                <th>Vigencia</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contracts)): ?>
            <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text-light)">No hay contratos con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($contracts as $c):
                $sc = $statusColors[$c['status']] ?? $statusColors['activo'];
                $expiring = !empty($c['end_date']) && strtotime($c['end_date']) <= strtotime('+30 days') && $c['status'] === 'activo';
            ?>
            <tr>
                <td>
                    <a href="/contracts/<?= $c['id'] ?>" style="font-weight:600;color:var(--text-main)">
                        <?= htmlspecialchars(ucfirst(strtolower($c['title']))) ?>
                    </a>
                    <?php if (!empty($c['renewable'])): ?>
                        <span style="font-size:10px;color:var(--primary);margin-left:4px">↻ Renovable</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/companies/<?= $c['company_id'] ?>">
                        <?= htmlspecialchars(ucfirst(strtolower($c['company_name']))) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $c['service_type']))) ?></td>
                <td style="text-align:center;font-weight:600"><?= $c['workers_assigned'] ?></td>
                <td><?= !empty($c['amount']) ? number_format($c['amount'], 2, ',', '.') . ' €' : '—' ?></td>
                <td style="font-size:12px;<?= $expiring ? 'color:var(--danger);font-weight:600' : '' ?>">
                    <?= !empty($c['start_date']) ? date('d/m/y', strtotime($c['start_date'])) : '—' ?>
                    <?php if (!empty($c['end_date'])): ?>
                        → <?= date('d/m/y', strtotime($c['end_date'])) ?>
                        <?php if ($expiring): ?> ⚠️<?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="lead-status-badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>">
                        <?= ucfirst($c['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="/contracts/<?= $c['id'] ?>" class="btn-sm">Ver</a>
                    <a href="/contracts/<?= $c['id'] ?>/edit" class="btn-sm">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

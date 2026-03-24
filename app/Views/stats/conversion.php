<style>
.st-kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
.st-kpi{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px 22px;position:relative;overflow:hidden;box-shadow:var(--shadow-soft)}
.st-kpi-label{font-size:11px;font-weight:600;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px}
.st-kpi-value{font-size:36px;font-weight:800;color:var(--text-main);letter-spacing:-1.5px;line-height:1;margin-bottom:4px}
.st-kpi-sub{font-size:12px;color:var(--text-soft)}
.st-kpi-bar{position:absolute;bottom:0;left:0;right:0;height:4px}
.st-kpi--green .st-kpi-value{color:#15803d}
.st-kpi--red .st-kpi-value{color:#b91c1c}
.st-kpi--blue .st-kpi-value{color:#1d4ed8}

.st-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.st-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow-soft)}
.st-card-head{padding:16px 20px;border-bottom:1px solid var(--border-soft);font-size:14px;font-weight:700;color:var(--text-main)}
.st-row{display:flex;align-items:center;gap:12px;padding:11px 20px;border-bottom:1px solid var(--border-soft)}
.st-row:last-child{border-bottom:none}
.st-row-label{flex:1;font-size:13px;font-weight:500;color:var(--text-main)}
.st-row-bar-wrap{width:120px;height:6px;background:#f1f5f9;border-radius:999px;overflow:hidden;flex-shrink:0}
.st-row-bar{height:100%;border-radius:999px;background:var(--primary);transition:width .4s}
.st-row-nums{text-align:right;flex-shrink:0;min-width:80px}
.st-row-total{font-size:13px;font-weight:700;color:var(--text-main)}
.st-row-rate{font-size:11px;color:var(--text-soft)}
.st-empty{padding:24px 20px;text-align:center;font-size:13px;color:var(--text-light)}

.st-funnel{padding:20px}
.st-funnel-item{display:flex;align-items:center;gap:12px;margin-bottom:10px}
.st-funnel-label{width:160px;font-size:12px;font-weight:600;color:var(--text-soft);text-align:right;flex-shrink:0}
.st-funnel-bar-wrap{flex:1;height:28px;background:#f1f5f9;border-radius:8px;overflow:hidden;position:relative}
.st-funnel-bar{height:100%;border-radius:8px;display:flex;align-items:center;padding-left:10px;font-size:12px;font-weight:700;color:#fff;transition:width .5s}
.st-funnel-num{font-size:12px;font-weight:700;color:var(--text-soft);flex-shrink:0;width:40px;text-align:right}

@media(max-width:900px){.st-grid{grid-template-columns:1fr}.st-kpis{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.st-kpis{grid-template-columns:1fr}}
</style>

<section class="page-header">
    <div>
        <h1>Estadísticas de conversión</h1>
        <p>Análisis del rendimiento comercial y conversión de leads</p>
    </div>
</section>

<!-- KPIs globales -->
<div class="st-kpis">
    <div class="st-kpi st-kpi--blue">
        <div class="st-kpi-label">Total leads</div>
        <div class="st-kpi-value"><?= $kpis['total'] ?></div>
        <div class="st-kpi-sub"><?= $kpis['active'] ?> activos actualmente</div>
        <div class="st-kpi-bar" style="background:var(--primary)"></div>
    </div>
    <div class="st-kpi st-kpi--green">
        <div class="st-kpi-label">Tasa de conversión</div>
        <div class="st-kpi-value"><?= $kpis['rate'] ?>%</div>
        <div class="st-kpi-sub"><?= $kpis['converted'] ?> leads convertidos</div>
        <div class="st-kpi-bar" style="background:#15803d"></div>
    </div>
    <div class="st-kpi">
        <div class="st-kpi-label">Tiempo medio conversión</div>
        <div class="st-kpi-value" style="font-size:28px"><?= $kpis['avg_time'] ?></div>
        <div class="st-kpi-sub"><?= $kpis['lost'] ?> leads perdidos</div>
        <div class="st-kpi-bar" style="background:#f59e0b"></div>
    </div>
</div>

<!-- Embudo de estados -->
<div class="st-card" style="margin-bottom:20px">
    <div class="st-card-head">Embudo de leads por estado</div>
    <div class="st-funnel">
        <?php
        $statusLabels = [
            'nuevo'=>'Nuevo','pendiente_contacto'=>'Pendiente contacto',
            'en_seguimiento'=>'En seguimiento','cualificado'=>'Cualificado',
            'interesado'=>'Interesado','no_interesado'=>'No interesado','convertido'=>'Convertido'
        ];
        $statusBarColors = [
            'nuevo'=>'#93c5fd','pendiente_contacto'=>'#fcd34d','en_seguimiento'=>'#c4b5fd',
            'cualificado'=>'#6ee7b7','interesado'=>'#86efac','no_interesado'=>'#d1d5db','convertido'=>'#4ade80'
        ];
        $maxStatus = max(array_column($byStatus, 'total') ?: [1]);
        foreach ($byStatus as $s):
            $pct = $maxStatus > 0 ? round($s['total'] * 100 / $maxStatus) : 0;
            $label = $statusLabels[$s['status']] ?? ucfirst($s['status']);
            $color = $statusBarColors[$s['status']] ?? '#93c5fd';
        ?>
        <div class="st-funnel-item">
            <div class="st-funnel-label"><?= htmlspecialchars($label) ?></div>
            <div class="st-funnel-bar-wrap">
                <div class="st-funnel-bar" style="width:<?= $pct ?>%;background:<?= $color ?>">
                    <?php if ($pct > 15): echo $s['total']; endif; ?>
                </div>
            </div>
            <div class="st-funnel-num"><?= $s['total'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Por origen y por servicio -->
<div class="st-grid">

    <!-- Por origen -->
    <div class="st-card">
        <div class="st-card-head">Conversión por origen</div>
        <?php if (empty($bySource)): ?>
            <div class="st-empty">Sin datos de origen</div>
        <?php else: ?>
            <?php $maxSrc = max(array_column($bySource, 'total') ?: [1]); ?>
            <?php foreach ($bySource as $s): ?>
            <div class="st-row">
                <div class="st-row-label"><?= htmlspecialchars(ucfirst(str_replace('_',' ',$s['source']))) ?></div>
                <div class="st-row-bar-wrap">
                    <div class="st-row-bar" style="width:<?= round($s['total'] * 100 / $maxSrc) ?>%"></div>
                </div>
                <div class="st-row-nums">
                    <div class="st-row-total"><?= $s['total'] ?></div>
                    <div class="st-row-rate"><?= $s['rate'] ?>% conv.</div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Por servicio -->
    <div class="st-card">
        <div class="st-card-head">Conversión por servicio de interés</div>
        <?php if (empty($byService)): ?>
            <div class="st-empty">Sin datos de servicio</div>
        <?php else: ?>
            <?php $maxSvc = max(array_column($byService, 'total') ?: [1]); ?>
            <?php foreach ($byService as $s): ?>
            <div class="st-row">
                <div class="st-row-label"><?= htmlspecialchars(ucfirst(str_replace('_',' ',$s['service_interest']))) ?></div>
                <div class="st-row-bar-wrap">
                    <div class="st-row-bar" style="width:<?= round($s['total'] * 100 / $maxSvc) ?>%"></div>
                </div>
                <div class="st-row-nums">
                    <div class="st-row-total"><?= $s['total'] ?></div>
                    <div class="st-row-rate"><?= $s['rate'] ?>% conv.</div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- Por comercial -->
<div class="st-card" style="margin-bottom:20px">
    <div class="st-card-head">Rendimiento por comercial</div>
    <?php if (empty($byUser)): ?>
        <div class="st-empty">Sin datos de usuarios asignados</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Comercial</th>
                    <th style="text-align:center">Total leads</th>
                    <th style="text-align:center">Convertidos</th>
                    <th style="text-align:center">Tasa</th>
                    <th>Rendimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php $maxU = max(array_column($byUser, 'total') ?: [1]); ?>
                <?php foreach ($byUser as $u): ?>
                <tr>
                    <td style="font-weight:600"><?= htmlspecialchars(ucwords(strtolower($u['user_name']))) ?></td>
                    <td style="text-align:center"><?= $u['total'] ?></td>
                    <td style="text-align:center;color:#15803d;font-weight:600"><?= $u['converted'] ?></td>
                    <td style="text-align:center">
                        <span class="lead-status-badge <?= $u['rate'] >= 30 ? 'ls-interesado' : ($u['rate'] >= 15 ? 'ls-cualificado' : 'ls-pendiente_contacto') ?>">
                            <?= $u['rate'] ?>%
                        </span>
                    </td>
                    <td style="width:180px">
                        <div style="height:6px;background:#f1f5f9;border-radius:999px;overflow:hidden">
                            <div style="height:100%;width:<?= round($u['total'] * 100 / $maxU) ?>%;background:var(--primary);border-radius:999px"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Evolución mensual -->
<?php if (!empty($perMonth)): ?>
<div class="st-card">
    <div class="st-card-head">Evolución mensual (últimos 12 meses)</div>
    <div style="padding:20px">
        <canvas id="monthChart" height="80"></canvas>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const monthData = <?= json_encode($perMonth) ?>;
new Chart(document.getElementById('monthChart'), {
    type: 'bar',
    data: {
        labels: monthData.map(d => d.month_label),
        datasets: [
            {
                label: 'Total leads',
                data: monthData.map(d => d.total),
                backgroundColor: 'rgba(47,128,237,0.15)',
                borderColor: '#2f80ed',
                borderWidth: 2,
                borderRadius: 6,
            },
            {
                label: 'Convertidos',
                data: monthData.map(d => d.converted),
                backgroundColor: 'rgba(21,128,61,0.8)',
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
<?php endif; ?>
<?php
$kpis     = $dashboard['kpis'];
$perDay   = $dashboard['leadsPerDay'];
$tasks    = $dashboard['upcomingTasks'];
$companies = $dashboard['recentCompanies'];
$activity  = $dashboard['recentActivity'];

function trend(int $current, int $prev): array {
    if ($prev === 0) return ['pct' => 0, 'up' => true];
    $pct = round((($current - $prev) / $prev) * 100, 1);
    return ['pct' => abs($pct), 'up' => $pct >= 0];
}

$tLeads    = trend($kpis['leads_week'], $kpis['leads_week_prev']);
$tContacts = trend($kpis['contacts_week'], $kpis['contacts_week_prev']);
?>
<style>
/* ── Reset dashboard ── */
.db-wrap{padding:0;font-family:inherit}

/* ── Header ── */
.db-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;gap:12px;flex-wrap:wrap}
.db-header-left h1{margin:0 0 3px;font-size:22px;font-weight:700;color:#1a2b4a;letter-spacing:-.3px}
.db-header-left p{margin:0;font-size:13px;color:#6b7280;text-transform:capitalize}
.db-new-btn{display:inline-flex;align-items:center;gap:7px;height:38px;padding:0 18px;background:#1a6ed8;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;white-space:nowrap;flex-shrink:0}
.db-new-btn:hover{background:#1558b0;color:#fff}

/* ── KPI row ── */
.db-kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px}
.db-kpi-card{background:#fff;border:1px solid #e8edf5;border-radius:12px;padding:20px 22px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.db-kpi-top{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.db-kpi-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.db-kpi-icon--blue{background:#e8f1fd}
.db-kpi-icon--green{background:#e6f7ee}
.db-kpi-icon--gray{background:#f0f2f5}
.db-kpi-lbl{font-size:13px;font-weight:600;color:#4b5563}
.db-kpi-num{font-size:40px;font-weight:800;color:#1a2b4a;letter-spacing:-1.5px;line-height:1;margin-bottom:10px}
.db-kpi-trend{display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600}
.db-kpi-trend--up{color:#16a34a}
.db-kpi-trend--down{color:#dc2626}
.db-kpi-trend span{color:#6b7280;font-weight:400}

/* ── Main grid ── */
.db-main{display:grid;grid-template-columns:1.4fr 1fr;gap:16px;align-items:start}

/* ── Chart card ── */
.db-chart-card{background:#fff;border:1px solid #e8edf5;border-radius:12px;padding:22px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.db-card-title{font-size:15px;font-weight:700;color:#1a2b4a;margin:0 0 18px}

/* ── Tasks card ── */
.db-tasks-card{background:#fff;border:1px solid #e8edf5;border-radius:12px;padding:22px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.db-tasks-split{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px}
.db-tasks-stat{background:#f7f9fc;border-radius:8px;padding:14px 16px;text-align:center}
.db-tasks-stat-lbl{font-size:12px;font-weight:600;color:#6b7280;margin-bottom:6px}
.db-tasks-stat-num{font-size:30px;font-weight:800;letter-spacing:-1px;line-height:1}
.db-tasks-stat-num--blue{color:#1a6ed8}
.db-tasks-stat-num--green{color:#16a34a}
.db-tasks-subtitle{font-size:13px;font-weight:700;color:#1a2b4a;margin:0 0 10px}
.db-task-item{display:flex;align-items:flex-start;gap:8px;padding:9px 0;border-bottom:1px solid #f0f2f5}
.db-task-item:last-child{border-bottom:none}
.db-task-dot{width:7px;height:7px;border-radius:50%;background:#1a6ed8;flex-shrink:0;margin-top:5px}
.db-task-dot--overdue{background:#dc2626}
.db-task-text{font-size:13px;color:#374151;line-height:1.4}
.db-task-date{font-size:11px;color:#9ca3af;margin-top:2px}

/* ── Chart canvas ── */
#leadsChart{width:100%!important;height:200px!important}

/* ── Responsive ── */
@media(max-width:900px){
  .db-kpi-row{grid-template-columns:1fr 1fr}
  .db-main{grid-template-columns:1fr}
}
@media(max-width:520px){
  .db-kpi-row{grid-template-columns:1fr}
  .db-kpi-num{font-size:32px}
}
</style>

<div class="db-wrap">

  <!-- Header -->
  <div class="db-header">
    <div class="db-header-left">
      <h1>Dashboard</h1>
      <p><?= date('l, j \d\e F \d\e Y') ?></p>
    </div>
    <a href="/leads/create" class="db-new-btn">+ Nuevo lead</a>
  </div>

  <!-- KPIs -->
  <div class="db-kpi-row">

    <div class="db-kpi-card">
      <div class="db-kpi-top">
        <div class="db-kpi-icon db-kpi-icon--blue">👤</div>
        <div class="db-kpi-lbl">Leads de la semana</div>
      </div>
      <a href="/leads?period=week" class="db-kpi-num" style="text-decoration:none;color:inherit"><?= $kpis['leads_week'] ?></a>
      <div class="db-kpi-trend <?= $tLeads['up'] ? 'db-kpi-trend--up' : 'db-kpi-trend--down' ?>">
        <?= $tLeads['up'] ? '▲' : '▼' ?> <?= $tLeads['pct'] ?>%
        <span>desde la semana pasada</span>
      </div>
    </div>

    <div class="db-kpi-card">
      <div class="db-kpi-top">
        <div class="db-kpi-icon db-kpi-icon--green">📞</div>
        <div class="db-kpi-lbl">Contactos en la semana</div>
      </div>
      <a href="/contacts?period=week" class="db-kpi-num" style="text-decoration:none;color:inherit"><?= $kpis['contacts_week'] ?></a>
      <div class="db-kpi-trend <?= $tContacts['up'] ? 'db-kpi-trend--up' : 'db-kpi-trend--down' ?>">
        <?= $tContacts['up'] ? '▲' : '▼' ?> <?= $tContacts['pct'] ?>%
        <span>desde la semana pasada</span>
      </div>
    </div>

    <div class="db-kpi-card">
      <div class="db-kpi-top">
        <div class="db-kpi-icon db-kpi-icon--gray">🏢</div>
        <div class="db-kpi-lbl">Empresas activas</div>
      </div>
      <a href="/companies?status=activa_prospecto" class="db-kpi-num" style="text-decoration:none;color:inherit"><?= $kpis['active_companies'] ?></a>
      <div class="db-kpi-trend db-kpi-trend--up">
        <?= $kpis['converted_leads'] ?> <span>leads convertidos en total</span>
      </div>
    </div>

  </div>

  <!-- Grid principal -->
  <div class="db-main">

    <!-- Gráfica leads por día -->
    <div class="db-chart-card">
      <p class="db-card-title">Nuevos leads esta semana</p>
      <canvas id="leadsChart"></canvas>
      <script>
      (function(){
        var labels = <?= json_encode(array_column($perDay, 'label')) ?>;
        var data   = <?= json_encode(array_column($perDay, 'total')) ?>;
        var ctx = document.getElementById('leadsChart');
        if(!ctx) return;
        var script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js';
        script.onload = function(){
          new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: 'Nuevos leads',
                data: data,
                borderColor: '#1a6ed8',
                backgroundColor: 'rgba(26,110,216,0.08)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#1a6ed8',
                tension: 0.3,
                fill: true
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 12 } } } },
              scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f0f2f5' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
              }
            }
          });
        };
        document.head.appendChild(script);
      })();
      </script>
    </div>

    <!-- Tareas -->
    <div class="db-tasks-card">
      <p class="db-card-title">Tareas de la semana</p>

      <div class="db-tasks-split">
        <div class="db-tasks-stat">
          <div class="db-tasks-stat-lbl">Pendientes</div>
          <div class="db-tasks-stat-num db-tasks-stat-num--blue"><?= $kpis['pending_tasks'] ?></div>
        </div>
        <div class="db-tasks-stat">
          <div class="db-tasks-stat-lbl">Finalizadas</div>
          <div class="db-tasks-stat-num db-tasks-stat-num--green"><?= $kpis['completed_tasks'] ?></div>
        </div>
      </div>

      <p class="db-tasks-subtitle">Tareas pendientes</p>

      <?php if (empty($tasks)): ?>
        <p style="font-size:13px;color:#9ca3af;margin:0">Sin tareas pendientes</p>
      <?php else: ?>
        <?php foreach (array_slice($tasks, 0, 5) as $t):
          $overdue = !empty($t['due_date']) && strtotime($t['due_date']) < time();
        ?>
        <div class="db-task-item">
          <div class="db-task-dot <?= $overdue ? 'db-task-dot--overdue' : '' ?>"></div>
          <div>
            <div class="db-task-text"><?= htmlspecialchars($t['title']) ?></div>
            <?php if (!empty($t['due_date'])): ?>
            <div class="db-task-date"><?= date('d/m/Y', strtotime($t['due_date'])) ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($tasks) > 5): ?>
        <a href="/tasks" style="font-size:12px;color:#1a6ed8;display:block;margin-top:10px">Ver todas →</a>
        <?php endif; ?>
      <?php endif; ?>
    </div>

  </div>

</div>
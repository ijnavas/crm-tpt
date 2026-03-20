<?php
$kpis  = $dashboard['kpis'];
$perDay = $dashboard['leadsPerDay'];
$tasks  = $dashboard['upcomingTasks'];
?>
<style>
/* Reutiliza los mismos estilos que dashboard/index.php */
.db-wrap{padding:0;font-family:inherit}
.db-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;gap:12px;flex-wrap:wrap}
.db-header-left h1{margin:0 0 3px;font-size:22px;font-weight:700;color:#1a2b4a;letter-spacing:-.3px}
.db-header-left p{margin:0;font-size:13px;color:#6b7280;text-transform:capitalize}
.db-new-btn{display:inline-flex;align-items:center;gap:7px;height:38px;padding:0 18px;background:#1a6ed8;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;white-space:nowrap}
.db-new-btn:hover{background:#1558b0;color:#fff}
.db-role-badge{display:inline-flex;align-items:center;padding:4px 12px;background:#e8f4fd;color:#1a6ed8;border-radius:999px;font-size:12px;font-weight:700;margin-bottom:20px}
.db-placeholder{background:#fff;border:2px dashed #e5e9f2;border-radius:14px;padding:48px 24px;text-align:center;color:#9ca3af;margin-bottom:20px}
.db-placeholder h3{margin:0 0 8px;font-size:16px;font-weight:600;color:#6b7280}
.db-placeholder p{margin:0;font-size:13px}
.db-kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px}
.db-kpi-card{background:#fff;border:1px solid #e8edf5;border-radius:12px;padding:20px 22px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.db-kpi-top{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.db-kpi-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;background:#e8f1fd}
.db-kpi-lbl{font-size:13px;font-weight:600;color:#4b5563}
.db-kpi-num{font-size:40px;font-weight:800;color:#1a2b4a;letter-spacing:-1.5px;line-height:1;margin-bottom:8px}
@media(max-width:600px){.db-kpi-row{grid-template-columns:1fr}}
</style>

<div class="db-wrap">
    <div class="db-header">
        <div class="db-header-left">
            <h1>Dashboard Comercial</h1>
            <p><?= date('l, j \d\e F \d\e Y') ?></p>
        </div>
        <a href="/leads/create" class="db-new-btn">+ Nuevo lead</a>
    </div>

    <div style="margin-bottom:20px">
        <span class="db-role-badge">👤 Equipo Comercial</span>
    </div>

    <!-- KPIs comerciales -->
    <div class="db-kpi-row">
        <div class="db-kpi-card">
            <div class="db-kpi-top">
                <div class="db-kpi-icon">📋</div>
                <div class="db-kpi-lbl">Mis leads esta semana</div>
            </div>
            <div class="db-kpi-num"><?= $kpis['leads_week'] ?></div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-top">
                <div class="db-kpi-icon">✅</div>
                <div class="db-kpi-lbl">Tareas pendientes</div>
            </div>
            <div class="db-kpi-num"><?= $kpis['pending_tasks'] ?></div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-top">
                <div class="db-kpi-icon">🎯</div>
                <div class="db-kpi-lbl">Convertidos</div>
            </div>
            <div class="db-kpi-num"><?= $kpis['converted_leads'] ?></div>
        </div>
    </div>

    <!-- Secciones pendientes de desarrollar -->
    <div class="db-placeholder">
        <h3>📊 Mis leads asignados</h3>
        <p>Aquí aparecerán los leads asignados al comercial con su estado y próxima acción</p>
    </div>

    <div class="db-placeholder">
        <h3>📅 Mi agenda de tareas</h3>
        <p>Tareas pendientes ordenadas por fecha de vencimiento</p>
    </div>

</div>

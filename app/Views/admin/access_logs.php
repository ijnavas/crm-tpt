<style>
.al-kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.al-kpi{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;position:relative;overflow:hidden}
.al-kpi-label{font-size:11px;font-weight:600;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.al-kpi-value{font-size:30px;font-weight:800;color:var(--text-main);letter-spacing:-1px}
.al-kpi-bar{position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--primary)}

.al-badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600}
.al-badge--login{background:#f0fdf4;color:#15803d}
.al-badge--logout{background:#fef2f2;color:#b91c1c}
.al-badge--visit{background:#eff6ff;color:#1d4ed8}
</style>

<section class="page-header">
    <div>
        <h1>Log de acceso</h1>
        <p>Registro de entradas, salidas y actividad de usuarios</p>
    </div>
</section>

<!-- KPIs -->
<div class="al-kpis">
    <div class="al-kpi">
        <div class="al-kpi-label">Logins hoy</div>
        <div class="al-kpi-value"><?= $stats['logins_today'] ?></div>
        <div class="al-kpi-bar" style="background:var(--success)"></div>
    </div>
    <div class="al-kpi">
        <div class="al-kpi-label">Usuarios activos (30 min)</div>
        <div class="al-kpi-value"><?= $stats['active_users'] ?></div>
        <div class="al-kpi-bar" style="background:var(--primary)"></div>
    </div>
    <div class="al-kpi">
        <div class="al-kpi-label">Eventos hoy</div>
        <div class="al-kpi-value"><?= $stats['total_today'] ?></div>
        <div class="al-kpi-bar"></div>
    </div>
</div>

<!-- Filtros -->
<div class="leads-filters" style="margin-bottom:16px">
    <form method="GET" action="/admin/logs">

        <select name="user_id" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los usuarios</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['user_id'] ?>" <?= (($filters['user_id'] ?? '') == $u['user_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['user_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="action" class="lf-select" onchange="this.form.submit()">
            <option value="">Todas las acciones</option>
            <option value="login"  <?= (($filters['action'] ?? '') === 'login')  ? 'selected' : '' ?>>Login</option>
            <option value="logout" <?= (($filters['action'] ?? '') === 'logout') ? 'selected' : '' ?>>Logout</option>
            <option value="visit"  <?= (($filters['action'] ?? '') === 'visit')  ? 'selected' : '' ?>>Visita</option>
        </select>

        <input type="date" name="date_from" class="lf-input" style="max-width:160px"
               value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
               title="Desde">
        <input type="date" name="date_to" class="lf-input" style="max-width:160px"
               value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
               title="Hasta">

        <button type="submit" class="lf-btn-search">Buscar</button>
        <?php if (!empty($filters['user_id']) || !empty($filters['action']) || !empty($filters['date_from'])): ?>
            <a href="/admin/logs" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabla -->
<section class="card" style="padding:0;overflow:hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Ruta</th>
                <th>IP</th>
                <th>Fecha y hora</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
            <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-light)">No hay registros con estos filtros</td></tr>
            <?php else: ?>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td style="font-weight:600"><?= htmlspecialchars(ucwords(strtolower($log['user_name'] ?? 'Sistema'))) ?></td>
                <td>
                    <span class="al-badge al-badge--<?= $log['action'] ?>">
                        <?= ucfirst($log['action']) ?>
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text-soft)"><?= htmlspecialchars($log['path'] ?? '—') ?></td>
                <td style="font-size:12px;color:var(--text-soft)"><?= htmlspecialchars($log['ip'] ?? '—') ?></td>
                <td style="font-size:12px;color:var(--text-soft)">
                    <?= !empty($log['created_at']) ? date('d/m/Y H:i:s', strtotime($log['created_at'])) : '—' ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
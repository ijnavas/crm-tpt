<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function nav_active(string $path, string $currentPath): string {
    return str_starts_with($currentPath, $path) ? 'active' : '';
}

// Obtener logo personalizado si existe
$logoPath = null;
try {
    $db = \App\Core\Database::connection();
    $stmt = $db->prepare("SELECT value FROM settings WHERE key_name = 'logo_path' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
    $rawPath = $row ? $row['value'] : null;
    // Limpiar posible query string viejo y añadir cache-buster fresco
    $logoPath = $rawPath ? strtok($rawPath, '?') . '?v=' . filemtime(BASE_PATH . '/public' . strtok($rawPath, '?')) : null;
} catch (\Throwable $e) {
    $logoPath = null;
}
?>

<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <button class="sidebar-close" onclick="closeSidebar()">✕</button>

    <div class="sidebar-brand">
        <?php if ($logoPath): ?>
            <img src="<?= htmlspecialchars($logoPath) ?>"
                 alt=""
                 id="sidebar-logo-img"
                 style="max-height:40px;max-width:150px;width:auto;object-fit:contain;display:block"
                 onerror="this.style.display='none';document.getElementById('sidebar-logo-fallback').style.display='flex'">
            <div id="sidebar-logo-fallback" style="display:none;align-items:center;gap:12px">
                <div class="sidebar-logo-mark">CRM</div>
                <div class="sidebar-logo-text"><strong>TPT</strong><span>Empleo</span></div>
            </div>
        <?php else: ?>
            <div class="sidebar-logo-mark">CRM</div>
            <div class="sidebar-logo-text">
                <strong>TPT</strong>
                <span>Empleo</span>
            </div>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav">
        <a href="/dashboard" class="sidebar-link <?= nav_active('/dashboard', $currentPath) ?>" onclick="closeSidebar()">
            <span class="sidebar-icon">⊞</span><span>Dashboard</span>
        </a>
        <a href="/leads" class="sidebar-link <?= nav_active('/leads', $currentPath) ?>" onclick="closeSidebar()">
            <span class="sidebar-icon">◈</span><span>Leads</span>
        </a>
        <a href="/companies" class="sidebar-link <?= nav_active('/companies', $currentPath) ?>" onclick="closeSidebar()">
            <span class="sidebar-icon">⬡</span><span>Empresas</span>
        </a>
        <a href="/contacts" class="sidebar-link <?= nav_active('/contacts', $currentPath) ?>" onclick="closeSidebar()">
            <span class="sidebar-icon">◎</span><span>Contactos</span>
        </a>
        <a href="/tasks" class="sidebar-link <?= nav_active('/tasks', $currentPath) ?>" onclick="closeSidebar()">
            <span class="sidebar-icon">◻</span><span>Tareas</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-footer-card">
            <small>CRM interno</small>
            <strong>TPT Empleo</strong>
            <span>Versión MVP</span>
        </div>
    </div>
</aside>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('visible');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('visible');
    document.body.style.overflow = '';
}
</script>
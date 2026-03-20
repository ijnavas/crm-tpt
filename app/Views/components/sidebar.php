<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function nav_active(string $path, string $currentPath): string {
    return str_starts_with($currentPath, $path) ? 'active' : '';
}


?>

<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <button class="sidebar-close" onclick="closeSidebar()">✕</button>

    <div class="sidebar-brand">
        <img src="https://tptempleo.es/wp-content/uploads/2023/05/logo1.png"
             alt="TPT Empleo"
             style="width:100%;max-height:180px;object-fit:contain;display:block">
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
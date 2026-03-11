<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

function nav_active(string $path, string $currentPath): string
{
    return str_starts_with($currentPath, $path) ? 'active' : '';
}
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo-mark">CRM</div>
        <div class="sidebar-logo-text">
            <strong>TPT</strong>
            <span>Empleo</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="/dashboard" class="sidebar-link <?= nav_active('/dashboard', $currentPath) ?>">
            <span class="sidebar-icon">◉</span>
            <span>Dashboard</span>
        </a>

        <a href="/leads" class="sidebar-link <?= nav_active('/leads', $currentPath) ?>">
            <span class="sidebar-icon">◉</span>
            <span>Leads</span>
        </a>

        <a href="/companies" class="sidebar-link <?= nav_active('/companies', $currentPath) ?>">
            <span class="sidebar-icon">◉</span>
            <span>Empresas</span>
        </a>

        <a href="/contacts" class="sidebar-link <?= nav_active('/contacts', $currentPath) ?>">
            <span class="sidebar-icon">◉</span>
            <span>Contactos</span>
        </a>

        <a href="/tasks" class="sidebar-link <?= nav_active('/tasks', $currentPath) ?>">
            <span class="sidebar-icon">◉</span>
            <span>Tareas</span>
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
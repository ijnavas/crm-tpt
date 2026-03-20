<?php
$user = \App\Core\Auth::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function mobile_active(string $path, string $current): string {
    return str_starts_with($current, $path) ? 'active' : '';
}
?>

<header class="topbar">
    <button class="topbar-menu-btn" onclick="openSidebar()">☰</button>

    <div class="topbar-search" id="search-wrapper">
        <input
            type="text"
            id="global-search"
            placeholder="Buscar empresa o contacto..."
            autocomplete="off"
        >
    </div>

    <div class="topbar-actions">
        <button class="topbar-btn hide-mobile">Filtrar</button>
        <button class="topbar-btn hide-mobile">Exportar</button>

        <div class="topbar-user">
            <div class="topbar-avatar">
                <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="topbar-user-info">
                <strong><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></strong>
                <span><?= htmlspecialchars($user['email'] ?? '') ?></span>
            </div>
            <form action="/logout" method="POST">
                <button type="submit" class="topbar-logout">Salir</button>
            </form>
        </div>
    </div>
</header>

<!-- Navbar inferior (solo móvil) -->
<nav class="mobile-nav">
    <a href="/dashboard" class="mobile-nav-item <?= mobile_active('/dashboard', $currentPath) ?>">
        <span class="mobile-nav-icon">⊞</span>
        Inicio
    </a>
    <a href="/leads" class="mobile-nav-item <?= mobile_active('/leads', $currentPath) ?>">
        <span class="mobile-nav-icon">◈</span>
        Leads
    </a>
    <a href="/companies" class="mobile-nav-item <?= mobile_active('/companies', $currentPath) ?>">
        <span class="mobile-nav-icon">⬡</span>
        Empresas
    </a>
    <a href="/contacts" class="mobile-nav-item <?= mobile_active('/contacts', $currentPath) ?>">
        <span class="mobile-nav-icon">◎</span>
        Contactos
    </a>
    <a href="/tasks" class="mobile-nav-item <?= mobile_active('/tasks', $currentPath) ?>">
        <span class="mobile-nav-icon">◻</span>
        Tareas
    </a>
</nav>

<!-- Dropdown búsqueda -->
<div class="search-dropdown" id="search-dropdown"></div>

<script>
(function () {
    const input    = document.getElementById('global-search');
    const dropdown = document.getElementById('search-dropdown');
    const wrapper  = document.getElementById('search-wrapper');
    let timer = null;

    function positionDropdown() {
        const rect = input.getBoundingClientRect();
        dropdown.style.top   = rect.bottom + 'px';
        dropdown.style.left  = rect.left + 'px';
        dropdown.style.width = rect.width + 'px';
    }
    function open() { positionDropdown(); wrapper.classList.add('is-open'); dropdown.classList.add('open'); }
    function close() { dropdown.innerHTML = ''; dropdown.classList.remove('open'); wrapper.classList.remove('is-open'); }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { close(); return; }
        timer = setTimeout(() => fetchResults(q), 220);
    });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowDown') { focusItem(0); e.preventDefault(); }
    });
    window.addEventListener('resize', () => { if (dropdown.classList.contains('open')) positionDropdown(); });
    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target) && !dropdown.contains(e.target)) close();
    });

    function focusItem(i) { const items = dropdown.querySelectorAll('.search-item'); if (items[i]) items[i].focus(); }

    function fetchResults(q) {
        fetch('/search?q=' + encodeURIComponent(q)).then(r => r.json()).then(renderResults).catch(() => {});
    }

    function renderResults(data) {
        dropdown.innerHTML = '';
        const total = (data.companies||[]).length + (data.contacts||[]).length;
        if (total === 0) { dropdown.innerHTML = '<div class="search-empty">Sin resultados</div>'; open(); return; }

        if ((data.companies||[]).length > 0) {
            append('div', 'search-label', 'Empresas');
            data.companies.forEach(c => {
                const a = make('a', 'search-item', '/companies/' + c.id,
                    '<span class="search-item-icon search-item-icon--company">E</span>' +
                    '<span class="search-item-body"><strong>' + esc(c.name) + '</strong><small>' + esc(c.sector||'') + (c.city?' · '+esc(c.city):'') + '</small></span>' +
                    '<span class="search-item-badge search-item-badge--' + esc(c.status||'') + '">' + esc(c.status||'') + '</span>');
                addNav(a); dropdown.appendChild(a);
            });
        }
        if ((data.contacts||[]).length > 0) {
            append('div', 'search-label', 'Contactos');
            data.contacts.forEach(c => {
                const a = make('a', 'search-item', '/contacts/' + c.id,
                    '<span class="search-item-icon search-item-icon--contact">' + esc((c.full_name||'C').charAt(0).toUpperCase()) + '</span>' +
                    '<span class="search-item-body"><strong>' + esc(c.full_name) + '</strong><small>' + esc(c.job_title||'') + (c.company_name?' · '+esc(c.company_name):'') + '</small></span>');
                addNav(a); dropdown.appendChild(a);
            });
        }
        open();
    }

    function append(tag, cls, text) { const el = document.createElement(tag); el.className = cls; el.textContent = text; dropdown.appendChild(el); }
    function make(tag, cls, href, html) { const el = document.createElement(tag); el.className = cls; el.href = href; el.tabIndex = 0; el.innerHTML = html; return el; }
    function addNav(a) {
        a.addEventListener('keydown', e => {
            const items = [...dropdown.querySelectorAll('.search-item')];
            const i = items.indexOf(a);
            if (e.key === 'ArrowDown' && items[i+1]) { items[i+1].focus(); e.preventDefault(); }
            if (e.key === 'ArrowUp') { i > 0 ? items[i-1].focus() : input.focus(); e.preventDefault(); }
            if (e.key === 'Enter') window.location = a.href;
            if (e.key === 'Escape') { close(); input.focus(); }
        });
    }
    function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
})();
</script>
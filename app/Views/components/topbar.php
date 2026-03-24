<?php
$user = \App\Core\Auth::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function mobile_active(string $path, string $current): string {
    return str_starts_with($current, $path) ? 'active' : '';
}

// Contar tareas vencidas
$overdueCount = 0;
try {
    $db = \App\Core\Database::connection();
    $stmt = $db->query("
        SELECT COUNT(*) FROM tasks
        WHERE status NOT IN ('completada')
        AND due_date IS NOT NULL
        AND due_date < NOW()
    ");
    $overdueCount = (int) $stmt->fetchColumn();
} catch (\Throwable $e) {
    $overdueCount = 0;
}
?>

<header class="topbar">
    <button class="topbar-menu-btn" onclick="openSidebar()">☰</button>

    <div class="topbar-search" id="search-wrapper">
        <input type="text" id="global-search" placeholder="Buscar empresa o contacto..." autocomplete="off">
    </div>

    <div class="topbar-actions">

        <!-- Badge tareas vencidas -->
        <?php if ($overdueCount > 0): ?>
        <a href="/tasks?status=vencida" class="topbar-overdue-badge" title="Tareas vencidas">
            ⚠️ <span><?= $overdueCount ?></span>
        </a>
        <?php endif; ?>

        <a href="/profile" class="topbar-user" title="Mi perfil" style="text-decoration:none">
            <div class="topbar-avatar" style="overflow:hidden">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" style="width:100%;height:100%;object-fit:cover" alt="">
                <?php else: ?>
                    <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div class="topbar-user-info">
                <strong><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></strong>
                <span><?= htmlspecialchars($user['email'] ?? '') ?></span>
            </div>
        </a>
        <form action="/logout" method="POST">
            <button type="submit" class="topbar-logout">Salir</button>
        </form>
    </div>
</header>

<!-- Navbar inferior (solo móvil) -->
<nav class="mobile-nav">
    <a href="/dashboard" class="mobile-nav-item <?= mobile_active('/dashboard', $currentPath) ?>">
        <span class="mobile-nav-icon">⊞</span>Inicio
    </a>
    <a href="/leads" class="mobile-nav-item <?= mobile_active('/leads', $currentPath) ?>">
        <span class="mobile-nav-icon">◈</span>Leads
    </a>
    <a href="/companies" class="mobile-nav-item <?= mobile_active('/companies', $currentPath) ?>">
        <span class="mobile-nav-icon">⬡</span>Empresas
    </a>
    <a href="/contacts" class="mobile-nav-item <?= mobile_active('/contacts', $currentPath) ?>">
        <span class="mobile-nav-icon">◎</span>Contactos
    </a>
    <a href="/tasks" class="mobile-nav-item <?= mobile_active('/tasks', $currentPath) ?>">
        <span class="mobile-nav-icon">◻</span>Tareas
    </a>
    <a href="/contracts" class="mobile-nav-item <?= mobile_active('/contracts', $currentPath) ?>">
        <span class="mobile-nav-icon">📋</span>Contratos
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
        const sections = [
            { key: 'companies', label: 'Empresas',    icon: '🏢', url: id => '/companies/'+id,  name: c => c.name,      sub: c => [c.sector, c.city].filter(Boolean).join(' · ') },
            { key: 'contacts',  label: 'Contactos',   icon: '👤', url: id => '/contacts/'+id,   name: c => c.full_name, sub: c => [c.job_title, c.company_name].filter(Boolean).join(' · ') },
            { key: 'leads',     label: 'Leads',       icon: '◈',  url: id => '/leads/'+id,      name: c => c.company_name || c.full_name, sub: c => c.full_name },
            { key: 'tasks',     label: 'Tareas',      icon: '✅', url: id => '/tasks/'+id+'/edit', name: c => c.title, sub: c => [c.type, c.status].filter(Boolean).join(' · ') },
            { key: 'contracts', label: 'Contratos',   icon: '📋', url: id => '/contracts/'+id,  name: c => c.title,     sub: c => c.company_name },
            { key: 'workers',   label: 'Trabajadores',icon: '👷', url: id => '/workers/'+id,    name: c => c.full_name, sub: c => [c.disability_type, c.status].filter(Boolean).join(' · ') },
        ];
        let total = 0;
        sections.forEach(s => total += (data[s.key]||[]).length);
        if (total === 0) { dropdown.innerHTML = '<div class="search-empty">Sin resultados</div>'; open(); return; }
        sections.forEach(s => {
            const items = data[s.key] || [];
            if (!items.length) return;
            append('div', 'search-label', s.label);
            items.forEach(item => {
                const a = make('a', 'search-item', s.url(item.id),
                    '<span class="search-item-icon" style="background:#f1f5f9;border-radius:6px;width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0">'+s.icon+'</span>'+
                    '<span class="search-item-body"><strong>'+esc(s.name(item))+'</strong><small>'+esc(s.sub(item)||'')+'</small></span>');
                addNav(a); dropdown.appendChild(a);
            });
        });
        open();
    }
    function append(tag,cls,text){const el=document.createElement(tag);el.className=cls;el.textContent=text;dropdown.appendChild(el);}
    function make(tag,cls,href,html){const el=document.createElement(tag);el.className=cls;el.href=href;el.tabIndex=0;el.innerHTML=html;return el;}
    function addNav(a){a.addEventListener('keydown',e=>{const items=[...dropdown.querySelectorAll('.search-item')];const i=items.indexOf(a);if(e.key==='ArrowDown'&&items[i+1]){items[i+1].focus();e.preventDefault();}if(e.key==='ArrowUp'){i>0?items[i-1].focus():input.focus();e.preventDefault();}if(e.key==='Enter')window.location=a.href;if(e.key==='Escape'){close();input.focus();}});}
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
})();
</script>
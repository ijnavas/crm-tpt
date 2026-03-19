<?php $user = \App\Core\Auth::user(); ?>

<header class="topbar">
    <div class="topbar-search" id="search-wrapper">
        <input
            type="text"
            id="global-search"
            placeholder="Buscar empresa o contacto..."
            autocomplete="off"
        >
    </div>

    <div class="topbar-actions">
        <button class="topbar-btn">Filtrar</button>
        <button class="topbar-btn">Exportar</button>

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

<!-- Dropdown fuera del topbar para evitar problemas de stacking context -->
<div class="search-dropdown" id="search-dropdown"></div>

<script>
(function () {
    const input    = document.getElementById('global-search');
    const dropdown = document.getElementById('search-dropdown');
    let timer = null;

    function positionDropdown() {
        const rect = input.getBoundingClientRect();
        dropdown.style.top   = (rect.bottom + 6) + 'px';
        dropdown.style.left  = rect.left + 'px';
        dropdown.style.width = rect.width + 'px';
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { close(); return; }
        timer = setTimeout(() => fetchResults(q), 220);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { close(); }
        if (e.key === 'ArrowDown') { focusItem(0); e.preventDefault(); }
    });

    input.addEventListener('focus', function () {
        if (dropdown.classList.contains('open')) positionDropdown();
    });

    window.addEventListener('resize', function () {
        if (dropdown.classList.contains('open')) positionDropdown();
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('search-wrapper').contains(e.target) && !dropdown.contains(e.target)) {
            close();
        }
    });

    function close() {
        dropdown.innerHTML = '';
        dropdown.classList.remove('open');
    }

    function focusItem(index) {
        const items = dropdown.querySelectorAll('.search-item');
        if (items[index]) items[index].focus();
    }

    function fetchResults(q) {
        fetch('/search?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => renderResults(data))
            .catch(() => {});
    }

    function renderResults(data) {
        dropdown.innerHTML = '';
        const total = (data.companies || []).length + (data.contacts || []).length;

        if (total === 0) {
            dropdown.innerHTML = '<div class="search-empty">Sin resultados</div>';
            positionDropdown();
            dropdown.classList.add('open');
            return;
        }

        if (data.companies && data.companies.length > 0) {
            const label = document.createElement('div');
            label.className = 'search-label';
            label.textContent = 'Empresas';
            dropdown.appendChild(label);

            data.companies.forEach(c => {
                const a = document.createElement('a');
                a.className = 'search-item';
                a.href = '/companies/' + c.id;
                a.tabIndex = 0;
                a.innerHTML =
                    '<span class="search-item-icon search-item-icon--company">E</span>' +
                    '<span class="search-item-body">' +
                        '<strong>' + esc(c.name) + '</strong>' +
                        '<small>' + esc(c.sector || '') + (c.city ? ' · ' + esc(c.city) : '') + '</small>' +
                    '</span>' +
                    '<span class="search-item-badge search-item-badge--' + esc(c.status || '') + '">' + esc(c.status || '') + '</span>';
                addKeyNav(a);
                dropdown.appendChild(a);
            });
        }

        if (data.contacts && data.contacts.length > 0) {
            const label = document.createElement('div');
            label.className = 'search-label';
            label.textContent = 'Contactos';
            dropdown.appendChild(label);

            data.contacts.forEach(c => {
                const a = document.createElement('a');
                a.className = 'search-item';
                a.href = '/contacts/' + c.id;
                a.tabIndex = 0;
                a.innerHTML =
                    '<span class="search-item-icon search-item-icon--contact">' + esc((c.full_name || 'C').charAt(0).toUpperCase()) + '</span>' +
                    '<span class="search-item-body">' +
                        '<strong>' + esc(c.full_name) + '</strong>' +
                        '<small>' + esc(c.job_title || '') + (c.company_name ? ' · ' + esc(c.company_name) : '') + '</small>' +
                    '</span>';
                addKeyNav(a);
                dropdown.appendChild(a);
            });
        }

        positionDropdown();
        dropdown.classList.add('open');
    }

    function addKeyNav(a) {
        a.addEventListener('keydown', e => {
            const items = [...dropdown.querySelectorAll('.search-item')];
            const idx = items.indexOf(a);
            if (e.key === 'ArrowDown' && items[idx + 1]) { items[idx + 1].focus(); e.preventDefault(); }
            if (e.key === 'ArrowUp')  { idx > 0 ? items[idx - 1].focus() : input.focus(); e.preventDefault(); }
            if (e.key === 'Enter')    { window.location = a.href; }
            if (e.key === 'Escape')   { close(); input.focus(); }
        });
    }

    function esc(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
})();
</script>
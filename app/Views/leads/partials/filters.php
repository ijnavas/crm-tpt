<div class="leads-filters">
    <form method="GET" action="/leads" id="leads-filter-form">
        <!-- Buscador con autocompletado -->
        <div class="lf-search-wrap" id="lf-search-wrap">
            <span class="lf-search-icon">🔍</span>
            <input
                type="text"
                name="q"
                id="lf-search"
                class="lf-input"
                placeholder="Buscar empresa o contacto..."
                value="<?= htmlspecialchars($filters['q'] ?? '') ?>"
                autocomplete="off"
            >
            <div class="lf-dropdown" id="lf-dropdown"></div>
        </div>

        <!-- Estado -->
        <select name="status" class="lf-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach ([
                'nuevo'              => 'Nuevo',
                'pendiente_contacto' => 'Pendiente contacto',
                'en_seguimiento'     => 'En seguimiento',
                'cualificado'        => 'Cualificado',
                'interesado'         => 'Interesado',
                'no_interesado'      => 'No interesado',
                'convertido'         => 'Convertido',
            ] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['status'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Prioridad -->
        <select name="priority" class="lf-select" onchange="this.form.submit()">
            <option value="">Todas las prioridades</option>
            <?php foreach (['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta', 'urgente' => 'Urgente'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($filters['priority'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="lf-btn-search">Buscar</button>

        <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($filters['priority']) || !empty($filters['period'])): ?>
            <a href="/leads" class="lf-btn-clear">✕ Limpiar</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($filters['period']) || !empty($filters['status'])): ?>
    <div class="lf-active-filter">
        🔍 Mostrando:
        <?php
            if (($filters['period'] ?? '') === 'week') echo 'leads de esta semana';
            elseif (($filters['period'] ?? '') === 'today') echo 'leads de hoy';
            elseif (!empty($filters['status'])) echo ucfirst(str_replace('_', ' ', $filters['status']));
        ?>
        · <a href="/leads">Limpiar</a>
    </div>
    <?php endif; ?>
</div>

<script>
(function(){
    const input    = document.getElementById('lf-search');
    const dropdown = document.getElementById('lf-dropdown');
    const wrap     = document.getElementById('lf-search-wrap');
    let timer = null;

    input.addEventListener('input', function(){
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { close(); return; }
        timer = setTimeout(() => fetch('/search?q=' + encodeURIComponent(q))
            .then(r => r.json()).then(render).catch(() => {}), 200);
    });

    input.addEventListener('keydown', function(e){
        if (e.key === 'Escape') close();
        if (e.key === 'Enter') { document.getElementById('leads-filter-form').submit(); close(); }
        if (e.key === 'ArrowDown') { const items = dropdown.querySelectorAll('.lf-item'); if(items[0]) items[0].focus(); e.preventDefault(); }
    });

    document.addEventListener('click', function(e){
        if (!wrap.contains(e.target)) close();
    });

    function close(){ dropdown.innerHTML = ''; dropdown.classList.remove('open'); }

    function render(data){
        dropdown.innerHTML = '';
        const leads = (data.leads || []);
        const companies = (data.companies || []);
        if (!leads.length && !companies.length) {
            dropdown.innerHTML = '<div class="lf-empty">Sin resultados</div>';
            dropdown.classList.add('open'); return;
        }
        const leads = (data.leads || []);
        if (leads.length) {
            label('Leads');
            leads.forEach(l => {
                const name = l.company_name || l.full_name;
                const a = item('/leads/' + l.id,
                    '<strong>' + esc(name) + '</strong><small>' + esc(l.full_name || '') + '</small>');
                dropdown.appendChild(a);
            });
        }
        if (companies.length) {
            label('Empresas');
            companies.forEach(c => {
                const a = item('/leads?q=' + encodeURIComponent(c.name),
                    '<strong>' + esc(c.name) + '</strong><small>' + esc(c.sector || '') + '</small>');
                dropdown.appendChild(a);
            });
        }
        if (!leads.length && !companies.length) {
            dropdown.innerHTML = '<div class="lf-empty">Sin resultados</div>';
        }
        dropdown.classList.add('open');
    }

    function label(t){ const d = document.createElement('div'); d.className='lf-label'; d.textContent=t; dropdown.appendChild(d); }
    function item(href, html){
        const a = document.createElement('a');
        a.className='lf-item'; a.href=href; a.tabIndex=0; a.innerHTML=html;
        a.addEventListener('keydown', e => {
            const items=[...dropdown.querySelectorAll('.lf-item')];
            const i=items.indexOf(a);
            if(e.key==='ArrowDown'&&items[i+1]){items[i+1].focus();e.preventDefault();}
            if(e.key==='ArrowUp'){i>0?items[i-1].focus():input.focus();e.preventDefault();}
            if(e.key==='Enter'){window.location=href;}
            if(e.key==='Escape'){close();input.focus();}
        });
        return a;
    }
    function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
})();
</script>
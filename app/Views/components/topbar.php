<?php $user = \App\Core\Auth::user(); ?>

<header class="topbar">
    <div class="topbar-search">
        <input type="text" placeholder="Buscar empresa o contacto...">
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
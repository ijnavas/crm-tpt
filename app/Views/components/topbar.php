<header class="topbar">
    <div class="topbar-left">
        <strong><?= htmlspecialchars($title ?? 'CRM TPT') ?></strong>
    </div>

    <div class="topbar-right">
        <?php $user = \App\Core\Auth::user(); ?>
        <span><?= htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?></span>

        <form action="/logout" method="POST" style="display:inline;">
            <button type="submit" class="btn btn-link">Salir</button>
        </form>
    </div>
</header>

<?php $currentUserId = \App\Core\Auth::id(); ?>

<section class="page-header">
    <div>
        <h1>Gestión de usuarios</h1>
        <p><?= count($users) ?> usuario<?= count($users) !== 1 ? 's' : '' ?> registrado<?= count($users) !== 1 ? 's' : '' ?></p>
    </div>
    <a href="/admin/users/create" class="btn btn-primary">+ Nuevo usuario</a>
</section>

<?php if ($flash = \App\Core\Session::getFlash('success')): ?>
<div class="alert" style="background:#f0fdf4;color:#15803d;border-color:#bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px">
    ✓ <?= htmlspecialchars($flash) ?>
</div>
<?php endif; ?>
<?php if ($flash = \App\Core\Session::getFlash('error')): ?>
<div class="alert alert-danger"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<style>
.users-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px}
.user-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px;box-shadow:var(--shadow-soft);transition:box-shadow .15s}
.user-card:hover{box-shadow:0 6px 20px rgba(15,39,71,.09)}
.user-card-top{display:flex;align-items:center;gap:14px;margin-bottom:16px}
.user-avatar-lg{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#2f80ed,#6ab0ff);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;flex-shrink:0;overflow:hidden}
.user-avatar-lg img{width:100%;height:100%;object-fit:cover}
.user-card-name{font-size:15px;font-weight:700;color:var(--text-main)}
.user-card-email{font-size:12px;color:var(--text-soft);margin-top:2px}
.user-card-badges{display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap}
.user-card-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600}
.badge-role-admin{background:#fdf4ff;color:#7e22ce}
.badge-role-comercial{background:#eff6ff;color:#1d4ed8}
.badge-role-direccion{background:#f0fdf4;color:#15803d}
.badge-status-activo{background:#f0fdf4;color:#15803d}
.badge-status-inactivo{background:#f9fafb;color:#6b7280}
.badge-dashboard{background:#fff8e8;color:#92400e}
.user-card-actions{display:flex;gap:8px;border-top:1px solid var(--border-soft);padding-top:14px;margin-top:4px}
.user-card-inactive{opacity:.6}
</style>

<div class="users-grid">
    <?php foreach ($users as $user): ?>
    <div class="user-card <?= $user['status'] === 'inactivo' ? 'user-card-inactive' : '' ?>">

        <div class="user-card-top">
            <div class="user-avatar-lg">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="">
                <?php else: ?>
                    <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div>
                <div class="user-card-name">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    <?php if ($user['id'] === $currentUserId): ?>
                        <span style="font-size:11px;color:var(--text-soft);font-weight:400"> (tú)</span>
                    <?php endif; ?>
                </div>
                <div class="user-card-email"><?= htmlspecialchars($user['email']) ?></div>
            </div>
        </div>

        <div class="user-card-badges">
            <span class="user-card-badge badge-role-<?= $user['role_name'] ?>">
                <?= ucfirst($user['role_name']) ?>
            </span>
            <span class="user-card-badge badge-status-<?= $user['status'] ?>">
                <?= ucfirst($user['status']) ?>
            </span>
            <span class="user-card-badge badge-dashboard">
                Dashboard: <?= ucfirst($user['dashboard'] ?? 'default') ?>
            </span>
        </div>

        <div class="user-card-actions">
            <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn-sm">✏️ Editar</a>

            <?php if ($user['id'] !== $currentUserId): ?>
            <form method="POST" action="/admin/users/<?= $user['id'] ?>/toggle" style="display:inline">
                <button class="btn-sm" style="color:<?= $user['status'] === 'activo' ? 'var(--danger)' : 'var(--success)' ?>">
                    <?= $user['status'] === 'activo' ? '🔒 Desactivar' : '✓ Activar' ?>
                </button>
            </form>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>
</div>

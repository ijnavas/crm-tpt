<style>
.prof-wrap{max-width:760px;margin:0 auto}
.prof-card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.prof-card-head{padding:20px 24px;border-bottom:1px solid var(--border-soft);display:flex;align-items:center;justify-content:space-between}
.prof-card-head h2{margin:0;font-size:15px;font-weight:700;color:var(--text-main)}
.prof-body{padding:24px}

/* Avatar section */
.prof-avatar-row{display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--border-soft)}
.prof-avatar{width:80px;height:80px;border-radius:50%;object-fit:cover;background:linear-gradient(135deg,#2f80ed,#6ab0ff);display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;font-weight:700;flex-shrink:0;overflow:hidden}
.prof-avatar img{width:100%;height:100%;object-fit:cover}
.prof-avatar-info h3{margin:0 0 4px;font-size:18px;font-weight:700}
.prof-avatar-info p{margin:0 0 10px;font-size:13px;color:var(--text-soft)}
.prof-avatar-upload{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--primary);cursor:pointer;font-weight:600}
.prof-avatar-upload input{display:none}

/* Form */
.prof-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.prof-field{display:flex;flex-direction:column;gap:6px}
.prof-field label{font-size:12px;font-weight:600;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px}
.prof-field input{border:1px solid var(--border);border-radius:10px;padding:10px 13px;font-size:14px;outline:none;transition:border-color .15s}
.prof-field input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(47,128,237,.08)}
.prof-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700;background:#eff6ff;color:#1d4ed8}
.prof-badge--admin{background:#fdf4ff;color:#7e22ce}

/* Logo section */
.logo-preview{width:180px;max-height:60px;object-fit:contain;border:1px solid var(--border);border-radius:10px;padding:8px;background:#fafbfc}
.logo-upload-area{border:2px dashed var(--border);border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:border-color .15s;margin-top:12px}
.logo-upload-area:hover{border-color:var(--primary)}
.logo-upload-area input{display:none}
.logo-upload-area p{margin:6px 0 0;font-size:12px;color:var(--text-soft)}

/* Buttons */
.prof-actions{display:flex;gap:10px;margin-top:4px}
.prof-btn{height:38px;padding:0 20px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:background .15s}
.prof-btn--primary{background:var(--primary);color:#fff}
.prof-btn--primary:hover{background:var(--primary-dark)}
.prof-btn--ghost{background:var(--bg-muted);color:var(--text-main);border:1px solid var(--border)}
.prof-btn--ghost:hover{background:#eef2f7}

@media(max-width:600px){
  .prof-grid{grid-template-columns:1fr}
  .prof-avatar-row{flex-direction:column;text-align:center}
}
</style>

<div class="prof-wrap">

<?php if ($flash = \App\Core\Session::getFlash('success')): ?>
<div class="alert" style="background:#f0fdf4;color:#15803d;border-color:#bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px">
    ✓ <?= htmlspecialchars($flash) ?>
</div>
<?php endif; ?>
<?php if ($flash = \App\Core\Session::getFlash('error')): ?>
<div class="alert alert-danger"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<form action="/profile/update" method="POST" enctype="multipart/form-data">

    <!-- Perfil -->
    <div class="prof-card">
        <div class="prof-card-head">
            <h2>Mi perfil</h2>
            <span class="prof-badge <?= $isAdmin ? 'prof-badge--admin' : '' ?>">
                <?= htmlspecialchars($user['role_name'] ?? 'usuario') ?>
            </span>
        </div>
        <div class="prof-body">

            <!-- Avatar -->
            <div class="prof-avatar-row">
                <div class="prof-avatar" id="avatarPreview">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar">
                    <?php else: ?>
                        <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div class="prof-avatar-info">
                    <h3><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></h3>
                    <p><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <label class="prof-avatar-upload">
                        📷 Cambiar foto
                        <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this)">
                    </label>
                </div>
            </div>

            <!-- Datos -->
            <div class="prof-grid">
                <div class="prof-field">
                    <label>Nombre</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                </div>
                <div class="prof-field">
                    <label>Apellidos</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                </div>
                <div class="prof-field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="prof-actions">
                <button type="submit" class="prof-btn prof-btn--primary">Guardar cambios</button>
            </div>
        </div>
    </div>

    <!-- Cambiar contraseña -->
    <div class="prof-card">
        <div class="prof-card-head"><h2>Cambiar contraseña</h2></div>
        <div class="prof-body">
            <div class="prof-grid">
                <div class="prof-field">
                    <label>Contraseña actual</label>
                    <input type="password" name="current_password" placeholder="••••••••">
                </div>
                <div class="prof-field"></div>
                <div class="prof-field">
                    <label>Nueva contraseña</label>
                    <input type="password" name="new_password" placeholder="••••••••">
                </div>
                <div class="prof-field">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" name="confirm_password" placeholder="••••••••">
                </div>
            </div>
            <div class="prof-actions">
                <button type="submit" class="prof-btn prof-btn--primary">Cambiar contraseña</button>
            </div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
    <!-- Logo (solo admin) -->
    <div class="prof-card">
        <div class="prof-card-head"><h2>Logo de la aplicación</h2></div>
        <div class="prof-body">
            <p style="font-size:13px;color:var(--text-soft);margin:0 0 14px">
                Este logo aparecerá en el sidebar. Tamaño máximo: 2MB. Formatos: PNG, JPG, SVG, WEBP.
            </p>

            <?php if (!empty($logo)): ?>
                <img src="<?= htmlspecialchars($logo) ?>" class="logo-preview" id="logoImg" alt="Logo actual">
            <?php else: ?>
                <div style="font-size:13px;color:var(--text-soft);margin-bottom:12px">Sin logo personalizado</div>
            <?php endif; ?>

            <label class="logo-upload-area" for="logoInput">
                <div style="font-size:24px">🖼️</div>
                <strong style="font-size:13px;color:var(--text-main)">Haz clic para subir un logo</strong>
                <p>PNG, JPG, SVG o WEBP · Máx. 2MB · Se mostrará a 40px de alto</p>
                <input type="file" id="logoInput" name="logo" accept="image/*" onchange="previewLogo(this)">
            </label>

            <div class="prof-actions" style="margin-top:16px">
                <button type="submit" class="prof-btn prof-btn--primary">Actualizar logo</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

</form>
</div>

<script>
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var prev = document.getElementById('avatarPreview');
        prev.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
    };
    reader.readAsDataURL(input.files[0]);
}

function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('logoImg');
        if (img) {
            img.src = e.target.result;
        } else {
            var newImg = document.createElement('img');
            newImg.id = 'logoImg';
            newImg.className = 'logo-preview';
            newImg.src = e.target.result;
            input.closest('.prof-body').insertBefore(newImg, input.closest('label'));
        }
    };
    reader.readAsDataURL(input.files[0]);
}
</script>

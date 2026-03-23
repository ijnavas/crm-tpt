<?php $isEdit = !empty($user['id']); ?>

<section class="page-header">
    <div>
        <h1><?= $isEdit ? 'Editar usuario' : 'Nuevo usuario' ?></h1>
        <?php if ($isEdit): ?>
        <p><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if ($flash = \App\Core\Session::getFlash('error')): ?>
<div class="alert alert-danger"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<style>
.uf-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.uf-card-head{padding:18px 22px;border-bottom:1px solid var(--border-soft);font-size:14px;font-weight:700;color:var(--text-main)}
.uf-body{padding:22px}
.uf-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:4px}
.uf-field{display:flex;flex-direction:column;gap:6px}
.uf-field label{font-size:12px;font-weight:600;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px}
.uf-field input,.uf-field select{border:1px solid var(--border);border-radius:10px;padding:10px 13px;font-size:14px;outline:none}
.uf-field input:focus,.uf-field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(47,128,237,.08)}
.uf-hint{font-size:11px;color:var(--text-soft);margin-top:4px}
.dashboard-options{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:4px}
.dashboard-opt{border:2px solid var(--border);border-radius:10px;padding:14px;cursor:pointer;text-align:center;transition:border-color .15s,background .15s}
.dashboard-opt:hover{border-color:var(--primary);background:#f5f9ff}
.dashboard-opt.selected{border-color:var(--primary);background:#eff6ff}
.dashboard-opt input{display:none}
.dashboard-opt-icon{font-size:24px;margin-bottom:6px}
.dashboard-opt-label{font-size:13px;font-weight:600;color:var(--text-main)}
.dashboard-opt-desc{font-size:11px;color:var(--text-soft);margin-top:3px}
@media(max-width:600px){.uf-grid{grid-template-columns:1fr}.dashboard-options{grid-template-columns:1fr}}
</style>

<form action="<?= $action ?>" method="POST">

    <!-- Datos personales -->
    <div class="uf-card">
        <div class="uf-card-head">Datos personales</div>
        <div class="uf-body">
            <div class="uf-grid">
                <div class="uf-field">
                    <label>Nombre</label>
                    <input type="text" name="first_name" class="autocap"
                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                </div>
                <div class="uf-field">
                    <label>Apellidos</label>
                    <input type="text" name="last_name" class="autocap"
                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                </div>
                <div class="uf-field">
                    <label>Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                <div class="uf-field">
                    <label>Estado</label>
                    <select name="status">
                        <option value="activo" <?= (($user['status'] ?? 'activo') === 'activo') ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= (($user['status'] ?? '') === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Rol -->
    <div class="uf-card">
        <div class="uf-card-head">Rol y permisos</div>
        <div class="uf-body">
            <div class="uf-grid">
                <div class="uf-field">
                    <label>Rol</label>
                    <select name="role_id">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"
                                <?= (($user['role_id'] ?? null) == $role['id']) ? 'selected' : '' ?>>
                                <?= ucfirst($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard -->
    <div class="uf-card">
        <div class="uf-card-head">Dashboard asignado</div>
        <div class="uf-body">
            <p style="font-size:13px;color:var(--text-soft);margin:0 0 14px">
                Selecciona qué dashboard verá este usuario al iniciar sesión.
            </p>
            <div class="dashboard-options">
                <?php
                $dashboards = [
                    'default'   => ['icon' => '⊞', 'label' => 'General',    'desc' => 'Dashboard completo con todos los KPIs'],
                    'comercial' => ['icon' => '📋', 'label' => 'Comercial',  'desc' => 'Mis leads, tareas y objetivos'],
                    'direccion' => ['icon' => '📊', 'label' => 'Dirección',  'desc' => 'Rendimiento global del equipo'],
                ];
                $current = $user['dashboard'] ?? 'default';
                foreach ($dashboards as $val => $d):
                ?>
                <label class="dashboard-opt <?= $current === $val ? 'selected' : '' ?>"
                       onclick="selectDashboard(this, '<?= $val ?>')">
                    <input type="radio" name="dashboard" value="<?= $val ?>" <?= $current === $val ? 'checked' : '' ?>>
                    <div class="dashboard-opt-icon"><?= $d['icon'] ?></div>
                    <div class="dashboard-opt-label"><?= $d['label'] ?></div>
                    <div class="dashboard-opt-desc"><?= $d['desc'] ?></div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Contraseña -->
    <div class="uf-card">
        <div class="uf-card-head"><?= $isEdit ? 'Cambiar contraseña' : 'Contraseña' ?></div>
        <div class="uf-body">
            <?php if ($isEdit): ?>
            <p class="uf-hint" style="margin-bottom:14px">Deja en blanco para mantener la contraseña actual.</p>
            <?php endif; ?>
            <div class="uf-grid">
                <div class="uf-field">
                    <label><?= $isEdit ? 'Nueva contraseña' : 'Contraseña' ?></label>
                    <input type="password" name="<?= $isEdit ? 'new_password' : 'password' ?>"
                           placeholder="••••••••" <?= $isEdit ? '' : 'required' ?>>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <?= $isEdit ? 'Guardar cambios' : 'Crear usuario' ?>
        </button>
        <a href="/admin/users" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<script>
function selectDashboard(label, val) {
    document.querySelectorAll('.dashboard-opt').forEach(el => el.classList.remove('selected'));
    label.classList.add('selected');
    label.querySelector('input').checked = true;
}
document.querySelectorAll('input.autocap').forEach(function(input) {
    if (input.value) input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) { this.value = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase(); this.setSelectionRange(pos, pos); }
    });
});
</script>

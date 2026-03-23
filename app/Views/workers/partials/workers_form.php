<?php $worker = $worker ?? []; ?>
<style>
.wf-section{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:20px}
.wf-head{padding:16px 20px;border-bottom:1px solid var(--border-soft);font-size:14px;font-weight:700;color:var(--text-main)}
.wf-body{padding:20px}
.wf-check{display:flex;align-items:center;gap:10px;padding:12px 16px;border:1px solid var(--border);border-radius:10px;cursor:pointer}
.wf-check input{width:18px;height:18px;cursor:pointer;accent-color:var(--primary)}
</style>

<!-- Datos personales -->
<div class="wf-section">
    <div class="wf-head">Datos personales</div>
    <div class="wf-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="first_name" class="autocap"
                       value="<?= htmlspecialchars($worker['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="last_name" class="autocap"
                       value="<?= htmlspecialchars($worker['last_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>DNI / NIE</label>
                <input type="text" name="dni" style="text-transform:uppercase"
                       value="<?= htmlspecialchars($worker['dni'] ?? '') ?>"
                       placeholder="12345678A">
            </div>
            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="birth_date"
                       value="<?= htmlspecialchars($worker['birth_date'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($worker['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Móvil</label>
                <input type="text" name="mobile"
                       value="<?= htmlspecialchars($worker['mobile'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($worker['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="status">
                    <?php foreach ($catalogs['statuses'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($worker['status'] ?? 'activo') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Discapacidad -->
<div class="wf-section">
    <div class="wf-head">Discapacidad</div>
    <div class="wf-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Tipo de discapacidad</label>
                <select name="disability_type">
                    <option value="">— Sin especificar —</option>
                    <?php foreach ($catalogs['disability_types'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($worker['disability_type'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Grado de discapacidad (%)</label>
                <input type="number" name="disability_degree" min="0" max="100"
                       value="<?= htmlspecialchars($worker['disability_degree'] ?? '') ?>"
                       placeholder="Ej: 33">
            </div>
        </div>
    </div>
</div>

<!-- Disponibilidad -->
<div class="wf-section">
    <div class="wf-head">Disponibilidad y jornada</div>
    <div class="wf-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Disponibilidad horaria</label>
                <select name="availability">
                    <option value="">— Sin especificar —</option>
                    <?php foreach ($catalogs['availability'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($worker['availability'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de jornada</label>
                <select name="schedule_type">
                    <option value="">— Sin especificar —</option>
                    <?php foreach ($catalogs['schedule_types'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($worker['schedule_type'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <label class="wf-check" style="margin-top:8px">
            <input type="checkbox" name="driving_license" value="1"
                   <?= !empty($worker['driving_license']) ? 'checked' : '' ?>>
            <div>
                <div style="font-size:13px;font-weight:600">Carnet de conducir</div>
                <div style="font-size:12px;color:var(--text-soft)">El trabajador dispone de carnet de conducir</div>
            </div>
        </label>
    </div>
</div>

<!-- Dirección -->
<div class="wf-section">
    <div class="wf-head">Dirección</div>
    <div class="wf-body">
        <div class="form-grid">
            <div class="form-group" style="grid-column:1/-1">
                <label>Dirección</label>
                <input type="text" name="address" class="autocap"
                       value="<?= htmlspecialchars($worker['address'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Ciudad</label>
                <input type="text" name="city" class="autocap"
                       value="<?= htmlspecialchars($worker['city'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Provincia</label>
                <input type="text" name="province" class="autocap"
                       value="<?= htmlspecialchars($worker['province'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Código postal</label>
                <input type="text" name="postal_code"
                       value="<?= htmlspecialchars($worker['postal_code'] ?? '') ?>">
            </div>
        </div>
    </div>
</div>

<!-- Formación -->
<div class="wf-section">
    <div class="wf-head">Formación y habilidades</div>
    <div class="wf-body">
        <div class="form-group">
            <label>Habilidades y formación</label>
            <textarea name="skills" rows="3"
                      placeholder="Ej: Limpieza industrial, carretillero, atención al cliente..."><?= htmlspecialchars($worker['skills'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Notas internas</label>
            <textarea name="notes" rows="3"><?= htmlspecialchars($worker['notes'] ?? '') ?></textarea>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    if (input.value) input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) { this.value = val.charAt(0).toUpperCase() + val.slice(1); this.setSelectionRange(pos, pos); }
    });
});
</script>

<?php $contact = $contact ?? []; ?>

<div class="form-grid">

    <div class="form-group">
        <label>Empresa</label>
        <select name="company_id" required>
            <option value="">Selecciona empresa</option>
            <?php foreach ($catalogs['companies'] as $company): ?>
                <option value="<?= $company['id'] ?>" <?= (($contact['company_id'] ?? null) == $company['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst(strtolower($company['name']))) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="first_name" class="autocap"
               value="<?= htmlspecialchars($contact['first_name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="last_name" class="autocap"
               value="<?= htmlspecialchars($contact['last_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Cargo</label>
        <input type="text" name="job_title" class="autocap"
               value="<?= htmlspecialchars($contact['job_title'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Departamento</label>
        <input type="text" name="department" class="autocap"
               value="<?= htmlspecialchars($contact['department'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Móvil</label>
        <input type="text" name="mobile"
               value="<?= htmlspecialchars($contact['mobile'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Canal preferido</label>
        <select name="preferred_channel">
            <option value="">— Sin definir —</option>
            <?php foreach (['email'=>'Email','telefono'=>'Teléfono','whatsapp'=>'WhatsApp','presencial'=>'Presencial'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($contact['preferred_channel'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Horario de contacto</label>
        <input type="text" name="contact_schedule" class="autocap"
               value="<?= htmlspecialchars($contact['contact_schedule'] ?? '') ?>"
               placeholder="Ej: Mañanas de 9 a 13h">
    </div>

    <div class="form-group">
        <label>Nivel de decisión</label>
        <select name="decision_level">
            <option value="">— Sin definir —</option>
            <?php foreach (['decisor'=>'Decisor','influenciador'=>'Influenciador','usuario'=>'Usuario','bloqueador'=>'Bloqueador'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($contact['decision_level'] ?? '') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach (['activo'=>'Activo','inactivo'=>'Inactivo','sin_respuesta'=>'Sin respuesta'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($contact['status'] ?? 'activo') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Contacto principal</label>
        <select name="is_primary">
            <option value="0" <?= empty($contact['is_primary']) ? 'selected' : '' ?>>No</option>
            <option value="1" <?= !empty($contact['is_primary']) ? 'selected' : '' ?>>Sí</option>
        </select>
    </div>

</div>

<div class="form-group">
    <label>Notas internas</label>
    <textarea name="notes_internal" rows="3"><?= htmlspecialchars($contact['notes_internal'] ?? '') ?></textarea>
</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    if (input.value) input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) {
            this.value = val.charAt(0).toUpperCase() + val.slice(1);
            this.setSelectionRange(pos, pos);
        }
    });
});
</script>
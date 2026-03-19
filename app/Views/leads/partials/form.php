<div class="form-grid">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="first_name" class="autocap" value="<?= htmlspecialchars($lead['first_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="last_name" class="autocap" value="<?= htmlspecialchars($lead['last_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Empresa</label>
        <input type="text" name="company_name" class="autocap" value="<?= htmlspecialchars($lead['company_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($lead['phone'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Fecha de alta</label>
        <input type="date" name="created_at_manual"
               value="<?= htmlspecialchars($lead['created_at_manual'] ?? date('Y-m-d')) ?>">
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $status): ?>
                <option value="<?= $status ?>" <?= (($lead['status'] ?? 'nuevo') === $status) ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Prioridad</label>
        <select name="priority">
            <?php foreach ($catalogs['priorities'] as $priority): ?>
                <option value="<?= $priority ?>" <?= (($lead['priority'] ?? 'media') === $priority) ? 'selected' : '' ?>>
                    <?= ucfirst($priority) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Origen</label>
        <select name="source">
            <?php foreach ($catalogs['sources'] as $source): ?>
                <option value="<?= $source ?>" <?= (($lead['source'] ?? 'web') === $source) ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $source)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Notas internas</label>
    <textarea name="notes_internal" rows="4"><?= htmlspecialchars($lead['notes_internal'] ?? '') ?></textarea>
</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    input.addEventListener('input', function() {
        var pos = this.selectionStart;
        var val = this.value;
        if (val.length > 0) {
            this.value = val.charAt(0).toUpperCase() + val.slice(1);
            this.setSelectionRange(pos, pos);
        }
    });
});
</script>
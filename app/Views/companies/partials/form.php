<?php $company = $company ?? []; ?>

<div class="form-grid">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="autocap"
               value="<?= htmlspecialchars($company['name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Razón social</label>
        <input type="text" name="legal_name" class="autocap"
               value="<?= htmlspecialchars($company['legal_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>CIF</label>
        <input type="text" name="tax_id"
               value="<?= htmlspecialchars($company['tax_id'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Sector</label>
        <input type="text" name="sector" class="autocap"
               value="<?= htmlspecialchars($company['sector'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($company['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $status): ?>
                <option value="<?= $status ?>" <?= (($company['status'] ?? 'prospecto') === $status) ? 'selected' : '' ?>>
                    <?= ucfirst($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Notas internas</label>
    <textarea name="notes_internal" rows="4"><?= htmlspecialchars($company['notes_internal'] ?? '') ?></textarea>
</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    // Mostrar valor existente con primera en mayúscula
    if (input.value) {
        input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    }
    input.addEventListener('input', function() {
        var pos = this.selectionStart;
        var val = this.value;
        if (val.length > 0) {
            this.value = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase();
            this.setSelectionRange(pos, pos);
        }
    });
});
</script>
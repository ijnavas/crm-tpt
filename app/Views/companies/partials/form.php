<?php $company = $company ?? []; ?>

<div class="form-grid">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" value="<?= htmlspecialchars($company['name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Razón social</label>
        <input type="text" name="legal_name" value="<?= htmlspecialchars($company['legal_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>CIF</label>
        <input type="text" name="tax_id" value="<?= htmlspecialchars($company['tax_id'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Sector</label>
        <input type="text" name="sector" value="<?= htmlspecialchars($company['sector'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($company['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $status): ?>
                <option value="<?= $status ?>" <?= (($company['status'] ?? 'prospecto') === $status) ? 'selected' : '' ?>>
                    <?= $status ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Notas internas</label>
    <textarea name="notes_internal" rows="4"><?= htmlspecialchars($company['notes_internal'] ?? '') ?></textarea>
</div>
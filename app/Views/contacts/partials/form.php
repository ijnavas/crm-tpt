<?php $contact = $contact ?? []; ?>

<div class="form-grid">
    <div class="form-group">
        <label>Empresa</label>
        <select name="company_id" required>
            <option value="">Selecciona empresa</option>
            <?php foreach ($catalogs['companies'] as $company): ?>
                <option value="<?= $company['id'] ?>" <?= (($contact['company_id'] ?? null) == $company['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($company['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($contact['first_name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($contact['last_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Cargo</label>
        <input type="text" name="job_title" value="<?= htmlspecialchars($contact['job_title'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
    </div>
</div>
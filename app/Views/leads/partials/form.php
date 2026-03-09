<div class="form-grid">
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($lead['first_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Apellidos</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($lead['last_name'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Empresa</label>
        <input type="text" name="company_name" value="<?= htmlspecialchars($lead['company_name'] ?? '') ?>">
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
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $status): ?>
                <option value="<?= $status ?>" <?= (($lead['status'] ?? 'nuevo') === $status) ? 'selected' : '' ?>>
                    <?= $status ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Prioridad</label>
        <select name="priority">
            <?php foreach ($catalogs['priorities'] as $priority): ?>
                <option value="<?= $priority ?>" <?= (($lead['priority'] ?? 'media') === $priority) ? 'selected' : '' ?>>
                    <?= $priority ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Origen</label>
        <select name="source">
            <?php foreach ($catalogs['sources'] as $source): ?>
                <option value="<?= $source ?>" <?= (($lead['source'] ?? 'web') === $source) ? 'selected' : '' ?>>
                    <?= $source ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Notas internas</label>
    <textarea name="notes_internal" rows="4"><?= htmlspecialchars($lead['notes_internal'] ?? '') ?></textarea>
</div>

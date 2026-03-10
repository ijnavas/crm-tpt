<?php $task = $task ?? []; ?>

<div class="form-grid">
    <div class="form-group">
        <label>Título</label>
        <input type="text" name="title" value="<?= htmlspecialchars($task['title'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Tipo</label>
        <select name="type">
            <?php foreach ($catalogs['types'] as $type): ?>
                <option value="<?= $type ?>" <?= (($task['type'] ?? 'seguimiento') === $type) ? 'selected' : '' ?>>
                    <?= $type ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Prioridad</label>
        <select name="priority">
            <?php foreach ($catalogs['priorities'] as $priority): ?>
                <option value="<?= $priority ?>" <?= (($task['priority'] ?? 'media') === $priority) ? 'selected' : '' ?>>
                    <?= $priority ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $status): ?>
                <option value="<?= $status ?>" <?= (($task['status'] ?? 'pendiente') === $status) ? 'selected' : '' ?>>
                    <?= $status ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tipo entidad</label>
        <select name="entity_type" required>
            <option value="lead">Lead</option>
            <option value="company">Empresa</option>
            <option value="contact">Contacto</option>
        </select>
    </div>

    <div class="form-group">
        <label>ID entidad</label>
        <input type="number" name="entity_id" value="<?= htmlspecialchars($task['entity_id'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Fecha vencimiento</label>
        <input type="datetime-local" name="due_date" value="<?= isset($task['due_date']) ? date('Y-m-d\\TH:i', strtotime($task['due_date'])) : '' ?>" required>
    </div>
</div>

<div class="form-group">
    <label>Descripción</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
</div>
<?php $task = $task ?? []; ?>

<div class="form-grid">

    <div class="form-group">
        <label>Título</label>
        <input type="text" name="title" class="autocap"
               value="<?= htmlspecialchars($task['title'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Tipo</label>
        <select name="type">
            <?php foreach ($catalogs['types'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($task['type'] ?? 'llamada') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Entidad</label>
        <select name="entity_type" id="entity_type" onchange="loadEntities(this.value)">
            <?php foreach ($catalogs['entity_types'] as $val => $label): ?>
                <option value="<?= $val ?>"
                    <?= (($task['entity_type'] ?? $entityType ?? 'company') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Asignada a</label>
        <select name="entity_id" id="entity_id">
            <?php if (!empty($task['entity_id'])): ?>
                <option value="<?= $task['entity_id'] ?>" selected>
                    <?= htmlspecialchars($task['entity_name'] ?? '#' . $task['entity_id']) ?>
                </option>
            <?php elseif (!empty($entityId)): ?>
                <option value="<?= $entityId ?>" selected>#<?= $entityId ?></option>
            <?php else: ?>
                <option value="">— Selecciona —</option>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Fecha inicio</label>
        <input type="datetime-local" name="start_date"
               value="<?= htmlspecialchars($task['start_date'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Fecha vencimiento</label>
        <input type="datetime-local" name="due_date"
               value="<?= htmlspecialchars($task['due_date'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Recordatorio</label>
        <input type="datetime-local" name="reminder_at"
               value="<?= htmlspecialchars($task['reminder_at'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Prioridad</label>
        <select name="priority">
            <?php foreach ($catalogs['priorities'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($task['priority'] ?? 'media') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="status">
            <?php foreach ($catalogs['statuses'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($task['status'] ?? 'pendiente') === $val) ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

</div>

<div class="form-group">
    <label>Notas / Resultado</label>
    <textarea name="notes" rows="3"><?= htmlspecialchars($task['notes'] ?? $task['result'] ?? '') ?></textarea>
</div>

<script>
const entityId   = '<?= $task['entity_id'] ?? $entityId ?? '' ?>';
const entityType = '<?= $task['entity_type'] ?? $entityType ?? 'company' ?>';

function loadEntities(type) {
    const select = document.getElementById('entity_id');
    select.innerHTML = '<option value="">Cargando...</option>';
    fetch('/search/entities?type=' + type)
        .then(r => r.json())
        .then(data => {
            select.innerHTML = '<option value="">— Selecciona —</option>';
            data.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.id;
                opt.textContent = e.name;
                if (String(e.id) === String(entityId) && type === entityType) opt.selected = true;
                select.appendChild(opt);
            });
        });
}

// Cargar entidades al iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadEntities(document.getElementById('entity_type').value);
});

// Autocap
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
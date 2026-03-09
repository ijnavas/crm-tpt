<section class="card">
    <form method="GET" action="/leads" class="filters-grid">
        <div class="form-group">
            <label>Buscar</label>
            <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Estado</label>
            <select name="status">
                <option value="">Todos</option>
                <?php foreach (['nuevo', 'pendiente_contacto', 'en_seguimiento', 'cualificado'] as $status): ?>
                    <option value="<?= $status ?>" <?= (($filters['status'] ?? '') === $status) ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Prioridad</label>
            <select name="priority">
                <option value="">Todas</option>
                <?php foreach (['baja', 'media', 'alta', 'urgente'] as $priority): ?>
                    <option value="<?= $priority ?>" <?= (($filters['priority'] ?? '') === $priority) ? 'selected' : '' ?>><?= $priority ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions align-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="/leads" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>
</section>

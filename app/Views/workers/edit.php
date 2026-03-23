<section class="page-header">
    <div>
        <h1>Editar trabajador</h1>
        <p><?= htmlspecialchars($worker['full_name'] ?? '') ?></p>
    </div>
</section>
<form action="/workers/<?= $worker['id'] ?>/update" method="POST">
    <?php require app_path('Views/workers/partials/form.php'); ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="/workers/<?= $worker['id'] ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

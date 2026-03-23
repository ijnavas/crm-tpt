<section class="page-header">
    <div>
        <h1>Editar contrato</h1>
        <p><?= htmlspecialchars($contract['title'] ?? '') ?></p>
    </div>
</section>
<form action="/contracts/<?= $contract['id'] ?>/update" method="POST" enctype="multipart/form-data">
    <?php require app_path('Views/contracts/partials/form.php'); ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="/contracts/<?= $contract['id'] ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
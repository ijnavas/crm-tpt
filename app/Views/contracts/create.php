<section class="page-header">
    <div><h1>Nuevo contrato</h1></div>
</section>
<form action="/contracts/store" method="POST">
    <?php require app_path('Views/contracts/partials/form.php'); ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear contrato</button>
        <a href="/contracts" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

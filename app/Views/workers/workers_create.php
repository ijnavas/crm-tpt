<section class="page-header">
    <div><h1>Nuevo trabajador</h1></div>
</section>
<form action="/workers/store" method="POST">
    <?php require app_path('Views/workers/partials/form.php'); ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear trabajador</button>
        <a href="/workers" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

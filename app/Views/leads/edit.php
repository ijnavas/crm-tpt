<section class="page-header">
    <h1>Editar lead</h1>
</section>

<section class="card">
    <form action="/leads/<?= $lead['id'] ?>/update" method="POST">
        <?php require app_path('Views/leads/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="/leads/<?= $lead['id'] ?>" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</section>

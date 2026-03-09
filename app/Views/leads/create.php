<section class="page-header">
    <h1>Nuevo lead</h1>
</section>

<section class="card">
    <form action="/leads/store" method="POST">
        <?php $lead = []; require app_path('Views/leads/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/leads" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>

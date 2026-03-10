<section class="page-header">
    <h1>Nuevo contacto</h1>
</section>

<section class="card">
    <form action="/contacts/store" method="POST">
        <?php require app_path('Views/contacts/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/contacts" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
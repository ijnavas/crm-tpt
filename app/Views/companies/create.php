<section class="page-header">
    <h1>Nueva empresa</h1>
</section>

<section class="card">
    <form action="/companies/store" method="POST">
        <?php require app_path('Views/companies/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/companies" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
<section class="page-header">
    <div>
        <h1>Editar empresa</h1>
        <p><?= htmlspecialchars($company['name'] ?? '') ?></p>
    </div>
</section>

<section class="card">
    <form action="/companies/<?= $company['id'] ?>/update" method="POST">
        <?php require app_path('Views/companies/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="/companies/<?= $company['id'] ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
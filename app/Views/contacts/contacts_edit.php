<section class="page-header">
    <div>
        <h1>Editar contacto</h1>
        <p><?= htmlspecialchars($contact['full_name'] ?? '') ?></p>
    </div>
</section>

<section class="card">
    <form action="/contacts/<?= $contact['id'] ?>/update" method="POST">
        <?php require app_path('Views/contacts/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="/contacts/<?= $contact['id'] ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>

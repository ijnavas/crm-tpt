<section class="page-header">
    <h1>Nueva tarea</h1>
</section>

<section class="card">
    <form action="/tasks/store" method="POST">
        <?php require app_path('Views/tasks/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/tasks" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
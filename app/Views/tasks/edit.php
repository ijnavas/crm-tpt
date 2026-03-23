<section class="page-header">
    <div>
        <h1>Editar tarea</h1>
        <p><?= htmlspecialchars($task['title'] ?? '') ?></p>
    </div>
</section>

<section class="card">
    <form action="/tasks/<?= $task['id'] ?>/update" method="POST">
        <?php require app_path('Views/tasks/partials/form.php'); ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="/tasks" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
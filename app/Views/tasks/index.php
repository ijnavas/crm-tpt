<section class="page-header">
    <div>
        <h1>Tareas</h1>
        <p>Seguimiento comercial y operativo</p>
    </div>
    <a href="/tasks/create" class="btn btn-primary">Nueva tarea</a>
</section>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Vencimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td>#<?= $task['id'] ?></td>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['type']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td><?= htmlspecialchars($task['priority']) ?></td>
                    <td><?= htmlspecialchars($task['due_date']) ?></td>
                    <td>
                        <a href="/tasks/<?= $task['id'] ?>">Ver</a>
                        <a href="/tasks/<?= $task['id'] ?>/edit">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
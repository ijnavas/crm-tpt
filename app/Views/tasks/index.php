<section class="page-header">
    <div>
        <h1>Tareas</h1>
        <p>Seguimiento comercial y operativo</p>
    </div>
    <a href="/tasks/create" class="btn btn-primary">Nueva tarea</a>
</section>

<section class="card">
    <?php if (empty($tasks)): ?>
        <p>No hay tareas.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Entidad</th>
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
                        <td><?= htmlspecialchars($task['title'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(($task['entity_type'] ?? '-') . ' #' . ($task['entity_id'] ?? '-')) ?></td>
                        <td><?= htmlspecialchars($task['status'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($task['priority'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($task['due_date'] ?? '-') ?></td>
                        <td>
                            <?php if (($task['status'] ?? '') !== 'completada'): ?>
                                <form method="POST" action="/tasks/<?= $task['id'] ?>/complete" style="display:inline;">
                                    <button class="btn btn-sm">Completar</button>
                                </form>
                            <?php else: ?>
                                <span>OK</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
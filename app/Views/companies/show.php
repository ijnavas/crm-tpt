<?php $company = $companyDetail['company']; ?>

<section class="page-header">
    <div>
        <h1><?= htmlspecialchars($company['name'] ?? 'Empresa') ?></h1>
        <p><?= htmlspecialchars($company['status'] ?? '') ?></p>
    </div>

    <div>
        <a href="/tasks/create?entity_type=company&entity_id=<?= $company['id'] ?>" class="btn btn-primary">
            + Nueva tarea
        </a>

        <a href="/companies" class="btn btn-secondary">
            Volver
        </a>
    </div>
</section>

<!-- DATOS GENERALES -->

<section class="card">

<h2>Datos generales</h2>

<table class="table">

<tr>
<td><strong>Nombre</strong></td>
<td><?= htmlspecialchars($company['name'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Email</strong></td>
<td><?= htmlspecialchars($company['email'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Teléfono</strong></td>
<td><?= htmlspecialchars($company['phone'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Sector</strong></td>
<td><?= htmlspecialchars($company['sector'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Ciudad</strong></td>
<td><?= htmlspecialchars($company['city'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Provincia</strong></td>
<td><?= htmlspecialchars($company['province'] ?? '-') ?></td>
</tr>

<tr>
<td><strong>Estado</strong></td>
<td><?= htmlspecialchars($company['status'] ?? '-') ?></td>
</tr>

</table>

</section>


<!-- CONTACTOS -->

<section class="card">

<h2>Contactos</h2>

<?php if (empty($companyDetail['contacts'])): ?>

<p>No hay contactos.</p>

<?php else: ?>

<table class="table">

<tr>
<th>Nombre</th>
<th>Cargo</th>
<th>Email</th>
<th>Teléfono</th>
</tr>

<?php foreach ($companyDetail['contacts'] as $contact): ?>

<tr>
<td><?= htmlspecialchars($contact['full_name'] ?? '') ?></td>
<td><?= htmlspecialchars($contact['job_title'] ?? '-') ?></td>
<td><?= htmlspecialchars($contact['email'] ?? '-') ?></td>
<td><?= htmlspecialchars($contact['phone'] ?? '-') ?></td>
</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

</section>


<!-- TAREAS -->

<section class="card">

<h2>Tareas</h2>

<?php if (empty($companyDetail['tasks'])): ?>

<p>No hay tareas.</p>

<?php else: ?>

<table class="table">

<tr>
<th>Tarea</th>
<th>Estado</th>
<th>Prioridad</th>
<th>Fecha</th>
<th></th>
</tr>

<?php foreach ($companyDetail['tasks'] as $task): ?>

<tr>

<td><?= htmlspecialchars($task['title']) ?></td>

<td><?= htmlspecialchars($task['status']) ?></td>

<td><?= htmlspecialchars($task['priority'] ?? '-') ?></td>

<td><?= htmlspecialchars($task['due_date'] ?? '-') ?></td>

<td>

<?php if ($task['status'] !== 'completada'): ?>

<form method="POST" action="/tasks/<?= $task['id'] ?>/complete">

<button class="btn btn-sm">Completar</button>

</form>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

</section>


<!-- HISTORIAL -->

<section class="card">

<h2>Historial</h2>

<?php if (empty($companyDetail['timeline'])): ?>

<p>No hay historial.</p>

<?php else: ?>

<ul>

<?php foreach ($companyDetail['timeline'] as $item): ?>

<li>
<?= htmlspecialchars($item['description'] ?? '') ?>
 ·
<?= htmlspecialchars($item['created_at'] ?? '') ?>
</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

</section>
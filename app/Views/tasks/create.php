<section class="page-header">
<h1>Nueva tarea</h1>
</section>

<section class="card">

<form action="/tasks/store" method="POST">

<input type="hidden" name="entity_type" value="<?= $entityType ?>">
<input type="hidden" name="entity_id" value="<?= $entityId ?>">

<div class="form-group">
<label>Título</label>
<input type="text" name="title" required>
</div>

<div class="form-group">
<label>Descripción</label>
<textarea name="description"></textarea>
</div>

<div class="form-group">
<label>Prioridad</label>
<select name="priority">
<option value="baja">Baja</option>
<option value="media" selected>Media</option>
<option value="alta">Alta</option>
</select>
</div>

<div class="form-group">
<label>Fecha límite</label>
<input type="datetime-local" name="due_date">
</div>

<button class="btn btn-primary">Guardar tarea</button>

</form>

</section>
<?php
$statusColors = [
    'activo'          => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'baja_temporal'   => ['bg'=>'#fefce8','color'=>'#a16207'],
    'baja_definitiva' => ['bg'=>'#fef2f2','color'=>'#b91c1c'],
    'en_formacion'    => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
];
$sc = $statusColors[$worker['status'] ?? 'activo'] ?? $statusColors['activo'];
$entityLabels = ['company'=>'Empresa','contract'=>'Contrato','lead'=>'Lead','task'=>'Tarea'];
$entityIcons  = ['company'=>'🏢','contract'=>'📋','lead'=>'◈','task'=>'✅'];
?>
<style>
.wk-hero{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px;box-shadow:var(--shadow-soft)}
.wk-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:20px}
.wk-initial{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#10b98122,#10b98155);color:#059669;font-size:22px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.wk-name{font-size:20px;font-weight:700;color:var(--text-main);margin:0 0 8px}
.wk-badges{display:flex;gap:6px;flex-wrap:wrap}
.wk-meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.wk-meta-item{display:flex;flex-direction:column;gap:3px}
.wk-meta-label{font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.4px}
.wk-meta-value{font-size:13px;color:var(--text-main);font-weight:500}
.wk-section{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow-soft)}
.wk-section-head{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border-soft)}
.wk-section-head h3{margin:0;font-size:14px;font-weight:700}
.assign-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border-soft)}
.assign-row:last-child{border-bottom:none}
.assign-icon{width:32px;height:32px;border-radius:8px;background:var(--bg-muted);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.assign-add{padding:16px 20px;background:#fafbfc;border-top:1px solid var(--border-soft)}
@media(max-width:768px){.wk-meta-grid{grid-template-columns:repeat(2,1fr)}}
</style>

<!-- Hero -->
<div class="wk-hero">
    <div class="wk-hero-top">
        <div style="display:flex;align-items:center;gap:16px">
            <div class="wk-initial"><?= strtoupper(substr($worker['first_name'] ?? 'T', 0, 1)) ?></div>
            <div>
                <h1 class="wk-name"><?= htmlspecialchars($worker['full_name']) ?></h1>
                <div class="wk-badges">
                    <span class="lead-status-badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>">
                        <?= ucfirst(str_replace('_',' ',$worker['status'])) ?>
                    </span>
                    <?php if (!empty($worker['disability_type'])): ?>
                    <span class="lead-status-badge" style="background:#fdf4ff;color:#7e22ce">
                        <?= ucfirst($worker['disability_type']) ?>
                        <?= !empty($worker['disability_degree']) ? ' · ' . $worker['disability_degree'] . '%' : '' ?>
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($worker['driving_license'])): ?>
                    <span class="lead-status-badge" style="background:#f0fdf4;color:#15803d">🚗 Carnet</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="/workers/<?= $worker['id'] ?>/edit" class="btn btn-secondary">✏️ Editar</a>
            <a href="/workers" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="wk-meta-grid">
        <div class="wk-meta-item">
            <span class="wk-meta-label">DNI / NIE</span>
            <span class="wk-meta-value"><?= htmlspecialchars($worker['dni'] ?? '—') ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Fecha nacimiento</span>
            <span class="wk-meta-value"><?= !empty($worker['birth_date']) ? date('d/m/Y', strtotime($worker['birth_date'])) : '—' ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Teléfono</span>
            <span class="wk-meta-value"><?= htmlspecialchars($worker['phone'] ?? $worker['mobile'] ?? '—') ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Email</span>
            <span class="wk-meta-value">
                <?= !empty($worker['email']) ? '<a href="mailto:'.htmlspecialchars($worker['email']).'">'.htmlspecialchars($worker['email']).'</a>' : '—' ?>
            </span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Disponibilidad</span>
            <span class="wk-meta-value"><?= !empty($worker['availability']) ? htmlspecialchars(ucfirst(str_replace(['manana','_'], ['Mañana',' '], $worker['availability']))) : '—' ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Tipo de jornada</span>
            <span class="wk-meta-value"><?= !empty($worker['schedule_type']) ? htmlspecialchars(ucfirst($worker['schedule_type'])) : '—' ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Ciudad</span>
            <span class="wk-meta-value"><?= htmlspecialchars($worker['city'] ?? '—') ?></span>
        </div>
        <div class="wk-meta-item">
            <span class="wk-meta-label">Dirección</span>
            <span class="wk-meta-value"><?= htmlspecialchars($worker['address'] ?? '—') ?></span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <!-- Asignaciones -->
    <div class="wk-section">
        <div class="wk-section-head">
            <h3>Asignaciones (<?= count($assignments) ?>)</h3>
        </div>

        <?php if (empty($assignments)): ?>
            <div style="padding:24px 20px;text-align:center;font-size:13px;color:var(--text-light)">Sin asignaciones</div>
        <?php else: ?>
            <?php foreach ($assignments as $a): ?>
            <div class="assign-row">
                <div class="assign-icon"><?= $entityIcons[$a['entity_type']] ?? '📌' ?></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600"><?= htmlspecialchars(ucfirst(strtolower($a['entity_name'] ?? '—'))) ?></div>
                    <div style="font-size:11px;color:var(--text-soft);margin-top:2px">
                        <?= $entityLabels[$a['entity_type']] ?? $a['entity_type'] ?>
                        <?php if (!empty($a['start_date'])): ?>
                            · desde <?= date('d/m/Y', strtotime($a['start_date'])) ?>
                        <?php endif; ?>
                        <?php if (!empty($a['end_date'])): ?>
                            → <?= date('d/m/Y', strtotime($a['end_date'])) ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($a['notes'])): ?>
                        <div style="font-size:11px;color:var(--text-soft);margin-top:2px"><?= htmlspecialchars($a['notes']) ?></div>
                    <?php endif; ?>
                </div>
                <form method="POST" action="/workers/<?= $worker['id'] ?>/assignments/<?= $a['id'] ?>/delete">
                    <button class="btn-sm" style="color:var(--danger)" onclick="return confirm('¿Eliminar asignación?')">✕</button>
                </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Formulario añadir asignación -->
        <div class="assign-add">
            <p style="font-size:12px;font-weight:700;color:var(--text-soft);margin:0 0 12px;text-transform:uppercase;letter-spacing:.4px">Nueva asignación</p>
            <form method="POST" action="/workers/<?= $worker['id'] ?>/assignments">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:12px">
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text-soft);display:block;margin-bottom:4px">Tipo</label>
                        <select name="entity_type" id="assign_type" onchange="loadAssignEntities(this.value)" style="width:100%;height:36px;border:1px solid var(--border);border-radius:8px;padding:0 10px;font-size:13px">
                            <?php foreach ($catalogs['entity_types'] as $val => $label): ?>
                                <option value="<?= $val ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text-soft);display:block;margin-bottom:4px">Entidad</label>
                        <select name="entity_id" id="assign_entity" style="width:100%;height:36px;border:1px solid var(--border);border-radius:8px;padding:0 10px;font-size:13px">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div>
                            <label style="font-size:11px;font-weight:600;color:var(--text-soft);display:block;margin-bottom:4px">Fecha inicio</label>
                            <input type="date" name="start_date" style="width:100%;height:36px;border:1px solid var(--border);border-radius:8px;padding:0 10px;font-size:13px;box-sizing:border-box">
                        </div>
                        <div>
                            <label style="font-size:11px;font-weight:600;color:var(--text-soft);display:block;margin-bottom:4px">Fecha fin</label>
                            <input type="date" name="end_date" style="width:100%;height:36px;border:1px solid var(--border);border-radius:8px;padding:0 10px;font-size:13px;box-sizing:border-box">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text-soft);display:block;margin-bottom:4px">Notas</label>
                        <input type="text" name="notes" placeholder="Opcional..." style="width:100%;height:36px;border:1px solid var(--border);border-radius:8px;padding:0 10px;font-size:13px;box-sizing:border-box">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;height:36px;font-size:13px">+ Añadir asignación</button>
            </form>
        </div>
    </div>

    <!-- Info extra -->
    <div>
        <?php if (!empty($worker['skills'])): ?>
        <div class="wk-section" style="margin-bottom:16px">
            <div class="wk-section-head"><h3>Formación y habilidades</h3></div>
            <div style="padding:16px 20px;font-size:13px;color:var(--text-main);line-height:1.6">
                <?= nl2br(htmlspecialchars($worker['skills'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($worker['notes'])): ?>
        <div class="wk-section">
            <div class="wk-section-head"><h3>Notas internas</h3></div>
            <div style="padding:16px 20px;font-size:13px;color:var(--text-main);line-height:1.6">
                <?= nl2br(htmlspecialchars($worker['notes'])) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
function loadAssignEntities(type) {
    var select = document.getElementById('assign_entity');
    select.innerHTML = '<option value="">Cargando...</option>';
    fetch('/search/entities?type=' + type)
        .then(function(r){ return r.json(); })
        .then(function(data) {
            select.innerHTML = '<option value="">— Selecciona —</option>';
            data.forEach(function(e) {
                var opt = document.createElement('option');
                opt.value = e.id;
                opt.textContent = e.name;
                select.appendChild(opt);
            });
        });
}
document.addEventListener('DOMContentLoaded', function() {
    loadAssignEntities(document.getElementById('assign_type').value);
});
</script>
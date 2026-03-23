<?php $contract = $contract ?? []; ?>
<style>
.ct-form-section{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:20px}
.ct-form-section-head{padding:16px 20px;border-bottom:1px solid var(--border-soft);font-size:14px;font-weight:700;color:var(--text-main)}
.ct-form-section-body{padding:20px}
.ct-check-wrap{display:flex;align-items:center;gap:10px;padding:12px 16px;border:1px solid var(--border);border-radius:10px;cursor:pointer}
.ct-check-wrap input[type=checkbox]{width:18px;height:18px;cursor:pointer}
</style>

<div class="ct-form-section">
    <div class="ct-form-section-head">Datos del contrato</div>
    <div class="ct-form-section-body">
        <div class="form-grid">
            <div class="form-group" style="grid-column:1/-1">
                <label>Título del contrato</label>
                <input type="text" name="title" class="autocap"
                       value="<?= htmlspecialchars($contract['title'] ?? '') ?>" required
                       placeholder="Ej: Servicio de limpieza oficinas centrales">
            </div>
            <div class="form-group">
                <label>Empresa cliente</label>
                <select name="company_id" required>
                    <option value="">— Selecciona empresa —</option>
                    <?php foreach ($catalogs['companies'] as $co): ?>
                        <option value="<?= $co['id'] ?>"
                            <?= (($contract['company_id'] ?? $company_id ?? null) == $co['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(ucfirst(strtolower($co['name']))) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de servicio</label>
                <select name="service_type" required>
                    <option value="">— Selecciona —</option>
                    <?php foreach ($catalogs['service_types'] as $val => $label): ?>
                        <option value="<?= $val ?>"
                            <?= (($contract['service_type'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="status">
                    <?php foreach ($catalogs['statuses'] as $val => $label): ?>
                        <option value="<?= $val ?>"
                            <?= (($contract['status'] ?? 'activo') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nº trabajadores asignados</label>
                <input type="number" name="workers_assigned" min="0"
                       value="<?= htmlspecialchars($contract['workers_assigned'] ?? '0') ?>">
            </div>
            <div class="form-group">
                <label>Importe (€)</label>
                <input type="number" name="amount" min="0" step="0.01"
                       value="<?= htmlspecialchars($contract['amount'] ?? '') ?>"
                       placeholder="0.00">
            </div>
        </div>
        <div class="form-group">
            <label>Descripción del servicio</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($contract['description'] ?? '') ?></textarea>
        </div>
    </div>
</div>

<div class="ct-form-section">
    <div class="ct-form-section-head">Vigencia</div>
    <div class="ct-form-section-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Fecha inicio</label>
                <input type="date" name="start_date"
                       value="<?= htmlspecialchars($contract['start_date'] ?? date('Y-m-d')) ?>" required>
            </div>
            <div class="form-group">
                <label>Fecha fin</label>
                <input type="date" name="end_date"
                       value="<?= htmlspecialchars($contract['end_date'] ?? '') ?>">
            </div>
        </div>
        <label class="ct-check-wrap">
            <input type="checkbox" name="renewable" value="1"
                   <?= !empty($contract['renewable']) ? 'checked' : '' ?>>
            <div>
                <div style="font-size:13px;font-weight:600">Contrato renovable</div>
                <div style="font-size:12px;color:var(--text-soft)">Se avisará 30 días antes del vencimiento</div>
            </div>
        </label>
    </div>
</div>

<div class="ct-form-section">
    <div class="ct-form-section-head">Documentación</div>
    <div class="ct-form-section-body">
        <div class="form-group">
            <label>Documento del contrato</label>
            <?php if (!empty($contract['document_url'])): ?>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px">
                <span>📄</span>
                <a href="<?= htmlspecialchars($contract['document_url']) ?>" target="_blank" style="font-size:13px;font-weight:600">
                    Ver documento actual
                </a>
                <span style="font-size:12px;color:var(--text-soft)">· Sube uno nuevo para reemplazarlo</span>
            </div>
            <?php endif; ?>
            <input type="file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                   style="border:1px solid var(--border);border-radius:10px;padding:10px;width:100%;font-size:13px">
            <small style="color:var(--text-soft);font-size:12px;margin-top:4px;display:block">
                Formatos permitidos: PDF, DOC, DOCX, JPG, PNG · Máximo 10MB
            </small>
        </div>
        <div class="form-group">
            <label>Notas internas</label>
            <textarea name="notes" rows="3"><?= htmlspecialchars($contract['notes'] ?? '') ?></textarea>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    if (input.value) input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) { this.value = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase(); this.setSelectionRange(pos, pos); }
    });
});
</script>
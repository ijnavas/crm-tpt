<?php
$companyName = ucfirst(strtolower($lead['company_name'] ?: $lead['full_name']));
$contactName = ucwords(strtolower(trim(($lead['first_name'] ?? '') . ' ' . ($lead['last_name'] ?? ''))));
?>
<style>
.cv-wrap{max-width:820px;margin:0 auto}
.cv-hero{background:linear-gradient(135deg,#1a6ed8 0%,#6366f1 100%);border-radius:16px;padding:28px 32px;margin-bottom:24px;color:#fff;display:flex;align-items:center;gap:20px}
.cv-hero-icon{width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0}
.cv-hero h1{margin:0 0 4px;font-size:20px;font-weight:700}
.cv-hero p{margin:0;font-size:13px;opacity:.85}

.cv-cols{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.cv-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow-soft)}
.cv-card-head{padding:14px 20px;border-bottom:1px solid var(--border-soft);display:flex;align-items:center;gap:10px}
.cv-card-head-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px}
.cv-card-head h3{margin:0;font-size:13px;font-weight:700}
.cv-card-head span{font-size:11px;color:var(--text-soft);margin-left:auto}
.cv-body{padding:16px 20px}
.cv-field{margin-bottom:14px}
.cv-field:last-child{margin-bottom:0}
.cv-field label{display:block;font-size:11px;font-weight:600;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px}
.cv-field input,.cv-field select{width:100%;border:1px solid var(--border);border-radius:9px;padding:9px 12px;font-size:13px;outline:none;transition:border-color .15s;box-sizing:border-box}
.cv-field input:focus,.cv-field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(47,128,237,.08)}

.cv-summary{background:#f8fafc;border:1px solid var(--border);border-radius:12px;padding:18px 20px;margin-bottom:20px}
.cv-summary h3{margin:0 0 12px;font-size:13px;font-weight:700;color:var(--text-soft);text-transform:uppercase;letter-spacing:.4px}
.cv-summary-items{display:flex;gap:12px;flex-wrap:wrap}
.cv-summary-item{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--border);border-radius:8px;padding:8px 14px;font-size:13px}
.cv-summary-item span{color:var(--text-soft);font-size:12px}

.cv-actions{display:flex;gap:12px}
.cv-btn-convert{flex:1;height:44px;background:linear-gradient(135deg,#1a6ed8,#6366f1);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:opacity .15s}
.cv-btn-convert:hover{opacity:.9}
.cv-btn-cancel{height:44px;padding:0 24px;background:#fff;color:var(--text-main);border:1px solid var(--border);border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center}
.cv-btn-cancel:hover{background:var(--bg-muted)}

@media(max-width:700px){.cv-cols{grid-template-columns:1fr}}
</style>

<div class="cv-wrap">

    <!-- Hero -->
    <div class="cv-hero">
        <div class="cv-hero-icon">🔄</div>
        <div>
            <h1>Convertir lead a empresa</h1>
            <p>Revisa y completa los datos. Se creará la empresa y el contacto automáticamente.</p>
        </div>
    </div>

    <form action="/leads/<?= $lead['id'] ?>/convert" method="POST">

        <div class="cv-cols">

            <!-- Empresa -->
            <div class="cv-card">
                <div class="cv-card-head">
                    <div class="cv-card-head-icon" style="background:#eff6ff">🏢</div>
                    <h3>Datos de la empresa</h3>
                    <span>Se creará nueva</span>
                </div>
                <div class="cv-body">
                    <div class="cv-field">
                        <label>Nombre empresa *</label>
                        <input type="text" name="company_name" class="autocap"
                               value="<?= htmlspecialchars($companyName) ?>" required>
                    </div>
                    <div class="cv-field">
                        <label>Razón social</label>
                        <input type="text" name="legal_name" class="autocap"
                               value="<?= htmlspecialchars($companyName) ?>">
                    </div>
                    <div class="cv-field">
                        <label>CIF</label>
                        <input type="text" name="tax_id" placeholder="B12345678">
                    </div>
                    <div class="cv-field">
                        <label>Sector</label>
                        <input type="text" name="sector" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['service_interest'] ?? ''))) ?>">
                    </div>
                    <div class="cv-field">
                        <label>Email</label>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
                    </div>
                    <div class="cv-field">
                        <label>Teléfono</label>
                        <input type="text" name="phone"
                               value="<?= htmlspecialchars($lead['phone'] ?? '') ?>">
                    </div>
                    <div class="cv-field">
                        <label>Ciudad</label>
                        <input type="text" name="city" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['city'] ?? ''))) ?>">
                    </div>
                    <div class="cv-field">
                        <label>Provincia</label>
                        <input type="text" name="province" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['province'] ?? ''))) ?>">
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="cv-card">
                <div class="cv-card-head">
                    <div class="cv-card-head-icon" style="background:#f0fdf4">👤</div>
                    <h3>Contacto principal</h3>
                    <span>Se creará nuevo</span>
                </div>
                <div class="cv-body">
                    <div class="cv-field">
                        <label>Nombre *</label>
                        <input type="text" name="first_name" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['first_name'] ?? ''))) ?>" required>
                    </div>
                    <div class="cv-field">
                        <label>Apellidos</label>
                        <input type="text" name="last_name" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['last_name'] ?? ''))) ?>">
                    </div>
                    <div class="cv-field">
                        <label>Cargo</label>
                        <input type="text" name="job_title" class="autocap"
                               value="<?= htmlspecialchars(ucfirst(strtolower($lead['job_title'] ?? ''))) ?>">
                    </div>
                    <div class="cv-field">
                        <label>Email</label>
                        <input type="email" name="contact_email"
                               value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
                    </div>
                    <div class="cv-field">
                        <label>Teléfono</label>
                        <input type="text" name="contact_phone"
                               value="<?= htmlspecialchars($lead['phone'] ?? '') ?>">
                    </div>
                    <div class="cv-field">
                        <label>Móvil</label>
                        <input type="text" name="mobile"
                               value="<?= htmlspecialchars($lead['mobile'] ?? '') ?>">
                    </div>
                </div>
            </div>

        </div>

        <!-- Resumen -->
        <div class="cv-summary">
            <h3>Qué se creará</h3>
            <div class="cv-summary-items">
                <div class="cv-summary-item">🏢 <strong>1 empresa</strong> <span>con estado Prospecto</span></div>
                <div class="cv-summary-item">👤 <strong>1 contacto</strong> <span>marcado como principal</span></div>
                <div class="cv-summary-item">🔄 <strong>Lead #<?= $lead['id'] ?></strong> <span>marcado como Convertido</span></div>
                <?php if (!empty($lead['notes_internal'])): ?>
                <div class="cv-summary-item">📝 <strong>Notas</strong> <span>transferidas a la empresa</span></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Acciones -->
        <div class="cv-actions">
            <button type="submit" class="cv-btn-convert">🔄 Convertir a empresa</button>
            <a href="/leads/<?= $lead['id'] ?>" class="cv-btn-cancel">Cancelar</a>
        </div>

    </form>

</div>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    if (input.value) input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) { this.value = val.charAt(0).toUpperCase() + val.slice(1); this.setSelectionRange(pos, pos); }
    });
});
</script>
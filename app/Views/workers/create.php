<?php
// Variables disponibles: $catalogs
$worker = [];
?>
<style>
.wf-section{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:20px}
.wf-head{padding:16px 20px;border-bottom:1px solid var(--border-soft);font-size:14px;font-weight:700;color:var(--text-main)}
.wf-body{padding:20px}
.wf-check{display:flex;align-items:center;gap:10px;padding:12px 16px;border:1px solid var(--border);border-radius:10px;cursor:pointer}
.wf-check input{width:18px;height:18px;cursor:pointer;accent-color:var(--primary)}
</style>

<section class="page-header">
    <div><h1>Nuevo trabajador</h1></div>
</section>

<form action="/workers/store" method="POST">

    <!-- Datos personales -->
    <div class="wf-section">
        <div class="wf-head">Datos personales</div>
        <div class="wf-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="first_name" class="autocap" required>
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="last_name" class="autocap" required>
                </div>
                <div class="form-group">
                    <label>DNI / NIE</label>
                    <input type="text" name="dni" style="text-transform:uppercase" placeholder="12345678A">
                </div>
                <div class="form-group">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="birth_date">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Móvil</label>
                    <input type="text" name="mobile">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status">
                        <option value="activo">Activo</option>
                        <option value="baja_temporal">Baja temporal</option>
                        <option value="baja_definitiva">Baja definitiva</option>
                        <option value="en_formacion">En formación</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Discapacidad -->
    <div class="wf-section">
        <div class="wf-head">Discapacidad</div>
        <div class="wf-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Tipo de discapacidad</label>
                    <select name="disability_type">
                        <option value="">— Sin especificar —</option>
                        <option value="fisica">Física</option>
                        <option value="psiquica">Psíquica</option>
                        <option value="sensorial">Sensorial</option>
                        <option value="intelectual">Intelectual</option>
                        <option value="organica">Orgánica</option>
                        <option value="multiple">Múltiple</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Grado de discapacidad (%)</label>
                    <input type="number" name="disability_degree" min="0" max="100" placeholder="Ej: 33">
                </div>
            </div>
        </div>
    </div>

    <!-- Disponibilidad -->
    <div class="wf-section">
        <div class="wf-head">Disponibilidad y jornada</div>
        <div class="wf-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Disponibilidad horaria</label>
                    <select name="availability">
                        <option value="">— Sin especificar —</option>
                        <option value="manana">Mañana</option>
                        <option value="tarde">Tarde</option>
                        <option value="completa">Jornada completa</option>
                        <option value="fines_semana">Fines de semana</option>
                        <option value="flexible">Flexible</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipo de jornada</label>
                    <select name="schedule_type">
                        <option value="">— Sin especificar —</option>
                        <option value="completa">Jornada completa</option>
                        <option value="parcial">Jornada parcial</option>
                        <option value="flexible">Flexible</option>
                    </select>
                </div>
            </div>
            <label class="wf-check" style="margin-top:8px">
                <input type="checkbox" name="driving_license" value="1">
                <div>
                    <div style="font-size:13px;font-weight:600">Carnet de conducir</div>
                    <div style="font-size:12px;color:var(--text-soft)">El trabajador dispone de carnet de conducir</div>
                </div>
            </label>
        </div>
    </div>

    <!-- Dirección -->
    <div class="wf-section">
        <div class="wf-head">Dirección</div>
        <div class="wf-body">
            <div class="form-grid">
                <div class="form-group" style="grid-column:1/-1">
                    <label>Dirección</label>
                    <input type="text" name="address" class="autocap">
                </div>
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" name="city" class="autocap">
                </div>
                <div class="form-group">
                    <label>Provincia</label>
                    <input type="text" name="province" class="autocap">
                </div>
                <div class="form-group">
                    <label>Código postal</label>
                    <input type="text" name="postal_code">
                </div>
            </div>
        </div>
    </div>

    <!-- Formación -->
    <div class="wf-section">
        <div class="wf-head">Formación y habilidades</div>
        <div class="wf-body">
            <div class="form-group">
                <label>Habilidades y formación</label>
                <textarea name="skills" rows="3" placeholder="Ej: Limpieza industrial, carretillero, atención al cliente..."></textarea>
            </div>
            <div class="form-group">
                <label>Notas internas</label>
                <textarea name="notes" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear trabajador</button>
        <a href="/workers" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<script>
document.querySelectorAll('input.autocap').forEach(function(input) {
    input.addEventListener('input', function() {
        var pos = this.selectionStart, val = this.value;
        if (val.length > 0) { this.value = val.charAt(0).toUpperCase() + val.slice(1); this.setSelectionRange(pos, pos); }
    });
});
</script>
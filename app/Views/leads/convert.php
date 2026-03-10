<section class="page-header">
    <div>
        <h1>Convertir lead</h1>
        <p>Lead #<?= $lead['id'] ?> · <?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?></p>
    </div>
</section>

<section class="card">
    <form action="/leads/<?= $lead['id'] ?>/convert" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Nombre empresa</label>
                <input type="text" name="company_name" value="<?= htmlspecialchars($lead['company_name'] ?: $lead['full_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Razón social</label>
                <input type="text" name="legal_name" value="<?= htmlspecialchars($lead['company_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Nombre contacto</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($lead['first_name'] ?? $lead['full_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Apellidos contacto</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($lead['last_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($lead['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Móvil</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($lead['mobile'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <input type="text" name="job_title" value="<?= htmlspecialchars($lead['job_title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Ciudad</label>
                <input type="text" name="city" value="<?= htmlspecialchars($lead['city'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Provincia</label>
                <input type="text" name="province" value="<?= htmlspecialchars($lead['province'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>CIF</label>
                <input type="text" name="tax_id" value="">
            </div>

            <div class="form-group">
                <label>Sector</label>
                <input type="text" name="sector" value="">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Convertir a empresa</button>
            <a href="/leads/<?= $lead['id'] ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
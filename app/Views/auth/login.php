<?php $error = \App\Core\Session::getFlash('error'); ?>
<?php if ($error): ?>
    <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;margin-bottom:16px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="auth-card">
    <h1>CRM TPT</h1>
    <p>Acceso al sistema</p>

    <form action="/login" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
    </form>
</div>

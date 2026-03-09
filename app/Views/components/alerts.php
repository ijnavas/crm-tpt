<?php $error = \App\Core\Session::getFlash('error'); ?>
<?php if ($error): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars((string) $error) ?>
    </div>
<?php endif; ?>

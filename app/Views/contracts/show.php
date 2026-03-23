<?php
$statusColors = [
    'activo'     => ['bg'=>'#f0fdf4','color'=>'#15803d'],
    'renovado'   => ['bg'=>'#eff6ff','color'=>'#1d4ed8'],
    'pausado'    => ['bg'=>'#fefce8','color'=>'#a16207'],
    'finalizado' => ['bg'=>'#f9fafb','color'=>'#6b7280'],
    'cancelado'  => ['bg'=>'#fef2f2','color'=>'#b91c1c'],
];
$sc = $statusColors[$contract['status'] ?? 'activo'] ?? $statusColors['activo'];
$expiring = !empty($contract['end_date']) && strtotime($contract['end_date']) <= strtotime('+30 days') && $contract['status'] === 'activo';
?>

<section class="page-header">
    <div>
        <h1><?= htmlspecialchars(ucfirst(strtolower($contract['title'] ?? ''))) ?></h1>
        <p>
            <a href="/companies/<?= $contract['company_id'] ?>">
                <?= htmlspecialchars(ucfirst(strtolower($contract['company_name'] ?? ''))) ?>
            </a>
        </p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="/contracts/<?= $contract['id'] ?>/edit" class="btn btn-primary">✏️ Editar</a>
        <a href="/contracts" class="btn btn-secondary">← Volver</a>
    </div>
</section>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <div class="card">
        <h2 style="margin-top:0;font-size:15px">Información del contrato</h2>
        <table class="table">
            <tr><td style="color:var(--text-soft);width:140px">Empresa</td>
                <td><a href="/companies/<?= $contract['company_id'] ?>"><?= htmlspecialchars(ucfirst(strtolower($contract['company_name'] ?? ''))) ?></a></td></tr>
            <tr><td style="color:var(--text-soft)">Servicio</td>
                <td><?= htmlspecialchars(ucfirst(str_replace('_',' ',$contract['service_type'] ?? ''))) ?></td></tr>
            <tr><td style="color:var(--text-soft)">Estado</td>
                <td><span class="lead-status-badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>"><?= ucfirst($contract['status'] ?? '') ?></span></td></tr>
            <tr><td style="color:var(--text-soft)">Trabajadores</td>
                <td><strong><?= $contract['workers_assigned'] ?></strong></td></tr>
            <tr><td style="color:var(--text-soft)">Importe</td>
                <td><?= !empty($contract['amount']) ? '<strong>' . number_format($contract['amount'], 2, ',', '.') . ' €</strong>' : '—' ?></td></tr>
            <tr><td style="color:var(--text-soft)">Renovable</td>
                <td><?= !empty($contract['renewable']) ? '✓ Sí' : 'No' ?></td></tr>
        </table>
    </div>

    <div class="card">
        <h2 style="margin-top:0;font-size:15px">Vigencia</h2>
        <table class="table">
            <tr><td style="color:var(--text-soft);width:140px">Fecha inicio</td>
                <td><?= !empty($contract['start_date']) ? date('d/m/Y', strtotime($contract['start_date'])) : '—' ?></td></tr>
            <tr><td style="color:var(--text-soft)">Fecha fin</td>
                <td style="<?= $expiring ? 'color:var(--danger);font-weight:600' : '' ?>">
                    <?= !empty($contract['end_date']) ? date('d/m/Y', strtotime($contract['end_date'])) : 'Indefinido' ?>
                    <?= $expiring ? ' ⚠️ Vence pronto' : '' ?>
                </td></tr>
            <tr><td style="color:var(--text-soft)">Creado</td>
                <td><?= !empty($contract['created_at']) ? date('d/m/Y', strtotime($contract['created_at'])) : '—' ?></td></tr>
        </table>

        <?php if (!empty($contract['document_url'])): ?>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border-soft)">
            <a href="<?= htmlspecialchars($contract['document_url']) ?>" target="_blank"
               class="btn btn-secondary" style="width:100%;justify-content:center">
                📄 Ver documento del contrato
            </a>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php if (!empty($contract['description'])): ?>
<div class="card">
    <h2 style="margin-top:0;font-size:15px">Descripción del servicio</h2>
    <p style="margin:0;color:var(--text-main);line-height:1.6"><?= nl2br(htmlspecialchars($contract['description'])) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($contract['notes'])): ?>
<div class="card">
    <h2 style="margin-top:0;font-size:15px">Notas internas</h2>
    <p style="margin:0;color:var(--text-main);line-height:1.6"><?= nl2br(htmlspecialchars($contract['notes'])) ?></p>
</div>
<?php endif; ?>

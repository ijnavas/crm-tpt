<section class="card">
    <h2>Historial</h2>

    <?php if (empty($leadDetail['timeline'])): ?>
        <p>No hay actividad registrada.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($leadDetail['timeline'] as $item): ?>
                <li><?= htmlspecialchars($item['description']) ?> · <?= htmlspecialchars($item['created_at']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

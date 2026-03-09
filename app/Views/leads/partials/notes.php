<section class="card">
    <h2>Notas</h2>

    <?php if (empty($leadDetail['notes'])): ?>
        <p>No hay notas todavía.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($leadDetail['notes'] as $note): ?>
                <li>
                    <strong><?= htmlspecialchars(trim(($note['first_name'] ?? '') . ' ' . ($note['last_name'] ?? ''))) ?></strong>
                    — <?= htmlspecialchars($note['note']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

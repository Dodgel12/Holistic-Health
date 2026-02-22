<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="card">
    <h2>Dettaglio Analisi #<?php echo $visita['visita_id']; ?></h2>
    <p><strong>Data:</strong> <?php echo htmlspecialchars($visita['data_analisi']); ?></p>
    <p><strong>Note:</strong> <?php echo nl2br(htmlspecialchars($visita['note'])); ?></p>

    <?php if (isset($visita['scheda_fisica'])): ?>
        <h3>Dati Fisici</h3>
        <ul>
            <li>Massa Grassa: <?php echo htmlspecialchars($visita['scheda_fisica']['massa_grassa']); ?>%</li>
            <li>Massa Magra: <?php echo htmlspecialchars($visita['scheda_fisica']['massa_magra']); ?>%</li>
        </ul>
    <?php endif; ?>

    <a href="clients.php" class="btn">Torna ai Clienti</a>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

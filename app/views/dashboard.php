<?php require_once 'partials/header.php'; ?>
/**
 * Vista della dashboard.
 * Mostra all'utente una panoramica dello stato
 * dell'applicazione e delle attività recenti.
 */

<div class="card">
    <h1>Benvenuto, <?php echo htmlspecialchars($user['name']); ?></h1>
    <p>Ruolo: <?php echo htmlspecialchars($user['role']); ?></p>
</div>

<div class="card">
    <h2>Accesso Rapido</h2>
    <div style="display: flex; gap: 1rem;">
        <a href="clients.php" class="btn">Gestione Clienti</a>
        <a href="appointments.php" class="btn">Calendario Appuntamenti</a>
        <a href="clients.php?action=new" class="btn">Nuovo Cliente</a>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>

<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="card">
    <h1>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
</div>

<div class="card">
    <h2>Accesso Rapido</h2>
    <div style="display: flex; gap: 1rem;">
        <a href="clients.php" class="btn">Gestione Clienti</a>
        <a href="appointments.php" class="btn">Calendario Appuntamenti</a>
        <a href="clients.php?action=new" class="btn">Nuovo Cliente</a>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

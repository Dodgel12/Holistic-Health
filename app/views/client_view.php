<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="card">
    <?php if (isset($client)): ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2><?php echo htmlspecialchars($client['nome'] . ' ' . $client['cognome']); ?></h2>
            <div>
                <a href="analysis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn">Nuova Analisi</a>
                <a href="clients.php" class="btn btn-danger">Torna alla lista</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
            <div>
                <h3>Dati Anagrafici</h3>
                <p><strong>Telefono:</strong> <?php echo htmlspecialchars($client['telefono']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
                <p><strong>Indirizzo:</strong> <?php echo htmlspecialchars($client['indirizzo']); ?></p>
                <p><strong>Professione:</strong> <?php echo htmlspecialchars($client['professione']); ?></p>
                <p><strong>Data Nascita:</strong> <?php echo htmlspecialchars($client['data_nascita']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <h2>Nuovo Cliente</h2>
        <form action="clients.php?action=create" method="POST">
            <div class="form-group"><label>Nome</label><input type="text" name="nome" required></div>
            <div class="form-group"><label>Cognome</label><input type="text" name="cognome" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email"></div>
            <div class="form-group"><label>Telefono</label><input type="text" name="telefono"></div>
            <div class="form-group"><label>Professione</label><input type="text" name="professione"></div>
            <div class="form-group"><label>Data Nascita</label><input type="date" name="data_nascita"></div>
            <div class="form-group"><label>Indirizzo</label><input type="text" name="indirizzo"></div>
            <button type="submit" class="btn">Salva</button>
            <a href="clients.php" class="btn btn-danger">Annulla</a>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

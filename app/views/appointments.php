<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="card">
    <h2>Gestione Appuntamenti</h2>

    <div style="margin-bottom: 2rem; padding: 1rem; background: #f9f9f9; border-radius: 4px;">
        <h3>Nuovo Appuntamento</h3>
        <form action="appointments.php?action=create" method="POST">
            <div class="form-group">
                <label>Cliente</label>
                <select name="cliente_id" required>
                    <option value="">Seleziona Cliente</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['cliente_id']; ?>">
                            <?php echo htmlspecialchars($client['nome'] . ' ' . $client['cognome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Data</label>
                <input type="date" name="data" required>
            </div>
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label>Ora Inizio</label>
                    <input type="time" name="ora_inizio" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Ora Fine</label>
                    <input type="time" name="ora_fine" required>
                </div>
            </div>
            <div class="form-group">
                <label>Note</label>
                <textarea name="note"></textarea>
            </div>
            <button type="submit" class="btn">Salva Appuntamento</button>
        </form>
    </div>

    <h3>Prossimi Appuntamenti</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Orario</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Stato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['data']); ?></td>
                        <td><?php echo htmlspecialchars(substr($app['ora_inizio'], 0, 5) . ' - ' . substr($app['ora_fine'], 0, 5)); ?></td>
                        <td><?php echo htmlspecialchars($app['nome'] . ' ' . $app['cognome']); ?></td>
                        <td><?php echo htmlspecialchars($app['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($app['stato']); ?></td>
                        <td>
                            <a href="appointments.php?action=delete&id=<?php echo $app['appuntamento_id']; ?>" class="btn btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Nessun appuntamento in programma.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

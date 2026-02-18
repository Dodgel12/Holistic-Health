<?php require_once 'partials/header.php'; ?>
/**
 * Vista per la creazione di una nuova analisi.
 * Genera dinamicamente il questionario e
 * raccoglie le risposte dell'utente.
 */

<div class="card">
    <h2>Nuova Analisi per <?php echo htmlspecialchars($client['nome'] . ' ' . $client['cognome']); ?></h2>
    
    <form action="analysis.php?action=store" method="POST">
        <input type="hidden" name="cliente_id" value="<?php echo $client['cliente_id']; ?>">
        
        <div class="form-group">
            <label>Note Generali</label>
            <textarea name="note" rows="3"></textarea>
        </div>

        <h3>Dati Fisici</h3>
        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex: 1;">
                <label>Massa Grassa (%)</label>
                <input type="number" step="0.01" name="massa_grassa">
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Massa Magra (%)</label>
                <input type="number" step="0.01" name="massa_magra">
            </div>
        </div>

        <?php if (!empty($questionari)): ?>
            <h3>Questionari Attivi</h3>
            <?php foreach ($questionari as $q): ?>
                <div style="margin-top: 1rem; border: 1px solid #eee; padding: 1rem;">
                    <h4><?php echo htmlspecialchars($q['nome']); ?></h4>
                    <!-- Questions handling would go here via AJAX or included PHP based on Domanda model -->
                    <p><em>(Implementazione domande questionario in fase di sviluppo)</em></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="submit" class="btn" style="margin-top: 1rem;">Salva Analisi</button>
        <a href="clients.php" class="btn btn-danger">Annulla</a>
    </form>
</div>

<?php require_once 'partials/footer.php'; ?>

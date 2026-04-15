<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$isEdit = !empty($activePlan);
$formAction = $isEdit ? 'therapy.php?action=update' : 'therapy.php?action=create';
?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="clients.php?action=show&id=<?php echo (int) $client['cliente_id']; ?>"><?php echo htmlspecialchars($client['nome'] . ' ' . $client['cognome']); ?></a>
            &rsaquo; <span>Piano Terapeutico</span>
        </div>
        <h1>Piano Terapeutico — <?php echo htmlspecialchars($client['nome'] . ' ' . $client['cognome']); ?></h1>
    </div>
</div>

<div class="therapy-layout">
    <div class="card">
        <div class="card-title">Piani del paziente</div>
        <?php if (!empty($plans)): ?>
            <div class="therapy-plan-list">
                <?php foreach ($plans as $p): ?>
                    <a class="btn <?php echo ($isEdit && (int) $activePlan['piano_id'] === (int) $p['piano_id']) ? 'btn-primary' : 'btn-ghost'; ?> btn-sm therapy-plan-link"
                       href="therapy.php?clientId=<?php echo (int) $client['cliente_id']; ?>&planId=<?php echo (int) $p['piano_id']; ?>">
                        <?php echo htmlspecialchars($p['titolo']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Nessun piano inserito.</p>
        <?php endif; ?>

        <?php if ($isEdit): ?>
            <form action="therapy.php?action=delete" method="POST" class="js-delete-confirm therapy-delete-wrap" data-delete-title="Elimina piano terapeutico" data-delete-message="Eliminare questo piano terapeutico?">
                <input type="hidden" name="cliente_id" value="<?php echo (int) $client['cliente_id']; ?>">
                <input type="hidden" name="piano_id" value="<?php echo (int) $activePlan['piano_id']; ?>">
                <button class="btn btn-danger btn-sm" type="submit">Elimina piano corrente</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-title"><?php echo $isEdit ? 'Modifica Piano' : 'Nuovo Piano'; ?></div>
        <form action="<?php echo $formAction; ?>" method="POST" class="therapy-form">
            <input type="hidden" name="cliente_id" value="<?php echo (int) $client['cliente_id']; ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="piano_id" value="<?php echo (int) $activePlan['piano_id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="titolo">Titolo piano *</label>
                <input id="titolo" name="titolo" required value="<?php echo htmlspecialchars($activePlan['titolo'] ?? 'Piano terapeutico base'); ?>">
            </div>

            <div class="therapy-date-grid">
                <div class="form-group">
                    <label for="data_inizio">Data inizio *</label>
                    <input type="date" id="data_inizio" name="data_inizio" required value="<?php echo htmlspecialchars($activePlan['data_inizio'] ?? date('Y-m-d')); ?>">
                </div>
                <div class="form-group">
                    <label for="data_fine">Data fine</label>
                    <input type="date" id="data_fine" name="data_fine" value="<?php echo htmlspecialchars($activePlan['data_fine'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="stato">Stato</label>
                    <?php $statoCurrent = $activePlan['stato'] ?? 'Attivo'; ?>
                    <select id="stato" name="stato">
                        <option value="Attivo" <?php echo $statoCurrent === 'Attivo' ? 'selected' : ''; ?>>Attivo</option>
                        <option value="Sospeso" <?php echo $statoCurrent === 'Sospeso' ? 'selected' : ''; ?>>Sospeso</option>
                        <option value="Concluso" <?php echo $statoCurrent === 'Concluso' ? 'selected' : ''; ?>>Concluso</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="obiettivi">Obiettivi terapeutici</label>
                <textarea id="obiettivi" name="obiettivi" placeholder="Obiettivi primari e secondari..."><?php echo htmlspecialchars($activePlan['obiettivi'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="note">Indicazioni operative</label>
                <textarea id="note" name="note" placeholder="Indicazioni cliniche, timing, follow-up..."><?php echo htmlspecialchars($activePlan['note'] ?? ''); ?></textarea>
            </div>

            <div class="therapy-catalog-grid">
                <div class="form-group">
                    <label>Alimenti consigliati</label>
                    <div class="therapy-list-box">
                        <?php foreach ($alimenti as $a): ?>
                            <label class="therapy-list-item">
                                <input type="checkbox"
                                        class="therapy-choice"
                                        data-category="alimenti"
                                        data-label="<?php echo htmlspecialchars($a['nome'], ENT_QUOTES); ?>"
                                       name="alimenti[]"
                                       value="<?php echo (int) $a['alimento_id']; ?>"
                                       <?php echo in_array((int) $a['alimento_id'], $selectedCatalog['alimenti'], true) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($a['nome']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Integratori</label>
                    <div class="therapy-list-box">
                        <?php foreach ($integratori as $i): ?>
                            <label class="therapy-list-item">
                                <input type="checkbox"
                                        class="therapy-choice"
                                        data-category="integratori"
                                        data-label="<?php echo htmlspecialchars($i['nome'], ENT_QUOTES); ?>"
                                       name="integratori[]"
                                       value="<?php echo (int) $i['integratore_id']; ?>"
                                       <?php echo in_array((int) $i['integratore_id'], $selectedCatalog['integratori'], true) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($i['nome']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Farmaci</label>
                    <div class="therapy-list-box">
                        <?php foreach ($farmaci as $f): ?>
                            <label class="therapy-list-item">
                                <input type="checkbox"
                                        class="therapy-choice"
                                        data-category="farmaci"
                                        data-label="<?php echo htmlspecialchars($f['nome'], ENT_QUOTES); ?>"
                                       name="farmaci[]"
                                       value="<?php echo (int) $f['farmaco_id']; ?>"
                                       <?php echo in_array((int) $f['farmaco_id'], $selectedCatalog['farmaci'], true) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($f['nome']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Raccomandazioni nel piano</label>
                    <div class="therapy-summary-box">
                        <div>
                            <div class="text-muted text-sm therapy-summary-label">Alimenti</div>
                            <div id="summary-alimenti"></div>
                        </div>

                        <div>
                            <div class="text-muted text-sm therapy-summary-label">Integratori</div>
                            <div id="summary-integratori"></div>
                        </div>

                        <div>
                            <div class="text-muted text-sm therapy-summary-label">Farmaci</div>
                            <div id="summary-farmaci"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="therapy-footer-actions">
                <button class="btn btn-primary" type="submit"><?php echo $isEdit ? 'Aggiorna piano' : 'Crea piano'; ?></button>
                <a class="btn btn-ghost" href="therapy.php?clientId=<?php echo (int) $client['cliente_id']; ?>">Nuovo</a>
                <a class="btn btn-ghost" href="clients.php?action=show&id=<?php echo (int) $client['cliente_id']; ?>">Torna al paziente</a>
            </div>
        </form>
    </div>
</div>

<script>
function renderTherapySummary() {
    const categories = ['alimenti', 'integratori', 'farmaci'];
    categories.forEach(category => {
        const target = document.getElementById('summary-' + category);
        if (!target) return;

        const selected = Array.from(document.querySelectorAll('.therapy-choice[data-category="' + category + '"]:checked'));
        if (!selected.length) {
            target.innerHTML = '<div class="text-muted text-sm">Nessuno selezionato</div>';
            return;
        }

        target.innerHTML = selected
            .map(cb => '<div>• ' + cb.dataset.label + '</div>')
            .join('');
    });
}

document.querySelectorAll('.therapy-choice').forEach(cb => {
    cb.addEventListener('change', renderTherapySummary);
});

renderTherapySummary();

document.querySelectorAll('.js-delete-confirm').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        DeleteModal.confirmDeleteForm(this, {
            title: this.dataset.deleteTitle || 'Conferma eliminazione',
            message: this.dataset.deleteMessage || 'Questa azione è irreversibile. Vuoi continuare?'
        });
    });
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

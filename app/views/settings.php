<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">Dashboard &rsaquo; <span>Impostazioni</span></div>
        <h1>Impostazioni</h1>
    </div>
</div>

<?php if (!empty($settingsMessage)): ?>
<div class="card callout-primary">
    <?php echo htmlspecialchars($settingsMessage); ?>
</div>
<?php endif; ?>

<div class="settings-grid">
    <div class="card">
        <div class="card-title">Gestione Domande</div>
        <form action="settings.php?action=question_create" method="POST" class="settings-form-top">
            <div class="form-group">
                <label for="newQuestion">Nuova domanda</label>
                <textarea id="newQuestion" name="testo" placeholder="Scrivi qui la domanda..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi domanda</button>
        </form>

        <?php if (!empty($domande)): ?>
            <div class="settings-stack">
                <?php foreach ($domande as $d): ?>
                    <div class="settings-item">
                        <form action="settings.php?action=question_update" method="POST" class="settings-form">
                            <input type="hidden" name="domanda_id" value="<?php echo (int) $d['domanda_id']; ?>">
                            <textarea name="testo" required><?php echo htmlspecialchars($d['testo']); ?></textarea>
                            <div class="settings-actions">
                                <button class="btn btn-outline btn-sm" type="submit">Salva modifica</button>
                            </div>
                        </form>
                        <form action="settings.php?action=question_delete" method="POST" class="settings-delete-row">
                            <input type="hidden" name="domanda_id" value="<?php echo (int) $d['domanda_id']; ?>">
                            <button class="btn btn-danger btn-sm" type="submit">Elimina</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Nessuna domanda configurata.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-title">Eliminazione Pazienti</div>
        <p class="text-muted muted-block">Usa il pulsante per aprire il modale di conferma eliminazione.</p>

        <?php if (!empty($clients)): ?>
            <div class="scroll-list">
                <?php foreach ($clients as $c): ?>
                    <div class="settings-item">
                        <div class="settings-item-title">
                            <?php echo htmlspecialchars($c['nome'] . ' ' . $c['cognome']); ?>
                        </div>
                        <form action="settings.php?action=client_delete" method="POST" class="settings-form">
                            <input type="hidden" name="cliente_id" value="<?php echo (int) $c['cliente_id']; ?>">
                            <button
                                class="btn btn-danger btn-sm"
                                type="button"
                                onclick="DeleteModal.confirmDeleteForm(this.form, { title: 'Elimina paziente', message: 'Confermi eliminazione paziente e storico collegato?', requirePassword: true, passwordLabel: 'Password di conferma', passwordFieldName: 'confirm_password' })">
                                Elimina paziente
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Nessun paziente disponibile.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card section-mt">
    <div class="card-title">Configurazione Cataloghi (Alimenti, Integratori, Farmaci)</div>

    <form action="settings.php?action=catalog_create" method="POST" class="catalog-form">
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <option value="alimento">Alimento</option>
                <option value="integratore">Integratore</option>
                <option value="farmaco">Farmaco</option>
            </select>
        </div>
        <div class="form-group">
            <label for="catalogNome">Nome</label>
            <input id="catalogNome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="catalogDesc">Descrizione</label>
            <input id="catalogDesc" name="descrizione">
        </div>
        <button class="btn btn-primary" type="submit">Aggiungi</button>
    </form>

    <div class="catalog-grid">
        <div class="catalog-col">
            <h3>Alimenti</h3>
            <?php if (!empty($alimenti)): ?>
                <?php foreach ($alimenti as $a): ?>
                    <div class="catalog-row">
                        <span><?php echo htmlspecialchars($a['nome']); ?></span>
                        <form action="settings.php?action=catalog_delete" method="POST">
                            <input type="hidden" name="categoria" value="alimento">
                            <input type="hidden" name="item_id" value="<?php echo (int) $a['alimento_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Nessun alimento.</p>
            <?php endif; ?>
        </div>

        <div class="catalog-col">
            <h3>Integratori</h3>
            <?php if (!empty($integratori)): ?>
                <?php foreach ($integratori as $i): ?>
                    <div class="catalog-row">
                        <span><?php echo htmlspecialchars($i['nome']); ?></span>
                        <form action="settings.php?action=catalog_delete" method="POST">
                            <input type="hidden" name="categoria" value="integratore">
                            <input type="hidden" name="item_id" value="<?php echo (int) $i['integratore_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Nessun integratore.</p>
            <?php endif; ?>
        </div>

        <div class="catalog-col">
            <h3>Farmaci</h3>
            <?php if (!empty($farmaci)): ?>
                <?php foreach ($farmaci as $f): ?>
                    <div class="catalog-row">
                        <span><?php echo htmlspecialchars($f['nome']); ?></span>
                        <form action="settings.php?action=catalog_delete" method="POST">
                            <input type="hidden" name="categoria" value="farmaco">
                            <input type="hidden" name="item_id" value="<?php echo (int) $f['farmaco_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Nessun farmaco.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

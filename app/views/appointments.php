<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">Dashboard &rsaquo; <span>Appuntamenti</span></div>
        <h1>Appuntamenti</h1>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    <!-- Tabella appuntamenti -->
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid var(--border);">
            <div class="card-title" style="margin:0;">Prossimi Appuntamenti</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Orario</th>
                        <th>Paziente</th>
                        <th>Tipo</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><strong><?php echo date('d/m/Y', strtotime($app['data'])); ?></strong></td>
                        <td class="text-muted"><?php echo substr($app['ora_inizio'],0,5).' – '.substr($app['ora_fine'],0,5); ?></td>
                        <td>
                            <div class="client-info">
                                <div class="avatar" style="width:30px;height:30px;font-size:11px;">
                                    <?php echo strtoupper(substr($app['nome'],0,1).substr($app['cognome'],0,1)); ?>
                                </div>
                                <?php echo htmlspecialchars($app['nome'].' '.$app['cognome']); ?>
                            </div>
                        </td>
                        <td><span class="badge badge-purple"><?php echo htmlspecialchars($app['tipo'] ?? 'Visita'); ?></span></td>
                        <td>
                            <?php
                            $stato = $app['stato'] ?? 'Programmato';
                            $badge = match($stato) {
                                'Completato' => 'badge-green',
                                'Annullato'  => 'badge-red',
                                default      => 'badge-yellow'
                            };
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($stato); ?></span>
                        </td>
                        <td>
                            <a href="appointments.php?action=delete&id=<?php echo $app['appuntamento_id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Eliminare questo appuntamento?')">Elimina</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted);">Nessun appuntamento in programma.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form nuovo appuntamento -->
    <div class="card">
        <div class="card-title">Nuovo Appuntamento</div>
        <form action="appointments.php?action=create" method="POST" id="appointmentForm" novalidate>
            <div class="form-group">
                <label for="cliente_id">Paziente *</label>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">Seleziona paziente…</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?php echo $c['cliente_id']; ?>">
                        <?php echo htmlspecialchars($c['nome'].' '.$c['cognome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="form-error" id="clienteError"></span>
            </div>
            <div class="form-group">
                <label for="data">Data *</label>
                <input type="date" id="data" name="data">
                <span class="form-error" id="dataError"></span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label for="ora_inizio">Ora Inizio *</label>
                    <input type="time" id="ora_inizio" name="ora_inizio">
                    <span class="form-error" id="oraInizioError"></span>
                </div>
                <div class="form-group">
                    <label for="ora_fine">Ora Fine *</label>
                    <input type="time" id="ora_fine" name="ora_fine">
                    <span class="form-error" id="oraFineError"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="Visita">Visita</option>
                    <option value="Consulenza">Consulenza</option>
                    <option value="Follow-up">Follow-up</option>
                    <option value="Prima visita">Prima visita</option>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" name="note" style="min-height:70px;" placeholder="Note sull'appuntamento…"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Salva Appuntamento
            </button>
        </form>
    </div>

</div>

<script src="../assets/js/validation.js"></script>
<script>
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    let ok = true;
    ok = Validation.selected(document.getElementById('cliente_id'),  document.getElementById('clienteError'),   'Seleziona un paziente.') && ok;
    ok = Validation.required(document.getElementById('data'),        document.getElementById('dataError'),      'La data è obbligatoria.') && ok;
    ok = Validation.required(document.getElementById('ora_inizio'),  document.getElementById('oraInizioError'), 'L\'ora di inizio è obbligatoria.') && ok;
    ok = Validation.required(document.getElementById('ora_fine'),    document.getElementById('oraFineError'),   'L\'ora di fine è obbligatoria.') && ok;

    // Verifica ora_inizio < ora_fine
    const inizio = document.getElementById('ora_inizio').value;
    const fine   = document.getElementById('ora_fine').value;
    if (ok && inizio >= fine) {
        Validation.showError(document.getElementById('ora_fine'), document.getElementById('oraFineError'), 'L\'ora di fine deve essere successiva a quella di inizio.');
        ok = false;
    }
    if (!ok) e.preventDefault();
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

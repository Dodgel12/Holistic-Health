<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php if (!isset($client)): ?>
<!-- ========= FORM NUOVO CLIENTE ========= -->
<div class="top-bar">
    <div>
        <div class="breadcrumb"><a href="clients.php">Pazienti</a> &rsaquo; <span>Nuovo Paziente</span></div>
        <h1>Nuovo Paziente</h1>
    </div>
</div>

<div class="card">
    <form action="clients.php?action=create" method="POST" id="newClientForm" novalidate>
        <div class="form-section-title">Dati Anagrafici</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" placeholder="Mario">
                <span class="form-error" id="nomeError"></span>
            </div>
            <div class="form-group">
                <label for="cognome">Cognome *</label>
                <input type="text" id="cognome" name="cognome" placeholder="Rossi">
                <span class="form-error" id="cognomeError"></span>
            </div>
            <div class="form-group">
                <label for="data_nascita">Data di Nascita</label>
                <input type="date" id="data_nascita" name="data_nascita">
                <span class="form-error" id="dataNascitaError"></span>
            </div>
            <div class="form-group">
                <label for="professione">Professione</label>
                <input type="text" id="professione" name="professione" placeholder="Es. Insegnante">
            </div>
            <div class="form-group">
                <label for="telefono">Telefono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="+39 320 000 0000">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="mario@email.com">
                <span class="form-error" id="emailError"></span>
            </div>
            <div class="form-group form-full">
                <label for="indirizzo">Indirizzo</label>
                <input type="text" id="indirizzo" name="indirizzo" placeholder="Via Roma 1, Milano">
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Salva Paziente
            </button>
            <a href="clients.php" class="btn btn-ghost">Annulla</a>
        </div>
    </form>
</div>

<script src="../assets/js/validation.js"></script>
<script>
document.getElementById('newClientForm').addEventListener('submit', function(e) {
    let ok = true;
    ok = Validation.required(document.getElementById('nome'),     document.getElementById('nomeError'),    'Il nome è obbligatorio.') && ok;
    ok = Validation.required(document.getElementById('cognome'),  document.getElementById('cognomeError'), 'Il cognome è obbligatorio.') && ok;
    ok = Validation.email(document.getElementById('email'),       document.getElementById('emailError')) && ok;
    if (!ok) e.preventDefault();
});
Validation.bindClear([
    {field: document.getElementById('nome'),    errorEl: document.getElementById('nomeError')},
    {field: document.getElementById('cognome'), errorEl: document.getElementById('cognomeError')},
    {field: document.getElementById('email'),   errorEl: document.getElementById('emailError')},
]);
</script>

<?php else: ?>
<!-- ========= VISTA CLIENTE ========= -->
<div class="top-bar">
    <div>
        <div class="breadcrumb"><a href="clients.php">Pazienti</a> &rsaquo; <span><?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></span></div>
        <h1><?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></h1>
    </div>
    <div class="top-bar-actions">
        <?php 
        $hasAnamnesi = (new App\Models\SchedaAnamnestica())->hasAnamnesis($client['cliente_id']);
        if (!$hasAnamnesi): ?>
            <a href="anamnesis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary" title="Esegui prima l'anamnesi">
                + Inizia con Anamnesi
            </a>
        <?php else: ?>
            <a href="anamnesis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-outline" style="opacity: 0.6;">
                Rifai Anamnesi
            </a>
            <a href="analysis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">
                + Nuova Visita
            </a>
        <?php endif; ?>
        <a href="clients.php" class="btn btn-ghost btn-sm">← Torna alla lista</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

    <!-- Dati anagrafici -->
    <div class="card">
        <div class="card-title">Dati Anagrafici</div>
        <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px;">
            <div class="avatar" style="width:56px;height:56px;font-size:20px;">
                <?php echo strtoupper(substr($client['nome'],0,1).substr($client['cognome'],0,1)); ?>
            </div>
            <div>
                <div style="font-size:18px;font-weight:700;"><?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></div>
                <div class="text-muted text-sm">ID: #<?php printf('%04d', $client['cliente_id']); ?></div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <div class="text-muted text-sm">Data di Nascita</div>
                <div style="font-weight:500;"><?php echo $client['data_nascita'] ? date('d/m/Y', strtotime($client['data_nascita'])) : '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Professione</div>
                <div style="font-weight:500;"><?php echo htmlspecialchars($client['professione'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Telefono</div>
                <div style="font-weight:500;"><?php echo htmlspecialchars($client['telefono'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Email</div>
                <div style="font-weight:500;"><?php echo htmlspecialchars($client['email'] ?? '—'); ?></div>
            </div>
            <div class="form-full" style="grid-column:1/-1;">
                <div class="text-muted text-sm">Indirizzo</div>
                <div style="font-weight:500;"><?php echo htmlspecialchars($client['indirizzo'] ?? '—'); ?></div>
            </div>
        </div>
    </div>

    <!-- Visite recenti -->
    <div class="card">
        <div class="flex-between mb-2">
            <div class="card-title" style="margin-bottom:0;">Visite Recenti</div>
            <a href="visits.php?action=history&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-ghost btn-sm">Vedi tutte →</a>
        </div>

        <?php if (!empty($visite)): ?>
            <?php foreach (array_slice($visite, 0, 5) as $v): ?>
            <div class="visit-item">
                <div>
                    <div style="font-weight:500;"><?php echo date('d/m/Y', strtotime($v['data_analisi'])); ?></div>
                    <div class="visit-date"><?php echo htmlspecialchars(substr($v['note'] ?? 'Visita di controllo', 0, 60)); ?></div>
                </div>
                <a href="visits.php?action=show&id=<?php echo $v['visita_id']; ?>" class="btn btn-ghost btn-sm">Vedi</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-sm" style="text-align:center;padding:20px;">Nessuna visita registrata.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

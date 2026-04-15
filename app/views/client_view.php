<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$formClient = $clientForm ?? null;
$isClientEdit = is_array($formClient);
?>

<?php if (!isset($client) || $isClientEdit): ?>
<!-- ========= FORM NUOVO CLIENTE ========= -->
<div class="top-bar">
    <div>
        <div class="breadcrumb"><a href="clients.php">Pazienti</a> &rsaquo; <span><?php echo $isClientEdit ? 'Modifica Paziente' : 'Nuovo Paziente'; ?></span></div>
        <h1><?php echo $isClientEdit ? 'Modifica Paziente' : 'Nuovo Paziente'; ?></h1>
    </div>
</div>

<div class="card">
    <form action="<?php echo $isClientEdit ? 'clients.php?action=update' : 'clients.php?action=create'; ?>" method="POST" id="newClientForm" novalidate>
        <?php if ($isClientEdit): ?>
            <input type="hidden" name="cliente_id" value="<?php echo (int) $formClient['cliente_id']; ?>">
        <?php endif; ?>

        <div class="form-section-title">Dati Anagrafici</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" placeholder="Mario" value="<?php echo htmlspecialchars($formClient['nome'] ?? ''); ?>">
                <span class="form-error" id="nomeError"></span>
            </div>
            <div class="form-group">
                <label for="cognome">Cognome *</label>
                <input type="text" id="cognome" name="cognome" placeholder="Rossi" value="<?php echo htmlspecialchars($formClient['cognome'] ?? ''); ?>">
                <span class="form-error" id="cognomeError"></span>
            </div>
            <div class="form-group">
                <label for="data_nascita">Data di Nascita</label>
                <input type="date" id="data_nascita" name="data_nascita" value="<?php echo htmlspecialchars($formClient['data_nascita'] ?? ''); ?>">
                <span class="form-error" id="dataNascitaError"></span>
            </div>
            <div class="form-group">
                <label for="professione">Professione</label>
                <input type="text" id="professione" name="professione" placeholder="Es. Insegnante" value="<?php echo htmlspecialchars($formClient['professione'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="telefono">Telefono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="+39 320 000 0000" value="<?php echo htmlspecialchars($formClient['telefono'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="mario@email.com" value="<?php echo htmlspecialchars($formClient['email'] ?? ''); ?>">
                <span class="form-error" id="emailError"></span>
            </div>
            <div class="form-group form-full">
                <label for="indirizzo">Indirizzo</label>
                <input type="text" id="indirizzo" name="indirizzo" placeholder="Via Roma 1, Milano" value="<?php echo htmlspecialchars($formClient['indirizzo'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-actions section-mt">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php echo $isClientEdit ? 'Aggiorna Paziente' : 'Salva Paziente'; ?>
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
        <a href="clients.php?action=edit&id=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">Modifica Dati</a>
        <a href="therapy.php?clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">Piano Terapeutico</a>
        <?php 
        $hasAnamnesi = (new App\Models\SchedaAnamnestica())->hasAnamnesis($client['cliente_id']);
        if (!$hasAnamnesi): ?>
            <a href="anamnesis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary" title="Esegui prima l'anamnesi">
                + Nuova Visita Anamnestica
            </a>
        <?php else: ?>
            <a href="anamnesis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">
                + Nuova Visita Anamnestica
            </a>
            <a href="analysis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">
                + Nuova Visita Fisica
            </a>
        <?php endif; ?>
        <a href="clients.php" class="btn btn-ghost btn-sm">← Torna alla lista</a>
    </div>
</div>

<div class="client-details-grid">

    <!-- Dati anagrafici -->
    <div class="card">
        <div class="card-title">Dati Anagrafici</div>
        <div class="client-profile-head">
            <div class="avatar client-profile-avatar">
                <?php echo strtoupper(substr($client['nome'],0,1).substr($client['cognome'],0,1)); ?>
            </div>
            <div>
                <div class="client-profile-name"><?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></div>
                <div class="text-muted text-sm">ID: #<?php printf('%04d', $client['cliente_id']); ?></div>
            </div>
        </div>

        <div class="client-info-grid">
            <div>
                <div class="text-muted text-sm">Data di Nascita</div>
                <div class="client-info-value"><?php echo $client['data_nascita'] ? date('d/m/Y', strtotime($client['data_nascita'])) : '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Professione</div>
                <div class="client-info-value"><?php echo htmlspecialchars($client['professione'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Telefono</div>
                <div class="client-info-value"><?php echo htmlspecialchars($client['telefono'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Email</div>
                <div class="client-info-value"><?php echo htmlspecialchars($client['email'] ?? '—'); ?></div>
            </div>
            <div class="client-info-full">
                <div class="text-muted text-sm">Indirizzo</div>
                <div class="client-info-value"><?php echo htmlspecialchars($client['indirizzo'] ?? '—'); ?></div>
            </div>
        </div>
    </div>

    <!-- Visite recenti -->
    <div class="card">
        <div class="flex-between mb-2">
            <div class="card-title inline-card-title">Visite Recenti</div>
            <a href="visits.php?action=history&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-ghost btn-sm">Vedi tutte →</a>
        </div>

        <?php if (!empty($visite)): ?>
            <?php foreach (array_slice($visite, 0, 5) as $v): ?>
            <div class="visit-item">
                <div>
                    <div class="client-info-value"><?php echo date('d/m/Y', strtotime($v['data_analisi'])); ?></div>
                    <div class="visit-date"><?php echo htmlspecialchars(substr($v['note'] ?? 'Visita di controllo', 0, 60)); ?></div>
                </div>
                <a href="visits.php?action=show&id=<?php echo $v['visita_id']; ?>" class="btn btn-ghost btn-sm">Vedi</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-sm visits-empty">Nessuna visita registrata.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card section-mt">
    <div class="flex-between ai-header">
        <div class="card-title inline-card-title">Assistente AI: andamento cliente</div>
        <form method="POST" action="clients.php?action=generate_ai_summary&id=<?php echo (int) $client['cliente_id']; ?>">
            <button type="submit" class="btn btn-primary btn-sm">Andamento cliente (AI)</button>
        </form>
    </div>

    <?php if (!empty($aiMessage)): ?>
        <div class="badge badge-green chip-inline">
            <?php echo htmlspecialchars($aiMessage); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($aiError)): ?>
        <div class="badge badge-red chip-inline">
            <?php echo htmlspecialchars($aiError); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($aiCanGenerate)): ?>
        <p class="text-muted text-sm muted-block">
            API non configurata. <?php echo htmlspecialchars($aiConfigHint ?? 'Configura la chiave API.'); ?>
        </p>
    <?php endif; ?>

    <p class="text-muted text-sm muted-block">
        Fonte riepilogo: <strong><?php echo ($aiSource ?? 'local') === 'api' ? 'AI esterna (API key)' : 'algoritmo locale'; ?></strong>
    </p>

    <p class="text-muted body-copy">
        <?php echo htmlspecialchars($aiSummary ?? 'Nessun riepilogo disponibile.'); ?>
    </p>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

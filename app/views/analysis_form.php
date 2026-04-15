<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$isEdit = isset($visita) && !empty($visita);
$fisica = $isEdit ? ($visita['scheda_fisica'] ?? []) : [];
$formAction = $isEdit ? 'visits.php?action=update' : 'analysis.php?action=store';
$pageTitle = $isEdit ? 'Modifica Visita Fisica' : 'Nuova Visita Fisica';
$submitLabel = $isEdit ? 'Aggiorna Visita Fisica' : 'Salva Visita Fisica';
$dataDefault = $isEdit
    ? ($visita['data_analisi'] ?? date('Y-m-d'))
    : date('Y-m-d');
?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>">
                <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?>
            </a> &rsaquo; <span><?php echo $pageTitle; ?></span>
        </div>
        <h1><?php echo $pageTitle; ?> — <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></h1>
    </div>
</div>

<div class="card">
    <form action="<?php echo $formAction; ?>" method="POST" id="visitForm" novalidate>
        <input type="hidden" name="cliente_id" value="<?php echo $client['cliente_id']; ?>">
        <input type="hidden" name="tipo_visita" value="fisica">
        <?php if ($isEdit): ?>
            <input type="hidden" name="visita_id" value="<?php echo (int) $visita['visita_id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="data_analisi">Data visita *</label>
            <input type="date" id="data_analisi" name="data_analisi" value="<?php echo htmlspecialchars($dataDefault); ?>" required>
            <span class="form-error" id="dataAnalisiError"></span>
        </div>

        <!-- Note generali -->
        <div class="form-group">
            <label for="note">Note Generali</label>
            <textarea id="note" name="note" placeholder="Osservazioni sulla visita…"><?php echo htmlspecialchars($isEdit ? ($visita['note'] ?? '') : ''); ?></textarea>
        </div>

        <div class="form-section-title">Dati Fisici (solo visita fisica)</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="peso">Peso (kg) *</label>
                <input type="number" step="0.1" id="peso" name="peso" min="20" max="300" placeholder="70.0" value="<?php echo htmlspecialchars($fisica['peso'] ?? ''); ?>">
                <span class="form-error" id="pesoError"></span>
            </div>
            <div class="form-group">
                <label for="altezza">Altezza (cm) *</label>
                <input type="number" step="0.1" id="altezza" name="altezza" min="100" max="250" placeholder="170" value="<?php echo htmlspecialchars($fisica['altezza'] ?? ''); ?>">
                <span class="form-error" id="altezzaError"></span>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php echo $submitLabel; ?>
            </button>
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>" class="btn btn-ghost">Annulla</a>
        </div>
    </form>
</div>

<script src="../assets/js/validation.js"></script>
<script>
// Validazione form.
document.getElementById('visitForm').addEventListener('submit', function(e) {
    let ok = true;
    ok = Validation.required(document.getElementById('data_analisi'),  document.getElementById('dataAnalisiError'), 'La data visita è obbligatoria.') && ok;
    ok = Validation.required(document.getElementById('peso'),         document.getElementById('pesoError'), 'Il peso è obbligatorio.') && ok;
    ok = Validation.numericRange(document.getElementById('peso'),     document.getElementById('pesoError'), 20, 300) && ok;
    ok = Validation.required(document.getElementById('altezza'),      document.getElementById('altezzaError'), 'L\'altezza è obbligatoria.') && ok;
    ok = Validation.numericRange(document.getElementById('altezza'),  document.getElementById('altezzaError'), 100, 250) && ok;

    if (!ok) e.preventDefault();
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

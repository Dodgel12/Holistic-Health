<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>">
                <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?>
            </a> &rsaquo; <span>Nuova Visita</span>
        </div>
        <h1>Nuova Visita — <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></h1>
    </div>
</div>

<div class="card">
    <form action="analysis.php?action=store" method="POST" id="visitForm" novalidate>
        <input type="hidden" name="cliente_id" value="<?php echo $client['cliente_id']; ?>">

        <!-- Note generali -->
        <div class="form-group">
            <label for="note">Note Generali</label>
            <textarea id="note" name="note" placeholder="Osservazioni sulla visita…"></textarea>
        </div>

        <div class="form-section-title">Dati Fisici</div>
        <div class="form-grid-3">
            <div class="form-group">
                <label for="peso">Peso (kg) *</label>
                <input type="number" step="0.1" id="peso" name="peso" min="20" max="300" placeholder="70.0">
                <span class="form-error" id="pesoError"></span>
            </div>
            <div class="form-group">
                <label for="altezza">Altezza (cm) *</label>
                <input type="number" step="0.1" id="altezza" name="altezza" min="100" max="250" placeholder="170">
                <span class="form-error" id="altezzaError"></span>
            </div>
            <div class="form-group">
                <label>Massa Magra (kg) — calcolata</label>
                <div class="computed-field" id="massaMagraDisplay">— inserisci peso e % grasso</div>
            </div>
            <div class="form-group">
                <label for="massa_grassa">Massa Grassa (%) *</label>
                <input type="number" step="0.1" id="massa_grassa" name="massa_grassa" min="2" max="70" placeholder="25.0">
                <span class="form-error" id="massaGrassaError"></span>
            </div>
            <div class="form-group">
                <label for="acqua_corporea">Acqua Corporea (%)</label>
                <input type="number" step="0.1" id="acqua_corporea" name="acqua_corporea" min="20" max="80" placeholder="55.0">
                <span class="form-error" id="acquaError"></span>
            </div>
            <div class="form-group">
                <label for="metabolismo_basale">Metabolismo Basale (kcal)</label>
                <input type="number" id="metabolismo_basale" name="metabolismo_basale" min="500" max="5000" placeholder="1500">
                <span class="form-error" id="metaError"></span>
            </div>
            <div class="form-group">
                <label for="eta_metabolica">Età Metabolica (anni)</label>
                <input type="number" id="eta_metabolica" name="eta_metabolica" min="10" max="100" placeholder="35">
                <span class="form-error" id="etaMetError"></span>
            </div>
            <div class="form-group">
                <label for="grasso_viscerale">Grasso Viscerale (livello 1-20)</label>
                <input type="number" id="grasso_viscerale" name="grasso_viscerale" min="1" max="20" placeholder="5">
                <span class="form-error" id="grassoViscError"></span>
            </div>
            <div class="form-group">
                <label for="massa_ossea">Massa Ossea (kg)</label>
                <input type="number" step="0.1" id="massa_ossea" name="massa_ossea" min="0.5" max="15" placeholder="3.0">
                <span class="form-error" id="massaOsseaError"></span>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Salva Visita
            </button>
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>" class="btn btn-ghost">Annulla</a>
        </div>
    </form>
</div>

<script src="../assets/js/validation.js"></script>
<script>
// --- Calcolo automatico Massa Magra ---
function updateMassaMagra() {
    const peso  = parseFloat(document.getElementById('peso').value);
    const grasso = parseFloat(document.getElementById('massa_grassa').value);
    const display = document.getElementById('massaMagraDisplay');
    if (!isNaN(peso) && !isNaN(grasso) && peso > 0 && grasso >= 0 && grasso <= 100) {
        const magra = (peso - (peso * grasso / 100)).toFixed(1);
        display.textContent = magra + ' kg';
    } else {
        display.textContent = '— inserisci peso e % grasso';
    }
}
document.getElementById('peso').addEventListener('input', updateMassaMagra);
document.getElementById('massa_grassa').addEventListener('input', updateMassaMagra);

// --- Validazione ---
document.getElementById('visitForm').addEventListener('submit', function(e) {
    let ok = true;
    ok = Validation.required(document.getElementById('peso'),         document.getElementById('pesoError'), 'Il peso è obbligatorio.') && ok;
    ok = Validation.numericRange(document.getElementById('peso'),     document.getElementById('pesoError'), 20, 300) && ok;
    ok = Validation.required(document.getElementById('altezza'),      document.getElementById('altezzaError'), 'L\'altezza è obbligatoria.') && ok;
    ok = Validation.numericRange(document.getElementById('altezza'),  document.getElementById('altezzaError'), 100, 250) && ok;
    ok = Validation.required(document.getElementById('massa_grassa'), document.getElementById('massaGrassaError'), 'La massa grassa è obbligatoria.') && ok;
    ok = Validation.numericRange(document.getElementById('massa_grassa'), document.getElementById('massaGrassaError'), 2, 70) && ok;

    const optional = [
        {f:'acqua_corporea', e:'acquaError', min:20, max:80},
        {f:'metabolismo_basale', e:'metaError', min:500, max:5000},
        {f:'eta_metabolica', e:'etaMetError', min:10, max:100},
        {f:'grasso_viscerale', e:'grassoViscError', min:1, max:20},
        {f:'massa_ossea', e:'massaOsseaError', min:0.5, max:15},
    ];
    optional.forEach(({f,e,min,max}) => {
        const field = document.getElementById(f);
        if (field.value !== '') {
            ok = Validation.numericRange(field, document.getElementById(e), min, max) && ok;
        }
    });

    if (!ok) e.preventDefault();
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

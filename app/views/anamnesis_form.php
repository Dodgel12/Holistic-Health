<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>">
                <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?>
            </a> &rsaquo; <span>Visita Anamnestica</span>
        </div>
        <h1>Visita Anamnestica — <?php echo htmlspecialchars($client['nome'].' '.$client['cognome']); ?></h1>
    </div>
</div>

<div class="card">
    <form action="anamnesis.php?action=store" method="POST" id="anamnesisForm" novalidate>
        <input type="hidden" name="cliente_id" value="<?php echo $client['cliente_id']; ?>">

        <!-- ===== STILE DI VITA ===== -->
        <div class="form-section-title">Stile di Vita</div>
        <div class="form-grid">
            <div class="form-group form-full">
                <label for="alimentazione">Alimentazione / Dieta Seguita</label>
                <textarea id="alimentazione" name="alimentazione" placeholder="Descrivi le abitudini alimentari…"></textarea>
            </div>
            <div class="form-group">
                <label for="attivita_fisica_tipo">Tipo di Attività Fisica</label>
                <input type="text" id="attivita_fisica_tipo" name="attivita_fisica_tipo" placeholder="Es. Camminata, Nuoto, Palestra">
            </div>
            <div class="form-group">
                <label for="attivita_fisica_frequenza">Frequenza Attività Fisica</label>
                <select id="attivita_fisica_frequenza" name="attivita_fisica_frequenza">
                    <option value="">Seleziona…</option>
                    <option>Mai</option>
                    <option>1-2 volte/settimana</option>
                    <option>3-4 volte/settimana</option>
                    <option>5+ volte/settimana</option>
                    <option>Quotidiana</option>
                </select>
            </div>
            <div class="form-group form-full">
                <label for="stile_vita_descrizione">Altre informazioni sullo stile di vita</label>
                <textarea id="stile_vita_descrizione" name="stile_vita_descrizione" style="min-height:70px;" placeholder="Lavoro sedentario, orari irregolari…"></textarea>
            </div>
        </div>

        <!-- ===== ANAMNESI PERSONALI ===== -->
        <div class="form-section-title">Anamnesi Personali</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Allergie</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="allergie" value="1"> Sì</label>
                    <label class="radio-option"><input type="radio" name="allergie" value="0" checked> No</label>
                </div>
            </div>
            <div class="form-group">
                <label for="allergie_dettagli">Dettagli allergie</label>
                <input type="text" id="allergie_dettagli" name="allergie_dettagli" placeholder="Es. Nichel, pollini, lattosio…">
            </div>
            <div class="form-group">
                <label>Patologie</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="patologie" value="1"> Sì</label>
                    <label class="radio-option"><input type="radio" name="patologie" value="0" checked> No</label>
                </div>
            </div>
            <div class="form-group">
                <label for="patologie_dettagli">Dettagli patologie</label>
                <input type="text" id="patologie_dettagli" name="patologie_dettagli" placeholder="Es. Ipertensione, Diabete tipo 2…">
            </div>
            <div class="form-group form-full">
                <label for="interventi_chirurgici">Interventi chirurgici / eventi rilevanti</label>
                <textarea id="interventi_chirurgici" name="interventi_chirurgici" style="min-height:70px;" placeholder="Descrivi eventuali interventi, fratture, ricoveri…"></textarea>
            </div>
            <div class="form-group">
                <label>Consumo di Alcol</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="alcol" value="1"> Sì</label>
                    <label class="radio-option"><input type="radio" name="alcol" value="0" checked> No</label>
                </div>
            </div>
            <div class="form-group">
                <label>Fumo</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="fumo" value="1"> Sì</label>
                    <label class="radio-option"><input type="radio" name="fumo" value="0" checked> No</label>
                </div>
            </div>
            <div class="form-group form-full">
                <label for="farmaci_correnti">Farmaci / Integratori attualmente assunti</label>
                <textarea id="farmaci_correnti" name="farmaci_correnti" style="min-height:70px;" placeholder="Nome farmaco, dosaggio, frequenza…"></textarea>
            </div>
        </div>

        <!-- ===== STATO PSICO-FISICO ===== -->
        <div class="form-section-title">Stato Psico-Fisico</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="livello_stress">Livello di Stress (1=basso, 10=alto): <strong id="stressVal">5</strong></label>
                <input type="range" id="livello_stress" name="livello_stress" min="1" max="10" value="5"
                       oninput="document.getElementById('stressVal').textContent = this.value">
                <div class="range-labels"><span>Basso</span><span>Alto</span></div>
            </div>
            <div class="form-group">
                <label for="concentrazione">Livello di Concentrazione (1–10): <strong id="concVal">5</strong></label>
                <input type="range" id="concentrazione" name="concentrazione" min="1" max="10" value="5"
                       oninput="document.getElementById('concVal').textContent = this.value">
                <div class="range-labels"><span>Scarsa</span><span>Ottima</span></div>
            </div>
            <div class="form-group">
                <label for="umore">Umore prevalente</label>
                <select id="umore" name="umore">
                    <option value="">Seleziona…</option>
                    <option>Sereno</option>
                    <option>Irritabile</option>
                    <option>Ansioso</option>
                    <option>Malinconico</option>
                    <option>Variabile</option>
                </select>
            </div>
            <div class="form-group">
                <label>Presenza di Ansia</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="ansia" value="1"> Sì</label>
                    <label class="radio-option"><input type="radio" name="ansia" value="0" checked> No</label>
                </div>
            </div>
            <div class="form-group form-full">
                <label for="motivazione">Motivazione / Obiettivi del cliente</label>
                <textarea id="motivazione" name="motivazione" placeholder="Cosa spinge il cliente a intraprendere questo percorso?"></textarea>
            </div>
        </div>

        <!-- ===== QUALITÀ DEL SONNO ===== -->
        <div class="form-section-title">Qualità del Sonno</div>
        <div class="form-grid-3">
            <div class="form-group">
                <label for="ore_sonno">Ore di Sonno (media)</label>
                <input type="number" step="0.5" min="2" max="14" id="ore_sonno" name="ore_sonno" placeholder="7.5">
                <span class="form-error" id="oreSonnoError"></span>
            </div>
            <div class="form-group">
                <label for="risvegli_notturni">Risvegli Notturni (n/notte)</label>
                <input type="number" min="0" max="20" id="risvegli_notturni" name="risvegli_notturni" placeholder="0">
            </div>
            <div class="form-group">
                <label for="qualita_percepita">Qualità Percepita</label>
                <select id="qualita_percepita" name="qualita_percepita">
                    <option value="">Seleziona…</option>
                    <option>Ottima</option>
                    <option>Buona</option>
                    <option>Discreta</option>
                    <option>Scarsa</option>
                    <option>Pessima</option>
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-top:12px;">
            <label>Difficoltà ad addormentarsi</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="difficolta_addormentarsi" value="1"> Sì</label>
                <label class="radio-option"><input type="radio" name="difficolta_addormentarsi" value="0" checked> No</label>
            </div>
        </div>

        <!-- ===== OSSERVAZIONI FINALI ===== -->
        <div class="form-section-title">Osservazioni Finali</div>
        <div class="form-group">
            <label for="osservazioni_finali">Note e osservazioni conclusive *</label>
            <textarea id="osservazioni_finali" name="osservazioni_finali" rows="5"
                      placeholder="Sintesi dell'anamnesi, priorità d'intervento…"></textarea>
            <span class="form-error" id="osservazioniError"></span>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Salva Anamnesi
            </button>
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>" class="btn btn-ghost">Annulla</a>
        </div>
    </form>
</div>

<script src="../assets/js/validation.js"></script>
<script>
document.getElementById('anamnesisForm').addEventListener('submit', function(e) {
    let ok = true;
    ok = Validation.required(
        document.getElementById('osservazioni_finali'),
        document.getElementById('osservazioniError'),
        'Le osservazioni finali sono obbligatorie.'
    ) && ok;

    const oreSonno = document.getElementById('ore_sonno');
    if (oreSonno.value !== '') {
        ok = Validation.numericRange(oreSonno, document.getElementById('oreSonnoError'), 2, 14) && ok;
    }
    if (!ok) e.preventDefault();
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

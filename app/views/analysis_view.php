<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$tipoVisita = $visita['tipo_visita'] ?? 'anamnestica';
$a = $visita['anamnesi_snapshot'] ?? null;
$f = $visita['fisica_snapshot'] ?? ($visita['scheda_fisica'] ?? []);
?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="visits.php?action=history&clientId=<?php echo $visita['cliente_id']; ?>">Storico</a>
            &rsaquo; <span>Visita del <?php echo date('d/m/Y', strtotime($visita['data_analisi'])); ?></span>
        </div>
        <h1>Visita del <?php echo date('d F Y', strtotime($visita['data_analisi'])); ?></h1>
        <div class="text-muted text-sm">Tipo: <?php echo $tipoVisita === 'fisica' ? 'Visita fisica' : 'Visita anamnestica'; ?></div>
    </div>
    <div class="top-bar-actions">
        <a href="visits.php?action=history&clientId=<?php echo $visita['cliente_id']; ?>" class="btn btn-ghost">← Storico</a>
    </div>
</div>

<div class="card">
    <div class="card-title">Note visita</div>
    <p class="note-block">
        <?php echo nl2br(htmlspecialchars($visita['note'] ?? 'Nessuna nota per questa visita.')); ?>
    </p>
</div>

<?php if (!empty($a)): ?>
<div class="form-section-title">Dati Anamnestici (ultimo disponibile fino a questa data)</div>

<div class="grid-2-col-gap-20">
    <div class="card">
        <div class="card-title">Stile di Vita</div>
        <div class="grid-gap-12">
            <div>
                <div class="text-muted text-sm">Alimentazione</div>
                <div><?php echo nl2br(htmlspecialchars($a['stile_vita']['alimentazione'] ?? '—')); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Attività Fisica</div>
                <div>
                    <?php echo htmlspecialchars($a['stile_vita']['attivita_fisica_tipo'] ?? '—'); ?>
                    (<?php echo htmlspecialchars($a['stile_vita']['attivita_fisica_frequenza'] ?? '—'); ?>)
                </div>
            </div>
            <div>
                <div class="text-muted text-sm">Note Stile di Vita</div>
                <div><?php echo nl2br(htmlspecialchars($a['stile_vita']['descrizione'] ?? '—')); ?></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Anamnesi Personali</div>
        <div class="grid-2-col-gap-12">
            <div>
                <div class="text-muted text-sm">Allergie</div>
                <div><?php echo ($a['personale']['allergie'] ?? 0) ? 'Sì: '.htmlspecialchars($a['personale']['allergie_dettagli']) : 'No'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Patologie</div>
                <div><?php echo ($a['personale']['patologie'] ?? 0) ? 'Sì: '.htmlspecialchars($a['personale']['patologie_dettagli']) : 'No'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Alcol / Fumo</div>
                <div>
                    Alcol: <?php echo ($a['personale']['alcol'] ?? 0) ? 'Sì' : 'No'; ?> /
                    Fumo: <?php echo ($a['personale']['fumo'] ?? 0) ? 'Sì' : 'No'; ?>
                </div>
            </div>
            <div>
                <div class="text-muted text-sm">Farmaci / Integratori</div>
                <div><?php echo nl2br(htmlspecialchars($a['personale']['farmaci_correnti'] ?? '—')); ?></div>
            </div>
            <div class="cell-full">
                <div class="text-muted text-sm">Interventi/Eventi</div>
                <div><?php echo nl2br(htmlspecialchars($a['personale']['interventi_chirurgici'] ?? '—')); ?></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Stato Psico-Fisico</div>
        <div class="grid-2-col-gap-12">
            <div>
                <div class="text-muted text-sm">Stress (1-10)</div>
                <div class="value-strong"><?php echo $a['psico_fisico']['livello_stress'] ?? '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Concentrazione (1-10)</div>
                <div class="value-strong"><?php echo $a['psico_fisico']['concentrazione'] ?? '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Umore</div>
                <div><?php echo htmlspecialchars($a['psico_fisico']['umore'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Ansia</div>
                <div><?php echo ($a['psico_fisico']['ansia'] ?? 0) ? 'Sì' : 'No'; ?></div>
            </div>
            <div class="cell-full">
                <div class="text-muted text-sm">Motivazione/Obiettivi</div>
                <div><?php echo nl2br(htmlspecialchars($a['psico_fisico']['motivazione'] ?? '—')); ?></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Qualità Sonno</div>
        <div class="grid-2-col-gap-12">
            <div>
                <div class="text-muted text-sm">Ore Sonno</div>
                <div><?php echo $a['sonno']['ore_sonno'] ?? '—'; ?> ore</div>
            </div>
            <div>
                <div class="text-muted text-sm">Risvegli</div>
                <div><?php echo $a['sonno']['risvegli_notturni'] ?? '0'; ?> a notte</div>
            </div>
            <div>
                <div class="text-muted text-sm">Qualità</div>
                <div><?php echo htmlspecialchars($a['sonno']['qualita_percepita'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Diff. Addormentarsi</div>
                <div><?php echo ($a['sonno']['difficolta_addormentarsi'] ?? 0) ? 'Sì' : 'No'; ?></div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($a['risposte_domande'])): ?>
<div class="card section-mt">
    <div class="card-title">Domande Aggiuntive</div>
    <div class="grid-gap-10">
        <?php foreach ($a['risposte_domande'] as $r): ?>
            <div class="analysis-answer-item">
                <div class="text-muted text-sm mb-1"><?php echo htmlspecialchars($r['domanda'] ?? 'Domanda'); ?></div>
                <div><?php echo nl2br(htmlspecialchars($r['risposta'] ?? '')); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="card section-mt">
    <div class="card-title">Osservazioni Finali</div>
    <div class="analysis-observation-box">
        <?php echo nl2br(htmlspecialchars($a['osservazioni_finali'] ?? '—')); ?>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-title">Dati Anamnestici</div>
    <p class="text-muted margins-reset">Nessun dato anamnestico disponibile fino a questa data.</p>
</div>
<?php endif; ?>

<div class="form-section-title">Valori Fisici di Riferimento (fino a questa data)</div>

<div class="grid-2-col-gap-20">
    <div class="card">
        <div class="card-title">Dati Fisici</div>
        <?php if (!empty($f)): ?>
            <?php if (!empty($f['data_analisi'])): ?>
                <p class="text-muted text-sm margins-reset">Rilevazione del <?php echo date('d/m/Y', strtotime($f['data_analisi'])); ?></p>
            <?php endif; ?>
            <div class="grid-2-col-gap-16">
                <?php
                $campi = [
                    'peso'              => ['Peso', 'kg'],
                    'altezza'           => ['Altezza', 'cm'],
                ];
                foreach ($campi as $key => [$label, $unit]):
                ?>
                <div>
                    <div class="text-muted text-sm"><?php echo $label; ?></div>
                    <div class="value-primary-lg">
                        <?php echo isset($f[$key]) && $f[$key] !== null ? $f[$key].' '.$unit : '—'; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted margins-reset">Nessuna rilevazione fisica disponibile fino a questa data.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-title">Contesto</div>
        <div class="grid-2-col-gap-12">
            <div>
                <div class="text-muted text-sm">Data visita corrente</div>
                <div class="value-strong"><?php echo date('d/m/Y', strtotime($visita['data_analisi'])); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Tipo visita</div>
                <div class="value-strong"><?php echo $tipoVisita === 'fisica' ? 'Fisica' : 'Anamnestica'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">ID visita</div>
                <div class="value-strong">#<?php echo (int) $visita['visita_id']; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Riferimento fisico</div>
                <div class="value-strong">
                    <?php echo !empty($f['data_analisi']) ? date('d/m/Y', strtotime($f['data_analisi'])) : 'Nessuno'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

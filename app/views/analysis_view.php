<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$visitaId = $visita['visita_id'];
$f = $visita['scheda_fisica'] ?? [];
$magra = (isset($f['peso'], $f['massa_grassa']))
    ? round($f['peso'] - ($f['peso'] * $f['massa_grassa'] / 100), 1)
    : null;
?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="visits.php?action=history&clientId=<?php echo $visita['cliente_id']; ?>">Storico</a>
            &rsaquo; <span>Visita del <?php echo date('d/m/Y', strtotime($visita['data_analisi'])); ?></span>
        </div>
        <h1>Visita del <?php echo date('d F Y', strtotime($visita['data_analisi'])); ?></h1>
    </div>
    <div class="top-bar-actions">
        <a href="visits.php?action=history&clientId=<?php echo $visita['cliente_id']; ?>" class="btn btn-ghost">← Storico</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    <div class="card">
        <div class="card-title">Dati Fisici</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <?php
            $campi = [
                'peso'              => ['Peso', 'kg'],
                'altezza'           => ['Altezza', 'cm'],
                'massa_grassa'      => ['Massa Grassa', '%'],
                'acqua_corporea'    => ['Acqua Corporea', '%'],
                'metabolismo_basale'=> ['Metabolismo Basale', 'kcal'],
                'eta_metabolica'    => ['Età Metabolica', 'anni'],
                'grasso_viscerale'  => ['Grasso Viscerale', 'livello'],
                'massa_ossea'       => ['Massa Ossea', 'kg'],
            ];
            foreach ($campi as $key => [$label, $unit]):
            ?>
            <div>
                <div class="text-muted text-sm"><?php echo $label; ?></div>
                <div style="font-size:18px; font-weight:700; color:var(--primary);">
                    <?php echo isset($f[$key]) && $f[$key] !== null ? $f[$key].' '.$unit : '—'; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <div>
                <div class="text-muted text-sm">Massa Magra (calcolata)</div>
                <div style="font-size:18px; font-weight:700; color:var(--accent);">
                    <?php echo $magra !== null ? $magra.' kg' : '—'; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Note</div>
        <p style="color:var(--text-muted); line-height:1.7;">
            <?php echo nl2br(htmlspecialchars($visita['note'] ?? 'Nessuna nota per questa visita.')); ?>
        </p>
    </div>

</div>

<?php if (!empty($visita['anamnesi'])):
    $a = $visita['anamnesi'];
?>
<div class="form-section-title">Dati Anamnestici</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
    <!-- Stile di Vita -->
    <div class="card">
        <div class="card-title">Stile di Vita</div>
        <div style="display:grid; gap:12px;">
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

    <!-- Anamnesi Personali -->
    <div class="card">
        <div class="card-title">Anamnesi Personali</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
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
            <div style="grid-column:1/-1;">
                <div class="text-muted text-sm">Interventi/Eventi</div>
                <div><?php echo nl2br(htmlspecialchars($a['personale']['interventi_chirurgici'] ?? '—')); ?></div>
            </div>
        </div>
    </div>

    <!-- Stato Psico-Fisico -->
    <div class="card">
        <div class="card-title">Stato Psico-Fisico</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <div class="text-muted text-sm">Stress (1-10)</div>
                <div style="font-weight:600;"><?php echo $a['psico_fisico']['livello_stress'] ?? '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Concentrazione (1-10)</div>
                <div style="font-weight:600;"><?php echo $a['psico_fisico']['concentrazione'] ?? '—'; ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Umore</div>
                <div><?php echo htmlspecialchars($a['psico_fisico']['umore'] ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-muted text-sm">Ansia</div>
                <div><?php echo ($a['psico_fisico']['ansia'] ?? 0) ? 'Sì' : 'No'; ?></div>
            </div>
            <div style="grid-column:1/-1;">
                <div class="text-muted text-sm">Motivazione/Obiettivi</div>
                <div><?php echo nl2br(htmlspecialchars($a['psico_fisico']['motivazione'] ?? '—')); ?></div>
            </div>
        </div>
    </div>

    <!-- Qualità Sonno -->
    <div class="card">
        <div class="card-title">Qualità Sonno</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
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

<div class="card">
    <div class="card-title">Osservazioni Finali</div>
    <div style="background:var(--bg); padding:16px; border-radius:var(--radius-sm); border-left:4px solid var(--primary);">
        <?php echo nl2br(htmlspecialchars($a['osservazioni_finali'] ?? '—')); ?>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

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

<?php require_once __DIR__ . '/partials/footer.php'; ?>

<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
// $client, $visite, $fisicaData (array of scheda_fisica per ogni visita) passati dal controller
$nomeCliente = htmlspecialchars($client['nome'].' '.$client['cognome']);
$labelsJson  = json_encode(array_column($visite, 'data_analisi'));
$pesoJson    = json_encode(array_map(fn($v) => $v['fisica']['peso'] ?? null, $visite));
$grassoJson  = json_encode(array_map(fn($v) => $v['fisica']['massa_grassa'] ?? null, $visite));
?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">
            <a href="clients.php">Pazienti</a> &rsaquo;
            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>"><?php echo $nomeCliente; ?></a>
            &rsaquo; <span>Storico Visite</span>
        </div>
        <h1>Storico Visite — <?php echo $nomeCliente; ?></h1>
    </div>
    <div class="top-bar-actions">
        <a href="analysis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">
            + Nuova Visita
        </a>
    </div>
</div>

<?php if (count($visite) >= 2): ?>
<!-- Grafici -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <div class="chart-title">Andamento Peso (kg)</div>
        <div class="chart-container"><canvas id="pesoChart"></canvas></div>
    </div>
    <div class="card">
        <div class="chart-title">Andamento Massa Grassa (%)</div>
        <div class="chart-container"><canvas id="grassoChart"></canvas></div>
    </div>
</div>
<?php endif; ?>

<!-- Tabella visite -->
<div class="card" style="padding:0; overflow:hidden;">
    <div style="padding:16px 20px; border-bottom:1px solid var(--border);">
        <div class="card-title" style="margin:0;"><?php echo count($visite); ?> visite registrate</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Peso</th>
                    <th>Massa Grassa</th>
                    <th>Massa Magra</th>
                    <th>Acqua</th>
                    <th>Note</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($visite)): ?>
                <?php foreach ($visite as $v):
                    $f = $v['fisica'] ?? [];
                    $magra = (isset($f['peso'], $f['massa_grassa']))
                        ? round($f['peso'] - ($f['peso'] * $f['massa_grassa'] / 100), 1)
                        : null;
                ?>
                <tr>
                    <td><strong><?php echo date('d/m/Y', strtotime($v['data_analisi'])); ?></strong></td>
                    <td><?php echo isset($f['peso']) ? $f['peso'].' kg' : '—'; ?></td>
                    <td><?php echo isset($f['massa_grassa']) ? $f['massa_grassa'].' %' : '—'; ?></td>
                    <td><?php echo $magra !== null ? $magra.' kg' : '—'; ?></td>
                    <td><?php echo isset($f['acqua_corporea']) ? $f['acqua_corporea'].' %' : '—'; ?></td>
                    <td class="text-muted text-sm"><?php echo htmlspecialchars(substr($v['note'] ?? '', 0, 50)); ?></td>
                    <td>
                        <a href="visits.php?action=show&id=<?php echo $v['visita_id']; ?>" class="btn btn-ghost btn-sm">Dettagli</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted);">Nessuna visita registrata.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (count($visite) >= 2): ?>
<script>
const labels = <?php echo $labelsJson; ?>.map(d => {
    const dt = new Date(d);
    return dt.toLocaleDateString('it-IT', {day:'2-digit', month:'short', year:'2-digit'});
});
const pesoData   = <?php echo $pesoJson; ?>;
const grassoData = <?php echo $grassoJson; ?>;

const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } }
    },
    elements: { point: { radius: 4, hoverRadius: 6 } }
};

new Chart(document.getElementById('pesoChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            data: pesoData,
            borderColor: '#3D1E8F',
            backgroundColor: 'rgba(61,30,143,0.08)',
            fill: true,
            tension: 0.3,
            borderWidth: 2
        }]
    },
    options: commonOptions
});

new Chart(document.getElementById('grassoChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            data: grassoData,
            borderColor: '#7C4DFF',
            backgroundColor: 'rgba(124,77,255,0.08)',
            fill: true,
            tension: 0.3,
            borderWidth: 2
        }]
    },
    options: commonOptions
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

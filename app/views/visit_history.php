<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
// Dati passati dal controller: cliente, visite e schede fisiche collegate.
$nomeCliente = htmlspecialchars($client['nome'].' '.$client['cognome']);

$chartVisite = $visite;
usort($chartVisite, function ($a, $b) {
    if ($a['data_analisi'] === $b['data_analisi']) {
        return ($a['visita_id'] ?? 0) <=> ($b['visita_id'] ?? 0);
    }
    return strcmp($a['data_analisi'], $b['data_analisi']);
});

$chartVisite = array_values(array_filter($chartVisite, function ($v) {
    return ($v['tipo_visita'] ?? 'anamnestica') === 'fisica';
}));

$labelsJson = json_encode(array_map(function ($v) {
    return date('d/m/y', strtotime($v['data_analisi'])) . ' · #' . (int) ($v['visita_id'] ?? 0);
}, $chartVisite));

$pesoJson = json_encode(array_map(function ($v) {
    return isset($v['fisica']['peso']) ? (float) $v['fisica']['peso'] : null;
}, $chartVisite));

$moodVisite = $visite;
usort($moodVisite, function ($a, $b) {
    if ($a['data_analisi'] === $b['data_analisi']) {
        return ($a['visita_id'] ?? 0) <=> ($b['visita_id'] ?? 0);
    }
    return strcmp($a['data_analisi'], $b['data_analisi']);
});

$moodLabelsJson = json_encode(array_map(function ($v) {
    return date('d/m/y', strtotime($v['data_analisi'])) . ' · #' . (int) ($v['visita_id'] ?? 0);
}, $moodVisite));

$stressJson = json_encode(array_map(function ($v) {
    return isset($v['anamnesi_snapshot']['psico_fisico']['livello_stress'])
        ? (float) $v['anamnesi_snapshot']['psico_fisico']['livello_stress']
        : null;
}, $moodVisite));

$concentrazioneJson = json_encode(array_map(function ($v) {
    return isset($v['anamnesi_snapshot']['psico_fisico']['concentrazione'])
        ? (float) $v['anamnesi_snapshot']['psico_fisico']['concentrazione']
        : null;
}, $moodVisite));

$ansiaJson = json_encode(array_map(function ($v) {
    if (!isset($v['anamnesi_snapshot']['psico_fisico']['ansia'])) {
        return null;
    }
    return ((int) $v['anamnesi_snapshot']['psico_fisico']['ansia']) === 1 ? 10 : 0;
}, $moodVisite));

$difficoltaSonnoJson = json_encode(array_map(function ($v) {
    if (!isset($v['anamnesi_snapshot']['sonno']['difficolta_addormentarsi'])) {
        return null;
    }
    return ((int) $v['anamnesi_snapshot']['sonno']['difficolta_addormentarsi']) === 1 ? 10 : 0;
}, $moodVisite));

$qualitaSonnoJson = json_encode(array_map(function ($v) {
    $map = [
        'Pessima' => 2,
        'Scarsa' => 4,
        'Discreta' => 6,
        'Buona' => 8,
        'Ottima' => 10,
    ];
    $q = trim((string) ($v['anamnesi_snapshot']['sonno']['qualita_percepita'] ?? ''));
    if ($q === '' || !isset($map[$q])) {
        return null;
    }
    return $map[$q];
}, $moodVisite));

$hasPesoData = false;
$hasStressData = false;
$hasConcentrazioneData = false;
$hasMoodExtraData = false;
foreach ($chartVisite as $v) {
    if (!$hasPesoData && isset($v['fisica']['peso']) && $v['fisica']['peso'] !== null && $v['fisica']['peso'] !== '') {
        $hasPesoData = true;
    }
}

foreach ($moodVisite as $v) {
    $p = $v['anamnesi_snapshot']['psico_fisico'] ?? [];
    $s = $v['anamnesi_snapshot']['sonno'] ?? [];
    if (!$hasStressData && isset($p['livello_stress']) && $p['livello_stress'] !== null && $p['livello_stress'] !== '') {
        $hasStressData = true;
    }
    if (!$hasConcentrazioneData && isset($p['concentrazione']) && $p['concentrazione'] !== null && $p['concentrazione'] !== '') {
        $hasConcentrazioneData = true;
    }
    if (
        !$hasMoodExtraData && (
            (isset($p['ansia']) && $p['ansia'] !== null && $p['ansia'] !== '') ||
            (isset($s['difficolta_addormentarsi']) && $s['difficolta_addormentarsi'] !== null && $s['difficolta_addormentarsi'] !== '') ||
            (isset($s['qualita_percepita']) && trim((string) $s['qualita_percepita']) !== '')
        )
    ) {
        $hasMoodExtraData = true;
    }
}
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
        <?php if (empty($visite)): ?>
            <a href="anamnesis.php?action=create&clientId=<?php echo $client['cliente_id']; ?>" class="btn btn-primary">
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
    </div>
</div>

<?php if (!empty($visitsMessage)): ?>
<div class="alert">
    <?php echo htmlspecialchars($visitsMessage); ?>
</div>
<?php endif; ?>

<?php if (count($chartVisite) >= 2 || count($moodVisite) >= 2): ?>
<!-- Grafici -->
<div class="grid-2-col-gap-20 mb-2">
    <div class="card">
        <div class="chart-title">Andamento Peso (kg)</div>
        <div class="chart-container">
            <?php if ($hasPesoData): ?>
                <canvas id="pesoChart"></canvas>
            <?php else: ?>
                <div class="chart-empty">Nessun dato peso disponibile nelle visite registrate.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="chart-title">Stress e Concentrazione (1-10)</div>
        <div class="chart-container">
            <?php if ($hasStressData || $hasConcentrazioneData): ?>
                <canvas id="stressConcentrazioneChart"></canvas>
            <?php else: ?>
                <div class="chart-empty">Nessun dato su stress/concentrazione disponibile nelle visite.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="chart-title">Indicatori Umore (0-10)</div>
    <div class="chart-container">
        <?php if ($hasMoodExtraData): ?>
            <canvas id="moodIndicatorsChart"></canvas>
        <?php else: ?>
            <div class="chart-empty">Nessun altro indicatore utile all'umore disponibile (ansia/sonno).</div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Tabella visite -->
<div class="card card-no-pad">
    <div class="card-toolbar">
        <div class="card-title inline-card-title"><?php echo count($visite); ?> visite registrate</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Peso</th>
                    <th>Altezza</th>
                    <th>Note</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($visite)): ?>
                <?php foreach ($visite as $v):
                    $f = $v['fisica'] ?? [];
                    $tipoVisita = $v['tipo_visita'] ?? 'anamnestica';
                ?>
                <tr>
                    <td><strong><?php echo date('d/m/Y', strtotime($v['data_analisi'])); ?></strong></td>
                    <td>
                        <?php if ($tipoVisita === 'fisica'): ?>
                            <span class="badge badge-green">Fisica</span>
                        <?php else: ?>
                            <span class="badge badge-purple">Anamnestica</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo isset($f['peso']) ? $f['peso'].' kg' : '—'; ?></td>
                    <td><?php echo isset($f['altezza']) ? $f['altezza'].' cm' : '—'; ?></td>
                    <td class="text-muted text-sm">
                        <?php echo htmlspecialchars(substr($v['note'] ?? '', 0, 60)); ?>
                    </td>
                    <td>
                        <div class="actions-right">
                            <a href="visits.php?action=show&id=<?php echo $v['visita_id']; ?>" class="btn btn-ghost btn-sm">Dettagli</a>
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="deleteVisitWithPassword(<?php echo (int) $v['visita_id']; ?>, <?php echo (int) $client['cliente_id']; ?>)">
                                Elimina
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="td-empty-center">Nessuna visita registrata.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (count($chartVisite) >= 2 || count($moodVisite) >= 2): ?>
<script>
const labels = <?php echo $labelsJson; ?>;
const pesoData   = <?php echo $pesoJson; ?>;

const moodLabels = <?php echo $moodLabelsJson; ?>;
const stressData = <?php echo $stressJson; ?>;
const concentrazioneData = <?php echo $concentrazioneJson; ?>;
const ansiaData = <?php echo $ansiaJson; ?>;
const difficoltaSonnoData = <?php echo $difficoltaSonnoJson; ?>;
const qualitaSonnoData = <?php echo $qualitaSonnoJson; ?>;

function getAxisBounds(values, padding = 2) {
    const numeric = values.filter(v => typeof v === 'number' && !Number.isNaN(v));
    if (!numeric.length) return {};
    const min = Math.min(...numeric);
    const max = Math.max(...numeric);
    if (min === max) {
        return {
            suggestedMin: Math.max(0, min - padding),
            suggestedMax: max + padding
        };
    }
    return {
        suggestedMin: Math.max(0, min - padding),
        suggestedMax: max + padding
    };
}

const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'nearest',
        intersect: false
    },
    plugins: { legend: { display: false } },
    scales: {
        x: {
            grid: { display: false },
            ticks: {
                font: { size: 11 },
                autoSkip: true,
                maxRotation: 0
            }
        },
        y: {
            beginAtZero: false,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { font: { size: 11 } }
        }
    },
    elements: {
        point: { radius: 3, hoverRadius: 6 },
        line: { spanGaps: true }
    }
};

const pesoCanvas = document.getElementById('pesoChart');
if (pesoCanvas) {
    new Chart(pesoCanvas, {
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
        options: {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    ...getAxisBounds(pesoData, 2)
                }
            }
        }
    });
}

const stressConcentrazioneCanvas = document.getElementById('stressConcentrazioneChart');
if (stressConcentrazioneCanvas) {
    new Chart(stressConcentrazioneCanvas, {
        type: 'line',
        data: {
            labels: moodLabels,
            datasets: [
                {
                    label: 'Stress',
                    data: stressData,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239,68,68,0.10)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 2
                },
                {
                    label: 'Concentrazione',
                    data: concentrazioneData,
                    borderColor: '#0EA5A4',
                    backgroundColor: 'rgba(14,165,164,0.10)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 2
                }
            ]
        },
        options: {
            ...commonOptions,
            plugins: { legend: { display: true } },
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    min: 0,
                    max: 10,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

const moodIndicatorsCanvas = document.getElementById('moodIndicatorsChart');
if (moodIndicatorsCanvas) {
    new Chart(moodIndicatorsCanvas, {
        type: 'line',
        data: {
            labels: moodLabels,
            datasets: [
                {
                    label: 'Ansia (0/10)',
                    data: ansiaData,
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245,158,11,0.10)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 2
                },
                {
                    label: 'Difficoltà ad addormentarsi (0/10)',
                    data: difficoltaSonnoData,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139,92,246,0.10)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 2
                },
                {
                    label: 'Qualità sonno (2-10)',
                    data: qualitaSonnoData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.10)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 2
                }
            ]
        },
        options: {
            ...commonOptions,
            plugins: { legend: { display: true } },
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    min: 0,
                    max: 10,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}
</script>
<?php endif; ?>

<form id="deleteVisitForm" method="POST" action="visits.php?action=delete" class="hidden">
    <input type="hidden" name="visita_id" id="delete_visita_id" value="">
    <input type="hidden" name="cliente_id" id="delete_cliente_id" value="">
    <input type="hidden" name="confirm_password" id="delete_confirm_password" value="">
</form>

<script>
function deleteVisitWithPassword(visitaId, clienteId) {
    document.getElementById('delete_visita_id').value = String(visitaId);
    document.getElementById('delete_cliente_id').value = String(clienteId);

    DeleteModal.confirmDeleteForm('#deleteVisitForm', {
        title: 'Elimina visita',
        message: 'Confermi l\'eliminazione definitiva della visita?',
        requirePassword: true,
        passwordLabel: 'Inserisci la password per confermare',
        passwordFieldName: 'confirm_password'
    });
}
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

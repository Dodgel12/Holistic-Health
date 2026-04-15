<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="top-bar">
    <div>
        <div class="breadcrumb">Dashboard &rsaquo; <span>Pazienti</span></div>
        <h1>Pazienti</h1>
    </div>
    <div class="top-bar-actions">
        <a href="clients.php?action=new" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuovo Paziente
        </a>
    </div>
</div>

<div class="card card-no-pad">
    <!-- Table header toolbar -->
    <div class="card-toolbar">
        <div class="search-bar">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="clientSearch" placeholder="Cerca paziente per nome o ID…" aria-label="Cerca paziente">
        </div>
        <div class="flex header-meta-right">
            <span class="search-hint">Shortcut: /</span>
            <span class="text-muted text-sm" id="clientCount"><?php echo count($clients); ?> pazienti</span>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ultima Visita</th>
                    <th class="table-th-right">Azioni</th>
                </tr>
            </thead>
            <tbody id="clientsTable">
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $c): ?>
                <tr data-name="<?php echo strtolower($c['nome'].' '.$c['cognome']); ?>"
                    data-id="<?php echo $c['cliente_id']; ?>">
                    <td>
                        <div class="client-info">
                            <div class="avatar">
                                <?php echo strtoupper(substr($c['nome'],0,1).substr($c['cognome'],0,1)); ?>
                            </div>
                            <div>
                                <div class="name"><?php echo htmlspecialchars($c['nome'].' '.$c['cognome']); ?></div>
                                <div class="sub">#<?php printf('%04d', $c['cliente_id']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($c['ultima_visita'])): ?>
                            <span class="badge badge-green">
                                <?php echo date('d M Y', strtotime($c['ultima_visita'])); ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted text-sm">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="table-header-actions">
                            <a href="clients.php?action=edit&id=<?php echo $c['cliente_id']; ?>"
                                         class="btn btn-primary btn-sm">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Modifica
                            </a>
                            <a href="visits.php?action=history&clientId=<?php echo $c['cliente_id']; ?>"
                                         class="btn btn-primary btn-sm">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Visite
                            </a>
                            <a href="therapy.php?clientId=<?php echo $c['cliente_id']; ?>"
                                         class="btn btn-primary btn-sm">
                                Piano
                            </a>
                            <a href="clients.php?action=show&id=<?php echo $c['cliente_id']; ?>"
                               class="btn btn-primary btn-sm">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                Cartella
                            </a>
                                <button class="btn btn-danger btn-sm btn-icon"
                                    onclick="DeleteModal.open(<?php echo $c['cliente_id']; ?>, '<?php echo addslashes($c['nome'].' '.$c['cognome']); ?>')"
                                    title="Elimina paziente">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="empty-state">Nessun paziente trovato.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModalOverlay">
    <div class="modal">
        <div class="modal-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3>Elimina paziente</h3>
        <p>Stai per eliminare <strong id="deleteClientName"></strong>.<br>
           Questa azione è irreversibile. Inserisci la tua password per confermare.</p>

        <form action="clients.php?action=delete" method="POST" id="deleteForm">
            <input type="hidden" name="cliente_id" id="deleteClientId">
            <div class="form-group">
                <label for="deletePassword">Password di conferma</label>
                <input type="password" id="deletePassword" name="confirm_password"
                       placeholder="La tua password">
                <span class="form-error" id="deletePasswordError"></span>
            </div>
        </form>

        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="DeleteModal.close()">Annulla</button>
            <button class="btn btn-danger" onclick="DeleteModal.confirm()">Elimina definitivamente</button>
        </div>
    </div>
</div>

<script>
// Ricerca in tempo reale.
const searchInput = document.getElementById('clientSearch');
const rows        = document.querySelectorAll('#clientsTable tr[data-name]');
const countEl     = document.getElementById('clientCount');

searchInput.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    let visible = 0;
    rows.forEach(row => {
        const match = row.dataset.name.includes(q) ||
                      ('#' + row.dataset.id.padStart(4,'0')).includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    countEl.textContent = visible + ' pazienti';
});

document.addEventListener('keydown', function(e) {
    if (e.key === '/' && document.activeElement !== searchInput) {
        e.preventDefault();
        searchInput.focus();
    }
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Stats Row -->
<div class="top-bar">
    <div>
        <div class="breadcrumb">Dashboard</div>
        <h1>Benvenuto/a, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <img class="dashboard-icon-img" src="../assets/images/patient.png" alt="">
        </div>
        <div class="stat-label">Pazienti Totali</div>
        <div class="stat-value"><?php echo $totalClienti; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <img class="dashboard-icon-img" src="../assets/images/appointments.png" alt="">
        </div>
        <div class="stat-label">Appuntamenti Oggi</div>
        <div class="stat-value"><?php echo $appuntamentiOggi; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <img class="dashboard-icon-img" src="../assets/images/dashboard.png" alt="">
        </div>
        <div class="stat-label">Visite questo mese</div>
        <div class="stat-value"><?php echo $visiteMese; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <img class="dashboard-icon-img" src="../assets/images/appointments.png" alt="">
        </div>
        <div class="stat-label">App. questo mese</div>
        <div class="stat-value"><?php echo count($appuntamentiMese); ?></div>
    </div>
</div>

<!-- Main grid: quick actions + calendar -->
<div class="page-grid">

    <!-- Quick Actions & Upcoming -->
    <div>
        <div class="card">
            <div class="card-title">Accesso Rapido</div>
            <div class="quick-actions">
                <a href="clients.php" class="btn btn-primary">
                    <img class="btn-icon-img" src="../assets/images/patient.png" alt="">
                    Gestione Pazienti
                </a>
                <a href="appointments.php" class="btn btn-primary">
                    <img class="btn-icon-img" src="../assets/images/appointments.png" alt="">
                    Appuntamenti
                </a>
                <a href="clients.php?action=new" class="btn btn-primary">
                    <img class="btn-icon-img" src="../assets/images/patient.png" alt="">
                    Nuovo Paziente
                </a>
                <a href="settings.php" class="btn btn-primary">
                    <img class="btn-icon-img" src="../assets/images/settings.png" alt="">
                    Impostazioni
                </a>
            </div>
        </div>

        <!-- Prossimi appuntamenti -->
        <div class="card">
            <div class="card-title">Prossimi Appuntamenti</div>
            <?php if (!empty($appuntamentiProssimi)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Orario</th>
                            <th>Paziente</th>
                            <th>Tipo</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($appuntamentiProssimi as $app): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($app['data'])); ?></td>
                            <td><?php echo substr($app['ora_inizio'],0,5) . ' - ' . substr($app['ora_fine'],0,5); ?></td>
                            <td>
                                <div class="client-info">
                                    <div class="avatar compact-avatar">
                                        <?php echo strtoupper(substr($app['nome'],0,1).substr($app['cognome'],0,1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($app['nome'].' '.$app['cognome']); ?>
                                </div>
                            </td>
                            <td><span class="badge badge-purple"><?php echo htmlspecialchars($app['tipo'] ?? 'Visita'); ?></span></td>
                            <td>
                                <div class="table-header-actions">
                                    <a href="clients.php?action=show&id=<?php echo (int) $app['cliente_id']; ?>" class="btn btn-primary btn-sm">Cartella</a>
                                    <a href="appointments.php?action=delete&id=<?php echo (int) $app['appuntamento_id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="DeleteModal.confirmDeleteLink(this.href, { title: 'Elimina appuntamento', message: 'Eliminare questo appuntamento?' }); return false;">Elimina</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="empty-state">Nessun appuntamento imminente.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Calendar Widget -->
    <div class="calendar-widget">
        <div class="cal-header">
            <h3 id="calMonthYear"></h3>
            <div class="cal-nav">
                <button id="calPrev" title="Mese precedente">&#8249;</button>
                <button id="calNext" title="Mese successivo">&#8250;</button>
            </div>
        </div>

        <div class="cal-grid" id="calGrid"></div>
    </div>

</div>

<script>
// Giorni con appuntamenti passati dal controller.
const eventDays = <?php echo json_encode(array_values($appuntamentiMese)); ?>;

const Cal = {
    current: new Date(),

    render() {
        const year  = this.current.getFullYear();
        const month = this.current.getMonth();
        const today = new Date();

        // Titolo mese/anno.
        document.getElementById('calMonthYear').textContent =
            this.current.toLocaleDateString('it-IT', { month: 'long', year: 'numeric' })
            .replace(/^./, s => s.toUpperCase());

        const grid = document.getElementById('calGrid');
        grid.innerHTML = '';

        // Header dei giorni della settimana.
        ['Lun','Mar','Mer','Gio','Ven','Sab','Dom'].forEach(d => {
            const el = document.createElement('div');
            el.className = 'cal-day-header';
            el.textContent = d;
            grid.appendChild(el);
        });

        // Primo giorno del mese: converte da domenica-first a lunedi-first.
        let firstDay = new Date(year, month, 1).getDay();
        firstDay = (firstDay === 0) ? 6 : firstDay - 1;

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevDays    = new Date(year, month, 0).getDate();

        // Celle del mese precedente per allineare la griglia.
        for (let i = firstDay - 1; i >= 0; i--) {
            const el = document.createElement('div');
            el.className = 'cal-day other-month';
            el.textContent = prevDays - i;
            grid.appendChild(el);
        }

        // Celle del mese corrente.
        for (let d = 1; d <= daysInMonth; d++) {
            const el = document.createElement('div');
            el.className = 'cal-day';

            const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            if (eventDays.includes(dateStr)) el.classList.add('has-event');

            if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                el.classList.add('today');
            }
            el.textContent = d;
            grid.appendChild(el);
        }

        // Celle del mese successivo per chiudere la griglia.
        const totalCells = firstDay + daysInMonth;
        const remainder  = (7 - (totalCells % 7)) % 7;
        for (let d = 1; d <= remainder; d++) {
            const el = document.createElement('div');
            el.className = 'cal-day other-month';
            el.textContent = d;
            grid.appendChild(el);
        }
    },

    prev() { this.current.setMonth(this.current.getMonth() - 1); this.render(); },
    next() { this.current.setMonth(this.current.getMonth() + 1); this.render(); }
};

document.getElementById('calPrev').addEventListener('click', () => Cal.prev());
document.getElementById('calNext').addEventListener('click', () => Cal.next());
Cal.render();
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

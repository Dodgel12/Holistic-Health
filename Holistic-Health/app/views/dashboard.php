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
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-label">Pazienti Totali</div>
        <div class="stat-value"><?php echo $totalClienti; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="stat-label">Appuntamenti Oggi</div>
        <div class="stat-value"><?php echo $appuntamentiOggi; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div class="stat-label">Visite questo mese</div>
        <div class="stat-value"><?php echo $visiteMese; ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-label">App. questo mese</div>
        <div class="stat-value"><?php echo count($appuntamentiMese); ?></div>
    </div>
</div>

<!-- Main grid: quick actions + calendar -->
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">

    <!-- Quick Actions & Upcoming -->
    <div>
        <div class="card">
            <div class="card-title">Accesso Rapido</div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="clients.php" class="btn btn-primary">
                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Gestione Pazienti
                </a>
                <a href="appointments.php" class="btn btn-outline">
                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Appuntamenti
                </a>
                <a href="clients.php?action=new" class="btn btn-ghost">
                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuovo Paziente
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
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($appuntamentiProssimi as $app): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($app['data'])); ?></td>
                            <td><?php echo substr($app['ora_inizio'],0,5) . ' - ' . substr($app['ora_fine'],0,5); ?></td>
                            <td>
                                <div class="client-info">
                                    <div class="avatar" style="width:30px;height:30px;font-size:11px;">
                                        <?php echo strtoupper(substr($app['nome'],0,1).substr($app['cognome'],0,1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($app['nome'].' '.$app['cognome']); ?>
                                </div>
                            </td>
                            <td><span class="badge badge-purple"><?php echo htmlspecialchars($app['tipo'] ?? 'Visita'); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-muted" style="text-align:center;padding:20px;">Nessun appuntamento imminente.</p>
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
// Giorni con appuntamenti (passati dal controller)
const eventDays = <?php echo json_encode(array_values($appuntamentiMese)); ?>;

const Cal = {
    current: new Date(),

    render() {
        const year  = this.current.getFullYear();
        const month = this.current.getMonth();
        const today = new Date();

        // Intestazione
        document.getElementById('calMonthYear').textContent =
            this.current.toLocaleDateString('it-IT', { month: 'long', year: 'numeric' })
            .replace(/^./, s => s.toUpperCase());

        const grid = document.getElementById('calGrid');
        grid.innerHTML = '';

        // Nomi giorni
        ['Lun','Mar','Mer','Gio','Ven','Sab','Dom'].forEach(d => {
            const el = document.createElement('div');
            el.className = 'cal-day-header';
            el.textContent = d;
            grid.appendChild(el);
        });

        // Primo giorno del mese (adatta: 0=Dom → trasforma in 1=Lun)
        let firstDay = new Date(year, month, 1).getDay();
        firstDay = (firstDay === 0) ? 6 : firstDay - 1;

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevDays    = new Date(year, month, 0).getDate();

        // Celle mese precedente
        for (let i = firstDay - 1; i >= 0; i--) {
            const el = document.createElement('div');
            el.className = 'cal-day other-month';
            el.textContent = prevDays - i;
            grid.appendChild(el);
        }

        // Celle mese corrente
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

        // Celle mese successivo
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

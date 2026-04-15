<?php
/**
 * Header + sidebar comune.
 * Va incluso nelle viste protette (tranne login).
 */
// Capisce quale voce del menu evidenziare.
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holistic Health - Gestionale</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Carica subito il tema salvato per evitare sfarfallii.
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>
<script>
    (function() {
        const collapsed = localStorage.getItem('sidebar_collapsed') === '1';
        if (collapsed) {
            document.body.classList.add('sidebar-collapsed');
        }
    })();
</script>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">H</div>
        <span class="logo-text">Holistic Health</span>
        <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Apri o chiudi sidebar">⟨⟩</button>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php"
           class="nav-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
            <img class="nav-icon-img" src="../assets/images/dashboard.png" alt="">
            <span>Dashboard</span>
        </a>

        <a href="clients.php"
              class="nav-item <?php echo in_array($currentPage, ['clients.php','client.php','therapy.php']) ? 'active' : ''; ?>">
            <img class="nav-icon-img" src="../assets/images/patient.png" alt="">
            <span>Pazienti</span>
        </a>

        <a href="appointments.php"
           class="nav-item <?php echo $currentPage === 'appointments.php' ? 'active' : ''; ?>">
            <img class="nav-icon-img" src="../assets/images/appointments.png" alt="">
            <span>Appuntamenti</span>
        </a>

        <a href="settings.php"
           class="nav-item <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
            <img class="nav-icon-img" src="../assets/images/settings.png" alt="">
            <span>Impostazioni</span>
        </a>


        <div class="nav-separator"></div>
    </nav>

    <div class="sidebar-footer">
        <button id="themeToggle" class="nav-item nav-item-button" type="button" aria-label="Cambia tema">
            <img class="theme-icon" src="../assets/images/theme.png" alt="">
            <span>Tema</span>
        </button>
        <a href="logout.php" class="nav-item">
            <img class="nav-icon-img" src="../assets/images/logout.png" alt="">
            <span>Logout</span>
        </a>
    </div>
</aside>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const sidebarToggle = document.getElementById('sidebarToggle');

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });

    function refreshSidebarToggleIcon() {
        sidebarToggle.textContent = document.body.classList.contains('sidebar-collapsed') ? '⟩⟩' : '⟨⟨';
    }

    sidebarToggle.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        refreshSidebarToggleIcon();
    });

    refreshSidebarToggleIcon();
</script>
<script src="../assets/js/delete-modal.js"></script>

<!-- MAIN CONTENT -->
<main class="main-content">

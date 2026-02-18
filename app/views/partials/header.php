/**
 * Header comune dell'applicazione.
 * Contiene intestazione HTML, menu di navigazione
 * e collegamenti a fogli di stile e script.
 */
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Holistic Health'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: #f4f4f9; color: #333; }
        .navbar { background-color: #2c3e50; padding: 1rem; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 1rem; }
        .navbar .brand { font-size: 1.2rem; font-weight: bold; }
        .container { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1rem; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: #3498db; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand">Holistic Health</div>
        <div class="menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="clients.php">Clienti</a>
                <a href="appointments.php">Appuntamenti</a>
                <a href="logout.php" style="color: #e74c3c;">Logout</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">

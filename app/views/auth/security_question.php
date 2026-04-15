<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domanda di Sicurezza - Holistic Health</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f7f7f7; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; padding:16px; color:#111; }
        .card { width:100%; max-width:500px; background:#fff; border-radius:14px; box-shadow:0 16px 40px rgba(0,0,0,0.12); padding:24px; border:1px solid #e6e6e6; }
        h1 { margin:0 0 8px; color:#111; }
        p { margin:0 0 14px; color:#333; }
        .form-group { margin-bottom:14px; }
        label { display:block; margin-bottom:6px; font-weight:600; }
        input { width:100%; padding:11px; border:1px solid #cfcfcf; border-radius:10px; background:#fff; color:#111; }
        input:focus { outline:none; border-color:#111; box-shadow:0 0 0 2px rgba(0,0,0,0.08); }
        .btn { width:100%; border:none; border-radius:10px; background:#111; color:#fff; padding:11px; font-weight:700; cursor:pointer; }
        .btn:hover { background:#000; }
        .msg { padding:10px; border-radius:10px; margin-bottom:12px; font-size:14px; }
        .error { background:#fbe9e9; color:#8d1d1d; border:1px solid #e6b9b9; }
    </style>
</head>
<body>
<div class="card">
    <h1>Configura domanda di sicurezza</h1>
    <p>Al primo accesso devi impostare una domanda personale e la sua risposta.</p>

    <?php if (!empty($error)): ?>
        <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="security_question.php">
        <div class="form-group">
            <label for="security_question">Domanda personale</label>
            <input id="security_question" name="security_question" placeholder="Es. Nome del mio primo animale domestico?" required>
        </div>
        <div class="form-group">
            <label for="security_answer">Risposta personale</label>
            <input id="security_answer" name="security_answer" placeholder="Inserisci la risposta" required>
        </div>
        <button class="btn" type="submit">Salva e continua</button>
    </form>
</div>
</body>
</html>

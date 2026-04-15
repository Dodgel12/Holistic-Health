<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Holistic Health</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f6fb; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; padding:16px; }
        .card { width:100%; max-width:460px; background:#fff; border-radius:14px; box-shadow:0 16px 40px rgba(0,0,0,0.12); padding:24px; }
        h1 { margin:0 0 8px; color:#162233; }
        p { margin:0 0 14px; color:#5a6b80; }
        .form-group { margin-bottom:12px; }
        label { display:block; margin-bottom:6px; font-weight:600; }
        input { width:100%; padding:11px; border:1px solid #ced6e4; border-radius:10px; }
        .btn { width:100%; border:none; border-radius:10px; background:#2f5ee7; color:#fff; padding:11px; font-weight:700; cursor:pointer; }
        .msg { padding:10px; border-radius:10px; margin-bottom:12px; font-size:14px; }
        .error { background:#ffe8ea; color:#a52536; border:1px solid #ffc9cf; }
        .ok { background:#e8f5e9; color:#1e6a34; border:1px solid #bce3c7; }
        .link-row { margin-top:12px; text-align:center; }
        a { color:#2f5ee7; text-decoration:none; }
    </style>
</head>
<body>
<div class="card">
    <h1>Imposta nuova password</h1>
    <p>Inserisci una nuova password sicura.</p>

    <?php if (!empty($error)): ?>
        <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="msg ok"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ($_GET['token'] ?? '')); ?>">
        <div class="form-group">
            <label for="new_password">Nuova password</label>
            <input type="password" id="new_password" name="new_password" minlength="8" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Conferma password</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
        </div>
        <button class="btn" type="submit">Aggiorna password</button>
    </form>

    <div class="link-row">
        <a href="login.php">Torna al login</a>
    </div>
</div>
</body>
</html>

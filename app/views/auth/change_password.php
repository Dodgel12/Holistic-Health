<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio Password - Holistic Health</title>
    <style>
        :root {
            --bg-1: #f7f9fc;
            --bg-2: #eaf1ff;
            --card: #ffffff;
            --text: #182433;
            --muted: #5b6b7f;
            --primary: #2f5ee7;
            --primary-dark: #2347ae;
            --danger-bg: #ffe8ea;
            --danger-text: #a52536;
            --radius: 16px;
            --shadow: 0 22px 52px rgba(24, 36, 51, 0.14);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 10% 20%, var(--bg-2), var(--bg-1) 56%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 16px;
        }

        .panel {
            width: 100%;
            max-width: 460px;
            background: var(--card);
            border: 1px solid rgba(47, 94, 231, 0.12);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
        }

        h1 {
            margin: 0 0 8px;
            color: var(--text);
            font-size: 28px;
        }

        p {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd5e2;
            border-radius: 10px;
            font-size: 15px;
            background: #fbfdff;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 94, 231, 0.16);
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: var(--primary);
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .error-message {
            background: var(--danger-bg);
            border: 1px solid #ffc9cf;
            color: var(--danger-text);
            border-radius: 10px;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
<div class="panel">
    <h1>Cambio password richiesto</h1>
    <p>Per motivi di sicurezza devi aggiornare la password prima di continuare.</p>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="change_password.php?action=update">
        <div class="form-group">
            <label for="current_password">Password attuale</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nuova password (minimo 8 caratteri)</label>
            <input type="password" id="new_password" name="new_password" minlength="8" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Conferma nuova password</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
        </div>

        <button class="btn" type="submit">Aggiorna password</button>
    </form>
</div>
</body>
</html>

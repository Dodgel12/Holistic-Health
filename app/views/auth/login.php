<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Holistic Health</title>
    <style>
        :root {
            --bg-1: #f2f2f2;
            --bg-2: #ffffff;
            --card: #ffffff;
            --text: #111111;
            --muted: #444444;
            --primary: #111111;
            --primary-dark: #000000;
            --danger-bg: #f7eaea;
            --danger-text: #8d1d1d;
            --radius: 16px;
            --shadow: 0 18px 42px rgba(0, 0, 0, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, var(--bg-1), var(--bg-2));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 16px;
        }

        .login-container {
            background-color: var(--card);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 420px;
            border: 1px solid #d8d8d8;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-badge {
            display: inline-block;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #111;
            background: #efefef;
            padding: 6px 10px;
            border-radius: 999px;
            margin-bottom: 12px;
            font-weight: 700;
            border: 1px solid #d9d9d9;
        }

        .login-header h1 {
            color: var(--text);
            margin: 0 0 8px;
            font-size: 30px;
            line-height: 1.15;
            letter-spacing: -0.01em;
        }

        .login-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 0.82rem 0.9rem;
            border: 1px solid #c7c7c7;
            border-radius: 10px;
            font-size: 1rem;
            background-color: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.12);
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .error-message {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 1px solid #e5baba;
            text-align: center;
            font-size: 14px;
        }

        .center-text {
            text-align: center;
        }

        .mt-12 {
            margin-top: 12px;
        }

        .inline-link-dark {
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <span class="login-badge">Area Professionista</span>
        <h1>Accedi al gestionale</h1>
        <p>Inserisci le tue credenziali per entrare nella dashboard.</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php
endif; ?>

    <form action="login.php?action=login" method="POST">
        <div class="form-group">
            <label for="username">Nome Utente</label>
            <input type="text" id="username" name="username" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Accedi</button>
        <div class="center-text mt-12">
            <a href="forgot_password.php" class="inline-link-dark">Password dimenticata?</a>
        </div>
    </form>
</div>

</body>
</html>

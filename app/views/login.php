<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Terranova</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a0a5e 0%, #2D1570 50%, #3D1E8F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
            color: rgba(255,255,255,0.9);
        }
        .login-logo .icon {
            font-size: 36px;
            display: block;
            margin-bottom: 8px;
        }
        .login-logo h1 {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .login-logo p {
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            margin-top: 4px;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-card h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 24px;
            text-align: center;
        }

        .error-msg {
            background: #FEE2E2;
            color: #991B1B;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #1A1A2E;
            background: #F9FAFB;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus {
            border-color: #7C4DFF;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(124,77,255,0.12);
        }
        .form-group input.error {
            border-color: #EF4444;
        }
        .field-error {
            font-size: 12px;
            color: #EF4444;
            margin-top: 4px;
            display: none;
        }
        .field-error.show { display: block; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3D1E8F, #7C4DFF);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(124,77,255,0.35);
        }
        .btn-login:active { transform: none; }

        .forgot-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #6B7280;
        }
        .forgot-link a {
            color: #7C4DFF;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-logo">
            <span class="icon">🌿</span>
            <h1>Terranova</h1>
            <p>Gestionale Naturopatica</p>
        </div>

        <div class="login-card">
            <h2>Login</h2>

            <?php if (isset($error)): ?>
            <div class="error-msg">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm" novalidate>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           placeholder="Username" autocomplete="username">
                    <span class="field-error" id="usernameError">Inserisci il tuo username.</span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Password" autocomplete="current-password">
                    <span class="field-error" id="passwordError">Inserisci la tua password.</span>
                </div>

                <button type="submit" class="btn-login">Accedi</button>
            </form>

            <p class="forgot-link">
                <a href="#">Password dimenticata?</a>
            </p>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        let valid = true;

        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const uErr = document.getElementById('usernameError');
        const pErr = document.getElementById('passwordError');

        // Reset
        [username, password].forEach(f => f.classList.remove('error'));
        [uErr, pErr].forEach(el => el.classList.remove('show'));

        if (!username.value.trim()) {
            username.classList.add('error');
            uErr.classList.add('show');
            valid = false;
        }
        if (!password.value.trim()) {
            password.classList.add('error');
            pErr.classList.add('show');
            valid = false;
        }
        if (!valid) e.preventDefault();
    });
    </script>
</body>
</html>

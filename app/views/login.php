<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Terranova</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: radial-gradient(circle at top left, var(--sidebar-bg), #0f0535);
            display: flex; align-items: center; justify-content: center;
            padding: 24px; overflow: hidden;
        }
        .login-wrapper {
            width: 100%; max-width: 420px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .login-logo {
            text-align: center; margin-bottom: 40px;
        }
        .login-logo .icon {
            font-size: 48px; margin-bottom: 12px;
            display: inline-block; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        }
        .login-logo h1 { 
            color: #fff; font-size: 32px; font-weight: 800; 
            letter-spacing: -1px; margin: 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .login-logo p { color: rgba(255,255,255,0.5); font-size: 15px; margin-top: 4px; }

        .card { 
            padding: 48px 40px; border: none; 
            box-shadow: 0 20px 80px rgba(0,0,0,0.4); 
            border-radius: 24px;
        }
        .card h2 { 
            text-align: center; margin-bottom: 32px; 
            font-weight: 700; font-size: 24px;
            letter-spacing: -0.5px;
        }
        
        .btn-login {
            margin-top: 12px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 8px 24px rgba(124, 77, 255, 0.3);
            border: none;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(124, 77, 255, 0.4);
        }

        #loginThemeToggle {
            position: fixed; top: 32px; right: 32px;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
            color: #fff; width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
        }
        #loginThemeToggle:hover { background: rgba(255,255,255,0.15); transform: rotate(15deg) scale(1.1); }

        [data-theme='dark'] body { background: var(--bg); }
        [data-theme='dark'] .card { border: 1px solid var(--border); }
        [data-theme='dark'] #loginThemeToggle { background: var(--bg-white); border-color: var(--border); color: var(--text); }

        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: none; } }
    </style>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>
    <button id="loginThemeToggle" title="Cambia tema">
        <svg id="sunIcon" style="display:none;" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <svg id="moonIcon" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>

    <div class="login-wrapper">
        <div class="login-logo">
            <div class="icon">🌿</div>
            <h1>Terranova</h1>
            <p>Salute Olistica & Benessere</p>
        </div>

        <div class="card">
            <h2>Accedi</h2>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm" novalidate>
                <div class="form-group mb-2">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           placeholder="Inserisci username" autocomplete="username">
                    <span class="form-error" id="usernameError">Inserisci il tuo username.</span>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Inserisci password" autocomplete="current-password">
                    <span class="form-error" id="passwordError">Inserisci la tua password.</span>
                </div>

                <button type="submit" class="btn btn-primary btn-login" style="width: 100%; justify-content: center; padding: 12px;">
                    Entra nel Gestionale
                </button>
            </form>

            <div class="forgot-link mt-2" style="text-align: center; font-size: 13px;">
                <a href="#" style="color: var(--text-muted); text-decoration: none;">Password dimenticata?</a>
            </div>
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
        [uErr, pErr].forEach(el => el.classList.remove('visible'));

        if (!username.value.trim()) {
            username.classList.add('error');
            uErr.classList.add('visible');
            valid = false;
        }
        if (!password.value.trim()) {
            password.classList.add('error');
            pErr.classList.add('visible');
            valid = false;
        }
        if (!valid) e.preventDefault();
    });

    // Theme Toggle Logic for Login
    const toggleBtn = document.getElementById('loginThemeToggle');
    const sun = document.getElementById('sunIcon');
    const moon = document.getElementById('moonIcon');

    function updateTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (theme === 'dark') {
            sun.style.display = 'block';
            moon.style.display = 'none';
        } else {
            sun.style.display = 'none';
            moon.style.display = 'block';
        }
    }

    updateTheme(document.documentElement.getAttribute('data-theme') || 'light');

    toggleBtn.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');
        updateTheme(current === 'dark' ? 'light' : 'dark');
    });
    </script>
</body>
</html>

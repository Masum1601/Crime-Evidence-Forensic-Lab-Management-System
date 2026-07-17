<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            try {
                const t = localStorage.getItem('cefl-theme');
                document.documentElement.setAttribute('data-theme',
                    (t === 'dark' || t === 'light') ? t :
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
            } catch(e) { document.documentElement.setAttribute('data-theme','dark'); }
        })();
    </script>
    <title>Register — CEFL System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg:         #080c14;
        --card:       #0f1929;
        --border:     #1a2540;
        --text:       #e8edf5;
        --muted:      --muted; /* placeholder to match variable name fallback */
        --muted:      #5a6a85;
        --accent:     #6366f1;
        --accent2:    #8b5cf6;
        --glow:       rgba(99,102,241,0.3);
        --input-bg:   #0a1020;
        --input-bdr:  #1e2e4a;
    }
    [data-theme="light"] {
        --bg:       #f0f2f8;
        --card:     #ffffff;
        --border:   #e4e7f0;
        --text:     #0f172a;
        --muted:    #64748b;
        --input-bg: #f8fafc;
        --input-bdr:#dde3f0;
    }

    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow-x: hidden;
        transition: background 0.3s;
        padding: 2rem 0;
    }

    /* Animated background blobs */
    .blob {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        animation: drift 12s ease-in-out infinite alternate;
        pointer-events: none;
    }
    .blob-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; animation-delay: 0s; }
    .blob-2 { width: 300px; height: 300px; background: #8b5cf6; bottom: -80px; right: -80px; animation-delay: -4s; }
    .blob-3 { width: 200px; height: 200px; background: #3b82f6; top: 40%; right: 15%; animation-delay: -8s; }
    @keyframes drift { from { transform: translate(0,0) scale(1); } to { transform: translate(30px,20px) scale(1.05); } }

    /* Card */
    .register-wrap {
        position: relative; z-index: 10;
        width: 100%; max-width: 440px;
        padding: 1rem;
    }
    .register-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.25rem 2rem;
        box-shadow: 0 24px 64px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.04);
    }

    /* Logo */
    .logo-ring {
        width: 60px; height: 60px; border-radius: 16px; margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #fff;
        box-shadow: 0 0 30px var(--glow);
    }
    .register-title { font-size: 1.3rem; font-weight: 800; text-align: center; color: var(--text); }
    .register-sub { font-size: 0.78rem; color: var(--muted); text-align: center; margin-top: 3px; margin-bottom: 1.5rem; }

    /* Alert */
    .err-box {
        background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
        border-radius: 10px; padding: 0.65rem 0.9rem;
        color: #f87171; font-size: 0.8rem;
        margin-bottom: 1.2rem;
    }
    .err-box ul { margin: 0; padding-left: 1rem; }

    /* Form */
    .field { margin-bottom: 0.9rem; }
    label {
        display: block; font-size: 0.72rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }
    .input-wrap { position: relative; }
    .input-wrap i {
        position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
        color: var(--muted); font-size: 0.9rem; pointer-events: none;
    }
    input[type=text], input[type=email], input[type=password] {
        width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem;
        background: var(--input-bg);
        border: 1px solid var(--input-bdr);
        border-radius: 10px;
        color: var(--text);
        font-size: 0.875rem;
        font-family: inherit;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
    }
    input::placeholder { color: var(--muted); }

    /* Submit */
    .btn-submit {
        width: 100%; padding: 0.7rem;
        border: none; border-radius: 10px; cursor: pointer;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        color: #fff; font-size: 0.9rem; font-weight: 700;
        font-family: inherit;
        box-shadow: 0 4px 18px var(--glow);
        display: flex; align-items: center; justify-content: center; gap: 0.4rem;
        transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s;
        margin-top: 1.25rem;
    }
    .btn-submit:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 8px 24px var(--glow); }
    .btn-submit:active { transform: translateY(0); }

    /* Theme toggle */
    .theme-toggle {
        position: fixed; top: 1rem; right: 1rem;
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--card); border: 1px solid var(--border);
        color: var(--muted); cursor: pointer; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s; z-index: 20;
    }
    .theme-toggle:hover { color: var(--accent); border-color: var(--accent); }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--muted); text-decoration: none;
        font-size: 0.8rem; font-weight: 600;
        margin-bottom: 0.85rem; transition: color 0.15s;
    }
    .back-link:hover { color: var(--accent); }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
        <i class="bi bi-moon-fill" id="theme-icon"></i>
    </button>

    <div class="register-wrap">
        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>
        <div class="register-card">
            <div class="logo-ring"><i class="bi bi-person-plus-fill"></i></div>
            <div class="register-title">Create an Account</div>
            <div class="register-sub">Register to submit information or request staff access</div>

            @if ($errors->any())
                <div class="err-box">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>Full Name</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="John Doe" required autofocus>
                    </div>
                </div>
                <div class="field">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@cefl.test" required>
                    </div>
                </div>
                <div class="field">
                    <label>Phone (optional)</label>
                    <div class="input-wrap">
                        <i class="bi bi-phone"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" placeholder="••••••••" required minlength="6">
                    </div>
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required minlength="6">
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Create Account
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.25rem; font-size: 0.85rem;">
                <span style="color: var(--muted);">Already have an account?</span>
                <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 600; text-decoration: none;">Login here</a>
            </div>
        </div>
    </div>

<script>
function syncIcon() {
    const dark = document.documentElement.getAttribute('data-theme') === 'dark';
    document.getElementById('theme-icon').className = dark ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
}
function toggleTheme() {
    const dark = document.documentElement.getAttribute('data-theme') === 'dark';
    document.documentElement.setAttribute('data-theme', dark ? 'light' : 'dark');
    localStorage.setItem('cefl-theme', dark ? 'light' : 'dark');
    syncIcon();
}
syncIcon();
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            try {
                const saved = localStorage.getItem('theme');
                const theme = (saved === 'dark' || saved === 'light')
                    ? saved
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <title>CEFL — Crime Evidence & Forensic Lab Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --page-bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --hero-grad-start: #dbeafe;
            --hero-grad-mid: #ede9fe;
            --hero-grad-end: #e2e8f0;
            --hero-badge-bg: rgba(59,130,246,0.12);
            --hero-badge-border: rgba(59,130,246,0.35);
            --hero-badge-text: #1d4ed8;
            --hero-overlay: rgba(99,102,241,0.1);
            --surface-bg: #eef2ff;
            --card-bg: rgba(255,255,255,0.85);
            --card-border: rgba(148,163,184,0.3);
            --nav-bg: rgba(248,250,252,0.85);
            --nav-border: rgba(15,23,42,0.08);
            --footer-bg: #eef2ff;
            --footer-border: rgba(148,163,184,0.25);
            --link-muted: #475569;
        }
        [data-theme="dark"] {
            --page-bg: #0f172a;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --hero-grad-start: #0f172a;
            --hero-grad-mid: #1e1b4b;
            --hero-grad-end: #0f172a;
            --hero-badge-bg: rgba(99,102,241,0.2);
            --hero-badge-border: rgba(99,102,241,0.4);
            --hero-badge-text: #a5b4fc;
            --hero-overlay: rgba(99,102,241,0.15);
            --surface-bg: #0a0f1e;
            --card-bg: rgba(255,255,255,0.04);
            --card-border: rgba(255,255,255,0.08);
            --nav-bg: rgba(15,23,42,0.8);
            --nav-border: rgba(255,255,255,0.06);
            --footer-bg: #0a0f1e;
            --footer-border: rgba(255,255,255,0.06);
            --link-muted: #94a3b8;
        }
        body { margin: 0; font-family: system-ui, sans-serif; background: var(--page-bg); color: var(--text-main); }
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--hero-grad-start) 0%, var(--hero-grad-mid) 50%, var(--hero-grad-end) 100%);
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 2rem;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 50% 50%, var(--hero-overlay) 0%, transparent 70%);
        }
        .hero-content { position: relative; z-index: 1; max-width: 700px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: var(--hero-badge-bg); border: 1px solid var(--hero-badge-border);
            padding: 0.35rem 1rem; border-radius: 20px;
            font-size: 0.78rem; color: var(--hero-badge-text); font-weight: 600; margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900; line-height: 1.1;
            background: linear-gradient(135deg, var(--text-main) 0%, #a5b4fc 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        .hero p { font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.6; }
        .hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary {
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: #fff; border: none; padding: 0.75rem 2rem;
            border-radius: 12px; font-weight: 700; font-size: 0.95rem;
            text-decoration: none; transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 8px 25px rgba(99,102,241,0.5); }
        .btn-hero-secondary {
            background: var(--card-bg); color: var(--text-main);
            border: 1px solid var(--card-border);
            padding: 0.75rem 2rem; border-radius: 12px;
            font-weight: 600; font-size: 0.95rem;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-hero-secondary:hover { opacity: 0.9; color: var(--text-main); }
        .features {
            background: var(--surface-bg); padding: 4rem 1rem;
        }
        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px; padding: 1.75rem;
            text-align: center; transition: all 0.2s;
        }
        .feature-card:hover { background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.3); transform: translateY(-4px); }
        .feature-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            border-radius: 14px; display: flex;
            align-items: center; justify-content: center;
            font-size: 1.4rem; color: #fff; margin: 0 auto 1rem;
        }
        .feature-card h5 { color: var(--text-main); font-weight: 700; margin-bottom: 0.5rem; }
        .feature-card p { color: var(--text-muted); font-size: 0.875rem; margin: 0; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav style="position:fixed;top:0;left:0;right:0;z-index:200;padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center;background:var(--nav-bg);backdrop-filter:blur(12px);border-bottom:1px solid var(--nav-border);">
        <div style="font-weight:800;font-size:1.1rem;color:var(--text-main);">
            <i class="bi bi-shield-lock-fill" style="color:#6366f1;margin-right:0.4rem;"></i>CEFL System
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ auth()->check() ? route('public.submit') : route('register') }}" style="color:#94a3b8;...">Submit a Tip</a>
            <a href="{{ route('login') }}" style="background:linear-gradient(135deg,#6366f1,#3b82f6);color:#fff;text-decoration:none;padding:0.4rem 1rem;border-radius:8px;font-weight:600;font-size:0.875rem;">Staff Login</a>
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero" style="padding-top:5rem;">
        <div class="hero-content">
            <div class="hero-badge"><i class="bi bi-shield-check"></i> Secure Forensic Management Platform</div>
            <h1>Crime Evidence & Forensic Lab Management System</h1>
            <p>A centralized platform for managing criminal investigations, forensic evidence, laboratory testing, and chain-of-custody tracking — built on Oracle SQL with PL/SQL automation.</p>
            <div class="hero-btns">
                <a href="{{ route('public.submit') }}" class="btn-hero-primary">
                    <i class="bi bi-send me-1"></i> Submit Information
                </a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Staff Login
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="features">
        <div class="container">
            <div class="text-center mb-4">
                <h2 style="color:var(--text-main);font-weight:800;">System Features</h2>
                <p style="color:var(--text-muted);">Everything needed to manage evidence, cases, and forensic workflows</p>
            </div>
            <div class="row g-3">
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-folder2-open"></i></div>
                    <h5>Case Management</h5>
                    <p>Create and monitor criminal cases. Assign investigating officers and track status.</p>
                </div></div>
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-box-seam"></i></div>
                    <h5>Evidence Tracking</h5>
                    <p>Register evidence items with barcodes, storage locations, and current status.</p>
                </div></div>
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <h5>Chain of Custody</h5>
                    <p>Every evidence transfer is logged automatically via PL/SQL trigger.</p>
                </div></div>
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-eyedropper"></i></div>
                    <h5>Forensic Testing</h5>
                    <p>Assign analysts to forensic tests and track progress from request to report.</p>
                </div></div>
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-people"></i></div>
                    <h5>Role-Based Access</h5>
                    <p>Admin, Officer, and Analyst roles with different dashboards and permissions.</p>
                </div></div>
                <div class="col-md-4"><div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-inbox"></i></div>
                    <h5>Public Submissions</h5>
                    <p>Citizens can submit tips and complaints directly without needing an account.</p>
                </div></div>
            </div>
        </div>
    </div>

    <div style="background:var(--footer-bg);text-align:center;padding:2rem;color:var(--text-muted);font-size:0.8rem;border-top:1px solid var(--footer-border);">
        CEFL System — Crime Evidence & Forensic Lab Management — Database Lab Project
    </div>
</body>
</html>
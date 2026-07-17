<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            try {
                const saved = localStorage.getItem('cefl-theme');
                const theme = (saved === 'dark' || saved === 'light')
                    ? saved
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    <title>CEFL — Crime Evidence & Forensic Lab Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        /* ═══════════════════════════════════════
           DESIGN TOKENS & SYSTEM VARIABLES
        ═══════════════════════════════════════ */
        :root {
            --bg-color: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --secondary: #8b5cf6;
            --cyan: #06b6d4;
            --nav-bg: rgba(248, 250, 252, 0.8);
            --nav-border: rgba(15, 23, 42, 0.06);
            --card-bg: rgba(255, 255, 255, 0.7);
            --card-border: rgba(148, 163, 184, 0.15);
            --gradient-start: #dbeafe;
            --gradient-mid: #ede9fe;
            --gradient-end: #f1f5f9;
        }

        [data-theme="dark"] {
            --bg-color: #030712;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.3);
            --secondary: #8b5cf6;
            --cyan: #06b6d4;
            --nav-bg: rgba(3, 7, 18, 0.75);
            --nav-border: rgba(255, 255, 255, 0.05);
            --card-bg: rgba(15, 23, 42, 0.6);
            --card-border: rgba(255, 255, 255, 0.06);
            --gradient-start: #080c14;
            --gradient-mid: #0f132a;
            --gradient-end: #030712;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background-color 0.4s, color 0.4s;
        }

        /* ═══════════════════════════════════════
           ANIMATED BACKGROUNDS (DRIFTING BLOBS)
        ═══════════════════════════════════════ */
        .glow-blobs {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.16;
            animation: drift 20s infinite alternate ease-in-out;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: var(--primary);
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 450px;
            height: 450px;
            background: var(--secondary);
            bottom: 10%;
            right: -100px;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: var(--cyan);
            top: 40%;
            left: 20%;
            animation-delay: -10s;
        }

        @keyframes drift {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(60px, 40px) scale(1.1) rotate(180deg); }
            100% { transform: translate(-40px, -20px) scale(0.9) rotate(360deg); }
        }

        /* Ambient grid pattern overlay */
        .ambient-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(var(--card-border) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: radial-gradient(ellipse at center, black, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at center, black, transparent 70%);
            opacity: 0.25;
            z-index: 0;
        }

        /* ═══════════════════════════════════════
           NAVBAR (GLASSMORPHIC)
        ═══════════════════════════════════════ */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--nav-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--nav-border);
            transition: all 0.3s;
        }

        .brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand i {
            color: var(--primary);
            text-shadow: 0 0 10px var(--primary-glow);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .nav-link-item {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-link-item:hover {
            color: var(--text-main);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff !important;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        }

        /* ═══════════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════════ */
        .hero {
            min-height: 100vh;
            padding-top: 8rem;
            padding-bottom: 5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-mid) 50%, var(--gradient-end) 100%);
            transition: background 0.4s;
        }

        .hero-content {
            max-width: 800px;
            text-align: center;
            padding: 0 1.5rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-glow);
            border: 1px solid rgba(99, 102, 241, 0.3);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            animation: pulseGlow 3s infinite alternate;
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.2); border-color: rgba(99, 102, 241, 0.3); }
            100% { box-shadow: 0 0 15px rgba(99, 102, 241, 0.4); border-color: rgba(99, 102, 241, 0.6); }
        }

        .hero-title {
            font-size: clamp(2.25rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--text-main) 30%, var(--secondary) 70%, var(--cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: revealUp 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 650px;
            margin: 0 auto 2.5rem;
            animation: revealUp 1.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: revealUp 1.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes revealUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-action-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 0.8rem 2.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action-primary:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.45);
        }

        .btn-action-secondary {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            padding: 0.8rem 2.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(8px);
        }

        .btn-action-secondary:hover {
            background: var(--card-border);
            color: var(--text-main);
            transform: translateY(-2px);
        }

        /* ═══════════════════════════════════════
           ACTIVITIES SECTION (SYSTEM OPERATION)
        ═══════════════════════════════════════ */
        .activities {
            padding: 6rem 1.5rem;
            position: relative;
            z-index: 1;
            background-color: var(--bg-color);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .section-header h2 {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 0.75rem;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Timeline grid of cards */
        .activity-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .activity-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2.25rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            /* Scroll Animation Initial State */
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s, box-shadow 0.3s;
        }

        .activity-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .activity-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--cyan));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .activity-card.visible:hover {
            transform: translateY(-6px);
            border-color: rgba(99, 102, 241, 0.25);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .activity-card:hover::before {
            opacity: 1;
        }

        .activity-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }

        .activity-card:hover .activity-icon {
            transform: scale(1.1) rotate(5deg);
            color: var(--secondary);
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(99, 102, 241, 0.15));
        }

        .activity-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .activity-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .activity-tech {
            font-size: 0.72rem;
            font-family: monospace;
            background: var(--primary-glow);
            color: var(--primary);
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            align-self: flex-start;
            font-weight: 600;
        }

        /* ═══════════════════════════════════════
           CITIZEN CALL TO ACTION
        ═══════════════════════════════════════ */
        .cta-section {
            padding: 5rem 1.5rem;
            position: relative;
            z-index: 1;
        }

        .cta-box {
            max-width: 1000px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(6, 182, 212, 0.08));
            border: 1px solid var(--card-border);
            border-radius: 30px;
            padding: 4rem 3rem;
            text-align: center;
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
        }

        .cta-box::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(6, 182, 212, 0.1), transparent 50%);
            pointer-events: none;
        }

        .cta-box h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .cta-box p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        /* ═══════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════ */
        footer {
            border-top: 1px solid var(--nav-border);
            padding: 2rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            background-color: var(--gradient-end);
            position: relative;
            z-index: 1;
        }

        /* Theme button in navbar */
        .theme-btn {
            background: transparent;
            border: 1px solid var(--nav-border);
            border-radius: 8px;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .theme-btn:hover {
            color: var(--text-main);
            border-color: var(--text-main);
        }
    </style>
</head>
<body>

    <!-- Drifting BG particles -->
    <div class="glow-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="ambient-grid"></div>

    <!-- Navbar -->
    <nav>
        <div class="brand">
            <i class="bi bi-shield-lock-fill"></i> CEFL System
        </div>
        <div class="nav-links">
            <a href="#activities" class="nav-link-item">System Operations</a>
            <button class="theme-btn" onclick="toggleTheme()" title="Toggle theme">
                <i class="bi bi-moon-fill" id="theme-icon"></i>
            </button>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-login">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Log In</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-shield-check"></i> Advanced Forensic Tracking Network
            </div>
            <h1 class="hero-title">Crime Evidence & Forensic Lab Management</h1>
            <p class="hero-subtitle">
                A centralized, secure digital environment built with Oracle PL/SQL integration. CEFL guarantees custody integrity, manages detailed casework, automates forensic reporting, and indexes equipment usage logs.
            </p>
            <div class="hero-btns">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-action-primary">
                        <i class="bi bi-speedometer2"></i> Enter Portal
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-action-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In to Account
                    </a>
                    <a href="{{ route('register') }}" class="btn-action-secondary">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Operational Activities -->
    <section class="activities" id="activities">
        <div class="section-header">
            <h2>System Activities</h2>
            <p>Our comprehensive framework coordinates forensic investigations, secure handling, and laboratory automation seamlessly.</p>
        </div>

        <div class="activity-grid">
            <!-- Case Registration -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-folder-fill"></i></div>
                    <h3>Case Registration</h3>
                    <p>Establish a solid foundation for investigations by registering cases with unique system-generated identifiers. Capture vital metadata including criminal classifications, incident details, and severity, while assigning lead investigating officers. This centralized log ensures that law enforcement staff can seamlessly coordinate, track, and update status logs from inception to closure.</p>
                </div>
            </div>

            <!-- Evidence Collection -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <h3>Evidence Tracking</h3>
                    <p>Maintain absolute control over all physical and digital evidence. Catalog items with automatically generated barcodes, detailed dimensions, and storage coordinates. The system prevents misplacement by assigning physical vault and container locations, allowing investigators to track evidence status through stages of storage, analysis, transfer, or ultimate disposal.</p>
                </div>
            </div>

            <!-- Chain of Custody -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <h3>Chain of Custody</h3>
                    <p>Securely automate the verification of hands-on transfers through integrated PL/SQL audit logs. The system records every custody handoff instantly, capturing from-user, to-user, and transfer authority details. This immutable registry protects the legal integrity of the evidence, providing a transparent, auditable history that complies with strict judicial standards.</p>
                </div>
            </div>

            <!-- Forensic Testing -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-eyedropper"></i></div>
                    <h3>Forensic Analysis</h3>
                    <p>Submit formal forensic testing requests directly through the evidence pipeline. Assign specialist laboratory analysts to perform precise examinations and log detailed analytical findings. Filing a verified test report automatically updates request statuses, linking scientific breakthroughs directly to active criminal cases.</p>
                </div>
            </div>

            <!-- Court Submissions -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-bank2"></i></div>
                    <h3>Court Proceedings</h3>
                    <p>Track evidence items presented in courtrooms with dedicated transition logging. Log submittal targets, judicial authorities, return deadlines, and status updates to manage courtroom evidence custody. This bridges laboratory storage with legal proceedings, keeping records complete for trial presentations.</p>
                </div>
            </div>

            <!-- Equipment Logs -->
            <div class="activity-card">
                <div>
                    <div class="activity-icon"><i class="bi bi-tools"></i></div>
                    <h3>Lab Equipment Logs</h3>
                    <p>Maximize laboratory productivity and schedule maintenance by monitoring high-tech equipment logs. Track active operational statuses, log utilization duration, and schedule periodic calibration routines. This guarantees instrument reliability, preventing technical failures and ensuring that forensic testing is executed under optimal, certified conditions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Public Tip Submissions CTA -->
    <section class="cta-section">
        <div class="cta-box">
            <h2>Public Tip & Evidence submission</h2>
            <p>
                Citizens can aid active police investigations by securely submitting digital tips, context descriptions, or files. Register a general citizen account today to start submitting tips.
            </p>
            @auth
                <a href="{{ route('public.submit') }}" class="btn-action-primary">
                    <i class="bi bi-send-fill"></i> Submit Tip Information
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-action-primary">
                    <i class="bi bi-person-plus-fill"></i> Create Citizen Account
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer>
        CEFL System — Crime Evidence & Forensic Lab Management Platform — Database Lab Project
    </footer>

    <script>
        function syncThemeIcon() {
            const dark = document.documentElement.getAttribute('data-theme') === 'dark';
            const icon = document.getElementById('theme-icon');
            if (icon) icon.className = dark ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
        function toggleTheme() {
            const html = document.documentElement;
            const dark = html.getAttribute('data-theme') === 'dark';
            html.setAttribute('data-theme', dark ? 'light' : 'dark');
            localStorage.setItem('cefl-theme', dark ? 'light' : 'dark');
            syncThemeIcon();
        }
        syncThemeIcon();

        // Staggered scroll animation for activity cards
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.activity-card');
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const card = entry.target;
                        card.classList.add('visible');
                        obs.unobserve(card);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            });

            cards.forEach((card) => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
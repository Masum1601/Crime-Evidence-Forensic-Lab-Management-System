<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            try {
                const saved = localStorage.getItem('cefl-theme');
                const theme = (saved === 'dark' || saved === 'light') ? saved
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch(e) { document.documentElement.setAttribute('data-theme', 'dark'); }
        })();
    </script>
    <title>@yield('title', 'CEFL') — Crime Evidence & Forensic Lab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
    /* ═══════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════ */
    :root {
        --accent:        #6366f1;
        --accent-2:      #8b5cf6;
        --accent-glow:   rgba(99,102,241,0.25);
        --success:       #10b981;
        --warning:       #f59e0b;
        --danger:        #ef4444;
        --info:          #3b82f6;

        /* Light theme */
        --sidebar-bg:    #0f172a;
        --body-bg:       #f0f2f8;
        --topbar-bg:     rgba(255,255,255,0.85);
        --card-bg:       #ffffff;
        --card-border:   #e4e7f0;
        --text-primary:  #0f172a;
        --text-muted:    #64748b;
        --hover-bg:      #f8fafc;
        --input-bg:      #ffffff;
        --badge-open-bg: #d1fae5; --badge-open-fg: #065f46;
        --badge-closed-bg:#f1f5f9; --badge-closed-fg:#475569;
        --badge-pending-bg:#fef3c7; --badge-pending-fg:#92400e;
        --table-stripe:  #f8fafc;
    }
    [data-theme="dark"] {
        --body-bg:       #080c14;
        --topbar-bg:     rgba(11,15,25,0.85);
        --card-bg:       #0f1929;
        --card-border:   #1a2540;
        --text-primary:  #e8edf5;
        --text-muted:    #5a6a85;
        --hover-bg:      #131d30;
        --input-bg:      #0a1020;
        --badge-open-bg: rgba(16,185,129,0.15); --badge-open-fg: #34d399;
        --badge-closed-bg:rgba(148,163,184,0.1); --badge-closed-fg:#94a3b8;
        --badge-pending-bg:rgba(245,158,11,0.15); --badge-pending-fg:#fbbf24;
        --table-stripe:  rgba(255,255,255,0.02);
        --accent-soft:   rgba(99,102,241,0.12);
    }

    /* ═══════════════════════════════════════
       RESET & BASE
    ═══════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--body-bg);
        color: var(--text-primary);
        min-height: 100vh;
        transition: background 0.3s, color 0.3s;
        font-size: 14px;
    }
    a { text-decoration: none; }

    /* ═══════════════════════════════════════
       LAYOUT
    ═══════════════════════════════════════ */
    .layout { display: flex; min-height: 100vh; }
    .main-wrap { flex: 1; display: flex; flex-direction: column; min-width: 0; overflow: hidden; }

    /* ═══════════════════════════════════════
       SIDEBAR
    ═══════════════════════════════════════ */
    .sidebar {
        width: 255px;
        flex-shrink: 0;
        background: var(--sidebar-bg);
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        border-right: 1px solid rgba(255,255,255,0.04);
        scrollbar-width: none;
    }
    .sidebar::-webkit-scrollbar { display: none; }

    /* Brand */
    .sidebar-brand {
        padding: 1.4rem 1.2rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .brand-icon {
        width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: #fff;
        box-shadow: 0 0 16px var(--accent-glow);
    }
    .brand-text .brand-name {
        font-size: 0.92rem; font-weight: 800; color: #fff; line-height: 1.1; letter-spacing: -0.01em;
    }
    .brand-text .brand-sub { font-size: 0.65rem; color: #475569; margin-top: 1px; }

    /* Nav */
    .sidebar-nav { padding: 0.75rem 0.75rem 0; flex: 1; }
    .nav-section {
        font-size: 0.6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.1em; color: #334155;
        padding: 0.9rem 0.5rem 0.3rem;
    }
    .nav-link {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.5rem 0.65rem;
        border-radius: 8px;
        color: #64748b;
        font-size: 0.82rem; font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 1px;
        position: relative;
    }
    .nav-link i { font-size: 0.95rem; width: 17px; text-align: center; flex-shrink: 0; }
    .nav-link:hover {
        background: rgba(255,255,255,0.05);
        color: #cbd5e1;
    }
    .nav-link.active {
        background: linear-gradient(135deg, rgba(99,102,241,0.25), rgba(139,92,246,0.15));
        color: #fff;
        border: 1px solid rgba(99,102,241,0.3);
    }
    .nav-link.active i { color: #818cf8; }

    /* User footer */
    .sidebar-footer {
        padding: 0.9rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .user-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
        padding: 0.6rem 0.75rem;
        display: flex; align-items: center; gap: 0.6rem;
        margin-bottom: 0.6rem;
    }
    .avatar {
        width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; color: #fff; font-weight: 700;
    }
    .user-name { font-size: 0.78rem; font-weight: 600; color: #cbd5e1; line-height: 1.2; }
    .user-role {
        font-size: 0.6rem; font-weight: 700; border-radius: 4px;
        padding: 1px 5px; display: inline-block; margin-top: 1px;
    }
    .role-admin   { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .role-officer { background: rgba(99,102,241,0.2);  color: #818cf8; }
    .role-analyst { background: rgba(16,185,129,0.15); color: #34d399; }
    .btn-logout {
        width: 100%; padding: 0.4rem 0.75rem;
        border-radius: 8px; font-size: 0.78rem; font-weight: 500;
        background: rgba(239,68,68,0.08); color: #f87171;
        border: 1px solid rgba(239,68,68,0.2);
        cursor: pointer; transition: all 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    }
    .btn-logout:hover { background: rgba(239,68,68,0.18); color: #fca5a5; }

    /* ═══════════════════════════════════════
       TOPBAR
    ═══════════════════════════════════════ */
    .topbar {
        background: var(--topbar-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--card-border);
        padding: 0.7rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 100;
    }
    .page-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    .topbar-actions { display: flex; align-items: center; gap: 0.6rem; }
    .icon-btn {
        width: 32px; height: 32px; border-radius: 8px;
        border: 1px solid var(--card-border);
        background: transparent; color: var(--text-muted);
        cursor: pointer; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }
    .icon-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

    /* ═══════════════════════════════════════
       MAIN CONTENT
    ═══════════════════════════════════════ */
    .main-content { padding: 1.5rem; flex: 1; }

    /* ═══════════════════════════════════════
       CARDS
    ═══════════════════════════════════════ */
    .card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 14px;
        overflow: hidden;
    }
    .card-stat { /* same as card, kept for compatibility */
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 14px;
    }

    /* Stat cards */
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.25rem 1.4rem;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        opacity: 0;
        transition: opacity 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.12); }
    .stat-card:hover::before { opacity: 1; }
    .stat-icon {
        width: 42px; height: 42px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .stat-label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 3px; }
    .stat-value { font-size: 1.9rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .stat-sub { font-size: 0.72rem; color: var(--text-muted); margin-top: 6px; }

    /* ═══════════════════════════════════════
       TABLES
    ═══════════════════════════════════════ */
    .table { color: var(--text-primary); margin: 0; }
    .table thead th {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: var(--text-muted);
        border-bottom: 1px solid var(--card-border);
        padding: 0.75rem 1rem; background: transparent;
        white-space: nowrap;
    }
    .table tbody td {
        padding: 0.8rem 1rem; border-color: var(--card-border);
        vertical-align: middle; font-size: 0.83rem;
    }
    .table tbody tr { transition: background 0.12s; }
    .table tbody tr:hover { background: var(--hover-bg); }
    .table tbody tr:nth-child(even) { background: var(--table-stripe); }
    .table tbody tr:nth-child(even):hover { background: var(--hover-bg); }

    /* ═══════════════════════════════════════
       BADGES
    ═══════════════════════════════════════ */
    .badge-soft {
        padding: 0.28em 0.6em; border-radius: 5px;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 0.02em;
        display: inline-block; white-space: nowrap;
    }
    .badge-open    { background: var(--badge-open-bg);    color: var(--badge-open-fg); }
    .badge-closed  { background: var(--badge-closed-bg);  color: var(--badge-closed-fg); }
    .badge-pending { background: var(--badge-pending-bg);  color: var(--badge-pending-fg); }
    .badge-storage  { background: rgba(59,130,246,0.12); color: #60a5fa; }
    .badge-analysis { background: rgba(139,92,246,0.12);  color: #a78bfa; }
    .badge-transit  { background: rgba(245,158,11,0.12);  color: #fbbf24; }
    .badge-released { background: rgba(16,185,129,0.12);  color: #34d399; }
    .badge-disposed { background: rgba(148,163,184,0.1);  color: #94a3b8; }
    .badge-active   { background: rgba(16,185,129,0.12);  color: #34d399; }
    .badge-inactive { background: rgba(148,163,184,0.1);  color: #94a3b8; }

    /* ═══════════════════════════════════════
       BUTTONS
    ═══════════════════════════════════════ */
    .btn {
        border-radius: 8px; font-weight: 600;
        font-size: 0.8rem; padding: 0.45rem 0.9rem;
        transition: all 0.15s; display: inline-flex;
        align-items: center; gap: 0.35rem;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #fff; border-color: transparent;
        box-shadow: 0 2px 8px var(--accent-glow);
    }
    .btn-primary:hover { opacity: 0.9; color: #fff; box-shadow: 0 4px 14px var(--accent-glow); transform: translateY(-1px); }
    .btn-secondary { background: var(--card-bg); border-color: var(--card-border); color: var(--text-muted); }
    .btn-secondary:hover { background: var(--hover-bg); color: var(--text-primary); }
    .btn-outline-secondary { background: transparent; border-color: var(--card-border); color: var(--text-muted); }
    .btn-outline-secondary:hover { background: var(--hover-bg); color: var(--text-primary); }

    /* Icon action buttons */
    .btn-action {
        width: 30px; height: 30px; border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem; border: 1px solid var(--card-border);
        background: transparent; color: var(--text-muted);
        transition: all 0.15s; cursor: pointer;
        text-decoration: none;
    }
    .btn-action:hover        { background: rgba(99,102,241,0.12); color: var(--accent); border-color: var(--accent); }
    .btn-action.view:hover   { background: rgba(59,130,246,0.12); color: #60a5fa; border-color: #60a5fa; }
    .btn-action.edit:hover   { background: rgba(245,158,11,0.12); color: #fbbf24; border-color: #f59e0b; }
    .btn-action.delete:hover { background: rgba(239,68,68,0.12);  color: #f87171; border-color: #ef4444; }

    /* ═══════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════ */
    .alert { border-radius: 10px; padding: 0.7rem 1rem; font-size: 0.83rem; border: none; }
    .alert-success { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
    .alert-danger  { background: rgba(239,68,68,0.1);   color: #f87171; border: 1px solid rgba(239,68,68,0.2); }

    /* ═══════════════════════════════════════
       FORMS
    ═══════════════════════════════════════ */
    .form-control, .form-select {
        background: var(--input-bg);
        border: 1px solid var(--card-border);
        border-radius: 9px;
        color: var(--text-primary);
        font-size: 0.83rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .form-control:focus, .form-select:focus {
        background: var(--input-bg);
        color: var(--text-primary);
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.18);
        outline: none;
    }
    .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.04em; }
    textarea.form-control { resize: vertical; }

    /* ═══════════════════════════════════════
       PAGE HEADER
    ═══════════════════════════════════════ */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .page-header h2 {
        font-size: 1.1rem; font-weight: 800; color: var(--text-primary);
        display: flex; align-items: center; gap: 0.5rem; margin: 0;
    }
    .page-header h2 i { color: var(--accent); font-size: 1rem; }

    /* ═══════════════════════════════════════
       FILTER BAR
    ═══════════════════════════════════════ */
    .filter-bar {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 0.9rem 1rem;
        margin-bottom: 1rem;
    }

    /* ═══════════════════════════════════════
       PAGINATION
    ═══════════════════════════════════════ */
    .pagination { gap: 3px; }
    .page-link {
        background: var(--card-bg); border: 1px solid var(--card-border);
        color: var(--text-muted); border-radius: 7px !important;
        font-size: 0.78rem; padding: 0.35rem 0.65rem; transition: all 0.15s;
    }
    .page-link:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
    .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; }

    /* ═══════════════════════════════════════
       LIST GROUP
    ═══════════════════════════════════════ */
    .list-group-item {
        background: transparent; border-color: var(--card-border);
        color: var(--text-primary); font-size: 0.83rem;
        padding: 0.75rem 1.25rem;
    }

    /* ═══════════════════════════════════════
       CODE
    ═══════════════════════════════════════ */
    code {
        background: rgba(99,102,241,0.1); color: #818cf8;
        padding: 0.1em 0.4em; border-radius: 4px; font-size: 0.8rem;
    }

    /* ═══════════════════════════════════════
       SCROLLBAR
    ═══════════════════════════════════════ */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 10px; }

    /* ═══════════════════════════════════════
       TRANSITIONS
    ═══════════════════════════════════════ */
    .card, .stat-card, .filter-bar { transition: background 0.3s, border-color 0.3s; }
    </style>
</head>
<body>
<div class="layout">

    {{-- ══════════════════ SIDEBAR ══════════════════ --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="brand-text">
                <div class="brand-name">CEFL System</div>
                <div class="brand-sub">Forensic Lab Management</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('cases.index') }}" class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> Cases
            </a>
            <a href="{{ route('evidence.index') }}" class="nav-link {{ request()->routeIs('evidence.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Evidence
            </a>
            <a href="{{ route('custody.index') }}" class="nav-link {{ request()->routeIs('custody.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Chain of Custody
            </a>
            @if(Route::has('tests.index'))
            <a href="{{ route('tests.index') }}" class="nav-link {{ request()->routeIs('tests.*') ? 'active' : '' }}">
                <i class="bi bi-eyedropper"></i> Forensic Tests
            </a>
            @endif

            @if(auth()->user()?->role?->role_name === 'Admin')
            <div class="nav-section">Admin</div>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
            @if(Route::has('admin.submissions'))
            <a href="{{ route('admin.submissions') }}" class="nav-link {{ request()->routeIs('admin.submissions*') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i> Public Submissions
            </a>
            @endif
            @endif
        </div>

        <div class="sidebar-footer">
            @php $u = auth()->user(); @endphp
            <div class="user-card">
                <div class="avatar">{{ strtoupper(substr($u?->full_name ?? 'U', 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ $u?->full_name ?? 'Guest' }}</div>
                    @php $rn = strtolower($u?->role?->role_name ?? ''); @endphp
                    <span class="user-role role-{{ $rn }}">{{ $u?->role?->role_name ?? 'User' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════ MAIN ══════════════════ --}}
    <div class="main-wrap">
        <div class="topbar">
            <div class="page-title">@yield('page_title', 'Dashboard')</div>
            <div class="topbar-actions">
                <button class="icon-btn" onclick="toggleTheme()" title="Toggle theme" id="theme-btn">
                    <i class="bi bi-moon-fill" id="theme-icon"></i>
                </button>
            </div>
        </div>

        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <div class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-exclamation-triangle-fill"></i> <strong>Please fix the following errors:</strong></div>
                    <ul class="mb-0 ps-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

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
</script>
</body>
</html>
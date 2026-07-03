<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CEFL System') - Crime Evidence & Forensic Lab Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background-color: #1e2a38;
        }
        .sidebar .nav-link {
            color: #c7d0db;
            padding: 0.65rem 1rem;
            border-radius: 6px;
            margin-bottom: 2px;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #2c3e50;
            color: #fff;
        }
        .sidebar .nav-link i { width: 20px; }
        .brand-box {
            color: #fff;
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #32404f;
        }
        .badge-status {
            font-size: 0.78rem;
            padding: 0.35em 0.7em;
        }
        .card-stat {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-2" style="width: 240px;">
            <div class="brand-box mb-2">
                <strong><i class="bi bi-shield-lock-fill me-1"></i> CEFL System</strong>
                <div class="small text-secondary">Forensic Lab Management</div>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cases.index') }}" class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open me-2"></i>Cases
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('evidence.index') }}" class="nav-link {{ request()->routeIs('evidence.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i>Evidence
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('custody.index') }}" class="nav-link {{ request()->routeIs('custody.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right me-2"></i>Chain of Custody
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>Users
                    </a>
                </li>
            </ul>
            <hr class="text-secondary">
            <div class="text-light small px-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->full_name ?? 'Guest' }}
                <div class="text-secondary">{{ auth()->user()->role->role_name ?? '' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="px-2 mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </nav>

        <!-- Main content -->
        <main class="flex-fill p-4">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
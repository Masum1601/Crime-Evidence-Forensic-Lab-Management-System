<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CEFL System') - Crime Evidence & Forensic Lab Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">CEFL System</a>
            <div>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('users.index') }}">Users</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('cases.index') }}">Cases</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>

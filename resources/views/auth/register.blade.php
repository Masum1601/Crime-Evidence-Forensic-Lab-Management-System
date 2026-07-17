<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - CEFL System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #1e2a38, #2c3e50); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .card-box { background: #fff; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); width: 100%; max-width: 440px; }
    </style>
</head>
<body>
    <div class="card card-box p-4">
        <div class="text-center mb-3">
            <i class="bi bi-person-plus-fill" style="font-size:2.2rem;color:#2c3e50;"></i>
            <h4 class="mt-2 mb-0">Create an Account</h4>
            <div class="text-muted small">Register to submit information or request staff access</div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone (optional)</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-dark w-100">
                <i class="bi bi-check-lg me-1"></i> Create Account
            </button>
        </form>

        <div class="text-center mt-3 small">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>
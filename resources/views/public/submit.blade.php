<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
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
    <title>Submit Information - CEFL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #cbd5e1;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --input-placeholder: #94a3b8;
            --back-link: #475569;
            --option-bg: #ffffff;
        }
        [data-theme="dark"] {
            --body-bg: #0f172a;
            --card-bg: #1e293b;
            --card-border: #334155;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-text: #f1f5f9;
            --input-placeholder: #475569;
            --back-link: #94a3b8;
            --option-bg: #1e293b;
        }
        body { background: var(--body-bg); min-height: 100vh; padding: 2rem 1rem; font-family: system-ui, sans-serif; }
        .submit-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; padding: 2rem; max-width: 600px; margin: 0 auto; color: var(--text-main); }
        .form-control, .form-select { background: var(--input-bg); border: 1px solid var(--input-border); color: var(--input-text); border-radius: 8px; }
        .form-control:focus, .form-select:focus { background: var(--input-bg); color: var(--input-text); border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        .form-label { color: var(--text-muted); font-size: 0.8rem; font-weight: 600; }
        .form-control::placeholder { color: var(--input-placeholder); }
        .btn-submit { background: linear-gradient(135deg, #6366f1, #3b82f6); border: none; border-radius: 10px; font-weight: 700; padding: 0.65rem 1.5rem; color: #fff; }
        .btn-submit:hover { opacity: 0.9; color: #fff; }
        select option { background: var(--option-bg); color: var(--input-text); }
    </style>
</head>
<body>
    <div style="text-align:center;margin-bottom:1.5rem;">
        <a href="{{ route('home') }}" style="color:var(--back-link);text-decoration:none;font-size:0.875rem;">
            <i class="bi bi-arrow-left me-1"></i> Back to Home
        </a>
    </div>

    <div class="submit-card">
        <div class="text-center mb-4">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#3b82f6);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;margin:0 auto 0.75rem;">
                <i class="bi bi-send"></i>
            </div>
            <h4 style="color:var(--text-main);font-weight:800;">Submit Information</h4>
            <p style="color:var(--text-muted);font-size:0.875rem;">Submit a tip, complaint, or information related to a case. Your identity can remain anonymous.</p>
        </div>

        @if(session('success'))
            <div class="alert" style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('public.submit.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="submitter_name" class="form-control" placeholder="Full name or 'Anonymous'" value="{{ old('submitter_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email (optional)</label>
                    <input type="email" name="submitter_email" class="form-control" placeholder="your@email.com" value="{{ old('submitter_email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone (optional)</label>
                    <input type="text" name="submitter_phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ old('submitter_phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Related Case (if known)</label>
                    <select name="related_case_id" class="form-select">
                        <option value="">-- Not sure / General --</option>
                        @foreach($cases as $case)
                            <option value="{{ $case->case_id }}">{{ $case->case_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Brief subject of your submission" value="{{ old('subject') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Your Information / Description</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Describe what you saw, heard, or know in as much detail as possible..." required>{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-submit w-100">
                        <i class="bi bi-send me-1"></i> Submit Information
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
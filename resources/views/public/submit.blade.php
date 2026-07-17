@extends('layouts.app_v3')

@section('title', 'Submit Information')
@section('page_title', 'Submit Information')

@section('content')
    <div class="card p-4" style="max-width:650px;">
        <p style="color:var(--text-muted);font-size:0.875rem;">
            Submitting as <strong>{{ auth()->user()->full_name }}</strong> ({{ auth()->user()->email }}).
            Your submission will be linked to your account for follow-up.
        </p>

        <form action="{{ route('public.submit.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Related Case (if known)</label>
                <select name="related_case_id" class="form-select">
                    <option value="">-- Not sure / General --</option>
                    @foreach($cases as $case)
                        <option value="{{ $case->case_id }}">{{ $case->case_title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-1"></i> Submit Information
            </button>
        </form>
    </div>
@endsection
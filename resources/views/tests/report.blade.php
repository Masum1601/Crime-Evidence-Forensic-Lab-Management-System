@extends('layouts.app_v3')

@section('title', 'File Test Report')
@section('page_title', 'File Report — ' . ($test->testType->test_name ?? ''))

@section('content')
    <form action="{{ route('tests.report.store', $test->request_id) }}" method="POST" class="card p-4" style="max-width:700px;">
        @csrf
        <div class="mb-2" style="font-size:0.875rem;color:var(--text-muted);">
            Evidence: <strong>{{ $test->evidence->evidence_name }}</strong> · Test: <strong>{{ $test->testType->test_name }}</strong>
        </div>
        <div class="mb-3">
            <label class="form-label">Result Summary</label>
            <input type="text" name="result_summary" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Detailed Report</label>
            <textarea name="detailed_report" class="form-control" rows="6"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> File Report & Complete Test</button>
    </form>
@endsection
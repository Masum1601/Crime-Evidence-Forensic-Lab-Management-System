@extends('layouts.app_v3')

@section('title', 'New Test Request')
@section('page_title', 'New Forensic Test Request')

@section('content')
    <form action="{{ route('tests.store') }}" method="POST" class="card p-4" style="max-width:700px;">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Evidence Item</label>
                <select name="evidence_id" class="form-select" required>
                    <option value="">-- Select Evidence --</option>
                    @foreach($evidenceItems as $item)
                        <option value="{{ $item->evidence_id }}">{{ $item->evidence_name }} ({{ $item->barcode_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Test Type</label>
                <select name="test_type_id" class="form-select" required>
                    <option value="">-- Select Test --</option>
                    @foreach($testTypes as $type)
                        <option value="{{ $type->test_type_id }}">{{ $type->test_name }} ({{ $type->estimated_duration }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Assign Analyst</label>
                <select name="assigned_analyst_id" class="form-select">
                    <option value="">-- Assign Later --</option>
                    @foreach($analysts as $analyst)
                        <option value="{{ $analyst->user_id }}">{{ $analyst->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" required>
                    @foreach(['LOW','NORMAL','HIGH','URGENT'] as $p)
                        <option value="{{ $p }}" {{ $p === 'NORMAL' ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions for the analyst..."></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Submit Request</button>
            <a href="{{ route('tests.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
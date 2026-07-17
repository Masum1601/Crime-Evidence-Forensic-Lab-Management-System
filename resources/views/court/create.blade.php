@extends('layouts.app_v3')

@section('title', 'Submit to Court')
@section('page_title', 'Submit Evidence to Court')

@section('content')
    <form action="{{ route('court.store') }}" method="POST" class="card p-4" style="max-width:600px;">
        @csrf
        <div class="mb-3">
            <label class="form-label">Evidence Item</label>
            <select name="evidence_id" class="form-select" required>
                <option value="">-- Select Evidence --</option>
                @foreach($evidenceItems as $item)
                    <option value="{{ $item->evidence_id }}">{{ $item->evidence_name }} ({{ $item->barcode_no }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Court Name</label>
            <input type="text" name="court_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Case Reference No.</label>
            <input type="text" name="case_reference_no" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Submit</button>
        <a href="{{ route('court.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
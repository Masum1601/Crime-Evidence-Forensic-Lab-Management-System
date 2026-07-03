@extends('layouts.app_v2')

@section('title', 'Edit Evidence')

@section('content')
    <h3 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Evidence</h3>

    <form action="{{ route('evidence.update', $evidence->evidence_id) }}" method="POST" class="card card-stat p-4">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Evidence Name</label>
                <input type="text" name="evidence_name" class="form-control" value="{{ $evidence->evidence_name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Barcode No.</label>
                <input type="text" name="barcode_no" class="form-control" value="{{ $evidence->barcode_no }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Linked Case</label>
                <select name="case_id" class="form-select" required>
                    @foreach ($cases as $case)
                        <option value="{{ $case->case_id }}" {{ $evidence->case_id == $case->case_id ? 'selected' : '' }}>{{ $case->case_title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Evidence Type</label>
                <input type="text" name="evidence_type" class="form-control" value="{{ $evidence->evidence_type }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Collected By</label>
                <select name="collected_by" class="form-select" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" {{ $evidence->collected_by == $user->user_id ? 'selected' : '' }}>{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Storage Location</label>
                <select name="location_id" class="form-select">
                    <option value="">-- Select Location --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->location_id }}" {{ $evidence->location_id == $location->location_id ? 'selected' : '' }}>{{ $location->location_name }} ({{ $location->room_no }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="current_status" class="form-select" required>
                    @foreach (['IN_STORAGE', 'IN_ANALYSIS', 'IN_TRANSIT', 'RELEASED', 'DISPOSED'] as $status)
                        <option value="{{ $status }}" {{ $evidence->current_status === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $evidence->description }}</textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
            <a href="{{ route('evidence.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
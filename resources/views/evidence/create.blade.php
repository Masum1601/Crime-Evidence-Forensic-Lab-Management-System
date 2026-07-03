@extends('layouts.app_v2')

@section('title', 'Register Evidence')

@section('content')
    <h3 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Register Evidence</h3>

    <form action="{{ route('evidence.store') }}" method="POST" class="card card-stat p-4">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Evidence Name <span class="text-danger">*</span></label>
                <input type="text" name="evidence_name" class="form-control" value="{{ old('evidence_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Barcode No.</label>
                <input type="text" name="barcode_no" class="form-control" value="{{ old('barcode_no') }}" placeholder="Optional unique barcode">
            </div>

            <div class="col-md-6">
                <label class="form-label">Linked Case <span class="text-danger">*</span></label>
                <select name="case_id" class="form-select" required>
                    <option value="">-- Select Case --</option>
                    @foreach ($cases as $case)
                        <option value="{{ $case->case_id }}" {{ old('case_id') == $case->case_id ? 'selected' : '' }}>
                            {{ $case->case_title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Evidence Type</label>
                <input type="text" name="evidence_type" class="form-control" value="{{ old('evidence_type') }}" placeholder="e.g. Weapon, Document, Digital">
            </div>

            <div class="col-md-6">
                <label class="form-label">Collected By <span class="text-danger">*</span></label>
                <select name="collected_by" class="form-select" required>
                    <option value="">-- Select Officer --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" {{ old('collected_by') == $user->user_id ? 'selected' : '' }}>
                            {{ $user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Storage Location</label>
                <select name="location_id" class="form-select">
                    <option value="">-- Select Location --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->location_id }}" {{ old('location_id') == $location->location_id ? 'selected' : '' }}>
                            {{ $location->location_name }} ({{ $location->room_no }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="current_status" class="form-select" required>
                    @foreach (['IN_STORAGE', 'IN_ANALYSIS', 'IN_TRANSIT', 'RELEASED', 'DISPOSED'] as $status)
                        <option value="{{ $status }}" {{ old('current_status', 'IN_STORAGE') === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional description...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Register Evidence
            </button>
            <a href="{{ route('evidence.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection

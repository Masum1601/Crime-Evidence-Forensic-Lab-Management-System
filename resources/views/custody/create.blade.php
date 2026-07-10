@extends('layouts.app_v3')

@section('title', 'Record Custody Transfer')

@section('content')
    <h3 class="mb-3"><i class="bi bi-arrow-left-right me-2"></i>Record Custody Transfer</h3>

    <form action="{{ route('custody.store') }}" method="POST" class="card card-stat p-4">
        @csrf

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Evidence Item</label>
                <select name="evidence_id" class="form-select" required>
                    <option value="">-- Select Evidence --</option>
                    @foreach ($evidenceItems as $item)
                        <option value="{{ $item->evidence_id }}" {{ (string) $selectedEvidenceId === (string) $item->evidence_id ? 'selected' : '' }}>
                            {{ $item->evidence_name }} ({{ $item->barcode_no }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">From (current holder)</label>
                <select name="from_user_id" class="form-select">
                    <option value="">-- N/A (Initial Collection) --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">To (new holder)</label>
                <select name="to_user_id" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Reason for Transfer</label>
                <input type="text" name="reason" class="form-control" placeholder="e.g. Sent for forensic analysis" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Remarks (optional)</label>
                <textarea name="remarks" class="form-control" rows="2"></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Record Transfer</button>
            <a href="{{ route('custody.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <div class="alert alert-info mt-3 small">
        <i class="bi bi-info-circle me-1"></i>
        This transfer will automatically be logged into the Audit Logs table by the
        <code>trg_custody_audit</code> PL/SQL trigger.
    </div>
@endsection
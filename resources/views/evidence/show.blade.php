@extends('layouts.app_v3')

@section('title', 'Evidence Detail')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-box-seam me-2"></i>{{ $evidence->evidence_name }}</h3>
        <a href="{{ route('custody.create', ['evidence_id' => $evidence->evidence_id]) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-right me-1"></i> Transfer Custody
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card card-stat p-3">
                <div class="mb-2"><span class="text-muted small">Barcode:</span> <code>{{ $evidence->barcode_no }}</code></div>
                <div class="mb-2"><span class="text-muted small">Type:</span> {{ $evidence->evidence_type }}</div>
                <div class="mb-2"><span class="text-muted small">Case:</span> {{ $evidence->case->case_title ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted small">Status:</span> <span class="badge bg-info badge-status">{{ $evidence->current_status }}</span></div>
                <div class="mb-2"><span class="text-muted small">Location:</span> {{ $evidence->location->location_name ?? '—' }}</div>
                <div><span class="text-muted small">Description:</span> {{ $evidence->description }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stat p-3">
                <div class="mb-2"><span class="text-muted small">Collected By:</span> {{ $evidence->collector->full_name ?? 'N/A' }}</div>
                <div><span class="text-muted small">Collection Date:</span> {{ $evidence->collection_date }}</div>
            </div>
        </div>
    </div>

    <div class="card card-stat">
        <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history me-1"></i> Chain of Custody History</div>
        <ul class="list-group list-group-flush">
            @forelse ($evidence->custodyRecords as $record)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $record->fromUser->full_name ?? 'Initial Collection' }}</strong>
                            <i class="bi bi-arrow-right mx-1"></i>
                            <strong>{{ $record->toUser->full_name ?? 'N/A' }}</strong>
                            <div class="small text-muted">{{ $record->reason }}</div>
                            @if ($record->remarks)
                                <div class="small text-muted">{{ $record->remarks }}</div>
                            @endif
                        </div>
                        <div class="text-muted small">{{ $record->transfer_date }}</div>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No custody history yet.</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('evidence.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Evidence
    </a>
@endsection
@extends('layouts.app_v3')

@section('title', 'Evidence Detail')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-box-seam me-2"></i>{{ $evidence->evidence_name }}</h3>
        <a href="{{ route('custody.create', ['evidence_id' => $evidence->evidence_id]) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-right me-1"></i> Transfer Custody
        </a>
    </div>

    {{-- Evidence Lifecycle Stepper --}}
    <div class="card card-stat p-4 mb-4">
        <h5 class="fw-bold mb-4" style="font-size: 0.95rem; color: var(--text-primary);">
            <i class="bi bi-bezier2 me-1 text-primary"></i> Evidence Lifecycle Status
        </h5>
        @php
            $status = $evidence->current_status;
            $activeIdx = 0;
            if ($status === 'IN_ANALYSIS') $activeIdx = 1;
            elseif ($status === 'IN_TRANSIT') $activeIdx = 2;
            elseif ($status === 'RELEASED' || $status === 'DISPOSED') $activeIdx = 3;
        @endphp
        
        <div class="lifecycle-stepper">
            <div class="stepper-track"></div>
            <div class="stepper-progress" style="width: {{ ($activeIdx / 3) * 100 }}%"></div>
            
            <div class="stepper-step {{ $activeIdx >= 0 ? 'active' : '' }} {{ $activeIdx == 0 ? 'current' : '' }}">
                <div class="step-icon"><i class="bi bi-archive"></i></div>
                <div class="step-label">In Storage</div>
            </div>
            <div class="stepper-step {{ $activeIdx >= 1 ? 'active' : '' }} {{ $activeIdx == 1 ? 'current' : '' }}">
                <div class="step-icon"><i class="bi bi-activity"></i></div>
                <div class="step-label">In Analysis</div>
            </div>
            <div class="stepper-step {{ $activeIdx >= 2 ? 'active' : '' }} {{ $activeIdx == 2 ? 'current' : '' }}">
                <div class="step-icon"><i class="bi bi-truck"></i></div>
                <div class="step-label">In Transit</div>
            </div>
            <div class="stepper-step {{ $activeIdx >= 3 ? 'active' : '' }} {{ $activeIdx == 3 ? 'current' : '' }}">
                <div class="step-icon"><i class="bi {{ ($status === 'DISPOSED') ? 'bi-trash3' : 'bi-check-circle' }}"></i></div>
                <div class="step-label">{{ ($status === 'DISPOSED') ? 'Disposed' : (($status === 'RELEASED') ? 'Released' : 'Released / Disposed') }}</div>
            </div>
        </div>
    </div>

    <style>
    .lifecycle-stepper {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 1rem 0;
    }
    .stepper-track {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-border);
        transform: translateY(-50%);
        z-index: 1;
        border-radius: 2px;
    }
    .stepper-progress {
        position: absolute;
        top: 50%;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        transform: translateY(-50%);
        z-index: 2;
        border-radius: 2px;
        transition: width 0.4s ease;
    }
    .stepper-step {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .step-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .stepper-step.active .step-icon {
        background: var(--card-bg);
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);
    }
    .stepper-step.current .step-icon {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        border-color: transparent;
        color: #fff;
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        transform: scale(1.1);
    }
    .step-label {
        margin-top: 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        transition: color 0.3s;
    }
    .stepper-step.active .step-label {
        color: var(--text-primary);
    }
    .stepper-step.current .step-label {
        color: var(--accent);
        font-weight: 700;
    }

    /* Timeline styles */
    .custody-timeline {
        position: relative;
        padding-left: 2rem;
        margin-left: 0.5rem;
        border-left: 2px dashed var(--card-border);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.75rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-badge {
        position: absolute;
        left: calc(-2rem - 11px);
        top: 8px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--body-bg);
        border: 2px solid var(--accent);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        z-index: 10;
        box-shadow: 0 0 0 4px var(--card-bg);
    }
    .timeline-card {
        background: var(--hover-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1.15rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }
    .timeline-card:hover {
        transform: translateX(3px);
    }
    .party-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-primary);
    }
    .party-name.highlighted {
        color: var(--accent);
    }
    .transfer-time {
        font-size: 0.72rem;
        color: var(--text-muted);
    }
    .detail-row {
        font-size: 0.8rem;
        color: var(--text-primary);
    }
    .detail-label {
        font-weight: 600;
        color: var(--text-muted);
        margin-right: 0.25rem;
    }
    .detail-value.italic {
        font-style: italic;
    }
    .text-accent {
        color: var(--accent) !important;
    }
    </style>

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

    <div class="card card-stat p-4">
        <h5 class="fw-bold mb-4" style="font-size:0.95rem; color:var(--text-primary)">
            <i class="bi bi-clock-history me-1 text-primary"></i> Chain of Custody Timeline
        </h5>
        
        @if ($evidence->custodyRecords->isEmpty())
            <div class="text-center py-4 text-muted">No custody transfers recorded yet.</div>
        @else
            <div class="custody-timeline">
                @foreach ($evidence->custodyRecords as $record)
                    <div class="timeline-item">
                        <div class="timeline-badge">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="timeline-card card">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2">
                                <div class="transfer-parties">
                                    <span class="party-name">{{ $record->fromUser->full_name ?? 'Crime Scene' }}</span>
                                    <i class="bi bi-arrow-right mx-2 text-accent"></i>
                                    <span class="party-name highlighted">{{ $record->toUser->full_name ?? 'N/A' }}</span>
                                </div>
                                <span class="transfer-time"><i class="bi bi-calendar-event me-1"></i>{{ $record->transfer_date }}</span>
                            </div>
                            <div class="transfer-details">
                                <div class="detail-row">
                                    <span class="detail-label">Reason:</span>
                                    <span class="detail-value">{{ $record->reason }}</span>
                                </div>
                                @if ($record->remarks)
                                    <div class="detail-row mt-1">
                                        <span class="detail-label">Remarks:</span>
                                        <span class="detail-value italic">{{ $record->remarks }}</span>
                                    </div>
                                @endif
                                <div class="detail-row mt-1" style="font-size:0.75rem;">
                                    <span class="detail-label">Authorized By:</span>
                                    <span class="detail-value">{{ $record->transferredByUser->full_name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <a href="{{ route('evidence.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Evidence
    </a>
@endsection
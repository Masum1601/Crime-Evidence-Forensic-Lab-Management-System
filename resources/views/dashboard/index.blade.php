@extends('layouts.app_v2')

@section('title', 'Dashboard')

@section('content')
    <h3 class="mb-4">Dashboard</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">Total Cases</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_cases'] }}</div>
                    </div>
                    <i class="bi bi-folder2-open fs-1 text-primary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">Open Cases</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['open_cases'] }}</div>
                    </div>
                    <i class="bi bi-unlock fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">Total Evidence</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_evidence'] }}</div>
                    </div>
                    <i class="bi bi-box-seam fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted small">Custody Transfers</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_custody_transfers'] }}</div>
                    </div>
                    <i class="bi bi-arrow-left-right fs-1 text-info opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card card-stat">
                <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history me-1"></i> Recent Cases</div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentCases as $case)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $case->case_title }}</div>
                                <div class="small text-muted">Officer: {{ $case->officer->full_name ?? 'N/A' }}</div>
                            </div>
                            <span class="badge bg-{{ $case->case_status === 'OPEN' ? 'success' : ($case->case_status === 'CLOSED' ? 'secondary' : 'warning') }} badge-status">
                                {{ $case->case_status }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No cases yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stat">
                <div class="card-header bg-white fw-bold"><i class="bi bi-box-seam me-1"></i> Recent Evidence</div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentEvidence as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $item->evidence_name }}</div>
                                <div class="small text-muted">Case: {{ $item->case->case_title ?? 'N/A' }}</div>
                            </div>
                            <span class="badge bg-info badge-status">{{ $item->current_status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No evidence yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
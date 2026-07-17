@extends('layouts.app_v3')
@section('title', 'Officer Dashboard')
@section('page_title', 'Investigating Officer Dashboard')

@section('content')
<div style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem">
    <div>
        <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ auth()->user()->full_name }} 👋
        </div>
        <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
            Track your assigned cases, log newly gathered evidence, and monitor chain of custody transfers — {{ now()->format('l, d F Y') }}
        </div>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            <i class="bi bi-folder-plus"></i> New Case
        </a>
        <a href="{{ route('evidence.create') }}" class="btn btn-secondary">
            <i class="bi bi-box-seam"></i> Register Evidence
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Assigned Cases</div>
                    <div class="stat-value">{{ $stats['my_cases'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
            <div class="stat-sub">Cases under your investigation</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Investigations</div>
                    <div class="stat-value" style="color: #34d399">{{ $stats['my_open_cases'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="stat-sub">Currently open cases</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Closed Cases</div>
                    <div class="stat-value">{{ $stats['my_closed_cases'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(148,163,184,0.12);color:#94a3b8">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-sub">Successfully resolved cases</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Evidence Collected</div>
                    <div class="stat-value">{{ $stats['my_evidence'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24">
                    <i class="bi bi-fingerprint"></i>
                </div>
            </div>
            <div class="stat-sub">Evidence items logged by you</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- My Cases --}}
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-folder" style="color:var(--accent)"></i> My Recent Cases
                </span>
                <a href="{{ route('cases.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Case Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCases as $case)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $case->case_title }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">Opened: {{ $case->opened_date }}</div>
                            </td>
                            <td>
                                <span style="font-size:0.8rem">{{ $case->case_type }}</span>
                            </td>
                            <td>
                                @php 
                                    $c = match($case->case_status) {
                                        'OPEN' => 'badge-open',
                                        'CLOSED' => 'badge-closed',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="badge-soft {{ $c }}">{{ $case->case_status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('cases.show', $case->case_id) }}" class="btn btn-secondary btn-sm px-2 py-1" style="font-size:0.72rem">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-folder-x d-block mb-1" style="font-size:1.2rem"></i>
                                No cases assigned to you yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- My Evidence --}}
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-box-seam" style="color:var(--accent)"></i> Evidence Logged By Me
                </span>
                <a href="{{ route('evidence.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Evidence Item</th>
                            <th>Case Reference</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentEvidence as $ev)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $ev->evidence_name }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">Type: {{ $ev->evidence_type }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.8rem; font-weight: 500">{{ $ev->case->case_title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @php 
                                    $c = match($ev->current_status) {
                                        'IN_STORAGE' => 'badge-storage',
                                        'UNDER_ANALYSIS' => 'badge-analysis',
                                        'IN_TRANSIT' => 'badge-transit',
                                        'RELEASED' => 'badge-released',
                                        'DISPOSED' => 'badge-disposed',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="badge-soft {{ $c }}">{{ str_replace('_', ' ', $ev->current_status) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('evidence.show', $ev->evidence_id) }}" class="btn btn-secondary btn-sm px-2 py-1" style="font-size:0.72rem">
                                    Track
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-clipboard-x d-block mb-1" style="font-size:1.2rem"></i>
                                No evidence items logged yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

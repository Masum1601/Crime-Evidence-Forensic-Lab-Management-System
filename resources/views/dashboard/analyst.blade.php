@extends('layouts.app_v3')
@section('title', 'Analyst Dashboard')
@section('page_title', 'Forensic Analyst Dashboard')

@section('content')
<div style="margin-bottom:1.5rem">
    <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
        {{ auth()->user()->full_name }} 👋
    </div>
    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
        Manage test requests assigned to you, record forensic results, and track lab equipment status — {{ now()->format('l, d F Y') }}
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Assigned Tests</div>
                    <div class="stat-value">{{ $stats['my_assigned_tests'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);color:#a78bfa">
                    <i class="bi bi-eyedropper"></i>
                </div>
            </div>
            <div class="stat-sub">Total lab test requests</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending Tests</div>
                    <div class="stat-value" style="color: #f59e0b">{{ $stats['my_pending_tests'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-sub">Awaiting laboratory start</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">In Progress</div>
                    <div class="stat-value" style="color: #3b82f6">{{ $stats['my_progress_tests'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
            <div class="stat-sub">Actively under examination</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Completed Reports</div>
                    <div class="stat-value" style="color: #10b981">{{ $stats['my_completed_tests'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
            </div>
            <div class="stat-sub">Reports compiled & signed off</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Test Queue --}}
    <div class="col-md-7">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-list-task" style="color:var(--accent)"></i> My Assigned Lab Tests
                </span>
                <a href="{{ route('tests.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Test/Evidence</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignedTests as $req)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $req->testType->test_name ?? 'Forensic Analysis' }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">
                                    Evidence: {{ $req->evidence->evidence_name ?? 'Unknown item' }}
                                </div>
                            </td>
                            <td>
                                @php 
                                    $pCls = match($req->priority) {
                                        'HIGH' => 'danger',
                                        'MEDIUM' => 'warning',
                                        default => 'info'
                                    };
                                @endphp
                                <span class="badge bg-{{ $pCls }} text-dark" style="font-size:0.65rem; padding: 2px 6px;">{{ $req->priority }}</span>
                            </td>
                            <td>
                                @php 
                                    $c = match($req->test_status) {
                                        'PENDING' => 'badge-pending',
                                        'IN_PROGRESS' => 'badge-analysis',
                                        'COMPLETED' => 'badge-open',
                                        default => 'badge-closed'
                                    };
                                @endphp
                                <span class="badge-soft {{ $c }}">{{ $req->test_status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('tests.index') }}" class="btn btn-secondary btn-sm px-2 py-1" style="font-size:0.72rem">
                                    Update
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-clipboard-check d-block mb-1" style="font-size:1.2rem"></i>
                                No active tests assigned to you
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Equipment Status --}}
    <div class="col-md-5">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-tools" style="color:var(--accent)"></i> Laboratory Equipment
                </span>
                @if(Route::has('equipment.index'))
                    <a href="{{ route('equipment.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Condition</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $eq)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.82rem">{{ $eq->equipment_name }}</div>
                                <div style="font-size:0.7rem;color:var(--text-muted)">{{ $eq->equipment_type }}</div>
                            </td>
                            <td>
                                @php 
                                    $condCls = match($eq->condition_status) {
                                        'GOOD' => 'text-success',
                                        'NEEDS_MAINTENANCE' => 'text-warning',
                                        default => 'text-danger'
                                    };
                                @endphp
                                <span class="fw-semibold {{ $condCls }}" style="font-size: 0.75rem;">
                                    {{ str_replace('_', ' ', $eq->condition_status) }}
                                </span>
                            </td>
                            <td>
                                @php 
                                    $avCls = match($eq->availability_status) {
                                        'AVAILABLE' => 'badge-open',
                                        'IN_USE' => 'badge-analysis',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="badge-soft {{ $avCls }}">{{ $eq->availability_status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-slash-circle d-block mb-1" style="font-size:1.2rem"></i>
                                No equipment cataloged
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

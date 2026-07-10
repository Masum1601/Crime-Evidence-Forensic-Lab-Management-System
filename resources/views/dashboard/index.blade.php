@extends('layouts.app_v3')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div style="margin-bottom:1.5rem">
    <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
        {{ auth()->user()->full_name }} 👋
    </div>
    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
        Here's what's happening in the forensic lab today — {{ now()->format('l, d F Y') }}
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Cases</div>
                    <div class="stat-value">{{ $stats['total_cases'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
            <div class="stat-sub">
                <span style="color:#34d399;font-weight:600">{{ $stats['open_cases'] }} open</span>
                &nbsp;·&nbsp; {{ $stats['closed_cases'] }} closed
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Evidence</div>
                    <div class="stat-value">{{ $stats['total_evidence'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-sub">
                <span style="color:#60a5fa;font-weight:600">{{ $stats['evidence_in_storage'] }} in storage</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Custody Transfers</div>
                    <div class="stat-value">{{ $stats['total_custody_transfers'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
            <div class="stat-sub">Auto-logged by PL/SQL trigger</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">System Users</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);color:#a78bfa">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-sub">Across all roles</div>
        </div>
    </div>
</div>

{{-- Recent Lists --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-clock-history" style="color:var(--accent)"></i> Recent Cases
                </span>
                <a href="{{ route('cases.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($recentCases as $case)
                @php $cls = match($case->case_status){'OPEN'=>'open','CLOSED'=>'closed',default=>'pending'}; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-weight:600;font-size:0.83rem">{{ $case->case_title }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted)">{{ $case->officer->full_name ?? 'Unassigned' }}</div>
                    </div>
                    <span class="badge-soft badge-{{ $cls }}">{{ $case->case_status }}</span>
                </li>
                @empty
                <li class="list-group-item" style="color:var(--text-muted)">No cases yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-box-seam" style="color:var(--accent)"></i> Recent Evidence
                </span>
                <a href="{{ route('evidence.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($recentEvidence as $item)
                @php $sm=['IN_STORAGE'=>'storage','IN_ANALYSIS'=>'analysis','IN_TRANSIT'=>'transit','RELEASED'=>'released','DISPOSED'=>'disposed'];$cls=$sm[$item->current_status]??'closed'; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-weight:600;font-size:0.83rem">{{ $item->evidence_name }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted)">{{ $item->case->case_title ?? 'N/A' }}</div>
                    </div>
                    <span class="badge-soft badge-{{ $cls }}">{{ $item->current_status }}</span>
                </li>
                @empty
                <li class="list-group-item" style="color:var(--text-muted)">No evidence yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
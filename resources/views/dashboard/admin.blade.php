@extends('layouts.app_v3')
@section('title', 'Admin Dashboard')
@section('page_title', 'System Administrator Dashboard')

@section('content')
<div style="margin-bottom:1.5rem">
    <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
        {{ auth()->user()->full_name }} 👋
    </div>
    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
        Manage system users, approve staff role requests, and review public submissions — {{ now()->format('l, d F Y') }}
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);color:#a78bfa">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-sub">Registered accounts</div>
        </div>
    </div>
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
            <div class="stat-sub">Investigations open/closed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Evidence Items</div>
                    <div class="stat-value">{{ $stats['total_evidence'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-sub">Tracked in custody</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-color: rgba(99,102,241,0.3)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Action Required</div>
                    <div class="stat-value" style="color: var(--accent)">
                        {{ $stats['pending_role_requests'] + $stats['pending_submissions'] }}
                    </div>
                </div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.15);color:#818cf8">
                    <i class="bi bi-bell-fill"></i>
                </div>
            </div>
            <div class="stat-sub">
                <span style="color:#fbbf24">{{ $stats['pending_role_requests'] }} requests</span> · 
                <span style="color:#60a5fa">{{ $stats['pending_submissions'] }} tips</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Role Requests --}}
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-person-check" style="color:var(--accent)"></i> Pending Role Requests
                </span>
                @if(Route::has('admin.role-requests'))
                    <a href="{{ route('admin.role-requests') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role Requested</th>
                            <th>Reason</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRoleRequests as $req)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $req->user->full_name }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">{{ $req->user->email }}</div>
                            </td>
                            <td>
                                <span class="badge-soft badge-pending">{{ $req->requestedRole->role_name }}</span>
                            </td>
                            <td style="font-size:0.78rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $req->reason }}">
                                {{ $req->reason }}
                            </td>
                            <td class="text-end">
                                @if(Route::has('admin.role-requests'))
                                    <a href="{{ route('admin.role-requests') }}" class="btn btn-secondary btn-sm px-2 py-1" style="font-size:0.72rem">
                                        Review
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-check-circle-fill text-success d-block mb-1" style="font-size:1.2rem"></i>
                                No pending role requests
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Public Submissions --}}
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-inbox" style="color:var(--accent)"></i> Recent Public Tips
                </span>
                @if(Route::has('admin.submissions'))
                    <a href="{{ route('admin.submissions') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Submitter</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubmissions as $sub)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $sub->subject }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">{{ Str::limit($sub->description, 40) }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.8rem">{{ $sub->submitter_name }}</div>
                            </td>
                            <td>
                                @php 
                                    $c = match($sub->status) {
                                        'PENDING' => 'badge-pending',
                                        'REVIEWED' => 'badge-open',
                                        'DISMISSED' => 'badge-closed',
                                        default => 'badge-closed'
                                    };
                                @endphp
                                <span class="badge-soft {{ $c }}">{{ $sub->status }}</span>
                            </td>
                            <td class="text-end">
                                @if(Route::has('admin.submissions'))
                                    <a href="{{ route('admin.submissions') }}" class="btn btn-secondary btn-sm px-2 py-1" style="font-size:0.72rem">
                                        Review
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-inbox-fill d-block mb-1" style="font-size:1.2rem"></i>
                                No public submissions logged
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

@extends('layouts.app_v3')
@section('title', 'Citizen Dashboard')
@section('page_title', 'Citizen Portal')

@section('content')
<div style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem">
    <div>
        <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
            Welcome, {{ auth()->user()->full_name }} 👋
        </div>
        <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
            Submit tips to active investigations, monitor your logs, and apply for system staff access — {{ now()->format('l, d F Y') }}
        </div>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('public.submit') }}" class="btn btn-primary">
            <i class="bi bi-send"></i> Submit new tip
        </a>
        <a href="{{ route('role-request.create') }}" class="btn btn-secondary">
            <i class="bi bi-person-badge"></i> Apply for Staff Access
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Submitted Tips --}}
    <div class="col-md-7">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-send" style="color:var(--accent)"></i> My Submitted Tips
                </span>
                <span style="font-size:0.75rem; color:var(--text-muted)">{{ $stats['my_submissions_count'] }} tips submitted</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Target Case</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mySubmissions as $sub)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.83rem">{{ $sub->subject }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">{{ Str::limit($sub->description, 60) }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.8rem">{{ $sub->relatedCase->case_title ?? 'General/No specific case' }}</div>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-inbox d-block mb-1" style="font-size:1.2rem"></i>
                                You haven't submitted any tips yet.
                                <a href="{{ route('public.submit') }}" class="d-block mt-2 text-decoration-underline" style="color:var(--accent)">Submit a Tip</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Access Requests --}}
    <div class="col-md-5">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-shield-lock" style="color:var(--accent)"></i> Staff Access Request Status
                </span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Requested Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myRequests as $req)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.82rem">{{ $req->requestedRole->role_name ?? 'Staff' }}</div>
                                <div style="font-size:0.7rem;color:var(--text-muted)">Reason: {{ Str::limit($req->reason, 40) }}</div>
                            </td>
                            <td>
                                @php 
                                    $c = match($req->status) {
                                        'PENDING' => 'badge-pending',
                                        'APPROVED' => 'badge-open',
                                        'REJECTED' => 'badge-closed',
                                        default => 'badge-closed'
                                    };
                                @endphp
                                <span class="badge-soft {{ $c }}">{{ $req->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-muted" style="font-size:0.8rem">
                                <i class="bi bi-person-badge d-block mb-1" style="font-size:1.2rem"></i>
                                No active access requests.
                                <a href="{{ route('role-request.create') }}" class="d-block mt-2 text-decoration-underline" style="color:var(--accent)">Apply for Officer/Analyst access</a>
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

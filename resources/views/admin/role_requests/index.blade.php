@extends('layouts.app_v3')

@section('title', 'Role Requests')
@section('page_title', 'Role Requests')

@section('content')
    <form method="GET" class="card p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="PENDING" {{ request('status', 'PENDING') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>Approved</option>
                    <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
        </div>
    </form>

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Requested Role</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $req->user->full_name }}</div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">{{ $req->user->email }}</div>
                        </td>
                        <td>{{ $req->requestedRole->role_name }}</td>
                        <td style="max-width:220px;">{{ Str::limit($req->reason, 60) }}</td>
                        <td><span class="badge-soft badge-{{ strtolower($req->status) === 'pending' ? 'pending' : (strtolower($req->status) === 'approved' ? 'normal' : 'urgent') }}">{{ $req->status }}</span></td>
                        <td style="font-size:0.78rem;color:var(--text-muted);">{{ $req->requested_at }}</td>
                        <td>
                            @if($req->status === 'PENDING')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('admin.role-requests.decide', $req->request_id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="decision" value="APPROVED">
                                        <button class="btn-icon" title="Approve"><i class="bi bi-check-lg" style="color:#10b981;"></i></button>
                                    </form>
                                    <form action="{{ route('admin.role-requests.decide', $req->request_id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="decision" value="REJECTED">
                                        <button class="btn-icon danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </div>
                            @else
                                <span style="font-size:0.78rem;color:var(--text-muted);">{{ $req->reviewer->full_name ?? '' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $requests->links() }}</div>
@endsection
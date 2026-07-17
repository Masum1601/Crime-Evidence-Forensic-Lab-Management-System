@extends('layouts.app_v3')

@section('title', 'Request Role')
@section('page_title', 'Request Staff Access')

@section('content')
    <div class="card p-4" style="max-width:600px;">
        @if($existingRequest)
            <div class="alert" style="background:#fef3c7;border:1px solid #fbbf24;color:#92400e;border-radius:10px;padding:1rem;">
                <i class="bi bi-hourglass-split me-1"></i>
                You already have a pending request for
                <strong>{{ $existingRequest->requestedRole->role_name }}</strong>.
                An admin needs to review it before you can request again.
            </div>
        @else
            <p style="color:var(--text-muted);font-size:0.875rem;">
                If your duties require Officer or Analyst access, submit a request below.
                An administrator will review and approve it.
            </p>
            <form action="{{ route('role-request.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Requested Role</label>
                    <select name="requested_role_id" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Explain why you need this access..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Submit Request
                </button>
            </form>
        @endif
    </div>
@endsection
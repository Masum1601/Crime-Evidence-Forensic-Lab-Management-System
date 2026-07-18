@extends('layouts.app_v3')

@section('title', 'Forensic Tests')
@section('page_title', 'Forensic Test Requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div>
        @if(auth()->user()->role->role_name !== 'Analyst')
            <a href="{{ route('tests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New Test Request
            </a>
        @endif
    </div>

    @if ($requests->isEmpty())
        <x-empty-state 
            icon="bi-eyedropper" 
            title="No Test Requests Found" 
            message="Create a forensic request to assign analysts and initiate chemical or biological testing." 
            actionUrl="{{ auth()->user()->role->role_name !== 'Analyst' ? route('tests.create') : null }}" 
            actionText="New Test Request" 
        />
    @else
    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Evidence</th>
                    <th>Test Type</th>
                    <th>Analyst</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Requested By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td>{{ $req->evidence->evidence_name ?? 'N/A' }}</td>
                        <td>{{ $req->testType->test_name ?? 'N/A' }}</td>
                        <td>{{ $req->analyst->full_name ?? '— Unassigned' }}</td>
                        <td>
                            <span class="badge-soft badge-{{ strtolower($req->priority) }}">{{ $req->priority }}</span>
                        </td>
                        <td>
                            <span class="badge-soft badge-{{ strtolower($req->test_status) === 'completed' ? 'normal' : 'pending' }}">
                                {{ $req->test_status }}
                            </span>
                        </td>
                        <td>{{ $req->requestedBy->full_name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @if($req->test_status === 'IN_PROGRESS')
                                    <a href="{{ route('tests.report.create', $req->request_id) }}" class="btn-icon" title="File Report">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                @endif

                                @if($req->test_status !== 'COMPLETED')
                                    <form action="{{ route('tests.update', $req->request_id) }}" method="POST" class="d-flex gap-1">
                                        @csrf @method('PUT')
                                        <select name="test_status" class="form-select form-select-sm" style="width:130px;">
                                            @foreach(['PENDING','IN_PROGRESS','COMPLETED','CANCELLED'] as $s)
                                                <option value="{{ $s }}" {{ $req->test_status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn-icon" title="Update"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @else
                                    <span style="font-size:0.78rem;color:var(--text-muted);">Completed</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div class="mt-3">{{ $requests->links() }}</div>
@endsection
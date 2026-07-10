@extends('layouts.app_v3')

@section('title', 'Public Submissions')
@section('page_title', 'Public Submissions')

@section('content')
    <form method="GET" class="card p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['PENDING','REVIEWED','DISMISSED'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Related Case</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $sub->submitter_name }}</div>
                                <div style="font-size:0.78rem;color:var(--text-muted);">{{ $sub->submitter_email }}</div>
                            </td>
                            <td>
                                <div>{{ $sub->subject }}</div>
                                <div style="font-size:0.78rem;color:var(--text-muted);">{{ Str::limit($sub->description, 60) }}</div>
                            </td>
                            <td>{{ $sub->relatedCase->case_title ?? '—' }}</td>
                            <td>
                                <span class="badge-soft badge-{{ strtolower($sub->status) }}">{{ $sub->status }}</span>
                            </td>
                            <td style="font-size:0.78rem;color:var(--text-muted);">{{ $sub->submitted_at }}</td>
                            <td>
                                @if($sub->status === 'PENDING')
                                    <form action="{{ route('admin.submissions.review', $sub->submission_id) }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="status" value="REVIEWED">
                                        <button class="btn-icon" title="Mark Reviewed"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @else
                                    <span style="font-size:0.78rem;color:var(--text-muted);">Done</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $submissions->links() }}</div>
@endsection
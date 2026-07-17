@extends('layouts.app_v3')

@section('title', 'Court Submissions')
@section('page_title', 'Court Submission Management')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('court.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Submit to Court
        </a>
    </div>

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Evidence</th>
                    <th>Court</th>
                    <th>Reference No.</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $s)
                    <tr>
                        <td>{{ $s->evidence->evidence_name ?? 'N/A' }}</td>
                        <td>{{ $s->court_name }}</td>
                        <td>{{ $s->case_reference_no }}</td>
                        <td style="font-size:0.78rem;color:var(--text-muted);">{{ $s->submission_date }}</td>
                        <td><span class="badge-soft badge-{{ strtolower($s->status) === 'submitted' ? 'pending' : 'normal' }}">{{ $s->status }}</span></td>
                        <td>
                            @if($s->status === 'SUBMITTED')
                                <form action="{{ route('court.update', $s->submission_id) }}" method="POST" class="d-flex gap-1">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="RETURNED">
                                    <input type="date" name="return_date" class="form-control form-control-sm" style="width:140px;" required>
                                    <button class="btn-icon" title="Mark Returned"><i class="bi bi-arrow-return-left"></i></button>
                                </form>
                            @else
                                <span style="font-size:0.78rem;color:var(--text-muted);">{{ $s->return_date }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No court submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $submissions->links() }}</div>
@endsection
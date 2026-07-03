@extends('layouts.app_v2')

@section('title', 'Chain of Custody')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-arrow-left-right me-2"></i>Chain of Custody</h3>
        <a href="{{ route('custody.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Record Transfer
        </a>
    </div>

    <form method="GET" class="card card-stat p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by evidence name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>

    <div class="card card-stat">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Evidence</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Transferred By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->evidence->evidence_name ?? 'N/A' }}</td>
                        <td>{{ $record->fromUser->full_name ?? 'Initial Collection' }}</td>
                        <td>{{ $record->toUser->full_name ?? 'N/A' }}</td>
                        <td>{{ $record->reason }}</td>
                        <td>{{ $record->transferredByUser->full_name ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $record->transfer_date }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No custody transfers recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $records->links() }}
    </div>
@endsection
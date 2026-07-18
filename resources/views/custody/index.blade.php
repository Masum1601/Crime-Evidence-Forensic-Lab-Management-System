@extends('layouts.app_v3')
@section('title', 'Chain of Custody')
@section('page_title', 'Chain of Custody')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-arrow-left-right"></i> Chain of Custody</h2>
    <a href="{{ route('custody.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Record Transfer
    </a>
</div>

<form method="GET" class="filter-bar mb-3">
    <div class="row g-2">
        <div class="col-md-10">
            <input type="text" name="search" class="form-control" placeholder="Search by evidence name…" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Search</button>
        </div>
    </div>
</form>

@if ($records->isEmpty())
    <x-empty-state 
        icon="bi-arrow-left-right" 
        title="No Custody Logs Found" 
        message="Log custody transfers to establish an audit trail for evidence verification." 
        actionUrl="{{ auth()->user()->role->role_name !== 'Analyst' ? route('custody.create') : null }}" 
        actionText="Record Transfer" 
    />
@else
<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Evidence</th>
                <th>From</th>
                <th></th>
                <th>To</th>
                <th>Reason</th>
                <th>Transferred By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
            <tr>
                <td style="font-weight:600">{{ $record->evidence->evidence_name ?? 'N/A' }}</td>
                <td style="color:var(--text-muted)">{!! $record->fromUser ? e($record->fromUser->full_name) : '<span style="font-style:italic">Crime Scene</span>' !!}</td>
                <td style="color:var(--accent)"><i class="bi bi-arrow-right"></i></td>
                <td style="font-weight:600">{{ $record->toUser->full_name ?? 'N/A' }}</td>
                <td style="color:var(--text-muted);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $record->reason }}</td>
                <td style="color:var(--text-muted)">{{ $record->transferredByUser->full_name ?? 'N/A' }}</td>
                <td style="color:var(--text-muted);font-size:0.75rem;white-space:nowrap">{{ $record->transfer_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="mt-3">{{ $records->links() }}</div>
@endsection
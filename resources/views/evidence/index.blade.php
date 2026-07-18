@extends('layouts.app_v3')
@section('title', 'Evidence')
@section('page_title', 'Evidence Management')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-box-seam"></i> Evidence</h2>
    <a href="{{ route('evidence.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Register Evidence
    </a>
</div>

{{-- Filter bar --}}
<form method="GET" class="filter-bar mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by name, type, or barcode…" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach (['IN_STORAGE','IN_ANALYSIS','IN_TRANSIT','RELEASED','DISPOSED'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </div>
</form>

{{-- Table --}}
@if ($evidenceItems->isEmpty())
    <x-empty-state 
        icon="bi-box-seam" 
        title="No Evidence Registered" 
        message="Log your first physical or digital evidence item to track its custody history." 
        actionUrl="{{ route('evidence.create') }}" 
        actionText="Register Evidence" 
    />
@else
<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Name</th>
                <th>Type</th>
                <th>Case</th>
                <th>Status</th>
                <th>Location</th>
                <th style="width:110px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($evidenceItems as $item)
            @php
                $statusMap = ['IN_STORAGE'=>'storage','IN_ANALYSIS'=>'analysis','IN_TRANSIT'=>'transit','RELEASED'=>'released','DISPOSED'=>'disposed'];
                $cls = $statusMap[$item->current_status] ?? 'closed';
            @endphp
            <tr>
                <td><code>{{ $item->barcode_no ?? '—' }}</code></td>
                <td style="font-weight:600">{{ $item->evidence_name }}</td>
                <td style="color:var(--text-muted)">{{ $item->evidence_type ?? '—' }}</td>
                <td>{{ $item->case->case_title ?? 'N/A' }}</td>
                <td><span class="badge-soft badge-{{ $cls }}">{{ $item->current_status }}</span></td>
                <td style="color:var(--text-muted)">{{ $item->location->location_name ?? '—' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('evidence.show', $item->evidence_id) }}" class="btn-action view" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('evidence.edit', $item->evidence_id) }}" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('evidence.destroy', $item->evidence_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this evidence item?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="mt-3">{{ $evidenceItems->links() }}</div>
@endsection
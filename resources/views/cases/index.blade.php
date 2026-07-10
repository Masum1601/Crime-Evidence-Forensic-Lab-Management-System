@extends('layouts.app_v3')
@section('title', 'Cases')
@section('page_title', 'Case Management')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-folder2-open"></i> Cases</h2>
    <a href="{{ route('cases.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Open New Case
    </a>
</div>

<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Officer</th>
                <th>Opened</th>
                <th style="width:90px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cases as $case)
            @php
                $cls = match($case->case_status) { 'OPEN'=>'open','CLOSED'=>'closed', default=>'pending' };
            @endphp
            <tr>
                <td style="color:var(--text-muted);font-size:0.75rem">{{ $case->case_id }}</td>
                <td style="font-weight:600">{{ $case->case_title }}</td>
                <td style="color:var(--text-muted)">{{ $case->case_type ?? '—' }}</td>
                <td><span class="badge-soft badge-{{ $cls }}">{{ $case->case_status }}</span></td>
                <td>{{ $case->officer->full_name ?? 'N/A' }}</td>
                <td style="color:var(--text-muted);font-size:0.78rem">{{ $case->opened_date }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('cases.edit', $case->case_id) }}" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('cases.destroy', $case->case_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this case?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-5" style="color:var(--text-muted)">
                <i class="bi bi-folder" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.4"></i>
                No cases found.
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
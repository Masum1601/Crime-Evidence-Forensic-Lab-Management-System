@extends('layouts.app_v2')

@section('title', 'Evidence')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-box-seam me-2"></i>Evidence</h3>
        <a href="{{ route('evidence.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Register Evidence
        </a>
    </div>

    <form method="GET" class="card card-stat p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by name, type, or barcode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach (['IN_STORAGE', 'IN_ANALYSIS', 'IN_TRANSIT', 'RELEASED', 'DISPOSED'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </div>
    </form>

    <div class="card card-stat">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Barcode</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Case</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evidenceItems as $item)
                    <tr>
                        <td><code>{{ $item->barcode_no }}</code></td>
                        <td>{{ $item->evidence_name }}</td>
                        <td>{{ $item->evidence_type }}</td>
                        <td>{{ $item->case->case_title ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info badge-status">{{ $item->current_status }}</span>
                        </td>
                        <td>{{ $item->location->location_name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('evidence.show', $item->evidence_id) }}" class="btn btn-sm btn-outline-info" title="View / Custody History">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('evidence.edit', $item->evidence_id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('evidence.destroy', $item->evidence_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this evidence item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No evidence records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $evidenceItems->links() }}
    </div>
@endsection
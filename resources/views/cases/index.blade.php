@extends('layouts.app_v2')

@section('title', 'Cases')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-folder2-open me-2"></i>Cases</h3>
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Case
        </a>
    </div>

    <div class="card card-stat">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Officer</th>
                    <th>Opened Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cases as $case)
                    <tr>
                        <td>{{ $case->case_id }}</td>
                        <td>{{ $case->case_title }}</td>
                        <td>{{ $case->case_type }}</td>
                        <td>
                            <span class="badge bg-{{ $case->case_status === 'OPEN' ? 'success' : ($case->case_status === 'CLOSED' ? 'secondary' : 'warning') }} badge-status">
                                {{ $case->case_status }}
                            </span>
                        </td>
                        <td>{{ $case->officer->full_name ?? 'N/A' }}</td>
                        <td>{{ $case->opened_date }}</td>
                        <td>
                            <a href="{{ route('cases.edit', $case->case_id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('cases.destroy', $case->case_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this case?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No cases found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
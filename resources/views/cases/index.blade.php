@extends('layouts.app')

@section('title', 'Cases')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Cases</h2>
        <a href="{{ route('cases.create') }}" class="btn btn-primary">+ Add Case</a>
    </div>

    <table class="table table-bordered bg-white">
        <thead>
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
                        <span class="badge bg-{{ $case->case_status === 'OPEN' ? 'success' : ($case->case_status === 'CLOSED' ? 'secondary' : 'warning') }}">
                            {{ $case->case_status }}
                        </span>
                    </td>
                    <td>{{ $case->officer->full_name ?? 'N/A' }}</td>
                    <td>{{ $case->opened_date }}</td>
                    <td>
                        <a href="{{ route('cases.edit', $case->case_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('cases.destroy', $case->case_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this case?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No cases found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

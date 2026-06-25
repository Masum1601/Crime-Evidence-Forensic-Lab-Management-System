@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Users</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
    </div>

    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->role_name ?? 'N/A' }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        <span class="badge bg-{{ $user->status === 'ACTIVE' ? 'success' : 'secondary' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

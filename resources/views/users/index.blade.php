@extends('layouts.app_v3')

@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Change Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge-soft role-{{ strtolower($user->role->role_name ?? '') }}">{{ $user->role->role_name ?? 'N/A' }}</span></td>
                        <td>
                            <span class="badge-soft badge-{{ $user->status === 'ACTIVE' ? 'normal' : 'closed' }}">{{ $user->status }}</span>
                        </td>
                        <td>
                            <form action="{{ route('users.change-role', $user->user_id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="role_id" class="form-select form-select-sm" style="width:120px;" onchange="this.form.submit()">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->role_id }}" {{ $user->role_id == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('users.toggle-status', $user->user_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn-icon" title="Toggle Active/Inactive">
                                    @if ($user->status === 'ACTIVE')
                                        <i class="bi bi-toggle-on text-success" style="font-size: 1.15rem;"></i>
                                    @else
                                        <i class="bi bi-toggle-off text-muted" style="font-size: 1.15rem;"></i>
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('users.edit', $user->user_id) }}" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                @csrf @method('DELETE')
                                <button class="btn-icon danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
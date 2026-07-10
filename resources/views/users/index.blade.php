@extends('layouts.app_v3')
@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-people"></i> Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Add User
    </a>
</div>

<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                <th style="width:90px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            @php $rn = strtolower($user->role?->role_name ?? ''); @endphp
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.75rem;flex-shrink:0">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>
                        <span style="font-weight:600">{{ $user->full_name }}</span>
                    </div>
                </td>
                <td style="color:var(--text-muted)">{{ $user->email }}</td>
                <td><span class="user-role role-{{ $rn }}" style="font-size:0.68rem;padding:2px 7px;border-radius:5px;font-weight:700">{{ $user->role?->role_name ?? '—' }}</span></td>
                <td style="color:var(--text-muted)">{{ $user->phone ?? '—' }}</td>
                <td>
                    <span class="badge-soft {{ $user->status === 'ACTIVE' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $user->status }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('users.edit', $user->user_id) }}" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5" style="color:var(--text-muted)">
                <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.4"></i>
                No users found.
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Add User')

@section('content')
    <h2>Add User</h2>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="ACTIVE">ACTIVE</option>
                <option value="INACTIVE">INACTIVE</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

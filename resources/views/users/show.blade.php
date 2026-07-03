@extends('layouts.app_v2')

@section('title', 'User Profile')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-person-circle me-2"></i>User Profile</h3>
        <div>
            <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-outline-warning btn-sm me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="card card-stat p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Full Name</div>
                <div class="fw-semibold">{{ $user->full_name }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold">{{ $user->email }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Role</div>
                <div class="fw-semibold">{{ $user->role->role_name ?? 'N/A' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Phone</div>
                <div class="fw-semibold">{{ $user->phone ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Status</div>
                <span class="badge bg-{{ $user->status === 'ACTIVE' ? 'success' : 'secondary' }} badge-status">
                    {{ $user->status }}
                </span>
            </div>
        </div>
    </div>
@endsection

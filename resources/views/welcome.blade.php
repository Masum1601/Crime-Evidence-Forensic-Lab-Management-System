@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="p-5 bg-white rounded shadow-sm">
        <h1>Crime Evidence & Forensic Lab Management System</h1>
        <a href="{{ route('users.index') }}" class="btn btn-primary me-2">Manage Users</a>
        <a href="{{ route('cases.index') }}" class="btn btn-secondary">Manage Cases</a>
    </div>
@endsection

@extends('layouts.app_v3')

@section('title', 'Add Case')

@section('content')
    <h2>Add Case</h2>

    <form action="{{ route('cases.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Case Title</label>
            <input type="text" name="case_title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Case Type</label>
            <input type="text" name="case_type" class="form-control" placeholder="e.g. Robbery, Assault, Fraud">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="case_description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="case_status" class="form-select" required>
                <option value="OPEN">OPEN</option>
                <option value="PENDING">PENDING</option>
                <option value="CLOSED">CLOSED</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Assigned Officer</label>
            <select name="officer_id" class="form-select" required>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->user_id }}">{{ $officer->full_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('cases.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

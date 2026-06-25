@extends('layouts.app')

@section('title', 'Edit Case')

@section('content')
    <h2>Edit Case</h2>

    <form action="{{ route('cases.update', $case->case_id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Case Title</label>
            <input type="text" name="case_title" class="form-control" value="{{ $case->case_title }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Case Type</label>
            <input type="text" name="case_type" class="form-control" value="{{ $case->case_type }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="case_description" class="form-control" rows="3">{{ $case->case_description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="case_status" class="form-select" required>
                <option value="OPEN" {{ $case->case_status === 'OPEN' ? 'selected' : '' }}>OPEN</option>
                <option value="PENDING" {{ $case->case_status === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                <option value="CLOSED" {{ $case->case_status === 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Assigned Officer</label>
            <select name="officer_id" class="form-select" required>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->user_id }}" {{ $case->officer_id == $officer->user_id ? 'selected' : '' }}>
                        {{ $officer->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('cases.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection

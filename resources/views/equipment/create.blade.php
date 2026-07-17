@extends('layouts.app_v3')

@section('title', 'Add Equipment')
@section('page_title', 'Add Equipment')

@section('content')
    <form action="{{ route('equipment.store') }}" method="POST" class="card p-4" style="max-width:600px;">
        @csrf
        <div class="mb-3">
            <label class="form-label">Equipment Name</label>
            <input type="text" name="equipment_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="equipment_type" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Serial No.</label>
            <input type="text" name="serial_no" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button>
        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
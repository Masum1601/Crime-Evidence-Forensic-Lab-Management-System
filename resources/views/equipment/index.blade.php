@extends('layouts.app_v3')

@section('title', 'Equipment')
@section('page_title', 'Equipment Management')

@section('content')
    @if(auth()->user()->role->role_name === 'Admin')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Equipment
        </a>
    </div>
    @endif

    @if ($equipment->isEmpty())
        <x-empty-state 
            icon="bi-tools" 
            title="No Equipment Logged" 
            message="Register lab equipment to track serial numbers, condition, and utilization." 
            actionUrl="{{ auth()->user()->role->role_name === 'Admin' ? route('equipment.create') : null }}" 
            actionText="Add Equipment" 
        />
    @else
    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Serial No.</th>
                    <th>Condition</th>
                    <th>Availability</th>
                    @if(in_array(auth()->user()->role->role_name, ['Admin', 'Analyst']))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($equipment as $eq)
                    <tr>
                        <td>{{ $eq->equipment_name }}</td>
                        <td>{{ $eq->equipment_type }}</td>
                        <td><code>{{ $eq->serial_no }}</code></td>
                        <td><span class="badge-soft badge-{{ $eq->condition_status === 'GOOD' ? 'normal' : 'urgent' }}">{{ $eq->condition_status }}</span></td>
                        <td><span class="badge-soft badge-{{ $eq->availability_status === 'AVAILABLE' ? 'normal' : 'pending' }}">{{ $eq->availability_status }}</span></td>
                        @if(in_array(auth()->user()->role->role_name, ['Admin', 'Analyst']))
                        <td>
                            @if($eq->availability_status === 'AVAILABLE')
                                <form action="{{ route('equipment.use', $eq->equipment_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn-icon" title="Log Usage"><i class="bi bi-play-circle"></i></button>
                                </form>
                            @else
                                <form action="{{ route('equipment.release', $eq->equipment_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn-icon" title="Release"><i class="bi bi-stop-circle"></i></button>
                                </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div class="mt-3">{{ $equipment->links() }}</div>
@endsection
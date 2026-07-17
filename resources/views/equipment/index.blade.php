@extends('layouts.app_v3')

@section('title', 'Equipment')
@section('page_title', 'Equipment Management')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Equipment
        </a>
    </div>

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Serial No.</th>
                    <th>Condition</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $eq)
                    <tr>
                        <td>{{ $eq->equipment_name }}</td>
                        <td>{{ $eq->equipment_type }}</td>
                        <td><code>{{ $eq->serial_no }}</code></td>
                        <td><span class="badge-soft badge-{{ $eq->condition_status === 'GOOD' ? 'normal' : 'urgent' }}">{{ $eq->condition_status }}</span></td>
                        <td><span class="badge-soft badge-{{ $eq->availability_status === 'AVAILABLE' ? 'normal' : 'pending' }}">{{ $eq->availability_status }}</span></td>
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
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No equipment logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $equipment->links() }}</div>
@endsection
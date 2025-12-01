@extends('layouts.app')

@section('page-title', 'Maintenance Records')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Maintenance Records</h2>
        <p class="text-muted">Track all your vehicle service history</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Log Maintenance
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Service Type</th>
                        <th>Service Center</th>
                        <th>Cost</th>
                        <th>Next Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenanceRecords as $record)
                        <tr>
                            <td>{{ $record->service_date->format('M d, Y') }}</td>
                            <td>
                                <strong>{{ $record->vehicle->vehicle_number }}</strong><br>
                                <small class="text-muted">{{ $record->vehicle->brand }} {{ $record->vehicle->model }}</small>
                            </td>
                            <td>{{ $record->service_type }}</td>
                            <td>{{ $record->service_center ?? '-' }}</td>
                            <td>{{ $record->cost ? '$' . number_format($record->cost, 2) : '-' }}</td>
                            <td>
                                @if($record->next_service_date)
                                    {{ $record->next_service_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('maintenance.edit', $record) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('maintenance.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this maintenance record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No maintenance records yet</p>
                                <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Log Your First Maintenance
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($maintenanceRecords->hasPages())
            <div class="mt-3">
                {{ $maintenanceRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

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

{{-- Flash handled by SweetAlert2 in layout --}}

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
                        <th>Next Service Date</th>
                        <th>Documents</th>
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
                                @if($record->next_due_date)
                                    {{ $record->next_due_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($record->service_bill)
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#serviceBillModal{{ $record->id }}" title="Service Bill">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                    @endif
                                    @if($record->related_document)
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#relatedDocModal{{ $record->id }}" title="Related Document">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                    @endif
                                    @if(!$record->service_bill && !$record->related_document)
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('maintenance.edit', $record) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('maintenance.destroy', $record) }}" method="POST" class="d-inline swal-confirm" data-swal-message="Are you sure you want to delete this maintenance record?">
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
                            <td colspan="8" class="text-center py-5">
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

<!-- Document Modals -->
@foreach($maintenanceRecords as $record)
    @if($record->service_bill)
    <div class="modal fade" id="serviceBillModal{{ $record->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Service Bill - {{ $record->vehicle->vehicle_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Storage::url($record->service_bill) }}" class="img-fluid" alt="Service Bill">
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($record->related_document)
    <div class="modal fade" id="relatedDocModal{{ $record->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Related Document - {{ $record->vehicle->vehicle_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Storage::url($record->related_document) }}" class="img-fluid" alt="Related Document">
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection

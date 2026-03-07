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
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#maintenanceDetailsModal{{ $record->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
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

<!-- Document & Details Modals -->
@foreach($maintenanceRecords as $record)
    <!-- Maintenance Details Modal -->
    <div class="modal fade" id="maintenanceDetailsModal{{ $record->id }}" tabindex="-1" aria-labelledby="maintenanceDetailsModalLabel{{ $record->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="maintenanceDetailsModalLabel{{ $record->id }}">
                        <i class="fas fa-wrench me-2"></i>Maintenance Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary border-bottom pb-2">Vehicle Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Vehicle:</th>
                                    <td>{{ $record->vehicle->brand }} {{ $record->vehicle->model }}</td>
                                </tr>
                                <tr>
                                    <th>Reg Number:</th>
                                    <td><strong>{{ $record->vehicle->vehicle_number }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary border-bottom pb-2">Service Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Service Type:</th>
                                    <td>{{ $record->service_type }}</td>
                                </tr>
                                <tr>
                                    <th>Service Date:</th>
                                    <td>{{ $record->service_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Mileage:</th>
                                    <td>{{ $record->mileage ? number_format($record->mileage) . ' km' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Cost:</th>
                                    <td><strong>{{ $record->cost ? '$' . number_format($record->cost, 2) : 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Service Center:</th>
                                    <td>{{ $record->service_center ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($record->next_due_date)
                    <div class="alert alert-info py-2 mb-3">
                        <i class="fas fa-calendar-alt me-2"></i>Next service recommended by: <strong>{{ $record->next_due_date->format('M d, Y') }}</strong>
                    </div>
                    @endif

                    @if($record->notes)
                    <div class="mt-3">
                        <h6 class="text-primary">Service Notes:</h6>
                        <p class="text-muted small p-2 bg-light rounded">{{ $record->notes }}</p>
                    </div>
                    @endif

                    @if($record->service_bill || $record->related_document)
                    <div class="mt-4">
                        <h6 class="text-primary">Attached Documents:</h6>
                        <div class="d-flex gap-3 mt-2">
                            @if($record->service_bill)
                            <div class="text-center">
                                <a href="{{ Storage::url($record->service_bill) }}" target="_blank" class="d-block mb-1">
                                    <img src="{{ Storage::url($record->service_bill) }}" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Service Bill">
                                </a>
                                <small class="text-muted">Service Bill</small>
                            </div>
                            @endif
                            @if($record->related_document)
                            <div class="text-center">
                                <a href="{{ Storage::url($record->related_document) }}" target="_blank" class="d-block mb-1">
                                    <img src="{{ Storage::url($record->related_document) }}" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Related Document">
                                </a>
                                <small class="text-muted">Other Document</small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('maintenance.edit', $record) }}" class="btn btn-primary">Edit Record</a>
                </div>
            </div>
        </div>
    </div>

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

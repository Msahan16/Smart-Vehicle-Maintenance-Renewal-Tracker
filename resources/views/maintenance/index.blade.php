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

<div class="card-custom border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Date</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Service Type</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Service Center</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Cost</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Next Service</th>
                        <th class="py-3 text-uppercase font-size-12 fw-bold text-center" style="font-size: 0.75rem; letter-spacing: 0.05em;">Docs</th>
                        <th class="pe-4 py-3 text-uppercase font-size-12 fw-bold text-end" style="font-size: 0.75rem; letter-spacing: 0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($maintenanceRecords as $record)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold text-dark">{{ $record->service_date->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-blue-50 text-blue-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; background: #eff6ff; color: #1e40af;">
                                        <i class="fas fa-car-side" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $record->vehicle->vehicle_number }}</div>
                                        <div class="text-muted small">{{ $record->vehicle->brand }} {{ $record->vehicle->model }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill fw-medium" style="background: #f1f5f9; color: #475569; padding: 6px 12px;">
                                    {{ $record->service_type }}
                                </span>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $record->service_center ?? '—' }}</span>
                            </td>
                            <td>
                                @if($record->cost)
                                    <span class="fw-bold text-dark">${{ number_format($record->cost, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($record->next_due_date)
                                    @php
                                        $isOverdue = $record->next_due_date->isPast();
                                        $isSoon = !$isOverdue && $record->next_due_date->diffInDays(now()) <= 14;
                                        $color = $isOverdue ? '#ef4444' : ($isSoon ? '#f59e0b' : '#64748b');
                                    @endphp
                                    <span class="small fw-medium" style="color: {{ $color }};">
                                        <i class="far fa-calendar-alt me-1"></i>{{ $record->next_due_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @if($record->service_bill)
                                        <a href="#" class="text-info hover-opacity" data-bs-toggle="modal" data-bs-target="#serviceBillModal{{ $record->id }}" title="Bill">
                                            <i class="fas fa-file-invoice" style="background: #e0f2fe; padding: 8px; border-radius: 6px;"></i>
                                        </a>
                                    @endif
                                    @if($record->related_document)
                                        <a href="#" class="text-info hover-opacity" data-bs-toggle="modal" data-bs-target="#relatedDocModal{{ $record->id }}" title="Doc">
                                            <i class="fas fa-file-alt" style="background: #e0f2fe; padding: 8px; border-radius: 6px;"></i>
                                        </a>
                                    @endif
                                    @if(!$record->service_bill && !$record->related_document)
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary border-0 bg-light-hover" data-bs-toggle="modal" data-bs-target="#maintenanceDetailsModal{{ $record->id }}" title="View" style="padding: 6px 10px;">
                                        <i class="far fa-eye text-primary"></i>
                                    </button>
                                    <a href="{{ route('maintenance.edit', $record) }}" class="btn btn-sm btn-outline-primary border-0 bg-light-hover" title="Edit" style="padding: 6px 10px;">
                                        <i class="far fa-edit text-primary"></i>
                                    </a>
                                    <form action="{{ route('maintenance.destroy', $record) }}" method="POST" class="d-inline swal-confirm m-0" data-swal-message="Are you sure?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 bg-light-hover" title="Delete" style="padding: 6px 10px;">
                                            <i class="far fa-trash-alt text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-wrench fa-3x text-muted mb-3 opacity-25"></i>
                                    <h5 class="text-muted fw-normal">No maintenance records found</h5>
                                    <a href="{{ route('maintenance.create') }}" class="btn btn-primary mt-3 px-4">
                                        <i class="fas fa-plus me-2"></i>Log First Service
                                    </a>
                                </div>
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

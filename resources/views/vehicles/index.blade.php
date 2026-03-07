@extends('layouts.app')

@section('page-title', 'My Vehicles')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>My Vehicles</h2>
        <p class="text-muted">Manage all your vehicles in one place</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Vehicle
        </a>
    </div>
</div>

{{-- Flash handled by SweetAlert2 in layout --}}

<div class="row">
    @forelse(auth()->user()->vehicles as $vehicle)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card-custom h-100">
                @php
                    // Resolve photo URL: support absolute URLs, storage paths, and public/storage
                    $photoUrl = null;
                    if (!empty($vehicle->photo)) {
                        if (preg_match('/^https?:\/\//', $vehicle->photo)) {
                            $photoUrl = $vehicle->photo;
                        } else {
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($vehicle->photo)) {
                                    $photoUrl = \Illuminate\Support\Facades\Storage::url($vehicle->photo);
                                } elseif (file_exists(public_path('storage/' . ltrim($vehicle->photo, '/')))) {
                                    $photoUrl = asset('storage/' . ltrim($vehicle->photo, '/'));
                                } else {
                                    // sometimes the stored path already contains 'public/' prefix
                                    $maybe = preg_replace('#^public/#', '', $vehicle->photo);
                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($maybe)) {
                                        $photoUrl = \Illuminate\Support\Facades\Storage::url($maybe);
                                    }
                                }
                            } catch (Exception $e) {
                                $photoUrl = null;
                            }
                        }
                    }
                @endphp

                @if(!empty($photoUrl))
                    <img src="{{ $photoUrl }}" class="card-img-top" alt="{{ $vehicle->brand }}" style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                @else
                    <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-car fa-4x text-white"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">{{ $vehicle->brand }} {{ $vehicle->model }}</h5>
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-id-card me-2"></i>{{ $vehicle->vehicle_number }}
                        </p>
                        <div>
                            <span class="badge bg-info me-1">{{ $vehicle->fuel_type }}</span>
                            <span class="badge bg-secondary">{{ $vehicle->manufactured_year }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small"><i class="fas fa-id-card-alt me-1"></i>License:</span>
                            @if($vehicle->license_expiry)
                                @php
                                    $daysLeft = now()->diffInDays($vehicle->license_expiry, false);
                                    $badgeClass = $daysLeft < 0 ? 'overdue' : ($daysLeft <= 30 ? 'due-soon' : 'safe');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $vehicle->license_expiry->format('M d, Y') }}</span>
                            @else
                                <span class="badge bg-secondary">Not set</span>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="fas fa-shield-alt me-1"></i>Insurance:</span>
                            @if($vehicle->insurance_expiry)
                                @php
                                    $daysLeft = now()->diffInDays($vehicle->insurance_expiry, false);
                                    $badgeClass = $daysLeft < 0 ? 'overdue' : ($daysLeft <= 30 ? 'due-soon' : 'safe');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $vehicle->insurance_expiry->format('M d, Y') }}</span>
                            @else
                                <span class="badge bg-secondary">Not set</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#vehicleDetailsModal{{ $vehicle->id }}">
                                <i class="fas fa-eye me-1"></i>View Details
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-outline-primary w-100" title="Edit Vehicle">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('vehicles.documents', $vehicle) }}" class="btn btn-outline-info w-100" title="Manage Documents">
                                        <i class="fas fa-file-alt me-1"></i>Documents
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="swal-confirm" data-swal-message="Are you sure you want to delete this vehicle?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" title="Delete Vehicle">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Details Modal -->
        <div class="modal fade" id="vehicleDetailsModal{{ $vehicle->id }}" tabindex="-1" aria-labelledby="vehicleDetailsModalLabel{{ $vehicle->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vehicleDetailsModalLabel{{ $vehicle->id }}">
                            <i class="fas fa-car me-2"></i>{{ $vehicle->brand }} {{ $vehicle->model }} Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                @if(!empty($photoUrl))
                                    <img src="{{ $photoUrl }}" class="img-fluid rounded shadow-sm" alt="{{ $vehicle->brand }}">
                                @else
                                    <div class="rounded shadow-sm d-flex align-items-center justify-content-center bg-light" style="height: 250px;">
                                        <i class="fas fa-car fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Vehicle Number:</th>
                                            <td><strong>{{ $vehicle->vehicle_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Fuel Type:</th>
                                            <td>{{ $vehicle->fuel_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Engine Capacity:</th>
                                            <td>{{ $vehicle->engine_capacity ?? 'N/A' }} cc</td>
                                        </tr>
                                        <tr>
                                            <th>Manufactured Year:</th>
                                            <td>{{ $vehicle->manufactured_year }}</td>
                                        </tr>
                                        <tr>
                                            <th>Color:</th>
                                            <td>{{ $vehicle->color ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>License Expiry:</th>
                                            <td>
                                                @if($vehicle->license_expiry)
                                                    @php
                                                        $L_daysLeft = now()->diffInDays($vehicle->license_expiry, false);
                                                        $L_badgeClass = $L_daysLeft < 0 ? 'overdue' : ($L_daysLeft <= 30 ? 'due-soon' : 'safe');
                                                    @endphp
                                                    <span class="badge {{ $L_badgeClass }}">{{ $vehicle->license_expiry->format('M d, Y') }}</span>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Insurance Expiry:</th>
                                            <td>
                                                @if($vehicle->insurance_expiry)
                                                    @php
                                                        $I_daysLeft = now()->diffInDays($vehicle->insurance_expiry, false);
                                                        $I_badgeClass = $I_daysLeft < 0 ? 'overdue' : ($I_daysLeft <= 30 ? 'due-soon' : 'safe');
                                                    @endphp
                                                    <span class="badge {{ $I_badgeClass }}">{{ $vehicle->insurance_expiry->format('M d, Y') }}</span>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($vehicle->emission_test_expiry)
                                        <tr>
                                            <th>Emission Expiry:</th>
                                            <td><span class="badge bg-secondary">{{ $vehicle->emission_test_expiry->format('M d, Y') }}</span></td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        @if($vehicle->notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2 text-primary"></i>Notes:</h6>
                            <p class="text-muted small p-2 bg-light rounded">{{ $vehicle->notes }}</p>
                        </div>
                        @endif

                        <div class="mt-4">
                            <h6><i class="fas fa-history me-2 text-primary"></i>Recent Maintenance:</h6>
                            @if($vehicle->maintenanceRecords->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($vehicle->maintenanceRecords->sortByDesc('service_date')->take(3) as $record)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $record->service_type }}</span>
                                                <small class="text-muted">{{ $record->service_date->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small">No maintenance records found.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-primary">Edit Vehicle</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body text-center py-5">
                    <i class="fas fa-car fa-4x text-muted mb-3"></i>
                    <h4>No Vehicles Yet</h4>
                    <p class="text-muted">Start by adding your first vehicle</p>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Add Your First Vehicle
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection

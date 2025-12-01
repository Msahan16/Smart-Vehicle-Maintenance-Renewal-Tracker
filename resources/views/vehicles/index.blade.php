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

@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    @forelse(auth()->user()->vehicles as $vehicle)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card-custom">
                @if($vehicle->photo)
                    <img src="{{ Storage::url($vehicle->photo) }}" class="card-img-top" alt="{{ $vehicle->brand }}" style="height: 200px; object-fit: cover; border-radius: 15px 15px 0 0;">
                @else
                    <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-car fa-4x text-white"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $vehicle->brand }} {{ $vehicle->model }}</h5>
                    <p class="text-muted mb-2">
                        <i class="fas fa-id-card me-2"></i>{{ $vehicle->vehicle_number }}
                    </p>
                    <p class="mb-2">
                        <span class="badge bg-info">{{ $vehicle->fuel_type }}</span>
                        <span class="badge bg-secondary">{{ $vehicle->manufactured_year }}</span>
                    </p>
                    
                    <hr>
                    
                    <div class="mb-2">
                        <strong>License:</strong>
                        @if($vehicle->license_expiry)
                            @php
                                $daysLeft = now()->diffInDays($vehicle->license_expiry, false);
                                $badgeClass = $daysLeft < 0 ? 'overdue' : ($daysLeft <= 30 ? 'due-soon' : 'safe');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $vehicle->license_expiry->format('M d, Y') }}</span>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Insurance:</strong>
                        @if($vehicle->insurance_expiry)
                            @php
                                $daysLeft = now()->diffInDays($vehicle->insurance_expiry, false);
                                $badgeClass = $daysLeft < 0 ? 'overdue' : ($daysLeft <= 30 ? 'due-soon' : 'safe');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $vehicle->insurance_expiry->format('M d, Y') }}</span>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
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

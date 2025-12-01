@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit text-primary"></i> Edit Vehicle</h2>
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Vehicles
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @livewire('vehicle-form', ['vehicle' => $vehicle])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

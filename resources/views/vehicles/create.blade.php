@extends('layouts.app')

@section('page-title', isset($vehicle) ? 'Edit Vehicle' : 'Add New Vehicle')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-car me-2"></i>{{ isset($vehicle) ? 'Edit Vehicle' : 'Add New Vehicle' }}
            </div>
            <div class="card-body">
                @livewire('vehicle-form', ['id' => $vehicle->id ?? null])
            </div>
        </div>
    </div>
</div>
@endsection

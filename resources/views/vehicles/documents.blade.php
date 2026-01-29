@extends('layouts.app')

@section('page-title', 'Vehicle Documents - ' . $vehicle->vehicle_number)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-alt text-primary me-2"></i>Vehicle Documents</h2>
                <p class="text-muted">{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->vehicle_number }}</p>
            </div>
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Vehicles
            </a>
        </div>
    </div>

    {{-- Flash handled by SweetAlert2 in layout --}}

    <!-- Vehicle License Documents -->
    <div class="col-lg-6 mb-4">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-id-card me-2"></i>Vehicle License Documents
            </div>
            <div class="card-body">
                <form action="{{ route('vehicles.documents.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="document_type" value="vehicle_license">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Front Side</label>
                            @if($vehicle->vehicle_license_front)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->vehicle_license_front) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->vehicle_license_front) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="vehicle_license_front" class="form-control @error('vehicle_license_front') is-invalid @enderror" accept="image/*">
                            @error('vehicle_license_front')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Back Side</label>
                            @if($vehicle->vehicle_license_back)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->vehicle_license_back) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->vehicle_license_back) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="vehicle_license_back" class="form-control @error('vehicle_license_back') is-invalid @enderror" accept="image/*">
                            @error('vehicle_license_back')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid d-md-block">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Vehicle License
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Insurance Documents -->
    <div class="col-lg-6 mb-4">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-shield-alt me-2"></i>Insurance Documents
            </div>
            <div class="card-body">
                <form action="{{ route('vehicles.documents.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="document_type" value="insurance">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Front Side</label>
                            @if($vehicle->insurance_doc_front)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->insurance_doc_front) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->insurance_doc_front) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="insurance_doc_front" class="form-control @error('insurance_doc_front') is-invalid @enderror" accept="image/*">
                            @error('insurance_doc_front')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Back Side</label>
                            @if($vehicle->insurance_doc_back)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->insurance_doc_back) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->insurance_doc_back) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="insurance_doc_back" class="form-control @error('insurance_doc_back') is-invalid @enderror" accept="image/*">
                            @error('insurance_doc_back')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid d-md-block">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Insurance Documents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Emission Test Documents -->
    <div class="col-lg-6 mb-4">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-cloud me-2"></i>Emission Test Documents
            </div>
            <div class="card-body">
                <form action="{{ route('vehicles.documents.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="document_type" value="emission_test">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Front Side</label>
                            @if($vehicle->emission_test_doc_front)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->emission_test_doc_front) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->emission_test_doc_front) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="emission_test_doc_front" class="form-control @error('emission_test_doc_front') is-invalid @enderror" accept="image/*">
                            @error('emission_test_doc_front')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-file-image me-1"></i>Back Side</label>
                            @if($vehicle->emission_test_doc_back)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vehicle->emission_test_doc_back) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $vehicle->emission_test_doc_back) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="emission_test_doc_back" class="form-control @error('emission_test_doc_back') is-invalid @enderror" accept="image/*">
                            @error('emission_test_doc_back')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid d-md-block">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Emission Test Documents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('page-title', 'Edit Maintenance')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-wrench me-2"></i>Edit Maintenance Record
            </div>
            <div class="card-body">
                <form action="{{ route('maintenance.update', $maintenance) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $maintenance->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                            <select name="service_type" id="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                                <option value="">Select Service Type</option>
                                <option value="Oil Change" {{ old('service_type', $maintenance->service_type) == 'Oil Change' ? 'selected' : '' }}>Oil Change</option>
                                <option value="Tire Rotation" {{ old('service_type', $maintenance->service_type) == 'Tire Rotation' ? 'selected' : '' }}>Tire Rotation</option>
                                <option value="Brake Service" {{ old('service_type', $maintenance->service_type) == 'Brake Service' ? 'selected' : '' }}>Brake Service</option>
                                <option value="Battery Replacement" {{ old('service_type', $maintenance->service_type) == 'Battery Replacement' ? 'selected' : '' }}>Battery Replacement</option>
                                <option value="Air Filter" {{ old('service_type', $maintenance->service_type) == 'Air Filter' ? 'selected' : '' }}>Air Filter</option>
                                <option value="Transmission Service" {{ old('service_type', $maintenance->service_type) == 'Transmission Service' ? 'selected' : '' }}>Transmission Service</option>
                                <option value="Engine Tune-up" {{ old('service_type', $maintenance->service_type) == 'Engine Tune-up' ? 'selected' : '' }}>Engine Tune-up</option>
                                <option value="General Inspection" {{ old('service_type', $maintenance->service_type) == 'General Inspection' ? 'selected' : '' }}>General Inspection</option>
                                <option value="Other" {{ old('service_type', $maintenance->service_type) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('service_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_date" class="form-label">Service Date <span class="text-danger">*</span></label>
                            <input type="date" name="service_date" id="service_date" class="form-control @error('service_date') is-invalid @enderror" value="{{ old('service_date', $maintenance->service_date->format('Y-m-d')) }}" required>
                            @error('service_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="next_service_date" class="form-label">Next Service Date</label>
                            <input type="date" name="next_service_date" id="next_service_date" class="form-control @error('next_service_date') is-invalid @enderror" value="{{ old('next_service_date', $maintenance->next_due_date?->format('Y-m-d')) }}">
                            @error('next_service_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mileage" class="form-label">Mileage (km)</label>
                            <input type="number" name="mileage" id="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage', $maintenance->mileage) }}" min="0">
                            @error('mileage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $maintenance->cost) }}" step="0.01" min="0">
                            </div>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_center" class="form-label">Service Center</label>
                            <input type="text" name="service_center" id="service_center" class="form-control @error('service_center') is-invalid @enderror" value="{{ old('service_center', $maintenance->service_center) }}" placeholder="e.g., Quick Lube Auto">
                            @error('service_center')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_bill" class="form-label">Service Bill</label>
                            @if($maintenance->service_bill)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($maintenance->service_bill) }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;" alt="Service Bill">
                                    <button type="button" class="btn btn-sm btn-info ms-2" data-bs-toggle="modal" data-bs-target="#serviceBillModal">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="service_bill" id="service_bill" class="form-control @error('service_bill') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'serviceBillPreview')">
                            <small class="text-muted">Upload new service bill to replace existing (JPG, PNG, max 2MB)</small>
                            @error('service_bill')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="serviceBillPreview" class="mt-2"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="related_document" class="form-label">Related Document</label>
                            @if($maintenance->related_document)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($maintenance->related_document) }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;" alt="Related Document">
                                    <button type="button" class="btn btn-sm btn-info ms-2" data-bs-toggle="modal" data-bs-target="#relatedDocModal">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="related_document" id="related_document" class="form-control @error('related_document') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'relatedDocPreview')">
                            <small class="text-muted">Upload new document to replace existing (JPG, PNG, max 2MB)</small>
                            @error('related_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="relatedDocPreview" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional details about the service...">{{ old('notes', $maintenance->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Maintenance Record
                        </button>
                        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Service Bill Modal -->
@if($maintenance->service_bill)
<div class="modal fade" id="serviceBillModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ Storage::url($maintenance->service_bill) }}" class="img-fluid" alt="Service Bill">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Related Document Modal -->
@if($maintenance->related_document)
<div class="modal fade" id="relatedDocModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Related Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ Storage::url($maintenance->related_document) }}" class="img-fluid" alt="Related Document">
            </div>
        </div>
    </div>
</div>
@endif

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

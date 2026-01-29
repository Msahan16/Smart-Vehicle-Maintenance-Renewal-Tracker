@extends('layouts.app')

@section('page-title', 'Log Maintenance')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-wrench me-2"></i>Log Maintenance Record
            </div>
            <div class="card-body">
                @if ($vehicles->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You need to add a vehicle first before logging maintenance.
                        <a href="{{ route('vehicles.create') }}" class="alert-link">Add Vehicle</a>
                    </div>
                @else
                    <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                <option value="">Select Vehicle</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
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
                                    <option value="Oil Change" {{ old('service_type') == 'Oil Change' ? 'selected' : '' }}>Oil Change</option>
                                    <option value="Tire Rotation" {{ old('service_type') == 'Tire Rotation' ? 'selected' : '' }}>Tire Rotation</option>
                                    <option value="Brake Service" {{ old('service_type') == 'Brake Service' ? 'selected' : '' }}>Brake Service</option>
                                    <option value="Battery Replacement" {{ old('service_type') == 'Battery Replacement' ? 'selected' : '' }}>Battery Replacement</option>
                                    <option value="Air Filter" {{ old('service_type') == 'Air Filter' ? 'selected' : '' }}>Air Filter</option>
                                    <option value="Transmission Service" {{ old('service_type') == 'Transmission Service' ? 'selected' : '' }}>Transmission Service</option>
                                    <option value="Engine Tune-up" {{ old('service_type') == 'Engine Tune-up' ? 'selected' : '' }}>Engine Tune-up</option>
                                    <option value="General Inspection" {{ old('service_type') == 'General Inspection' ? 'selected' : '' }}>General Inspection</option>
                                    <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="service_date" class="form-label">Service Date <span class="text-danger">*</span></label>
                                <input type="date" name="service_date" id="service_date" class="form-control @error('service_date') is-invalid @enderror" value="{{ old('service_date', date('Y-m-d')) }}" required>
                                @error('service_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="next_service_date" class="form-label">Next Service Date</label>
                                <input type="date" name="next_service_date" id="next_service_date" class="form-control @error('next_service_date') is-invalid @enderror" value="{{ old('next_service_date') }}">
                                @error('next_service_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mileage" class="form-label">Mileage (km)</label>
                                <input type="number" name="mileage" id="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage') }}" min="0">
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
                                    <input type="number" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost') }}" step="0.01" min="0">
                                </div>
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="service_center" class="form-label">Service Center</label>
                                <input type="text" name="service_center" id="service_center" class="form-control @error('service_center') is-invalid @enderror" value="{{ old('service_center') }}" placeholder="e.g., Quick Lube Auto">
                                @error('service_center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_bill" class="form-label">Service Bill</label>
                                <input type="file" name="service_bill" id="service_bill" class="form-control @error('service_bill') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'serviceBillPreview')">
                                <small class="text-muted">Upload service bill image (JPG, PNG, max 2MB)</small>
                                @error('service_bill')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="serviceBillPreview" class="mt-2"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="related_document" class="form-label">Related Document</label>
                                <input type="file" name="related_document" id="related_document" class="form-control @error('related_document') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'relatedDocPreview')">
                                <small class="text-muted">Upload related document (JPG, PNG, max 2MB)</small>
                                @error('related_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="relatedDocPreview" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional details about the service...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Maintenance Record
                            </button>
                            <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary text-center">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

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

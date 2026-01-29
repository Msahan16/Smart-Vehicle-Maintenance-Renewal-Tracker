<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="vehicle_number" class="form-label">Vehicle Number *</label>
                <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                       id="vehicle_number" wire:model="vehicle_number" placeholder="e.g., ABC-1234">
                @error('vehicle_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="brand" class="form-label">Brand *</label>
                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                       id="brand" wire:model="brand" placeholder="e.g., Toyota">
                @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="model" class="form-label">Model *</label>
                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                       id="model" wire:model="model" placeholder="e.g., Camry">
                @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="fuel_type" class="form-label">Fuel Type *</label>
                <select class="form-control @error('fuel_type') is-invalid @enderror" 
                        id="fuel_type" wire:model="fuel_type">
                    <option value="">Select Fuel Type</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Electric">Electric</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="CNG">CNG</option>
                    <option value="LPG">LPG</option>
                </select>
                @error('fuel_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="engine_capacity" class="form-label">Engine Capacity</label>
                <input type="text" class="form-control @error('engine_capacity') is-invalid @enderror" 
                       id="engine_capacity" wire:model="engine_capacity" placeholder="e.g., 2.5L">
                @error('engine_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="manufactured_year" class="form-label">Manufactured Year *</label>
                <input type="number" class="form-control @error('manufactured_year') is-invalid @enderror" 
                       id="manufactured_year" wire:model="manufactured_year" placeholder="{{ date('Y') }}">
                @error('manufactured_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                       id="color" wire:model="color" placeholder="e.g., Silver">
                @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="photo" class="form-label">Vehicle Photo</label>
                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                       id="photo" wire:model="photo" accept="image/*">
                @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="license_expiry" class="form-label">License Expiry</label>
                <input type="date" class="form-control @error('license_expiry') is-invalid @enderror" 
                       id="license_expiry" wire:model="license_expiry">
                @error('license_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="insurance_expiry" class="form-label">Insurance Expiry</label>
                <input type="date" class="form-control @error('insurance_expiry') is-invalid @enderror" 
                       id="insurance_expiry" wire:model="insurance_expiry">
                @error('insurance_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="emission_test_expiry" class="form-label">Emission Test Expiry</label>
                <input type="date" class="form-control @error('emission_test_expiry') is-invalid @enderror" 
                       id="emission_test_expiry" wire:model="emission_test_expiry">
                @error('emission_test_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" wire:model="notes" rows="3" placeholder="Any additional notes..."></textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 mt-3">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $vehicle ? 'Update Vehicle' : 'Save Vehicle' }}
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary text-center">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@extends('layouts.app')

@section('page-title', 'Profile Settings')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        {{-- Flash handled by SweetAlert2 in layout --}}

        <div class="card-custom mb-4">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>Profile Information
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="driver_license_number" class="form-label">Driver License Number</label>
                            <input type="text" class="form-control @error('driver_license_number') is-invalid @enderror" id="driver_license_number" name="driver_license_number" value="{{ old('driver_license_number', $user->driver_license_number) }}">
                            @error('driver_license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="driver_license_expiry" class="form-label">Driver License Expiry</label>
                            <input type="date" class="form-control @error('driver_license_expiry') is-invalid @enderror" id="driver_license_expiry" name="driver_license_expiry" value="{{ old('driver_license_expiry', $user->driver_license_expiry?->format('Y-m-d')) }}">
                            @error('driver_license_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email_notifications" class="form-label">Email Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('email_notifications') is-invalid @enderror" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Receive email reminders
                                </label>
                                @error('email_notifications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Driver License Documents Section -->
                        <div class="col-12 mb-4">
                            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <div class="card-body">
                                    <h6 class="mb-3 text-white"><i class="fas fa-id-card me-2"></i>Driver License Documents</h6>
                                    <div class="row">
                                        <!-- Front Side -->
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="bg-white p-3 rounded">
                                                <p class="mb-2 fw-bold"><i class="fas fa-image me-1"></i>Front Side</p>
                                                @if($user->driver_license_front)
                                                    <div class="border rounded p-2 mb-2 text-center" style="background-color: #f8f9fa;">
                                                        <img src="{{ asset('storage/' . $user->driver_license_front) }}" alt="Driver License Front" class="img-fluid" style="max-height: 180px; object-fit: contain;">
                                                    </div>
                                                    <a href="{{ asset('storage/' . $user->driver_license_front) }}" target="_blank" class="btn btn-sm btn-primary w-100 mb-2">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                @else
                                                    <div class="border rounded p-4 mb-2 text-center" style="background-color: #f8f9fa;">
                                                        <i class="fas fa-user fa-4x text-muted mb-2"></i>
                                                        <p class="text-muted mb-0 small">No file chosen</p>
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control @error('driver_license_front') is-invalid @enderror" id="driver_license_front" name="driver_license_front" accept="image/*" onchange="previewImage(event, 'driver_license_front_preview')">
                                                @error('driver_license_front')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="driver_license_front_preview" class="mt-2"></div>
                                            </div>
                                        </div>

                                        <!-- Back Side -->
                                        <div class="col-md-6">
                                            <div class="bg-white p-3 rounded">
                                                <p class="mb-2 fw-bold"><i class="fas fa-image me-1"></i>Back Side</p>
                                                @if($user->driver_license_back)
                                                    <div class="border rounded p-2 mb-2 text-center" style="background-color: #f8f9fa;">
                                                        <img src="{{ asset('storage/' . $user->driver_license_back) }}" alt="Driver License Back" class="img-fluid" style="max-height: 180px; object-fit: contain;">
                                                    </div>
                                                    <a href="{{ asset('storage/' . $user->driver_license_back) }}" target="_blank" class="btn btn-sm btn-primary w-100 mb-2">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                @else
                                                    <div class="border rounded p-4 mb-2 text-center" style="background-color: #f8f9fa;">
                                                        <i class="fas fa-user fa-4x text-muted mb-2"></i>
                                                        <p class="text-muted mb-0 small">No file chosen</p>
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control @error('driver_license_back') is-invalid @enderror" id="driver_license_back" name="driver_license_back" accept="image/*" onchange="previewImage(event, 'driver_license_back_preview')">
                                                @error('driver_license_back')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="driver_license_back_preview" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-lock me-2"></i>Change Password
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <p class="mb-1 small text-muted">New document preview:</p>
                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
            `;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

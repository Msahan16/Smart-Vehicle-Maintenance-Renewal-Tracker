@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <img src="{{ asset('images/logo-icon-modern.png') }}" alt="Logo" class="logo-img">
        <h1>Welcome Back!</h1>
        <p>Smart Vehicle Maintenance Tracker</p>
    </div>

    <div class="auth-body">
        @if (session('status'))
            <div class="alert alert-success mb-4 rounded-4 border-0">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4 rounded-4 border-0">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-2">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i> Email Address
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="your-email@example.com">
                </div>
                @error('email')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i> Password
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Enter your password">
                </div>
                @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

      

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-arrow-right me-2"></i> Login to Dashboard
            </button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Forgot your password?
                </a>
            @endif
        </form>
    </div>

    <div class="auth-footer text-muted">
        Need an account? 
        <a href="{{ route('register') }}" class="ms-1">
            Register Now
        </a>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Vehicle Tracker') }} - @yield('title', 'Login')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'><path fill='%231e40af' d='M171.3 96H224v96H111.3l30.4-75.9C146.5 104 158.2 96 171.3 96zM272 192V96h81.2c9.7 0 18.9 4.4 25 12l67.2 84H272zm256.2 1L428.2 68c-18.2-22.8-45.8-36-75-36H171.3c-39.3 0-74.6 23.9-89.1 60.3L40.6 196.4C16.8 205.8 0 228.9 0 256V368c0 17.7 14.3 32 32 32H65.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H385.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H608c17.7 0 32-14.3 32-32V320c0-65.2-48.8-119-111.8-127zM434.7 368a48 48 0 1 1 90.5 32 48 48 0 1 1 -90.5-32zM160 336a48 48 0 1 1 0 96 48 48 0 1 1 0-96z'/></svg>" type="image/svg+xml">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --brand-blue: #134e7f;
            --brand-green: #10b981;
            --primary-color: #334155;
            --secondary-color: #64748b;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            max-width: 450px;
            width: 100%;
        }

        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); /* Softer shadow */
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .auth-header {
            background: white; /* Clean white header like main app */
            padding: 40px 30px 20px;
            text-align: center;
            color: var(--primary-color);
        }

        .auth-header .icon-wrapper {
            width: 64px;
            height: 64px;
            background: #eff6ff; /* Light blue bg */
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
        }

        .auth-header .icon-wrapper i {
            font-size: 32px;
            color: var(--brand-blue);
            background: none;
            -webkit-text-fill-color: var(--brand-blue);
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }

        .auth-header p {
            margin: 8px 0 0;
            opacity: 1;
            color: #64748b;
            font-size: 14px;
        }

        .auth-body {
            padding: 20px 30px 40px;
        }

        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .form-control:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(19, 78, 127, 0.1); /* Brand blue ring */
            outline: none;
        }

        .input-group-text {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #64748b;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .btn-primary {
            background: var(--brand-green); /* Action Green */
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .auth-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .auth-footer a {
            color: var(--brand-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .auth-footer a:hover {
            color: #0f3d64;
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        /* Background Pattern - Optional but nice */
        .vehicle-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--brand-blue); /* Solid Brand Blue Background */
            z-index: -1;
        }
        
        /* Subtle texture or illustration overlay could go here, 
           matches the "Login to LTI" modal overlay style */
    </style>
    @stack('styles')
</head>
<body>
    <div class="vehicle-bg"></div>
    
    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

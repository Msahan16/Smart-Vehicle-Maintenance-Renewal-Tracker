<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Vehicle Tracker') }} - @yield('title', 'Login')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'><path fill='%231e40af' d='M171.3 96H224v96H111.3l30.4-75.9C146.5 104 158.2 96 171.3 96zM272 192V96h81.2c9.7 0 18.9 4.4 25 12l67.2 84H272zm256.2 1L428.2 68c-18.2-22.8-45.8-36-75-36H171.3c-39.3 0-74.6 23.9-89.1 60.3L40.6 196.4C16.8 205.8 0 228.9 0 256V368c0 17.7 14.3 32 32 32H65.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H385.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H608c17.7 0 32-14.3 32-32V320c0-65.2-48.8-119-111.8-127zM434.7 368a48 48 0 1 1 90.5 32 48 48 0 1 1 -90.5-32zM160 336a48 48 0 1 1 0 96 48 48 0 1 1 0-96z'/></svg>" type="image/svg+xml">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --brand-blue: #0284c7; /* A brighter blue for the switch */
            --brand-green: #0d9488; /* Teal color like the button */
            --primary-color: #f8fafc;
            --secondary-color: #cbd5e1;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.3);
            --text-main: #334155;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .vehicle-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/VC.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            transform: scale(1.1);
            filter: brightness(0.6) contrast(1.1);
            transition: transform 10s ease-out;
        }

        body:hover .vehicle-bg {
            transform: scale(1.0);
        }

        .auth-container {
            max-width: 440px;
            width: 100%;
            z-index: 10;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.4);
            overflow: hidden;
            animation: fadeInScale 0.6s ease-out;
        }

        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        .auth-header {
            padding: 45px 30px 20px;
            text-align: center;
        }

        .auth-header .logo-img {
            width: 85px;
            height: 85px;
            margin-bottom: 25px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            background: white;
            padding: 10px;
            border-radius: 20px;
        }

        .auth-header h1 {
            font-size: 30px;
            font-weight: 700;
            margin: 0;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 400;
        }

        .auth-body {
            padding: 20px 45px 35px;
        }

        .form-label {
            font-weight: 500;
            color: #fff;
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            font-size: 12px;
            margin-right: 8px;
            opacity: 0.8;
        }

        .input-group {
            background: #fff;
            border-radius: 14px;
            padding: 2px;
            margin-bottom: 22px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            padding-left: 18px;
            font-size: 18px;
        }

        .form-control {
            background: transparent;
            border: none;
            padding: 14px 15px;
            font-size: 15px;
            color: #1e293b;
            font-weight: 500;
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
            outline: none;
            color: #1e293b;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            border: none;
            border-radius: 14px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
            margin-top: 5px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(13, 148, 136, 0.4);
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            padding: 28px 30px;
            background: rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            font-size: 15px;
        }

        .auth-footer a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: #99f6e4;
            text-decoration: underline;
        }

        .forgot-link {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* Custom Switch Styling to match image */
        .form-switch {
            padding-left: 3.5em;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-left: -3.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255, 255, 255, 1%29'/%3e%3c/svg%3e");
            background-color: rgba(255, 255, 255, 0.3);
            border-color: transparent;
            cursor: pointer;
            transition: background-position .15s ease-in-out, background-color .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-color: #0ea5e9;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .form-check-label {
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }

        .alert {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
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

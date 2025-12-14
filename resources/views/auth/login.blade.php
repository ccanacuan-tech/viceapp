<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Iniciar Sesión</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f0f2f5; /* Un gris claro para el fondo */
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to top right, #c3d4e4, #f0f2f5);
        }
        .login-card {
            background-color: white;
            padding: 2.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 28rem; 
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo {
            height: 5rem; /* 80px */
            width: auto;
            margin: 0 auto 1rem;
        }
        .login-title {
            font-size: 1.5rem; /* 24px */
            font-weight: 700;
            color: #1a202c; /* Un gris oscuro */
        }
        .login-subtitle {
            font-size: 1rem; /* 16px */
            color: #4a5568; /* Un gris más claro */
        }
        .btn-primary {
            background-color: #c53030; /* Rojo oscuro */
            color: white;
            font-weight: 600;
            width: 100%;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #9b2c2c; /* Rojo más oscuro al pasar el ratón */
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/logo10.png') }}" alt="Logo del Colegio" class="login-logo">
                <h1 class="login-title">Sistema de Gestión de Planificación</h1>
                <p class="login-subtitle">U.E. Fiscomisional 10 de Agosto</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Recordar sesión') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="btn-primary">
                        {{ __('Iniciar Sesión') }}
                    </button>
                </div>
                
                @if (Route::has('password.request'))
                    <div class="text-center mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>
</html>

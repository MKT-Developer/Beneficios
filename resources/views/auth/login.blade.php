@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<!-- <div class="auth-logo">
    <img src="/admin-assets/images/mera_white_tagline.png" alt="Logo">
</div> -->

<div class="auth-card @if($errors->any()) shake @endif">

    <h2>Bienvenido de nuevo</h2>
    <p class="auth-subtitle">Ingresa tus datos para continuar</p>

    <form id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="email required autofocus class=" @error('email') error-input @enderror">
        </div>

        <div class="form-group password-group">
            <label>Contraseña</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()" id="eyeIcon">
                    <!-- ojo cerrado por defecto -->
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C7 19 2.73 16.11 1 12c.73-1.68 1.83-3.17 3.17-4.36M9.9 4.24A10.94 10.94 0 0 1 12 5c5 0 9.27 2.89 11 7a10.94 10.94 0 0 1-4.06 5.06M1 1l22 22" />
                    </svg>

                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember">
                Recordarme
            </label>
        </div>

        <button class="btn btn-primary" id="loginBtn">Entrar</button>

        <p class="auth-link">
            <a href="{{ route('password.request') }}">
                ¿Olvidaste tu contraseña?
            </a>
        </p>

        <p class="auth-link">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Regístrate</a>
        </p>
    </form>
</div>

@endsection
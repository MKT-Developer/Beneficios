@extends('layouts.guest')

@section('title', 'Registro')

@section('content')

<div class="auth-card @if($errors->any()) shake @endif">

    <h2>Crear cuenta</h2>
    <p class="auth-subtitle">Completa tus datos para acceder al sistema</p>

    <form id="registerForm" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- NOMBRE -->
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <!-- PASSWORD -->
        <div class="form-group password-group">
            <label>Contraseña</label>

            <div class="input-wrapper">
                <input type="password" name="password" id="registerPassword" required>

                <span class="toggle-password" onclick="toggleRegisterPassword()">
                    <!-- closed -->
                    <svg id="regEyeClosed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C7 19 2.73 16.11 1 12c.73-1.68 1.83-3.17 3.17-4.36M9.9 4.24A10.94 10.94 0 0 1 12 5c5 0 9.27 2.89 11 7a10.94 10.94 0 0 1-4.06 5.06M1 1l22 22" />
                    </svg>

                    <!-- open -->
                    <svg id="regEyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
            </div>

            <!-- strength -->
            <div class="password-strength">
                <div id="strengthBar"></div>
            </div>

            <small id="strengthText"></small>

            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <!-- CONFIRM -->
        <div class="form-group password-group">
            <label>Confirmar contraseña</label>

            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="registerPasswordConfirm" required>

                <span class="toggle-password" onclick="toggleRegisterPasswordConfirm()">
                    <svg id="regEyeClosedConfirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C7 19 2.73 16.11 1 12c.73-1.68 1.83-3.17 3.17-4.36M9.9 4.24A10.94 10.94 0 0 1 12 5c5 0 9.27 2.89 11 7a10.94 10.94 0 0 1-4.06 5.06M1 1l22 22" />
                    </svg>

                    <svg id="regEyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
            </div>

            <small id="matchText"></small>
        </div>

        <!-- BOTÓN -->
        <button class="btn btn-primary" id="registerBtn">
            Crear cuenta
        </button>

        <p class="auth-link">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}">Inicia sesión</a>
        </p>

    </form>

</div>

@endsection
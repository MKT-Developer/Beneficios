@extends('layouts.guest')

@section('title', 'Recuperar contraseña')

@section('content')

<div class="auth-card">

    <h2>¿Olvidaste tu contraseña?</h2>
    <p class="auth-subtitle">
        Ingresa tu correo y te enviaremos un enlace para restablecerla
    </p>

    {{-- MENSAJE DE ÉXITO --}}
    @if (session('status'))
    <div class="alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="@error('email') error-input @enderror">

            @error('email')
            <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- BOTÓN -->
        <button class="btn btn-primary">
            Enviar enlace de recuperación
        </button>

        <!-- LINK LOGIN -->
        <p class="auth-link">
            <a href="{{ route('login') }}">
                Volver a iniciar sesión
            </a>
        </p>

    </form>

</div>

@endsection
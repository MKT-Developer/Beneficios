@csrf
@php
$user = $user ?? new \App\Models\User();
@endphp

<div class="form-group">
    <label>Nombre *</label>
    <input
        type="text"
        name="name"
        value="{{ old('name', optional($user)->name) }}"
        data-validate="text"
        data-required="1"
        data-max="255"
        data-message="El nombre es obligatorio">

    @error('name')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>Email *</label>
    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        data-validate="email"
        data-required="1"
        data-message="Correo inválido">

    @error('email')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- PASSWORD --}}
<div class="form-group">
    <label>Password {{ isset($user) ? '' : '*' }}</label>

    <div class="input-wrapper">

        <input
            id="registerPassword"
            type="password"
            name="password"
            data-validate="text"
            {{ isset($user) ? '' : 'data-required=1' }}
            data-min="6"
            data-message="Mínimo 6 caracteres">

        <span class="toggle-password" onclick="toggleRegisterPassword()">
            <i id="regEyeOpen" class="fas fa-eye"></i>
            <i id="regEyeClosed" class="fas fa-eye-slash" style="display:none;"></i>
        </span>

    </div>

    {{-- BARRA DE FUERZA --}}
    <div class="password-strength">
        <div id="strengthBar"></div>
    </div>
    <small id="strengthText"></small>

    @error('password')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- CONFIRM PASSWORD SOLO EN CREATE --}}
@if(!$user->exists)
<div class="form-group">
    <label>Confirmar Password *</label>

    <div class="input-wrapper">

        <input
            id="registerPasswordConfirm"
            type="password"
            name="password_confirmation"
            data-validate="text"
            data-required="1"
            data-min="6"
            data-message="Confirma tu contraseña">

        <span class="toggle-password" onclick="toggleRegisterPasswordConfirm()">
            <i id="regEyeOpenConfirm" class="fas fa-eye"></i>
            <i id="regEyeClosedConfirm" class="fas fa-eye-slash" style="display:none;"></i>
        </span>

    </div>

    <small id="matchText"></small>
</div>
@endif

<div class="form-group">
    <label>Rol *</label>
    <select
        name="role"
        data-validate="select"
        data-required="1"
        {{ isset($user) && $user->isLocked() ? 'disabled' : '' }}>

        <option value="">Selecciona un rol</option>

        @if(auth()->user()->isSuperAdmin())
        <option value="superadmin"
            {{ old('role', $user->role ?? '') == 'superadmin' ? 'selected' : '' }}>
            SuperAdmin
        </option>
        @endif

        <option value="admin"
            {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
            Admin
        </option>

        <option value="editor"
            {{ old('role', $user->role ?? '') == 'editor' ? 'selected' : '' }}>
            Editor
        </option>
    </select>

    @error('role')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-actions">
    <button type="submit" id="registerBtn" class="btn btn-primary">
        Guardar
    </button>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>
@csrf

{{-- Pilar --}}
<div class="form-group">
    <label for="pilar_id">Pilar *</label>

    <select
        id="pilar_id"
        name="pilar_id"
        data-validate="select"
        data-required="1"
        data-message="Selecciona un pilar">

        <option value="">Selecciona un pilar</option>

        @foreach($pilares as $pilar)
        <option value="{{ $pilar->id }}"
            {{ old('pilar_id', $beneficio->pilar_id) == $pilar->id ? 'selected' : '' }}>
            {{ $pilar->nombre }} — {{ $pilar->pais->nombre }}
        </option>
        @endforeach
    </select>

    @error('pilar_id')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Nombre --}}
<div class="form-group">
    <label for="nombre">Nombre *</label>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $beneficio->nombre) }}"
        data-validate="text"
        data-required="1"
        data-max="255"
        data-message="El nombre es obligatorio">

    @error('nombre')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Descripción --}}
<div class="form-group">
    <label for="descripcion">Descripción *</label>

    <textarea
        id="descripcion"
        name="descripcion"
        data-validate="text"
        data-required="1"
        data-message="La descripción es obligatoria">{{ old('descripcion', $beneficio->descripcion) }}</textarea>

    @error('descripcion')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Beneficios --}}
<div class="form-group">
    <label for="beneficios">Beneficios</label>

    <textarea
        id="beneficios"
        name="beneficios"
        placeholder="• 20% descuento
• 10% consulta
• Examen gratis">{{ old('beneficios', $beneficio->beneficios) }}</textarea>

    <small class="form-help">Usa viñetas (•) para separar cada beneficio.</small>
</div>

{{-- Condiciones --}}
<div class="form-group">
    <label for="condiciones">Condiciones</label>

    <textarea
        id="condiciones"
        name="condiciones"
        placeholder="• Credencial vigente
• No acumulable">{{ old('condiciones', $beneficio->condiciones) }}</textarea>
</div>

{{-- Logo --}}
<div class="form-group">
    <label for="logo">Logo</label>

    @if(!empty($beneficio->logo))
    <div class="image-preview logo_beneficio">
        <img src="{{ asset('storage/beneficios/' . $beneficio->logo) }}" alt="Logo">
    </div>
    @endif

    <input
        type="file"
        id="logo"
        name="logo"
        data-validate="file"
        data-max-size="2097152"
        data-types="image/jpeg,image/png,image/webp,image/svg+xml"
        data-message="Formato inválido o archivo muy grande">

    @error('logo')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Orden --}}
<div class="form-group">
    <label for="orden">Orden</label>

    <input
        type="number"
        id="orden"
        name="orden"
        value="{{ old('orden', $beneficio->orden ?? 0) }}"
        data-validate="number"
        data-message="Debe ser un número válido">
</div>

{{-- Activo --}}
<div class="form-group">
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $beneficio->activo ?? 1) ? 'checked' : '' }}>
        Activo
    </label>
</div>

<hr>

<h4>Contacto</h4>

<div class="form-group">
    <label for="redsocial">Red social</label>
    <input type="text" id="redsocial" name="redsocial"
        value="{{ old('redsocial', $beneficio->redsocial) }}">
</div>

<div class="form-group">
    <label for="sitio">Sitio web</label>
    <input type="url" id="sitio" name="sitio"
        value="{{ old('sitio', $beneficio->sitio) }}">
</div>

<div class="form-group">
    <label for="telefono">Teléfono</label>
    <input type="text" id="telefono" name="telefono"
        value="{{ old('telefono', $beneficio->telefono) }}">
</div>

<div class="form-group">
    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo"
        value="{{ old('correo', $beneficio->correo) }}">
</div>

<hr>

{{-- Ubicaciones --}}
@php
$selectedUbicaciones = old(
'ubicaciones',
$beneficio->ubicaciones?->pluck('id')->all() ?? []
);
@endphp

<div class="form-group">
    <label>Ubicaciones</label>

    <div class="form-row">
        @foreach($ubicaciones as $ubicacion)
        <label class="checkbox-label">
            <input
                type="checkbox"
                name="ubicaciones[]"
                value="{{ $ubicacion->id }}"
                {{ in_array($ubicacion->id, $selectedUbicaciones) ? 'checked' : '' }}>
            {{ $ubicacion->nombre }}
        </label>
        @endforeach
    </div>
</div>

<div class="form-actions">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.beneficios.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>
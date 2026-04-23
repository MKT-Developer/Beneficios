@csrf

{{-- Selección de país --}}
<div class="form-group">
    <label for="pais_id">País</label>

    <select
        name="pais_id"
        id="pais_id"
        data-validate="select"
        data-required="1">

        <option value="">Selecciona un país</option>

        @foreach($paises as $pais)
        <option value="{{ $pais->id }}"
            {{ old('pais_id', $pilar->pais_id ?? '') == $pais->id ? 'selected' : '' }}>
            {{ $pais->nombre }}
        </option>
        @endforeach
    </select>

    @error('pais_id')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Nombre del Pilar --}}
<div class="form-group">
    <label for="nombre">Nombre del Pilar</label>

    <input
        type="text"
        name="nombre"
        id="nombre"
        value="{{ old('nombre', $pilar->nombre ?? '') }}"
        placeholder="Ej: Pilar de bienestar"
        data-validate="text"
        data-required="1"
        data-max="255"
        data-message="El nombre es obligatorio y máximo 255 caracteres">

    @error('nombre')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Icono / Imagen --}}
<div class="form-group">
    <label for="icono">Icono (imagen o SVG)</label>

    @if(!empty($pilar?->icono))
    <div class="image-preview">
        <img src="{{ asset('storage/pilares/' . $pilar->icono) }}" alt="Icono actual">
    </div>
    @endif

    <input
        type="file"
        name="icono"
        id="icono"
        data-validate="file"
        data-max-size="2097152"
        data-types="image/jpeg,image/png,image/webp,image/svg+xml"
        data-message="El icono debe ser PNG, JPG, WEBP o SVG (máx 2MB)">

    <small class="form-help">PNG, JPG, WEBP o SVG</small>

    @error('icono')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

{{-- Descripción --}}
<div class="form-group">
    <label for="descripcion">Descripción</label>

    <textarea
        name="descripcion"
        id="descripcion"
        rows="4"
        placeholder="Describe brevemente este pilar...">{{ old('descripcion', $pilar->descripcion ?? '') }}</textarea>
</div>

{{-- Orden y activo --}}
<div class="form-row">
    {{-- Orden --}}
    <div class="form-group">
        <label for="orden">Orden</label>

        <input
            type="number"
            name="orden"
            id="orden"
            min="1"
            value="{{ old('orden', $pilar->orden ?? $siguienteOrden) }}"
            data-validate="number"
            data-message="El orden debe ser un número válido">

        @error('orden')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    {{-- Activo --}}
    <div class="form-group checkbox-label">
        <label class="">
            Activo
        </label>
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $pilar->activo ?? true) ? 'checked' : '' }}>
    </div>
</div>

{{-- Acciones --}}
<div class="form-actions">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pilares.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
@csrf

<div class="form-group">
    <label for="nombre">Nombre *</label>

    <input
        type="text"
        name="nombre"
        id="nombre"
        value="{{ old('nombre', $ubicacion->nombre ?? '') }}"
        data-validate="text"
        data-required="1"
        data-max="255"
        data-message="El nombre es obligatorio">

    @error('nombre')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $ubicacion->activo ?? true) ? 'checked' : '' }}>
        Activo
    </label>
</div>

<div class="form-actions">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.ubicaciones.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>
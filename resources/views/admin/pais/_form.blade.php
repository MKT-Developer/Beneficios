@csrf

<div class="form-group">
    <label for="nombre">Nombre del país</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $pais->nombre ?? '') }}"
        placeholder="Ej: México"
        required>
    @error('nombre')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="codigo">Código</label>
    <input
        type="text"
        id="codigo"
        name="codigo"
        value="{{ old('codigo', $pais->codigo ?? '') }}"
        placeholder="mx, co, ar"
        maxlength="10"
        required>
    @error('codigo')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="flag">Bandera</label>
    @if(!empty($pais->flag))
    <div style="margin-bottom:8px;">
        <img src="{{ asset('storage/flags/' . $pais->flag) }}" alt="Bandera" style="height:40px; border-radius:4px;">
    </div>
    @endif
    <input
        type="file"
        id="flag"
        name="flag"
        accept="image/*">
    @error('flag')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $pais->activo ?? true) ? 'checked' : '' }}>
        Activo
    </label>
</div>

<div class="form-actions">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pais.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
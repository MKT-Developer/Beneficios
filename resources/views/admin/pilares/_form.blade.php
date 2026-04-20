@csrf

{{-- Selección de país --}}
<div class="form-group">
    <label for="pais_id">País</label>
    <select name="pais_id" id="pais_id" required>
        <option value="">Selecciona un país</option>
        @foreach($paises as $pais)
        <option value="{{ $pais->id }}"
            {{ old('pais_id', $pilar->pais_id ?? '') == $pais->id ? 'selected' : '' }}>
            {{ $pais->nombre }}
        </option>
        @endforeach
    </select>
    @error('pais_id')
    <span class="text-danger">{{ $message }}</span>
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
        required>
    @error('nombre')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- Icono / Imagen --}}
<div class="form-group">
    <label for="icono">Icono (imagen, puede ser .svg)</label>
    @if(!empty($pilar?->icono))
    <div style="margin-bottom:8px;">
        <img src="{{ asset('storage/pilares/' . $pilar->icono) }}" alt="Icono actual" style="height:40px; object-fit:contain;">
    </div>
    @endif
    <input
        type="file"
        name="icono"
        id="icono"
        accept=".png,.jpg,.jpeg,.gif,.svg">
    @error('icono')
    <span class="text-danger">{{ $message }}</span>
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
    <div class="form-group">
        <label for="orden">Orden</label>
        <input
            type="number"
            name="orden"
            id="orden"
            min="1"
            value="{{ old('orden', $pilar->orden ?? $siguienteOrden) }}">
        @error('orden')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input
                type="checkbox"
                name="activo"
                value="1"
                {{ old('activo', $pilar->activo ?? true) ? 'checked' : '' }}>
            Activo
        </label>
    </div>
</div>

{{-- Acciones --}}
<div class="form-actions">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pilares.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
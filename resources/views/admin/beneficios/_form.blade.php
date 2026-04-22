@csrf

{{-- Pilar --}}
<div class="form-group">
    <label for="pilar_id">Pilar *</label>
    <select id="pilar_id" name="pilar_id" required>
        <option value="">Selecciona un pilar</option>
        @foreach($pilares as $pilar)
        <option value="{{ $pilar->id }}"
            {{ old('pilar_id', $beneficio->pilar_id ?? '') == $pilar->id ? 'selected' : '' }}>
            {{ $pilar->nombre }} — {{ $pilar->pais->nombre }}
        </option>
        @endforeach
    </select>
    @error('pilar_id') <span class="text-danger">{{ $message }}</span> @enderror
</div>

{{-- Nombre --}}
<div class="form-group">
    <label for="nombre">Nombre *</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $beneficio->nombre ?? '') }}"
        required>
    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
</div>

{{-- Descripción --}}
<div class="form-group">
    <label for="descripcion">Descripción *</label>
    <textarea
        id="descripcion"
        name="descripcion"
        required>{{ old('descripcion', $beneficio->descripcion ?? '') }}</textarea>
    @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
</div>

{{-- Beneficios --}}
<div class="form-group">
    <label for="beneficios">
        Beneficios
    </label>

    <textarea
        id="beneficios"
        name="beneficios"
        placeholder="Ejemplo:
• 20% en productos
• 10% en consultas
• Examen gratis">{{ old('beneficios', $beneficio->beneficios ?? '') }}</textarea>

    <small class="form-help">
        Usa viñetas (•) para separar cada beneficio.
    </small>

    @error('beneficios')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- Condiciones --}}
<div class="form-group">
    <label for="condiciones">Condiciones</label>

    <textarea
        id="condiciones"
        name="condiciones"
        placeholder="Ejemplo:
• Presentar credencial vigente
• No acumulable con otras promociones">{{ old('condiciones', $beneficio->condiciones ?? '') }}</textarea>

    <small class="form-help">
        Agrega restricciones o términos del beneficio.
    </small>
</div>

{{-- Logo --}}
<div class="form-group">
    <label for="logo">Logo del beneficio</label>

    @if(!empty($beneficio->logo))
    <div class="image-preview">
        <img src="{{ asset('storage/beneficios/' . $beneficio->logo) }}" alt="Logo del beneficio">
    </div>
    @endif

    <input
        type="file"
        id="logo"
        name="logo"
        accept="image/*">

    @error('logo')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- Orden --}}
<div class="form-group">
    <label for="orden">Orden</label>
    <input
        type="number"
        id="orden"
        name="orden"
        class="input-order"
        value="{{ old('orden', $beneficio->orden ?? 0) }}">
</div>

{{-- Activo --}}
<div class="form-group">
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $beneficio->activo ?? true) ? 'checked' : '' }}>
        Activo
    </label>
</div>

<hr>

<h4>Contacto</h4>

<div class="form-group">
    <label for="redsocial">Red social</label>
    <input
        type="text"
        id="redsocial"
        name="redsocial"
        value="{{ old('redsocial', $beneficio->redsocial ?? '') }}">
</div>

<div class="form-group">
    <label for="sitio">Sitio web</label>
    <input
        type="url"
        id="sitio"
        name="sitio"
        value="{{ old('sitio', $beneficio->sitio ?? '') }}">
</div>

<div class="form-group">
    <label for="telefono">Teléfono</label>
    <input
        type="text"
        id="telefono"
        name="telefono"
        value="{{ old('telefono', $beneficio->telefono ?? '') }}">
</div>

<div class="form-group">
    <label for="correo">Correo</label>
    <input
        type="email"
        id="correo"
        name="correo"
        value="{{ old('correo', $beneficio->correo ?? '') }}">
</div>

<hr>

{{-- Ubicaciones --}}
<div class="form-group">
    <label>Ubicaciones</label>

    @php
    $selectedUbicaciones = old(
    'ubicaciones',
    $beneficio->ubicaciones->pluck('id')->toArray() ?? []
    );
    @endphp

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
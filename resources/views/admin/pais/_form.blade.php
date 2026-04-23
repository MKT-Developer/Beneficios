@csrf

<div class="form-group">
    <label for="nombre">Nombre del país</label>
    <input
        type="text"
        name="nombre"
        value="{{ old('nombre') ?? ($pais->nombre ?? '') }}"
        data-validate="text"
        data-min="3"
        data-max="255"
        data-required="1"
        data-message="El nombre debe tener entre 3 y 255 caracteres" />

    @error('nombre')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="codigo">Código</label>
    <input
        type="text"
        name="codigo"
        value="{{ old('codigo') ?? ($pais->codigo ?? '') }}"
        data-validate="regex"
        data-pattern="^[a-z]{2,5}$"
        data-message="El código debe ser 2 a 5 letras minúsculas" />

    <small class="form-help">Usa código ISO corto (ej: mx)</small>

    @error('codigo')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="flag">Bandera</label>


    @if(!empty($pais->flag))
    <div class="image-preview">
        <img src="{{ asset('storage/flags/' . $pais->flag) }}" alt="Bandera">
    </div>
    @endif

    <input
        type="file"
        name="flag"
        data-validate="file"
        data-max-size="2097152"
        data-types="image/jpeg,image/png,image/webp"
        data-message="La bandera debe ser JPG, PNG o WEBP (máx 2MB)" />

    <small class="form-help">PNG o JPG recomendado</small>

    @error('flag')
    <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="activo"
            value="1"
            {{
        old('activo') !== null
            ? (old('activo') ? 'checked' : '')
            : (($pais->activo ?? true) ? 'checked' : '')
    }}>
        Activo
    </label>
</div>

<div class="form-actions">
    <button class="btn btn-primary" id="saveBtn">Guardar</button>

    <a href="{{ route('admin.pais.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>
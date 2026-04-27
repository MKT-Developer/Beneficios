@forelse($beneficios as $beneficio)
<tr data-id="{{ $beneficio->id }}">

    {{-- BENEFICIO --}}
    <td data-label="Beneficio">
        <div class="beneficio-title">
            {{ $beneficio->nombre }}
        </div>

        <div class="beneficio-meta hide-mobile">
            ID: {{ $beneficio->id }}
        </div>
    </td>

    {{-- PILAR --}}
    <td data-label="Pilar">
        @if($beneficio->pilar)

        <div class="cell-flex">

            @if($beneficio->pilar->icono)
            <img src="{{ asset('storage/pilares/' . $beneficio->pilar->icono) }}"
                class="icon-sm"
                alt="pilar icon">
            @endif

            <a href="{{ route('admin.pilares.edit', $beneficio->pilar) }}"
                class="link-primary">
                {{ $beneficio->pilar->nombre }}
            </a>

        </div>

        @else
        <span class="text-muted">—</span>
        @endif
    </td>

    {{-- PAÍS --}}
    <td data-label="País">
        @if($beneficio->pilar && $beneficio->pilar->pais)

        <div class="cell-flex">

            @if($beneficio->pilar->pais->flag)
            <img src="{{ asset('storage/flags/' . $beneficio->pilar->pais->flag) }}"
                class="pais-icon"
                alt="flag">
            @else
            <span class="pais-icon-fallback">🌍</span>
            @endif

            <a href="{{ route('admin.pais.edit', $beneficio->pilar->pais) }}"
                class="link-primary">
                {{ $beneficio->pilar->pais->nombre }}
            </a>

        </div>

        @else
        <span class="text-muted">—</span>
        @endif
    </td>

    {{-- ESTADO --}}
    <td class="text-center" data-label="Estado">
        <button class="badge {{ $beneficio->activo ? 'badge-success' : 'badge-danger' }}"
            data-model="beneficio"
            data-id="{{ $beneficio->id }}">
            {{ $beneficio->activo ? 'Activo' : 'Inactivo' }}
        </button>
    </td>

    {{-- ACCIONES --}}
    <td class="td-actions" data-label="Acciones">

        <button class="btn btn-sm btn-info"
            onclick="previewBeneficio({{ $beneficio->id }})">
            <i class="fas fa-eye"></i>
        </button>

        @if(auth()->user()->canEdit())
        <a href="{{ route('admin.beneficios.edit', $beneficio) }}"
            class="btn btn-warning">
            <i class="fas fa-pen"></i>
        </a>
        @endif

        @if(auth()->user()->canDelete())
        <form action="{{ route('admin.beneficios.destroy', $beneficio) }}"
            method="POST"
            onsubmit="event.preventDefault(); openDeleteModal(this, 'beneficio')">
            @csrf
            @method('DELETE')

            <button class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        @endif

    </td>

</tr>

@empty
<tr>
    <td colspan="5" class="empty-state">
        <div class="empty-state-content">

            <i class="fas fa-gift empty-icon"></i>

            <p>No hay beneficios registrados</p>

            <a href="{{ route('admin.beneficios.create') }}"
                class="btn btn-sm btn-primary">
                Crear primer beneficio
            </a>

        </div>
    </td>
</tr>
@endforelse
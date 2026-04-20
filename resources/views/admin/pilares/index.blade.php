@extends('layouts.admin')

@section('title', 'Pilares')

@section('content')

<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Pilares</h2>
        <a href="{{ route('admin.pilares.create') }}" class="btn btn-primary">+ Nuevo pilar</a>
    </div>

    {{-- TOASTS --}}
    @if(session('success'))
    <div data-toast="success" data-message="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
    <div data-toast="error" data-message="{{ session('error') }}"></div>
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div data-toast="error" data-message="{{ $error }}"></div>
    @endforeach
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-pilares">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>País</th>
                        <th>Icono</th>
                        <th>Orden</th>
                        <th class="text-center">Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pilares as $pilar)
                    <tr>

                        {{-- ID --}}
                        <td data-label="ID">
                            {{ $pilar->id }}
                        </td>

                        {{-- Nombre --}}
                        <td data-label="Nombre">
                            <strong>{{ $pilar->nombre }}</strong>
                        </td>

                        {{-- País --}}
                        <td data-label="País">
                            @if($pilar->pais)
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($pilar->pais->flag)
                                <img src="{{ asset('storage/flags/' . $pilar->pais->flag) }}"
                                    alt="{{ $pilar->pais->nombre }}"
                                    class="pais-icon">
                                @else
                                <span class="pais-icon">🌍</span>
                                @endif

                                <a href="{{ route('admin.pais.edit', $pilar->pais) }}"
                                    style="color:#0d6efd; text-decoration:none; font-weight:500;">
                                    {{ $pilar->pais->nombre }}
                                </a>
                            </div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Icono --}}
                        <td data-label="Icono">
                            @if($pilar->icono)
                            <img src="{{ asset('storage/pilares/' . $pilar->icono) }}"
                                style="width:28px; height:28px; object-fit:contain;">
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Orden --}}
                        <td data-label="Orden">
                            {{ $pilar->orden }}
                        </td>

                        {{-- Activo (TOGGLE igual que países) --}}
                        <td data-label="Estado" class="text-center">
                            <button class="badge {{ $pilar->activo ? 'badge-success' : 'badge-danger' }}"
                                data-model="pilar"
                                data-id="{{ $pilar->id }}">
                                {{ $pilar->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>

                        {{-- Acciones --}}
                        <td data-label="Acciones" class="td-actions">

                            <a href="{{ route('admin.pilares.edit', $pilar) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.pilares.destroy', $pilar) }}"
                                method="POST"
                                onsubmit="event.preventDefault(); openDeleteModal(this);">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <div style="padding:20px;">
                                <i class="fas fa-layer-group" style="font-size:26px; opacity:.25;"></i>
                                <p style="margin:8px 0;">No hay pilares registrados</p>
                                <a href="{{ route('admin.pilares.create') }}" class="btn btn-sm btn-primary">
                                    Crear primer pilar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

{{-- Toggle endpoint --}}
<script>
    window.toggleModel = "pilar";
</script>

@endsection
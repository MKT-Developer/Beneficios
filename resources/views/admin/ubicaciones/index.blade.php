@extends('layouts.admin')

@section('title', 'Ubicaciones')

@section('content')

<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Ubicaciones</h2>
        <a href="{{ route('admin.ubicaciones.create') }}" class="btn btn-primary">
            + Nueva ubicación
        </a>
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

            <div class="table-wrapper">

                <table class="table table-ubicaciones">

                    <thead>
                        <tr>
                            <th>Ubicación</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Beneficios</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ubicaciones as $ubicacion)
                        <tr>
                            <td>
                                <div class="beneficio-title">
                                    {{ $ubicacion->nombre }}
                                </div>

                                <div class="beneficio-meta hide-mobile">
                                    ID: {{ $ubicacion->id }}
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge {{ $pais->activo ? 'badge-success' : 'badge-danger' }}>
                                    {{ $ubicacion->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td class="text-center hide-mobile">
                                {{ $ubicacion->beneficios_count }}
                            </td>

                            <td class="td-actions text-center">

                                <a href="{{ route('admin.ubicaciones.edit', $ubicacion) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form
                                    action="{{ route('admin.ubicaciones.destroy', $ubicacion) }}"
                                    method="POST"
                                    onsubmit="event.preventDefault(); openDeleteModal(this, 'ubicación')">
                                    <!-- onsubmit="event.preventDefault(); openDeleteModal(this, 'ubicación')"> -->

                                    @csrf
                                    @method('DELETE')

                                    <!-- <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button> -->
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">

                                <div class="empty-state-content">

                                    <i class="fas fa-map-marker-alt empty-icon"></i>

                                    <p>No hay ubicaciones registradas</p>

                                    <a href="{{ route('admin.ubicaciones.create') }}"
                                        class="btn btn-sm btn-primary">
                                        Crear primera ubicación
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

</div>

@endsection
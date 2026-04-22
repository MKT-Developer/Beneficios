@extends('layouts.admin')

@section('title', 'Países')

@section('content')
<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Países</h2>
        <a href="{{ route('admin.pais.create') }}" class="btn btn-primary">+ Nuevo país</a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
    <div data-toast="success" data-message="{{ session('success') }}"></div>
    @endif

    {{-- ERROR CRÍTICO --}}
    @if(session('error'))
    <div data-toast="error" data-message="{{ session('error') }}"></div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div data-toast="error" data-message="{{ $error }}"></div>
    @endforeach
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-paises">
                <thead>
                    <tr>
                        <th>Bandera</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th class="text-center">Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($paises as $pais)
                    <tr>

                        <td data-label="Bandera" class="text-center">
                            @if($pais->flag)
                            <img src="{{ asset('storage/flags/' . $pais->flag) }}"
                                alt="{{ $pais->nombre }}"
                                class="pais-icon">
                            @else
                            <span class="pais-icon">🌐</span>
                            @endif
                        </td>

                        <td data-label="Nombre">
                            <strong>{{ $pais->nombre }}</strong>
                        </td>

                        <td data-label="Código">
                            {{ $pais->codigo }}
                        </td>

                        <td data-label="Estado" class="text-center">
                            <button class="badge {{ $pais->activo ? 'badge-success' : 'badge-danger' }}"
                                data-model="pais"
                                data-id="{{ $pais->id }}">
                                {{ $pais->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>

                        <td data-label="Acciones" class="td-actions">
                            <a href="{{ route('admin.pais.edit', $pais) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.pais.destroy', $pais) }}"
                                method="POST"
                                onsubmit="event.preventDefault(); openDeleteModal(this, 'país')">
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
                        <td colspan="5" class="text-center text-muted">
                            <div style="padding:20px;">
                                <i class="fas fa-globe" style="font-size:26px; opacity:.25;"></i>
                                <p style="margin:8px 0;">No hay países registrados</p>
                                <a href="{{ route('admin.pais.create') }}" class="btn btn-sm btn-primary">
                                    Crear primer país
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

<script>
    window.toggleModel = "pais";
</script>

@endsection
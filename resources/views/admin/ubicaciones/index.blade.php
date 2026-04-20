@extends('layouts.admin')

@section('title', 'Ubicaciones')

@section('content')
<div class="container">
    <div class="header-actions">
        <h1>Ubicaciones</h1>
        <a href="{{ route('admin.ubicaciones.create') }}" class="btn btn-primary">+ Nueva ubicación</a>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Activo</th>
                        <th>Beneficios</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ubicaciones as $ubicacion)
                    <tr>
                        <td>{{ $ubicacion->id }}</td>
                        <td>{{ $ubicacion->nombre }}</td>
                        <td class="text-center">
                            <span class="badge" style="background-color: {{ $ubicacion->activo ? '#10b981' : '#f87171' }}; color:#fff;">
                                {{ $ubicacion->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">{{ $ubicacion->beneficios_count }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.ubicaciones.edit', $ubicacion) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.ubicaciones.destroy', $ubicacion) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar esta ubicación?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay ubicaciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
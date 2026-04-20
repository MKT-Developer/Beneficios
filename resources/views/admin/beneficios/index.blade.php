@extends('layouts.admin')

@section('title', 'Beneficios')

@section('content')
<div class="container">

    <div class="header-actions">
        <h1>Beneficios</h1>
        <a href="{{ route('admin.beneficios.create') }}" class="btn btn-primary">
            + Nuevo beneficio
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Beneficio</th>
                        <th>Pilar</th>
                        <th>País</th>
                        <th>Ubicaciones</th>
                        <th>Orden</th>
                        <th>Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($beneficios as $beneficio)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $beneficio->id }}</td>

                        {{-- LOGO --}}
                        <td>
                            @if($beneficio->logo)
                            <img src="{{ asset('storage/beneficios/' . $beneficio->logo) }}"
                                style="width:32px; height:32px; object-fit:contain;">
                            @endif
                        </td>


                        {{-- Beneficio --}}
                        <td>
                            <a href="{{ route('admin.beneficios.edit', $beneficio) }}"
                                style="font-weight:600; color:#111827; text-decoration:none;">
                                {{ $beneficio->nombre }}
                            </a>
                        </td>

                        {{-- Pilar --}}
                        <td>
                            @if($beneficio->pilar)
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($beneficio->pilar->icono)
                                <img src="{{ asset('storage/pilares/' . $beneficio->pilar->icono) }}"
                                    style="width:24px; height:24px; object-fit:contain;">
                                @endif

                                <a href="{{ route('admin.pilares.edit', $beneficio->pilar) }}"
                                    style="color:#0d6efd; text-decoration:none;">
                                    {{ $beneficio->pilar->nombre }}
                                </a>
                            </div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- País --}}
                        <td>
                            @if($beneficio->pilar && $beneficio->pilar->pais)
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($beneficio->pilar->pais->flag)
                                <img src="{{ asset($beneficio->pilar->pais->flag) }}"
                                    style="width:24px; height:16px; border-radius:4px;">
                                @else
                                <span class="pais-icon">🌍</span>
                                @endif

                                <a href="{{ route('admin.pais.edit', $beneficio->pilar->pais) }}"
                                    style="color:#0d6efd; text-decoration:none;">
                                    {{ $beneficio->pilar->pais->nombre }}
                                </a>
                            </div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Ubicaciones --}}
                        <td>
                            @if($beneficio->ubicaciones->count() === 0)
                            <span class="text-muted">Todas</span>

                            @elseif($beneficio->ubicaciones->count() <= 2)
                                {{ $beneficio->ubicaciones->pluck('nombre')->join(', ') }}

                                @else
                                {{ $beneficio->ubicaciones->count() }} ubicaciones
                                @endif
                                </td>

                                {{-- Orden --}}
                        <td class="text-center">
                            {{ $beneficio->orden ?? '—' }}
                        </td>

                        {{-- Activo --}}
                        <td class="text-center">
                            <span class="badge" style="background-color: {{ $beneficio->activo ? '#10b981' : '#f87171' }}; color:#fff;">
                                {{ $beneficio->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="text-center">
                            <div class="action-buttons">
                                <button class="badge {{ $beneficio->activo ? 'badge-success' : 'badge-danger' }}"
                                    onclick="toggleActivo(this, {{ $beneficio->id }})"
                                    data-model="beneficio">
                                    {{ $beneficio->activo ? 'Activo' : 'Inactivo' }}
                                </button

                                    <form action="{{ route('admin.beneficios.destroy', $beneficio) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar este beneficio?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Eliminar
                                </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No hay beneficios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    window.toggleModel = "beneficio";
</script>

@endsection

<!-- 
** MEJORES PENDIENTES **
- Preview completo del beneficio
- Filtrar por país/pilar
- Grad y drip para ordenar beneficios
- Toggle activo sin recargar
- Contador de beneficios por pilar 
-->
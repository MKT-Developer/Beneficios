@extends('layouts.admin')

@section('title', 'Beneficios')

@section('content')

<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Beneficios</h2>
        <a href="{{ route('admin.beneficios.create') }}" class="btn btn-primary">
            + Nuevo beneficio
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

            <div class="beneficios-controls">

                <div class="table-toolbar">

                    {{-- Buscador --}}
                    <input type="text"
                        id="search"
                        placeholder="Buscar beneficio..."
                        class="input filter-control">

                    {{-- Filtro por país --}}
                    <select id="filter-pais" class="select filter-control">
                        <option value="">Todos los países</option>
                        @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                        @endforeach
                    </select>

                    {{-- Filtro por pilar --}}
                    <select id="filter-pilar" class="select filter-control">
                        <option value="">Todos los pilares</option>
                        @foreach($pilares as $pilar)
                        <option value="{{ $pilar->id }}">{{ $pilar->nombre }}</option>
                        @endforeach
                    </select>

                    {{-- Reset --}}
                    <button onclick="resetFilters()" class="btn btn-sm btn-light">
                        Limpiar
                    </button>

                </div>

                <div id="beneficios-counter">
                    Mostrando <strong>{{ $beneficios->count() }}</strong> beneficios
                </div>
            </div>

            <div class="table-wrapper">

                <!-- LOADER -->
                <div id="table-loader" class="global-loader">
                    <div class="loader-spinner"></div>
                </div>

                <table class="table table-beneficios table-beneficios-responsive">
                    <thead>
                        <tr>
                            <th>Beneficio</th>
                            <th>Pilar</th>
                            <th>País</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="table-body">
                        @include('admin.beneficios.partials.table', ['beneficios' => $beneficios])
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    window.toggleModel = "beneficio";
</script>

@endsection
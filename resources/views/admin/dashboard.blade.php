@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p class="text-muted">Resumen general del sistema RH Beneficios</p>
</div>

<div class="dashboard-cards">

    <a href="{{ route('admin.pais.index') }}" class="dash-card paises">
        <div class="dash-card-icon">
            <i class="fas fa-globe"></i>
        </div>
        <div>
            <h3>Países</h3>
            <p>{{ $paisesTotales }} activos</p>
        </div>
    </a>

    <a href="{{ route('admin.pilares.index') }}" class="dash-card pilares">
        <div class="dash-card-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <h3>Pilares</h3>
            <p>{{ $pilaresActivos }} activos</p>
        </div>
    </a>

    <a href="{{ route('admin.beneficios.index') }}" class="dash-card beneficios">
        <div class="dash-card-icon">
            <i class="fas fa-gift"></i>
        </div>
        <div>
            <h3>Beneficios</h3>
            <p>{{ $beneficiosTotales }} registrados</p>
        </div>
    </a>

    <a href="{{ route('admin.ubicaciones.index') }}" class="dash-card ubicaciones">
        <div class="dash-card-icon">
            <i class="fas fa-city"></i>
        </div>
        <div>
            <h3>Ubicaciones</h3>
            <p>{{ $ubicacionesTotales }} registradas</p>
        </div>
    </a>

</div>

@endsection
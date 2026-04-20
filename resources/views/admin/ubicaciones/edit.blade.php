@extends('layouts.admin')

@section('title', isset($ubicacion) ? 'Editar ubicación' : 'Nueva ubicación')

@section('content')
<div class="admin-page">
    <h2>{{ isset($ubicacion) ? 'Editar ubicación' : 'Nueva ubicación' }}</h2>
    <div class="card max-w-lg">
        <form action="{{ isset($ubicacion) ? route('admin.ubicaciones.update', $ubicacion) : route('admin.ubicaciones.store') }}"
            method="POST">
            @if(isset($ubicacion))
            @method('PUT')
            @endif
            @include('admin.ubicaciones._form')
        </form>
    </div>
</div>
@endsection
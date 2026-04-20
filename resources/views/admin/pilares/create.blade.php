@extends('layouts.admin')

@section('title', 'Crear Pilar')

@section('content')

<h1>Nuevo Pilar</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.pilares.store') }}" enctype="multipart/form-data" method="POST" class="admin-form">
            @include('admin.pilares._form')
        </form>
    </div>
</div>

@endsection
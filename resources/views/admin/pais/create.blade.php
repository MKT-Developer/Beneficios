@extends('layouts.admin')

@section('title', isset($pais) ? 'Editar país' : 'Crear país')

@section('content')
<div class="admin-page">
    <h2 class="text-xl font-bold mb-4">{{ isset($pais) ? 'Editar país' : 'Nuevo país' }}</h2>

    <div class="card max-w-lg">
        <div class="card-body">
            <form
                method="POST"
                action="{{ route('admin.pais.store') }}"
                enctype="multipart/form-data">
                @include('admin.pais._form')
            </form>

        </div>
    </div>
</div>
@endsection
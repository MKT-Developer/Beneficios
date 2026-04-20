@extends('layouts.admin')

@section('title', 'Editar beneficio')

@section('content')
<div class="admin-page">
    <h2>Editar beneficio</h2>

    <div class="card max-w-lg">
        <form
            action="{{ route('admin.beneficios.update', $beneficio) }}"
            method="POST"
            enctype="multipart/form-data">
            @method('PUT')
            @include('admin.beneficios._form')
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Nuevo beneficio')

@section('content')
<div class="admin-page">
    <h2>Nuevo beneficio</h2>

    <div class="card max-w-lg">
        <form
            action="{{ route('admin.beneficios.store') }}"
            method="POST"
            enctype="multipart/form-data">
            @include('admin.beneficios._form')
        </form>
    </div>
</div>
@endsection
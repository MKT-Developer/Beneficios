@extends('layouts.admin')

@section('title', 'Editar Pilar')

@section('content')

<h1>Editar Pilar</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.pilares.update', $pilar) }}" enctype="multipart/form-data" method="POST" class="admin-form">
            @csrf
            @method('PUT')

            @include('admin.pilares._form')
        </form>
    </div>
</div>

@endsection
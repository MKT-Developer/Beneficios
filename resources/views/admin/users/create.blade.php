@extends('layouts.admin')

@section('title', 'Crear Usuario')

@section('content')

<div class="page-header">
    <h1>Crear usuario</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @include('admin.users._form')
    </form>
</div>

@endsection
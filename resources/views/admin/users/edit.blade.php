@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')

<div class="page-header">
    <h1>Editar usuario</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        @include('admin.users._form')
    </form>
</div>

@endsection
@extends('layouts.admin')

@section('title', 'Widget')

@section('content')

<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Widget</h2>
    </div>

    <div class="card" style="height:70vh; padding:0; overflow:hidden;">

        <iframe
            src="{{ url('/widget') }}"
            style="width:100%; height:100%; border:none;">
        </iframe>

    </div>

</div>

@endsection
@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')

<div class="admin-page">

    <div class="header-actions">
        <h2 class="text-xl font-bold">Usuarios</h2>

        @if(auth()->user()->canManageUsers())
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            + Nuevo usuario
        </a>
        @endif
    </div>

    {{-- TOASTS --}}
    @if(session('success'))
    <div data-toast="success" data-message="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
    <div data-toast="error" data-message="{{ session('error') }}"></div>
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div data-toast="error" data-message="{{ $error }}"></div>
    @endforeach
    @endif

    <div class="card">
        <div class="table-responsive">

            <div class="table-wrapper">

                <table class="table table-users">

                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center hide-mobile">Creado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>

                            {{-- USUARIO --}}
                            <td>
                                <div class="beneficio-title">
                                    {{ $user->name }}
                                </div>

                                <div class="beneficio-meta hide-mobile">
                                    ID: {{ $user->id }}
                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td>
                                {{ $user->email }}
                            </td>

                            {{-- ROL --}}
                            <td class="text-center">
                                @php
                                $roleClass = match($user->role) {
                                'superadmin' => 'badge-danger',
                                'admin' => 'badge-warning',
                                'editor' => 'badge-secondary',
                                default => 'badge-secondary'
                                };
                                @endphp

                                <span class="badge {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            {{-- FECHA --}}
                            <td class="text-center hide-mobile">
                                {{ $user->created_at?->format('d/m/Y') }}
                            </td>

                            {{-- ACCIONES --}}
                            <td class="td-actions text-center">

                                {{-- EDITAR --}}
                                @if(!$user->isLocked() && auth()->user()->canManageUsers())
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @endif

                                {{-- ELIMINAR --}}
                                @if(!$user->isLocked() && auth()->user()->canDelete())
                                <form
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST"
                                    onsubmit="event.preventDefault(); openDeleteModal(this, 'usuario')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-content">

                                    <i class="fas fa-users empty-icon"></i>

                                    <p>No hay usuarios registrados</p>

                                    @if(auth()->user()->canManageUsers())
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-sm btn-primary">
                                        Crear primer usuario
                                    </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>

@endsection
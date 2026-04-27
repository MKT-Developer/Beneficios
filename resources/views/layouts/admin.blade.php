<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Admin')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="/admin-assets/images/favicon_mera.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin-assets/images/favicon_mera.png">
    <link rel="apple-touch-icon" href="/admin-assets/images/favicon_mera.png">
</head>



<body class="admin-body">

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img class="logo-full" src="/admin-assets/images/mera_white_tagline.png" alt="Logo MERA">
                <img class="logo-mini" src="/admin-assets/images/favicon_mera.png" alt="Logo MERA">
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    data-tooltip="Dashboard">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.pais.index') }}"
                    class="nav-item {{ request()->routeIs('admin.pais.*') ? 'active' : '' }}"
                    data-tooltip="Países">
                    <i class="fas fa-globe"></i>
                    <span>Países</span>
                </a>

                <a href="{{ route('admin.pilares.index') }}"
                    class="nav-item {{ request()->routeIs('admin.pilares.*') ? 'active' : '' }}"
                    data-tooltip="Pilares">
                    <i class="fas fa-layer-group"></i>
                    <span>Pilares</span>
                </a>

                <a href="{{ route('admin.beneficios.index') }}"
                    class="nav-item {{ request()->routeIs('admin.beneficios.*') ? 'active' : '' }}"
                    data-tooltip="Beneficios">
                    <i class="fas fa-gift"></i>
                    <span>Beneficios</span>
                </a>

                <a href="{{ route('admin.ubicaciones.index') }}"
                    class="nav-item {{ request()->routeIs('admin.ubicaciones.*') ? 'active' : '' }}"
                    data-tooltip="Ubicaciones">
                    <i class="fas fa-city"></i>
                    <span>Ubicaciones</span>
                </a>

                <!-- <a href="javascript:void(0)" class="nav-item disabled" data-tooltip="Widget"> -->
                <a href="{{ route('admin.widget.index') }}"
                    class="nav-item {{ request()->routeIs('admin.widget.*') ? 'active' : '' }}"
                    data-tooltip="Widget">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Widget</span>
                </a>

                @if(auth()->user()->canManageUsers())
                <a href="{{ route('admin.users.index') }}"
                    class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                @endif

            </nav>
        </aside>

        <!-- Proteger links dentro del sidebar (opcional fino) -->
        <!-- @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.users.index') }}" class="nav-item">
            <i class="fas fa-users"></i>
            <span>Usuarios</span>
        </a>
        @endif -->

        <!-- MAIN -->
        <main class="main-content">

            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <!-- HEADER -->
            <header class="admin-header">
                <div class="header-left">
                    <button id="sidebarToggle" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <strong>Panel de Administración</strong>
                </div>

                <!-- USER DROPDOWN -->
                <div class="admin-user">
                    <div class="user-dropdown" id="userDropdown">

                        <div class="user-trigger">
                            <div class="avatar">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>

                            <span>{{ auth()->user()->name ?? 'Usuario' }}</span>

                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="dropdown-menu">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Cerrar sesión</button>
                                <!-- <button type="submit" class="logout-btn">Cerrar sesión</button> -->
                            </form>
                        </div>

                    </div>

                </div>
            </header>

            <!-- CONTENT -->
            <section class="admin-page">
                <div class="page-wrapper">
                    @yield('content')
                </div>
            </section>

        </main>
    </div>

    <div id="toast-container"></div>

    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">

            <h3 id="deleteModalTitle">Eliminar registro</h3>

            <p id="deleteModalText">
                ¿Estás seguro de que deseas eliminar este registro?
            </p>

            <div class="modal-actions">
                <button id="cancelDelete" class="btn btn-secondary">
                    Cancelar
                </button>

                <button id="confirmDelete" class="btn btn-danger">
                    Eliminar
                </button>
            </div>

        </div>
    </div>

    <div id="beneficio-modal" class="modal hidden" data-loading="false">
        <div class="modal-content">

            <div class="modal-header">
                <img id="mb-logo" class="modal-logo" src="" alt="">
                <h3 id="modal-title"></h3>
            </div>

            <div id="modal-body">

                <!-- GENERAL -->
                <div class="modal-section">
                    <h4>Información general</h4>
                    <p id="mb-pilar"></p>
                    <p id="mb-pais"></p>
                </div>

                <!-- DETALLE -->
                <div class="modal-section">
                    <h4>Detalle</h4>
                    <p id="mb-descripcion"></p>
                    <p id="mb-condiciones"></p>
                </div>

                <!-- CONTACTO -->
                <div class="modal-section">
                    <h4>Contacto</h4>
                    <p id="mb-email"></p>
                    <p id="mb-telefono"></p>
                    <p id="mb-sitio"></p>
                </div>

                <!-- OPERATIVO -->
                <div class="modal-section">
                    <h4>Operativo</h4>
                    <p id="mb-activo"></p>
                    <p id="mb-ubicaciones"></p>
                </div>

            </div>

            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal()">Cerrar</button>
                <a id="modal-edit" class="btn btn-warning">Editar</a>
            </div>

        </div>
    </div>

</body>

</html>
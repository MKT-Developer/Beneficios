<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Acceso')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="/admin-assets/images/favicon_mera.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin-assets/images/favicon_mera.png">
    <link rel="apple-touch-icon" href="/admin-assets/images/favicon_mera.png">
</head>

<body class="auth-body">

    <!-- LOADER GLOBAL -->
    <div id="globalLoader" class="global-loader">
        <div class="loader-spinner"></div>
    </div>

    <div class="auth-wrapper">

        <!-- LOGO GLOBAL (TODAS LAS PÁGINAS AUTH) -->
        <div class="auth-logo">
            <img src="{{ asset('admin-assets/images/mera_white_tagline.png') }}" alt="Logo">
        </div>

        @yield('content')
    </div>

    <!-- JS APP -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>
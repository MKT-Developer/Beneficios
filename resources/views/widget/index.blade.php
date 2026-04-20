<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>RH Beneficios Widget</title>
    <link rel="stylesheet" href="{{ asset('widget-assets/widget.css') }}">
</head>

<body>

    <div id="rh-widget-root">
        <div class="widget-container">

            <!-- =====================
            CONTROLES
        ====================== -->
            <div class="top-controls">

                <!-- Selector de país -->
                <div class="pais-selector">
                    <button type="button" class="pais-dropdown-toggle">
                        <span class="pais-selected">
                            <img
                                src="{{ asset('storage/flags/' . $pais->flag) }}"
                                
                                alt="{{ $pais->nombre }}"
                                class="pais-flag">
                            <span class="pais-name">{{ $pais->nombre }}</span>
                        </span>
                        <span class="pais-arrow">▼</span>
                    </button>

                    <ul class="pais-dropdown">
                        @foreach ($paises as $item)
                        <li>
                            <button
                                type="button"
                                class="pais-option {{ $item->id === $pais->id ? 'active' : '' }}"
                                data-pais="{{ $item->codigo }}">
                                <img
                                    src="{{ asset('storage/flags/' . $item->flag) }}"
                                    alt="{{ $item->nombre }}"
                                    class="pais-flag">
                                <span class="pais-name">{{ $item->nombre }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pilares -->
                <div id="pilares" class="pilares-container">
                    <!-- dinámico -->
                </div>
            </div>

            <!-- =====================
            BANNER
        ====================== -->
            <header class="widget-banner">
                <div class="banner-overlay">
                    <!-- <h1>Diseñados para tu bienestar</h1> -->
                    <!-- <p>Descubre beneficios y promociones exclusivas para ti</p> -->
                </div>
            </header>

            <!-- =====================
            HEADER CONTENT
        ===================== -->
            <section class="header-content">
                <h2>Diseñados para tu bienestar</h2>
                <p>
                    Descubre todos los beneficios y promociones especiales diseñados
                    para mejorar tu calidad de vida
                </p>

                <div class="header-info">
                    <div class="info">
                        <h3 id="total-beneficios">25+</h3>
                        <p>Beneficios únicos</p>
                    </div>
                    <div class="info">
                        <h3>Acceso</h3>
                        <p>Nacional & Internacional</p>
                    </div>
                    <div class="info">
                        <h3>24/7</h3>
                        <p>Disponibilidad</p>
                    </div>
                </div>
            </section>


            <!-- =====================
            INFO PILAR
        ====================== -->
            <div id="pilar-info" class="pilar-info">
                <!-- dinámico -->
            </div>

            <!-- =====================
            BENEFICIOS
        ====================== -->
            <div id="beneficios" class="beneficios-container">
                <p class="placeholder">Selecciona un pilar para ver los beneficios</p>
            </div>

        </div>
    </div>


    <script src="{{ asset('widget-assets/widget-app.js') }}"></script>
</body>

</html>
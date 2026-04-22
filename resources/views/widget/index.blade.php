<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH Beneficios Widget</title>
    <link rel="stylesheet" href="{{ asset('widget-assets/widget.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="widget-container">

        <!-- =====================
             CONTROLES
        ===================== -->
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

            <!-- Pilares (dinámico vía JS) -->
            <div id="pilares" class="pilares-container"></div>

        </div>

        <!-- =====================
             BANNER
        ===================== -->
        <header class="widget-banner">
            <div class="banner-overlay"></div>
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
                    <h3>25+</h3>
                    <p>Beneficios únicos</p>
                </div>
                <div class="info">
                    <h3>Acceso</h3>
                    <p>Nacional &amp; Internacional</p>
                </div>
                <div class="info">
                    <h3>24/7</h3>
                    <p>Disponibilidad</p>
                </div>
            </div>
        </section>

        <!-- =====================
             INFO PILAR (dinámico vía JS)
        ===================== -->
        <div id="pilar-info" class="pilar-info"></div>

        <!-- =====================
             BENEFICIOS (dinámico vía JS)
        ===================== -->
        <div id="beneficios" class="beneficios-container">
            <p class="placeholder">Selecciona un pilar para ver los beneficios</p>
        </div>

    </div>{{-- fin .widget-container --}}

    <script src="{{ asset('widget-assets/widget-app.js') }}"></script>
</body>

</html>
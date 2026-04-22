let pais = new URLSearchParams(window.location.search).get('pais') || 'mx';
// PRODUCCION
// const API_BASE = 'https://beneficios.meracorporation.com/api';
// const BASE = 'https://beneficios.meracorporation.com';
// const STORAGE = 'https://beneficios.meracorporation.com/storage';

// LOCAL
const API_BASE = 'http://127.0.0.1:8000/api';
const BASE = 'http://127.0.0.1:8000/';
const STORAGE = 'http://127.0.0.1:8000/storage';

const pilaresContainer = document.getElementById('pilares');
const beneficiosContainer = document.getElementById('beneficios');
const pilarInfo = document.getElementById('pilar-info');

let pilarActivo = null;
let fetchController = null; // Cancela fetch anterior al cambiar de pilar

/* =========================
   Selector de país
========================= */
document.addEventListener('DOMContentLoaded', () => {
    const selector = document.querySelector('.pais-selector');
    if (!selector) return;

    const toggle = selector.querySelector('.pais-dropdown-toggle');
    const dropdown = selector.querySelector('.pais-dropdown');
    const options = selector.querySelectorAll('.pais-option');

    /* Abrir / cerrar dropdown */
    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';
        toggle.classList.toggle('open', !isOpen); // activa rotación de flecha
    });

    /* Seleccionar país */
    options.forEach(option => {
        option.addEventListener('click', () => {
            window.location.href = `?pais=${option.dataset.pais}`;
        });
    });

    /* Cerrar al hacer click fuera */
    document.addEventListener('click', () => {
        dropdown.style.display = 'none';
        toggle.classList.remove('open');
    });
});

/* =========================
   Cargar pilares
========================= */
async function cargarPilares() {
    try {
        const res = await fetch(`${API_BASE}/paises/${pais}/pilares`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const pilares = await res.json();

        pilaresContainer.innerHTML = '';
        beneficiosContainer.innerHTML = '<p class="placeholder">No hay beneficios actualmente</p>';
        pilarInfo.innerHTML = '';

        pilares.forEach((pilar, index) => {
            const btn = document.createElement('button');
            btn.classList.add('pilar-btn');
            btn.dataset.slug = pilar.slug;

            // Ruta consistente con la usada en activarPilar
            const iconUrl = pilar.icono
                ? `${STORAGE}/pilares/${pilar.icono}`
                : `${BASE}/widget-assets/assets/default.png`;

            btn.innerHTML = `<img src="${iconUrl}" alt="${pilar.nombre}" onerror="this.src='${BASE}/widget-assets/assets/default.png'"><span>${pilar.nombre}</span>`;
            btn.addEventListener('click', () => activarPilar(pilar));
            pilaresContainer.appendChild(btn);

            if (index === 0) activarPilar(pilar);
        });
    } catch (err) {
        console.error('Error cargando pilares:', err);
        pilaresContainer.innerHTML = '<p>Error cargando pilares</p>';
    }
}

/* =========================
   Activar pilar
========================= */
function activarPilar(pilar) {
    document.querySelectorAll('.pilar-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.slug === pilar.slug);
    });

    pilarActivo = pilar.slug;

    // Ruta consistente con la usada en cargarPilares
    const iconUrl = pilar.icono
        ? `${STORAGE}/pilares/${pilar.icono}`
        : `${BASE}/widget-assets/assets/default.png`;

    const iconoHTML = `<img src="${iconUrl}" alt="${pilar.nombre}" onerror="this.src='${BASE}/widget-assets/assets/default.png'">`;
    const descripcionHTML = pilar.descripcion || '';

    pilarInfo.innerHTML = `<h2>${pilar.nombre.toUpperCase()}</h2><p>${iconoHTML}${descripcionHTML}</p>`;

    cargarBeneficios();
}

/* =========================
   Cargar beneficios
========================= */
async function cargarBeneficios() {
    // Cancela fetch anterior si aún está en vuelo
    if (fetchController) fetchController.abort();
    fetchController = new AbortController();
    const { signal } = fetchController;

    // Skeleton mientras cargan los beneficios
    beneficiosContainer.innerHTML = `
        <div class="beneficio-card skeleton" style="height:220px"></div>
        <div class="beneficio-card skeleton" style="height:220px"></div>
        <div class="beneficio-card skeleton" style="height:220px"></div>`;

    try {
        const res = await fetch(`${API_BASE}/pilares/${pilarActivo}/beneficios`, { signal });

        if (!res.ok) {
            console.warn(`Error cargando beneficios: ${res.status} ${res.statusText}`);
            beneficiosContainer.innerHTML = '<p>No se pudieron cargar los beneficios. Intenta más tarde.</p>';
            return;
        }

        const data = await res.json();

        if (!data.beneficios?.length) {
            beneficiosContainer.innerHTML = '<p>No hay beneficios disponibles.</p>';
            return;
        }

        beneficiosContainer.innerHTML = '';
        data.beneficios.forEach(b => renderBeneficio(b));

    } catch (err) {
        if (err.name === 'AbortError') return; // Cambio de pilar — ignorar
        console.error('Error cargando beneficios:', err);
        beneficiosContainer.innerHTML = `<p>Error cargando beneficios: ${err.message}</p>`;
    }
}

/* =========================
   Render card de beneficio
========================= */
function renderBeneficio(b) {
    const card = document.createElement('div');
    card.classList.add('beneficio-card');

    const logoUrl = b.logo || `${BASE}/widget-assets/assets/default-beneficio.png`;
    const bulletsPreview = generarPreviewBullets(b.descripcion);

    card.innerHTML = `
        <div class="card-header">
            <img src="${logoUrl}" alt="${b.nombre}">
        </div>

        <div class="card-body">

            <h3>${b.nombre}</h3>

            <ul class="preview-list">
                ${bulletsPreview}
            </ul>

            <!-- EXPANDIBLE -->
            <div class="extra-content">

                ${b.beneficios ? `
                    <div class="card-section">
                        <h4>Beneficios</h4>
                        ${formatearTexto(b.beneficios)}
                    </div>
                ` : ''}

                ${b.condiciones ? `
                    <div class="card-section">
                        <h4>Condiciones</h4>
                        ${formatearTexto(b.condiciones)}
                    </div>
                ` : ''}

                <!-- UBICACIONES -->
                <div class="ubicaciones-wrapper"></div>

            </div>

            <!-- BOTÓN -->
            <button class="ver-mas-btn">Ver más</button>

            <!-- CONTACTO -->
            <div class="contacto">

                ${b.redsocial ? `
                    <a href="https://instagram.com/${b.redsocial.replace('@', '')}" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                ` : ''}

                ${b.sitio ? `
                    <a href="${b.sitio}" target="_blank">
                        <i class="fas fa-globe"></i>
                    </a>
                ` : ''}

                ${b.telefono ? `
                    <a href="tel:${b.telefono.replace(/\s/g, '')}">
                        <i class="fas fa-phone"></i>
                    </a>
                ` : ''}

                ${b.correo ? `
                    <a href="mailto:${b.correo}">
                        <i class="fas fa-envelope"></i>
                    </a>
                ` : ''}

            </div>

        </div>
    `;

    // =========================
    // UBICACIONES
    // =========================
    const wrapper = card.querySelector('.ubicaciones-wrapper');
    wrapper.appendChild(generarUbicaciones(b));

    // =========================
    // TOGGLE VER MÁS
    // =========================
    const btn = card.querySelector('.ver-mas-btn');
    const extra = card.querySelector('.extra-content');

    btn.addEventListener('click', () => {
        const expanded = card.classList.toggle('expanded');

        btn.textContent = expanded ? 'Ver menos' : 'Ver más';

        if (extra) {
            extra.style.display = expanded ? 'block' : 'none';
        }

        const extraUbicaciones = card.querySelector('.ubicaciones-extra');
        const moreLabel = card.querySelector('.chips-more');

        if (extraUbicaciones) {
            extraUbicaciones.style.display = expanded ? 'flex' : 'none';
        }

        if (moreLabel) {
            moreLabel.style.display = expanded ? 'none' : 'inline-block';
        }
    });

    beneficiosContainer.appendChild(card);
}

function generarPreviewBullets(texto = '') {
    if (!texto) return '';

    return texto
        .split('\n')
        .filter(l => l.trim())
        .slice(0, 3)
        .map(l => `<li>${l.replace('•', '').trim()}</li>`)
        .join('');
}

function formatearTexto(texto = '') {
    if (!texto) return '';

    return texto
        .replace(/(💸.*|📍.*)/g, '<h4>$1</h4>')
        .replace(/•\s(.+)/g, '<li>$1</li>')
        .replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>');
}

function generarUbicaciones(b) {
    const ubicaciones = b.ubicaciones || [];
    const maxVisibles = 4;

    const container = document.createElement('div');
    container.classList.add('card_paises');

    const wrapper = document.createElement('div');
    wrapper.classList.add('chips-wrapper');

    const visibles = ubicaciones.slice(0, maxVisibles);
    const resto = ubicaciones.length - maxVisibles;

    visibles.forEach(u => {
        const chip = document.createElement('span');
        chip.classList.add('chip');
        chip.textContent = u.nombre;
        wrapper.appendChild(chip);
    });

    container.appendChild(wrapper);

    if (resto > 0) {
        const moreLabel = document.createElement('span');
        moreLabel.classList.add('chips-more');
        moreLabel.textContent = `+${resto} ubicaciones`;
        container.appendChild(moreLabel);
    }

    const extra = document.createElement('div');
    extra.classList.add('ubicaciones-extra');

    ubicaciones.slice(maxVisibles).forEach(u => {
        const chip = document.createElement('span');
        chip.classList.add('chip');
        chip.textContent = u.nombre;
        extra.appendChild(chip);
    });

    container.appendChild(extra);

    return container;
}

// function renderBeneficio(b) {
//     const card = document.createElement('div');
//     card.classList.add('beneficio-card');

//     const logoUrl = b.logo
//         ? `${b.logo}`
//         : `${BASE}/widget-assets/assets/default-beneficio.png`;

//     const ubicacionesHTML = b.ubicaciones?.map(u => `<span class="chip">${u.nombre}</span>`).join('') || 'N/A';

//     card.innerHTML = `
//         <div class="card-header">
//             <img src="${logoUrl}" alt="${b.nombre}" onerror="this.src='${BASE}/widget-assets/assets/default-beneficio.png'">
//         </div>
//         <div class="card-body">
//             <h3>${b.nombre}</h3>
//             <p class="descripcion">${b.descripcion}</p>
//             ${b.condiciones ? `<p class="condiciones">${b.condiciones}</p>` : ''}
//             <div class="contacto">
//                 ${b.redsocial ? `<div class="card_redsocial">${b.redsocial}</div>` : ''}
//                 ${b.sitio ? `<div class="card_sitio">${b.sitio}</div>` : ''}
//                 ${b.telefono ? `<div class="card_telefono">${b.telefono}</div>` : ''}
//                 ${b.correo ? `<div class="card_correo">${b.correo}</div>` : ''}
//             </div>
//             <div class="card_paises">
//                 Disponible en: ${ubicacionesHTML}
//             </div>
//         </div>`;

//     beneficiosContainer.appendChild(card);
// }

/* =========================
   Init
========================= */
cargarPilares();
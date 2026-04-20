let pais = new URLSearchParams(window.location.search).get('pais') || 'mx';
const API_BASE = 'https://beneficios.meracorporation.com/api';
const BASE = 'https://beneficios.meracorporation.com';
const STORAGE = 'https://beneficios.meracorporation.com/storage';

const pilaresContainer = document.getElementById('pilares');
const beneficiosContainer = document.getElementById('beneficios');
const pilarInfo = document.getElementById('pilar-info');

let pilarActivo = null;

/* =========================
   Selector de país
========================= */
document.addEventListener('DOMContentLoaded', () => {
    const selector = document.querySelector('.pais-selector');
    if (!selector) return;

    const toggle = selector.querySelector('.pais-dropdown-toggle');
    const dropdown = selector.querySelector('.pais-dropdown');
    const options = selector.querySelectorAll('.pais-option');

    /* =========================
       Abrir / cerrar dropdown
    ========================= */
    toggle.addEventListener('click', (e) => {
        e.stopPropagation();

        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';
    });

    /* =========================
       Seleccionar país
    ========================= */
    options.forEach(option => {
        option.addEventListener('click', () => {
            const pais = option.dataset.pais;
            window.location.href = `?pais=${pais}`;
        });
    });

    /* =========================
       Cerrar al hacer click fuera
    ========================= */
    document.addEventListener('click', () => {
        dropdown.style.display = 'none';
    });
});



/* =========================
   Banner
========================= */
function actualizarBanner() {
    const banner = document.querySelector('.widget-banner');
    if (!banner) return;

    banner.style.backgroundImage = `url('${BASE}/widget-assets/assets/portada_desk.png')`;
}

/* =========================
   Header content
========================= */
function renderHeaderContent() {
    const header = document.querySelector('.header_content');
    if (!header) return;

    header.innerHTML = `
        <h2>Diseñados para tu bienestar</h2>
        <p>Descubre todos los beneficios y promociones especiales diseñados para mejorar tu calidad de vida</p>
        <div class="header_info">
            <div class="info"><h3>25+</h3><p>Beneficios Únicos</p></div>
            <div class="info"><h3>25+</h3><p>Beneficios Únicos</p></div>
            <div class="info"><h3>25+</h3><p>Beneficios Únicos</p></div>
        </div>
    `;
}

/* =========================
   Cargar pilares
========================= */
async function cargarPilares() {
    try {
        const res = await fetch(`${API_BASE}/paises/${pais}/pilares`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const pilares = await res.json();

        pilaresContainer.innerHTML = '';
        beneficiosContainer.innerHTML = '<p class="placeholder">Selecciona un pilar para ver los beneficios</p>';
        pilarInfo.innerHTML = '';

        pilares.forEach((pilar, index) => {
            const btn = document.createElement('button');
            btn.classList.add('pilar-btn');
            btn.dataset.slug = pilar.slug;

            // Ruta completa para icono de pilar
            const iconUrl = pilar.icono ? `${STORAGE}/pilares/${pilar.icono}` : `${BASE}/widget-assets/assets/default.png`;

            btn.innerHTML = `<img src="${iconUrl}" alt="${pilar.nombre}" onerror="this.src='${BASE}/widget-assets/assets/default.png'"><span>${pilar.nombre}</span>`;
            btn.onclick = () => activarPilar(pilar);
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
    document.querySelectorAll('.pilar-btn').forEach(b => b.classList.remove('active'));

    Array.from(pilaresContainer.children).forEach(btn => {
        if (btn.dataset.slug === pilar.slug) btn.classList.add('active');
    });

    pilarActivo = pilar.slug;

    const iconUrl = pilar.icono ? `${STORAGE}/${pilar.icono}` : `${BASE}/widget-assets/assets/default.png`;
    const iconoHTML = `<img src="${iconUrl}" alt="${pilar.nombre}" onerror="this.src='${BASE}/widget-assets/assets/default.png'">`;
    const descripcionHTML = pilar.descripcion || '';

    pilarInfo.innerHTML = `<h2>${pilar.nombre.toUpperCase()}</h2><p>${iconoHTML}${descripcionHTML}</p>`;

    cargarBeneficios();
}

/* =========================
   Cargar beneficios
========================= */
async function cargarBeneficios() {
    // Skeleton mientras cargan los beneficios
    beneficiosContainer.innerHTML = `
      <div class="beneficio-card skeleton" style="height:220px"></div>
      <div class="beneficio-card skeleton" style="height:220px"></div>
      <div class="beneficio-card skeleton" style="height:220px"></div>`;

    try {
        // Ruta correcta, sin pais
        const res = await fetch(`${API_BASE}/pilares/${pilarActivo}/beneficios`);
        if (!res.ok) {
            console.warn(`Error cargando beneficios: ${res.status} ${res.statusText}`);
            beneficiosContainer.innerHTML = '<p>No se pudieron cargar los beneficios. Intenta más tarde.</p>';
            return;
        }

        const data = await res.json();

        if (!data.beneficios || !data.beneficios.length) {
            beneficiosContainer.innerHTML = '<p>No hay beneficios disponibles.</p>';
            return;
        }

        beneficiosContainer.innerHTML = '';

        data.beneficios.forEach(b => renderBeneficio(b));

    } catch (err) {
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

    console.log(b.logo);

    // Logo: fallback por defecto
    const logoUrl = b.logo
        ? `${b.logo}` // añade la carpeta "beneficios/"
        : `${BASE}/widget-assets/assets/default-beneficio.png`;


    const logoHTML = `<img src="${logoUrl}" alt="${b.nombre}">`;

    // Ubicaciones
    const ubicacionesHTML = b.ubicaciones?.map(u => `<span class="chip">${u.nombre}</span>`).join('') || 'N/A';

    card.innerHTML = `
        <div class="card-header">${logoHTML}</div>
        <div class="card-body">
            <h3>${b.nombre}</h3>
            <p class="descripcion">${b.descripcion}</p>
            ${b.condiciones ? `<p class="condiciones">${b.condiciones}</p>` : ''}
            <div class="contacto">
                ${b.redsocial ? `<div class="card_redsocial">${b.redsocial}</div>` : ''}
                ${b.sitio ? `<div class="card_sitio">${b.sitio}</div>` : ''}
                ${b.telefono ? `<div class="card_telefono">${b.telefono}</div>` : ''}
                ${b.correo ? `<div class="card_correo">${b.correo}</div>` : ''}
            </div>
            <div class="card_paises">
                Disponible en: ${ubicacionesHTML}
            </div>
        </div>
    `;

    beneficiosContainer.appendChild(card);
}

/* =========================
   Init
========================= */
actualizarBanner();
renderHeaderContent();
cargarPilares();

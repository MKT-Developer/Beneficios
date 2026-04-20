(function () {
    // Crear iframe embebible
    const iframe = document.createElement('iframe');

    // URL del widget sin slash al final para evitar 403
    const params = new URLSearchParams(window.location.search);
    const pais = params.get('pais') || 'mx';
    iframe.src = `https://beneficios.meracorporation.com/widget?pais=${pais}`;

    iframe.width = '100%';
    iframe.height = '800';
    iframe.style.border = '0';
    iframe.loading = 'lazy';

    // Insertar el iframe antes del script que lo llama
    document.currentScript.parentNode.insertBefore(
        iframe,
        document.currentScript
    );
})();

// ==========================
// Código opcional para debug / pruebas en el mismo contexto
// ==========================
const API = 'https://beneficios.meracorporation.com/api';

async function cargarPilares() {
    const params = new URLSearchParams(window.location.search);
    const pais = params.get('pais') || 'mx';

    const res = await fetch(`${API}/paises/${pais}/pilares`);
    const data = await res.json();

    const contenedor = document.getElementById('pilares');
    if (!contenedor) return;

    contenedor.innerHTML = '';

    data.forEach((pilar, index) => {
        const btn = document.createElement('button');
        btn.textContent = pilar.nombre;
        btn.onclick = () => cargarBeneficios(pilar.slug);
        contenedor.appendChild(btn);

        if (index === 0) cargarBeneficios(pilar.slug);
    });
}

// async function cargarBeneficios(slug) {
//     const res = await fetch(`${API}/pilares/${slug}/beneficios`);
//     const data = await res.json();

//     const contenedor = document.getElementById('beneficios');
//     contenedor.innerHTML = '';

//     data.beneficios.forEach(b => {
//         contenedor.innerHTML += `
//             <div class="card">
//                 ${b.logo ? `<img src="${b.logo}" alt="${b.nombre}">` : ''}
//                 <h3>${b.nombre}</h3>
//                 <p>${b.descripcion}</p>
//                 ${b.condiciones ? `<small>${b.condiciones}</small>` : ''}
//                 ${b.contacto ? `<div class="contacto">${b.contacto}</div>` : ''}
//                 <div class="paises">${b.paises_disponibles ?? ''}</div>
//             </div>
//         `;
//     });
// }

// cargarPilares();


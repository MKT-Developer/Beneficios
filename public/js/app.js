document.addEventListener("DOMContentLoaded", () => {

    /* =========================
       PASSWORD TOGGLES
    ========================= */

    window.toggleRegisterPassword = function () {
        const input = document.getElementById("registerPassword");
        const open = document.getElementById("regEyeOpen");
        const closed = document.getElementById("regEyeClosed");

        if (!input) return;

        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";

        if (open && closed) {
            open.style.display = isHidden ? "block" : "none";
            closed.style.display = isHidden ? "none" : "block";
        }
    };

    window.toggleRegisterPasswordConfirm = function () {
        const input = document.getElementById("registerPasswordConfirm");
        const open = document.getElementById("regEyeOpenConfirm");
        const closed = document.getElementById("regEyeClosedConfirm");

        if (!input) return;

        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";

        if (open && closed) {
            open.style.display = isHidden ? "block" : "none";
            closed.style.display = isHidden ? "none" : "block";
        }
    };

    /* =========================
       EMAIL VALIDATION
    ========================= */

    const email = document.getElementById("email");

    if (email) {
        email.addEventListener("input", () => {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            email.classList.remove("input-valid", "input-invalid");

            if (email.value === "") return;

            email.classList.add(regex.test(email.value) ? "input-valid" : "input-invalid");
        });
    }

    /* =========================
       REGISTER VALIDATION
    ========================= */

    const registerPassword = document.getElementById("registerPassword");
    const registerConfirm = document.getElementById("registerPasswordConfirm");

    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");
    const matchText = document.getElementById("matchText");
    const registerBtn = document.getElementById("registerBtn");

    function checkStrength(value) {
        let strength = 0;

        if (value.length >= 6) strength++;
        if (value.length >= 10) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[^A-Za-z0-9]/.test(value)) strength++;

        return strength;
    }

    function validateRegister() {
        if (!registerPassword || !registerConfirm || !registerBtn) return;

        const pass = registerPassword.value;
        const confirm = registerConfirm.value;

        const strength = checkStrength(pass);
        const isStrong = strength >= 3;
        const isMatch = pass === confirm && confirm.length > 0;

        if (strengthBar) {
            const width = (strength / 5) * 100;
            strengthBar.style.width = width + "%";

            if (strength <= 1) {
                strengthBar.style.background = "#dc2626";
                strengthText && (strengthText.textContent = "Débil");
            } else if (strength <= 3) {
                strengthBar.style.background = "#f59e0b";
                strengthText && (strengthText.textContent = "Media");
            } else {
                strengthBar.style.background = "#16a34a";
                strengthText && (strengthText.textContent = "Fuerte");
            }
        }

        if (matchText) {
            if (confirm.length === 0) {
                matchText.textContent = "";
                matchText.className = "";
            } else {
                matchText.textContent = isMatch
                    ? "✔ Las contraseñas coinciden"
                    : "✖ No coinciden";

                matchText.className = isMatch ? "match-ok" : "match-bad";
            }
        }

        registerBtn.disabled = !(isStrong && isMatch);
        registerBtn.style.opacity = registerBtn.disabled ? "0.5" : "1";
        registerBtn.style.cursor = registerBtn.disabled ? "not-allowed" : "pointer";
    }

    if (registerPassword && registerConfirm) {
        registerPassword.addEventListener("input", validateRegister);
        registerConfirm.addEventListener("input", validateRegister);
    }

    /* =========================
       LOADER
    ========================= */

    const loader = document.getElementById("globalLoader");

    function showLoader() {
        if (loader) loader.classList.add("active");
    }

    window.showLoader = showLoader;

    window.addEventListener("load", () => {
        if (loader) loader.classList.remove("active");
    });

    /* =========================
       LOGIN / REGISTER
    ========================= */

    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", () => {
            const btn = document.getElementById("loginBtn");

            if (btn) {
                btn.disabled = true;
                btn.innerText = "Entrando...";
            }

            showLoader();
        });
    }

    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", () => {
            const btn = document.getElementById("registerBtn");

            if (btn) {
                btn.disabled = true;
                btn.innerText = "Creando cuenta...";
            }

            showLoader();
        });
    }

    /* =========================
       REMEMBER EMAIL
    ========================= */

    const rememberCheckbox = document.getElementById("remember");

    if (email && rememberCheckbox) {
        const saved = localStorage.getItem("rememberEmail");

        if (saved) email.value = saved;

        email.addEventListener("input", () => {
            if (rememberCheckbox.checked) {
                localStorage.setItem("rememberEmail", email.value);
            }
        });
    }

    /* =========================
       SIDEBAR (PRO VERSION)
    ========================= */

    const toggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    if (toggle && sidebar) {

        toggle.addEventListener("click", () => {

            // 📱 MOBILE
            if (window.innerWidth <= 980) {
                sidebar.classList.toggle("open");
                sidebar.classList.remove("collapsed");
                overlay?.classList.toggle("active");
            }

            // 💻 DESKTOP
            else {
                const isCollapsed = sidebar.classList.toggle("collapsed");
                localStorage.setItem("sidebar", isCollapsed);
            }

        });

        // restore desktop state
        if (window.innerWidth > 980 && localStorage.getItem("sidebar") === "true") {
            sidebar.classList.add("collapsed");
        }

        // close mobile overlay
        overlay?.addEventListener("click", () => {
            sidebar.classList.remove("open");
            overlay.classList.remove("active");
        });
    }

    /* =========================
       SWIPE GESTURE (IMPROVED)
    ========================= */

    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    document.addEventListener("touchstart", (e) => {
        if (window.innerWidth > 980) return;

        startX = e.touches[0].clientX;
        currentX = startX;
        isDragging = true;

    });

    document.addEventListener("touchmove", (e) => {
        if (!isDragging) return;

        currentX = e.touches[0].clientX;
    });

    document.addEventListener("touchend", () => {
        if (!isDragging) return;

        const diff = currentX - startX;

        // Ignorar micro-movimientos
        if (Math.abs(diff) < 60) {
            isDragging = false;
            return;
        }

        if (diff > 60) {
            sidebar?.classList.add("open");
            overlay?.classList.add("active");
        }

        if (diff < -60) {
            sidebar?.classList.remove("open");
            overlay?.classList.remove("active");
        }

        isDragging = false;
    });

    /* =========================
       RESPONSIVE RESET
    ========================= */

    window.addEventListener("resize", () => {

        const width = window.innerWidth;

        if (width > 980) {
            overlay?.classList.remove("active");
            sidebar?.classList.remove("open");
        }

        if (width <= 980) {
            sidebar?.classList.remove("collapsed");
        }
    });

    /* =========================
       USER DROPDOWN
    ========================= */
    const dropdown = document.getElementById("userDropdown");

    if (dropdown) {

        const trigger = dropdown.querySelector(".user-trigger");

        if (trigger) {
            trigger.addEventListener("click", (e) => {
                e.stopPropagation();
                dropdown.classList.toggle("active");
            });
        }

        document.addEventListener("click", (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove("active");
            }
        });

        const menu = dropdown.querySelector(".dropdown-menu");

        if (menu) {
            menu.addEventListener("click", (e) => {
                e.stopPropagation();
            });
        }
    }

    /* =========================
       PAGE TRANSITION
    ========================= */

    document.querySelectorAll(".nav-item").forEach(link => {
        link.addEventListener("click", function () {
            if (!this.classList.contains("disabled")) {
                document.body.classList.add("loading");
            }
        });
    });

    /* =========================
       TOAST SYSTEM
    ========================= */

    window.showToast = function (message, type = "success") {
        const container = document.getElementById("toast-container");
        if (!container) return;

        const toast = document.createElement("div");
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<span>${message}</span>`;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateX(20px)";
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };

    /* =========================
       AUTO TOAST FROM BLADE
    ========================= */

    document.querySelectorAll("[data-toast]").forEach(el => {
        window.showToast(el.dataset.message, el.dataset.toast);
        el.remove();
    });

    /* =========================
       DELETE MODAL (FIXED)
    ========================= */
    let deleteForm = null;
    let deleteLabel = "registro";

    window.openDeleteModal = function (form, label = "registro") {

        deleteForm = form;
        deleteLabel = label;

        form.dataset.modalActive = "true";

        const modal = document.getElementById("deleteModal");

        const title = document.getElementById("deleteModalTitle");
        const text = document.getElementById("deleteModalText");

        if (title) title.textContent = `Eliminar ${label}`;
        // if (text) text.textContent = `¿Estás seguro de que deseas eliminar este registro de tipo ${label}?`;
        if (text) text.textContent = `¿Estás seguro de que deseas eliminar este elemento?`;


        modal?.classList.remove("hidden");
    };

    window.closeDeleteModal = function () {
        if (deleteForm) {
            deleteForm.dataset.modalActive = "false";
        }
    };

    const modal = document.getElementById("deleteModal");
    const cancelBtn = document.getElementById("cancelDelete");
    const confirmBtn = document.getElementById("confirmDelete");

    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            modal?.classList.add("hidden");

            if (deleteForm) {
                const btn = deleteForm.querySelector("button");
                if (btn) {
                    btn.classList.remove("btn-loading");
                    btn.disabled = false;
                }
            }

            deleteForm = null;
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
            if (!deleteForm) return;

            const btn = deleteForm.querySelector("button[type='submit']");

            if (btn) {
                btn.classList.add("btn-loading");
                btn.disabled = true;
            }

            modal?.classList.add("hidden");

            deleteForm.submit();
        });
    }

    /* =========================
       GLOBAL FORM LOADER (SAFE)
    ========================= */
    // document.addEventListener("submit", (e) => {
    //     const form = e.target;

    //     // 🚨 evitar doble loader si viene del modal delete
    //     if (form.dataset.modalActive === "true") return;

    //     if (form.dataset.noLoader === "true") return;

    //     const btn = form.querySelector("button");

    //     if (btn) {
    //         btn.classList.add("btn-loading");
    //         btn.disabled = true;
    //     }
    // });

    /* =========================
       TOGGLE PAIS
    ========================= */
    const baseToggleUrl = "{{ route('admin.toggle', ['model' => ':model', 'id' => ':id']) }}";

    document.addEventListener("click", function (e) {

        const btn = e.target.closest("[data-model][data-id]");
        if (!btn) return;

        const model = btn.dataset.model;
        const id = btn.dataset.id;

        const url = baseToggleUrl
            .replace(':model', model)
            .replace(':id', id);

        fetch(url, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
            .then(r => r.json())
            .then(data => {

                if (!data.success) return;

                btn.classList.toggle("badge-success");
                btn.classList.toggle("badge-danger");

                btn.textContent = data.activo ? "Activo" : "Inactivo";

                showToast("Estado actualizado", "success");
            })
            .catch(() => {
                showToast("Error al actualizar estado", "error");
            });
    });

    /* =========================
        FILTROS
    ========================= */

    let timeout = null;
    let sortableInstance = null;
    let controller = null;

    function hasFilters() {
        const search = document.getElementById('search');
        const pais = document.getElementById('filter-pais');
        const pilar = document.getElementById('filter-pilar');

        return (
            (search && search.value) ||
            (pais && pais.value) ||
            (pilar && pilar.value)
        );
    }

    function initSortable() {
        const el = document.getElementById('table-body');
        if (!el) return;

        if (sortableInstance && sortableInstance.el && document.body.contains(sortableInstance.el)) {
            sortableInstance.destroy();
        }

        if (hasFilters()) return;

        sortableInstance = new Sortable(el, {
            animation: 150,
            ghostClass: 'dragging',
            chosenClass: 'drag-chosen',
            onEnd: function () {
                let order = [];

                document.querySelectorAll('#table-body tr').forEach((row, index) => {
                    if (!row.dataset.id) return;

                    order.push({
                        id: row.dataset.id,
                        orden: index + 1
                    });
                });

                fetch('/admin/beneficios/reordenar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order })
                });
            }
        });
    }

    /* =========================
        FETCH BENEFICIOS (FIXED)
    ========================= */

    function fetchBeneficios() {

        if (controller) controller.abort();
        controller = new AbortController();

        const search = document.getElementById('search').value;
        const pais = document.getElementById('filter-pais').value;
        const pilar = document.getElementById('filter-pilar').value;

        const params = new URLSearchParams({ search, pais, pilar });

        const table = document.getElementById('table-body');

        // UI loading state
        showTableLoader();

        if (table) {
            table.style.opacity = "0.5";
            table.style.pointerEvents = "none";
        }

        if (sortableInstance) {
            try { sortableInstance.destroy(); } catch (e) { }
            sortableInstance = null;
        }

        fetch(`/admin/beneficios?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: controller.signal
        })
            .then(res => {
                if (!res.ok) throw new Error('Server error');
                return res.json();
            })
            .then(data => {

                if (table) table.innerHTML = data.html;

                const counter = document.getElementById('beneficios-counter');
                if (counter) {
                    counter.innerHTML = `Mostrando <strong>${data.count}</strong> beneficios`;
                }

                initSortable();

            })
            .catch(err => {

                if (err.name === 'AbortError') return;

                console.error(err);
            })
            .finally(() => {

                // SIEMPRE limpiar UI
                hideTableLoader();

                if (table) {
                    table.style.opacity = "1";
                    table.style.pointerEvents = "auto";
                }
            });
    }

    /* =========================
        EVENTOS FILTROS
    ========================= */

    const searchInput = document.getElementById('search');

    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            clearTimeout(timeout);
            timeout = setTimeout(fetchBeneficios, 300);
        });
    }

    window.resetFilters = function () {
        document.getElementById('search').value = '';
        document.getElementById('filter-pais').value = '';
        document.getElementById('filter-pilar').value = '';
        fetchBeneficios();
    };

    const filterPais = document.getElementById('filter-pais');
    const filterPilar = document.getElementById('filter-pilar');

    if (filterPais) {
        filterPais.addEventListener('change', fetchBeneficios);
    }

    if (filterPilar) {
        filterPilar.addEventListener('change', fetchBeneficios);
    }

    initSortable();

    /* =========================
        LOADER TABLE
    ========================= */

    function showTableLoader() {
        document.getElementById('table-loader')?.classList.add('active');
    }

    function hideTableLoader() {
        document.getElementById('table-loader')?.classList.remove('active');
    }

    /* =========================
        PREVIEW BENEFICIO
    ========================= */

    function previewBeneficio(id) {

        const modal = document.getElementById('beneficio-modal');

        if (modal.dataset.loading === "true") return;
        modal.dataset.loading = "true";

        showLoader();

        fetch(`/admin/beneficios/${id}`)
            .then(res => res.json())
            .then(data => {

                const logo = document.getElementById('mb-logo');
                if (logo) {
                    logo.src = data.logo
                        ? `/storage/beneficios/${data.logo}`
                        : '/admin-assets/images/default.png';
                }

                document.getElementById('mb-pilar').innerHTML =
                    `<strong>Pilar:</strong> ${data.pilar ?? '—'}`;

                document.getElementById('mb-pais').innerHTML =
                    `<strong>País:</strong> ${data.pais ?? '—'}`;

                document.getElementById('mb-descripcion').innerHTML =
                    `<strong>Descripción:</strong> ${truncateText(data.descripcion)}`;

                document.getElementById('mb-condiciones').innerHTML =
                    `<strong>Condiciones:</strong> ${truncateText(data.condiciones)}`;

                document.getElementById('mb-email').innerHTML =
                    `<strong>Email:</strong> ${data.correo ?? '—'}`;

                document.getElementById('mb-telefono').innerHTML =
                    `<strong>Teléfono:</strong> ${data.telefono ?? '—'}`;

                document.getElementById('mb-sitio').innerHTML =
                    `<strong>Sitio:</strong> ${data.sitio ?? '—'}`;

                document.getElementById('mb-activo').innerHTML =
                    `<strong>Estado:</strong> ${renderEstado(data.activo)}`;

                document.getElementById('mb-ubicaciones').innerHTML =
                    `<strong>Ubicaciones:</strong> ${renderUbicaciones(data.ubicaciones)}`;

                document.getElementById('modal-edit').href =
                    `/admin/beneficios/${id}/edit`;

                modal.classList.remove('hidden');
            })
            .finally(() => {
                modal.dataset.loading = "false";
            });
    }

    /* =========================
        HELPERS MODAL
    ========================= */

    function renderEstado(activo) {
        return activo
            ? `<span class="badge badge-success">Activo</span>`
            : `<span class="badge badge-warning">Inactivo</span>`;
    }

    function truncateText(text, limit = 120) {
        if (!text) return '—';

        if (text.length <= limit) return `<span>${text}</span>`;

        const short = text.slice(0, limit);

        return `
        <span class="text-truncated">${short}...</span>
        <span class="text-full hidden">${text}</span>
        <button class="btn-link" onclick="toggleText(this)">Ver más</button>
    `;
    }

    window.toggleText = function (btn) {
        const container = btn.parentElement;
        container.querySelector('.text-truncated').classList.toggle('hidden');
        container.querySelector('.text-full').classList.toggle('hidden');

        btn.innerText = btn.innerText === 'Ver más' ? 'Ver menos' : 'Ver más';
    };

    function renderUbicaciones(ubicaciones) {
        if (!ubicaciones || ubicaciones.length === 0) return '—';

        return `<ul class="modal-list">
        ${ubicaciones.map(u => `<li>${u}</li>`).join('')}
    </ul>`;
    }

    /* =========================
        MODAL CLOSE OUTSIDE
    ========================= */
    document.getElementById('beneficio-modal')?.addEventListener('click', (e) => {
        if (e.target.id === 'beneficio-modal') closeModal();
    });

    function closeModal() {
        document.getElementById('beneficio-modal').classList.add('hidden');
    }

    window.previewBeneficio = previewBeneficio;
    window.closeModal = closeModal;

    /* =========================
       VALIDACIÓN GLOBAL REUTILIZABLE
    ========================= */
    const errorCache = new WeakMap();
    const touched = new WeakMap();

    const validators = {
        text: (input) => {
            const value = input.value.trim();
            const min = +input.dataset.min || 0;
            // const min = input.dataset.min ? +input.dataset.min : 0;
            // const max = input.dataset.max ? +input.dataset.max : Infinity;
            const max = +input.dataset.max || Infinity;
            const required = input.dataset.required === "1";

            if (required && !value) return input.dataset.message || "Requerido";

            if (value.length < min) {
                return input.dataset.message || `Mínimo ${min} caracteres`;
            }

            if (value.length > max) {
                return input.dataset.message || `Máximo ${max} caracteres`;
            }

            return true;
        },

        regex: (input) => {
            const value = input.value.trim();
            const required = input.dataset.required === "1";

            if (!value) {
                return required ? (input.dataset.message || "Requerido") : true;
            }

            const pattern = new RegExp(input.dataset.pattern);

            return pattern.test(value)
                ? true
                : input.dataset.message;
        },

        email: (input) => {
            const value = input.value.trim();
            const required = input.dataset.required === "1";

            if (!value) {
                return required ? (input.dataset.message || "Requerido") : true;
            }

            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
                ? true
                : input.dataset.message;
        },

        file: (input) => {
            const file = input.files?.[0];
            const required = input.dataset.required === "1";

            if (!file) {
                return required ? input.dataset.message || "Archivo requerido" : true;
            }

            const maxSize = +input.dataset.maxSize;
            const types = (input.dataset.types || "").split(",");

            if (maxSize && file.size > maxSize) {
                return input.dataset.message;
            }

            if (types.length && !types.includes(file.type)) {
                return input.dataset.message;
            }

            return true;
        },

        select: (input) => {
            const value = input.value;
            const required = input.dataset.required === "1";

            if (!value && !required) {
                return true; // no marcar nada
            }

            if (required && !value) {
                return input.dataset.message || "Selecciona una opción";
            }

            return true;
        },

        number: (input) => {
            const value = input.value.trim();
            const required = input.dataset.required === "1";

            if (!value) {
                return required ? input.dataset.message || "Requerido" : true;
            }

            if (!/^\d+$/.test(value)) {
                return input.dataset.message || "Debe ser un número válido";
            }

            const num = Number(value);
            const min = input.min ? Number(input.min) : null;
            const max = input.max ? Number(input.max) : null;

            if (min !== null && num < min) {
                return input.dataset.message || `Mínimo ${min}`;
            }

            if (max !== null && num > max) {
                return input.dataset.message || `Máximo ${max}`;
            }

            return true;
        }
    };

    function validateInput(input) {
        const type = input.dataset.validate;
        const validator = validators[type];

        if (!validator) return true;

        const result = validator(input);
        const valid = result === true;

        const isTouched = touched.get(input);

        // 👇 solo aplicar estilos si ya fue tocado
        if (isTouched) {
            input.classList.toggle("input-valid", valid);
            input.classList.toggle("input-invalid", !valid);

            if (!valid) setFieldError(input, result);
            else clearFieldError(input);
        }

        return valid;
    }

    document.addEventListener("input", (e) => {
        const input = e.target;

        if (!input.matches("[data-validate]")) return;

        // Solo valida si ya fue tocado
        if (!touched.get(input)) return;

        requestAnimationFrame(() => {
            validateInput(input);
        });
    });

    document.addEventListener("blur", (e) => {
        const input = e.target;

        if (!input.matches("[data-validate]")) return;

        touched.set(input, true);

        validateInput(input);

    }, true);

    function setFieldError(input, message) {

        let msg = errorCache.get(input);

        input.classList.add("shake");
        setTimeout(() => input.classList.remove("shake"), 300);

        if (!msg) {
            msg = document.createElement("small");
            msg.className = "field-error";
            input.parentElement.appendChild(msg);
            errorCache.set(input, msg);
        }

        msg.textContent = message;
    }

    function clearFieldError(input) {

        const msg = errorCache.get(input);

        if (msg) {
            msg.textContent = "";
        }
    }

    /* =========================
       GLOBAL FORM LOADER (SAFE)
    ========================= */
    document.addEventListener("submit", (e) => {

        const form = e.target;

        const inputs = form.querySelectorAll("[data-validate]");

        let isValid = true;
        let firstError = null;

        inputs.forEach(input => {

            touched.set(input, true); // Fuerza validación visual

            const ok = validateInput(input);

            if (!ok && !firstError) {
                firstError = input;
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();

            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                firstError.classList.add("shake");
                setTimeout(() => firstError.classList.remove("shake"), 300);
            }

            showToast("Revisa los campos antes de guardar", "error");
            return;
        }

        requestAnimationFrame(() => {
            const btn = form.querySelector("button[type='submit']");

            if (btn) {
                btn.classList.add("btn-loading");
                btn.disabled = true;
            }

            showLoader();
        });
    });
});
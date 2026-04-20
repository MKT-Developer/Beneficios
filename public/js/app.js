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

        // 🔥 ignorar micro-movimientos
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

    window.openDeleteModal = function (form) {
        deleteForm = form;
        document.getElementById("deleteModal")?.classList.remove("hidden");
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

            const btn = deleteForm.querySelector("button");
            if (btn) {
                btn.classList.add("btn-loading");
                btn.disabled = true;
            }

            window.showToast("Eliminando...", "info");
            deleteForm.submit();
        });
    }

    /* =========================
       GLOBAL FORM LOADER (SAFE)
    ========================= */

    document.addEventListener("submit", (e) => {
        const form = e.target;

        // evita interferencia con modal cancelado o acciones JS
        if (form.dataset.noLoader === "true") return;

        const btn = form.querySelector("button");

        if (btn) {
            btn.classList.add("btn-loading");
            btn.disabled = true;
        }
    });

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

});
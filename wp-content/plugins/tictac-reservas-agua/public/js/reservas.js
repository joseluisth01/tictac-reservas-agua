/**
 * TicTac Reservas Agua - Frontend Booking App
 * SPA de 4 pasos para el proceso de reserva.
 * 
 * Se inicializa automáticamente al cargar la página con el shortcode.
 * Toda la lógica de pasos, calendario, validación y pago se gestiona aquí.
 */

(function () {
    'use strict';

    const App = {
        // Estado global
        state: {
            currentStep: 1,
            categorias: [],
            actividades: [],
            selectedActivities: [], // { actividad_id, personas, sesiones, fecha, hora, precio }
            clientData: {},
            paymentMethod: '',
            total: 0,
        },

        // Inicialización
        init() {
            this.loadCategorias();
            this.loadActividades();
            this.bindEvents();
            this.renderTrustBadges();
            this.updateSummary();
        },

        // ── API calls ──
        async api(endpoint, options = {}) {
            const url = ttra_config.rest_url + endpoint;
            const defaults = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': ttra_config.nonce,
                },
            };
            const response = await fetch(url, { ...defaults, ...options });
            return response.json();
        },

        async loadCategorias() {
            this.state.categorias = await this.api('categorias');
            this.renderCategoryFilters();
        },

        async loadActividades(categoriaId = null) {
            const endpoint = categoriaId ? `actividades?categoria=${categoriaId}` : 'actividades';
            this.state.actividades = await this.api(endpoint);
            this.renderActivities();
        },

        async loadCalendar(actividadId, year, month) {
            return this.api(`calendario/${actividadId}/${year}/${month}`);
        },

        async loadSlots(actividadId, fecha) {
            return this.api(`slots/${actividadId}/${fecha}`);
        },

        // ── Eventos ──
        bindEvents() {
            // Navegación entre pasos
            document.querySelectorAll('.ttra-btn--next').forEach(btn => {
                btn.addEventListener('click', () => this.goToStep(parseInt(btn.dataset.next)));
            });
            document.querySelectorAll('.ttra-btn--prev').forEach(btn => {
                btn.addEventListener('click', () => this.goToStep(parseInt(btn.dataset.prev)));
            });

            // Finalizar reserva
            document.getElementById('ttra-btn-finalizar')?.addEventListener('click', () => this.submitReservation());

            // Sidebar CTA
            document.getElementById('ttra-sidebar-cta')?.addEventListener('click', () => {
                this.goToStep(this.state.currentStep + 1);
            });
        },

        // ── Navegación de pasos ──
        goToStep(step) {
            if (step < 1 || step > 4) return;
            if (step > this.state.currentStep && !this.validateStep(this.state.currentStep)) return;

            // Ocultar paso actual
            document.getElementById(`ttra-step-${this.state.currentStep}`)?.classList.add('ttra-step--hidden');

            // Mostrar nuevo paso
            document.getElementById(`ttra-step-${step}`)?.classList.remove('ttra-step--hidden');

            // Actualizar stepper
            document.querySelectorAll('.ttra-stepper__step').forEach(el => {
                const s = parseInt(el.dataset.step);
                el.classList.toggle('ttra-stepper__step--active', s === step);
                el.classList.toggle('ttra-stepper__step--completed', s < step);
            });

            this.state.currentStep = step;

            // Acciones específicas por paso
            if (step === 2) this.initCalendars();
            if (step === 4) this.renderPaymentMethods();

            this.updateSummary();
            this.updateSidebarCTA();

            // Scroll arriba
            document.getElementById('ttra-reservas-app')?.scrollIntoView({ behavior: 'smooth' });
        },

        // ── Validación por paso ──
        validateStep(step) {
            switch (step) {
                case 1:
                    if (this.state.selectedActivities.length === 0) {
                        alert('Selecciona al menos una actividad.');
                        return false;
                    }
                    return true;
                case 2:
                    const incomplete = this.state.selectedActivities.find(a => !a.fecha || !a.hora);
                    if (incomplete) {
                        alert('Selecciona fecha y hora para todas las actividades.');
                        return false;
                    }
                    return true;
                case 3:
                    return this.validateForm();
                default:
                    return true;
            }
        },

        validateForm() {
            const form = document.getElementById('ttra-form-datos');
            const required = form.querySelectorAll('[required]');
            let valid = true;
            required.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('ttra-input--error');
                    valid = false;
                } else {
                    input.classList.remove('ttra-input--error');
                }
            });
            if (!valid) alert('Rellena todos los campos obligatorios.');
            return valid;
        },

        // ── Renders ──
        renderCategoryFilters() {
            const container = document.getElementById('ttra-categories-filter');
            if (!container) return;

            let html = `<button class="ttra-cat-btn ttra-cat-btn--active" data-cat="all">${ttra_config.i18n.todo}</button>`;
            this.state.categorias.forEach(cat => {
                html += `<button class="ttra-cat-btn" data-cat="${cat.id}">${cat.nombre}</button>`;
            });
            container.innerHTML = html;

            container.querySelectorAll('.ttra-cat-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    container.querySelectorAll('.ttra-cat-btn').forEach(b => b.classList.remove('ttra-cat-btn--active'));
                    btn.classList.add('ttra-cat-btn--active');
                    const catId = btn.dataset.cat === 'all' ? null : btn.dataset.cat;
                    this.loadActividades(catId);
                });
            });
        },

        renderActivities() {
            const container = document.getElementById('ttra-activities-list');
            if (!container) return;

            if (!this.state.actividades.length) {
                container.innerHTML = '<p class="ttra-empty">No hay actividades disponibles.</p>';
                return;
            }

            let html = '';
            this.state.actividades.forEach(act => {
                const selected = this.state.selectedActivities.find(s => s.actividad_id == act.id);
                const personas = selected ? selected.personas : 1;
                const sesiones = selected ? selected.sesiones : 1;
                const checked = selected ? 'checked' : '';

                html += `
                <div class="ttra-activity-card ${selected ? 'ttra-activity-card--selected' : ''}" data-id="${act.id}">
                    <div class="ttra-activity-card__check">
                        <input type="checkbox" ${checked} data-id="${act.id}">
                    </div>
                    <div class="ttra-activity-card__info">
                        <strong>${act.nombre}</strong><span class="ttra-activity-card__subtipo">${act.subtipo || ''}</span>
                    </div>
                    <div class="ttra-activity-card__duration">
                        <img src="${ttra_config.uploads_url}/2026/04/system-regular-162-update-hover-update-1.svg" alt="">
                        <span class="ttra-icon-clock"></span> ${act.duracion_minutos} minutos
                    </div>
                    <div class="ttra-activity-card__config">
                        <label>${ttra_config.i18n.personas}
                            <select class="ttra-select ttra-select--sm" data-field="personas" data-id="${act.id}">
                                ${this.generateOptions(act.min_personas, act.max_personas, personas)}
                            </select>
                        </label>
                        <label>${ttra_config.i18n.sesiones}
                            <select class="ttra-select ttra-select--sm" data-field="sesiones" data-id="${act.id}">
                                ${this.generateOptions(1, act.max_sesiones, sesiones)}
                            </select>
                        </label>
                    </div>
                    <div class="ttra-activity-card__price">
                        <span class="ttra-price" data-id="${act.id}">${this.calcPrice(act, personas, sesiones)} ${ttra_config.currency_symbol}</span>
                        ${act.precio_tipo === 'por_persona' ? '<small class="ttra-price-note">' + act.precio_base + '€/pax</small>' : ''}
                    </div>
                    <div class="ttra-activity-card__icon"><img src="${ttra_config.uploads_url}/2026/04/system-regular-162-update-hover-update-1-1.svg" alt=""></div>
                </div>`;
            });

            container.innerHTML = html;
            this.bindActivityEvents();
        },

        renderTrustBadges() {
            const container = document.getElementById('ttra-trust-badges');
            if (!container) return;

            let html = '';
            const b = ttra_config.badges;
            const l = ttra_config.labels;
            html += `<div class="ttra-badge ttra-badge--trust"><img src="${ttra_config.uploads_url}/2026/04/Icon-3.svg" alt=""> ${l.cancelacion_gratuita}</div>`;
            html += `<div class="ttra-badge ttra-badge--trust"><img src="${ttra_config.uploads_url}/2026/04/Icon-3.svg" alt=""> ${l.no_fianza}</div>`;
            html += `<div class="ttra-badge ttra-badge--trust"><img src="${ttra_config.uploads_url}/2026/04/Icon-3.svg" alt=""> ${l.pago_seguro}</div>`;
            html += `<div class="ttra-badge ttra-badge--trust"><img src="${ttra_config.uploads_url}/2026/04/Icon-3.svg" alt=""> ${l.equipo_seguridad}</div>`;
            container.innerHTML = html;
        },

        renderPaymentMethods() {
            const container = document.getElementById('ttra-payment-methods');
            if (!container) return;

            const methods = ttra_config.metodos_pago;
            const labels = {
                tarjeta: { name: 'Tarjeta de Crédito/Débito', sub: 'Visa, Mastercard', icon: '💳' },
                bizum: { name: 'Bizum', sub: '', icon: '📱' },
                google_pay: { name: 'Google Pay', sub: '', icon: '🅖' },
                apple_pay: { name: 'Apple Pay', sub: '', icon: '🍎' },
            };

            let html = '';
            methods.forEach(m => {
                const info = labels[m] || { name: m, sub: '', icon: '💰' };
                html += `
                <div class="ttra-payment-option" data-method="${m}">
                    <input type="radio" name="metodo_pago" value="${m}">
                    <div class="ttra-payment-option__info">
                        <strong>${info.name}</strong>
                        ${info.sub ? `<span>${info.sub}</span>` : ''}
                    </div>
                    <div class="ttra-payment-option__icon">${info.icon}</div>
                </div>`;
            });
            container.innerHTML = html;

            container.querySelectorAll('.ttra-payment-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    container.querySelectorAll('.ttra-payment-option').forEach(o => o.classList.remove('ttra-payment-option--selected'));
                    opt.classList.add('ttra-payment-option--selected');
                    opt.querySelector('input').checked = true;
                    this.state.paymentMethod = opt.dataset.method;
                });
            });
        },

        // ── Calendarios ──
        initCalendars() {
            const container = document.getElementById('ttra-calendars-grid');
            if (!container) return;

            let html = '';
            this.state.selectedActivities.forEach((sel, idx) => {
                const act = this.state.actividades.find(a => a.id == sel.actividad_id);
                if (!act) return;
                html += `
                <div class="ttra-calendar-block" data-idx="${idx}" data-actividad="${sel.actividad_id}">
                    <p class="ttra-calendar-block__label">
                        Selecciona fecha y hora para la actividad:
                        <br><strong>${act.nombre}</strong> <em>${act.subtipo || ''}</em>
                    </p>
                    <div class="ttra-calendar" id="ttra-cal-${idx}"></div>
                    <div class="ttra-slots-section">
                        <label class="ttra-slots-label">${ttra_config.i18n.horarios_disponibles}</label>
                        <select class="ttra-select ttra-select--slots" id="ttra-slots-${idx}">
                            <option value="">00:00</option>
                        </select>
                    </div>
                    <button class="ttra-btn ttra-btn--outline ttra-btn--done" data-idx="${idx}">
                        ${ttra_config.i18n.seleccion_finalizada}
                    </button>
                </div>`;
            });
            container.innerHTML = html;

            // Inicializar cada calendario
            this.state.selectedActivities.forEach((sel, idx) => {
                this.buildCalendar(idx, sel.actividad_id, new Date().getFullYear(), new Date().getMonth() + 1);
            });
        },

        async buildCalendar(idx, actividadId, year, month) {
            const cal = document.getElementById(`ttra-cal-${idx}`);
            if (!cal) return;

            const dias = await this.loadCalendar(actividadId, year, month);
            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            let html = `
            <div class="ttra-cal-header">
                <button class="ttra-cal-nav" data-dir="-1" data-idx="${idx}" data-act="${actividadId}">‹</button>
                <span class="ttra-cal-month">${monthNames[month - 1]} ${year}</span>
                <button class="ttra-cal-nav" data-dir="1" data-idx="${idx}" data-act="${actividadId}">›</button>
            </div>
            <div class="ttra-cal-grid">
                <div class="ttra-cal-day-header">L</div>
                <div class="ttra-cal-day-header">M</div>
                <div class="ttra-cal-day-header">X</div>
                <div class="ttra-cal-day-header">J</div>
                <div class="ttra-cal-day-header">V</div>
                <div class="ttra-cal-day-header">S</div>
                <div class="ttra-cal-day-header">D</div>`;

            // Calcular primer día del mes
            const firstDay = new Date(year, month - 1, 1).getDay();
            const offset = firstDay === 0 ? 6 : firstDay - 1;
            for (let i = 0; i < offset; i++) {
                html += '<div class="ttra-cal-day ttra-cal-day--empty"></div>';
            }

            const totalDays = new Date(year, month, 0).getDate();
            for (let d = 1; d <= totalDays; d++) {
                const fecha = `${year}-${String(month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const info = dias[fecha];
                const available = info && info.disponible;
                const selected = this.state.selectedActivities[idx]?.fecha === fecha;

                html += `<div class="ttra-cal-day ${available ? 'ttra-cal-day--available' : 'ttra-cal-day--disabled'} ${selected ? 'ttra-cal-day--selected' : ''}" 
                          data-fecha="${fecha}" data-idx="${idx}" data-act="${actividadId}">${d}</div>`;
            }

            html += '</div>';
            cal.innerHTML = html;

            // Bind events
            cal.querySelectorAll('.ttra-cal-day--available').forEach(day => {
                day.addEventListener('click', () => this.selectDate(idx, actividadId, day.dataset.fecha));
            });
            cal.querySelectorAll('.ttra-cal-nav').forEach(btn => {
                btn.addEventListener('click', () => {
                    let newMonth = month + parseInt(btn.dataset.dir);
                    let newYear = year;
                    if (newMonth < 1) { newMonth = 12; newYear--; }
                    if (newMonth > 12) { newMonth = 1; newYear++; }
                    this.buildCalendar(idx, actividadId, newYear, newMonth);
                });
            });
        },

        async selectDate(idx, actividadId, fecha) {
            // Marcar día seleccionado visualmente
            const cal = document.getElementById(`ttra-cal-${idx}`);
            cal.querySelectorAll('.ttra-cal-day--selected').forEach(d => d.classList.remove('ttra-cal-day--selected'));
            cal.querySelector(`[data-fecha="${fecha}"]`)?.classList.add('ttra-cal-day--selected');

            // Cargar slots
            const slots = await this.loadSlots(actividadId, fecha);
            const select = document.getElementById(`ttra-slots-${idx}`);
            if (select) {
                let opts = '<option value="">Selecciona hora</option>';
                slots.forEach(s => {
                    opts += `<option value="${s.hora}">${s.hora} (${s.plazas_disponibles} plazas)</option>`;
                });
                select.innerHTML = opts;
                select.onchange = () => {
                    this.state.selectedActivities[idx].fecha = fecha;
                    this.state.selectedActivities[idx].hora = select.value;
                    this.updateSummary();
                    this.checkStep2Complete();
                };
            }

            this.state.selectedActivities[idx].fecha = fecha;
            this.updateSummary();
        },

        // ── Actividades: bind events ──
        bindActivityEvents() {
            document.querySelectorAll('.ttra-activity-card input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', () => this.toggleActivity(cb));
            });
            document.querySelectorAll('.ttra-activity-card select').forEach(sel => {
                sel.addEventListener('change', () => this.updateActivityConfig(sel));
            });
        },

        toggleActivity(checkbox) {
            const id = parseInt(checkbox.dataset.id);
            const card = checkbox.closest('.ttra-activity-card');

            if (checkbox.checked) {
                const act = this.state.actividades.find(a => a.id == id);
                const personas = parseInt(card.querySelector('[data-field="personas"]').value) || 1;
                const sesiones = parseInt(card.querySelector('[data-field="sesiones"]').value) || 1;
                this.state.selectedActivities.push({
                    actividad_id: id,
                    personas,
                    sesiones,
                    fecha: '',
                    hora: '',
                    precio: this.calcPrice(act, personas, sesiones),
                });
                card.classList.add('ttra-activity-card--selected');
            } else {
                this.state.selectedActivities = this.state.selectedActivities.filter(a => a.actividad_id !== id);
                card.classList.remove('ttra-activity-card--selected');
            }

            this.updateTotal();
            this.updateSummary();
            this.updateNextButton();
        },

        updateActivityConfig(select) {
            const id = parseInt(select.dataset.id);
            const field = select.dataset.field;
            const act = this.state.actividades.find(a => a.id == id);
            const sel = this.state.selectedActivities.find(s => s.actividad_id == id);

            if (sel) {
                sel[field] = parseInt(select.value);
                const card = select.closest('.ttra-activity-card');
                const personas = parseInt(card.querySelector('[data-field="personas"]').value);
                const sesiones = parseInt(card.querySelector('[data-field="sesiones"]').value);
                sel.precio = this.calcPrice(act, personas, sesiones);

                // Actualizar precio visual
                const priceEl = card.querySelector('.ttra-price');
                if (priceEl) priceEl.textContent = `${sel.precio} ${ttra_config.currency_symbol}`;

                this.updateTotal();
                this.updateSummary();
            }
        },

        // ── Utilidades ──
        calcPrice(act, personas, sesiones) {
            if (act.precio_tipo === 'por_persona') {
                return (parseFloat(act.precio_base) * personas * sesiones).toFixed(0);
            }
            return (parseFloat(act.precio_base) * sesiones).toFixed(0);
        },

        generateOptions(min, max, selected) {
            let html = '';
            for (let i = min; i <= max; i++) {
                html += `<option value="${i}" ${i == selected ? 'selected' : ''}>${i}</option>`;
            }
            return html;
        },

        updateTotal() {
            this.state.total = this.state.selectedActivities.reduce((sum, a) => sum + parseFloat(a.precio || 0), 0);
        },

        updateSummary() {
            const container = document.getElementById('ttra-summary-items');
            const totalEl = document.getElementById('ttra-summary-total');
            if (!container) return;

            let html = '';
            this.state.selectedActivities.forEach((sel, idx) => {
                const act = this.state.actividades.find(a => a.id == sel.actividad_id);
                if (!act) return;
                html += `
                <div class="ttra-summary__item">
                    <div class="ttra-summary__item-header">
                        <span>Actividad seleccionada ${String(idx + 1).padStart(2, '0')}</span>
                        <span>${sel.precio} ${ttra_config.currency_symbol}</span>
                    </div>
                    <div class="ttra-summary__item-detail">
                        <span>${ttra_config.i18n.personas}</span><span>${sel.personas}</span>
                    </div>
                    <div class="ttra-summary__item-detail">
                        <span>${ttra_config.i18n.sesiones}</span><span>${sel.sesiones}</span>
                    </div>
                    <div class="ttra-summary__item-detail">
                        <span>Fecha</span><span>${sel.fecha || '-'}</span>
                    </div>
                    <div class="ttra-summary__item-detail">
                        <span>Hora</span><span>${sel.hora || '-'}</span>
                    </div>
                </div>`;
            });

            container.innerHTML = html;
            if (totalEl) totalEl.textContent = `${this.state.total} ${ttra_config.currency_symbol}`;
        },

        updateNextButton() {
    const btn = document.querySelector('#ttra-step-1 .ttra-btn--next');
    if (btn) btn.disabled = this.state.selectedActivities.length === 0;

    // Sincronizar sidebar
    this.updateSidebarCTA();
},

        updateSidebarCTA() {
            const btn = document.getElementById('ttra-sidebar-cta');
            if (!btn) return;

            const next = this.state.currentStep + 1;
            if (next <= 4) {
                btn.textContent = `${ttra_config.i18n.continuar} (PASO ${next}) →`;
            } else {
                btn.textContent = `${ttra_config.i18n.finalizar} →`;
            }

            // Paso 1: deshabilitar hasta que haya al menos una actividad seleccionada
            if (this.state.currentStep === 1) {
                btn.disabled = this.state.selectedActivities.length === 0;

                // Paso 2: deshabilitar hasta que todas tengan fecha y hora
            } else if (this.state.currentStep === 2) {
                const allDone = this.state.selectedActivities.every(a => a.fecha && a.hora);
                btn.disabled = !allDone;

            } else {
                btn.disabled = false;
            }
        },

        checkStep2Complete() {
            const allDone = this.state.selectedActivities.every(a => a.fecha && a.hora);

            // Botón inferior del paso 2
            const btnNext = document.querySelector('#ttra-step-2 .ttra-btn--next');
            if (btnNext) btnNext.disabled = !allDone;

            // Botón del sidebar
            const btnSidebar = document.getElementById('ttra-sidebar-cta');
            if (btnSidebar && this.state.currentStep === 2) btnSidebar.disabled = !allDone;
        },

        // ── Submit ──
        async submitReservation() {
            if (!this.state.paymentMethod) {
                alert('Selecciona un método de pago.');
                return;
            }

            const form = document.getElementById('ttra-form-datos');
            const formData = {
                nombre: form.querySelector('[name="nombre"]').value,
                email: form.querySelector('[name="email"]').value,
                telefono: form.querySelector('[name="telefono"]').value,
                dni_pasaporte: form.querySelector('[name="dni_pasaporte"]').value,
                fecha_nacimiento: this.buildFechaNacimiento(form),
                direccion: form.querySelector('[name="direccion"]').value,
                actividades: this.state.selectedActivities,
            };

            // Crear reserva
            const result = await this.api('reservas', {
                method: 'POST',
                body: JSON.stringify(formData),
            });

            if (!result.success) {
                alert(result.message || 'Error al crear la reserva.');
                return;
            }

            // Iniciar pago
            const pagoData = await this.api('pago/iniciar', {
                method: 'POST',
                body: JSON.stringify({
                    codigo_reserva: result.codigo_reserva,
                    metodo_pago: this.state.paymentMethod,
                }),
            });

            // Redirigir a Redsys
            if (pagoData.url) {
                const redsysForm = document.getElementById('ttra-redsys-form');
                redsysForm.action = pagoData.url;
                redsysForm.querySelector('[name="Ds_SignatureVersion"]').value = pagoData.Ds_SignatureVersion;
                redsysForm.querySelector('[name="Ds_MerchantParameters"]').value = pagoData.Ds_MerchantParameters;
                redsysForm.querySelector('[name="Ds_Signature"]').value = pagoData.Ds_Signature;
                redsysForm.submit();
            }
        },

        buildFechaNacimiento(form) {
            const d = form.querySelector('[name="nacimiento_dia"]').value;
            const m = form.querySelector('[name="nacimiento_mes"]').value;
            const y = form.querySelector('[name="nacimiento_anyo"]').value;
            if (d && m && y) return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            return '';
        },
    };

    // Boot
    document.addEventListener('DOMContentLoaded', () => App.init());

})();

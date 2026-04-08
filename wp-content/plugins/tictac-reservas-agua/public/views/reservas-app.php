<?php
/**
 * Vista principal del sistema de reservas (shortcode output).
 * Todo el flujo de 4 pasos se gestiona por JS (SPA).
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="ttra-reservas-app" class="ttra-app containerancho" data-categoria="<?php echo esc_attr( $atts['categoria'] ?? '' ); ?>">

    <!-- ══════════════ STEPPER ══════════════ -->
    <div class="ttra-stepper">
        <div class="ttra-stepper__step ttra-stepper__step--active" data-step="1">
            <span class="ttra-stepper__number">1</span>
            <span class="ttra-stepper__label"><?php esc_html_e( 'ELIGE ACTIVIDADES', 'tictac-reservas-agua' ); ?></span>
        </div>
        <div class="ttra-stepper__step" data-step="2">
            <span class="ttra-stepper__number">2</span>
            <span class="ttra-stepper__label"><?php esc_html_e( 'FECHA Y HORA', 'tictac-reservas-agua' ); ?></span>
        </div>
        <div class="ttra-stepper__step" data-step="3">
            <span class="ttra-stepper__number">3</span>
            <span class="ttra-stepper__label"><?php esc_html_e( 'TUS DATOS', 'tictac-reservas-agua' ); ?></span>
        </div>
        <div class="ttra-stepper__step" data-step="4">
            <span class="ttra-stepper__number">4</span>
            <span class="ttra-stepper__label"><?php esc_html_e( 'MÉTODO DE PAGO', 'tictac-reservas-agua' ); ?></span>
        </div>
    </div>

    <!-- ══════════════ CONTENIDO PRINCIPAL ══════════════ -->
    <div class="ttra-layout">

        <!-- ÁREA IZQUIERDA: PASOS -->
        <div class="ttra-layout__main">

            <!-- PASO 1: Actividades -->
            <div class="ttra-step" id="ttra-step-1" data-step="1">
                <h2 class="ttra-step__title"><?php esc_html_e( 'ELIGE LAS ACTIVIDADES DESEADAS', 'tictac-reservas-agua' ); ?></h2>
                <img src="<?php echo esc_url( wp_upload_dir()['baseurl'] . '/2026/04/Vector-18.svg' ); ?>" alt="">
                <p class="ttra-step__desc" id="ttra-paso1-desc">Selecciona una o más actividades, elige número de personas participantes y cantidad de sesiones. En el siguiente paso concreta el día y la hora que quieres.</p>

                <!-- Filtros de categoría -->
                <div class="ttra-categories" id="ttra-categories-filter"></div>

                <!-- Lista de actividades -->
                <div class="ttra-activities-list" id="ttra-activities-list">
                    <div class="ttra-loader"><?php esc_html_e( 'Cargando actividades...', 'tictac-reservas-agua' ); ?></div>
                </div>

                <!-- Botón continuar -->
                <div class="ttra-step__actions">
                    <button class="ttra-btn ttra-btn--primary ttra-btn--next" data-next="2" disabled>
                        <?php esc_html_e( 'CONTINUAR (PASO 2)', 'tictac-reservas-agua' ); ?> →
                    </button>
                </div>
            </div>

            <!-- PASO 2: Fecha y Hora -->
            <div class="ttra-step ttra-step--hidden" id="ttra-step-2" data-step="2">
                <h2 class="ttra-step__title"><?php esc_html_e( 'SELECCIONA FECHA Y HORA', 'tictac-reservas-agua' ); ?></h2>
                <div class="ttra-wave-divider"></div>
                <p class="ttra-step__desc" id="ttra-paso2-desc"></p>

                <!-- Calendarios dinámicos por actividad seleccionada -->
                <div class="ttra-calendars-grid" id="ttra-calendars-grid"></div>

                <div class="ttra-step__actions ttra-step__actions--between">
                    <button class="ttra-btn ttra-btn--outline ttra-btn--prev" data-prev="1">
                        ← <?php esc_html_e( 'RETROCEDER (PASO 1)', 'tictac-reservas-agua' ); ?>
                    </button>
                    <button class="ttra-btn ttra-btn--primary ttra-btn--next" data-next="3" disabled>
                        <?php esc_html_e( 'CONTINUAR (PASO 3)', 'tictac-reservas-agua' ); ?> →
                    </button>
                </div>
            </div>

            <!-- PASO 3: Datos del cliente -->
            <div class="ttra-step ttra-step--hidden" id="ttra-step-3" data-step="3">
                <h2 class="ttra-step__title"><?php esc_html_e( 'RELLENA CON TUS DATOS', 'tictac-reservas-agua' ); ?></h2>
                <div class="ttra-wave-divider"></div>
                <p class="ttra-step__desc" id="ttra-paso3-desc"></p>

                <div class="ttra-form" id="ttra-form-datos">
                    <div class="ttra-form__row">
                        <div class="ttra-form__field ttra-form__field--full">
                            <label><?php esc_html_e( 'Nombre y apellido*', 'tictac-reservas-agua' ); ?></label>
                            <input type="text" name="nombre" required>
                        </div>
                    </div>
                    <div class="ttra-form__row">
                        <div class="ttra-form__field">
                            <label><?php esc_html_e( 'Teléfono*', 'tictac-reservas-agua' ); ?></label>
                            <input type="tel" name="telefono" required>
                        </div>
                        <div class="ttra-form__field">
                            <label><?php esc_html_e( 'Email*', 'tictac-reservas-agua' ); ?></label>
                            <input type="email" name="email" required>
                        </div>
                    </div>
                    <div class="ttra-form__row">
                        <div class="ttra-form__field">
                            <label><?php esc_html_e( 'Nº DNI/Pasaporte *', 'tictac-reservas-agua' ); ?></label>
                            <input type="text" name="dni_pasaporte" required>
                        </div>
                        <div class="ttra-form__field">
                            <label><?php esc_html_e( 'Fecha de Nacimiento *', 'tictac-reservas-agua' ); ?></label>
                            <div class="ttra-form__date-group">
                                <select name="nacimiento_dia" class="ttra-select">
                                    <option value="">--</option>
                                    <?php for ( $d = 1; $d <= 31; $d++ ) : ?>
                                        <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                                    <?php endfor; ?>
                                </select>
                                <select name="nacimiento_mes" class="ttra-select">
                                    <option value="">--</option>
                                    <?php
                                    $meses = array( 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre' );
                                    foreach ( $meses as $i => $m ) :
                                    ?>
                                        <option value="<?php echo $i + 1; ?>"><?php echo $m; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="nacimiento_anyo" class="ttra-select">
                                    <option value="">--</option>
                                    <?php for ( $y = date('Y'); $y >= 1920; $y-- ) : ?>
                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="ttra-form__row">
                        <div class="ttra-form__field ttra-form__field--full">
                            <label><?php esc_html_e( 'Dirección en España (si aplica)', 'tictac-reservas-agua' ); ?></label>
                            <input type="text" name="direccion">
                        </div>
                    </div>
                </div>

                <div class="ttra-step__actions ttra-step__actions--between">
                    <button class="ttra-btn ttra-btn--outline ttra-btn--prev" data-prev="2">
                        ← <?php esc_html_e( 'RETROCEDER (PASO 2)', 'tictac-reservas-agua' ); ?>
                    </button>
                    <button class="ttra-btn ttra-btn--primary ttra-btn--next" data-next="4">
                        <?php esc_html_e( 'CONTINUAR (PASO 4)', 'tictac-reservas-agua' ); ?> →
                    </button>
                </div>
            </div>

            <!-- PASO 4: Método de pago -->
            <div class="ttra-step ttra-step--hidden" id="ttra-step-4" data-step="4">
                <h2 class="ttra-step__title"><?php esc_html_e( 'FINALIZA: MÉTODOS DE PAGO', 'tictac-reservas-agua' ); ?></h2>
                <div class="ttra-wave-divider"></div>
                <p class="ttra-step__desc" id="ttra-paso4-desc"></p>

                <div class="ttra-payment-methods" id="ttra-payment-methods"></div>

                <div class="ttra-step__actions ttra-step__actions--between">
                    <button class="ttra-btn ttra-btn--outline ttra-btn--prev" data-prev="3">
                        ← <?php esc_html_e( 'RETROCEDER (PASO 3)', 'tictac-reservas-agua' ); ?>
                    </button>
                    <button class="ttra-btn ttra-btn--primary ttra-btn--submit" id="ttra-btn-finalizar">
                        <?php esc_html_e( 'FINALIZAR RESERVA', 'tictac-reservas-agua' ); ?> →
                    </button>
                </div>
            </div>

            <!-- PASO 5: Confirmación (post-pago) -->
            <div class="ttra-step ttra-step--hidden" id="ttra-step-confirm" data-step="confirm">
                <div class="ttra-confirmation" id="ttra-confirmation"></div>
            </div>

        </div>

        <!-- SIDEBAR: RESUMEN -->
        <aside class="ttra-layout__sidebar">
            <div class="ttra-summary" id="ttra-summary">
                <h3 class="ttra-summary__title"><?php esc_html_e( 'RESUMEN DE LA RESERVA', 'tictac-reservas-agua' ); ?></h3>
                <div class="ttra-wave-divider ttra-wave-divider--sm"></div>

                <div class="ttra-summary__items" id="ttra-summary-items">
                    <!-- Se rellena dinámicamente por JS -->
                </div>

                <div class="ttra-summary__total">
                    <span class="ttra-summary__total-label"><?php esc_html_e( 'Total', 'tictac-reservas-agua' ); ?></span>
                    <span class="ttra-summary__total-price" id="ttra-summary-total">0 €</span>
                </div>

                <!-- Badges de confianza -->
                <div class="ttra-trust-badges" id="ttra-trust-badges"></div>

                <!-- Botón duplicado en sidebar -->
                <button class="ttra-btn ttra-btn--primary ttra-btn--sidebar-cta" id="ttra-sidebar-cta">
                    <?php esc_html_e( 'CONTINUAR (PASO 2)', 'tictac-reservas-agua' ); ?> →
                </button>
            </div>
        </aside>

    </div>

    <!-- Form oculto para redirigir a Redsys -->
    <form id="ttra-redsys-form" method="POST" style="display:none;">
        <input type="hidden" name="Ds_SignatureVersion">
        <input type="hidden" name="Ds_MerchantParameters">
        <input type="hidden" name="Ds_Signature">
    </form>

</div>

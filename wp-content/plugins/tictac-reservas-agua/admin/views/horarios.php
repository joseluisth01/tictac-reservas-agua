<?php
/**
 * Vista admin: Horarios por actividad.
 * Variables: $actividades, $actividad_id (int), $horarios (array)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$msg   = $_GET['msg'] ?? '';
$nonce = wp_create_nonce( 'ttra_admin_nonce' );
$dias  = TTRA_Helpers::dias_semana();
$act_sel = $actividad_id > 0 ? TTRA_Actividad::get_by_id( $actividad_id ) : null;
?>

<div class="wrap ttra-admin-wrap">

    <h1 class="ttra-admin-title">
        <span class="dashicons dashicons-clock"></span>
        <?php esc_html_e( 'Horarios de Actividades', 'tictac-reservas-agua' ); ?>
    </h1>

    <?php if ( $msg === 'saved' ) : ?>
        <div class="notice notice-success is-dismissible"><p>✅ <?php esc_html_e( 'Horarios guardados correctamente.', 'tictac-reservas-agua' ); ?></p></div>
    <?php endif; ?>

    <!-- Selector de actividad -->
    <div class="ttra-admin-card">
        <h2><?php esc_html_e( 'Selecciona la actividad', 'tictac-reservas-agua' ); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="page" value="ttra-horarios">
            <div style="display:flex; gap:12px; align-items:center">
                <select name="actividad_id" class="regular-text" id="sel-actividad">
                    <option value=""><?php esc_html_e( '-- Selecciona actividad --', 'tictac-reservas-agua' ); ?></option>
                    <?php foreach ( $actividades as $act ) : ?>
                        <option value="<?php echo $act->id; ?>" <?php selected( $actividad_id, $act->id ); ?>>
                            <?php echo esc_html( $act->nombre . ( $act->subtipo ? ' — ' . $act->subtipo : '' ) ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="button button-primary">
                    <?php esc_html_e( 'Cargar horarios', 'tictac-reservas-agua' ); ?>
                </button>
            </div>
        </form>

        <?php if ( $act_sel ) : ?>
            <div class="ttra-info-banner" style="margin-top:16px">
                <strong>📋 <?php echo esc_html( $act_sel->nombre ); ?></strong>
                <span> — <?php echo intval( $act_sel->duracion_minutos ); ?> min — <?php echo TTRA_Helpers::formato_precio( $act_sel->precio_base ); ?> <?php echo $act_sel->precio_tipo === 'por_persona' ? esc_html__( '/persona', 'tictac-reservas-agua' ) : ''; ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if ( $act_sel ) : ?>
    <!-- Formulario de horarios -->
    <div class="ttra-admin-card ttra-card--full">
        <div class="ttra-admin-card__header">
            <h2><?php esc_html_e( 'Configurar franjas horarias', 'tictac-reservas-agua' ); ?></h2>
            <button type="button" id="ttra-add-horario" class="button button-primary">
                + <?php esc_html_e( 'Añadir franja', 'tictac-reservas-agua' ); ?>
            </button>
        </div>
        <p class="description"><?php esc_html_e( 'Define los días y horarios disponibles para esta actividad. El sistema generará slots automáticamente según el intervalo.', 'tictac-reservas-agua' ); ?></p>

        <form method="POST" action="" id="ttra-horarios-form">
            <input type="hidden" name="ttra_action" value="save_horario">
            <input type="hidden" name="ttra_nonce" value="<?php echo $nonce; ?>">
            <input type="hidden" name="actividad_id" value="<?php echo intval( $actividad_id ); ?>">

            <div id="ttra-horarios-container">

                <?php if ( empty( $horarios ) ) : ?>
                    <p class="ttra-empty-msg" id="ttra-no-horarios">
                        🕐 <?php esc_html_e( 'No hay franjas configuradas. Añade la primera.', 'tictac-reservas-agua' ); ?>
                    </p>
                <?php else : ?>
                    <?php foreach ( $horarios as $idx => $h ) : ?>
                    <div class="ttra-horario-row" data-idx="<?php echo $idx; ?>">
                        <div class="ttra-horario-row__grip">☰</div>
                        <div class="ttra-horario-row__fields">
                            <div class="ttra-horario-field">
                                <label><?php esc_html_e( 'Día', 'tictac-reservas-agua' ); ?></label>
                                <select name="horarios[<?php echo $idx; ?>][dia_semana]" class="ttra-select-dia">
                                    <?php foreach ( $dias as $num => $nombre_dia ) : ?>
                                        <option value="<?php echo $num; ?>" <?php selected( $h->dia_semana, $num ); ?>>
                                            <?php echo esc_html( $nombre_dia ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="ttra-horario-field">
                                <label><?php esc_html_e( 'Desde', 'tictac-reservas-agua' ); ?></label>
                                <input type="time" name="horarios[<?php echo $idx; ?>][hora_inicio]"
                                       value="<?php echo esc_attr( $h->hora_inicio ); ?>" required>
                            </div>
                            <div class="ttra-horario-field">
                                <label><?php esc_html_e( 'Hasta', 'tictac-reservas-agua' ); ?></label>
                                <input type="time" name="horarios[<?php echo $idx; ?>][hora_fin]"
                                       value="<?php echo esc_attr( $h->hora_fin ); ?>" required>
                            </div>
                            <div class="ttra-horario-field">
                                <label><?php esc_html_e( 'Intervalo', 'tictac-reservas-agua' ); ?></label>
                                <select name="horarios[<?php echo $idx; ?>][intervalo_minutos]">
                                    <?php foreach ( [15,20,30,45,60,90,120] as $min ) : ?>
                                        <option value="<?php echo $min; ?>" <?php selected( $h->intervalo_minutos, $min ); ?>>
                                            <?php echo $min; ?> min
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="ttra-horario-field">
                                <label><?php esc_html_e( 'Plazas/slot', 'tictac-reservas-agua' ); ?></label>
                                <input type="number" name="horarios[<?php echo $idx; ?>][plazas_por_slot]"
                                       value="<?php echo intval( $h->plazas_por_slot ); ?>" min="1" max="999" style="width:70px">
                            </div>
                            <div class="ttra-horario-field ttra-horario-field--toggle">
                                <label><?php esc_html_e( 'Activo', 'tictac-reservas-agua' ); ?></label>
                                <label class="ttra-toggle ttra-toggle--sm">
                                    <input type="checkbox" name="horarios[<?php echo $idx; ?>][activo]" value="1"
                                        <?php checked( $h->activo, 1 ); ?>>
                                    <span class="ttra-toggle__slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="ttra-horario-row__preview" data-idx="<?php echo $idx; ?>">
                            <!-- se rellena por JS -->
                        </div>
                        <button type="button" class="ttra-btn-remove-horario" title="<?php esc_attr_e( 'Eliminar', 'tictac-reservas-agua' ); ?>">✕</button>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div><!-- #ttra-horarios-container -->

            <?php if ( ! empty( $horarios ) || true ) : ?>
            <div class="ttra-form-actions" style="margin-top:24px">
                <?php submit_button( __( 'Guardar horarios', 'tictac-reservas-agua' ), 'primary', 'submit', false ); ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

</div>

<!-- Template para nueva fila -->
<script type="text/html" id="ttra-horario-template">
<div class="ttra-horario-row" data-idx="__IDX__">
    <div class="ttra-horario-row__grip">☰</div>
    <div class="ttra-horario-row__fields">
        <div class="ttra-horario-field">
            <label><?php esc_html_e( 'Día', 'tictac-reservas-agua' ); ?></label>
            <select name="horarios[__IDX__][dia_semana]" class="ttra-select-dia">
                <?php foreach ( $dias as $num => $nombre_dia ) : ?>
                    <option value="<?php echo $num; ?>"><?php echo esc_html( $nombre_dia ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ttra-horario-field">
            <label><?php esc_html_e( 'Desde', 'tictac-reservas-agua' ); ?></label>
            <input type="time" name="horarios[__IDX__][hora_inicio]" value="09:00" required>
        </div>
        <div class="ttra-horario-field">
            <label><?php esc_html_e( 'Hasta', 'tictac-reservas-agua' ); ?></label>
            <input type="time" name="horarios[__IDX__][hora_fin]" value="18:00" required>
        </div>
        <div class="ttra-horario-field">
            <label><?php esc_html_e( 'Intervalo', 'tictac-reservas-agua' ); ?></label>
            <select name="horarios[__IDX__][intervalo_minutos]">
                <?php foreach ( [15,20,30,45,60,90,120] as $min ) : ?>
                    <option value="<?php echo $min; ?>" <?php echo $min === 30 ? 'selected' : ''; ?>><?php echo $min; ?> min</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ttra-horario-field">
            <label><?php esc_html_e( 'Plazas/slot', 'tictac-reservas-agua' ); ?></label>
            <input type="number" name="horarios[__IDX__][plazas_por_slot]" value="10" min="1" max="999" style="width:70px">
        </div>
        <div class="ttra-horario-field ttra-horario-field--toggle">
            <label><?php esc_html_e( 'Activo', 'tictac-reservas-agua' ); ?></label>
            <label class="ttra-toggle ttra-toggle--sm">
                <input type="checkbox" name="horarios[__IDX__][activo]" value="1" checked>
                <span class="ttra-toggle__slider"></span>
            </label>
        </div>
    </div>
    <div class="ttra-horario-row__preview" data-idx="__IDX__"></div>
    <button type="button" class="ttra-btn-remove-horario" title="Eliminar">✕</button>
</div>
</script>

<script>
(function() {
    let idx = <?php echo count( $horarios ); ?>;
    const container = document.getElementById('ttra-horarios-container');
    const tpl = document.getElementById('ttra-horario-template');

    // Añadir fila
    document.getElementById('ttra-add-horario')?.addEventListener('click', function() {
        const html = tpl.innerHTML.replaceAll('__IDX__', idx++);
        const noHorarios = document.getElementById('ttra-no-horarios');
        if (noHorarios) noHorarios.remove();
        container.insertAdjacentHTML('beforeend', html);
        bindRow(container.lastElementChild);
        updatePreviews();
    });

    // Eliminar fila
    function bindRow(row) {
        row.querySelector('.ttra-btn-remove-horario')?.addEventListener('click', function() {
            row.remove();
            updatePreviews();
        });
        // Preview al cambiar valores
        row.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('change', updatePreviews);
        });
    }

    // Bind filas existentes
    container?.querySelectorAll('.ttra-horario-row').forEach(bindRow);

    // Preview de slots
    function updatePreviews() {
        document.querySelectorAll('.ttra-horario-row').forEach(row => {
            const inicio = row.querySelector('[name*="hora_inicio"]')?.value;
            const fin    = row.querySelector('[name*="hora_fin"]')?.value;
            const inter  = parseInt(row.querySelector('[name*="intervalo_minutos"]')?.value || 30);
            const preview = row.querySelector('.ttra-horario-row__preview');
            if (!preview || !inicio || !fin) return;
            const slots = [];
            let cur = timeToMin(inicio);
            const finMin = timeToMin(fin);
            while (cur < finMin) {
                slots.push(minToTime(cur));
                cur += inter;
            }
            preview.innerHTML = slots.length
                ? '<small class="ttra-slots-preview">' + slots.map(s => '<span>' + s + '</span>').join('') + '</small>'
                : '';
        });
    }

    function timeToMin(t) {
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    }
    function minToTime(m) {
        return String(Math.floor(m/60)).padStart(2,'0') + ':' + String(m%60).padStart(2,'0');
    }

    updatePreviews();
    document.querySelectorAll('.ttra-horario-row input, .ttra-horario-row select').forEach(el => {
        el.addEventListener('change', updatePreviews);
    });
})();
</script>

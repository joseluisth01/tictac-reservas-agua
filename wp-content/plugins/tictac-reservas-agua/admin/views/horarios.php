<?php
/**
 * Vista admin: Horarios por actividad.
 * Variables: $actividades, $actividad_id (int), $horarios (array)
 *
 * Cada franja permite seleccionar MÚLTIPLES días a la vez (chips).
 * Al guardar, si una franja tiene N días → se insertan N registros en BD.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$msg   = $_GET['msg'] ?? '';
$nonce = wp_create_nonce( 'ttra_admin_nonce' );
$dias  = TTRA_Helpers::dias_semana(); // [1=>'Lunes', 2=>'Martes', ...]
$dias_abrev = [1=>'Lu',2=>'Ma',3=>'Mi',4=>'Ju',5=>'Vi',6=>'Sá',7=>'Do'];
$act_sel = $actividad_id > 0 ? TTRA_Actividad::get_by_id( $actividad_id ) : null;

// Agrupar horarios por combinación → una franja puede tener varios días
$franjas = [];
foreach ( (array) $horarios as $h ) {
    $key = $h->hora_inicio . '|' . $h->hora_fin . '|' . $h->intervalo_minutos . '|' . $h->plazas_por_slot . '|' . $h->activo;
    if ( ! isset( $franjas[ $key ] ) ) {
        $franjas[ $key ] = [
            'hora_inicio'       => $h->hora_inicio,
            'hora_fin'          => $h->hora_fin,
            'intervalo_minutos' => $h->intervalo_minutos,
            'plazas_por_slot'   => $h->plazas_por_slot,
            'activo'            => $h->activo,
            'dias'              => [],
            'ids'               => [],
        ];
    }
    $franjas[ $key ]['dias'][] = (int) $h->dia_semana;
    $franjas[ $key ]['ids'][]  = (int) $h->id;
}
$franjas = array_values( $franjas );
?>

<style>
/* ── Franja card ── */
.ttra-franja {
    background: #fff;
    border: 1px solid #dcdcde;
    border-radius: 10px;
    padding: 16px 16px 16px 12px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    transition: border-color .2s, box-shadow .2s;
}
.ttra-franja:hover { border-color: #00A0E3; box-shadow: 0 2px 8px rgba(0,160,227,.1); }

/* ── Grip ── */
.ttra-franja__grip {
    color: #ccc;
    font-size: 18px;
    cursor: grab;
    padding-top: 6px;
    user-select: none;
    flex-shrink: 0;
}

/* ── Body ── */
.ttra-franja__body { flex: 1; display: flex; flex-direction: column; gap: 12px; }

/* ── Fila días ── */
.ttra-dias-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.ttra-dias-label {
    font-size: 11px;
    font-weight: 700;
    color: #646970;
    text-transform: uppercase;
    letter-spacing: .04em;
    flex-shrink: 0;
    min-width: 34px;
}
/* chip día */
.ttra-dia-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 2px solid #dcdcde;
    background: #f6f7f7;
    font-size: 11px;
    font-weight: 700;
    color: #646970;
    cursor: pointer;
    user-select: none;
    transition: all .15s;
    flex-shrink: 0;
}
.ttra-dia-chip:hover { border-color: #00A0E3; color: #00A0E3; background: #e8f4fd; }
.ttra-dia-chip--active { background: #00A0E3; border-color: #00A0E3; color: #fff; }

/* ── Fila campos horarios ── */
.ttra-campos-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
}
.ttra-campo {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ttra-campo label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #888;
}
.ttra-campo input[type="time"],
.ttra-campo select,
.ttra-campo input[type="number"] {
    border: 1px solid #c3c4c7;
    border-radius: 5px;
    padding: 6px 8px;
    font-size: 13px;
    background: #fafafa;
    transition: border-color .2s;
}
.ttra-campo input:focus,
.ttra-campo select:focus { border-color: #00A0E3; outline: none; background: #fff; }
.ttra-campo--time input  { width: 100px; }
.ttra-campo--inter select { width: 100px; }
.ttra-campo--plazas input { width: 70px; }
.ttra-campo--toggle { flex-direction: row; align-items: center; gap: 8px; padding-bottom: 4px; }
.ttra-campo--toggle label { margin: 0; font-size: 10px; }

/* ── Preview slots ── */
.ttra-preview {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    min-height: 22px;
}
.ttra-slots-count {
    font-size: 12px;
    font-weight: 700;
    color: #00A0E3;
    flex-shrink: 0;
}
.ttra-slot-tag {
    background: #e8f4fd;
    color: #003B6F;
    padding: 2px 7px;
    border-radius: 4px;
    font-size: 11px;
    font-family: monospace;
    font-weight: 600;
}
.ttra-preview--empty { color: #dc3545; font-size: 12px; }

/* ── Btn eliminar ── */
.ttra-franja__del {
    background: none;
    border: 1px solid #e2e4e7;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    cursor: pointer;
    color: #dc3545;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all .2s;
    margin-top: 2px;
}
.ttra-franja__del:hover { background: #dc3545; color: #fff; border-color: #dc3545; }

/* ── Warning días ── */
.ttra-dias-warning { color: #dc3545; font-size: 11px; margin-top: 2px; display: none; }
</style>

<div class="wrap ttra-admin-wrap">

    <h1 class="ttra-admin-title">
        <span class="dashicons dashicons-clock"></span>
        <?php esc_html_e( 'Horarios de Actividades', 'tictac-reservas-agua' ); ?>
    </h1>

    <?php if ( $msg === 'saved' ) : ?>
        <div class="notice notice-success is-dismissible"><p>✅ <?php esc_html_e( 'Horarios guardados correctamente.', 'tictac-reservas-agua' ); ?></p></div>
    <?php endif; ?>

    <!-- Selector actividad -->
    <div class="ttra-admin-card">
        <form method="GET" action="">
            <input type="hidden" name="page" value="ttra-horarios">
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <label style="font-weight:600; font-size:13px;"><?php esc_html_e( 'Actividad:', 'tictac-reservas-agua' ); ?></label>
                <select name="actividad_id" style="min-width:240px;">
                    <option value=""><?php esc_html_e( '-- Selecciona --', 'tictac-reservas-agua' ); ?></option>
                    <?php foreach ( $actividades as $act ) : ?>
                        <option value="<?php echo $act->id; ?>" <?php selected( $actividad_id, $act->id ); ?>>
                            <?php echo esc_html( $act->nombre . ( $act->subtipo ? ' — ' . $act->subtipo : '' ) ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="button button-primary"><?php esc_html_e( 'Cargar', 'tictac-reservas-agua' ); ?></button>
            </div>
        </form>

        <?php if ( $act_sel ) : ?>
            <div class="ttra-info-banner" style="margin-top:14px;">
                <strong><?php echo esc_html( $act_sel->nombre ); ?></strong>
                &nbsp;—&nbsp;<?php echo intval( $act_sel->duracion_minutos ); ?> min
                &nbsp;—&nbsp;<?php echo TTRA_Helpers::formato_precio( $act_sel->precio_base ); ?>
                <?php echo $act_sel->precio_tipo === 'por_persona' ? '/persona' : ''; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ( $act_sel ) : ?>
    <div class="ttra-admin-card ttra-card--full">

        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px; flex-wrap:wrap; gap:10px;">
            <div>
                <h2 style="margin:0 0 4px;"><?php esc_html_e( 'Configurar franjas horarias', 'tictac-reservas-agua' ); ?></h2>
                <p class="description" style="margin:0;">
                    <?php esc_html_e( 'Selecciona los días que aplican a cada franja. Para un día con horario distinto, crea una franja aparte con solo ese día marcado.', 'tictac-reservas-agua' ); ?>
                </p>
            </div>
            <button type="button" id="btn-add-franja" class="button button-primary" style="flex-shrink:0;">
                + <?php esc_html_e( 'Añadir franja', 'tictac-reservas-agua' ); ?>
            </button>
        </div>

        <form method="POST" action="" id="form-horarios">
            <input type="hidden" name="ttra_action" value="save_horario">
            <input type="hidden" name="ttra_nonce" value="<?php echo $nonce; ?>">
            <input type="hidden" name="actividad_id" value="<?php echo intval( $actividad_id ); ?>">

            <div id="horarios-container" style="display:flex; flex-direction:column; gap:10px; margin-top:16px;">

            <?php if ( empty( $franjas ) ) : ?>
                <p class="ttra-empty-msg" id="msg-sin-franjas">
                    🕐 <?php esc_html_e( 'Sin franjas configuradas. Añade la primera.', 'tictac-reservas-agua' ); ?>
                </p>
            <?php else : ?>
                <?php foreach ( $franjas as $fi => $f ) :
                    $dias_sel = $f['dias'];
                    $inter    = intval( $f['intervalo_minutos'] );
                    $plazas   = intval( $f['plazas_por_slot'] );
                ?>
                <div class="ttra-franja" data-fi="<?php echo $fi; ?>">
                    <span class="ttra-franja__grip">☰</span>
                    <div class="ttra-franja__body">

                        <!-- Días -->
                        <div class="ttra-dias-row">
                            <span class="ttra-dias-label">Días</span>
                            <?php foreach ( $dias_abrev as $num => $abrev ) :
                                $activo = in_array( $num, $dias_sel );
                            ?>
                            <label class="ttra-dia-chip <?php echo $activo ? 'ttra-dia-chip--active' : ''; ?>">
                                <input type="checkbox"
                                       name="horarios[<?php echo $fi; ?>][dias][]"
                                       value="<?php echo $num; ?>"
                                       <?php checked( $activo ); ?>
                                       style="display:none;">
                                <?php echo $abrev; ?>
                            </label>
                            <?php endforeach; ?>
                            <span class="ttra-dias-warning">⚠️ Selecciona al menos un día</span>
                        </div>

                        <!-- Campos horario -->
                        <div class="ttra-campos-row">
                            <div class="ttra-campo ttra-campo--time">
                                <label>Desde</label>
                                <input type="time" name="horarios[<?php echo $fi; ?>][hora_inicio]"
                                       value="<?php echo esc_attr( $f['hora_inicio'] ); ?>" required>
                            </div>
                            <div class="ttra-campo ttra-campo--time">
                                <label>Hasta</label>
                                <input type="time" name="horarios[<?php echo $fi; ?>][hora_fin]"
                                       value="<?php echo esc_attr( $f['hora_fin'] ); ?>" required>
                            </div>
                            <div class="ttra-campo ttra-campo--inter">
                                <label>Intervalo</label>
                                <select name="horarios[<?php echo $fi; ?>][intervalo_minutos]">
                                    <?php foreach ( [15,20,30,45,60,90,120] as $min ) : ?>
                                        <option value="<?php echo $min; ?>" <?php selected( $inter, $min ); ?>><?php echo $min; ?> min</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="ttra-campo ttra-campo--plazas">
                                <label>Plazas/slot</label>
                                <input type="number" name="horarios[<?php echo $fi; ?>][plazas_por_slot]"
                                       value="<?php echo $plazas; ?>" min="1" max="999">
                            </div>
                            <div class="ttra-campo ttra-campo--toggle">
                                <label>Activo</label>
                                <label class="ttra-toggle ttra-toggle--sm">
                                    <input type="checkbox" name="horarios[<?php echo $fi; ?>][activo]" value="1"
                                        <?php checked( $f['activo'], 1 ); ?>>
                                    <span class="ttra-toggle__slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Preview slots -->
                        <div class="ttra-preview"></div>

                    </div><!-- body -->
                    <button type="button" class="ttra-franja__del" title="Eliminar franja">✕</button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            </div><!-- container -->

            <div style="margin-top:20px; padding-top:16px; border-top:1px solid #f0f0f1; display:flex; gap:10px;">
                <?php submit_button( __( 'Guardar horarios', 'tictac-reservas-agua' ), 'primary', 'submit', false ); ?>
            </div>
        </form>
    </div>
    <?php endif; ?>

</div>

<!-- ══ Template nueva franja (PHP la renderiza para tener los días abreviados correctos) ══ -->
<script type="text/html" id="tpl-franja">
<div class="ttra-franja" data-fi="__FI__">
    <span class="ttra-franja__grip">☰</span>
    <div class="ttra-franja__body">
        <div class="ttra-dias-row">
            <span class="ttra-dias-label">Días</span>
            <?php foreach ( $dias_abrev as $num => $abrev ) : ?>
            <label class="ttra-dia-chip">
                <input type="checkbox" name="horarios[__FI__][dias][]" value="<?php echo $num; ?>" style="display:none;">
                <?php echo $abrev; ?>
            </label>
            <?php endforeach; ?>
            <span class="ttra-dias-warning">⚠️ Selecciona al menos un día</span>
        </div>
        <div class="ttra-campos-row">
            <div class="ttra-campo ttra-campo--time">
                <label>Desde</label>
                <input type="time" name="horarios[__FI__][hora_inicio]" value="09:00" required>
            </div>
            <div class="ttra-campo ttra-campo--time">
                <label>Hasta</label>
                <input type="time" name="horarios[__FI__][hora_fin]" value="18:00" required>
            </div>
            <div class="ttra-campo ttra-campo--inter">
                <label>Intervalo</label>
                <select name="horarios[__FI__][intervalo_minutos]">
                    <option value="15">15 min</option>
                    <option value="20">20 min</option>
                    <option value="30" selected>30 min</option>
                    <option value="45">45 min</option>
                    <option value="60">60 min</option>
                    <option value="90">90 min</option>
                    <option value="120">120 min</option>
                </select>
            </div>
            <div class="ttra-campo ttra-campo--plazas">
                <label>Plazas/slot</label>
                <input type="number" name="horarios[__FI__][plazas_por_slot]" value="10" min="1" max="999">
            </div>
            <div class="ttra-campo ttra-campo--toggle">
                <label>Activo</label>
                <label class="ttra-toggle ttra-toggle--sm">
                    <input type="checkbox" name="horarios[__FI__][activo]" value="1" checked>
                    <span class="ttra-toggle__slider"></span>
                </label>
            </div>
        </div>
        <div class="ttra-preview"></div>
    </div>
    <button type="button" class="ttra-franja__del" title="Eliminar">✕</button>
</div>
</script>

<script>
(function () {
    var fi        = <?php echo count($franjas); ?>;
    var container = document.getElementById('horarios-container');

    /* ── helpers ── */
    function toMin(t) {
        var p = t.split(':').map(Number);
        return p[0] * 60 + (p[1] || 0);
    }
    function fromMin(m) {
        return String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0');
    }

    /* ── preview slots ── */
    function updatePreview(franja) {
        var tIni = franja.querySelector('[name*="hora_inicio"]');
        var tFin = franja.querySelector('[name*="hora_fin"]');
        var tInt = franja.querySelector('[name*="intervalo_minutos"]');
        var prev = franja.querySelector('.ttra-preview');
        if (!tIni || !tFin || !prev) return;

        var ini  = toMin(tIni.value || '09:00');
        var fin  = toMin(tFin.value || '18:00');
        var inter = parseInt(tInt ? tInt.value : 30);
        var slots = [];

        for (var cur = ini; cur < fin; cur += inter) slots.push(fromMin(cur));

        if (slots.length === 0) {
            prev.innerHTML = '<span class="ttra-preview--empty">⚠️ Sin slots — revisa las horas</span>';
        } else {
            prev.innerHTML = '<span class="ttra-slots-count">' + slots.length + ' slots:</span>' +
                slots.map(function(s) { return '<span class="ttra-slot-tag">' + s + '</span>'; }).join('');
        }
    }

    /* ── chips días ── */
    function bindChips(franja) {
        franja.querySelectorAll('.ttra-dia-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var cb = chip.querySelector('input[type="checkbox"]');
                cb.checked = !cb.checked;
                chip.classList.toggle('ttra-dia-chip--active', cb.checked);
                var warn = franja.querySelector('.ttra-dias-warning');
                var alguno = franja.querySelectorAll('.ttra-dia-chip--active').length > 0;
                if (warn) warn.style.display = alguno ? 'none' : '';
            });
        });
    }

    /* ── bind franja completa ── */
    function bindFranja(franja) {
        bindChips(franja);
        franja.querySelectorAll('input[type="time"], select').forEach(function (el) {
            el.addEventListener('change', function () { updatePreview(franja); });
            el.addEventListener('input',  function () { updatePreview(franja); });
        });
        franja.querySelector('.ttra-franja__del')?.addEventListener('click', function () {
            if (confirm('¿Eliminar esta franja horaria?')) {
                franja.style.transition = 'opacity .2s';
                franja.style.opacity = '0';
                setTimeout(function () { franja.remove(); }, 200);
            }
        });
        updatePreview(franja);
    }

    /* ── franjas existentes ── */
    container.querySelectorAll('.ttra-franja').forEach(bindFranja);

    /* ── añadir nueva franja ── */
    document.getElementById('btn-add-franja')?.addEventListener('click', function () {
        var sinMsg = document.getElementById('msg-sin-franjas');
        if (sinMsg) sinMsg.remove();

        var tpl = document.getElementById('tpl-franja').innerHTML.replace(/__FI__/g, fi++);
        var tmp = document.createElement('div');
        tmp.innerHTML = tpl;
        var nueva = tmp.firstElementChild;
        container.appendChild(nueva);
        bindFranja(nueva);
        nueva.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    /* ── validación submit ── */
    document.getElementById('form-horarios')?.addEventListener('submit', function (e) {
        var ok = true;
        container.querySelectorAll('.ttra-franja').forEach(function (franja) {
            var dias = franja.querySelectorAll('.ttra-dia-chip--active').length;
            var warn = franja.querySelector('.ttra-dias-warning');
            if (dias === 0) {
                ok = false;
                if (warn) warn.style.display = '';
            }
        });
        if (!ok) {
            e.preventDefault();
            alert('Hay franjas sin ningún día seleccionado.');
        }
    });

})();
</script>
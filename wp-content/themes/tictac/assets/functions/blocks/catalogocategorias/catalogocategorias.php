<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_catalogocategorias',
        'title' => 'Catálogo Categorías',
        'fields' => array(

            // ── COLOR DE FONDO DEL BLOQUE ────────────────────────────────
            array(
                'key' => 'field_cc_color_fondo',
                'label' => 'Color de fondo del bloque',
                'name' => 'cc_color_fondo',
                'type' => 'color_picker',
                'default_value' => '#ffffff',
                'enable_opacity' => 1,
                'return_format' => 'array',
                'instructions' => 'Color de fondo del bloque completo.',
            ),

            // ── COLUMNAS POR BREAKPOINT ──────────────────────────────────
            array(
                'key' => 'field_cc_sep_columnas',
                'label' => '📐 COLUMNAS POR DISPOSITIVO',
                'name' => 'cc_sep_columnas',
                'type' => 'message',
                'message' => 'Define cuántas tarjetas se muestran por fila. Si hay más tarjetas que columnas, se activa el carrusel automáticamente.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_cols_desktop',
                'label' => 'Columnas Desktop (≥992px)',
                'name' => 'cc_cols_desktop',
                'type' => 'radio',
                'default_value' => '4',
                'choices' => array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'),
                'layout' => 'horizontal',
            ),
            array(
                'key' => 'field_cc_cols_tablet',
                'label' => 'Columnas Tablet (768px–991px)',
                'name' => 'cc_cols_tablet',
                'type' => 'radio',
                'default_value' => '2',
                'choices' => array('1'=>'1','2'=>'2','3'=>'3','4'=>'4'),
                'layout' => 'horizontal',
            ),
            array(
                'key' => 'field_cc_cols_mobile',
                'label' => 'Columnas Mobile (<768px)',
                'name' => 'cc_cols_mobile',
                'type' => 'radio',
                'default_value' => '1',
                'choices' => array('1'=>'1','2'=>'2','3'=>'3'),
                'layout' => 'horizontal',
            ),

            // ── CARRUSEL ─────────────────────────────────────────────────
            array(
                'key' => 'field_cc_sep_carousel',
                'label' => '🎠 CARRUSEL',
                'name' => 'cc_sep_carousel',
                'type' => 'message',
                'message' => 'Si hay más tarjetas que columnas, se activa el carrusel. Configura aquí sus controles.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_flecha_prev',
                'label' => 'Flecha anterior (izquierda)',
                'name' => 'cc_flecha_prev',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Imagen para el botón "anterior" del carrusel.',
            ),
            array(
                'key' => 'field_cc_flecha_next',
                'label' => 'Flecha siguiente (derecha)',
                'name' => 'cc_flecha_next',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Imagen para el botón "siguiente" del carrusel.',
            ),
            array(
                'key' => 'field_cc_punto_activo',
                'label' => 'Punto activo - Color',
                'name' => 'cc_punto_activo',
                'type' => 'color_picker',
                'default_value' => '#213975',
                'enable_opacity' => 1,
                'return_format' => 'array',
                'instructions' => 'Color del punto de paginación activo.',
            ),
            array(
                'key' => 'field_cc_punto_inactivo',
                'label' => 'Punto inactivo - Color',
                'name' => 'cc_punto_inactivo',
                'type' => 'color_picker',
                'default_value' => '#cccccc',
                'enable_opacity' => 1,
                'return_format' => 'array',
                'instructions' => 'Color de los puntos de paginación inactivos.',
            ),

            // ── CONFIGURACIÓN DEL BOTÓN ──────────────────────────────────
            array(
                'key' => 'field_cc_sep_boton',
                'label' => '🎨 CONFIGURACIÓN DEL BOTÓN',
                'name' => 'cc_sep_boton',
                'type' => 'message',
                'message' => 'Estilos globales para todos los botones del bloque.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_boton_fondo',
                'label' => 'Botón - Fondo',
                'name' => 'cc_boton_fondo',
                'type' => 'color_picker',
                'default_value' => '#47C8F5',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_texto',
                'label' => 'Botón - Color texto',
                'name' => 'cc_boton_texto',
                'type' => 'color_picker',
                'default_value' => '#ffffff',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_borde',
                'label' => 'Botón - Color borde',
                'name' => 'cc_boton_borde',
                'type' => 'color_picker',
                'default_value' => '#47C8F5',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_imagen',
                'label' => 'Botón - Imagen (derecha)',
                'name' => 'cc_boton_imagen',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Icono/imagen que aparece a la derecha del texto del botón.',
            ),

            // ── HOVER DEL BOTÓN ──────────────────────────────────────────
            array(
                'key' => 'field_cc_sep_hover',
                'label' => '✨ HOVER DEL BOTÓN',
                'name' => 'cc_sep_hover',
                'type' => 'message',
                'message' => 'Estilos al pasar el ratón sobre el botón.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_boton_hover_fondo',
                'label' => 'Hover - Fondo',
                'name' => 'cc_boton_hover_fondo',
                'type' => 'color_picker',
                'default_value' => '#213975',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_hover_texto',
                'label' => 'Hover - Color texto',
                'name' => 'cc_boton_hover_texto',
                'type' => 'color_picker',
                'default_value' => '#ffffff',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_hover_borde',
                'label' => 'Hover - Color borde',
                'name' => 'cc_boton_hover_borde',
                'type' => 'color_picker',
                'default_value' => '#213975',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_boton_hover_imagen',
                'label' => 'Hover - Imagen del botón',
                'name' => 'cc_boton_hover_imagen',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Imagen alternativa del botón al hacer hover. Opcional.',
            ),

            // ── FONDO DE LA TARJETA ──────────────────────────────────────
            array(
                'key' => 'field_cc_sep_tarjeta',
                'label' => '🃏 FONDO DE LA TARJETA',
                'name' => 'cc_sep_tarjeta',
                'type' => 'message',
                'message' => 'Color de fondo de cada tarjeta, normal y en hover.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_tarjeta_fondo',
                'label' => 'Tarjeta - Fondo',
                'name' => 'cc_tarjeta_fondo',
                'type' => 'color_picker',
                'default_value' => '#ffffff',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_cc_tarjeta_hover_fondo',
                'label' => 'Tarjeta - Fondo en hover',
                'name' => 'cc_tarjeta_hover_fondo',
                'type' => 'color_picker',
                'default_value' => '#f0f8ff',
                'enable_opacity' => 1,
                'return_format' => 'array',
            ),

            // ── TARJETAS (REPEATER) ──────────────────────────────────────
            array(
                'key' => 'field_cc_sep_items',
                'label' => '🃏 TARJETAS',
                'name' => 'cc_sep_items',
                'type' => 'message',
                'message' => 'Añade y ordena las tarjetas del catálogo.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_cc_items',
                'label' => 'Tarjetas',
                'name' => 'cc_items',
                'type' => 'repeater',
                'min' => 1,
                'layout' => 'block',
                'button_label' => '+ Añadir tarjeta',
                'sub_fields' => array(
                    array(
                        'key' => 'field_cc_item_tipo_media',
                        'label' => 'Tipo de media',
                        'name' => 'cc_item_tipo_media',
                        'type' => 'radio',
                        'default_value' => 'imagen',
                        'choices' => array(
                            'imagen' => '🖼️ Imagen (ocupa todo el ancho)',
                            'gif'    => '🎞️ GIF (centrado, tamaño original)',
                        ),
                        'layout' => 'horizontal',
                    ),
                    array(
                        'key' => 'field_cc_item_media',
                        'label' => 'Imagen / GIF',
                        'name' => 'cc_item_media',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'instructions' => 'Sube la imagen o GIF de la tarjeta.',
                    ),
                    array(
                        'key' => 'field_cc_item_media_hover',
                        'label' => 'Imagen hover (opcional)',
                        'name' => 'cc_item_media_hover',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'instructions' => 'Imagen alternativa al hacer hover. Solo para tipo Imagen.',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_cc_item_tipo_media',
                            'operator' => '==',
                            'value'    => 'imagen',
                        ))),
                    ),
                    array(
                        'key' => 'field_cc_item_titulo',
                        'label' => 'Título',
                        'name' => 'cc_item_titulo',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                    array(
                        'key' => 'field_cc_item_parrafo',
                        'label' => 'Párrafo',
                        'name' => 'cc_item_parrafo',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                    array(
                        'key' => 'field_cc_item_boton_texto',
                        'label' => 'Texto del botón',
                        'name' => 'cc_item_boton_texto',
                        'type' => 'text',
                        'default_value' => 'RESERVAR',
                    ),
                    array(
                        'key' => 'field_cc_item_boton_enlace',
                        'label' => 'Enlace del botón',
                        'name' => 'cc_item_boton_enlace',
                        'type' => 'link',
                        'return_format' => 'array',
                    ),
                ),
            ),

        ),
        'location' => array(array(array(
            'param'    => 'block',
            'operator' => '==',
            'value'    => 'acf/catalogocategorias',
        ))),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));

endif;

if (!function_exists('tictac_catalogocategorias_acf')) {
    function tictac_catalogocategorias_acf()
    {
        acf_register_block_type(array(
            'name'            => 'catalogocategorias',
            'title'           => __('Catálogo Categorías', 'tictac'),
            'description'     => __('Grid de tarjetas con carrusel automático, imagen/gif, título, párrafo y botón personalizable.', 'tictac'),
            'render_callback' => 'tictac_catalogocategorias_render',
            'mode'            => 'preview',
            'supports'        => array('mode' => true, 'align' => false),
            'icon'            => 'grid-view',
            'keywords'        => array('catalogo', 'categorias', 'tarjetas', 'grid', 'carousel'),
        ));
    }
    add_action('acf/init', 'tictac_catalogocategorias_acf');
}

if (!function_exists('tictac_catalogocategorias_scripts')) {
    function tictac_catalogocategorias_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_style('tictac-catalogocategorias', get_stylesheet_directory_uri() . '/assets/functions/blocks/catalogocategorias/catalogocategorias.min.css', array(), '1.0');
        }
    }
    add_action('wp_enqueue_scripts', 'tictac_catalogocategorias_scripts');
    add_action('admin_enqueue_scripts', 'tictac_catalogocategorias_scripts');
}

if (!function_exists('cc_color_css')) {
    function cc_color_css($color, $fallback = 'transparent')
    {
        if (empty($color)) return $fallback;
        if (is_string($color)) return $color;
        if (is_array($color)) {
            if (!empty($color['rgba'])) return $color['rgba'];
            if (!empty($color['hex']))  return $color['hex'];
            if (isset($color['red'])) {
                $r = intval($color['red']);
                $g = intval($color['green']);
                $b = intval($color['blue']);
                $a = isset($color['alpha']) ? floatval($color['alpha']) : 1;
                return "rgba($r,$g,$b,$a)";
            }
        }
        return $fallback;
    }
}

if (!function_exists('tictac_catalogocategorias_render')) {
    function tictac_catalogocategorias_render($block)
    {
        $block_id    = isset($block['id']) ? esc_attr($block['id']) : 'cc-' . uniqid();
        $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
        $anchor      = !empty($block['anchor']) ? ' id="' . esc_attr($block['anchor']) . '"' : '';

        // Colores bloque
        $fondo               = cc_color_css(get_field('cc_color_fondo'),        '#ffffff');
        $tarjeta_fondo       = cc_color_css(get_field('cc_tarjeta_fondo'),       '#ffffff');
        $tarjeta_hover_fondo = cc_color_css(get_field('cc_tarjeta_hover_fondo'), '#f0f8ff');

        // Botón
        $btn_fondo           = cc_color_css(get_field('cc_boton_fondo'),         '#47C8F5');
        $btn_texto           = cc_color_css(get_field('cc_boton_texto'),         '#ffffff');
        $btn_borde           = cc_color_css(get_field('cc_boton_borde'),         '#47C8F5');
        $btn_img             = get_field('cc_boton_imagen');
        $btn_img_url         = ($btn_img && !empty($btn_img['url'])) ? esc_url($btn_img['url']) : '';

        // Botón hover
        $btn_hover_fondo     = cc_color_css(get_field('cc_boton_hover_fondo'),   '#213975');
        $btn_hover_texto     = cc_color_css(get_field('cc_boton_hover_texto'),   '#ffffff');
        $btn_hover_borde     = cc_color_css(get_field('cc_boton_hover_borde'),   '#213975');
        $btn_hover_img       = get_field('cc_boton_hover_imagen');
        $btn_hover_img_url   = ($btn_hover_img && !empty($btn_hover_img['url'])) ? esc_url($btn_hover_img['url']) : '';

        // Carrusel
        $flecha_prev         = get_field('cc_flecha_prev');
        $flecha_prev_url     = ($flecha_prev && !empty($flecha_prev['url'])) ? esc_url($flecha_prev['url']) : '';
        $flecha_next         = get_field('cc_flecha_next');
        $flecha_next_url     = ($flecha_next && !empty($flecha_next['url'])) ? esc_url($flecha_next['url']) : '';
        $punto_activo        = cc_color_css(get_field('cc_punto_activo'),   '#213975');
        $punto_inactivo      = cc_color_css(get_field('cc_punto_inactivo'), '#cccccc');

        // Columnas (whitelist)
        $cols_validas  = array('1','2','3','4','5','6');
        $cols_desktop  = in_array(get_field('cc_cols_desktop'), $cols_validas) ? intval(get_field('cc_cols_desktop')) : 4;
        $cols_tablet   = in_array(get_field('cc_cols_tablet'),  $cols_validas) ? intval(get_field('cc_cols_tablet'))  : 2;
        $cols_mobile   = in_array(get_field('cc_cols_mobile'),  $cols_validas) ? intval(get_field('cc_cols_mobile'))  : 1;

        $items = get_field('cc_items');
        $total = is_array($items) ? count($items) : 0;

        // ¿Se activa carrusel? Solo si hay más items que columnas en algún breakpoint
        $carousel_desktop = $total > $cols_desktop;
        $carousel_tablet  = $total > $cols_tablet;
        $carousel_mobile  = $total > $cols_mobile;
        $hay_carousel     = $carousel_desktop || $carousel_tablet || $carousel_mobile;
        ?>

        <style>
            #cc-block-<?php echo $block_id; ?> {
                background-color: <?php echo $fondo; ?>;
            }
            /* Grid normal */
            #cc-block-<?php echo $block_id; ?> .cc_grid {
                grid-template-columns: repeat(<?php echo $cols_desktop; ?>, 1fr);
            }
            @media (max-width: 991px) {
                #cc-block-<?php echo $block_id; ?> .cc_grid {
                    grid-template-columns: repeat(<?php echo $cols_tablet; ?>, 1fr);
                }
            }
            @media (max-width: 767px) {
                #cc-block-<?php echo $block_id; ?> .cc_grid {
                    grid-template-columns: repeat(<?php echo $cols_mobile; ?>, 1fr);
                }
            }
            /* Carrusel: ancho de cada slide según columnas */
            #cc-block-<?php echo $block_id; ?> .cc_carousel_track .cc_tarjeta {
                width: calc((100% - (<?php echo $cols_desktop; ?> - 1) * 24px) / <?php echo $cols_desktop; ?>);
                flex-shrink: 0;
            }
            @media (max-width: 991px) {
                #cc-block-<?php echo $block_id; ?> .cc_carousel_track .cc_tarjeta {
                    width: calc((100% - (<?php echo $cols_tablet; ?> - 1) * 16px) / <?php echo $cols_tablet; ?>);
                }
            }
            @media (max-width: 767px) {
                #cc-block-<?php echo $block_id; ?> .cc_carousel_track .cc_tarjeta {
                    width: calc((100% - (<?php echo $cols_mobile; ?> - 1) * 16px) / <?php echo $cols_mobile; ?>);
                }
            }
            /* Puntos */
            #cc-block-<?php echo $block_id; ?> .cc_dot { background-color: <?php echo $punto_inactivo; ?> !important; }
            #cc-block-<?php echo $block_id; ?> .cc_dot.active { background-color: <?php echo $punto_activo; ?> !important; }
            /* Tarjeta */
            #cc-block-<?php echo $block_id; ?> .cc_tarjeta { background-color: <?php echo $tarjeta_fondo; ?>; }
            #cc-block-<?php echo $block_id; ?> .cc_tarjeta:hover { background-color: <?php echo $tarjeta_hover_fondo; ?>; }
            /* Botón */
            #cc-block-<?php echo $block_id; ?> .cc_boton {
                background-color: <?php echo $btn_fondo; ?> !important;
                color: <?php echo $btn_texto; ?> !important;
                border-color: <?php echo $btn_borde; ?> !important;
            }
            <?php if ($btn_img_url) : ?>
            #cc-block-<?php echo $block_id; ?> .cc_boton .cc_boton_img { content: url('<?php echo $btn_img_url; ?>') !important; }
            <?php endif; ?>
            #cc-block-<?php echo $block_id; ?> .cc_boton:hover,
            #cc-block-<?php echo $block_id; ?> .cc_tarjeta:hover .cc_boton {
                background-color: <?php echo $btn_hover_fondo; ?> !important;
                color: <?php echo $btn_hover_texto; ?> !important;
                border-color: <?php echo $btn_hover_borde; ?> !important;
            }
            <?php if ($btn_hover_img_url) : ?>
            #cc-block-<?php echo $block_id; ?> .cc_boton:hover .cc_boton_img,
            #cc-block-<?php echo $block_id; ?> .cc_tarjeta:hover .cc_boton .cc_boton_img {
                content: url('<?php echo $btn_hover_img_url; ?>') !important;
            }
            <?php endif; ?>
        </style>

        <div id="cc-block-<?php echo $block_id; ?>"<?php echo $anchor; ?>
             class="catalogocategorias<?php echo $block_class ? ' ' . $block_class : ''; ?> containerancho"
             data-cols-desktop="<?php echo $cols_desktop; ?>"
             data-cols-tablet="<?php echo $cols_tablet; ?>"
             data-cols-mobile="<?php echo $cols_mobile; ?>"
             data-total="<?php echo $total; ?>">

            <?php if ($hay_carousel) : ?>
            <!-- CARRUSEL -->
            <div class="cc_carousel">
                <div class="cc_carousel_wrapper">
                    <div class="cc_carousel_track">
            <?php else : ?>
            <!-- GRID -->
            <div class="cc_grid">
            <?php endif; ?>

                <?php if ($items) : foreach ($items as $item) :
                    $tipo_media      = isset($item['cc_item_tipo_media']) ? $item['cc_item_tipo_media'] : 'imagen';
                    $media           = isset($item['cc_item_media']) && is_array($item['cc_item_media']) ? $item['cc_item_media'] : null;
                    $media_hover     = isset($item['cc_item_media_hover']) && is_array($item['cc_item_media_hover']) ? $item['cc_item_media_hover'] : null;
                    $media_hover_url = ($media_hover && !empty($media_hover['url']) && $tipo_media === 'imagen') ? esc_url($media_hover['url']) : '';
                    $tiene_img_hover = $media_hover_url !== '';
                    $titulo          = isset($item['cc_item_titulo'])      ? $item['cc_item_titulo']      : '';
                    $parrafo         = isset($item['cc_item_parrafo'])     ? $item['cc_item_parrafo']     : '';
                    $boton_texto     = isset($item['cc_item_boton_texto']) ? esc_html($item['cc_item_boton_texto']) : 'RESERVAR';
                    $boton_enlace    = isset($item['cc_item_boton_enlace']) && is_array($item['cc_item_boton_enlace']) ? $item['cc_item_boton_enlace'] : null;
                    $enlace_url      = ($boton_enlace && !empty($boton_enlace['url'])) ? esc_url($boton_enlace['url']) : '#';
                    $enlace_target   = ($boton_enlace && !empty($boton_enlace['target'])) ? esc_attr($boton_enlace['target']) : '_self';
                ?>
                    <div class="cc_tarjeta<?php echo $tiene_img_hover ? ' cc_tarjeta--img-hover' : ''; ?>">

                        <?php if ($media && !empty($media['url'])) : ?>
                            <div class="cc_media cc_media--<?php echo esc_attr($tipo_media); ?><?php echo $tiene_img_hover ? ' cc_media--img-hover' : ''; ?>">
                                <?php if ($tipo_media === 'gif') : ?>
                                    <div class="cc_gif_wrapper">
                                        <img src="<?php echo esc_url($media['url']); ?>"
                                             alt="<?php echo esc_attr($media['alt']); ?>"
                                             loading="lazy">
                                    </div>
                                <?php else : ?>
                                    <img src="<?php echo esc_url($media['url']); ?>"
                                         alt="<?php echo esc_attr($media['alt']); ?>"
                                         class="cc_media_default"
                                         loading="lazy">
                                    <?php if ($tiene_img_hover) : ?>
                                        <img src="<?php echo $media_hover_url; ?>"
                                             alt=""
                                             class="cc_media_hover_img"
                                             aria-hidden="true"
                                             loading="lazy">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="cc_body">
                            <?php if ($titulo) : ?>
                                <div class="cc_titulo"><?php echo $titulo; ?></div>
                            <?php endif; ?>
                            <?php if ($parrafo) : ?>
                                <div class="cc_parrafo"><?php echo $parrafo; ?></div>
                            <?php endif; ?>
                            <a href="<?php echo $enlace_url; ?>"
                               target="<?php echo $enlace_target; ?>"
                               class="cc_boton">
                                <span class="cc_boton_label"><?php echo $boton_texto; ?></span>
                                <?php if ($btn_img_url) : ?>
                                    <img src="<?php echo $btn_img_url; ?>" alt="" class="cc_boton_img" aria-hidden="true">
                                <?php endif; ?>
                            </a>
                        </div>

                    </div>
                <?php endforeach; endif; ?>

            <?php if ($hay_carousel) : ?>
                    </div><!-- /.cc_carousel_track -->
                </div><!-- /.cc_carousel_wrapper -->

                <!-- Controles: flechas izq + puntos derecha -->
                <div class="cc_carousel_controls d-flex justify-content-between align-items-center" style="display:none!important;">
                    <div class="cc_carousel_arrows d-flex align-items-center">
                        <button class="cc_arrow cc_arrow_prev" aria-label="Anterior">
                            <?php if ($flecha_prev_url) : ?>
                                <img src="<?php echo $flecha_prev_url; ?>" alt="Anterior">
                            <?php else : ?>
                                <span>&#8592;</span>
                            <?php endif; ?>
                        </button>
                        <button class="cc_arrow cc_arrow_next" aria-label="Siguiente">
                            <?php if ($flecha_next_url) : ?>
                                <img src="<?php echo $flecha_next_url; ?>" alt="Siguiente">
                            <?php else : ?>
                                <span>&#8594;</span>
                            <?php endif; ?>
                        </button>
                    </div>
                    <div class="cc_carousel_dots"></div>
                </div>
            </div><!-- /.cc_carousel -->
            <?php else : ?>
            </div><!-- /.cc_grid -->
            <?php endif; ?>

        </div><!-- /#cc-block -->

        <?php if ($hay_carousel) : ?>
        <script>
        (function() {
            var block      = document.getElementById('cc-block-<?php echo $block_id; ?>');
            if (!block) return;

            var track      = block.querySelector('.cc_carousel_track');
            var controls   = block.querySelector('.cc_carousel_controls');
            var dotsWrap   = block.querySelector('.cc_carousel_dots');
            var btnPrev    = block.querySelector('.cc_arrow_prev');
            var btnNext    = block.querySelector('.cc_arrow_next');
            var tarjetas   = track.querySelectorAll('.cc_tarjeta');
            var total      = tarjetas.length;
            var current    = 0;

            function getCols() {
                var w = window.innerWidth;
                if (w <= 767) return parseInt(block.dataset.colsMobile)  || 1;
                if (w <= 991) return parseInt(block.dataset.colsTablet)  || 2;
                return parseInt(block.dataset.colsDesktop) || 4;
            }

            function getPages() { return Math.ceil(total / getCols()); }

            function buildDots() {
                dotsWrap.innerHTML = '';
                var pages = getPages();
                for (var i = 0; i < pages; i++) {
                    var dot = document.createElement('button');
                    dot.className = 'cc_dot' + (i <= current ? ' active' : '');
                    dot.setAttribute('aria-label', 'Página ' + (i + 1));
                    dot.dataset.page = i;
                    dot.addEventListener('click', function() { goTo(parseInt(this.dataset.page)); });
                    dotsWrap.appendChild(dot);
                }
            }

            function goTo(page) {
                var pages = getPages();
                if (page < 0) page = pages - 1;
                if (page >= pages) page = 0;
                current = page;
                var cols  = getCols();
                var gap   = window.innerWidth <= 991 ? 16 : 24;
                var cardW = (track.offsetWidth - (cols - 1) * gap) / cols;
                var offset = current * cols * (cardW + gap);
                track.style.transform = 'translateX(-' + offset + 'px)';
                // Puntos acumulativos: activos todos los anteriores + el actual
                dotsWrap.querySelectorAll('.cc_dot').forEach(function(d, i) {
                    d.classList.toggle('active', i <= current);
                });
                controls.style.cssText = pages > 1 ? 'display:flex!important' : 'display:none!important';
            }

            btnPrev.addEventListener('click', function() { goTo(current - 1); });
            btnNext.addEventListener('click', function() { goTo(current + 1); });

            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (current >= getPages()) current = getPages() - 1;
                    buildDots();
                    goTo(current);
                }, 150);
            });

            buildDots();
            goTo(0);
        })();
        </script>
        <?php endif; ?>

        <?php
    }
}
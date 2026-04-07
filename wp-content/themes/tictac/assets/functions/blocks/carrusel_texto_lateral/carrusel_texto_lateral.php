<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_carrusel_texto_lateral',
        'title' => 'Carrusel Texto Lateral',
        'fields' => array(
            array(
                'key' => 'field_carrusel_lat_titulo',
                'label' => 'Título (WYSIWYG)',
                'name' => 'carrusel_texto_lateral_titulo',
                'type' => 'wysiwyg',
                'required' => 0,
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'delay' => 0,
                'instructions' => 'Título que aparecerá encima del carrusel y texto',
            ),
            // ===== TIPO DE MEDIA =====
            array(
                'key' => 'field_carrusel_lat_tipo_media',
                'label' => 'Tipo de Media',
                'name' => 'carrusel_texto_lateral_tipo_media',
                'type' => 'select',
                'required' => 1,
                'instructions' => 'Elige si mostrar un carrusel de imágenes o un iframe (Google Maps, vídeo, etc.)',
                'choices' => array(
                    'carrusel' => 'Carrusel de Imágenes',
                    'iframe' => 'Iframe (Google Maps, etc.)',
                ),
                'default_value' => 'carrusel',
                'allow_null' => 0,
            ),
            // ===== IFRAME =====
            array(
                'key' => 'field_carrusel_lat_iframe_url',
                'label' => 'URL o Código del Iframe',
                'name' => 'carrusel_texto_lateral_iframe_url',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 4,
                'instructions' => 'Pega aquí la URL del iframe o el código completo <iframe>.',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_carrusel_lat_tipo_media',
                            'operator' => '==',
                            'value' => 'iframe',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_carrusel_lat_autoloop',
                'label' => 'Activar Autoloop',
                'name' => 'carrusel_texto_lateral_autoloop',
                'type' => 'true_false',
                'required' => 0,
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Activado',
                'ui_off_text' => 'Desactivado',
                'instructions' => 'Activar o desactivar el autoplay del carrusel',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_carrusel_lat_tipo_media',
                            'operator' => '==',
                            'value' => 'carrusel',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_carrusel_lat_invertir',
                'label' => 'Invertir Layout - Desktop/Tablet Grande (>768px)',
                'name' => 'carrusel_texto_lateral_invertir',
                'type' => 'true_false',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Invertido (Textos Izq / Carrusel Dch)',
                'ui_off_text' => 'Normal (Carrusel Izq / Textos Dch)',
                'instructions' => 'Invierte la posición del carrusel y los textos en DESKTOP y TABLET GRANDE',
            ),
            array(
                'key' => 'field_carrusel_lat_invertir_mobile',
                'label' => 'Invertir Layout - Móvil/Tablet Pequeña (<768px)',
                'name' => 'carrusel_texto_lateral_invertir_mobile',
                'type' => 'true_false',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Invertido (Texto Primero / Carrusel Después)',
                'ui_off_text' => 'Normal (Carrusel Primero / Texto Después)',
                'instructions' => 'Invierte la posición en MÓVIL y TABLET PEQUEÑA.',
            ),
            // ===== CARRUSEL DESKTOP =====
            array(
                'key' => 'field_carrusel_lat_imagenes_repeater',
                'label' => 'Imágenes del Carrusel - DESKTOP (>1024px)',
                'name' => 'carrusel_texto_lateral_imagenes',
                'type' => 'repeater',
                'min' => 0,
                'layout' => 'row',
                'button_label' => '+ Añadir Imagen Desktop',
                'collapsed' => 'carrusel_texto_lateral_imagen',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_carrusel_lat_tipo_media',
                            'operator' => '==',
                            'value' => 'carrusel',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_carrusel_lat_imagen',
                        'label' => 'Imagen',
                        'name' => 'carrusel_texto_lateral_imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'large',
                    ),
                ),
            ),
            // ===== CARRUSEL MÓVIL/TABLET =====
            array(
                'key' => 'field_carrusel_lat_imagenes_mobile_repeater',
                'label' => 'Imágenes del Carrusel - MÓVIL/TABLET (<1024px)',
                'name' => 'carrusel_texto_lateral_imagenes_mobile',
                'type' => 'repeater',
                'min' => 0,
                'layout' => 'row',
                'button_label' => '+ Añadir Imagen Móvil/Tablet',
                'collapsed' => 'carrusel_texto_lateral_imagen',
                'instructions' => 'Imágenes específicas para móvil y tablet. Si está vacío, se usarán las imágenes de desktop.',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_carrusel_lat_tipo_media',
                            'operator' => '==',
                            'value' => 'carrusel',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_carrusel_lat_imagen_mobile',
                        'label' => 'Imagen',
                        'name' => 'carrusel_texto_lateral_imagen',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                        'preview_size' => 'large',
                    ),
                ),
            ),
            array(
                'key' => 'field_carrusel_lat_imagen_comun',
                'label' => 'Imagen Común para Textos',
                'name' => 'carrusel_texto_lateral_imagen_comun',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Imagen que aparecerá a la izquierda de TODOS los textos.',
            ),
            array(
                'key' => 'field_carrusel_lat_usar_imagen_individual',
                'label' => 'Usar Imagen Individual por Texto',
                'name' => 'carrusel_texto_lateral_usar_imagen_individual',
                'type' => 'true_false',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Activado',
                'ui_off_text' => 'Desactivado',
            ),
            array(
                'key' => 'field_carrusel_lat_bloque_subtitulo',
                'label' => 'Subtítulo del Bloque de Texto',
                'name' => 'carrusel_texto_lateral_bloque_subtitulo',
                'type' => 'wysiwyg',
                'required' => 0,
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'delay' => 0,
                'instructions' => 'Subtítulo (Montserrat, azul, uppercase)',
            ),
            array(
                'key' => 'field_carrusel_lat_bloque_titulo',
                'label' => 'Título del Bloque de Texto',
                'name' => 'carrusel_texto_lateral_bloque_titulo',
                'type' => 'wysiwyg',
                'required' => 0,
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'delay' => 0,
                'instructions' => 'Título (Playfair Display, 48px)',
            ),
            array(
                'key' => 'field_carrusel_lat_textos_repeater',
                'label' => 'Elementos de Texto',
                'name' => 'carrusel_texto_lateral_textos',
                'type' => 'repeater',
                'min' => 0,
                'layout' => 'block',
                'button_label' => 'Añadir Texto',
                'sub_fields' => array(
                    array(
                        'key' => 'field_carrusel_lat_texto_imagen',
                        'label' => 'Imagen del Texto (Opcional)',
                        'name' => 'carrusel_texto_lateral_texto_imagen',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_carrusel_lat_usar_imagen_individual',
                                    'operator' => '==',
                                    'value' => 1,
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_carrusel_lat_texto_contenido',
                        'label' => 'Texto (WYSIWYG)',
                        'name' => 'carrusel_texto_lateral_texto_contenido',
                        'type' => 'wysiwyg',
                        'required' => 0,
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                        'delay' => 0,
                    ),
                ),
            ),
            // ===== BOTÓN CTA 1 =====
            array(
                'key' => 'field_carrusel_lat_boton_cta',
                'label' => 'Botón CTA 1 (debajo de los textos)',
                'name' => 'carrusel_texto_lateral_boton_cta',
                'type' => 'link',
                'required' => 0,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_bg',
                'label' => 'CTA 1 - Color de Fondo',
                'name' => 'carrusel_texto_lateral_cta1_bg',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_color',
                'label' => 'CTA 1 - Color de Texto',
                'name' => 'carrusel_texto_lateral_cta1_color',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#0A0A0A',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_borde',
                'label' => 'CTA 1 - Color de Borde',
                'name' => 'carrusel_texto_lateral_cta1_borde',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_hover_bg',
                'label' => 'CTA 1 - Color de Fondo (Hover)',
                'name' => 'carrusel_texto_lateral_cta1_hover_bg',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_hover_color',
                'label' => 'CTA 1 - Color de Texto (Hover)',
                'name' => 'carrusel_texto_lateral_cta1_hover_color',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta1_hover_borde',
                'label' => 'CTA 1 - Color de Borde (Hover)',
                'name' => 'carrusel_texto_lateral_cta1_hover_borde',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            // ===== BOTÓN CTA 2 =====
            array(
                'key' => 'field_carrusel_lat_boton_cta2',
                'label' => 'Botón CTA 2 (debajo de los textos)',
                'name' => 'carrusel_texto_lateral_boton_cta2',
                'type' => 'link',
                'required' => 0,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_bg',
                'label' => 'CTA 2 - Color de Fondo',
                'name' => 'carrusel_texto_lateral_cta2_bg',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_color',
                'label' => 'CTA 2 - Color de Texto',
                'name' => 'carrusel_texto_lateral_cta2_color',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_borde',
                'label' => 'CTA 2 - Color de Borde',
                'name' => 'carrusel_texto_lateral_cta2_borde',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_hover_bg',
                'label' => 'CTA 2 - Color de Fondo (Hover)',
                'name' => 'carrusel_texto_lateral_cta2_hover_bg',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_hover_color',
                'label' => 'CTA 2 - Color de Texto (Hover)',
                'name' => 'carrusel_texto_lateral_cta2_hover_color',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#0A0A0A',
            ),
            array(
                'key' => 'field_carrusel_lat_cta2_hover_borde',
                'label' => 'CTA 2 - Color de Borde (Hover)',
                'name' => 'carrusel_texto_lateral_cta2_hover_borde',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#D4AF37',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/carrusel-texto-lateral',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Carrusel o iframe a la izquierda con elementos de texto a la derecha',
        'show_in_rest' => 0,
    ));

endif;

if (!function_exists('tictac_carrusel_texto_lateral_acf')) {
    function tictac_carrusel_texto_lateral_acf()
    {
        acf_register_block_type([
            'name'            => 'carrusel-texto-lateral',
            'title'           => __('Carrusel Texto Lateral', 'tictac'),
            'description'     => __('Carrusel o iframe a la izquierda con elementos de texto a la derecha', 'tictac'),
            'render_callback' => 'tictac_carrusel_texto_lateral_render',
            'mode'            => 'preview',
            'supports'        => array('mode' => true, 'align' => false),
            'icon'            => 'align-pull-left',
            'keywords'        => ['carrusel', 'texto', 'lateral', 'dos columnas', 'lista', 'iframe', 'mapa'],
        ]);
    }
    add_action('acf/init', 'tictac_carrusel_texto_lateral_acf');
}

if (!function_exists('tictac_carrusel_texto_lateral_scripts')) {
    function tictac_carrusel_texto_lateral_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_style('tictac-carrusel-texto-lateral', get_stylesheet_directory_uri() . '/assets/functions/blocks/carrusel_texto_lateral/carrusel_texto_lateral.min.css', array(), '2.6');
        }
    }
    add_action('wp_enqueue_scripts', 'tictac_carrusel_texto_lateral_scripts');
}

if (!function_exists('tictac_carrusel_texto_lateral_render')) {
    function tictac_carrusel_texto_lateral_render($block)
    {
        $titulo                 = get_field('carrusel_texto_lateral_titulo');
        $tipo_media             = get_field('carrusel_texto_lateral_tipo_media') ?: 'carrusel';
        $iframe_url             = get_field('carrusel_texto_lateral_iframe_url');
        $imagenes_desktop       = get_field('carrusel_texto_lateral_imagenes');
        $imagenes_mobile        = get_field('carrusel_texto_lateral_imagenes_mobile');
        $imagen_comun           = get_field('carrusel_texto_lateral_imagen_comun');
        $usar_imagen_individual = get_field('carrusel_texto_lateral_usar_imagen_individual');
        $textos                 = get_field('carrusel_texto_lateral_textos');
        $bloque_subtitulo       = get_field('carrusel_texto_lateral_bloque_subtitulo');
        $bloque_titulo          = get_field('carrusel_texto_lateral_bloque_titulo');
        $autoloop               = get_field('carrusel_texto_lateral_autoloop');
        $invertir               = get_field('carrusel_texto_lateral_invertir');
        $invertir_mobile        = get_field('carrusel_texto_lateral_invertir_mobile');
        $block_class            = isset($block['className']) ? esc_attr($block['className']) : '';
        $block_id               = isset($block['id']) ? esc_attr($block['id']) : 'carrusel-texto-lateral-' . uniqid();

        $boton_cta        = get_field('carrusel_texto_lateral_boton_cta');
        $cta1_bg          = get_field('carrusel_texto_lateral_cta1_bg') ?: '#D4AF37';
        $cta1_color       = get_field('carrusel_texto_lateral_cta1_color') ?: '#0A0A0A';
        $cta1_borde       = get_field('carrusel_texto_lateral_cta1_borde') ?: '#D4AF37';
        $cta1_hover_bg    = get_field('carrusel_texto_lateral_cta1_hover_bg') ?: 'rgba(255,255,255,0.1)';
        $cta1_hover_color = get_field('carrusel_texto_lateral_cta1_hover_color') ?: '#D4AF37';
        $cta1_hover_borde = get_field('carrusel_texto_lateral_cta1_hover_borde') ?: '#D4AF37';

        $boton_cta2        = get_field('carrusel_texto_lateral_boton_cta2');
        $cta2_bg           = get_field('carrusel_texto_lateral_cta2_bg') ?: 'transparent';
        $cta2_color        = get_field('carrusel_texto_lateral_cta2_color') ?: '#D4AF37';
        $cta2_borde        = get_field('carrusel_texto_lateral_cta2_borde') ?: '#D4AF37';
        $cta2_hover_bg     = get_field('carrusel_texto_lateral_cta2_hover_bg') ?: '#D4AF37';
        $cta2_hover_color  = get_field('carrusel_texto_lateral_cta2_hover_color') ?: '#0A0A0A';
        $cta2_hover_borde  = get_field('carrusel_texto_lateral_cta2_hover_borde') ?: '#D4AF37';

        if ($tipo_media === 'carrusel') {
            if (!$imagenes_desktop || !is_array($imagenes_desktop) || empty($imagenes_desktop)) {
                if (is_admin()) echo '<p style="padding: 20px; background: #f0f0f0; text-align: center;">Por favor, añade al menos una imagen al carrusel desktop.</p>';
                return;
            }
        } elseif ($tipo_media === 'iframe') {
            if (empty($iframe_url)) {
                if (is_admin()) echo '<p style="padding: 20px; background: #f0f0f0; text-align: center;">Por favor, introduce la URL o código del iframe.</p>';
                return;
            }
        }

        $usar_desktop_en_mobile = false;
        if ($tipo_media === 'carrusel') {
            if (!$imagenes_mobile || !is_array($imagenes_mobile) || empty($imagenes_mobile)) {
                $imagenes_mobile        = $imagenes_desktop;
                $usar_desktop_en_mobile = true;
            }
        }

        $tiene_carrusel_mobile = ($tipo_media === 'carrusel' && !$usar_desktop_en_mobile);
        $mobile_carousel_class = $tiene_carrusel_mobile ? 'has-mobile-carousel' : '';
        $invertir_class        = $invertir ? 'is-inverted' : '';
        $invertir_mobile_class = $invertir_mobile ? 'is-inverted-mobile' : '';

        $iframe_html = '';
        if ($tipo_media === 'iframe' && !empty($iframe_url)) {
            $iframe_url_trimmed = trim($iframe_url);
            if (strpos($iframe_url_trimmed, '<iframe') !== false) {
                $iframe_html = $iframe_url_trimmed;
            } else {
                $iframe_html = '<iframe src="' . esc_url($iframe_url_trimmed) . '" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
            }
        }

        $tiene_cta1 = (!empty($boton_cta) && !empty($boton_cta['url']));
        $tiene_cta2 = (!empty($boton_cta2) && !empty($boton_cta2['url']));

        // Helper para renderizar los botones CTA
        $render_ctas = function() use ($tiene_cta1, $tiene_cta2, $boton_cta, $boton_cta2) {
            if ($tiene_cta1) :
                $cta_url    = esc_url($boton_cta['url']);
                $cta_title  = !empty($boton_cta['title']) ? esc_html($boton_cta['title']) : 'Ver más';
                $cta_target = !empty($boton_cta['target']) ? esc_attr($boton_cta['target']) : '_self';
            ?>
                <a href="<?php echo $cta_url; ?>"
                   class="carrusel-texto-lateral-cta carrusel-texto-lateral-cta-1"
                   target="<?php echo $cta_target; ?>"
                   <?php echo $cta_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo $cta_title; ?>
                </a>
            <?php endif;
            if ($tiene_cta2) :
                $cta2_url    = esc_url($boton_cta2['url']);
                $cta2_title  = !empty($boton_cta2['title']) ? esc_html($boton_cta2['title']) : 'Ver más';
                $cta2_target = !empty($boton_cta2['target']) ? esc_attr($boton_cta2['target']) : '_self';
            ?>
                <a href="<?php echo $cta2_url; ?>"
                   class="carrusel-texto-lateral-cta carrusel-texto-lateral-cta-2"
                   target="<?php echo $cta2_target; ?>"
                   <?php echo $cta2_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo $cta2_title; ?>
                </a>
            <?php endif;
        };

        $render_carrusel = function($imagenes, $carrusel_id, $carrusel_type) {
            $total = count($imagenes);
        ?>
            <div class="carrusel-texto-lateral-item-container position-relative overflow-hidden"
                 id="<?php echo esc_attr($carrusel_id); ?>"
                 data-carrusel-type="<?php echo esc_attr($carrusel_type); ?>"
                 data-total-slides="<?php echo $total; ?>">

                <div class="carrusel-texto-lateral-track d-flex">
                    <?php foreach ($imagenes as $img) :
                        $imagen = isset($img['carrusel_texto_lateral_imagen']) && is_array($img['carrusel_texto_lateral_imagen']) ? $img['carrusel_texto_lateral_imagen'] : null;
                        if (!$imagen || empty($imagen['url'])) continue;
                    ?>
                        <div class="carrusel-texto-lateral-slide flex-shrink-0">
                            <div class="carrusel-texto-lateral-image-wrapper">
                                <img src="<?php echo esc_url($imagen['url']); ?>"
                                     alt="<?php echo isset($imagen['alt']) ? esc_attr($imagen['alt']) : ''; ?>"
                                     class="img-fluid carrusel-texto-lateral-img">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total > 1) : ?>
                <div class="carrusel-texto-lateral-bottom-controls d-flex justify-content-between align-items-center">
                    <div class="carrusel-texto-lateral-arrows-wrapper d-flex align-items-center gap-2">
                        <button class="btn carrusel-texto-lateral-arrow carrusel-texto-lateral-arrow-prev"
                                aria-label="Anterior" type="button">
                            <svg width="12" height="19" viewBox="0 0 12 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6259 17.0074L3.31446 9.41768L11.6006 1.86263C11.7328 1.75522 11.835 1.62057 11.8988 1.46989C11.9626 1.31922 11.9861 1.15686 11.9675 0.996218C11.9663 0.791222 11.8931 0.591957 11.7588 0.427708C11.6244 0.26346 11.436 0.142865 11.2211 0.0835914C11.007 -0.0021326 10.7683 -0.0225763 10.5404 0.0252809C10.3126 0.0731381 10.1076 0.186727 9.95604 0.349267L7.90665 2.20918L0 9.41768L1.63194 10.9079L3.09942 12.2364L9.91809 18.4745C10.0351 18.596 10.1824 18.6898 10.3476 18.7481C10.5127 18.8065 10.6909 18.8275 10.8669 18.8096C11.0903 18.8035 11.3059 18.7332 11.4831 18.6087C11.6602 18.4843 11.7899 18.312 11.8536 18.1164C11.9451 17.9326 11.9724 17.7275 11.9317 17.5291C11.8909 17.3306 11.7841 17.1485 11.6259 17.0074Z" fill="white"/>
                            </svg>
                        </button>
                        <button class="btn carrusel-texto-lateral-arrow carrusel-texto-lateral-arrow-next"
                                aria-label="Siguiente" type="button">
                            <svg width="12" height="19" viewBox="0 0 12 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: scaleX(-1);">
                                <path d="M11.6259 17.0074L3.31446 9.41768L11.6006 1.86263C11.7328 1.75522 11.835 1.62057 11.8988 1.46989C11.9626 1.31922 11.9861 1.15686 11.9675 0.996218C11.9663 0.791222 11.8931 0.591957 11.7588 0.427708C11.6244 0.26346 11.436 0.142865 11.2211 0.0835914C11.007 -0.0021326 10.7683 -0.0225763 10.5404 0.0252809C10.3126 0.0731381 10.1076 0.186727 9.95604 0.349267L7.90665 2.20918L0 9.41768L1.63194 10.9079L3.09942 12.2364L9.91809 18.4745C10.0351 18.596 10.1824 18.6898 10.3476 18.7481C10.5127 18.8065 10.6909 18.8275 10.8669 18.8096C11.0903 18.8035 11.3059 18.7332 11.4831 18.6087C11.6602 18.4843 11.7899 18.312 11.8536 18.1164C11.9451 17.9326 11.9724 17.7275 11.9317 17.5291C11.8909 17.3306 11.7841 17.1485 11.6259 17.0074Z" fill="white"/>
                            </svg>
                        </button>
                    </div>
                    <div class="carrusel-texto-lateral-pagination-wrapper flex-grow-1 ms-3">
                        <div class="carrusel-texto-lateral-pagination">
                            <div class="carrusel-texto-lateral-pagination-fill"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php
        };
    ?>
        <style>
            #<?php echo $block_id; ?>-scope .carrusel-texto-lateral-cta-1 {
                background: <?php echo $cta1_bg; ?>;
                color: <?php echo $cta1_color; ?>;
                border: 2px solid <?php echo $cta1_borde; ?>;
            }
            #<?php echo $block_id; ?>-scope .carrusel-texto-lateral-cta-1:hover {
                background: <?php echo $cta1_hover_bg; ?>;
                color: <?php echo $cta1_hover_color; ?>;
                border-color: <?php echo $cta1_hover_borde; ?>;
            }
            #<?php echo $block_id; ?>-scope .carrusel-texto-lateral-cta-2 {
                background: <?php echo $cta2_bg; ?>;
                color: <?php echo $cta2_color; ?>;
                border: 2px solid <?php echo $cta2_borde; ?>;
            }
            #<?php echo $block_id; ?>-scope .carrusel-texto-lateral-cta-2:hover {
                background: <?php echo $cta2_hover_bg; ?>;
                color: <?php echo $cta2_hover_color; ?>;
                border-color: <?php echo $cta2_hover_borde; ?>;
            }
        </style>

        <div id="<?php echo $block_id; ?>-scope"
             class="<?php echo $block_class; ?> carrusel-texto-lateral-block <?php echo $mobile_carousel_class; ?> <?php echo $invertir_class; ?> <?php echo $invertir_mobile_class; ?>"
             data-block-id="<?php echo $block_id; ?>"
             data-type="carrusel-texto-lateral"
             data-tipo-media="<?php echo esc_attr($tipo_media); ?>"
             data-tiene-carrusel-mobile="<?php echo $tiene_carrusel_mobile ? 'true' : 'false'; ?>"
             data-autoloop="<?php echo $autoloop ? 'true' : 'false'; ?>"
             data-invertir="<?php echo $invertir ? 'true' : 'false'; ?>"
             data-invertir-mobile="<?php echo $invertir_mobile ? 'true' : 'false'; ?>">

            <div class="carrusel-texto-lateral-container d-flex flex-column align-items-center">

                <?php if (!empty($titulo)) : ?>
                    <div class="carrusel-texto-lateral-titulo-content col-11 col-lg-12">
                        <?php echo $titulo; ?>
                    </div>
                <?php endif; ?>

                <div class="carrusel-texto-lateral-grid">

                    <!-- COLUMNA IZQUIERDA: CARRUSEL O IFRAME -->
                    <div class="carrusel-texto-lateral-columna-izquierda">
                        <div class="carrusel-texto-lateral-carrusel-wrapper">

                            <?php if ($tipo_media === 'iframe') : ?>
                                <div class="carrusel-texto-lateral-iframe-container">
                                    <?php echo $iframe_html; ?>
                                </div>
                            <?php else : ?>
                                <div class="carrusel-texto-lateral-content carrusel-texto-lateral-content-desktop position-relative">
                                    <?php $render_carrusel($imagenes_desktop, $block_id . '-carrusel-desktop', 'desktop'); ?>
                                </div>
                                <div class="carrusel-texto-lateral-content carrusel-texto-lateral-content-mobile position-relative">
                                    <?php $render_carrusel($imagenes_mobile, $block_id . '-carrusel-mobile', 'mobile'); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: SUBTÍTULO + TÍTULO + LÍNEA + TEXTOS + CTA (solo desktop) -->
                    <div class="carrusel-texto-lateral-columna-derecha pe-xl-4">

                        <?php if (!empty($bloque_subtitulo)) : ?>
                            <div class="carrusel-texto-lateral-bloque-subtitulo"><?php echo wp_kses_post($bloque_subtitulo); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($bloque_titulo)) : ?>
                            <div class="carrusel-texto-lateral-bloque-titulo"><?php echo wp_kses_post($bloque_titulo); ?></div>
                            <div class="carrusel-texto-lateral-bloque-linea"></div>
                        <?php endif; ?>

                        <?php if (is_array($textos) && !empty($textos)) : ?>
                            <div class="carrusel-texto-lateral-lista-textos d-flex flex-column justify-content-between">
                                <?php foreach ($textos as $texto_item) :
                                    $contenido    = isset($texto_item['carrusel_texto_lateral_texto_contenido']) ? $texto_item['carrusel_texto_lateral_texto_contenido'] : '';
                                    $imagen_texto = isset($texto_item['carrusel_texto_lateral_texto_imagen']) ? $texto_item['carrusel_texto_lateral_texto_imagen'] : null;
                                    if (empty($contenido)) continue;
                                    $imagen_a_mostrar = null;
                                    if ($usar_imagen_individual && $imagen_texto && !empty($imagen_texto['url'])) {
                                        $imagen_a_mostrar = $imagen_texto;
                                    } elseif (!$usar_imagen_individual && $imagen_comun && !empty($imagen_comun['url'])) {
                                        $imagen_a_mostrar = $imagen_comun;
                                    }
                                ?>
                                    <div class="carrusel-texto-lateral-texto-item d-flex flex-row">
                                        <?php if ($imagen_a_mostrar) : ?>
                                            <div class="carrusel-texto-lateral-texto-icono me-2">
                                                <img src="<?php echo esc_url($imagen_a_mostrar['url']); ?>"
                                                     alt="<?php echo esc_attr(isset($imagen_a_mostrar['alt']) ? $imagen_a_mostrar['alt'] : ''); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="carrusel-texto-lateral-texto-contenido">
                                            <?php echo $contenido; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tiene_cta1 || $tiene_cta2) : ?>
                            <!--
                                CTA DESKTOP: visible en md+ (≥768px), oculto en móvil.
                                Bootstrap d-none d-md-flex lo gestiona sin conflictos.
                            -->
                            <div class="carrusel-texto-lateral-cta-wrapper d-none d-md-flex flex-wrap align-items-center gap-3">
                                <?php $render_ctas(); ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div><!-- /.carrusel-texto-lateral-grid -->

                <?php if ($tiene_cta1 || $tiene_cta2) : ?>
                    <!--
                        CTA MÓVIL: visible solo en móvil (<768px), oculto en md+.
                        Está fuera del grid → queda físicamente debajo de todo el bloque.
                        Bootstrap d-flex d-md-none lo gestiona sin conflictos.
                    -->
                    <div class="carrusel-texto-lateral-cta-wrapper d-flex d-md-none flex-wrap align-items-center gap-3 mt-4 col-12">
                        <?php $render_ctas(); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <script>
            (function() {
                'use strict';

                class TictacCarruselTextoLateral {
                    constructor(blockElement) {
                        this.blockElement        = blockElement;
                        this.blockId             = blockElement.getAttribute('data-block-id');
                        this.tipoMedia           = blockElement.getAttribute('data-tipo-media') || 'carrusel';
                        this.tieneCarruselMobile = blockElement.getAttribute('data-tiene-carrusel-mobile') === 'true';
                        this.autoloopEnabled     = blockElement.getAttribute('data-autoloop') === 'true';

                        if (this.tipoMedia === 'iframe') return;

                        this.containerDesktop = blockElement.querySelector('#' + this.blockId + '-carrusel-desktop');
                        this.containerMobile  = blockElement.querySelector('#' + this.blockId + '-carrusel-mobile');
                        this.carruseles       = {};

                        if (this.containerDesktop) this.carruseles.desktop = this.initCarrusel(this.containerDesktop, 'desktop');
                        if (this.containerMobile)  this.carruseles.mobile  = this.initCarrusel(this.containerMobile, 'mobile');
                    }

                    initCarrusel(container, type) {
                        var track       = container.querySelector('.carrusel-texto-lateral-track');
                        var slides      = container.querySelectorAll('.carrusel-texto-lateral-slide');
                        var totalSlides = slides.length;
                        if (totalSlides === 0) return null;

                        var carrusel = {
                            type: type,
                            container: container,
                            track: track,
                            slides: slides,
                            totalSlides: totalSlides,
                            currentIndex: 0,
                            autoplayInterval: 4000,
                            autoplayTimer: null,
                            transitionDuration: 0.8,
                        };

                        this.bindArrowEvents(carrusel);
                        this.bindImageClickEvents(carrusel);
                        this.updateCarrusel(carrusel);
                        if (this.autoloopEnabled) this.startAutoplay(carrusel);
                        return carrusel;
                    }

                    bindArrowEvents(carrusel) {
                        var self = this;
                        carrusel.container.querySelectorAll('.carrusel-texto-lateral-arrow').forEach(function(arrow) {
                            arrow.addEventListener('click', function(e) { self.handleArrowClick(e, carrusel); });
                        });
                    }

                    handleArrowClick(e, carrusel) {
                        e.preventDefault();
                        var isNext = e.currentTarget.classList.contains('carrusel-texto-lateral-arrow-next');
                        if (isNext) {
                            if (carrusel.currentIndex >= carrusel.totalSlides - 1) return;
                            carrusel.currentIndex++;
                        } else {
                            if (carrusel.currentIndex <= 0) return;
                            carrusel.currentIndex--;
                        }
                        this.updateCarrusel(carrusel);
                        if (this.autoloopEnabled) this.startAutoplay(carrusel);
                    }

                    bindImageClickEvents(carrusel) {
                        var self = this;
                        carrusel.container.querySelectorAll('.carrusel-texto-lateral-img').forEach(function(img) {
                            img.addEventListener('click', function(e) { self.handleImageClick(e, carrusel); });
                            img.style.cursor = 'pointer';
                        });
                    }

                    handleImageClick(e, carrusel) {
                        e.preventDefault();
                        if (carrusel.currentIndex >= carrusel.totalSlides - 1) return;
                        carrusel.currentIndex++;
                        this.updateCarrusel(carrusel);
                        if (this.autoloopEnabled) this.startAutoplay(carrusel);
                    }

                    startAutoplay(carrusel) {
                        if (!this.autoloopEnabled) return;
                        if (carrusel.autoplayTimer) clearInterval(carrusel.autoplayTimer);
                        if (carrusel.totalSlides <= 1) return;
                        var self = this;
                        carrusel.autoplayTimer = setInterval(function() {
                            if (carrusel.currentIndex >= carrusel.totalSlides - 1) {
                                clearInterval(carrusel.autoplayTimer);
                                carrusel.autoplayTimer = null;
                                return;
                            }
                            carrusel.currentIndex++;
                            self.updateCarrusel(carrusel);
                        }, carrusel.autoplayInterval);
                    }

                    updateCarrusel(carrusel) {
                        if (!carrusel.track || !carrusel.slides[0]) return;

                        var slideWidth = carrusel.slides[0].clientWidth;
                        carrusel.track.style.transition = 'transform ' + carrusel.transitionDuration + 's cubic-bezier(0.4, 0, 0.2, 1)';
                        carrusel.track.style.transform  = 'translateX(' + (carrusel.currentIndex * slideWidth * -1) + 'px)';

                        var prevArrow = carrusel.container.querySelector('.carrusel-texto-lateral-arrow-prev');
                        var nextArrow = carrusel.container.querySelector('.carrusel-texto-lateral-arrow-next');

                        if (prevArrow) {
                            var prevPath = prevArrow.querySelector('svg path');
                            if (carrusel.currentIndex <= 0) { prevPath.setAttribute('opacity', '0.5'); prevArrow.style.pointerEvents = 'none'; }
                            else { prevPath.removeAttribute('opacity'); prevArrow.style.pointerEvents = 'auto'; }
                        }
                        if (nextArrow) {
                            var nextPath = nextArrow.querySelector('svg path');
                            if (carrusel.currentIndex >= carrusel.totalSlides - 1) { nextPath.setAttribute('opacity', '0.5'); nextArrow.style.pointerEvents = 'none'; }
                            else { nextPath.removeAttribute('opacity'); nextArrow.style.pointerEvents = 'auto'; }
                        }

                        var fill = carrusel.container.querySelector('.carrusel-texto-lateral-pagination-fill');
                        if (fill) fill.style.width = (((carrusel.currentIndex + 1) / carrusel.totalSlides) * 100) + '%';
                    }
                }

                function initCarruselesTextoLateral() {
                    document.querySelectorAll('[data-type="carrusel-texto-lateral"]').forEach(function(el) {
                        new TictacCarruselTextoLateral(el);
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initCarruselesTextoLateral);
                } else {
                    initCarruselesTextoLateral();
                }
            })();
        </script>
    <?php
    }
}
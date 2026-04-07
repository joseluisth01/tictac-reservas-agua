<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_galeria_grid',
        'title' => 'Galería Grid Interactiva',
        'fields' => array(
            array(
                'key' => 'field_galeria_ancho',
                'label' => 'Ancho del Grid',
                'name' => 'galeria_grid_ancho',
                'type' => 'radio',
                'required' => 0,
                'default_value' => '3',
                'choices' => array(
                    '2' => '2 columnas de ancho',
                    '3' => '3 columnas de ancho',
                ),
                'instructions' => 'Define si la galería tendrá 2 o 3 columnas',
            ),
            array(
                'key' => 'field_galeria_flecha_izq',
                'label' => 'Flecha Izquierda (Anterior)',
                'name' => 'galeria_flecha_izq',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Sube la imagen para el botón de anterior. Si no subes nada, usará ← por defecto.',
            ),
            array(
                'key' => 'field_galeria_flecha_der',
                'label' => 'Flecha Derecha (Siguiente)',
                'name' => 'galeria_flecha_der',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'instructions' => 'Sube la imagen para el botón de siguiente. Si no subes nada, usará → por defecto.',
            ),
            array(
                'key' => 'field_galeria_colecciones',
                'label' => 'Colecciones de Galerías',
                'name' => 'galeria_colecciones',
                'type' => 'repeater',
                'min' => 1,
                'layout' => 'block',
                'button_label' => '+ Añadir Galería',
                'instructions' => 'Crea múltiples galerías. Cada una tendrá su propio conjunto de imágenes.',
                'sub_fields' => array(
                    array(
                        'key' => 'field_galeria_nombre',
                        'label' => 'Nombre de la Galería',
                        'name' => 'galeria_nombre',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Ej: Portfolio, Productos, Equipo',
                        'instructions' => 'Este nombre aparecerá en el botón',
                    ),
                    array(
                        'key' => 'field_galeria_separador',
                        'label' => 'Separador de Tab (Imagen)',
                        'name' => 'galeria_separador',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'instructions' => 'Imagen de separador que aparecerá después de este botón de galería. Déjalo vacío si no deseas separador.',
                    ),
                    array(
                        'key' => 'field_galeria_imagenes',
                        'label' => 'Imágenes de la Galería',
                        'name' => 'galeria_grid_imagenes',
                        'type' => 'repeater',
                        'min' => 0,
                        'layout' => 'block',
                        'button_label' => '+ Añadir Imagen',
                        'instructions' => 'Agrega las imágenes en el orden que deseas.',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_galeria_imagen',
                                'label' => 'Imagen',
                                'name' => 'galeria_imagen',
                                'type' => 'image',
                                'required' => 1,
                                'return_format' => 'array',
                                'preview_size' => 'medium',
                            ),
                            array(
                                'key' => 'field_galeria_altura',
                                'label' => 'Altura',
                                'name' => 'galeria_altura',
                                'type' => 'radio',
                                'required' => 0,
                                'default_value' => '1',
                                'choices' => array(
                                    '1' => 'Altura estándar (1 cuadrado)',
                                    '2' => 'Altura extra (2 cuadrados)',
                                ),
                                'instructions' => 'Define si la imagen ocupa 1 o 2 cuadrados de alto',
                            ),
                            array(
                                'key' => 'field_galeria_enlace',
                                'label' => 'Enlace',
                                'name' => 'galeria_enlace',
                                'type' => 'link',
                                'required' => 0,
                                'return_format' => 'array',
                                'instructions' => 'Opcional: Enlaza esta imagen a una página. Déjalo vacío si no quieres enlace.',
                            ),
                            array(
                                'key' => 'field_galeria_titulo_hover',
                                'label' => 'Título (Hover)',
                                'name' => 'galeria_titulo_hover',
                                'type' => 'text',
                                'required' => 0,
                                'instructions' => 'Título que aparecerá sobre la imagen al hacer hover. Si existe, se mostrará un overlay oscuro con el título arriba y el texto del enlace abajo.',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/galeria-grid',
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
        'description' => 'Galería grid interactiva sin espacios entre imágenes',
        'show_in_rest' => 0,
    ));

endif;

if (!function_exists('tictac_galeria_grid_acf')) {
    function tictac_galeria_grid_acf()
    {
        acf_register_block_type([
            'name'        => 'galeria-grid',
            'title'        => __('Galería Grid Interactiva', 'tictac'),
            'description'    => __('Galería con grid responsive sin espacios', 'tictac'),
            'render_callback'  => 'tictac_galeria_grid_render',
            'mode'        => 'preview',
            'supports'    => array(
                'mode' => true,
                'align' => false,
            ),
            'icon'        => 'format-gallery',
            'keywords'      => ['galeria', 'grid', 'imagenes', 'layout'],
        ]);
    }

    add_action('acf/init', 'tictac_galeria_grid_acf');
}

if (!function_exists('tictac_galeria_grid_enqueue')) {
    function tictac_galeria_grid_enqueue()
    {
        if (!is_admin()) {
            wp_enqueue_style(
                'tictac-galeria-grid',
                get_stylesheet_directory_uri() . '/assets/functions/blocks/galeria_grid/galeria_grid.min.css',
                array(),
                '1.0'
            );
        }
    }
    
    add_action('wp_enqueue_scripts', 'tictac_galeria_grid_enqueue');
}

/**
 * Función helper para renderizar una imagen con hover overlay
 */
if (!function_exists('tictac_galeria_render_imagen_con_hover')) {
    function tictac_galeria_render_imagen_con_hover($item, $clase_wrapper = '', $estilo_wrapper = '', $estilo_img = '') {
        $imagen = isset($item['galeria_imagen']) && is_array($item['galeria_imagen']) ? $item['galeria_imagen'] : null;
        $enlace = isset($item['galeria_enlace']) && is_array($item['galeria_enlace']) ? $item['galeria_enlace'] : null;
        $titulo_hover = isset($item['galeria_titulo_hover']) && !empty($item['galeria_titulo_hover']) ? esc_html($item['galeria_titulo_hover']) : '';
        
        if (!$imagen || empty($imagen['url'])) {
            return '';
        }
        
        $imagen_url = esc_url($imagen['url']);
        $imagen_alt = isset($imagen['alt']) ? esc_attr($imagen['alt']) : '';
        
        $tiene_enlace = $enlace && !empty($enlace['url']);
        $enlace_url = $tiene_enlace ? esc_url($enlace['url']) : '';
        $enlace_target = ($tiene_enlace && isset($enlace['target']) && $enlace['target']) ? esc_attr($enlace['target']) : '_self';
        $enlace_title = ($tiene_enlace && isset($enlace['title']) && $enlace['title']) ? esc_attr($enlace['title']) : '';
        
        // Mostrar overlay si tiene título o texto de enlace
        $mostrar_overlay = $tiene_enlace && ($titulo_hover || $enlace_title);
        $clase_overlay = $mostrar_overlay ? ' galeria-item-has-overlay' : '';
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr($clase_wrapper . $clase_overlay); ?>" style="<?php echo esc_attr($estilo_wrapper); ?>">
            <?php if ($tiene_enlace) : ?>
                <a href="<?php echo $enlace_url; ?>" 
                   target="<?php echo $enlace_target; ?>"
                   class="galeria-item-link">
                   <?php if ($mostrar_overlay) : ?>
                        <div class="galeria-hover-overlay">
                            <div class="galeria-hover-content">
                                <?php if ($titulo_hover) : ?>
                                    <span class="galeria-hover-title"><?php echo $titulo_hover; ?></span>
                                <?php endif; ?>
                                <?php if ($enlace_title) : ?>
                                    <span class="galeria-hover-link-text"><?php echo $enlace_title; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
            <?php endif; ?>
            
            <img src="<?php echo $imagen_url; ?>" 
                 alt="<?php echo $imagen_alt; ?>" 
                 style="<?php echo esc_attr($estilo_img); ?>">
            
            <?php if ($tiene_enlace) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('tictac_galeria_grid_render')) {
    function tictac_galeria_grid_render($block)
    {
        $ancho = get_field('galeria_grid_ancho');
        $colecciones = get_field('galeria_colecciones');
        $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
        $block_id = isset($block['id']) ? esc_attr($block['id']) : 'galeria-grid-' . uniqid();
        
        $ancho = $ancho ? intval($ancho) : 3;
        
        if (!$colecciones || !is_array($colecciones) || empty($colecciones)) {
            if (is_admin()) {
                echo '<p style="padding: 20px; background: #f0f0f0; text-align: center;">Por favor, añade al menos una galería con imágenes.</p>';
            }
            return;
        }
        
        $flecha_izq = get_field('galeria_flecha_izq');
        $flecha_der = get_field('galeria_flecha_der');
        $flecha_izq_url = $flecha_izq && isset($flecha_izq['url']) ? esc_url($flecha_izq['url']) : null;
        $flecha_der_url = $flecha_der && isset($flecha_der['url']) ? esc_url($flecha_der['url']) : null;
        ?>
        
        <!-- Estilos para hover overlay -->
        <style>
            /* HOVER OVERLAY */
            .galeria-item-link {
                display: block;
                width: 100%;
                height: 100%;
                position: relative;
            }
            .galeria-hover-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.3s ease;
                z-index: 2;
            }
            .galeria-hover-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 20px;
                opacity: 0;
                transform: translateY(10px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            .galeria-hover-title {
                color: #fff;
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 8px;
                line-height: 1.3;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            .galeria-hover-link-text {
                color: rgba(255, 255, 255, 0.85);
                font-size: 14px;
                font-weight: 400;
                text-decoration: underline;
                text-underline-offset: 3px;
                text-shadow: 0 1px 3px rgba(0,0,0,0.3);
            }
            
            /* Hover state */
            .galeria-item-has-overlay:hover .galeria-hover-overlay {
                background: rgba(0, 0, 0, 0.65);
            }
            .galeria-item-has-overlay:hover .galeria-hover-content {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .galeria-hover-title {
                    font-size: 15px;
                    margin-bottom: 5px;
                }
                .galeria-hover-link-text {
                    font-size: 12px;
                }
                .galeria-hover-content {
                    padding: 15px;
                }
            }
            @media (max-width: 480px) {
                .galeria-hover-title {
                    font-size: 13px;
                }
                .galeria-hover-link-text {
                    font-size: 11px;
                }
                .galeria-hover-content {
                    padding: 10px;
                }
            }
        </style>
        
        <!-- ===== VERSIÓN DESKTOP (>1023px) ===== -->
        <div class="<?php echo $block_class; ?> galeria-grid-block" 
             data-block-id="<?php echo $block_id; ?>"
             data-ancho="<?php echo $ancho; ?>">
            
            <?php 
            $galeriasConContenido = array_filter($colecciones, function($col) {
                $imagenes = isset($col['galeria_grid_imagenes']) ? $col['galeria_grid_imagenes'] : array();
                return is_array($imagenes) && !empty($imagenes);
            });
            ?>
            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-nav-wrapper">
                <div class="galeria-tabs-wrapper">
                <div class="col-9 d-flex flex-row justify-content-between px-2">
                    <div class="galeria-tabs">
                        <?php foreach ($galeriasConContenido as $idx => $coleccion) : 
                            $nombre = isset($coleccion['galeria_nombre']) ? esc_html($coleccion['galeria_nombre']) : 'Galería ' . ($idx + 1);
                            $separador = isset($coleccion['galeria_separador']) && is_array($coleccion['galeria_separador']) ? $coleccion['galeria_separador'] : null;
                            $separador_url = $separador && isset($separador['url']) ? esc_url($separador['url']) : null;
                        ?>
                            <button class="galeria-tab-btn<?php echo $idx === 0 ? ' active' : ''; ?>" 
                                    data-tab="<?php echo $idx; ?>">
                                <?php echo $nombre; ?>
                            </button>
                            <?php if ($separador_url && $idx < count($galeriasConContenido) - 1) : ?>
                                <div class="galeria-tab-separator">
                                    <img src="<?php echo $separador_url; ?>" alt="Separador" class="galeria-separator-img">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="galeria-arrows">
                        <button class="galeria-arrow galeria-arrow-prev" data-direction="prev">
                            <?php if ($flecha_izq_url) : ?>
                                <img src="<?php echo $flecha_izq_url; ?>" alt="Anterior" class="galeria-arrow-img">
                            <?php else : ?>
                                ←
                            <?php endif; ?>
                        </button>
                        <button class="galeria-arrow galeria-arrow-next" data-direction="next">
                            <?php if ($flecha_der_url) : ?>
                                <img src="<?php echo $flecha_der_url; ?>" alt="Siguiente" class="galeria-arrow-img">
                            <?php else : ?>
                                →
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Galerías Desktop -->
            <?php foreach ($galeriasConContenido as $tab_idx => $coleccion) :
                $imagenes = isset($coleccion['galeria_grid_imagenes']) ? $coleccion['galeria_grid_imagenes'] : array();
            ?>
                <div class="galeria-grid-container<?php echo $tab_idx === 0 ? ' active' : ''; ?>" 
                     data-galeria="<?php echo $tab_idx; ?>"
                     style="<?php echo $tab_idx === 0 ? '' : 'display: none;'; ?>">
                    <div class="galeria-grid" 
                         style="grid-template-columns: repeat(<?php echo $ancho; ?>, 1fr);">
                        
                        <?php foreach ($imagenes as $img_idx => $item) :
                            $imagen = isset($item['galeria_imagen']) && is_array($item['galeria_imagen']) ? $item['galeria_imagen'] : null;
                            $altura = isset($item['galeria_altura']) ? intval($item['galeria_altura']) : 1;
                            $enlace = isset($item['galeria_enlace']) && is_array($item['galeria_enlace']) ? $item['galeria_enlace'] : null;
                            $titulo_hover = isset($item['galeria_titulo_hover']) && !empty($item['galeria_titulo_hover']) ? esc_html($item['galeria_titulo_hover']) : '';
                            
                            if (!$imagen || empty($imagen['url'])) {
                                continue;
                            }
                            
                            $imagen_url = esc_url($imagen['url']);
                            $imagen_alt = isset($imagen['alt']) ? esc_attr($imagen['alt']) : '';
                            $altura_class = $altura === 2 ? 'galeria-item-double' : '';
                            
                            $tiene_enlace = $enlace && !empty($enlace['url']);
                            $enlace_url = $tiene_enlace ? esc_url($enlace['url']) : '';
                            $enlace_target = ($tiene_enlace && isset($enlace['target']) && $enlace['target']) ? esc_attr($enlace['target']) : '_self';
                            $enlace_title = ($tiene_enlace && isset($enlace['title']) && $enlace['title']) ? esc_attr($enlace['title']) : '';
                            
                            $mostrar_overlay = $tiene_enlace && ($titulo_hover || $enlace_title);
                            $clase_overlay = $mostrar_overlay ? ' galeria-item-has-overlay' : '';
                        ?>
                            <div class="galeria-item <?php echo $altura_class . $clase_overlay; ?>" 
                                 data-altura="<?php echo $altura; ?>">
                                <div class="galeria-item-inner">
                                    <?php if ($tiene_enlace) : ?>
                                        <a href="<?php echo $enlace_url; ?>" 
                                           target="<?php echo $enlace_target; ?>"
                                           class="galeria-item-link">
                                            <?php if ($mostrar_overlay) : ?>
                                            <div class="galeria-hover-overlay">
                                                <div class="galeria-hover-content">
                                                    <?php if ($titulo_hover) : ?>
                                                        <span class="galeria-hover-title"><?php echo $titulo_hover; ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($enlace_title) : ?>
                                                        <span class="galeria-hover-link-text"><?php echo $enlace_title; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <img src="<?php echo $imagen_url; ?>" 
                                         alt="<?php echo $imagen_alt; ?>" 
                                         class="galeria-item-img">
                                    
                                    <?php if ($tiene_enlace) : ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Paginación Desktop -->
            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-pagination d-flex justify-content-center">
                <div class="col-10 d-flex justify-content-center">
                    <?php foreach ($galeriasConContenido as $idx => $coleccion) : ?>
                        <span class="galeria-pagination-dot<?php echo $idx === 0 ? ' active' : ''; ?>" data-page="<?php echo $idx; ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== VERSIÓN TABLET (768px - 1023px) ===== -->
        <div class="galeria-grid-block-tablet" data-block-id-tablet="<?php echo $block_id; ?>">
            
            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-nav-wrapper">
                <div class="galeria-tabs-wrapper">
                    <div class="galeria-tabs">
                        <?php foreach ($galeriasConContenido as $idx => $coleccion) : 
                            $nombre = isset($coleccion['galeria_nombre']) ? esc_html($coleccion['galeria_nombre']) : 'Galería ' . ($idx + 1);
                            $separador = isset($coleccion['galeria_separador']) && is_array($coleccion['galeria_separador']) ? $coleccion['galeria_separador'] : null;
                            $separador_url = $separador && isset($separador['url']) ? esc_url($separador['url']) : null;
                        ?>
                            <button class="galeria-tab-btn-tablet<?php echo $idx === 0 ? ' active' : ''; ?>" 
                                    data-tab="<?php echo $idx; ?>">
                                <?php echo $nombre; ?>
                            </button>
                            <?php if ($separador_url && $idx < count($galeriasConContenido) - 1) : ?>
                                <div class="galeria-tab-separator">
                                    <img src="<?php echo $separador_url; ?>" alt="Separador" class="galeria-separator-img">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="galeria-arrows">
                        <button class="galeria-arrow galeria-arrow-prev-tablet" data-direction="prev">
                            <?php if ($flecha_izq_url) : ?>
                                <img src="<?php echo $flecha_izq_url; ?>" alt="Anterior" class="galeria-arrow-img">
                            <?php else : ?>
                                ←
                            <?php endif; ?>
                        </button>
                        <button class="galeria-arrow galeria-arrow-next-tablet" data-direction="next">
                            <?php if ($flecha_der_url) : ?>
                                <img src="<?php echo $flecha_der_url; ?>" alt="Siguiente" class="galeria-arrow-img">
                            <?php else : ?>
                                →
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Galerías Tablet -->
            <?php foreach ($galeriasConContenido as $tab_idx => $coleccion) :
                $imagenes = isset($coleccion['galeria_grid_imagenes']) ? $coleccion['galeria_grid_imagenes'] : array();
                
                $pages = [];
                $currentPage = [];
                $idx = 0;
                
                while ($idx < count($imagenes)) {
                    $item = $imagenes[$idx];
                    $altura = isset($item['galeria_altura']) ? intval($item['galeria_altura']) : 1;
                    
                    if ($altura === 2) {
                        if (count($currentPage) === 1) {
                            for ($j = $idx + 1; $j < count($imagenes); $j++) {
                                $nextAltura = isset($imagenes[$j]['galeria_altura']) ? intval($imagenes[$j]['galeria_altura']) : 1;
                                if ($nextAltura === 1) {
                                    $currentPage[] = $imagenes[$j];
                                    $idx = $j;
                                    break;
                                }
                            }
                        }
                        
                        if (!empty($currentPage)) {
                            $pages[] = $currentPage;
                            $currentPage = [];
                        }
                        
                        $pages[] = [$item];
                    } else {
                        $currentPage[] = $item;
                        
                        if (count($currentPage) === 2) {
                            $pages[] = $currentPage;
                            $currentPage = [];
                        }
                    }
                    
                    $idx++;
                }
                
                if (!empty($currentPage)) {
                    $pages[] = $currentPage;
                }
            ?>
                <div class="galeria-grid-container-tablet<?php echo $tab_idx === 0 ? ' active' : ''; ?>" 
                     data-galeria-tablet="<?php echo $tab_idx; ?>"
                     style="<?php echo $tab_idx === 0 ? '' : 'display: none;'; ?>"
                     data-total-pages="<?php echo count($pages); ?>">
                    
                    <?php foreach ($pages as $page_idx => $page_items) : ?>
                    <div class="galeria-page-tablet" data-page="<?php echo $page_idx; ?>" style="<?php echo $page_idx === 0 ? '' : 'display: none;'; ?>">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2px; padding: 0 20px;">
                            <?php foreach ($page_items as $item) :
                                $imagen = isset($item['galeria_imagen']) && is_array($item['galeria_imagen']) ? $item['galeria_imagen'] : null;
                                $altura = isset($item['galeria_altura']) ? intval($item['galeria_altura']) : 1;
                                $enlace = isset($item['galeria_enlace']) && is_array($item['galeria_enlace']) ? $item['galeria_enlace'] : null;
                                $titulo_hover = isset($item['galeria_titulo_hover']) && !empty($item['galeria_titulo_hover']) ? esc_html($item['galeria_titulo_hover']) : '';
                                
                                if (!$imagen || empty($imagen['url'])) {
                                    continue;
                                }
                                
                                $imagen_url = esc_url($imagen['url']);
                                $imagen_alt = isset($imagen['alt']) ? esc_attr($imagen['alt']) : '';
                                $item_height = $altura === 2 ? '500px' : '250px';
                                $grid_row = $altura === 2 ? 'span 2' : 'unset';
                                
                                $tiene_enlace = $enlace && !empty($enlace['url']);
                                $enlace_url = $tiene_enlace ? esc_url($enlace['url']) : '';
                                $enlace_target = ($tiene_enlace && isset($enlace['target']) && $enlace['target']) ? esc_attr($enlace['target']) : '_self';
                                $enlace_title = ($tiene_enlace && isset($enlace['title']) && $enlace['title']) ? esc_attr($enlace['title']) : '';
                                
                                $mostrar_overlay = $tiene_enlace && ($titulo_hover || $enlace_title);
                                $clase_overlay = $mostrar_overlay ? ' galeria-item-has-overlay' : '';
                            ?>
                                <div class="<?php echo $clase_overlay; ?>" style="width: 100%; height: <?php echo $item_height; ?>; overflow: hidden; background: #f5f5f5; grid-row: <?php echo $grid_row; ?>; position: relative;">
                                    <?php if ($tiene_enlace) : ?>
                                        <a href="<?php echo $enlace_url; ?>" 
                                           target="<?php echo $enlace_target; ?>"
                                           class="galeria-item-link"
                                           style="display: block; width: 100%; height: 100%;">
                                            <?php if ($mostrar_overlay) : ?>
                                            <div class="galeria-hover-overlay">
                                                <div class="galeria-hover-content">
                                                    <?php if ($titulo_hover) : ?>
                                                        <span class="galeria-hover-title"><?php echo $titulo_hover; ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($enlace_title) : ?>
                                                        <span class="galeria-hover-link-text"><?php echo $enlace_title; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <img src="<?php echo $imagen_url; ?>" 
                                         alt="<?php echo $imagen_alt; ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    
                                    <?php if ($tiene_enlace) : ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                </div>
            <?php endforeach; ?>

            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-pagination-tablet"></div>
            <?php endif; ?>
        </div>

        <!-- ===== VERSIÓN MOBILE (<768px) ===== -->
        <div class="galeria-grid-block-mobile" data-block-id-mobile="<?php echo $block_id; ?>">
            
            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-nav-wrapper">
                <div class="galeria-tabs-wrapper-mobile">
                    <div class="galeria-tabs">
                        <?php foreach ($galeriasConContenido as $idx => $coleccion) : 
                            $nombre = isset($coleccion['galeria_nombre']) ? esc_html($coleccion['galeria_nombre']) : 'Galería ' . ($idx + 1);
                            $separador = isset($coleccion['galeria_separador']) && is_array($coleccion['galeria_separador']) ? $coleccion['galeria_separador'] : null;
                            $separador_url = $separador && isset($separador['url']) ? esc_url($separador['url']) : null;
                        ?>
                            <button class="galeria-tab-btn-mobile<?php echo $idx === 0 ? ' active' : ''; ?>" 
                                    data-tab="<?php echo $idx; ?>">
                                <?php echo $nombre; ?>
                            </button>
                            <?php if ($separador_url && $idx < count($galeriasConContenido) - 1) : ?>
                                <div class="galeria-tab-separator">
                                    <img src="<?php echo $separador_url; ?>" alt="Separador" class="galeria-separator-img">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Galerías Mobile -->
            <?php foreach ($galeriasConContenido as $tab_idx => $coleccion) :
                $imagenes = isset($coleccion['galeria_grid_imagenes']) ? $coleccion['galeria_grid_imagenes'] : array();
                
                $pages = [];
                $currentPage = [];
                $idx = 0;
                
                while ($idx < count($imagenes)) {
                    $item = $imagenes[$idx];
                    $altura = isset($item['galeria_altura']) ? intval($item['galeria_altura']) : 1;
                    
                    if ($altura === 2) {
                        if (count($currentPage) === 1) {
                            for ($j = $idx + 1; $j < count($imagenes); $j++) {
                                $nextAltura = isset($imagenes[$j]['galeria_altura']) ? intval($imagenes[$j]['galeria_altura']) : 1;
                                if ($nextAltura === 1) {
                                    $currentPage[] = $imagenes[$j];
                                    $idx = $j;
                                    break;
                                }
                            }
                        }
                        
                        if (!empty($currentPage)) {
                            $pages[] = $currentPage;
                            $currentPage = [];
                        }
                        
                        $pages[] = [$item];
                    } else {
                        $currentPage[] = $item;
                        
                        if (count($currentPage) === 2) {
                            $pages[] = $currentPage;
                            $currentPage = [];
                        }
                    }
                    
                    $idx++;
                }
                
                if (!empty($currentPage)) {
                    $pages[] = $currentPage;
                }
            ?>
                <div class="galeria-grid-container-mobile<?php echo $tab_idx === 0 ? ' active' : ''; ?>" 
                     data-galeria-mobile="<?php echo $tab_idx; ?>"
                     style="<?php echo $tab_idx === 0 ? '' : 'display: none;'; ?>"
                     data-total-pages="<?php echo count($pages); ?>">
                    
                    <?php foreach ($pages as $page_idx => $page_items) : ?>
                    <div class="galeria-page-mobile" data-page="<?php echo $page_idx; ?>" style="<?php echo $page_idx === 0 ? '' : 'display: none;'; ?>">
                        <div style="height: 400px; display: flex; flex-direction: column; gap: 5px; padding: 0px;">
                            <?php foreach ($page_items as $item) :
                                $imagen = isset($item['galeria_imagen']) && is_array($item['galeria_imagen']) ? $item['galeria_imagen'] : null;
                                $altura = isset($item['galeria_altura']) ? intval($item['galeria_altura']) : 1;
                                $enlace = isset($item['galeria_enlace']) && is_array($item['galeria_enlace']) ? $item['galeria_enlace'] : null;
                                $titulo_hover = isset($item['galeria_titulo_hover']) && !empty($item['galeria_titulo_hover']) ? esc_html($item['galeria_titulo_hover']) : '';
                                
                                if (!$imagen || empty($imagen['url'])) {
                                    continue;
                                }
                                
                                $imagen_url = esc_url($imagen['url']);
                                $imagen_alt = isset($imagen['alt']) ? esc_attr($imagen['alt']) : '';
                                $item_height = $altura === 2 ? '398px' : '199px';
                                
                                $tiene_enlace = $enlace && !empty($enlace['url']);
                                $enlace_url = $tiene_enlace ? esc_url($enlace['url']) : '';
                                $enlace_target = ($tiene_enlace && isset($enlace['target']) && $enlace['target']) ? esc_attr($enlace['target']) : '_self';
                                $enlace_title = ($tiene_enlace && isset($enlace['title']) && $enlace['title']) ? esc_attr($enlace['title']) : '';
                                
                                $mostrar_overlay = $tiene_enlace && ($titulo_hover || $enlace_title);
                                $clase_overlay = $mostrar_overlay ? ' galeria-item-has-overlay' : '';
                            ?>
                                <div class="<?php echo $clase_overlay; ?>" style="width: 100%; height: <?php echo $item_height; ?>; overflow: hidden; background: #f5f5f5; flex-shrink: 0; position: relative;">
                                    <?php if ($tiene_enlace) : ?>
                                        <a href="<?php echo $enlace_url; ?>" 
                                           target="<?php echo $enlace_target; ?>"
                                           class="galeria-item-link"
                                           style="display: block; width: 100%; height: 100%;">
                                            <?php if ($mostrar_overlay) : ?>
                                            <div class="galeria-hover-overlay">
                                                <div class="galeria-hover-content">
                                                    <?php if ($titulo_hover) : ?>
                                                        <span class="galeria-hover-title"><?php echo $titulo_hover; ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($enlace_title) : ?>
                                                        <span class="galeria-hover-link-text"><?php echo $enlace_title; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <img src="<?php echo $imagen_url; ?>" 
                                         alt="<?php echo $imagen_alt; ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    
                                    <?php if ($tiene_enlace) : ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                </div>
            <?php endforeach; ?>

            <div class="galeria-arrows-wrapper-mobile">
                <button class="galeria-arrow galeria-arrow-prev-mobile" data-direction="prev">
                    <?php if ($flecha_izq_url) : ?>
                        <img src="<?php echo $flecha_izq_url; ?>" alt="Anterior" class="galeria-arrow-img">
                    <?php else : ?>
                        ←
                    <?php endif; ?>
                </button>
                <button class="galeria-arrow galeria-arrow-next-mobile" data-direction="next">
                    <?php if ($flecha_der_url) : ?>
                        <img src="<?php echo $flecha_der_url; ?>" alt="Siguiente" class="galeria-arrow-img">
                    <?php else : ?>
                        →
                    <?php endif; ?>
                </button>
            </div>

            <?php if (count($galeriasConContenido) > 1) : ?>
            <div class="galeria-pagination-mobile"></div>
            <?php endif; ?>
        </div>

        <!-- Scripts Desktop -->
        <script>
        (function() {
            const wrapper = document.querySelector('[data-block-id="<?php echo $block_id; ?>"]');
            if (!wrapper) return;
            
            const tabs = wrapper.querySelectorAll('.galeria-tab-btn');
            const containers = wrapper.querySelectorAll('.galeria-grid-container');
            const prevBtn = wrapper.querySelector('.galeria-arrow-prev');
            const nextBtn = wrapper.querySelector('.galeria-arrow-next');
            const paginationDots = wrapper.querySelectorAll('.galeria-pagination-dot');
            
            let currentTab = 0;

            function activateTab(tabIndex) {
                if (tabIndex < 0) tabIndex = tabs.length - 1;
                if (tabIndex >= tabs.length) tabIndex = 0;
                
                currentTab = tabIndex;
                
                tabs.forEach(t => t.classList.remove('active'));
                paginationDots.forEach(d => d.classList.remove('active'));
                
                containers.forEach(c => {
                    if (c !== containers[tabIndex]) {
                        c.style.display = 'none';
                    }
                });
                
                const activeContainer = containers[tabIndex];
                activeContainer.style.display = 'block';
                activeContainer.offsetHeight;
                
                tabs[tabIndex].classList.add('active');
                paginationDots[tabIndex].classList.add('active');
            }

            tabs.forEach((tab, idx) => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(idx);
                });
            });

            paginationDots.forEach((dot, idx) => {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(idx);
                });
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(currentTab - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(currentTab + 1);
                });
            }
        })();
        </script>

        <!-- Scripts Tablet -->
        <script>
        (function() {
            const wrapperTablet = document.querySelector('[data-block-id-tablet="<?php echo $block_id; ?>"]');
            if (!wrapperTablet) return;

            const tabsTablet = wrapperTablet.querySelectorAll('.galeria-tab-btn-tablet');
            const containersTablet = wrapperTablet.querySelectorAll('.galeria-grid-container-tablet');
            const prevBtnTablet = wrapperTablet.querySelector('.galeria-arrow-prev-tablet');
            const nextBtnTablet = wrapperTablet.querySelector('.galeria-arrow-next-tablet');
            const paginationContainerTablet = wrapperTablet.querySelector('.galeria-pagination-tablet');

            let currentTabTablet = 0;
            let currentPageTablet = {};

            containersTablet.forEach((_, idx) => {
                currentPageTablet[idx] = 0;
            });

            function updateArrowVisibility() {
                const container = wrapperTablet.querySelector('[data-galeria-tablet="' + currentTabTablet + '"]');
                if (!container) return;
                const totalPages = parseInt(container.dataset.totalPages) || 1;
                const currentPage = currentPageTablet[currentTabTablet];
                if (prevBtnTablet) prevBtnTablet.style.display = currentPage === 0 ? 'none' : 'flex';
                if (nextBtnTablet) nextBtnTablet.style.display = currentPage === totalPages - 1 ? 'none' : 'flex';
            }

            function updatePaginationDots(tabIndex) {
                if (!paginationContainerTablet) return;
                const container = wrapperTablet.querySelector('[data-galeria-tablet="' + tabIndex + '"]');
                if (!container) return;
                const totalPages = parseInt(container.dataset.totalPages) || 1;
                const currentPage = currentPageTablet[tabIndex];
                paginationContainerTablet.innerHTML = '';
                for (let p = 0; p < totalPages; p++) {
                    const dot = document.createElement('span');
                    dot.className = 'galeria-pagination-dot-tablet' + (p === currentPage ? ' active' : '');
                    dot.dataset.tab = tabIndex;
                    dot.dataset.page = p;
                    dot.addEventListener('click', function(e) {
                        e.preventDefault();
                        activatePageTablet(tabIndex, p);
                    });
                    paginationContainerTablet.appendChild(dot);
                }
                updateArrowVisibility();
            }

            function activatePageTablet(tabIndex, pageNum) {
                const container = wrapperTablet.querySelector('[data-galeria-tablet="' + tabIndex + '"]');
                if (!container) return;
                const totalPages = parseInt(container.dataset.totalPages) || 1;
                if (pageNum < 0) pageNum = totalPages - 1;
                if (pageNum >= totalPages) pageNum = 0;
                currentPageTablet[tabIndex] = pageNum;
                const pages = container.querySelectorAll('.galeria-page-tablet');
                pages.forEach((page, idx) => {
                    page.style.display = idx === pageNum ? 'block' : 'none';
                });
                updatePaginationDots(tabIndex);
            }

            function activateTabTablet(tabIndex) {
                if (tabIndex < 0) tabIndex = tabsTablet.length - 1;
                if (tabIndex >= tabsTablet.length) tabIndex = 0;
                currentTabTablet = tabIndex;
                tabsTablet.forEach(t => t.classList.remove('active'));
                containersTablet.forEach(c => {
                    if (c !== containersTablet[tabIndex]) c.style.display = 'none';
                });
                tabsTablet[tabIndex].classList.add('active');
                const activeContainer = containersTablet[tabIndex];
                activeContainer.style.display = 'block';
                activeContainer.offsetHeight;
                activatePageTablet(tabIndex, 0);
            }

            tabsTablet.forEach((tab, idx) => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTabTablet(idx);
                });
            });

            if (prevBtnTablet) {
                prevBtnTablet.addEventListener('click', function(e) {
                    e.preventDefault();
                    activatePageTablet(currentTabTablet, currentPageTablet[currentTabTablet] - 1);
                });
            }

            if (nextBtnTablet) {
                nextBtnTablet.addEventListener('click', function(e) {
                    e.preventDefault();
                    activatePageTablet(currentTabTablet, currentPageTablet[currentTabTablet] + 1);
                });
            }

            activateTabTablet(0);
        })();
        </script>

        <!-- Scripts Mobile -->
        <script>
        (function() {
            const wrapperMobile = document.querySelector('[data-block-id-mobile="<?php echo $block_id; ?>"]');
            if (!wrapperMobile) return;

            const tabsMobile = wrapperMobile.querySelectorAll('.galeria-tab-btn-mobile');
            const containersMobile = wrapperMobile.querySelectorAll('.galeria-grid-container-mobile');
            const prevBtnMobile = wrapperMobile.querySelector('.galeria-arrow-prev-mobile');
            const nextBtnMobile = wrapperMobile.querySelector('.galeria-arrow-next-mobile');
            const paginationContainerMobile = wrapperMobile.querySelector('.galeria-pagination-mobile');

            let currentTabMobile = 0;
            let currentPageMobile = {};

            containersMobile.forEach((_, idx) => {
                currentPageMobile[idx] = 0;
            });

            function updatePaginationDots(tabIndex) {
                if (!paginationContainerMobile) return;
                const container = wrapperMobile.querySelector('[data-galeria-mobile="' + tabIndex + '"]');
                if (!container) return;
                const totalPages = parseInt(container.dataset.totalPages) || 1;
                const currentPage = currentPageMobile[tabIndex];
                paginationContainerMobile.innerHTML = '';
                for (let p = 0; p < totalPages; p++) {
                    const dot = document.createElement('span');
                    dot.className = 'galeria-pagination-dot-mobile' + (p === currentPage ? ' active' : '');
                    dot.dataset.tab = tabIndex;
                    dot.dataset.page = p;
                    dot.addEventListener('click', function(e) {
                        e.preventDefault();
                        activatePageMobile(tabIndex, p);
                    });
                    paginationContainerMobile.appendChild(dot);
                }
            }

            function activatePageMobile(tabIndex, pageNum) {
                const container = wrapperMobile.querySelector('[data-galeria-mobile="' + tabIndex + '"]');
                if (!container) return;
                const totalPages = parseInt(container.dataset.totalPages) || 1;
                if (pageNum < 0) pageNum = totalPages - 1;
                if (pageNum >= totalPages) pageNum = 0;
                currentPageMobile[tabIndex] = pageNum;
                const pages = container.querySelectorAll('.galeria-page-mobile');
                pages.forEach((page, idx) => {
                    page.style.display = idx === pageNum ? 'block' : 'none';
                });
                updatePaginationDots(tabIndex);
            }

            function activateTabMobile(tabIndex) {
                if (tabIndex < 0) tabIndex = tabsMobile.length - 1;
                if (tabIndex >= tabsMobile.length) tabIndex = 0;
                currentTabMobile = tabIndex;
                tabsMobile.forEach(t => t.classList.remove('active'));
                containersMobile.forEach(c => {
                    if (c !== containersMobile[tabIndex]) c.style.display = 'none';
                });
                tabsMobile[tabIndex].classList.add('active');
                const activeContainer = containersMobile[tabIndex];
                activeContainer.style.display = 'block';
                activeContainer.offsetHeight;
                activatePageMobile(tabIndex, 0);
            }

            tabsMobile.forEach((tab, idx) => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTabMobile(idx);
                });
            });

            if (prevBtnMobile) {
                prevBtnMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    activatePageMobile(currentTabMobile, currentPageMobile[currentTabMobile] - 1);
                });
            }

            if (nextBtnMobile) {
                nextBtnMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    activatePageMobile(currentTabMobile, currentPageMobile[currentTabMobile] + 1);
                });
            }

            activateTabMobile(0);
        })();
        </script>
        <?php
    }
}
?>
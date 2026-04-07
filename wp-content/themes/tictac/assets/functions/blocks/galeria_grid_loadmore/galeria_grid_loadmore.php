<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_galeria_grid_loadmore',
        'title' => 'Galería Grid Loadmore',
        'fields' => array(
            array(
                'key' => 'field_galeria_lm_titulo_alturas',
                'label' => '📐 CONFIGURACIÓN DE ALTURAS',
                'name' => 'titulo_alturas',
                'type' => 'message',
                'message' => 'Define la altura estándar de las imágenes en cada dispositivo. La altura "2x" será automáticamente el doble.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_galeria_lm_altura_desktop',
                'label' => 'Altura Estándar - Desktop',
                'name' => 'galeria_lm_altura_desktop',
                'type' => 'number',
                'required' => 0,
                'default_value' => '200',
                'min' => '100',
                'max' => '400',
                'step' => '10',
                'instructions' => 'Altura en píxeles para pantallas >1024px. La altura 2x será el doble.',
                'placeholder' => '200',
            ),
            array(
                'key' => 'field_galeria_lm_altura_tablet',
                'label' => 'Altura Estándar - Tablet',
                'name' => 'galeria_lm_altura_tablet',
                'type' => 'number',
                'required' => 0,
                'default_value' => '160',
                'min' => '80',
                'max' => '300',
                'step' => '10',
                'instructions' => 'Altura en píxeles para pantallas 768px - 1023px. La altura 2x será el doble.',
                'placeholder' => '160',
            ),
            array(
                'key' => 'field_galeria_lm_altura_mobile',
                'label' => 'Altura Estándar - Mobile',
                'name' => 'galeria_lm_altura_mobile',
                'type' => 'number',
                'required' => 0,
                'default_value' => '140',
                'min' => '80',
                'max' => '250',
                'step' => '10',
                'instructions' => 'Altura en píxeles para pantallas <768px. La altura 2x será el doble.',
                'placeholder' => '140',
            ),
            array(
                'key' => 'field_galeria_lm_titulo_general',
                'label' => '⚙️ CONFIGURACIÓN GENERAL',
                'name' => 'titulo_general',
                'type' => 'message',
                'message' => 'Configura el comportamiento de la galería.',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_galeria_lm_ancho',
                'label' => 'Ancho del Grid - Desktop',
                'name' => 'galeria_lm_grid_ancho',
                'type' => 'radio',
                'required' => 0,
                'default_value' => '3',
                'choices' => array(
                    '2' => '2 columnas de ancho',
                    '3' => '3 columnas de ancho',
                ),
                'instructions' => 'Define si la galería tendrá 2 o 3 columnas en desktop',
            ),
            array(
                'key' => 'field_galeria_lm_paginacion_activa',
                'label' => '📑 Activar Paginación',
                'name' => 'galeria_lm_paginacion_activa',
                'type' => 'true_false',
                'required' => 0,
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Sí, activar "Cargar más"',
                'ui_off_text' => 'No, mostrar todas las imágenes',
                'instructions' => 'Si está desactivada, se mostrarán todas las imágenes sin botón de paginación',
            ),
            array(
                'key' => 'field_galeria_lm_imagenes_iniciales',
                'label' => 'Imágenes iniciales a mostrar',
                'name' => 'galeria_lm_imagenes_iniciales',
                'type' => 'number',
                'required' => 0,
                'default_value' => 6,
                'min' => 1,
                'max' => 8,
                'step' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_galeria_lm_paginacion_activa',
                            'operator' => '==',
                            'value' => 1,
                        ),
                    ),
                ),
                'instructions' => 'Cuántas imágenes mostrar al cargar la página (máximo 8)',
            ),
            array(
                'key' => 'field_galeria_lm_imagenes_carga',
                'label' => 'Imágenes por carga',
                'name' => 'galeria_lm_imagenes_carga',
                'type' => 'number',
                'required' => 0,
                'default_value' => 3,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_galeria_lm_paginacion_activa',
                            'operator' => '==',
                            'value' => 1,
                        ),
                    ),
                ),
                'instructions' => 'Cuántas imágenes cargar con cada click en "Cargar más"',
            ),
            array(
                'key' => 'field_galeria_lm_velocidad_animacion',
                'label' => 'Velocidad de animación (ms)',
                'name' => 'galeria_lm_velocidad_animacion',
                'type' => 'number',
                'required' => 0,
                'default_value' => '400',
                'min' => '200',
                'max' => '1000',
                'step' => '50',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_galeria_lm_paginacion_activa',
                            'operator' => '==',
                            'value' => 1,
                        ),
                    ),
                ),
                'instructions' => 'Duración de la animación de persiana en milisegundos',
            ),
            array(
                'key' => 'field_galeria_lm_texto_boton',
                'label' => 'Texto del botón',
                'name' => 'galeria_lm_texto_boton',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'Cargar más',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_galeria_lm_paginacion_activa',
                            'operator' => '==',
                            'value' => 1,
                        ),
                    ),
                ),
                'instructions' => 'Texto que aparecerá en el botón',
            ),
            array(
                'key' => 'field_galeria_lm_titulo_imagenes',
                'label' => '🎨 ITEMS DE LA GALERÍA',
                'name' => 'titulo_imagenes',
                'type' => 'message',
                'message' => 'Agrega y organiza los items de la galería. Puedes añadir imágenes o videos. Usa la altura estándar (1x) o más alta (2x).',
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_galeria_lm_imagenes',
                'label' => 'Items de la Galería',
                'name' => 'galeria_lm_imagenes',
                'type' => 'repeater',
                'min' => 1,
                'layout' => 'block',
                'button_label' => '+ Añadir Item',
                'instructions' => 'Agrega los items en el orden que deseas.',
                'sub_fields' => array(
                    array(
                        'key' => 'field_galeria_lm_tipo_contenido',
                        'label' => 'Tipo de Contenido',
                        'name' => 'galeria_lm_tipo_contenido',
                        'type' => 'radio',
                        'required' => 1,
                        'default_value' => 'imagen',
                        'choices' => array(
                            'imagen' => '🖼️ Imagen',
                            'video' => '🎥 Video',
                        ),
                        'layout' => 'horizontal',
                        'instructions' => 'Elige si quieres mostrar una imagen o un video',
                    ),
                    array(
                        'key' => 'field_galeria_lm_imagen',
                        'label' => 'Imagen',
                        'name' => 'galeria_lm_imagen',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'imagen',
                                ),
                            ),
                        ),
                        'instructions' => 'Selecciona la imagen',
                    ),
                    array(
                        'key' => 'field_galeria_lm_imagen_accion',
                        'label' => 'Acción al hacer clic',
                        'name' => 'galeria_lm_imagen_accion',
                        'type' => 'radio',
                        'required' => 0,
                        'default_value' => 'ninguna',
                        'choices' => array(
                            'ninguna' => '➖ Ninguna (comportamiento normal)',
                            'lightbox' => '🔍 Ampliar imagen (Lightbox)',
                        ),
                        'layout' => 'horizontal',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'imagen',
                                ),
                            ),
                        ),
                        'instructions' => 'Define qué sucede al hacer clic en la imagen',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_tipo',
                        'label' => 'Tipo de Video',
                        'name' => 'galeria_lm_video_tipo',
                        'type' => 'radio',
                        'required' => 0,
                        'default_value' => 'externo',
                        'choices' => array(
                            'externo' => '🔗 URL Externa (YouTube, Vimeo)',
                            'mp4' => '📁 Archivo MP4 (Subir)',
                        ),
                        'layout' => 'horizontal',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                            ),
                        ),
                        'instructions' => 'Elige la fuente del video',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_url',
                        'label' => 'URL del Video',
                        'name' => 'galeria_lm_video_url',
                        'type' => 'url',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_video_tipo',
                                    'operator' => '==',
                                    'value' => 'externo',
                                ),
                            ),
                        ),
                        'instructions' => 'URL de YouTube o Vimeo (ej: https://youtube.com/watch?v=...)',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_mp4',
                        'label' => 'Archivo MP4',
                        'name' => 'galeria_lm_video_mp4',
                        'type' => 'file',
                        'required' => 0,
                        'return_format' => 'array',
                        'mime_types' => 'mp4,webm,ogg',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_video_tipo',
                                    'operator' => '==',
                                    'value' => 'mp4',
                                ),
                            ),
                        ),
                        'instructions' => 'Sube un archivo de video MP4, WebM u OGG',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_autoplay',
                        'label' => 'Reproducir automáticamente',
                        'name' => 'galeria_lm_video_autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_video_tipo',
                                    'operator' => '==',
                                    'value' => 'mp4',
                                ),
                            ),
                        ),
                        'instructions' => 'El video se reproducirá automáticamente (sin sonido)',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_loop',
                        'label' => 'Reproducir en bucle',
                        'name' => 'galeria_lm_video_loop',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_video_tipo',
                                    'operator' => '==',
                                    'value' => 'mp4',
                                ),
                            ),
                        ),
                        'instructions' => 'El video se repetirá continuamente',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_controls',
                        'label' => 'Mostrar controles',
                        'name' => 'galeria_lm_video_controls',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_video_tipo',
                                    'operator' => '==',
                                    'value' => 'mp4',
                                ),
                            ),
                        ),
                        'instructions' => 'Muestra los controles de reproducción',
                    ),
                    array(
                        'key' => 'field_galeria_lm_video_poster',
                        'label' => 'Imagen de Portada (Video)',
                        'name' => 'galeria_lm_video_poster',
                        'type' => 'image',
                        'required' => 0,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                            ),
                        ),
                        'instructions' => 'Imagen que se muestra como portada del video (recomendado)',
                    ),
                    array(
                        'key' => 'field_galeria_lm_altura',
                        'label' => 'Altura',
                        'name' => 'galeria_lm_altura',
                        'type' => 'radio',
                        'required' => 0,
                        'default_value' => '1',
                        'choices' => array(
                            '1' => 'Altura estándar (1x)',
                            '2' => 'Altura extra (2x)',
                        ),
                        'layout' => 'horizontal',
                        'instructions' => 'Define si el item ocupa 1 o 2 cuadrados de alto',
                    ),
                    array(
                        'key' => 'field_galeria_lm_enlace',
                        'label' => 'Enlace',
                        'name' => 'galeria_lm_enlace',
                        'type' => 'link',
                        'required' => 0,
                        'return_format' => 'array',
                        'instructions' => 'Opcional: Enlaza este item a una página. Solo funciona si la acción es "Ninguna".',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'imagen',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_imagen_accion',
                                    'operator' => '==',
                                    'value' => 'ninguna',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_galeria_lm_enlace_titulo',
                        'label' => 'Título del Enlace',
                        'name' => 'galeria_lm_enlace_titulo',
                        'type' => 'text',
                        'required' => 0,
                        'instructions' => 'Título que aparecerá sobre la imagen al hacer hover (opcional)',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_galeria_lm_tipo_contenido',
                                    'operator' => '==',
                                    'value' => 'imagen',
                                ),
                                array(
                                    'field' => 'field_galeria_lm_imagen_accion',
                                    'operator' => '==',
                                    'value' => 'ninguna',
                                ),
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
                    'value' => 'acf/galeria-grid-loadmore',
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
        'description' => 'Galería grid con botón cargar más y alturas configurables',
        'show_in_rest' => 0,
    ));

endif;

if (!function_exists('tictac_galeria_grid_loadmore_acf')) {
    function tictac_galeria_grid_loadmore_acf()
    {
        acf_register_block_type([
            'name'        => 'galeria_grid_loadmore',
            'title'        => __('Galería Grid Loadmore', 'tictac'),
            'description'    => __('Galería con grid responsive y botón cargar más', 'tictac'),
            'render_callback'  => 'tictac_galeria_grid_loadmore_render',
            'mode'        => 'preview',
            'supports'    => array(
                'mode' => true,
                'align' => false,
            ),
            'icon'        => 'format-gallery',
            'keywords'      => ['galeria', 'grid', 'imagenes', 'loadmore', 'video'],
        ]);
    }
    add_action('acf/init', 'tictac_galeria_grid_loadmore_acf');
}

if (!function_exists('tictac_galeria_grid_loadmore_scripts')) {
    function tictac_galeria_grid_loadmore_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_style('tictac-galeria-grid-loadmore', get_stylesheet_directory_uri() . '/assets/functions/blocks/galeria_grid_loadmore/galeria_grid_loadmore.min.css', array(), '1.1');
        } else {
            wp_enqueue_style('tictac-galeria-grid-loadmore-admin', get_stylesheet_directory_uri() . '/assets/functions/blocks/galeria_grid_loadmore/galeria_grid_loadmore_admin.min.css', array(), '1.0');
            wp_add_inline_script('acf-input', tictac_galeria_grid_loadmore_simulador_js());
        }
    }
    add_action('wp_enqueue_scripts', 'tictac_galeria_grid_loadmore_scripts');
    add_action('admin_enqueue_scripts', 'tictac_galeria_grid_loadmore_scripts');
}

if (!function_exists('tictac_galeria_grid_loadmore_simulador_js')) {
    function tictac_galeria_grid_loadmore_simulador_js()
    {
        return <<<'JS'
(function() {
    'use strict';
    class GaleriaLoadmoreSimulador {
        constructor(repeater) {
            this.repeater = repeater;
            this.observer = null;
            this.simulator = null;
            this.heightsData = null;
            this.init();
        }
        init() {
            this.createSimulator();
            this.updateSimulator();
            this.observeChanges();
        }
        getHeightsData() {
            const desktopField = document.querySelector('[data-name="galeria_lm_altura_desktop"] input');
            const tabletField = document.querySelector('[data-name="galeria_lm_altura_tablet"] input');
            const mobileField = document.querySelector('[data-name="galeria_lm_altura_mobile"] input');
            return {
                desktop: desktopField ? parseInt(desktopField.value) || 200 : 200,
                tablet: tabletField ? parseInt(tabletField.value) || 160 : 160,
                mobile: mobileField ? parseInt(mobileField.value) || 140 : 140,
            };
        }
        createSimulator() {
            const simId = 'galeria_lm_simulator';
            if (document.getElementById(simId)) return;
            const html = `
                <div id="${simId}" class="galeria_lm_simulator">
                    <div class="galeria_lm_simulator_header">
                        <div class="galeria_lm_simulator_title">📐 VISTA PREVIA - SIMULADOR EN VIVO</div>
                        <div class="galeria_lm_simulator_controls">
                            <label><span>Vista:</span>
                                <select class="galeria_lm_view_selector">
                                    <option value="mobile">📱 Mobile</option>
                                    <option value="tablet">📱 Tablet</option>
                                    <option value="desktop" selected>💻 Desktop</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="galeria_lm_heights_info">
                        <strong>Alturas configuradas:</strong> 
                        <span id="heights_desktop_info">Desktop: --px</span> | 
                        <span id="heights_tablet_info">Tablet: --px</span> | 
                        <span id="heights_mobile_info">Mobile: --px</span>
                    </div>
                    <div id="galeria_lm_simulator_grid" class="galeria_lm_simulator_grid"></div>
                    <div class="galeria_lm_simulator_help"><p><strong>⚙️ Config:</strong> <span id="config_lm_items">0 items</span></p></div>
                </div>`;
            const container = this.repeater.closest('.acf-field-object-repeater') || this.repeater.parentElement;
            if (!container || !container.parentElement) return;
            container.parentElement.insertAdjacentHTML('afterend', html);
            const selector = document.querySelector('.galeria_lm_view_selector');
            if (selector) selector.addEventListener('change', (e) => this.updateSimulator(e.target.value));
            this.simulator = document.getElementById('galeria_lm_simulator_grid');
        }
        observeChanges() {
            this.observer = new MutationObserver(() => this.updateSimulator());
            this.observer.observe(this.repeater, { childList: true, subtree: true, attributes: true });
            const heightFields = document.querySelectorAll('[data-name="galeria_lm_altura_desktop"] input, [data-name="galeria_lm_altura_tablet"] input, [data-name="galeria_lm_altura_mobile"] input');
            heightFields.forEach(field => {
                field.addEventListener('change', () => this.updateSimulator());
                field.addEventListener('input', () => this.updateSimulator());
            });
        }
        updateSimulator(viewType = null) {
            if (!this.simulator) return;
            if (!viewType) {
                const selector = document.querySelector('.galeria_lm_view_selector');
                viewType = selector ? selector.value : 'desktop';
            }
            const heights = this.getHeightsData();
            this.heightsData = heights;
            document.getElementById('heights_desktop_info').textContent = `Desktop: ${heights.desktop}px (2x: ${heights.desktop * 2}px)`;
            document.getElementById('heights_tablet_info').textContent = `Tablet: ${heights.tablet}px (2x: ${heights.tablet * 2}px)`;
            document.getElementById('heights_mobile_info').textContent = `Mobile: ${heights.mobile}px (2x: ${heights.mobile * 2}px)`;
            const breakpoints = {
                desktop: { cols: 3, height: heights.desktop },
                tablet: { cols: 2, height: heights.tablet },
                mobile: { cols: 2, height: heights.mobile }
            };
            const breakpoint = breakpoints[viewType] || breakpoints.desktop;
            const items = this.getItems();
            this.simulator.style.gridTemplateColumns = `repeat(${breakpoint.cols}, 1fr)`;
            this.simulator.style.gridAutoRows = breakpoint.height + 'px';
            this.simulator.className = `galeria_lm_simulator_grid view_${viewType}`;
            this.simulator.innerHTML = '';
            if (items.length === 0) {
                this.simulator.innerHTML = '<div style="grid-column: 1 / -1; padding: 40px; text-align: center; color: #999;">👆 Añade items</div>';
                this.updateConfig(0);
                return;
            }
            items.forEach((item, idx) => {
                const itemEl = this.createSimulatorItem(item, idx, breakpoint.height);
                this.simulator.appendChild(itemEl);
            });
            this.updateConfig(items.length);
        }
        getItems() {
            const items = [];
            let rows = this.repeater.querySelectorAll('.acf-row');
            rows.forEach((row) => {
                const tipoWrapper = row.querySelector('[data-name="galeria_lm_tipo_contenido"]');
                const tipoChecked = tipoWrapper ? tipoWrapper.querySelector('input:checked') : null;
                const tipoContenido = tipoChecked ? tipoChecked.value : 'imagen';
                let itemData = { tipo: tipoContenido, url: null, altura: 1, enlace: null, videoTipo: null, accion: 'ninguna' };
                if (tipoContenido === 'imagen') {
                    const imgWrapper = row.querySelector('[data-name="galeria_lm_imagen"]');
                    if (!imgWrapper) return;
                    const img = imgWrapper.querySelector('img');
                    itemData.url = img ? img.src : null;
                    const accionWrapper = row.querySelector('[data-name="galeria_lm_imagen_accion"]');
                    const accionChecked = accionWrapper ? accionWrapper.querySelector('input:checked') : null;
                    itemData.accion = accionChecked ? accionChecked.value : 'ninguna';
                } else {
                    const videoTipoWrapper = row.querySelector('[data-name="galeria_lm_video_tipo"]');
                    const videoTipoChecked = videoTipoWrapper ? videoTipoWrapper.querySelector('input:checked') : null;
                    itemData.videoTipo = videoTipoChecked ? videoTipoChecked.value : 'externo';
                    if (itemData.videoTipo === 'externo') {
                        const urlInput = row.querySelector('[data-name="galeria_lm_video_url"] input');
                        itemData.url = urlInput ? urlInput.value : null;
                    } else {
                        const mp4Wrapper = row.querySelector('[data-name="galeria_lm_video_mp4"]');
                        const mp4Link = mp4Wrapper ? mp4Wrapper.querySelector('a.acf-file-label') : null;
                        itemData.url = mp4Link ? mp4Link.href : 'mp4_uploaded';
                    }
                    const posterWrapper = row.querySelector('[data-name="galeria_lm_video_poster"]');
                    const posterImg = posterWrapper ? posterWrapper.querySelector('img') : null;
                    if (posterImg) itemData.posterUrl = posterImg.src;
                }
                const alturaWrapper = row.querySelector('[data-name="galeria_lm_altura"]');
                const radioChecked = alturaWrapper ? alturaWrapper.querySelector('input:checked') : null;
                itemData.altura = radioChecked ? parseInt(radioChecked.value) : 1;
                const enlaceWrapper = row.querySelector('[data-name="galeria_lm_enlace"]');
                const enlaceInput = enlaceWrapper ? enlaceWrapper.querySelector('input[type="hidden"]') : null;
                if (enlaceInput && enlaceInput.value) {
                    try { itemData.enlace = JSON.parse(enlaceInput.value); } catch(e) { itemData.enlace = null; }
                }
                if (itemData.url || itemData.tipo === 'video') items.push(itemData);
            });
            return items;
        }
        createSimulatorItem(item, idx, baseHeight) {
            const div = document.createElement('div');
            div.className = 'galeria_lm_simulator_item';
            if (item.altura === 2) div.style.gridRow = 'span 2';
            const heightText = item.altura === 2 ? `${baseHeight * 2}px` : `${baseHeight}px`;
            const linkIcon = item.enlace && item.enlace.url ? ' 🔗' : '';
            const tipoIcon = item.tipo === 'video' ? '🎥' : '🖼️';
            const tipoText = item.tipo === 'video' ? (item.videoTipo === 'mp4' ? 'MP4' : 'URL') : 'Img';
            const accionIcon = item.accion === 'lightbox' ? ' 🔍' : '';
            if (item.tipo === 'video' && item.posterUrl) {
                div.innerHTML = `<img src="${item.posterUrl}" alt="Video ${idx + 1}" loading="lazy"><div class="item_info">${tipoIcon} ${tipoText} ${idx + 1}${item.altura === 2 ? ' (2x)' : ''} • ${heightText}</div>`;
            } else if (item.tipo === 'video') {
                div.innerHTML = `<div style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:#333;color:#fff;font-size:40px;">🎥</div><div class="item_info">${tipoIcon} ${tipoText} ${idx + 1}${item.altura === 2 ? ' (2x)' : ''} • ${heightText}</div>`;
            } else {
                div.innerHTML = `<img src="${item.url}" alt="Imagen ${idx + 1}" loading="lazy"><div class="item_info">${tipoIcon} ${idx + 1}${item.altura === 2 ? ' (2x)' : ''} • ${heightText}${linkIcon}${accionIcon}</div>`;
            }
            return div;
        }
        updateConfig(count) {
            const itemsEl = document.getElementById('config_lm_items');
            if (itemsEl) itemsEl.textContent = count + ' items';
        }
    }
    if (typeof acf !== 'undefined' && acf.addAction) {
        acf.addAction('render_field_group', () => {
            const repeater = document.querySelector('[data-name="galeria_lm_imagenes"] .acf-repeater');
            if (repeater && !document.getElementById('galeria_lm_simulator')) new GaleriaLoadmoreSimulador(repeater);
        });
    }
    setTimeout(() => {
        const repeater = document.querySelector('[data-name="galeria_lm_imagenes"] .acf-repeater');
        if (repeater && !document.getElementById('galeria_lm_simulator')) new GaleriaLoadmoreSimulador(repeater);
    }, 1500);
})();
JS;
    }
}

// Función helper para renderizar un item (imagen o video)
if (!function_exists('tictac_galeria_lm_render_item')) {
    function tictac_galeria_lm_render_item($item, $item_index, $imagenes_iniciales) {
        $tipo_contenido = isset($item['galeria_lm_tipo_contenido']) ? $item['galeria_lm_tipo_contenido'] : 'imagen';
        $altura = isset($item['galeria_lm_altura']) ? intval($item['galeria_lm_altura']) : 1;
        $altura_class = $altura === 2 ? 'galeria_lm_item_double' : '';
        $visible_class = $item_index < $imagenes_iniciales ? 'galeria_lm_item_visible' : 'galeria_lm_item_hidden';
        
        $imagen_accion = isset($item['galeria_lm_imagen_accion']) ? $item['galeria_lm_imagen_accion'] : 'ninguna';
        $es_lightbox = $tipo_contenido === 'imagen' && $imagen_accion === 'lightbox';
        
        $enlace = isset($item['galeria_lm_enlace']) && is_array($item['galeria_lm_enlace']) ? $item['galeria_lm_enlace'] : null;
        $tiene_enlace = $tipo_contenido === 'imagen' && $imagen_accion === 'ninguna' && $enlace && !empty($enlace['url']);
        $enlace_url = $tiene_enlace ? esc_url($enlace['url']) : '';
        $enlace_target = ($tiene_enlace && isset($enlace['target']) && $enlace['target']) ? esc_attr($enlace['target']) : '_self';
        $enlace_title = ($tiene_enlace && isset($enlace['title']) && $enlace['title']) ? esc_attr($enlace['title']) : '';
        
        // Título personalizado para el hover
        $enlace_titulo_hover = isset($item['galeria_lm_enlace_titulo']) && !empty($item['galeria_lm_enlace_titulo']) ? esc_html($item['galeria_lm_enlace_titulo']) : '';
        
        // Determinar si mostrar overlay (tiene enlace Y tiene título o texto de enlace)
        $mostrar_overlay = $tiene_enlace && ($enlace_titulo_hover || $enlace_title);
        
        $imagen = isset($item['galeria_lm_imagen']) && is_array($item['galeria_lm_imagen']) ? $item['galeria_lm_imagen'] : null;
        $imagen_full_url = '';
        $imagen_alt = '';
        if ($imagen && !empty($imagen['url'])) {
            $imagen_full_url = isset($imagen['sizes']['large']) ? esc_url($imagen['sizes']['large']) : esc_url($imagen['url']);
            $imagen_alt = isset($imagen['alt']) ? esc_attr($imagen['alt']) : '';
        }
        
        ob_start();
        ?>
        <div class="galeria_lm_item <?php echo $altura_class; ?> <?php echo $visible_class; ?> galeria_lm_item_<?php echo $tipo_contenido; ?><?php echo $mostrar_overlay ? ' galeria_lm_item_has_overlay' : ''; ?>" 
             data-altura="<?php echo $altura; ?>"
             data-idx="<?php echo $item_index; ?>"
             data-tipo="<?php echo $tipo_contenido; ?>">
            <div class="galeria_lm_item_inner">
                <?php if ($tipo_contenido === 'imagen') : 
                    if ($imagen && !empty($imagen['url'])) :
                        $imagen_url = esc_url($imagen['url']);
                ?>
                    <?php if ($es_lightbox) : ?>
                        <!-- IMAGEN CON LIGHTBOX -->
                        <div class="galeria_lm_lightbox_trigger" 
                             onclick="galeriaLmOpenLightbox('<?php echo $imagen_full_url; ?>', '<?php echo $imagen_alt; ?>')"
                             style="cursor: zoom-in; width: 100%; height: 100%; position: relative;">
                            <img src="<?php echo $imagen_url; ?>" 
                                 alt="<?php echo $imagen_alt; ?>" 
                                 class="galeria_lm_item_img"
                                 loading="lazy">
                            <div class="galeria_lm_lightbox_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    <line x1="11" y1="8" x2="11" y2="14"></line>
                                    <line x1="8" y1="11" x2="14" y2="11"></line>
                                </svg>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- IMAGEN NORMAL -->
                        <img src="<?php echo $imagen_url; ?>" 
                             alt="<?php echo $imagen_alt; ?>" 
                             class="galeria_lm_item_img"
                             loading="lazy">
                        <?php if ($tiene_enlace) : ?>
                            <a href="<?php echo $enlace_url; ?>" 
                               target="<?php echo $enlace_target; ?>"
                               class="galeria_lm_item_link_overlay"
                               aria-label="<?php echo $enlace_titulo_hover ? $enlace_titulo_hover : ($enlace_title ? $enlace_title : 'Ver más'); ?>">
                                <?php if ($mostrar_overlay) : ?>
                                    <div class="galeria_lm_hover_overlay">
                                        <div class="galeria_lm_hover_content">
                                            <?php if ($enlace_titulo_hover) : ?>
                                                <span class="galeria_lm_hover_title"><?php echo $enlace_titulo_hover; ?></span>
                                            <?php endif; ?>
                                            <?php if ($enlace_title) : ?>
                                                <span class="galeria_lm_hover_link_text"><?php echo $enlace_title; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php else : // VIDEO
                    $video_tipo = isset($item['galeria_lm_video_tipo']) ? $item['galeria_lm_video_tipo'] : 'externo';
                    $video_url = isset($item['galeria_lm_video_url']) ? esc_url($item['galeria_lm_video_url']) : '';
                    $video_mp4 = isset($item['galeria_lm_video_mp4']) && is_array($item['galeria_lm_video_mp4']) ? $item['galeria_lm_video_mp4'] : null;
                    $video_poster = isset($item['galeria_lm_video_poster']) && is_array($item['galeria_lm_video_poster']) ? $item['galeria_lm_video_poster'] : null;
                    $video_autoplay = isset($item['galeria_lm_video_autoplay']) ? $item['galeria_lm_video_autoplay'] : false;
                    $video_loop = isset($item['galeria_lm_video_loop']) ? $item['galeria_lm_video_loop'] : false;
                    $video_controls = isset($item['galeria_lm_video_controls']) ? $item['galeria_lm_video_controls'] : true;
                    
                    $poster_url = $video_poster && !empty($video_poster['url']) ? esc_url($video_poster['url']) : '';
                    $mp4_url = $video_mp4 && !empty($video_mp4['url']) ? esc_url($video_mp4['url']) : '';
                    
                    if ($video_tipo === 'externo' && $video_url) : ?>
                        <div class="galeria_lm_video_wrapper" data-video-url="<?php echo $video_url; ?>">
                            <?php if ($poster_url) : ?>
                                <img src="<?php echo $poster_url; ?>" alt="Video" class="galeria_lm_video_poster" loading="lazy">
                            <?php endif; ?>
                            <div class="galeria_lm_play_btn" data-video-type="externo">
                                <span class="galeria_lm_play_icon"></span>
                            </div>
                        </div>
                    <?php elseif ($video_tipo === 'mp4' && $mp4_url) : ?>
                        <div class="galeria_lm_video_wrapper galeria_lm_video_mp4_wrapper">
                            <?php if ($video_autoplay) : ?>
                                <video class="galeria_lm_video_mp4 galeria_lm_video_autoplay"
                                    autoplay muted playsinline
                                    <?php echo $video_loop ? 'loop' : ''; ?>
                                    <?php echo $video_controls ? 'controls' : ''; ?>
                                    <?php echo $poster_url ? 'poster="' . $poster_url . '"' : ''; ?>>
                                    <source src="<?php echo $mp4_url; ?>" type="video/mp4">
                                </video>
                            <?php else : ?>
                                <?php if ($poster_url) : ?>
                                    <img src="<?php echo $poster_url; ?>" alt="Video" class="galeria_lm_video_poster" loading="lazy">
                                <?php endif; ?>
                                <div class="galeria_lm_play_btn" data-video-type="mp4">
                                    <span class="galeria_lm_play_icon"></span>
                                </div>
                                <video class="galeria_lm_video_mp4" style="display: none;"
                                    <?php echo $video_loop ? 'loop' : ''; ?>
                                    <?php echo $video_controls ? 'controls' : ''; ?>>
                                    <source src="<?php echo $mp4_url; ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('tictac_galeria_lm_item_valido')) {
    function tictac_galeria_lm_item_valido($item) {
        $tipo_contenido = isset($item['galeria_lm_tipo_contenido']) ? $item['galeria_lm_tipo_contenido'] : 'imagen';
        if ($tipo_contenido === 'imagen') {
            $imagen = isset($item['galeria_lm_imagen']) && is_array($item['galeria_lm_imagen']) ? $item['galeria_lm_imagen'] : null;
            return $imagen && !empty($imagen['url']);
        } else {
            $video_tipo = isset($item['galeria_lm_video_tipo']) ? $item['galeria_lm_video_tipo'] : 'externo';
            if ($video_tipo === 'externo') {
                return !empty($item['galeria_lm_video_url']);
            } else {
                $video_mp4 = isset($item['galeria_lm_video_mp4']) && is_array($item['galeria_lm_video_mp4']) ? $item['galeria_lm_video_mp4'] : null;
                return $video_mp4 && !empty($video_mp4['url']);
            }
        }
    }
}

if (!function_exists('tictac_galeria_grid_loadmore_render')) {
    function tictac_galeria_grid_loadmore_render($block)
    {
        $ancho = get_field('galeria_lm_grid_ancho');
        $items = get_field('galeria_lm_imagenes');
        $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
        $block_id = isset($block['id']) ? esc_attr($block['id']) : 'galeria-lm-' . uniqid();
        
        $altura_desktop = intval(get_field('galeria_lm_altura_desktop')) ?: 200;
        $altura_tablet = intval(get_field('galeria_lm_altura_tablet')) ?: 160;
        $altura_mobile = intval(get_field('galeria_lm_altura_mobile')) ?: 140;
        
        $paginacion_activa = get_field('galeria_lm_paginacion_activa');
        
        $ancho = $ancho ? intval($ancho) : 3;
        $imagenes_iniciales_config = $paginacion_activa ? (intval(get_field('galeria_lm_imagenes_iniciales')) ?: 6) : 999999;
        $imagenes_iniciales = min($imagenes_iniciales_config, 20);
        $imagenes_carga = intval(get_field('galeria_lm_imagenes_carga')) ?: 3;
        $velocidad_animacion = intval(get_field('galeria_lm_velocidad_animacion')) ?: 400;
        $texto_boton = get_field('galeria_lm_texto_boton') ?: 'Cargar más';
        
        if (!$items || !is_array($items) || empty($items)) {
            if (is_admin()) {
                echo '<p style="padding: 20px; background: #f0f0f0; text-align: center;">Por favor, añade al menos un item a la galería.</p>';
            }
            return;
        }
        
        $total_items = 0;
        foreach ($items as $item) {
            if (tictac_galeria_lm_item_valido($item)) {
                $total_items++;
            }
        }
        
        $mostrar_boton = $paginacion_activa && $total_items > $imagenes_iniciales;
        ?>
        
        <style>
            .galeria_lm_item_link_overlay {
                position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;
                text-decoration: none; border: none; outline: none; background: transparent; cursor: pointer;
            }
            .galeria_lm_item { position: relative; }
            .galeria_lm_item_inner { position: relative; width: 100%; height: 100%; }
            
            /* HOVER OVERLAY CON TÍTULO Y TEXTO */
            .galeria_lm_hover_overlay {
                position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0); 
                display: flex; align-items: center; justify-content: center;
                transition: background 0.3s ease;
            }
            .galeria_lm_hover_content {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                text-align: center; padding: 20px; opacity: 0;
                transform: translateY(10px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            .galeria_lm_hover_title {
                color: #fff; font-size: 18px; font-weight: 600;
                margin-bottom: 8px; line-height: 1.3;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            .galeria_lm_hover_link_text {
                color: rgba(255, 255, 255, 0.85); font-size: 14px; font-weight: 400;
                text-decoration: underline; text-underline-offset: 3px;
                text-shadow: 0 1px 3px rgba(0,0,0,0.3);
            }
            
            /* Hover state */
            .galeria_lm_item_has_overlay:hover .galeria_lm_hover_overlay {
                background: rgba(0, 0, 0, 0.65);
            }
            .galeria_lm_item_has_overlay:hover .galeria_lm_hover_content {
                opacity: 1; transform: translateY(0);
            }
            
            /* Responsive hover overlay */
            @media (max-width: 768px) {
                .galeria_lm_hover_title { font-size: 15px; margin-bottom: 5px; }
                .galeria_lm_hover_link_text { font-size: 12px; }
                .galeria_lm_hover_content { padding: 15px; }
            }
            @media (max-width: 480px) {
                .galeria_lm_hover_title { font-size: 13px; }
                .galeria_lm_hover_link_text { font-size: 11px; }
                .galeria_lm_hover_content { padding: 10px; }
            }
            
            .galeria_lm_video_wrapper {
                position: relative; width: 100%; height: 100%; display: flex;
                align-items: center; justify-content: center; background: #000;
            }
            .galeria_lm_video_poster {
                width: 100%; height: 100%; object-fit: cover;
                position: absolute; top: 0; left: 0; z-index: 1;
            }
            .galeria_lm_video_mp4 { width: 100%; height: 100%; object-fit: cover; }
            .galeria_lm_video_autoplay { display: block; }
            .galeria_lm_video_iframe {
                width: 100%; height: 100%; border: none;
                position: absolute; top: 0; left: 0;
            }
            .galeria_lm_play_btn {
                position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                width: 60px; height: 60px; background: rgba(255, 255, 255, 0.9);
                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                cursor: pointer; z-index: 10; transition: all 0.3s ease;
            }
            .galeria_lm_play_btn:hover { transform: translate(-50%, -50%) scale(1.1); background: #fff; }
            .galeria_lm_play_icon {
                width: 0; height: 0; border-left: 15px solid #e74c3c;
                border-top: 10px solid transparent; border-bottom: 10px solid transparent; margin-left: 3px;
            }
            
            /* LIGHTBOX SIMPLE */
            .galeria_lm_lightbox_trigger { display: block; width: 100%; height: 100%; cursor: zoom-in; position: relative; }
            .galeria_lm_lightbox_trigger:hover .galeria_lm_lightbox_icon { opacity: 1; }
            .galeria_lm_lightbox_icon {
                position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                width: 50px; height: 50px; background: rgba(0, 0, 0, 0.6); border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                opacity: 0; transition: opacity 0.3s ease; pointer-events: none; z-index: 5;
            }
            .galeria_lm_lightbox_icon svg { width: 24px; height: 24px; color: #fff; }
            
            /* LIGHTBOX OVERLAY */
            #galeria_lm_lightbox_overlay {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.75); z-index: 999999;
                display: none; align-items: center; justify-content: center; cursor: zoom-out;
            }
            #galeria_lm_lightbox_overlay.active { display: flex; }
            #galeria_lm_lightbox_overlay img {
                max-width: 90%; max-height: 90vh; object-fit: contain;
                border-radius: 0px; box-shadow: 0 5px 30px rgba(0,0,0,0.5); cursor: default;
            }
            #galeria_lm_lightbox_overlay .galeria_lm_lightbox_close {
                position: absolute; top: 20px; right: 20px; width: 44px; height: 44px;
                background: rgba(255, 255, 255, 0.1); border: none; border-radius: 50%;
                cursor: pointer; display: flex; align-items: center; justify-content: center;
                transition: background 0.3s ease; z-index: 10;
            }
            #galeria_lm_lightbox_overlay .galeria_lm_lightbox_close:hover { background: rgba(255, 255, 255, 0.25); }
            #galeria_lm_lightbox_overlay .galeria_lm_lightbox_close svg { width: 24px; height: 24px; color: #fff; }
            @media (max-width: 768px) {
                #galeria_lm_lightbox_overlay img { max-width: 95%; max-height: 80vh; }
                .galeria_lm_lightbox_icon { width: 40px; height: 40px; }
                .galeria_lm_lightbox_icon svg { width: 20px; height: 20px; }
            }
        </style>
        
        <!-- LIGHTBOX OVERLAY GLOBAL -->
        <div id="galeria_lm_lightbox_overlay">
            <button class="galeria_lm_lightbox_close" onclick="galeriaLmCloseLightbox()" aria-label="Cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <img src="" alt="" id="galeria_lm_lightbox_img" onclick="event.stopPropagation()">
        </div>
        
        <!-- DESKTOP -->
        <div class="<?php echo $block_class; ?> galeria_grid_loadmore_block" 
             data-block-id-loadmore="<?php echo $block_id; ?>"
             data-ancho="<?php echo $ancho; ?>"
             data-animation-speed="<?php echo $velocidad_animacion; ?>"
             style="--galeria-altura-desktop: <?php echo $altura_desktop; ?>px;">
            <div class="galeria_lm_grid_container">
                <div class="galeria_lm_grid" style="grid-template-columns: repeat(<?php echo $ancho; ?>, 1fr); grid-auto-rows: <?php echo $altura_desktop; ?>px; gap: 8px;">
                    <?php 
                    $item_valido_index = 0;
                    foreach ($items as $item) :
                        if (!tictac_galeria_lm_item_valido($item)) continue;
                        echo tictac_galeria_lm_render_item($item, $item_valido_index, $imagenes_iniciales);
                        $item_valido_index++;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php if ($mostrar_boton) : ?>
            <div class="d-flex flex-row col-12 justify-content-center align-items-center" style="margin-top: 40px;">
                <div class="col-10 d-flex justify-content-center align-items-center">
                    <hr class="col-5 me-2 custom-line">
                    <button class="banner-toggle-btn galeria_lm_button" 
                            data-items-per-load="<?php echo $imagenes_carga; ?>"
                            data-total-items="<?php echo $total_items; ?>"
                            data-initial-items="<?php echo $imagenes_iniciales; ?>"
                            data-expanded="false" aria-expanded="false">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pluse.svg" alt="Cargar más" class="toggle-btn-icon">
                    </button>
                    <hr class="col-5 ms-2 custom-line">
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- TABLET -->
        <div class="galeria_grid_loadmore_block_tablet" 
             data-block-id-loadmore-tablet="<?php echo $block_id; ?>"
             data-ancho="2"
             data-animation-speed="<?php echo $velocidad_animacion; ?>"
             style="--galeria-altura-tablet: <?php echo $altura_tablet; ?>px;">
            <div class="galeria_lm_grid_container">
                <div class="galeria_lm_grid" style="grid-template-columns: repeat(2, 1fr); grid-auto-rows: <?php echo $altura_tablet; ?>px; gap: 8px;">
                    <?php 
                    $item_valido_index = 0;
                    foreach ($items as $item) :
                        if (!tictac_galeria_lm_item_valido($item)) continue;
                        echo tictac_galeria_lm_render_item($item, $item_valido_index, $imagenes_iniciales);
                        $item_valido_index++;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php if ($mostrar_boton) : ?>
            <div class="d-flex flex-row col-12 justify-content-center align-items-center" style="margin-top: 40px;">
                <div class="col-11 d-flex justify-content-center align-items-center">
                    <hr class="col-5 me-2 custom-line">
                    <button class="banner-toggle-btn galeria_lm_button" 
                            data-items-per-load="<?php echo $imagenes_carga; ?>"
                            data-total-items="<?php echo $total_items; ?>"
                            data-initial-items="<?php echo $imagenes_iniciales; ?>"
                            data-expanded="false" aria-expanded="false">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pluse.svg" alt="Cargar más" class="toggle-btn-icon">
                    </button>
                    <hr class="col-5 ms-2 custom-line">
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- MOBILE -->
        <div class="galeria_grid_loadmore_block_mobile" 
             data-block-id-loadmore-mobile="<?php echo $block_id; ?>"
             data-ancho="2"
             data-animation-speed="<?php echo $velocidad_animacion; ?>"
             style="--galeria-altura-mobile: <?php echo $altura_mobile; ?>px;">
            <div class="galeria_lm_grid_container">
                <div class="galeria_lm_grid" style="grid-template-columns: repeat(2, 1fr); grid-auto-rows: <?php echo $altura_mobile; ?>px; gap: 5px;">
                    <?php 
                    $item_valido_index = 0;
                    foreach ($items as $item) :
                        if (!tictac_galeria_lm_item_valido($item)) continue;
                        echo tictac_galeria_lm_render_item($item, $item_valido_index, $imagenes_iniciales);
                        $item_valido_index++;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php if ($mostrar_boton) : ?>
            <div class="d-flex flex-row col-12 justify-content-center align-items-center" style="margin-top: 40px;">
                <div class="col-10 d-flex justify-content-center align-items-center">
                    <hr class="col-5 me-2 custom-line">
                    <button class="banner-toggle-btn galeria_lm_button" 
                            data-items-per-load="<?php echo $imagenes_carga; ?>"
                            data-total-items="<?php echo $total_items; ?>"
                            data-initial-items="<?php echo $imagenes_iniciales; ?>"
                            data-expanded="false" aria-expanded="false">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pluse.svg" alt="Cargar más" class="toggle-btn-icon">
                    </button>
                    <hr class="col-5 ms-2 custom-line">
                </div>
            </div>
            <?php endif; ?>
        </div>

        <script>
        // FUNCIONES GLOBALES SIMPLES PARA LIGHTBOX
        function galeriaLmOpenLightbox(src, alt) {
            var overlay = document.getElementById('galeria_lm_lightbox_overlay');
            var img = document.getElementById('galeria_lm_lightbox_img');
            if (!overlay || !img) return;
            img.src = src;
            img.alt = alt || '';
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function galeriaLmCloseLightbox() {
            var overlay = document.getElementById('galeria_lm_lightbox_overlay');
            if (!overlay) return;
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Cerrar con click en overlay
        document.getElementById('galeria_lm_lightbox_overlay').addEventListener('click', function(e) {
            if (e.target === this) galeriaLmCloseLightbox();
        });
        
        // Cerrar con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') galeriaLmCloseLightbox();
        });

        // Videos y Loadmore
        (function() {
            function extractVideoData(url) {
                if (!url) return null;
                var match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/);
                if (match && match[1]) return { type: 'youtube', id: match[1] };
                match = url.match(/vimeo\.com\/(\d+)/);
                if (match && match[1]) return { type: 'vimeo', id: match[1] };
                return null;
            }
            
            document.querySelectorAll('.galeria_lm_play_btn[data-video-type="externo"]').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var wrapper = btn.closest('.galeria_lm_video_wrapper');
                    var videoUrl = wrapper.dataset.videoUrl;
                    var videoData = extractVideoData(videoUrl);
                    if (videoData) {
                        var embedUrl = '';
                        if (videoData.type === 'youtube') embedUrl = 'https://www.youtube.com/embed/' + videoData.id + '?autoplay=1';
                        else if (videoData.type === 'vimeo') embedUrl = 'https://player.vimeo.com/video/' + videoData.id + '?autoplay=1';
                        if (embedUrl) wrapper.innerHTML = '<iframe class="galeria_lm_video_iframe" src="' + embedUrl + '" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                    }
                });
            });
            
            document.querySelectorAll('.galeria_lm_play_btn[data-video-type="mp4"]').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var wrapper = btn.closest('.galeria_lm_video_wrapper');
                    var video = wrapper.querySelector('.galeria_lm_video_mp4');
                    var poster = wrapper.querySelector('.galeria_lm_video_poster');
                    if (video) {
                        if (poster) poster.style.display = 'none';
                        btn.style.display = 'none';
                        video.style.display = 'block';
                        video.play();
                    }
                });
            });
            
            var blocks = document.querySelectorAll('[data-block-id-loadmore], [data-block-id-loadmore-tablet], [data-block-id-loadmore-mobile]');
            blocks.forEach(function(block) {
                var button = block.querySelector('.galeria_lm_button');
                var grid = block.querySelector('.galeria_lm_grid');
                var buttonWrapper = button ? button.closest('.d-flex') : null;
                if (!button || !grid || !buttonWrapper) return;
                var itemsPerLoad = parseInt(button.dataset.itemsPerLoad) || 3;
                var totalItems = parseInt(button.dataset.totalItems) || 0;
                var initialItems = parseInt(button.dataset.initialItems) || 6;
                var animationSpeed = parseInt(block.dataset.animationSpeed) || 400;
                var visibleItems = initialItems;
                var isAnimating = false;
                
                function updateGrid() {
                    var items = grid.querySelectorAll('.galeria_lm_item');
                    items.forEach(function(item, idx) {
                        if (idx < visibleItems) {
                            if (!item.classList.contains('galeria_lm_item_visible')) {
                                item.classList.remove('galeria_lm_item_hidden');
                                item.classList.add('galeria_lm_item_visible');
                            }
                        } else {
                            item.classList.add('galeria_lm_item_hidden');
                            item.classList.remove('galeria_lm_item_visible');
                        }
                    });
                    buttonWrapper.style.display = visibleItems >= totalItems ? 'none' : 'flex';
                }
                
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (isAnimating) return;
                    isAnimating = true;
                    button.disabled = true;
                    visibleItems += itemsPerLoad;
                    updateGrid();
                    setTimeout(function() {
                        isAnimating = false;
                        button.disabled = false;
                    }, animationSpeed);
                });
                
                updateGrid();
            });
        })();
        </script>
        <?php
    }
}
?>
<?php
// 1. Primero registrar el bloque
function empresas_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'empresas',
            'title'             => __('Empresas', 'tictac'),
            'description'       => __('Slider de logos de empresas', 'tictac'),
            'render_callback'   => 'empresas',
            'mode'              => 'edit',
            'icon'              => 'slides',
            'keywords'          => ['custom', 'empresas', 'logos', 'slider', 'bloque'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
            'enqueue_assets'    => 'empresas_enqueue_assets',
        ]);
    }
}
add_action('acf/init', 'empresas_acf');

// 2. Después registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_empresas',
        'title' => 'Empresas',
        'fields' => array(
            array(
                'key' => 'field_empresas_titulo',
                'label' => 'Título',
                'name' => 'empresas_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'EMPRESAS QUE YA HAN AUMENTADO SUS INGRESOS',
            ),
            array(
                'key' => 'field_empresas_repetidor',
                'label' => 'Logos de Empresas',
                'name' => 'empresas_repetidor',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Agregar Logo',
                'sub_fields' => array(
                    array(
                        'key' => 'field_empresa_logo',
                        'label' => 'Logo',
                        'name' => 'empresa_logo',
                        'type' => 'image',
                        'required' => 1,
                    ),
                ),
            ),
            array(
                'key' => 'field_flecha_izquierda',
                'label' => 'Flecha Izquierda',
                'name' => 'flecha_izquierda',
                'type' => 'image',
                'required' => 0,
                'instructions' => 'Imagen para la flecha de navegación izquierda',
            ),
            array(
                'key' => 'field_flecha_derecha',
                'label' => 'Flecha Derecha',
                'name' => 'flecha_derecha',
                'type' => 'image',
                'required' => 0,
                'instructions' => 'Imagen para la flecha de navegación derecha',
            ),
            array(
                'key' => 'field_enlace_texto',
                'label' => 'Texto del Enlace',
                'name' => 'enlace_texto',
                'type' => 'text',
                'required' => 0,
                'instructions' => 'Texto que aparecerá en el botón (ej: "Ver todas las empresas")',
            ),
            array(
                'key' => 'field_enlace_url',
                'label' => 'Enlace',
                'name' => 'enlace_url',
                'type' => 'page_link',
                'required' => 0,
                'instructions' => 'Selecciona la página a la que debe dirigir el enlace',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/empresas',
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
        'description' => '',
        'show_in_rest' => 0,
    ));

endif;

// 3. Función para encolar assets cuando se usa el bloque
function empresas_enqueue_assets()
{
    // Splide CSS
    wp_enqueue_style(
        'splide-css',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css',
        array(),
        '4.1.4'
    );

    // CSS personalizado del bloque
    wp_enqueue_style(
        'empresas-css',
        get_stylesheet_directory_uri() . '/assets/functions/blocks/empresas/empresas.min.css',
        array('splide-css'),
        filemtime(get_stylesheet_directory() . '/assets/functions/blocks/empresas/empresas.min.css')
    );

    // Splide JS
    wp_enqueue_script(
        'splide-js',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
        array(),
        '4.1.4',
        true
    );
}

// 4. Encolar estilos globales
function empresas_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('empresas', get_stylesheet_directory_uri() . '/assets/functions/blocks/empresas/empresas.min.css');
    }
}
add_action('wp_enqueue_scripts', 'empresas_scripts');

// 5. Función de renderizado
function empresas($block)
{
    $titulo = get_field('empresas_titulo');
    $empresas = get_field('empresas_repetidor');
    $flecha_izq = get_field('flecha_izquierda');
    $flecha_der = get_field('flecha_derecha');
    $enlace_texto = get_field('enlace_texto');
    $enlace_url = get_field('enlace_url');
    $block_id = 'slider-empresas-' . uniqid();

    // Si no hay empresas, no renderizar el slider
    if (!$empresas || empty($empresas)) {
        echo '<div class="empresas-vacio"><p>No hay logos agregados. Por favor, agrega logos en el editor.</p></div>';
        return;
    }
?>
    <div class="<?php if (isset($block['className'])) {
                    echo esc_attr($block['className']);
                } ?> empresas">
        <div class="containerancho">
            <!-- Título -->
            <div class="empresas-header">
                <h2 class="empresas-titulo"><?php echo esc_html($titulo); ?></h2>
            </div>

            <!-- Contenido del slider -->
            <div class="empresas-contenido">
                <!-- Splide Slider -->
                <div class="splide slider-empresas" id="<?php echo esc_attr($block_id); ?>">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($empresas as $empresa): ?>
                                <li class="splide__slide">
                                    <div class="empresa-logo">
                                        <img src="<?php echo esc_url($empresa['empresa_logo']['url']); ?>"
                                            alt="<?php echo esc_attr($empresa['empresa_logo']['alt'] ?: 'Logo empresa'); ?>">
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Controles del slider -->
                <div class="empresas-controles">
                    <!-- Flechas -->
                    <div class="empresas-navigation">
                        <button class="empresas-prev" data-slider="<?php echo esc_attr($block_id); ?>" aria-label="Anterior">
                            <?php if ($flecha_izq): ?>
                                <img src="<?php echo esc_url($flecha_izq['url']); ?>" 
                                     alt="<?php echo esc_attr($flecha_izq['alt'] ?: 'Anterior'); ?>">
                            <?php endif; ?>
                        </button>
                        <button class="empresas-next" data-slider="<?php echo esc_attr($block_id); ?>" aria-label="Siguiente">
                            <?php if ($flecha_der): ?>
                                <img src="<?php echo esc_url($flecha_der['url']); ?>" 
                                     alt="<?php echo esc_attr($flecha_der['alt'] ?: 'Siguiente'); ?>">
                            <?php endif; ?>
                        </button>
                    </div>

                    <!-- Paginación -->
                    <div class="empresas-pagination" id="pagination-<?php echo esc_attr($block_id); ?>"></div>
                </div>

                <!-- Enlace (solo si existe) -->
                <?php if ($enlace_url && $enlace_texto): ?>
                    <div class="empresas-enlace-contenedor">
                        <a href="<?php echo esc_url($enlace_url); ?>" class="empresas-enlace">
                            <?php echo esc_html($enlace_texto); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cargar Splide directamente aquí -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script>
        (function($) {
            'use strict';

            function initEmpresasSliders() {
                if (typeof Splide === 'undefined') {
                    return;
                }

                var sliders = document.querySelectorAll('.slider-empresas');

                if (sliders.length === 0) {
                    return;
                }

                sliders.forEach(function(sliderElement) {
                    var sliderId = sliderElement.id;

                    if (!sliderId) {
                        return;
                    }

                    try {
                        var splideEmpresas = new Splide('#' + sliderId, {
                            type: 'loop',
                            arrows: false,
                            pagination: false,
                            perPage: 5,
                            perMove: 1,
                            autoplay: true,
                            Montserratval: 2500,
                            speed: 800,
                            gap: 0,
                            breakpoints: {
                                1200: {
                                    perPage: 4
                                },
                                1024: {
                                    perPage: 4
                                },
                                768: {
                                    perPage: 3
                                },
                                640: {
                                    perPage: 2
                                },
                                480: {
                                    perPage: 1
                                }
                            }
                        });

                        splideEmpresas.mount();

                        function actualizarBordes() {
                            var allSlides = sliderElement.querySelectorAll('.splide__slide');

                            allSlides.forEach(function(slide) {
                                var logo = slide.querySelector('.empresa-logo');
                                if (logo) {
                                    logo.style.borderLeft = 'none';
                                    logo.style.borderRight = 'none';
                                }
                            });

                            var visibleSlides = sliderElement.querySelectorAll('.splide__slide.is-visible');

                            visibleSlides.forEach(function(slide, index) {
                                var logo = slide.querySelector('.empresa-logo');
                                if (logo && index > 0) {
                                    logo.style.borderLeft = '1px solid #E0E0E0';
                                }
                            });
                        }

                        setTimeout(actualizarBordes, 100);

                        splideEmpresas.on('moved', function() {
                            actualizarBordes();
                        });

                        splideEmpresas.on('resized', function() {
                            actualizarBordes();
                        });

                        var prevBtn = document.querySelector('.empresas-prev[data-slider="' + sliderId + '"]');
                        var nextBtn = document.querySelector('.empresas-next[data-slider="' + sliderId + '"]');

                        if (prevBtn) {
                            prevBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                splideEmpresas.go('<');
                            });
                        }

                        if (nextBtn) {
                            nextBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                splideEmpresas.go('>');
                            });
                        }

                        var paginationContainer = document.getElementById('pagination-' + sliderId);

                        if (!paginationContainer) {
                            return;
                        }

                        var slides = splideEmpresas.length;

                        paginationContainer.innerHTML = '';

                        for (var i = 0; i < slides; i++) {
                            var dot = document.createElement('button');
                            dot.className = 'pagination-dot';
                            dot.setAttribute('data-index', i);
                            dot.setAttribute('aria-label', 'Ir a slide ' + (i + 1));
                            paginationContainer.appendChild(dot);
                        }

                        var firstDot = paginationContainer.querySelector('.pagination-dot');
                        if (firstDot) {
                            firstDot.classList.add('active');
                        }

                        var dots = paginationContainer.querySelectorAll('.pagination-dot');
                        dots.forEach(function(dot) {
                            dot.addEventListener('click', function(e) {
                                e.preventDefault();
                                var index = parseInt(this.getAttribute('data-index'));
                                splideEmpresas.go(index);
                            });
                        });

                        splideEmpresas.on('moved', function(newIndex) {
                            dots.forEach(function(dot) {
                                dot.classList.remove('active');
                            });
                            var activeDot = paginationContainer.querySelector('.pagination-dot[data-index="' + newIndex + '"]');
                            if (activeDot) {
                                activeDot.classList.add('active');
                            }
                        });

                    } catch (error) {
                        return;
                    }
                });
            }

            $(document).ready(function() {
                initEmpresasSliders();
            });

        })(jQuery);
    </script>
<?php
}
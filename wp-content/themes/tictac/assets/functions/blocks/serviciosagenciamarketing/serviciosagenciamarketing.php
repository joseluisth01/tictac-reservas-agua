<?php
// 1. Primero registrar el bloque
function serviciosagenciamarketing_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'serviciosagenciamarketing',
            'title'             => __('Servicios Agencia Marketing', 'tictac'),
            'description'       => __('Grid de servicios de la agencia', 'tictac'),
            'render_callback'   => 'serviciosagenciamarketing',
            'mode'              => 'edit',
            'icon'              => 'grid-view',
            'keywords'          => ['custom', 'servicios', 'marketing', 'bloque'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'serviciosagenciamarketing_acf');

// 2. Después registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_serviciosagenciamarketing',
        'title' => 'Servicios Agencia Marketing',
        'fields' => array(
            array(
                'key' => 'field_serviciosagenciamarketing_titulo',
                'label' => 'Título Principal',
                'name' => 'serviciosagenciamarketing_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'SOMOS EXPERTOS EN MARKETING ONLINE EN CÓRDOBA',
            ),
            array(
                'key' => 'field_serviciosagenciamarketing_repetidor',
                'label' => 'Servicios',
                'name' => 'serviciosagenciamarketing_repetidor',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Agregar Servicio',
                'sub_fields' => array(
                    array(
                        'key' => 'field_servicio_icono',
                        'label' => 'Icono',
                        'name' => 'servicio_icono',
                        'type' => 'image',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_servicio_titulo',
                        'label' => 'Título',
                        'name' => 'servicio_titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_servicio_parrafo',
                        'label' => 'Párrafo',
                        'name' => 'servicio_parrafo',
                        'type' => 'textarea',
                        'required' => 1,
                        'rows' => 3,
                    ),
                    array(
                        'key' => 'field_servicio_texto_enlace',
                        'label' => 'Texto del Enlace',
                        'name' => 'servicio_texto_enlace',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => 'VER MÁS',
                    ),
                    array(
                        'key' => 'field_servicio_enlace',
                        'label' => 'Enlace',
                        'name' => 'servicio_enlace',
                        'type' => 'page_link',
                        'required' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/serviciosagenciamarketing',
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

// 3. Encolar los estilos y scripts
function serviciosagenciamarketing_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('serviciosagenciamarketing', get_stylesheet_directory_uri() . '/assets/functions/blocks/serviciosagenciamarketing/serviciosagenciamarketing.min.css');
    }
}
add_action('wp_enqueue_scripts', 'serviciosagenciamarketing_scripts');

// 4. Función de renderizado
function serviciosagenciamarketing($block)
{
    $titulo = get_field('serviciosagenciamarketing_titulo');
    $servicios = get_field('serviciosagenciamarketing_repetidor');

    // URLs de las flechas
    $flecha_izq = home_url('/wp-content/uploads/2025/10/Vector_35.svg');
    $flecha_der = home_url('/wp-content/uploads/2025/10/Vector_36.svg');

    $block_id = 'carousel-' . uniqid();
?>
    <!-- Cargar Splide CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    <div class="<?php if (isset($block['className'])) {
                    echo esc_attr($block['className']);
                } ?> serviciosagenciamarketing">
        <div class="containerancho">
            <div class="serviciosagenciamarketing-header">
                <h2 class="serviciosagenciamarketing-titulo"><?php echo esc_html($titulo); ?></h2>
            </div>

            <!-- Grid de Servicios (Desktop) -->
            <div class="serviciosagenciamarketing-grid">
                <?php if ($servicios): ?>
                    <?php foreach ($servicios as $servicio): ?>
                        <div class="servicio-card">
                            <div class="servicio-icono">
                                <img src="<?php echo esc_url($servicio['servicio_icono']['url']); ?>" alt="<?php echo esc_attr($servicio['servicio_icono']['alt']); ?>">
                            </div>
                            <h3 class="servicio-titulo"><?php echo esc_html($servicio['servicio_titulo']); ?></h3>
                            <p class="servicio-parrafo"><?php echo esc_html($servicio['servicio_parrafo']); ?></p>
                            <a href="<?php echo esc_url($servicio['servicio_enlace']); ?>" class="servicio-boton">
                                <?php echo esc_html($servicio['servicio_texto_enlace']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Carrusel Splide (Mobile) -->
            <div class="serviciosagenciamarketing-carousel-wrapper">
                <div id="<?php echo esc_attr($block_id); ?>" class="splide serviciosagenciamarketing-splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php if ($servicios): ?>
                                <?php foreach ($servicios as $servicio): ?>
                                    <li class="splide__slide">
                                        <div class="servicio-card">
                                            <div class="servicio-icono">
                                                <img src="<?php echo esc_url($servicio['servicio_icono']['url']); ?>" alt="<?php echo esc_attr($servicio['servicio_icono']['alt']); ?>">
                                            </div>
                                            <h3 class="servicio-titulo"><?php echo esc_html($servicio['servicio_titulo']); ?></h3>
                                            <p class="servicio-parrafo"><?php echo esc_html($servicio['servicio_parrafo']); ?></p>
                                            <a href="<?php echo esc_url($servicio['servicio_enlace']); ?>" class="servicio-boton">
                                                <?php echo esc_html($servicio['servicio_texto_enlace']); ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Flechas personalizadas -->
                    <div class="splide__arrows">
                        <button class="splide__arrow splide__arrow--prev">
                            <img src="<?php echo esc_url($flecha_izq); ?>" alt="Anterior">
                        </button>
                        <button class="splide__arrow splide__arrow--next">
                            <img src="<?php echo esc_url($flecha_der); ?>" alt="Siguiente">
                        </button>
                    </div>

                    <!-- Puntos de paginación -->
                    <ul class="splide__pagination"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Cargar Splide JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script>
        (function() {
            var blockId = '<?php echo esc_js($block_id); ?>';

            function initSplide() {
                var splideElement = document.getElementById(blockId);

                if (!splideElement) {
                    return;
                }

                // Verificar si Splide está disponible
                if (typeof Splide === 'undefined') {
                    setTimeout(initSplide, 100);
                    return;
                }

                try {
                    var splide = new Splide('#' + blockId, {
                        type: 'slide',
                        perPage: 1,
                        perMove: 1,
                        gap: '15px',
                        padding: {
                            left: '0',
                            right: '13%'
                        }, // Cambiado aquí
                        arrows: true,
                        pagination: true,
                        drag: true,
                        snap: true,
                        autoWidth: false,
                        focus: 0, // Cambiado de 'center' a 0 para alinear a la izquierda
                        trimSpace: false,
                        breakpoints: {
                            800: {
                                destroy: false,
                            }
                        }
                    });

                    splide.mount();
                } catch (error) {
                    console.error('Error al inicializar Splide:', error);
                }
            }

            // Iniciar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSplide);
            } else {
                initSplide();
            }
        })();
    </script>
<?php
}

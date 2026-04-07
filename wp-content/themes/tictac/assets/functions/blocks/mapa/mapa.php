<?php
// 1. Registrar el bloque
function mapa_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'mapa',
            'title'             => __('Mapa de Ubicación', 'tictac'),
            'description'       => __('Muestra un mapa con información de contacto y horarios', 'tictac'),
            'render_callback'   => 'mapa',
            'mode'              => 'edit',
            'icon'              => 'location-alt',
            'keywords'          => ['custom', 'mapa', 'ubicación', 'contacto', 'horario'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'mapa_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_mapa',
        'title' => 'Mapa de Ubicación',
        'fields' => array(
            array(
                'key' => 'field_mapa_titulo',
                'label' => 'Título Principal (H2)',
                'name' => 'mapa_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'VISITA NUESTRA TIENDA DE MUEBLES BARATOS EN MÁLAGA EN EL POLÍGONO EL VISO',
            ),
            array(
                'key' => 'field_mapa_descripcion',
                'label' => 'Descripción (opcional)',
                'name' => 'mapa_descripcion',
                'type' => 'wysiwyg',
                'required' => 0,
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'instructions' => 'Texto que aparecerá debajo del título. Dejar vacío si no se necesita.',
            ),
            array(
                'key' => 'field_mapa_iframe',
                'label' => 'Iframe de Google Maps',
                'name' => 'mapa_iframe',
                'type' => 'textarea',
                'required' => 1,
                'rows' => 5,
                'instructions' => 'Pega aquí el código iframe completo de Google Maps',
                'placeholder' => '<iframe src="..." width="600" height="450"...</iframe>',
            ),
            array(
                'key' => 'field_mapa_contenido_izquierda',
                'label' => 'Contenido Izquierda',
                'name' => 'mapa_contenido_izquierda',
                'type' => 'wysiwyg',
                'required' => 1,
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_mapa_contenido_derecha',
                'label' => 'Contenido Derecha',
                'name' => 'mapa_contenido_derecha',
                'type' => 'wysiwyg',
                'required' => 1,
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ),
            array(
                'key' => 'field_mapa_boton_texto',
                'label' => 'Texto del Botón',
                'name' => 'mapa_boton_texto',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'CÓMO LLEGAR AHORA',
            ),
            array(
                'key' => 'field_mapa_boton_enlace',
                'label' => 'Enlace del Botón',
                'name' => 'mapa_boton_enlace',
                'type' => 'url',
                'required' => 1,
                'placeholder' => 'https://maps.google.com/...',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/mapa',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function mapa_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('mapa', get_stylesheet_directory_uri() . '/assets/functions/blocks/mapa/mapa.min.css');
    }
}
add_action('wp_enqueue_scripts', 'mapa_scripts');

// 4. Función de renderizado
function mapa($block)
{
    $titulo = get_field('mapa_titulo');
    $descripcion = get_field('mapa_descripcion');
    $iframe = get_field('mapa_iframe');
    $contenido_izquierda = get_field('mapa_contenido_izquierda');
    $contenido_derecha = get_field('mapa_contenido_derecha');
    $boton_texto = get_field('mapa_boton_texto');
    $boton_enlace = get_field('mapa_boton_enlace');

    // Generar ID único para este bloque
    $block_id = 'mapa-' . uniqid();
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> mapa" id="<?php echo $block_id; ?>">

        <!-- Título Principal -->
        <?php if ($titulo): ?>
            <h2 class="mapa-titulo"><?= $titulo ?></h2>
        <?php endif; ?>

        <!-- Descripción (solo si tiene contenido) -->
        <?php if ($descripcion): ?>
            <div class="mapa-descripcion"><?= $descripcion ?></div>
        <?php endif; ?>

        <!-- Contenedor del Mapa -->
        <?php if ($iframe): ?>
            <div class="mapa-iframe-wrapper">
                <?php echo $iframe; ?>
            </div>
        <?php endif; ?>

        <!-- Contenido Inferior (2 columnas) -->
        <div class="mapa-contenido">
            <!-- Columna Izquierda -->
            <?php if ($contenido_izquierda): ?>
                <div class="mapa-contenido-columna mapa-contenido-izquierda">
                    <?php echo $contenido_izquierda; ?>
                </div>
            <?php endif; ?>

            <!-- Columna Derecha -->
            <?php if ($contenido_derecha): ?>
                <div class="mapa-contenido-columna mapa-contenido-derecha">
                    <?php echo $contenido_derecha; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botón -->
        <?php if ($boton_enlace && $boton_texto): ?>
            <div class="mapa-boton-wrapper">
                <a href="<?php echo esc_url($boton_enlace); ?>" 
                   class="mapa-boton"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php echo esc_html($boton_texto); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php
}
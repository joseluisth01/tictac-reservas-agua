<?php
// 1. Primero registrar el bloque
function bloqueanalisis_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloqueanalisis',
            'title'             => __('Bloque Análisis', 'tictac'),
            'description'       => __('Bloque promocional de diagnóstico gratuito', 'tictac'),
            'render_callback'   => 'bloqueanalisis',
            'mode'              => 'edit',
            'icon'              => 'chart-line',
            'keywords'          => ['custom', 'banner', 'análisis', 'diagnóstico', 'bloque'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloqueanalisis_acf');

// 2. Después registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloqueanalisis',
        'title' => 'Bloque Análisis',
        'fields' => array(
            array(
                'key' => 'field_bloqueanalisis_titulo',
                'label' => 'Título',
                'name' => 'bloqueanalisis_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'DIAGNÓSTICO GRATUITO EN 30\'',
            ),
            array(
                'key' => 'field_bloqueanalisis_imagen_fondo',
                'label' => 'Imagen de Fondo',
                'name' => 'bloqueanalisis_imagen_fondo',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen de fondo del bloque',
            ),
            array(
                'key' => 'field_bloqueanalisis_subtitulo',
                'label' => 'Subtítulo',
                'name' => 'bloqueanalisis_subtitulo',
                'type' => 'textarea',
                'required' => 0,
                'default_value' => 'ANÁLISIS RÁPIDO DE TU WEB O CANAL DE VENTA + PLAN DE ACCIÓN PRIORIZADO',
                'instructions' => 'Subtítulo opcional debajo del título',
            ),
            array(
                'key' => 'field_bloqueanalisis_descripcion',
                'label' => 'Descripción',
                'name' => 'bloqueanalisis_descripcion',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 3,
                'instructions' => 'Descripción opcional debajo del subtítulo',
            ),
            array(
                'key' => 'field_bloqueanalisis_texto_boton',
                'label' => 'Texto del Botón',
                'name' => 'bloqueanalisis_texto_boton',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'QUIERO MI PLAN',
            ),
            array(
                'key' => 'field_bloqueanalisis_enlace_boton',
                'label' => 'Enlace del Botón',
                'name' => 'bloqueanalisis_enlace_boton',
                'type' => 'page_link',
                'required' => 0,
                'instructions' => 'Deja vacío si quieres mostrar el formulario al hacer clic',
            ),
            array(
                'key' => 'field_bloqueanalisis_gif',
                'label' => 'GIF Flechas',
                'name' => 'bloqueanalisis_gif',
                'type' => 'image',
                'required' => 0,
                'instructions' => 'GIF de las flechas que aparecerá dentro del botón a ambos lados',
            ),
            array(
                'key' => 'field_bloqueanalisis_shortcode_formulario',
                'label' => 'Shortcode del Formulario',
                'name' => 'bloqueanalisis_shortcode_formulario',
                'type' => 'text',
                'required' => 0,
                'instructions' => 'Introduce el shortcode de Contact Form 7 (ej: [contact-form-7 id="123"])',
                'placeholder' => '[contact-form-7 id="123"]',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloqueanalisis',
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
function bloqueanalisis_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloqueanalisis', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloqueanalisis/bloqueanalisis.min.css');
        wp_enqueue_script('bloqueanalisis-js', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloqueanalisis/bloqueanalisis.js', array(), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'bloqueanalisis_scripts');

// 4. Función de renderizado
function bloqueanalisis($block)
{
    $titulo = get_field('bloqueanalisis_titulo');
    $imagen_fondo = get_field('bloqueanalisis_imagen_fondo');
    $subtitulo = get_field('bloqueanalisis_subtitulo');
    $descripcion = get_field('bloqueanalisis_descripcion');
    $texto_boton = get_field('bloqueanalisis_texto_boton');
    $enlace_boton = get_field('bloqueanalisis_enlace_boton');
    $gif = get_field('bloqueanalisis_gif');
    $shortcode_formulario = get_field('bloqueanalisis_shortcode_formulario');
    
    // Generar ID único para este bloque
    $block_id = 'bloqueanalisis-' . uniqid();
?>
    <div class="<?php if(isset($block['className'])){ echo $block['className']; } ?> bloqueanalisis" id="<?php echo $block_id; ?>">
        <div class="containerancho">
            <!-- Título en la parte superior -->
            <div class="bloqueanalisis-header">
                <h2 class="bloqueanalisis-titulo"><?= $titulo; ?></h2>
            </div>

            <!-- Contenido del bloque con imagen de fondo -->
            <div class="bloqueanalisis-contenido" style="background-image: url('<?= $imagen_fondo['url']; ?>');">
                <div class="bloqueanalisis-overlay">
                    <div class="bloqueanalisis-inner">
                        <!-- Texto a la izquierda -->
                        <div class="bloqueanalisis-texto">
                            <!-- Subtítulo (opcional) -->
                            <?php if(!empty($subtitulo)): ?>
                                <h3 class="bloqueanalisis-subtitulo"><?= nl2br($subtitulo); ?></h3>
                            <?php endif; ?>

                            <!-- Descripción (opcional) -->
                            <?php if(!empty($descripcion)): ?>
                                <div class="bloqueanalisis-descripcion"><?= nl2br($descripcion); ?></div>
                            <?php endif; ?>

                            <!-- Botón con GIFs DENTRO -->
                            <div class="bloqueanalisis-boton-wrapper" id="boton-wrapper-<?php echo $block_id; ?>">
                                <?php if($enlace_boton && !$shortcode_formulario): ?>
                                    <a href="<?= $enlace_boton; ?>" class="bloqueanalisis-boton">
                                <?php else: ?>
                                    <button type="button" class="bloqueanalisis-boton" onclick="mostrarFormulario('<?php echo $block_id; ?>')">
                                <?php endif; ?>
                                    <?php if($gif): ?>
                                        <img src="<?= $gif['url']; ?>" alt="<?= $gif['alt']; ?>" class="bloqueanalisis-gif-izq">
                                    <?php endif; ?>
                                    
                                    <span class="bloqueanalisis-boton-texto"><?= $texto_boton; ?></span>
                                    
                                    <?php if($gif): ?>
                                        <img src="<?= $gif['url']; ?>" alt="<?= $gif['alt']; ?>" class="bloqueanalisis-gif-der">
                                    <?php endif; ?>
                                <?php if($enlace_boton && !$shortcode_formulario): ?>
                                    </a>
                                <?php else: ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Formulario a la derecha (oculto inicialmente) -->
                        <?php if($shortcode_formulario): ?>
                        <div class="bloqueanalisis-formulario" id="formulario-<?php echo $block_id; ?>" style="display: none;">
                            <div class="bloqueanalisis-formulario-contenedor">
                                <!-- Botón cerrar -->
                                <button type="button" class="bloqueanalisis-formulario-cerrar" onclick="cerrarFormulario('<?php echo $block_id; ?>')" aria-label="Cerrar formulario">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <?= do_shortcode($shortcode_formulario); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($shortcode_formulario): ?>
    <script>
    function mostrarFormulario(blockId) {
        var botonWrapper = document.getElementById('boton-wrapper-' + blockId);
        var formulario = document.getElementById('formulario-' + blockId);
        
        if (botonWrapper && formulario) {
            botonWrapper.style.display = 'none';
            formulario.style.display = 'block';
        }
    }

    function cerrarFormulario(blockId) {
        var botonWrapper = document.getElementById('boton-wrapper-' + blockId);
        var formulario = document.getElementById('formulario-' + blockId);
        
        if (botonWrapper && formulario) {
            formulario.style.display = 'none';
            botonWrapper.style.display = 'flex';
        }
    }
    </script>
    <?php endif; ?>

<?php
}
<?php
// 1. Registrar el bloque
function textofoto_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'textofoto',
            'title'             => __('Texto + Foto', 'tictac'),
            'description'       => __('Muestra texto a la izquierda y una imagen a la derecha', 'tictac'),
            'render_callback'   => 'textofoto',
            'mode'              => 'edit',
            'icon'              => 'align-pull-left',
            'keywords'          => ['custom', 'texto', 'foto', 'imagen', 'columnas'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'textofoto_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_textofoto',
        'title' => 'Texto + Foto',
        'fields' => array(
            array(
                'key' => 'field_textofoto_titulo',
                'label' => 'Título (H2)',
                'name' => 'textofoto_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => '¿POR QUÉ SOMOS EL OUTLET DE REFERENCIA EN DESCANSO?',
            ),
            array(
                'key' => 'field_textofoto_parrafo',
                'label' => 'Párrafo Descriptivo',
                'name' => 'textofoto_parrafo',
                'type' => 'textarea',
                'required' => 1,
                'rows' => 6,
                'default_value' => 'A diferencia de las grandes superficies frías, en Low Factory somos tu vecino de confianza. Nos hemos consolidado como el outlet de colchones preferido en Málaga porque, aquí, nunca pagas por marca o por metro a publicidad, pagas por el núcleo y el tejido del colchón. Además, nuestro servicio de entrega y subida a domicilio marca la diferencia.',
            ),
            array(
                'key' => 'field_textofoto_imagen',
                'label' => 'Imagen',
                'name' => 'textofoto_imagen',
                'type' => 'image',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/textofoto',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function textofoto_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('textofoto', get_stylesheet_directory_uri() . '/assets/functions/blocks/textofoto/textofoto.min.css');
    }
}
add_action('wp_enqueue_scripts', 'textofoto_scripts');

// 4. Función de renderizado
function textofoto($block)
{
    $titulo = get_field('textofoto_titulo');
    $parrafo = get_field('textofoto_parrafo');
    $imagen = get_field('textofoto_imagen');

    // Generar ID único para este bloque
    $block_id = 'textofoto-' . uniqid();
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> textofoto" id="<?php echo $block_id; ?>">

        <div class="textofoto-contenedor">
            <!-- Columna Izquierda: Texto -->
            <div class="textofoto-texto">
                <?php if ($titulo): ?>
                    <h2 class="textofoto-titulo"><?= $titulo ?></h2>
                <?php endif; ?>

                <?php if ($parrafo): ?>
                    <p class="textofoto-parrafo"><?= nl2br(esc_html($parrafo)); ?></p>
                <?php endif; ?>
            </div>

            <!-- Columna Derecha: Imagen -->
            <?php if ($imagen): ?>
                <div class="textofoto-imagen">
                    <img src="<?php echo esc_url($imagen['url']); ?>" 
                         alt="<?php echo esc_attr($imagen['alt'] ?: $titulo); ?>">
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php
}
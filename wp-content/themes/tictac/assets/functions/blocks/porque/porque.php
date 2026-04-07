<?php
// 1. Registrar el bloque
function bloqueporque_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloqueporque',
            'title'             => __('Bloque Porque', 'tictac'),
            'description'       => __('Bloque con etiqueta, título y repetidor de hasta 4 elementos con imagen', 'tictac'),
            'render_callback'   => 'bloqueporque',
            'mode'              => 'edit',
            'icon'              => 'grid-view',
            'keywords'          => ['custom', 'porque', 'grid', 'cards'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloqueporque_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloqueporque',
        'title' => 'Bloque Porque',
        'fields' => array(
            array(
                'key' => 'field_porque_etiqueta',
                'label' => 'Etiqueta Superior',
                'name' => 'porque_etiqueta',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_porque_titulo',
                'label' => 'Título (H2)',
                'name' => 'porque_titulo',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_porque_parrafo',
                'label' => 'Párrafo (Opcional)',
                'name' => 'porque_parrafo',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 3,
                'instructions' => 'Texto que aparecerá debajo del título',
            ),
            array(
                'key' => 'field_porque_repetidor',
                'label' => 'Cards',
                'name' => 'porque_repetidor',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Añadir Card',
                'sub_fields' => array(
                    array(
                        'key' => 'field_porque_card_imagen',
                        'label' => 'Imagen',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_porque_card_titulo',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_porque_card_texto',
                        'label' => 'Texto',
                        'name' => 'texto',
                        'type' => 'wysiwyg',
                        'required' => 0,
                        'rows' => 3,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloqueporque',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloqueporque_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloqueporque', get_stylesheet_directory_uri() . '/assets/functions/blocks/porque/porque.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloqueporque_scripts');

// 4. Función de renderizado
function bloqueporque($block)
{
    // Variables ACF
    $etiqueta = get_field('porque_etiqueta');
    $titulo = get_field('porque_titulo');
    $parrafo = get_field('porque_parrafo');
    $repetidor = get_field('porque_repetidor');

    // Generar ID único para este bloque
    $block_id = 'bloqueporque-' . uniqid();
?>

<section class="porque-section <?php if (isset($block['className'])) { echo esc_attr($block['className']); } ?>" id="<?php echo $block_id; ?>">
    <div class="containerancho">
        
        <!-- ENCABEZADO -->
        <div class="porque-encabezado">
            <?php if ($etiqueta) : ?>
                <span class="porque-etiqueta"><?php echo esc_html($etiqueta); ?></span>
            <?php endif; ?>

            <?php if ($titulo) : ?>
                <h2 class="porque-titulo"><?php echo esc_html($titulo); ?></h2>
            <?php endif; ?>

            <?php if ($parrafo) : ?>
                <p class="porque-parrafo"><?= $parrafo ?></p>
            <?php endif; ?>
        </div>

        <!-- GRID DE CARDS -->
        <?php if ($repetidor && count($repetidor) > 0) : ?>
            <div class="porque-grid">
                <?php foreach ($repetidor as $card) : 
                    $imagen = $card['imagen'];
                    $titulo_card = $card['titulo'];
                    $texto_card = $card['texto'];
                ?>
                    <div class="porque-card">
                        <?php if ($imagen) : ?>
                            <div class="porque-card-imagen">
                                <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php echo esc_attr($imagen['alt'] ?: $titulo_card); ?>">
                            </div>
                        <?php endif; ?>

                        <div class="porque-card-contenido">
                            <?php if ($titulo_card) : ?>
                                <h3 class="porque-card-titulo"><?php echo esc_html($titulo_card); ?></h3>
                            <?php endif; ?>

                            <?php if ($texto_card) : ?>
                                <p class="porque-card-texto my-2"><?= $texto_card ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
}
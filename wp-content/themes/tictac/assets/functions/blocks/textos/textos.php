<?php
// 1. Registrar el bloque
function bloquetextos_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'textos',
            'title'             => __('Bloque Textos', 'tictac'),
            'description'       => __('Bloque con etiqueta, título y párrafo', 'tictac'),
            'render_callback'   => 'bloquetextos',
            'mode'              => 'edit',
            'icon'              => 'editor-aligncenter',
            'keywords'          => ['custom', 'textos', 'título', 'párrafo'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloquetextos_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloquetextos',
        'title' => 'Bloque Textos',
        'fields' => array(
            array(
                'key' => 'field_textos_etiqueta',
                'label' => 'Etiqueta Superior',
                'name' => 'textos_etiqueta',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_textos_titulo',
                'label' => 'Título (H2)',
                'name' => 'textos_titulo',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_textos_parrafo',
                'label' => 'Párrafo',
                'name' => 'textos_parrafo',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 4,
                'instructions' => 'Texto que aparecerá debajo del título',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/textos',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloquetextos_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquetextos', get_stylesheet_directory_uri() . '/assets/functions/blocks/textos/textos.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloquetextos_scripts');

// 4. Función de renderizado
function bloquetextos($block)
{
    // Variables ACF
    $etiqueta = get_field('textos_etiqueta');
    $titulo = get_field('textos_titulo');
    $parrafo = get_field('textos_parrafo');

    // Generar ID único para este bloque
    $block_id = 'bloquetextos-' . uniqid();
?>

<section class="textos-section <?php if (isset($block['className'])) { echo esc_attr($block['className']); } ?>" id="<?php echo $block_id; ?>">
    <div class="containerancho">
        
        <!-- ENCABEZADO -->
        <div class="textos-encabezado">
            <?php if ($etiqueta) : ?>
                <span class="textos-etiqueta"><?php echo esc_html($etiqueta); ?></span>
            <?php endif; ?>

            <?php if ($titulo) : ?>
                <h2 class="textos-titulo"><?php echo esc_html($titulo); ?></h2>
            <?php endif; ?>

            <?php if ($parrafo) : ?>
                <p class="textos-parrafo"><?= $parrafo ?></p>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php
}
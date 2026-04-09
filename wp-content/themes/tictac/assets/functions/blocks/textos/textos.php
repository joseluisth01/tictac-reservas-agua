<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloquetextos',
        'title' => 'Bloque Textos',
        'fields' => array(
            array(
                'key' => 'field_bt_titulo',
                'label' => 'Título',
                'name' => 'bt_titulo',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'instructions' => 'Texto principal del bloque.',
            ),
            array(
                'key' => 'field_bt_imagen',
                'label' => 'Imagen decorativa',
                'name' => 'bt_imagen',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'instructions' => 'Imagen entre el título y el párrafo (ej: olas decorativas). Opcional.',
            ),
            array(
                'key' => 'field_bt_parrafo',
                'label' => 'Párrafo',
                'name' => 'bt_parrafo',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'instructions' => 'Texto descriptivo debajo de la imagen.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloquetextos',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));

endif;

if (!function_exists('tictac_bloquetextos_acf')) {
    function tictac_bloquetextos_acf()
    {
        acf_register_block_type(array(
            'name'            => 'bloquetextos',
            'title'           => __('Bloque Textos', 'tictac'),
            'description'     => __('Título + imagen decorativa + párrafo centrados.', 'tictac'),
            'render_callback' => 'tictac_bloquetextos_render',
            'mode'            => 'preview',
            'supports'        => array(
                'mode'  => true,
                'align' => false,
            ),
            'icon'     => 'editor-aligncenter',
            'keywords' => array('texto', 'titulo', 'parrafo'),
        ));
    }
    add_action('acf/init', 'tictac_bloquetextos_acf');
}

if (!function_exists('tictac_bloquetextos_scripts')) {
    function tictac_bloquetextos_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_style('textos', get_stylesheet_directory_uri() . '/assets/functions/blocks/textos/textos.min.css', array(), '1.0');
        }
    }
    add_action('wp_enqueue_scripts', 'tictac_bloquetextos_scripts');
    add_action('admin_enqueue_scripts', 'tictac_bloquetextos_scripts');
}

if (!function_exists('tictac_bloquetextos_render')) {
    function tictac_bloquetextos_render($block)
    {
        $titulo  = get_field('bt_titulo');
        $imagen  = get_field('bt_imagen');
        $parrafo = get_field('bt_parrafo');

        $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
        $anchor      = !empty($block['anchor']) ? ' id="' . esc_attr($block['anchor']) . '"' : '';
        ?>

        <section<?php echo $anchor; ?> class="bloquetextos<?php echo $block_class ? ' ' . $block_class : ''; ?> containerancho">

            <?php if ($titulo) : ?>
                <div class="bloquetextos__titulo">
                    <?php echo $titulo; ?>
                </div>
            <?php endif; ?>

            <?php if ($imagen && !empty($imagen['url'])) : ?>
                <div class="bloquetextos__imagen mt-3">
                    <img src="<?php echo esc_url($imagen['url']); ?>"
                         alt="<?php echo esc_attr($imagen['alt']); ?>"
                         loading="lazy">
                </div>
            <?php endif; ?>

            <?php if ($parrafo) : ?>
                <div class="bloquetextos__parrafo">
                    <?php echo $parrafo; ?>
                </div>
            <?php endif; ?>

        </section>

        <?php
    }
}
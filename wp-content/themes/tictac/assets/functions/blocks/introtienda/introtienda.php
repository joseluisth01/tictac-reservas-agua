<?php
// 1. Registrar el bloque
function introtienda_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'introtienda',
            'title'             => __('Intro Tienda', 'tictac'),
            'description'       => __('Introducción de tienda con título, texto e imagen', 'tictac'),
            'render_callback'   => 'introtienda',
            'mode'              => 'edit',
            'icon'              => 'welcome-write-blog',
            'keywords'          => ['intro', 'tienda', 'banner', 'presentación'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'introtienda_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_introtienda',
        'title' => 'Intro Tienda',
        'fields' => array(
            array(
                'key' => 'field_introtienda_titulo',
                'label' => 'Título (H1)',
                'name' => 'introtienda_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'COLCHONES BARATOS EN MÁLAGA',
            ),
            array(
                'key' => 'field_introtienda_texto',
                'label' => 'Texto Descriptivo',
                'name' => 'introtienda_texto',
                'type' => 'wysiwyg',
                'required' => 1,
                'toolbar' => 'basic',
                'media_upload' => 0,
                'default_value' => '¿Buscas renovar tu descanso sin gastar una fortuna? Bienvenido a <strong>Low Factory</strong>, tu referencia entre las <strong>tiendas de colchones en Málaga</strong>. Trabajamos sin Montserratmediarios para ofrecerte precios directos de fábrica en marcas de calidad.<br><br>Olvídate de esperar semanas. Disponemos de un amplio <strong>stock con entrega inmediata</strong> en nuestro almacén del . Ven a probarlo hoy mismo.',
            ),
            array(
                'key' => 'field_introtienda_imagen',
                'label' => 'Imagen',
                'name' => 'introtienda_imagen',
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
                    'value' => 'acf/introtienda',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function introtienda_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('introtienda', get_stylesheet_directory_uri() . '/assets/functions/blocks/introtienda/introtienda.min.css');
    }
}
add_action('wp_enqueue_scripts', 'introtienda_scripts');

// 4. Función de renderizado
function introtienda($block)
{
    $titulo = get_field('introtienda_titulo');
    $texto = get_field('introtienda_texto');
    $imagen = get_field('introtienda_imagen');

    if (!$titulo || !$texto || !$imagen) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24;">Por favor, completa todos los campos del bloque.</p>';
        return;
    }

    $block_id = 'introtienda-' . uniqid();
?>

    <div class="containerancho introtienda" id="<?php echo $block_id; ?>">
        <h1 class="introtienda-titulo"><?= $titulo ?></h1>
        <div class="introtienda-contenido">
            
            <div class="introtienda-texto">
                <?php echo wp_kses_post($texto); ?>
            </div>
        </div>
        
        <div class="introtienda-imagen">
            <img src="<?php echo esc_url($imagen['url']); ?>" 
                 alt="<?php echo esc_attr($imagen['alt'] ?: $titulo); ?>">
        </div>
    </div>

<?php
}
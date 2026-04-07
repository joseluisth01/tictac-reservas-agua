<?php
// 1. Registrar el bloque
function catalogocategorias_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'catalogocategorias',
            'title'             => __('Catálogo de Categorías', 'tictac'),
            'description'       => __('Muestra tarjetas informativas en grid personalizable', 'tictac'),
            'render_callback'   => 'catalogocategorias',
            'mode'              => 'edit',
            'icon'              => 'grid-view',
            'keywords'          => ['custom', 'categorías', 'catálogo', 'tarjetas'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'catalogocategorias_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_catalogocategorias',
        'title' => 'Catálogo de Categorías',
        'fields' => array(
            // Selector de columnas
            array(
                'key' => 'field_catalogocategorias_columnas',
                'label' => 'Número de Columnas',
                'name' => 'catalogocategorias_columnas',
                'type' => 'select',
                'required' => 1,
                'choices' => array(
                    '2' => '2 Columnas',
                    '3' => '3 Columnas',
                    '4' => '4 Columnas',
                    '5' => '5 Columnas',
                ),
                'default_value' => '4',
                'instructions' => 'Selecciona cuántas tarjetas se mostrarán por fila',
            ),
            // Repeater de tarjetas
            array(
                'key' => 'field_catalogocategorias_tarjetas',
                'label' => 'Tarjetas',
                'name' => 'catalogocategorias_tarjetas',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Tarjeta',
                'sub_fields' => array(
                    // Imagen
                    array(
                        'key' => 'field_catalogocategorias_imagen',
                        'label' => 'Imagen',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                    ),
                    // Familia (Subtítulo)
                    array(
                        'key' => 'field_catalogocategorias_familia',
                        'label' => 'Familia (Subtítulo)',
                        'name' => 'familia',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ),
                    // Nombre (Título)
                    array(
                        'key' => 'field_catalogocategorias_nombre',
                        'label' => 'Nombre (Título)',
                        'name' => 'nombre',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ),
                    // Descripción
                    array(
                        'key' => 'field_catalogocategorias_descripcion',
                        'label' => 'Descripción',
                        'name' => 'descripcion',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'tabs' => 'visual',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                    // Tratamiento
                    array(
                        'key' => 'field_catalogocategorias_tratamiento',
                        'label' => 'Tratamiento',
                        'name' => 'tratamiento',
                        'type' => 'wysiwyg',
                        'required' => 0,
                        'tabs' => 'visual',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/catalogocategorias',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function catalogocategorias_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('catalogocategorias', get_stylesheet_directory_uri() . '/assets/functions/blocks/catalogocategorias/catalogocategorias.min.css');
    }
}
add_action('wp_enqueue_scripts', 'catalogocategorias_scripts');

// 4. Función de renderizado
function catalogocategorias($block)
{
    $columnas = get_field('catalogocategorias_columnas') ?: '4';
    $tarjetas = get_field('catalogocategorias_tarjetas');

    if (!$tarjetas) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, añade al menos una tarjeta.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'catalogocategorias-' . uniqid();
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> catalogocategorias" id="<?php echo $block_id; ?>" data-columnas="<?php echo esc_attr($columnas); ?>">

        <!-- Grid de Tarjetas -->
        <div class="catalogocategorias-grid catalogocategorias-grid-<?php echo esc_attr($columnas); ?>">
            <?php foreach ($tarjetas as $tarjeta): 
                $imagen = $tarjeta['imagen'];
                $familia = $tarjeta['familia'];
                $nombre = $tarjeta['nombre'];
                $descripcion = $tarjeta['descripcion'];
                $tratamiento = $tarjeta['tratamiento'];
            ?>
                <div class="catalogocategorias-card">
                    <!-- Imagen -->
                    <?php if ($imagen): ?>
                        <div class="catalogocategorias-card-imagen">
                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                 alt="<?php echo esc_attr($imagen['alt'] ?: $nombre); ?>">
                        </div>
                    <?php endif; ?>

                    <!-- Contenido -->
                    <div class="catalogocategorias-card-contenido">
                        <!-- Familia (Subtítulo) -->
                        <?php if ($familia): ?>
                            <div class="catalogocategorias-familia">
                                <?php echo $familia; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Nombre (Título) -->
                        <?php if ($nombre): ?>
                            <div class="catalogocategorias-nombre">
                                <?php echo $nombre; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Descripción -->
                        <?php if ($descripcion): ?>
                            <div class="catalogocategorias-descripcion">
                                <?php echo $descripcion; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Tratamiento -->
                        <?php if ($tratamiento): ?>
                            <div class="catalogocategorias-tratamiento">
                                <?php echo $tratamiento; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php
}
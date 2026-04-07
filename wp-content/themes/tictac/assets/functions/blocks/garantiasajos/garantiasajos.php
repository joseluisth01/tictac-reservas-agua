<?php
// 1. Registrar el bloque
function garantiasajos_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'garantiasajos',
            'title'             => __('Garantías Ajos', 'tictac'),
            'description'       => __('Bloque con repetidor de elementos con imagen, título y texto', 'tictac'),
            'render_callback'   => 'garantiasajos',
            'mode'              => 'edit',
            'icon'              => 'grid-view',
            'keywords'          => ['custom', 'garantías', 'ajos', 'grid'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'garantiasajos_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_garantiasajos',
        'title' => 'Garantías Ajos',
        'fields' => array(
            // Selector de elementos por fila
            array(
                'key' => 'field_garantiasajos_elementos_por_fila',
                'label' => 'Elementos por Fila',
                'name' => 'garantiasajos_elementos_por_fila',
                'type' => 'select',
                'required' => 1,
                'choices' => array(
                    '2' => '2 Elementos por Fila',
                    '3' => '3 Elementos por Fila',
                    '4' => '4 Elementos por Fila',
                    '5' => '5 Elementos por Fila',
                ),
                'default_value' => '3',
                'instructions' => 'Selecciona cuántos elementos se mostrarán por fila (máximo 5)',
            ),
            // Repeater de elementos (ilimitado)
            array(
                'key' => 'field_garantiasajos_elementos',
                'label' => 'Elementos',
                'name' => 'garantiasajos_elementos',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Elemento',
                'instructions' => 'Añade todos los elementos que necesites. Se distribuirán automáticamente según los elementos por fila seleccionados arriba.',
                'sub_fields' => array(
                    // Imagen/Icono
                    array(
                        'key' => 'field_garantiasajos_imagen',
                        'label' => 'Imagen/Icono',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'instructions' => 'Imagen o icono que aparecerá en el círculo',
                    ),
                    // Título
                    array(
                        'key' => 'field_garantiasajos_titulo',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ),
                    // Texto descriptivo
                    array(
                        'key' => 'field_garantiasajos_texto',
                        'label' => 'Texto Descriptivo',
                        'name' => 'texto',
                        'type' => 'wysiwyg',
                        'required' => 1,
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
                    'value' => 'acf/garantiasajos',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function garantiasajos_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('garantiasajos', get_stylesheet_directory_uri() . '/assets/functions/blocks/garantiasajos/garantiasajos.min.css');
    }
}
add_action('wp_enqueue_scripts', 'garantiasajos_scripts');

// 4. Función de renderizado
function garantiasajos($block)
{
    $elementos_por_fila = get_field('garantiasajos_elementos_por_fila') ?: '3';
    $elementos = get_field('garantiasajos_elementos');

    if (!$elementos) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, añade al menos un elemento.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'garantiasajos-' . uniqid();
?>
    <section class="garantiasajos-section <?php if (isset($block['className'])) echo esc_attr($block['className']); ?>" id="<?php echo $block_id; ?>" data-elementos-por-fila="<?php echo esc_attr($elementos_por_fila); ?>">
        <div class="containerancho">
            
            <!-- Grid de Elementos con Flexbox -->
            <div class="garantiasajos-grid garantiasajos-grid-<?php echo esc_attr($elementos_por_fila); ?>">
                <?php foreach ($elementos as $elemento): 
                    $imagen = $elemento['imagen'];
                    $titulo = $elemento['titulo'];
                    $texto = $elemento['texto'];
                ?>
                    <div class="garantiasajos-card">
                        <!-- Header con Imagen y Título -->
                        <div class="garantiasajos-card-header d-flex align-items-center align-content-center">
                            <!-- Imagen/Icono -->
                            <?php if ($imagen): ?>
                                <div class="garantiasajos-card-icono">
                                    <img src="<?php echo esc_url($imagen['url']); ?>" 
                                         alt="<?php echo esc_attr($imagen['alt'] ?: 'Icono'); ?>">
                                </div>
                            <?php endif; ?>

                            <!-- Título -->
                            <?php if ($titulo): ?>
                                <div class="garantiasajos-card-titulo ">
                                    <?php echo $titulo; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Texto Descriptivo -->
                        <?php if ($texto): ?>
                            <div class="garantiasajos-card-texto">
                                <?php echo $texto; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
<?php
}
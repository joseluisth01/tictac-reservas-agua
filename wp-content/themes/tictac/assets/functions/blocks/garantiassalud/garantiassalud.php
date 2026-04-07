<?php
// 1. Registrar el bloque
function garantiassalud_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'garantiassalud',
            'title'             => __('Garantías Salud', 'tictac'),
            'description'       => __('Bloque con repetidor de elementos + elemento automático con botón', 'tictac'),
            'render_callback'   => 'garantiassalud',
            'mode'              => 'edit',
            'icon'              => 'grid-view',
            'keywords'          => ['custom', 'garantías', 'salud', 'grid'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'garantiassalud_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_garantiassalud',
        'title' => 'Garantías Salud',
        'fields' => array(
            // Selector de elementos por fila
            array(
                'key' => 'field_garantiassalud_elementos_por_fila',
                'label' => 'Elementos por Fila',
                'name' => 'garantiassalud_elementos_por_fila',
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
                'key' => 'field_garantiassalud_elementos',
                'label' => 'Elementos',
                'name' => 'garantiassalud_elementos',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Elemento',
                'instructions' => 'Añade todos los elementos que necesites. Automáticamente se añadirá un elemento adicional con botón al final.',
                'sub_fields' => array(
                    // Imagen/Icono
                    array(
                        'key' => 'field_garantiassalud_imagen',
                        'label' => 'Imagen/Icono',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'instructions' => 'Imagen o icono que aparecerá en el círculo',
                    ),
                    // Título
                    array(
                        'key' => 'field_garantiassalud_titulo',
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
                        'key' => 'field_garantiassalud_texto',
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
            // Configuración del elemento adicional automático
            array(
                'key' => 'field_garantiassalud_boton_texto',
                'label' => 'Texto del Elemento Adicional',
                'name' => 'garantiassalud_boton_texto',
                'type' => 'text',
                'default_value' => 'SABER MÁS',
                'required' => 1,
                'instructions' => 'Texto que aparecerá en el elemento adicional automático',
            ),
            array(
                'key' => 'field_garantiassalud_boton_enlace',
                'label' => 'Enlace del Botón',
                'name' => 'garantiassalud_boton_enlace',
                'type' => 'page_link',
                'post_type' => array('page', 'post'),
                'allow_archives' => true,
                'multiple' => 0,
                'required' => 1,
                'instructions' => 'Selecciona la página a la que debe dirigir el botón',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/garantiassalud',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function garantiassalud_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('garantiassalud', get_stylesheet_directory_uri() . '/assets/functions/blocks/garantiassalud/garantiassalud.min.css');
    }
}
add_action('wp_enqueue_scripts', 'garantiassalud_scripts');

// 4. Función de renderizado
function garantiassalud($block)
{
    $elementos_por_fila = get_field('garantiassalud_elementos_por_fila') ?: '3';
    $elementos = get_field('garantiassalud_elementos');
    $boton_texto = get_field('garantiassalud_boton_texto') ?: 'SABER MÁS';
    $boton_enlace = get_field('garantiassalud_boton_enlace');

    $upload_dir = wp_upload_dir();
    $imagen_url = $upload_dir['baseurl'] . '/2026/01/Vector-69.svg';

    if (!$elementos) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, añade al menos un elemento.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'garantiassalud-' . uniqid();
?>
    <section class="garantiassalud-section <?php if (isset($block['className'])) echo esc_attr($block['className']); ?>" id="<?php echo $block_id; ?>" data-elementos-por-fila="<?php echo esc_attr($elementos_por_fila); ?>">
        <div class="containerancho">
            
            <!-- Grid de Elementos con Flexbox -->
            <div class="garantiassalud-grid garantiassalud-grid-<?php echo esc_attr($elementos_por_fila); ?>">
                
                <?php 
                // Elementos normales del repeater
                foreach ($elementos as $elemento): 
                    $imagen_id = $elemento['imagen'];
                    $titulo = $elemento['titulo'];
                    $texto = $elemento['texto'];
                    
                    // Obtener imagen en tamaño completo para máxima calidad
                    $imagen_url_full = wp_get_attachment_image_url($imagen_id, 'full');
                    $imagen_alt = get_post_meta($imagen_id, '_wp_attachment_image_alt', true);
                    $imagen_srcset = wp_get_attachment_image_srcset($imagen_id, 'full');
                ?>
                    <div class="garantiassalud-card">
                        <!-- Contenedor para icono + título (flex en móvil) -->
                        <div class="garantiassalud-card-header">
                            <!-- Icono -->
                            <?php if ($imagen_url_full): ?>
                                <div class="garantiassalud-card-icono">
                                    <img src="<?php echo esc_url($imagen_url_full); ?>" 
                                         alt="<?php echo esc_attr($imagen_alt ?: 'Icono'); ?>"
                                         <?php if ($imagen_srcset): ?>
                                         srcset="<?php echo esc_attr($imagen_srcset); ?>"
                                         sizes="(max-width: 600px) 60px, (max-width: 900px) 70px, 80px"
                                         <?php endif; ?>
                                         loading="lazy">
                                </div>
                            <?php endif; ?>

                            <!-- Título -->
                            <?php if ($titulo): ?>
                                <div class="garantiassalud-card-titulo">
                                    <?php echo $titulo; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Texto Descriptivo -->
                        <?php if ($texto): ?>
                            <div class="garantiassalud-card-texto">
                                <?php echo $texto; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Elemento adicional automático (mitad del ancho) -->
                <div class="garantiassalud-card garantiassalud-card-adicional">
                    <div class="garantiassalud-adicional-contenido">
                        <span class="garantiassalud-adicional-texto"><?php echo esc_html($boton_texto); ?></span>
                        <?php if ($boton_enlace): ?>
                            <a href="<?php echo esc_url($boton_enlace); ?>" class="garantiassalud-adicional-boton py-1 py-lg-2">
                                <img src="<?php echo esc_url($imagen_url); ?>" alt="">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>
    </section>
<?php
}
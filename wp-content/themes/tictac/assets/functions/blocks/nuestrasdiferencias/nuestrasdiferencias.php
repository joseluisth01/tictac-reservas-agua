<?php
// 1. Primero registrar el bloque
function nuestrasdiferencias_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'nuestrasdiferencias',
            'title'             => __('Nuestras Diferencias', 'tictac'),
            'description'       => __('Bloque con lista de diferencias e imagen', 'tictac'),
            'render_callback'   => 'nuestrasdiferencias',
            'mode'              => 'edit',
            'icon'              => 'star-filled',
            'keywords'          => ['custom', 'diferencias', 'lista', 'imagen', 'bloque'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'nuestrasdiferencias_acf');

// 2. Después registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_nuestrasdiferencias',
        'title' => 'Nuestras Diferencias',
        'fields' => array(
            array(
                'key' => 'field_diferencias_titulo',
                'label' => 'Título Principal',
                'name' => 'diferencias_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'LO QUE NOS HACE DISTINTOS A OTRAS AGENCIAS DE CÓRDOBA',
            ),
            array(
                'key' => 'field_diferencias_repetidor',
                'label' => 'Lista de Diferencias',
                'name' => 'diferencias_repetidor',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Agregar Diferencia',
                'sub_fields' => array(
                    array(
                        'key' => 'field_diferencia_icono',
                        'label' => 'Icono',
                        'name' => 'diferencia_icono',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_diferencia_titulo',
                        'label' => 'Título',
                        'name' => 'diferencia_titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_diferencia_descripcion',
                        'label' => 'Descripción',
                        'name' => 'diferencia_descripcion',
                        'type' => 'wysiwyg',
                        'required' => 0,
                        'rows' => 3,
                        'instructions' => 'Descripción opcional de la diferencia',
                    ),
                ),
            ),
            array(
                'key' => 'field_diferencias_imagen',
                'label' => 'Imagen Grande (Derecha)',
                'name' => 'diferencias_imagen',
                'type' => 'image',
                'required' => 1,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_diferencias_parrafo_inferior',
                'label' => 'Párrafo Inferior',
                'name' => 'diferencias_parrafo_inferior',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 4,
                'instructions' => 'Párrafo opcional que se mostrará en la parte inferior del bloque',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/nuestrasdiferencias',
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

// 3. Encolar los estilos
function nuestrasdiferencias_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('nuestrasdiferencias', get_stylesheet_directory_uri() . '/assets/functions/blocks/nuestrasdiferencias/nuestrasdiferencias.min.css');
    }
}
add_action('wp_enqueue_scripts', 'nuestrasdiferencias_scripts');

// 4. Función de renderizado
function nuestrasdiferencias($block)
{
    $titulo = get_field('diferencias_titulo');
    $diferencias = get_field('diferencias_repetidor');
    $imagen_grande = get_field('diferencias_imagen');
    $parrafo_inferior = get_field('diferencias_parrafo_inferior');
?>
    <div class="<?php if(isset($block['className'])){ echo esc_attr($block['className']); } ?> nuestrasdiferencias">
        <div class="containerancho">
            <!-- Título Principal -->
            <div class="diferencias-header">
                <h2 class="diferencias-titulo"><?php echo esc_html($titulo); ?></h2>
            </div>

            <!-- Contenido -->
            <div class="diferencias-contenido">
                <!-- Columna Izquierda: Lista de diferencias -->
                <div class="diferencias-lista">
                    <?php if($diferencias): ?>
                        <?php foreach($diferencias as $diferencia): ?>
                            <div class="diferencia-item">
                                <div class="diferencia-icono">
                                    <img src="<?php echo esc_url($diferencia['diferencia_icono']['url']); ?>" 
                                         alt="<?php echo esc_attr($diferencia['diferencia_icono']['alt']); ?>">
                                </div>
                                <div class="diferencia-texto">
                                    <h3><?php echo esc_html($diferencia['diferencia_titulo']); ?></h3>
                                    <?php if(!empty($diferencia['diferencia_descripcion'])): ?>
                                        <div class="diferencia-descripcion"><?= $diferencia['diferencia_descripcion'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay diferencias agregadas</p>
                    <?php endif; ?>
                </div>

                <!-- Columna Derecha: Imagen grande -->
                <div class="diferencias-imagen">
                    <?php if($imagen_grande): ?>
                        <img src="<?php echo esc_url($imagen_grande['url']); ?>" 
                             alt="<?php echo esc_attr($imagen_grande['alt']); ?>">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Párrafo Inferior (opcional) -->
            <?php if(!empty($parrafo_inferior)): ?>
                <div class="diferencias-parrafo-inferior">
                    <div class="contenidoparrafo">
                        <?= $parrafo_inferior ?>
                    </div>
                    
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}
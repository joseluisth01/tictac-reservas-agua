<?php
// 1. Primero registrar el bloque
function pasosmarketing_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'pasosmarketing',
            'title'             => __('Pasos Marketing', 'tictac'),
            'description'       => __('Pasos del proceso de marketing', 'tictac'),
            'render_callback'   => 'pasosmarketing',
            'mode'              => 'edit',
            'icon'              => 'list-view',
            'keywords'          => ['custom', 'pasos', 'proceso', 'marketing', 'bloque'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'pasosmarketing_acf');

// 2. Después registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_pasosmarketing',
        'title' => 'Pasos Marketing',
        'fields' => array(
            array(
                'key' => 'field_pasosmarketing_titulo',
                'label' => 'Título Principal',
                'name' => 'pasosmarketing_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'ASÍ TRABAJAMOS EN LA AGENCIA DE MARKETING',
            ),
            array(
                'key' => 'field_pasosmarketing_repetidor',
                'label' => 'Pasos',
                'name' => 'pasosmarketing_repetidor',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Agregar Paso',
                'sub_fields' => array(
                    array(
                        'key' => 'field_paso_numero',
                        'label' => 'Número',
                        'name' => 'paso_numero',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => '1',
                        'maxlength' => 2,
                    ),
                    array(
                        'key' => 'field_paso_titulo',
                        'label' => 'Título',
                        'name' => 'paso_titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_paso_parrafo',
                        'label' => 'Párrafo',
                        'name' => 'paso_parrafo',
                        'type' => 'textarea',
                        'required' => 1,
                        'rows' => 2,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/pasosmarketing',
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
function pasosmarketing_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('pasosmarketing', get_stylesheet_directory_uri() . '/assets/functions/blocks/pasosmarketing/pasosmarketing.min.css');
    }
}
add_action('wp_enqueue_scripts', 'pasosmarketing_scripts');

// 4. Función de renderizado
function pasosmarketing($block)
{
    $titulo = get_field('pasosmarketing_titulo');
    $pasos = get_field('pasosmarketing_repetidor');
?>
    <div class="<?php if(isset($block['className'])){ echo $block['className']; } ?> pasosmarketing">
        <div class="containerancho">
            <!-- Título Principal -->
            <div class="pasosmarketing-header">
                <h2 class="pasosmarketing-titulo"><?= $titulo; ?></h2>
            </div>

            <!-- Contenido de Pasos -->
            <div class="pasosmarketing-contenido">
                <div class="pasos-grid">
                    <?php if($pasos): ?>
                        <?php foreach($pasos as $paso): ?>
                            <div class="paso-card">
                                <!-- Número -->
                                <div class="paso-numero-wrapper">
                                    <span class="paso-numero"><?= $paso['paso_numero']; ?></span>
                                </div>
                                <div class="rosa">
<!-- Título -->
                                <h3 class="paso-titulo"><?= $paso['paso_titulo']; ?></h3>

                                <!-- Párrafo -->
                                <p class="paso-parrafo"><?= $paso['paso_parrafo']; ?></p>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
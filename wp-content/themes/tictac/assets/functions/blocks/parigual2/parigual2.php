<?php
// 1. Registrar el bloque
function bloqueparigual2_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloqueparigual2',
            'title'             => __('Bloque Par Igual 2', 'tictac'),
            'description'       => __('Bloque con dos columnas 50/50 idénticas: lista izquierda y lista derecha', 'tictac'),
            'render_callback'   => 'bloqueparigual2',
            'mode'              => 'edit',
            'icon'              => 'columns',
            'keywords'          => ['custom', 'parigual2', '50/50', 'columnas', 'lista'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloqueparigual2_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloqueparigual2',
        'title' => 'Bloque Par Igual 2',
        'fields' => array(
            // ENCABEZADO GENERAL
            array(
                'key' => 'field_parigual2_tab_general',
                'label' => 'Encabezado General',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_parigual2_etiqueta_general',
                'label' => 'Etiqueta Superior (Verde)',
                'name' => 'parigual2_etiqueta_general',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_parigual2_titulo_general',
                'label' => 'Título Principal (H2)',
                'name' => 'parigual2_titulo_general',
                'type' => 'text',
                'required' => 0,
            ),
            
            // BLOQUE IZQUIERDO
            array(
                'key' => 'field_parigual2_tab_izq',
                'label' => 'Bloque Izquierdo',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_parigual2_etiqueta_izq',
                'label' => 'Etiqueta',
                'name' => 'parigual2_etiqueta_izq',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_parigual2_titulo_izq',
                'label' => 'Título (H3)',
                'name' => 'parigual2_titulo_izq',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_parigual2_parrafo_izq',
                'label' => 'Párrafo',
                'name' => 'parigual2_parrafo_izq',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_parigual2_repetidor_izq',
                'label' => 'Lista de Items',
                'name' => 'parigual2_repetidor_izq',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_lista_imagen_izq',
                        'label' => 'Icono',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_lista_titulo_izq',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_lista_texto_izq',
                        'label' => 'Texto',
                        'name' => 'texto',
                        'type' => 'text',
                        'required' => 0,
                    ),
                ),
            ),
            array(
                'key' => 'field_parigual2_texto_adicional_izq',
                'label' => 'Texto Adicional',
                'name' => 'parigual2_texto_adicional_izq',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
                'instructions' => 'Texto que aparecerá debajo del repetidor',
            ),
            array(
                'key' => 'field_parigual2_imagen_fondo_izq',
                'label' => 'Imagen de Fondo del Bloque Izquierdo',
                'name' => 'parigual2_imagen_fondo_izq',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'instructions' => 'Imagen de fondo para el bloque izquierdo',
            ),
            
            // BLOQUE DERECHO
            array(
                'key' => 'field_parigual2_tab_der',
                'label' => 'Bloque Derecho',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_parigual2_etiqueta_der',
                'label' => 'Etiqueta',
                'name' => 'parigual2_etiqueta_der',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_parigual2_titulo_der',
                'label' => 'Título (H3)',
                'name' => 'parigual2_titulo_der',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_parigual2_parrafo_der',
                'label' => 'Párrafo',
                'name' => 'parigual2_parrafo_der',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_parigual2_repetidor_der',
                'label' => 'Lista de Items',
                'name' => 'parigual2_repetidor_der',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_lista_imagen_der',
                        'label' => 'Icono',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_lista_titulo_der',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_lista_texto_der',
                        'label' => 'Texto',
                        'name' => 'texto',
                        'type' => 'text',
                        'required' => 0,
                    ),
                ),
            ),
            array(
                'key' => 'field_parigual2_texto_adicional_der',
                'label' => 'Texto Adicional',
                'name' => 'parigual2_texto_adicional_der',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
                'instructions' => 'Texto que aparecerá debajo del repetidor',
            ),
            array(
                'key' => 'field_parigual2_imagen_fondo_der',
                'label' => 'Imagen de Fondo del Bloque Derecho',
                'name' => 'parigual2_imagen_fondo_der',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'instructions' => 'Imagen de fondo para el bloque derecho',
            ),
            array(
                'key' => 'field_parigual2_boton_texto',
                'label' => 'Texto del Botón',
                'name' => 'parigual2_boton_texto',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'CONTACTAR',
                'instructions' => 'Dejar vacío si no se desea mostrar el botón',
            ),
            array(
                'key' => 'field_parigual2_boton_icono',
                'label' => 'Icono del Botón',
                'name' => 'parigual2_boton_icono',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'instructions' => 'Imagen/icono que aparecerá dentro del enlace del botón',
            ),
            array(
                'key' => 'field_parigual2_boton_enlace',
                'label' => 'Página de Destino',
                'name' => 'parigual2_boton_enlace',
                'type' => 'page_link',
                'required' => 0,
                'post_type' => array('page'),
                'allow_null' => 1,
                'multiple' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloqueparigual2',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloqueparigual2_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloqueparigual2', get_stylesheet_directory_uri() . '/assets/functions/blocks/parigual2/parigual2.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloqueparigual2_scripts');

// 4. Función de renderizado
function bloqueparigual2($block)
{
    // Variables ACF generales
    $etiqueta_general = get_field('parigual2_etiqueta_general');
    $titulo_general = get_field('parigual2_titulo_general');
    
    // Variables ACF para el bloque izquierdo
    $etiqueta_izq = get_field('parigual2_etiqueta_izq');
    $titulo_izq = get_field('parigual2_titulo_izq');
    $parrafo_izq = get_field('parigual2_parrafo_izq');
    $repetidor_izq = get_field('parigual2_repetidor_izq');
    $texto_adicional_izq = get_field('parigual2_texto_adicional_izq');
    $imagen_fondo_izq = get_field('parigual2_imagen_fondo_izq');

    // Variables ACF para el bloque derecho
    $etiqueta_der = get_field('parigual2_etiqueta_der');
    $titulo_der = get_field('parigual2_titulo_der');
    $parrafo_der = get_field('parigual2_parrafo_der');
    $repetidor_der = get_field('parigual2_repetidor_der');
    $texto_adicional_der = get_field('parigual2_texto_adicional_der');
    $imagen_fondo_der = get_field('parigual2_imagen_fondo_der');
    $boton_texto = get_field('parigual2_boton_texto');
    $boton_icono = get_field('parigual2_boton_icono');
    $boton_enlace = get_field('parigual2_boton_enlace');

    // Generar ID único para este bloque
    $block_id = 'bloqueparigual2-' . uniqid();
?>

<section class="parigual2-section <?php if (isset($block['className'])) { echo esc_attr($block['className']); } ?>" id="<?php echo $block_id; ?>">
    <div class="containerancho">
        
        <!-- ENCABEZADO GENERAL -->
        <?php if ($etiqueta_general || $titulo_general) : ?>
            <div class="parigual2-encabezado">
                <?php if ($etiqueta_general) : ?>
                    <span class="parigual2-etiqueta-verde"><?php echo esc_html($etiqueta_general); ?></span>
                <?php endif; ?>

                <?php if ($titulo_general) : ?>
                    <h2 class="parigual2-titulo-principal"><?php echo esc_html($titulo_general); ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="parigual2-wrapper">
            
            <!-- BLOQUE IZQUIERDO -->
            <div class="parigual2-bloque parigual2-izquierda" <?php if ($imagen_fondo_izq) : ?>style="background-image: url('<?php echo esc_url($imagen_fondo_izq['url']); ?>');"<?php endif; ?>>
                <?php if ($etiqueta_izq) : ?>
                    <span class="parigual2-etiqueta"><?php echo esc_html($etiqueta_izq); ?></span>
                <?php endif; ?>

                <?php if ($titulo_izq) : ?>
                    <h3 class="parigual2-titulo"><?php echo esc_html($titulo_izq); ?></h3>
                <?php endif; ?>

                <?php if ($parrafo_izq) : ?>
                    <p class="parigual2-parrafo"><?php echo esc_html($parrafo_izq); ?></p>
                <?php endif; ?>

                <?php if ($repetidor_izq && count($repetidor_izq) > 0) : ?>
                    <div class="parigual2-lista">
                        <?php foreach ($repetidor_izq as $item) : 
                            $imagen = $item['imagen'];
                            $titulo_item = $item['titulo'];
                            $texto_item = $item['texto'];
                        ?>
                            <div class="lista-item">
                                <?php if ($imagen) : ?>
                                    <div class="lista-icono">
                                        <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php echo esc_attr($imagen['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="lista-contenido">
                                    <?php if ($titulo_item) : ?>
                                        <p class="lista-titulo"><?php echo esc_html($titulo_item); ?></p>
                                    <?php endif; ?>
                                    <?php if ($texto_item) : ?>
                                        <p class="lista-texto"><?php echo esc_html($texto_item); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($texto_adicional_izq) : ?>
                    <div class="parigual2-texto-adicional">
                        <p><?php echo esc_html($texto_adicional_izq); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- BLOQUE DERECHO -->
            <div class="parigual2-bloque parigual2-derecha" <?php if ($imagen_fondo_der) : ?>style="background-image: url('<?php echo esc_url($imagen_fondo_der['url']); ?>');"<?php endif; ?>>
                <?php if ($etiqueta_der) : ?>
                    <span class="parigual2-etiqueta"><?php echo esc_html($etiqueta_der); ?></span>
                <?php endif; ?>

                <?php if ($titulo_der) : ?>
                    <h3 class="parigual2-titulo"><?php echo esc_html($titulo_der); ?></h3>
                <?php endif; ?>

                <?php if ($parrafo_der) : ?>
                    <p class="parigual2-parrafo"><?php echo esc_html($parrafo_der); ?></p>
                <?php endif; ?>

                <?php if ($repetidor_der && count($repetidor_der) > 0) : ?>
                    <div class="parigual2-lista">
                        <?php foreach ($repetidor_der as $item) : 
                            $imagen = $item['imagen'];
                            $titulo_item = $item['titulo'];
                            $texto_item = $item['texto'];
                        ?>
                            <div class="lista-item">
                                <?php if ($imagen) : ?>
                                    <div class="lista-icono">
                                        <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php echo esc_attr($imagen['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="lista-contenido">
                                    <?php if ($titulo_item) : ?>
                                        <p class="lista-titulo"><?php echo esc_html($titulo_item); ?></p>
                                    <?php endif; ?>
                                    <?php if ($texto_item) : ?>
                                        <p class="lista-texto"><?php echo esc_html($texto_item); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($texto_adicional_der) : ?>
                    <div class="parigual2-texto-adicional">
                        <p><?php echo esc_html($texto_adicional_der); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Botón (solo se muestra si hay texto y enlace) -->
                <?php if ($boton_texto && $boton_enlace) : ?>
                    <div class="parigual2-cta">
                        <a href="<?php echo esc_url($boton_enlace); ?>" class="btn-parigual2">
                            <?php echo esc_html($boton_texto); ?>
                            <?php if ($boton_icono) : ?>
                                <img src="<?php echo esc_url($boton_icono['url']); ?>" alt="<?php echo esc_attr($boton_icono['alt']); ?>" class="btn-icono">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php
}
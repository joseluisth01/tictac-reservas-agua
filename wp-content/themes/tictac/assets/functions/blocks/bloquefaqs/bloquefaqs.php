<?php
// 1. Registrar el bloque
function bloquefaqs_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloquefaqs',
            'title'             => __('Bloque FAQs', 'tictac'),
            'description'       => __('Bloque con pasos/proceso a la izquierda y FAQs a la derecha', 'tictac'),
            'render_callback'   => 'bloquefaqs',
            'mode'              => 'edit',
            'icon'              => 'editor-help',
            'keywords'          => ['custom', 'faqs', 'preguntas', 'proceso'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloquefaqs_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloquefaqs',
        'title' => 'Bloque FAQs',
        'fields' => array(
            // COLUMNA IZQUIERDA
            array(
                'key' => 'field_faqs_tab_izq',
                'label' => 'Columna Izquierda (Proceso)',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_faqs_etiqueta_izq',
                'label' => 'Etiqueta',
                'name' => 'faqs_etiqueta_izq',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_titulo_izq',
                'label' => 'Título (H2)',
                'name' => 'faqs_titulo_izq',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_faqs_repetidor_izq',
                'label' => 'Pasos del Proceso',
                'name' => 'faqs_repetidor_izq',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Paso',
                'sub_fields' => array(
                    array(
                        'key' => 'field_faqs_paso_icono',
                        'label' => 'Icono',
                        'name' => 'icono',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'id',
                    ),
                    array(
                        'key' => 'field_faqs_paso_titulo',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_faqs_paso_texto',
                        'label' => 'Texto',
                        'name' => 'texto',
                        'type' => 'wysiwyg',
                        'required' => 0,
                        'rows' => 2,
                    ),
                ),
            ),
            array(
                'key' => 'field_faqs_boton_izq_texto',
                'label' => 'Texto del Botón',
                'name' => 'faqs_boton_izq_texto',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_boton_izq_enlace',
                'label' => 'Enlace del Botón',
                'name' => 'faqs_boton_izq_enlace',
                'type' => 'page_link',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_boton_izq_icono',
                'label' => 'Icono del Botón',
                'name' => 'faqs_boton_izq_icono',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'id',
            ),
            
            // COLUMNA DERECHA
            array(
                'key' => 'field_faqs_tab_der',
                'label' => 'Columna Derecha (FAQs)',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_faqs_etiqueta_der',
                'label' => 'Etiqueta',
                'name' => 'faqs_etiqueta_der',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_titulo_der',
                'label' => 'Título (H2)',
                'name' => 'faqs_titulo_der',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_faqs_repetidor_der',
                'label' => 'Preguntas Frecuentes',
                'name' => 'faqs_repetidor_der',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir FAQ',
                'sub_fields' => array(
                    array(
                        'key' => 'field_faqs_pregunta',
                        'label' => 'Pregunta',
                        'name' => 'pregunta',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_faqs_respuesta',
                        'label' => 'Respuesta',
                        'name' => 'respuesta',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'rows' => 4,
                    ),
                ),
            ),
            array(
                'key' => 'field_faqs_boton_der_texto',
                'label' => 'Texto del Botón',
                'name' => 'faqs_boton_der_texto',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_boton_der_enlace',
                'label' => 'Enlace del Botón',
                'name' => 'faqs_boton_der_enlace',
                'type' => 'page_link',
                'required' => 0,
            ),
            array(
                'key' => 'field_faqs_boton_der_icono',
                'label' => 'Icono del Botón',
                'name' => 'faqs_boton_der_icono',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'id',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloquefaqs',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloquefaqs_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquefaqs', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloquefaqs/bloquefaqs.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloquefaqs_scripts');

// 4. Función de renderizado
function bloquefaqs($block)
{
    // Variables ACF - Columna Izquierda
    $etiqueta_izq = get_field('faqs_etiqueta_izq');
    $titulo_izq = get_field('faqs_titulo_izq');
    $repetidor_izq = get_field('faqs_repetidor_izq');
    $boton_izq_texto = get_field('faqs_boton_izq_texto');
    $boton_izq_enlace = get_field('faqs_boton_izq_enlace');
    $boton_izq_icono_id = get_field('faqs_boton_izq_icono');

    // Variables ACF - Columna Derecha
    $etiqueta_der = get_field('faqs_etiqueta_der');
    $titulo_der = get_field('faqs_titulo_der');
    $repetidor_der = get_field('faqs_repetidor_der');
    $boton_der_texto = get_field('faqs_boton_der_texto');
    $boton_der_enlace = get_field('faqs_boton_der_enlace');
    $boton_der_icono_id = get_field('faqs_boton_der_icono');

    // Generar ID único para este bloque
    $block_id = 'bloquefaqs-' . uniqid();
?>

<section class="faqs-section <?php if (isset($block['className'])) { echo esc_attr($block['className']); } ?>" id="<?php echo $block_id; ?>">
    <div class="containerancho">
        <div class="faqs-wrapper">
            
            <!-- COLUMNA IZQUIERDA (30%) -->
            <div class="faqs-columna faqs-izquierda">
                <?php if ($etiqueta_izq) : ?>
                    <span class="faqs-etiqueta faqs-etiqueta-verde"><?php echo esc_html($etiqueta_izq); ?></span>
                <?php endif; ?>

                <?php if ($titulo_izq) : ?>
                    <h2 class="faqs-titulo"><?php echo esc_html($titulo_izq); ?></h2>
                <?php endif; ?>

                <?php if ($repetidor_izq && count($repetidor_izq) > 0) : ?>
                    <div class="faqs-pasos">
                        <?php foreach ($repetidor_izq as $paso) : 
                            $icono_id = $paso['icono'];
                            $titulo_paso = $paso['titulo'];
                            $texto_paso = $paso['texto'];
                            
                            // Obtener imagen en tamaño completo (full) para máxima calidad
                            $icono_url = wp_get_attachment_image_url($icono_id, 'full');
                            $icono_alt = get_post_meta($icono_id, '_wp_attachment_image_alt', true);
                            $icono_srcset = wp_get_attachment_image_srcset($icono_id, 'full');
                        ?>
                            <div class="faqs-paso">
                                <?php if ($icono_url) : ?>
                                    <div class="faqs-paso-icono">
                                        <img src="<?php echo esc_url($icono_url); ?>" 
                                             alt="<?php echo esc_attr($icono_alt); ?>"
                                             <?php if ($icono_srcset) : ?>
                                             srcset="<?php echo esc_attr($icono_srcset); ?>"
                                             sizes="(max-width: 1100px) 60px, 80px"
                                             <?php endif; ?>
                                             loading="lazy">
                                    </div>
                                <?php endif; ?>
                                <div class="faqs-paso-contenido">
                                    <?php if ($titulo_paso) : ?>
                                        <h4 class="faqs-paso-titulo"><?php echo esc_html($titulo_paso); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($texto_paso) : ?>
                                        <div class="faqs-paso-texto"><?= $texto_paso ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($boton_izq_texto && $boton_izq_enlace) : ?>
                    <a href="<?php echo esc_url($boton_izq_enlace); ?>" class="faqs-boton faqs-boton-amarillo d-flex justify-content-center">
                        <span><?php echo esc_html($boton_izq_texto); ?></span>
                        <?php if ($boton_izq_icono_id) : 
                            $boton_izq_icono_url = wp_get_attachment_image_url($boton_izq_icono_id, 'full');
                            $boton_izq_icono_alt = get_post_meta($boton_izq_icono_id, '_wp_attachment_image_alt', true);
                            $boton_izq_icono_srcset = wp_get_attachment_image_srcset($boton_izq_icono_id, 'full');
                        ?>
                            <img src="<?php echo esc_url($boton_izq_icono_url); ?>" 
                                 alt="<?php echo esc_attr($boton_izq_icono_alt); ?>" 
                                 class="faqs-boton-icono"
                                 <?php if ($boton_izq_icono_srcset) : ?>
                                 srcset="<?php echo esc_attr($boton_izq_icono_srcset); ?>"
                                 sizes="15px"
                                 <?php endif; ?>
                                 loading="lazy">
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- COLUMNA DERECHA (70%) -->
            <div class="faqs-columna faqs-derecha ">
                <?php if ($etiqueta_der) : ?>
                    <span class="faqs-etiqueta faqs-etiqueta-gris mb-2"><?php echo esc_html($etiqueta_der); ?></span>
                <?php endif; ?>

                <?php if ($titulo_der) : ?>
                    <h2 class="faqs-titulo"><?php echo esc_html($titulo_der); ?></h2>
                <?php endif; ?>

                <?php if ($repetidor_der && count($repetidor_der) > 0) : ?>
                    <div class="faqs-acordeon">
                        <?php foreach ($repetidor_der as $index => $faq) : 
                            $pregunta = $faq['pregunta'];
                            $respuesta = $faq['respuesta'];
                            $faq_id = $block_id . '-faq-' . $index;
                        ?>
                            <div class="faqs-item">
                                <button class="faqs-pregunta" data-faq-id="<?php echo $faq_id; ?>">
                                    <h3><?php echo esc_html($pregunta); ?></h3>
                                    <span class="faqs-icono">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faqs-respuesta" id="<?php echo $faq_id; ?>">
                                    <div class="faqs-respuesta-contenido">
                                        <div><?= $respuesta ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($boton_der_texto && $boton_der_enlace) : ?>
                    <a href="<?php echo esc_url($boton_der_enlace); ?>" class="faqs-boton faqs-boton-verde">
                        <span><?php echo esc_html($boton_der_texto); ?></span>
                        <?php if ($boton_der_icono_id) : 
                            $boton_der_icono_url = wp_get_attachment_image_url($boton_der_icono_id, 'full');
                            $boton_der_icono_alt = get_post_meta($boton_der_icono_id, '_wp_attachment_image_alt', true);
                            $boton_der_icono_srcset = wp_get_attachment_image_srcset($boton_der_icono_id, 'full');
                        ?>
                            <img src="<?php echo esc_url($boton_der_icono_url); ?>" 
                                 alt="<?php echo esc_attr($boton_der_icono_alt); ?>" 
                                 class="ms-2 faqs-boton-icono"
                                 <?php if ($boton_der_icono_srcset) : ?>
                                 srcset="<?php echo esc_attr($boton_der_icono_srcset); ?>"
                                 sizes="15px"
                                 <?php endif; ?>
                                 loading="lazy">
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?php echo $block_id; ?>';
    const section = document.getElementById(blockId);
    
    if (section) {
        const preguntas = section.querySelectorAll('.faqs-pregunta');
        const respuestas = section.querySelectorAll('.faqs-respuesta');
        
        // Abrir la primera FAQ por defecto
        if (preguntas.length > 0) {
            preguntas[0].classList.add('active');
            respuestas[0].classList.add('active');
        }
        
        preguntas.forEach(function(pregunta) {
            pregunta.addEventListener('click', function() {
                const faqId = this.getAttribute('data-faq-id');
                const respuesta = document.getElementById(faqId);
                const isActive = this.classList.contains('active');
                
                // Cerrar todas las FAQs del bloque
                section.querySelectorAll('.faqs-pregunta').forEach(function(p) {
                    p.classList.remove('active');
                });
                section.querySelectorAll('.faqs-respuesta').forEach(function(r) {
                    r.classList.remove('active');
                });
                
                // Si no estaba activa, abrirla
                if (!isActive) {
                    this.classList.add('active');
                    respuesta.classList.add('active');
                }
            });
        });
    }
});
</script>

<?php
}
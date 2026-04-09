<?php
// 1. Registrar el bloque
function bloquefaqs_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloquefaqs',
            'title'             => __('Bloque FAQs', 'tictac'),
            'description'       => __('Bloque de preguntas frecuentes con iconos personalizados', 'tictac'),
            'render_callback'   => 'bloquefaqs',
            'mode'              => 'edit',
            'icon'              => 'editor-help',
            'keywords'          => ['custom', 'faqs', 'preguntas'],
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
            array(
                'key' => 'field_faqs_icono_izq',
                'label' => 'Icono izquierda (común para todas las FAQs)',
                'name' => 'faqs_icono_izq',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'id',
                'instructions' => 'Icono que aparece a la izquierda de cada pregunta (ej: flecha)',
            ),
            array(
                'key' => 'field_faqs_icono_der',
                'label' => 'Icono derecha (común para todas las FAQs)',
                'name' => 'faqs_icono_der',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'id',
                'instructions' => 'Icono que aparece a la derecha y rota al desplegar (ej: chevron)',
            ),
            array(
                'key' => 'field_faqs_fondo_item',
                'label' => 'Fondo del elemento FAQ',
                'name' => 'faqs_fondo_item',
                'type' => 'text',
                'required' => 0,
                'default_value' => '#F3F4F1',
                'instructions' => 'Color o gradiente del bloque FAQ completo. Ej: #F3F4F1 o linear-gradient(135deg, #667eea, #764ba2)',
                'placeholder' => '#F3F4F1 o linear-gradient(...)',
            ),
            array(
                'key' => 'field_faqs_fondo_pregunta',
                'label' => 'Fondo de la pregunta',
                'name' => 'faqs_fondo_pregunta',
                'type' => 'text',
                'required' => 0,
                'default_value' => '#F3F4F1',
                'instructions' => 'Color o gradiente de la barra de pregunta. Ej: #F3F4F1 o linear-gradient(135deg, #667eea, #764ba2)',
                'placeholder' => '#F3F4F1 o linear-gradient(...)',
            ),
            array(
                'key' => 'field_faqs_repetidor',
                'label' => 'Preguntas Frecuentes',
                'name' => 'faqs_repetidor',
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
    $icono_izq_id = get_field('faqs_icono_izq');
    $icono_der_id = get_field('faqs_icono_der');
    $fondo_item = get_field('faqs_fondo_item') ?: '#F3F4F1';
    $fondo_pregunta = get_field('faqs_fondo_pregunta') ?: '#F3F4F1';
    $repetidor = get_field('faqs_repetidor');

    // Iconos comunes
    $icono_izq_url = $icono_izq_id ? wp_get_attachment_image_url($icono_izq_id, 'full') : '';
    $icono_izq_alt = $icono_izq_id ? get_post_meta($icono_izq_id, '_wp_attachment_image_alt', true) : '';

    $icono_der_url = $icono_der_id ? wp_get_attachment_image_url($icono_der_id, 'full') : '';
    $icono_der_alt = $icono_der_id ? get_post_meta($icono_der_id, '_wp_attachment_image_alt', true) : '';

    // Generar ID único para este bloque
    $block_id = 'bloquefaqs-' . uniqid();
?>

<section class="faqs-section <?php if (isset($block['className'])) { echo esc_attr($block['className']); } ?>" id="<?php echo $block_id; ?>"
    style="--faqs-bg-item: <?php echo esc_attr($fondo_item); ?>; --faqs-bg-pregunta: <?php echo esc_attr($fondo_pregunta); ?>;">
    <div class="containerancho">

        <?php if ($repetidor && count($repetidor) > 0) : ?>
            <div class="faqs-acordeon">
                <?php foreach ($repetidor as $index => $faq) : 
                    $pregunta = $faq['pregunta'];
                    $respuesta = $faq['respuesta'];
                    $faq_id = $block_id . '-faq-' . $index;
                ?>
                    <div class="faqs-item <?php echo ($index === 0) ? 'faqs-item--activo' : ''; ?>">
                        <button class="faqs-pregunta p-4 <?php echo ($index === 0) ? 'active' : ''; ?>" data-faq-id="<?php echo $faq_id; ?>">
                            <?php if ($icono_izq_url) : ?>
                                <span class="faqs-icono-izq">
                                    <img src="<?php echo esc_url($icono_izq_url); ?>" 
                                         alt="<?php echo esc_attr($icono_izq_alt); ?>" 
                                         loading="lazy">
                                </span>
                            <?php endif; ?>
                            <h3><?php echo esc_html($pregunta); ?></h3>
                            <?php if ($icono_der_url) : ?>
                                <span class="faqs-icono-der">
                                    <img src="<?php echo esc_url($icono_der_url); ?>" 
                                         alt="<?php echo esc_attr($icono_der_alt); ?>" 
                                         loading="lazy">
                                </span>
                            <?php endif; ?>
                        </button>
                        <div class="faqs-respuesta <?php echo ($index === 0) ? 'active' : ''; ?>" id="<?php echo $faq_id; ?>">
                            <div class="faqs-respuesta-contenido">
                                <div><?= $respuesta ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
(function() {
    function initFaqs() {
        var blockId = '<?php echo $block_id; ?>';
        var section = document.getElementById(blockId);
        
        if (!section || section.dataset.faqsInit) return;
        section.dataset.faqsInit = 'true';

        var preguntas = section.querySelectorAll('.faqs-pregunta');

        preguntas.forEach(function(pregunta) {
            pregunta.addEventListener('click', function(e) {
                e.preventDefault();
                var isActive = this.classList.contains('active');
                
                // Si ya está activa, no hacer nada
                if (isActive) return;

                // Cerrar todas
                section.querySelectorAll('.faqs-pregunta').forEach(function(p) {
                    p.classList.remove('active');
                });
                section.querySelectorAll('.faqs-respuesta').forEach(function(r) {
                    r.classList.remove('active');
                });
                section.querySelectorAll('.faqs-item').forEach(function(item) {
                    item.classList.remove('faqs-item--activo');
                });

                // Abrir la clicada
                this.classList.add('active');
                var faqId = this.getAttribute('data-faq-id');
                var respuesta = document.getElementById(faqId);
                if (respuesta) {
                    respuesta.classList.add('active');
                }
                this.closest('.faqs-item').classList.add('faqs-item--activo');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFaqs);
    } else {
        initFaqs();
    }
})();
</script>

<?php
}
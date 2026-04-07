<?php
// === BLOQUE FAQS SIMPLE - LOGO IZQUIERDA, BOTÓN +/- DERECHA ===

if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'grupo_faqs_bloque',
        'title' => 'FAQs',
        'fields' => array(
            array(
                'key' => 'faqs_titulo_bloque',
                'label' => 'Título del Bloque',
                'name' => 'faqs_titulo',
                'type' => 'text',
                'required' => 0,
                'instructions' => 'Título opcional del bloque FAQs',
                'placeholder' => 'Preguntas Frecuentes',
            ),
            array(
                'key' => 'faqs_items_repeater',
                'label' => 'Items FAQs',
                'name' => 'faqs_items',
                'type' => 'repeater',
                'instructions' => 'Añade las preguntas y respuestas',
                'button_label' => 'Añadir FAQ',
                'min' => 1,
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'faqs_logo',
                        'label' => 'Logo/Icono',
                        'name' => 'logo',
                        'type' => 'image',
                        'required' => 1,
                        'instructions' => 'Logo o icono para este FAQ. Recomendado: 50x50px',
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'faqs_pregunta',
                        'label' => 'Pregunta',
                        'name' => 'pregunta',
                        'type' => 'text',
                        'required' => 1,
                        'instructions' => 'La pregunta que se mostrará',
                        'placeholder' => 'Escribe la pregunta',
                    ),
                    array(
                        'key' => 'faqs_respuesta',
                        'label' => 'Respuesta',
                        'name' => 'respuesta',
                        'type' => 'textarea',
                        'required' => 1,
                        'instructions' => 'La respuesta a la pregunta',
                        'rows' => 5,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/faqs',
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
        'description' => 'Bloque de FAQs simples',
        'show_in_rest' => 0,
    ));

endif;

function faqs_bloque_acf()
{
    acf_register_block_type([
        'name'        => 'faqs',
        'title'       => __('FAQs', 'tictac'),
        'description' => __('Bloque simple de preguntas frecuentes', 'tictac'),
        'render_callback' => 'faqs_bloque_render',
        'mode'        => 'preview',
        'icon'        => 'format-status',
        'keywords'    => ['faqs', 'preguntas', 'frecuentes', 'accordion'],
    ]);
}

add_action('acf/init', 'faqs_bloque_acf');

function faqs_bloque_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('faqs-bloque', get_stylesheet_directory_uri() . '/assets/functions/blocks/faqs/faqs.min.css', array(), '1.0');
    }
}
add_action('wp_enqueue_scripts', 'faqs_bloque_scripts');

function faqs_bloque_render($block)
{
    $faqs_titulo = get_field('faqs_titulo');
    $faqs_items = get_field('faqs_items');
    
    if (!$faqs_items || empty($faqs_items)) {
        echo '<div class="faqs-error">Por favor añade al menos un FAQ</div>';
        return;
    }
    
    $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
    $unique_id = 'faqs-' . wp_unique_id('block-');
    
    $faqs_titulo = !empty($faqs_titulo) ? sanitize_text_field($faqs_titulo) : '';
?>
    <div class="<?php echo $block_class; ?> faqs-wrapper" data-faqs-id="<?php echo $unique_id; ?>">
        <?php if (!empty($faqs_titulo)) : ?>
            <h2 class="faqs-titulo">
                <?php echo $faqs_titulo; ?>
            </h2>
        <?php endif; ?>

        <div class="faqs-lista">
            <?php foreach ($faqs_items as $index => $item) :
                $logo = isset($item['logo']) && !empty($item['logo']) ? $item['logo'] : null;
                $pregunta = isset($item['pregunta']) ? sanitize_text_field($item['pregunta']) : '';
                $respuesta = isset($item['respuesta']) ? sanitize_textarea_field($item['respuesta']) : '';
                
                if (empty($pregunta) || empty($respuesta)) {
                    continue;
                }
                
                $item_id = 'faqs-item-' . $index . '-' . wp_unique_id('item-');
                $is_first = ($index === 0);
            ?>
                <div class="faqs-item <?php echo $is_first ? 'is-open' : ''; ?>" data-faqs-item-id="<?php echo $item_id; ?>">
                    <div class="faqs-header">
                        <div class="faqs-logo">
                            <?php if ($logo) : ?>
                                <img src="<?php echo esc_url($logo['url']); ?>" 
                                     alt="<?php echo esc_attr($logo['alt'] ?: 'Logo'); ?>" 
                                     class="faqs-logo-img"
                                     loading="lazy">
                            <?php endif; ?>
                        </div>

                        <div class="faqs-pregunta-wrapper">
                            <p class="faqs-pregunta">
                                <?php echo $pregunta; ?>
                            </p>
                        </div>

                        <button class="faqs-toggle-btn" data-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-label="Expandir respuesta">
                            <img class="faqs-toggle-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/+.svg" alt="Expandir">
                        </button>
                    </div>

                    <div class="faqs-respuesta-wrapper">
                        <p class="faqs-respuesta">
                            <?php echo $respuesta; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        (function() {
            const initFAQs = function() {
                const faqsId = '<?php echo $unique_id; ?>';
                const faqsWrapper = document.querySelector('[data-faqs-id="' + faqsId + '"]');
                
                if (!faqsWrapper) return;

                const items = faqsWrapper.querySelectorAll('.faqs-item');

                const updateHeight = function(respuestaWrapper) {
                    if (respuestaWrapper.parentElement.classList.contains('is-open')) {
                        respuestaWrapper.style.maxHeight = respuestaWrapper.scrollHeight + 'px';
                    }
                };

                items.forEach((item, index) => {
                    const btn = item.querySelector('.faqs-toggle-btn');
                    const icon = item.querySelector('.faqs-toggle-icon');
                    const respuestaWrapper = item.querySelector('.faqs-respuesta-wrapper');

                    if (!btn || !respuestaWrapper || !icon) return;

                    // Evitar listeners duplicados
                    if (btn.dataset.initialized === 'true') return;
                    btn.dataset.initialized = 'true';

                    // Calcular altura inicial para primer elemento
                    if (item.classList.contains('is-open')) {
                        // Usar requestAnimationFrame para asegurar que el contenido está renderizado
                        requestAnimationFrame(() => {
                            respuestaWrapper.style.maxHeight = respuestaWrapper.scrollHeight + 'px';
                            icon.src = '<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/-.svg';
                        });
                    }

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const isExpanded = btn.getAttribute('data-expanded') === 'true';

                        if (isExpanded) {
                            // CERRAR
                            btn.setAttribute('data-expanded', 'false');
                            btn.setAttribute('aria-expanded', 'false');
                            item.classList.remove('is-open');
                            icon.src = '<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/+.svg';
                            respuestaWrapper.style.maxHeight = '0';
                        } else {
                            // ABRIR
                            btn.setAttribute('data-expanded', 'true');
                            btn.setAttribute('aria-expanded', 'true');
                            item.classList.add('is-open');
                            icon.src = '<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/-.svg';
                            
                            // Permitir que el navegador calcule la altura del contenido
                            requestAnimationFrame(() => {
                                respuestaWrapper.style.maxHeight = respuestaWrapper.scrollHeight + 'px';
                            });
                        }
                    });

                    // Recalcular altura si el contenido cambia (responsive, imágenes cargadas, etc)
                    const resizeObserver = new ResizeObserver(() => {
                        if (item.classList.contains('is-open')) {
                            respuestaWrapper.style.maxHeight = respuestaWrapper.scrollHeight + 'px';
                        }
                    });
                    resizeObserver.observe(respuestaWrapper);
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initFAQs);
            } else {
                initFAQs();
            }

            // Reinicializar si el contenido se carga dinámicamente
            window.addEventListener('load', () => {
                setTimeout(initFAQs, 100);
            });
        })();
    </script>
<?php
}
?>
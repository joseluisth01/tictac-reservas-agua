<?php
// 1. Registrar el bloque
function garantias_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'garantias',
            'title'             => __('Garantías', 'tictac'),
            'description'       => __('Muestra iconos de garantías con acordeón desplegable', 'tictac'),
            'render_callback'   => 'garantias',
            'mode'              => 'edit',
            'icon'              => 'shield',
            'keywords'          => ['custom', 'garantias', 'iconos', 'acordeon'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'garantias_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_garantias',
        'title' => 'Garantías',
        'fields' => array(
            array(
                'key' => 'field_garantias_iconos',
                'label' => 'Iconos de Garantías',
                'name' => 'garantias_iconos',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Añadir Garantía',
                'sub_fields' => array(
                    array(
                        'key' => 'field_garantia_icono',
                        'label' => 'Icono',
                        'name' => 'icono',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                    ),
                    array(
                        'key' => 'field_garantia_texto',
                        'label' => 'Texto Principal',
                        'name' => 'texto',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => 'Título de la garantía',
                    ),
                    array(
                        'key' => 'field_garantia_subtexto',
                        'label' => 'Texto Desplegable (opcional)',
                        'name' => 'subtexto',
                        'type' => 'textarea',
                        'required' => 0,
                        'rows' => 3,
                        'instructions' => 'Texto que se mostrará al hacer click. Si se deja vacío, no habrá acordeón.',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/garantias',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos (usa los mismos estilos del bloque hero)
function garantias_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquehero', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloquehero/bloquehero.min.css');
    }
}
add_action('wp_enqueue_scripts', 'garantias_scripts');

// 4. Función de renderizado
function garantias($block)
{
    $iconos = get_field('garantias_iconos');

    if (!$iconos) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, añade al menos una garantía.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'garantias-' . uniqid();
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> bloquehero-iconos" id="<?php echo $block_id; ?>">
        <div class="">
            <div class="bloquehero-iconos-grid">
                <?php foreach ($iconos as $index => $icono): ?>
                    <div class="bloquehero-icono-item">
                        <div class="bloquehero-icono-header" data-accordion-trigger="<?php echo $index; ?>">
                            <img src="<?php echo esc_url($icono['icono']['url']); ?>"
                                alt="<?php echo esc_attr($icono['icono']['alt'] ?: $icono['texto']); ?>">
                            <p class="iconotitulo"><?= $icono['texto'] ?></p>
                        </div>
                        <?php if (!empty($icono['subtexto'])): ?>
                            <div class="bloquehero-icono-content" data-accordion-content="<?php echo $index; ?>">
                                <div class="bloquehero-icono-toggle-wrapper">
                                    <button class="bloquehero-icono-toggle" aria-label="Expandir información">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="bloquehero-icono-text">
                                    <p><?php echo nl2br(esc_html($icono['subtexto'])); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const blockId = '<?php echo esc_js($block_id); ?>';
            const container = document.getElementById(blockId);
            if (!container) return;

            const iconosItems = container.querySelectorAll('.bloquehero-icono-item');
            
            iconosItems.forEach(item => {
                const header = item.querySelector('.bloquehero-icono-header');
                const toggle = item.querySelector('.bloquehero-icono-toggle');
                
                if (header) {
                    // Click en el header completo
                    header.addEventListener('click', function() {
                        toggleAccordion(item);
                    });
                }
                
                if (toggle) {
                    // Click en la flecha
                    toggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        toggleAccordion(item);
                    });
                }
            });
            
            function toggleAccordion(item) {
                const wasActive = item.classList.contains('active');
                
                // Cerrar todos los acordeones
                iconosItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                });
                
                // Si no estaba activo, activarlo
                if (!wasActive) {
                    item.classList.add('active');
                }
            }
        })();
    </script>

<?php
}
<?php
// 1. Registrar el bloque
function productospacks_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'productospacks',
            'title'             => __('Productos Packs', 'tictac'),
            'description'       => __('Muestra packs de productos con precios y descripciones', 'tictac'),
            'render_callback'   => 'productospacks',
            'mode'              => 'edit',
            'icon'              => 'products',
            'keywords'          => ['custom', 'packs', 'productos', 'ofertas'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'productospacks_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_productospacks',
        'title' => 'Productos Packs',
        'fields' => array(
            array(
                'key' => 'field_productospacks_titulo',
                'label' => 'Título Principal (H2)',
                'name' => 'productospacks_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'AMUEBLAR PISO COMPLETO BARATO EN MÁLAGA',
            ),
            array(
                'key' => 'field_productospacks_parrafo',
                'label' => 'Párrafo Descriptivo (opcional)',
                'name' => 'productospacks_parrafo',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 3,
                'default_value' => '',
                'instructions' => 'Texto que aparecerá debajo del título principal. Dejar vacío si no se necesita.',
            ),
            array(
                'key' => 'field_productospacks_packs',
                'label' => 'Packs (Máximo 4)',
                'name' => 'productospacks_packs',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Añadir Pack',
                'sub_fields' => array(
                    array(
                        'key' => 'field_pack_imagen',
                        'label' => 'Imagen del Pack',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                    ),
                    array(
                        'key' => 'field_pack_etiqueta',
                        'label' => 'Etiqueta Superior (ej: LA OPCIÓN MÁS ECONÓMICA)',
                        'name' => 'etiqueta',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => 'OFERTA ESPECIAL',
                    ),
                    array(
                        'key' => 'field_pack_precio',
                        'label' => 'Precio (solo número, sin €)',
                        'name' => 'precio',
                        'type' => 'number',
                        'required' => 1,
                        'default_value' => 899.99,
                        'min' => 0,
                        'step' => 0.01,
                    ),
                    array(
                        'key' => 'field_pack_titulo',
                        'label' => 'Título del Pack (H3)',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => 'PACK AHORRO / ESTUDIANTES',
                    ),
                    array(
                        'key' => 'field_pack_descripcion',
                        'label' => 'Descripción',
                        'name' => 'descripcion',
                        'type' => 'textarea',
                        'required' => 1,
                        'rows' => 3,
                        'default_value' => 'Ideal para estudios o pisos de estudiantes. Muebles resistentes y funcionales al mejor precio.',
                    ),
                ),
            ),
            array(
                'key' => 'field_productospacks_boton_texto',
                'label' => 'Texto del Botón',
                'name' => 'productospacks_boton_texto',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'VER TODOS LOS PACKS',
            ),
            array(
                'key' => 'field_productospacks_boton_enlace',
                'label' => 'Enlace del Botón',
                'name' => 'productospacks_boton_enlace',
                'type' => 'link',
                'required' => 1,
                'return_format' => 'array',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/productospacks',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function productospacks_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('productospacks', get_stylesheet_directory_uri() . '/assets/functions/blocks/productospacks/productospacks.min.css');
    }
}
add_action('wp_enqueue_scripts', 'productospacks_scripts');

// 4. Función de renderizado
function productospacks($block)
{
    $titulo = get_field('productospacks_titulo');
    $parrafo = get_field('productospacks_parrafo');
    $packs = get_field('productospacks_packs');
    $boton_texto = get_field('productospacks_boton_texto');
    $boton_enlace = get_field('productospacks_boton_enlace');

    if (!$packs) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, añade al menos un pack.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'productospacks-' . uniqid();
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> productospacks" id="<?php echo $block_id; ?>">

        <!-- Título Principal -->
        <?php if ($titulo): ?>
            <h2 class="productospacks-titulo"><?= $titulo ?></h2>
        <?php endif; ?>

        <!-- Párrafo Descriptivo -->
        <?php if ($parrafo): ?>
            <div class="productospacks-parrafo"><?= $parrafo ?></div>
        <?php endif; ?>

        <!-- Grid de Packs -->
        <div class="productospacks-grid <?php echo (count($packs) === 1) ? 'productospacks-grid-single' : ''; ?>">

            <?php foreach ($packs as $pack):
                $imagen = $pack['imagen'];
                $etiqueta = $pack['etiqueta'];
                $precio = $pack['precio'];
                $titulo_pack = $pack['titulo'];
                $descripcion = $pack['descripcion'];
            ?>
                <div class="productospacks-card">
                    <!-- Imagen de fondo -->
                    <div class="productospacks-card-imagen" style="background-image: url('<?php echo esc_url($imagen['url']); ?>');">
                        <!-- Etiqueta superior izquierda -->
                        <?php if ($etiqueta): ?>
                            <div class="productospacks-etiqueta"><?php echo esc_html($etiqueta); ?></div>
                        <?php endif; ?>

                        <!-- Badge de precio -->
                        <?php if ($precio): ?>
                            <div class="productospacks-precio">
                                <span class="productospacks-precio-label">DESDE</span>
                                <span class="productospacks-precio-valor"><?php echo number_format($precio, 2, ',', '.'); ?> €</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido inferior -->
                    <div class="productospacks-card2-contenido">
                        <h3 class="productospacks-card-titulo"><?php echo esc_html($titulo_pack); ?></h3>
                    </div>
                    <div class="productospacks-card-contenido">
                        <p class="productospacks-card-descripcion"><?= $descripcion ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Botón Ver Todos -->
        <?php if ($boton_enlace && $boton_texto): ?>
            <div class="productospacks-boton-wrapper">
                <a href="<?php echo esc_url($boton_enlace['url']); ?>" 
                   class="productospacks-boton"
                   <?php if ($boton_enlace['target']): ?>target="<?php echo esc_attr($boton_enlace['target']); ?>"<?php endif; ?>>
                    <?php echo esc_html($boton_texto); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Script de Animación -->
    <script>
        (function() {
            const blockId = '<?php echo esc_js($block_id); ?>';
            const container = document.getElementById(blockId);
            if (!container) return;

            const cards = container.querySelectorAll('.productospacks-card');
            const totalCards = cards.length;
            
            if (totalCards === 0) return;

            const delayBetweenCards = 1500; // 1.5 segundos entre cada card (más lento)
            const delayAfterAll = 3000; // 3 segundos con todos activos (más lento)
            const delayBeforeRestart = 1000; // 1 segundo en estado original antes de reiniciar
            const initialDelay = 3000; // 3 segundos antes de comenzar la primera animación

            function activateCard(index) {
                if (index < totalCards) {
                    cards[index].classList.add('productospacks-card-active');
                }
            }

            function deactivateAllCards() {
                cards.forEach(card => {
                    card.classList.remove('productospacks-card-active');
                });
            }

            function startAnimation() {
                let index = 0;

                // Función para activar cards una por una
                function activateNext() {
                    if (index < totalCards) {
                        activateCard(index);
                        index++;
                        setTimeout(activateNext, delayBetweenCards);
                    } else {
                        // Todas las cards están activas, esperar y luego desactivar todas
                        setTimeout(() => {
                            deactivateAllCards();
                            // Esperar un momento en estado original antes de reiniciar
                            setTimeout(startAnimation, delayBeforeRestart);
                        }, delayAfterAll);
                    }
                }

                activateNext();
            }

            // Iniciar la animación después del delay inicial
            setTimeout(startAnimation, initialDelay);
        })();
    </script>
<?php
}
<?php
// 1. Registrar el bloque
function bloqueconexion_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloqueconexion',
            'title'             => __('Bloque Conexión', 'tictac'),
            'description'       => __('Bloque con slider de productos (izquierda) y lista de items (derecha)', 'tictac'),
            'render_callback'   => 'bloqueconexion',
            'mode'              => 'edit',
            'icon'              => 'layout',
            'keywords'          => ['custom', 'conexion', 'slider', 'productos', 'lista'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloqueconexion_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloqueconexion',
        'title' => 'Bloque Conexión',
        'fields' => array(
            // === BLOQUE IZQUIERDO (Slider de Productos) ===
            array(
                'key' => 'field_conexion_tab_izq',
                'label' => 'Bloque Izquierdo (Slider)',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_conexion_bgcolor_izq',
                'label' => 'Color de Fondo',
                'name' => 'conexion_bgcolor_izq',
                'type' => 'color_picker',
                'required' => 0,
                'default_value' => '#1E3562',
            ),
            array(
                'key' => 'field_conexion_subtitulo_izq',
                'label' => 'Subtítulo del Banner (H2)',
                'name' => 'conexion_subtitulo_izq',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_conexion_titulo_izq',
                'label' => 'Título Destacado',
                'name' => 'conexion_titulo_izq',
                'type' => 'text',
                'required' => 0,
                'instructions' => 'Se mostrará como texto destacado (strong) debajo del H2',
            ),
            array(
                'key' => 'field_conexion_descripcion_izq',
                'label' => 'Descripción',
                'name' => 'conexion_descripcion_izq',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_conexion_productos_cantidad',
                'label' => 'Cantidad de Productos en Slider',
                'name' => 'conexion_productos_cantidad',
                'type' => 'number',
                'required' => 0,
                'default_value' => 6,
                'min' => 2,
                'max' => 20,
            ),
            array(
                'key' => 'field_conexion_productos_visibles',
                'label' => 'Productos Visibles a la Vez',
                'name' => 'conexion_productos_visibles',
                'type' => 'number',
                'required' => 0,
                'default_value' => 3,
                'min' => 1,
                'max' => 6,
            ),
            array(
                'key' => 'field_conexion_boton_texto_izq',
                'label' => 'Texto del Botón',
                'name' => 'conexion_boton_texto_izq',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'VER TODOS LOS PRODUCTOS',
            ),
            array(
                'key' => 'field_conexion_boton_enlace_izq',
                'label' => 'Página de Destino',
                'name' => 'conexion_boton_enlace_izq',
                'type' => 'page_link',
                'required' => 0,
                'post_type' => array('page', 'product'),
                'allow_null' => 1,
                'multiple' => 0,
            ),

            // === BLOQUE DERECHO (Lista de Items) ===
            array(
                'key' => 'field_conexion_tab_der',
                'label' => 'Bloque Derecho (Lista)',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_conexion_etiqueta_der',
                'label' => 'Etiqueta',
                'name' => 'conexion_etiqueta_der',
                'type' => 'text',
                'required' => 0,
            ),
            array(
                'key' => 'field_conexion_titulo_der',
                'label' => 'Título (H2)',
                'name' => 'conexion_titulo_der',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_conexion_parrafo_der',
                'label' => 'Párrafo',
                'name' => 'conexion_parrafo_der',
                'type' => 'wysiwyg',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_conexion_repetidor_der',
                'label' => 'Lista de Items',
                'name' => 'conexion_repetidor_der',
                'type' => 'repeater',
                'required' => 1,
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Añadir Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_conexion_lista_imagen',
                        'label' => 'Icono',
                        'name' => 'imagen',
                        'type' => 'image',
                        'required' => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_conexion_lista_titulo',
                        'label' => 'Título',
                        'name' => 'titulo',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_conexion_lista_texto',
                        'label' => 'Texto',
                        'name' => 'texto',
                        'type' => 'text',
                        'required' => 0,
                    ),
                ),
            ),
            array(
                'key' => 'field_conexion_imagen_fondo',
                'label' => 'Imagen de Fondo del Bloque Derecho',
                'name' => 'conexion_imagen_fondo',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'instructions' => 'Imagen de fondo para el bloque derecho',
            ),
            array(
                'key' => 'field_conexion_boton_texto_der',
                'label' => 'Texto del Botón',
                'name' => 'conexion_boton_texto_der',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'CONTACTAR',
            ),
            array(
                'key' => 'field_conexion_boton_icono',
                'label' => 'Icono del Botón',
                'name' => 'conexion_boton_icono',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
                'instructions' => 'Imagen/icono que aparecerá dentro del enlace del botón',
            ),
            array(
                'key' => 'field_conexion_boton_enlace_der',
                'label' => 'Página de Destino',
                'name' => 'conexion_boton_enlace_der',
                'type' => 'page_link',
                'required' => 0,
                'post_type' => array('page'),
                'allow_null' => 0,
                'multiple' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloqueconexion',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloqueconexion_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloqueconexion', get_stylesheet_directory_uri() . '/assets/functions/blocks/conexion/conexion.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloqueconexion_scripts');

// 4. Función para obtener productos aleatorios
function bloqueconexion_get_random_products($cantidad = 6)
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $cantidad,
        'orderby' => 'rand',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    );

    $products = get_posts($args);
    $product_images = array();

    foreach ($products as $product) {
        $thumbnail_id = get_post_thumbnail_id($product->ID);
        if ($thumbnail_id) {
            $image_url = wp_get_attachment_image_url($thumbnail_id, 'large');
            $image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            if ($image_url) {
                $product_images[] = array(
                    'url' => $image_url,
                    'alt' => $image_alt ?: $product->post_title,
                    'title' => $product->post_title
                );
            }
        }
    }

    return $product_images;
}

// 5. Función de renderizado
function bloqueconexion($block)
{
    // Campos del bloque izquierdo
    $bgcolor_izq = get_field('conexion_bgcolor_izq') ?: '#1E3562';
    $subtitulo_izq = get_field('conexion_subtitulo_izq');
    $titulo_izq = get_field('conexion_titulo_izq');
    $descripcion_izq = get_field('conexion_descripcion_izq');
    $productos_cantidad = get_field('conexion_productos_cantidad') ?: 6;
    $productos_visibles = get_field('conexion_productos_visibles') ?: 3;
    $boton_texto_izq = get_field('conexion_boton_texto_izq');
    $boton_enlace_izq = get_field('conexion_boton_enlace_izq');

    // Campos del bloque derecho
    $etiqueta_der = get_field('conexion_etiqueta_der');
    $titulo_der = get_field('conexion_titulo_der');
    $parrafo_der = get_field('conexion_parrafo_der');
    $repetidor_der = get_field('conexion_repetidor_der');
    $imagen_fondo = get_field('conexion_imagen_fondo');
    $boton_texto_der = get_field('conexion_boton_texto_der');
    $boton_icono = get_field('conexion_boton_icono');
    $boton_enlace_der = get_field('conexion_boton_enlace_der');

    // Obtener productos
    $productos = bloqueconexion_get_random_products($productos_cantidad);

    // Generar ID único para este bloque
    $block_id = 'bloqueconexion-' . uniqid();
?>

<section class="conexion-section <?php if (isset($block['className'])) echo esc_attr($block['className']); ?>" id="<?php echo $block_id; ?>">
    <div class="containerancho">
        <div class="conexion-wrapper">
            
            <!-- BLOQUE IZQUIERDO - Slider de Productos -->
            <div class="conexion-izquierda" style="background-color: <?php echo esc_attr($bgcolor_izq); ?>;">
                <div class="conexion-secundario-contenido">
                    <?php if ($titulo_izq): ?>
                        <div class="text-lg-left">
                            <span class="conexion-secundario-titulo px-4 px-lg-5 py-2">
                                <?php echo esc_html($titulo_izq); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($subtitulo_izq): ?>
                        <h2 class="conexion-secundario-subtitulo my-3"><?php echo esc_html($subtitulo_izq); ?></h2>
                    <?php endif; ?>
                
                    <?php if ($descripcion_izq): ?>
                        <p class="conexion-secundario-descripcion"><?php echo nl2br(esc_html($descripcion_izq)); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($productos)): ?>
                        <!-- Slider de Productos -->
                        <div class="conexion-productos-slider my-3" data-visibles="<?php echo esc_attr($productos_visibles); ?>">
                            <div class="conexion-productos-track">
                                <?php foreach ($productos as $index => $producto): ?>
                                    <div class="conexion-producto-slide">
                                        <img src="<?php echo esc_url($producto['url']); ?>"
                                            alt="<?php echo esc_attr($producto['alt']); ?>">
                                        <div class="conexion-producto-overlay">
                                            <div class="d-flex flex-row justify-content-around col-12">
                                                <p class="conexion-producto-nombre col-8 mb-3"><?php echo esc_html($producto['title']); ?></p>
                                                <div class="conexion-producto-flechas">
                                                    <button class="conexion-flecha-prev" aria-label="Anterior">
                                                        <svg width="21" height="27" viewBox="0 0 21 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g filter="url(#filter0_d_2010_109)">
                                                                <path d="M14.9094 18.0335C14.7995 18.2374 14.6458 18.416 14.4587 18.5572C14.2715 18.6985 14.0553 18.7992 13.8247 18.8524C13.594 18.9056 13.3542 18.9102 13.1216 18.8657C12.889 18.8213 12.6689 18.7289 12.4762 18.5949C12.358 18.5083 12.2467 18.4132 12.1433 18.3103L5.58093 11.9506C5.39822 11.7926 5.25199 11.5989 5.15187 11.3822C5.05175 11.1655 5 10.9307 5 10.6932C5 10.4557 5.05175 10.221 5.15187 10.0043C5.25199 9.78756 5.39822 9.59386 5.58093 9.43587L11.2874 3.89895C11.6044 3.59904 11.9056 3.29142 12.2226 2.99919C12.4272 2.79139 12.6854 2.64034 12.9703 2.56167C13.2552 2.48299 13.5566 2.47954 13.8434 2.55165C14.1301 2.62376 14.3919 2.76883 14.6016 2.97187C14.8112 3.17492 14.9613 3.4286 15.0362 3.7067C15.1103 3.95952 15.1165 4.22657 15.0541 4.48236C14.9916 4.73815 14.8627 4.97415 14.6795 5.16783C14.41 5.45237 14.1247 5.72153 13.8473 5.99068L9.24255 10.4587C8.99686 10.6971 8.99686 10.6971 9.24255 10.9278L14.5289 16.0571C14.8121 16.2971 15.0036 16.6227 15.0726 16.9813C15.1416 17.3398 15.0841 17.7105 14.9094 18.0335Z" fill="white"/>
                                                            </g>
                                                            <defs>
                                                                <filter id="filter0_d_2010_109" x="0" y="0" width="20.1011" height="26.396" filterUnits="userSpaceOnUse" color-Montserratpolation-filters="sRGB">
                                                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                                                    <feOffset dy="2.5"/>
                                                                    <feGaussianBlur stdDeviation="2.5"/>
                                                                    <feColorMatrix type="matrix" values="0 0 0 0 0.184314 0 0 0 0 0.180392 0 0 0 0 0.176471 0 0 0 0.6 0"/>
                                                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2010_109"/>
                                                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2010_109" result="shape"/>
                                                                </filter>
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                    <button class="conexion-flecha-next" aria-label="Siguiente">
                                                        <svg width="21" height="27" viewBox="0 0 21 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g filter="url(#filter0_d_2010_110)">
                                                                <path d="M5.19195 3.3612C5.30199 3.15764 5.45593 2.9793 5.64336 2.83824C5.83079 2.69718 6.04734 2.59669 6.27838 2.54355C6.50942 2.49041 6.74955 2.48586 6.98256 2.53022C7.21557 2.57457 7.43602 2.6668 7.62901 2.80066C7.74741 2.88708 7.85886 2.98205 7.96242 3.08476L14.5353 9.4349C14.7183 9.59265 14.8648 9.78605 14.9651 10.0024C15.0654 10.2188 15.1172 10.4532 15.1172 10.6903C15.1172 10.9275 15.0654 11.1619 14.9651 11.3783C14.8648 11.5946 14.7183 11.788 14.5353 11.9458L8.81975 17.4743C8.50222 17.7738 8.20056 18.0809 7.88303 18.3727C7.67803 18.5802 7.4195 18.731 7.13412 18.8096C6.84873 18.8881 6.54685 18.8916 6.25963 18.8196C5.9724 18.7476 5.71025 18.6027 5.50023 18.4C5.29022 18.1973 5.13995 17.944 5.06495 17.6663C4.99064 17.4138 4.98445 17.1472 5.04698 16.8918C5.10952 16.6364 5.2387 16.4007 5.42217 16.2074C5.69207 15.9233 5.97785 15.6545 6.25569 15.3858L10.8678 10.9245C11.1139 10.6865 11.1139 10.6865 10.8678 10.4562L5.573 5.33456C5.28941 5.09494 5.09756 4.76988 5.02843 4.41184C4.95929 4.0538 5.0169 3.68368 5.19195 3.3612Z" fill="white"/>
                                                            </g>
                                                            <defs>
                                                                <filter id="filter0_d_2010_110" x="0" y="0" width="20.1172" height="26.3712" filterUnits="userSpaceOnUse" color-Montserratpolation-filters="sRGB">
                                                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                                                    <feOffset dy="2.5"/>
                                                                    <feGaussianBlur stdDeviation="2.5"/>
                                                                    <feColorMatrix type="matrix" values="0 0 0 0 0.184314 0 0 0 0 0.180392 0 0 0 0 0.176471 0 0 0 0.6 0"/>
                                                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2010_110"/>
                                                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2010_110" result="shape"/>
                                                                </filter>
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($boton_texto_izq && $boton_enlace_izq): ?>
                        <a href="<?php echo esc_url($boton_enlace_izq); ?>" class="boton-secundario">
                            <?php echo esc_html($boton_texto_izq); ?>
                            <img src="<?= wp_make_link_relative(content_url('uploads/2026/01/Vector-55.svg')); ?>" alt="">
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- BLOQUE DERECHO - Lista de Items -->
            <div class="conexion-derecha" <?php if ($imagen_fondo): ?>style="background-image: url('<?php echo esc_url($imagen_fondo['url']); ?>');"<?php endif; ?>>
                <?php if ($etiqueta_der): ?>
                    <span class="conexion-etiqueta"><?php echo esc_html($etiqueta_der); ?></span>
                <?php endif; ?>

                <?php if ($titulo_der): ?>
                    <h2 class="conexion-titulo"><?php echo esc_html($titulo_der); ?></h2>
                <?php endif; ?>

                <?php if ($parrafo_der): ?>
                    <p class="conexion-parrafo"><?= $parrafo_der ?></p>
                <?php endif; ?>

                <?php if ($repetidor_der && count($repetidor_der) > 0): ?>
                    <div class="conexion-lista">
                        <?php foreach ($repetidor_der as $item): 
                            $imagen = $item['imagen'];
                            $titulo_item = $item['titulo'];
                            $texto_item = $item['texto'];
                        ?>
                            <div class="lista-item">
                                <?php if ($imagen): ?>
                                    <div class="lista-icono">
                                        <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php echo esc_attr($imagen['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="lista-contenido">
                                    <?php if ($titulo_item): ?>
                                        <p class="lista-titulo"><?php echo esc_html($titulo_item); ?></p>
                                    <?php endif; ?>
                                    <?php if ($texto_item): ?>
                                        <p class="lista-texto"><?php echo esc_html($texto_item); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($boton_texto_der && $boton_enlace_der): ?>
                    <div class="conexion-cta">
                        <a href="<?php echo esc_url($boton_enlace_der); ?>" class="btn-contactar">
                            <?php echo esc_html($boton_texto_der); ?>
                            <?php if ($boton_icono): ?>
                                <img src="<?php echo esc_url($boton_icono['url']); ?>" alt="<?php echo esc_attr($boton_icono['alt']); ?>" class="btn-icono">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<script>
(function() {
    const blockId = '<?php echo esc_js($block_id); ?>';
    const bloque = document.getElementById(blockId);
    if (!bloque) return;

    // === SLIDER DE PRODUCTOS ===
    const productosSlider = bloque.querySelector('.conexion-productos-slider');
    if (productosSlider) {
        const track = productosSlider.querySelector('.conexion-productos-track');
        const slides = productosSlider.querySelectorAll('.conexion-producto-slide');
        const visibles = parseInt(productosSlider.getAttribute('data-visibles')) || 3;

        let currentIndex = 0;
        const totalSlides = slides.length;
        const gapSize = 10;
        
        function getVisiblesActual() {
            const isMobile = window.innerWidth <= 600;
            const isTablet = window.innerWidth <= 800;
            if (isMobile) return 1;
            if (isTablet) return Math.min(2, visibles);
            return visibles;
        }

        function updateSlider() {
            const visiblesActual = getVisiblesActual();
            const maxIndex = Math.max(0, totalSlides - visiblesActual);
            
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            
            const containerWidth = productosSlider.offsetWidth;
            const totalGaps = visiblesActual - 1;
            const slideWidth = (containerWidth - (totalGaps * gapSize)) / visiblesActual;
            const offset = currentIndex * (slideWidth + gapSize);
            track.style.transform = `translateX(-${offset}px)`;

            const allPrevBtns = productosSlider.querySelectorAll('.conexion-flecha-prev');
            const allNextBtns = productosSlider.querySelectorAll('.conexion-flecha-next');
            
            allPrevBtns.forEach(btn => {
                btn.disabled = currentIndex === 0;
                btn.style.opacity = currentIndex === 0 ? '0.4' : '1';
            });
            
            allNextBtns.forEach(btn => {
                btn.disabled = currentIndex >= maxIndex;
                btn.style.opacity = currentIndex >= maxIndex ? '0.4' : '1';
            });
        }

        function updateSlideWidths() {
            const visiblesActual = getVisiblesActual();
            const containerWidth = productosSlider.offsetWidth;
            const totalGaps = visiblesActual - 1;
            const slideWidth = (containerWidth - (totalGaps * gapSize)) / visiblesActual;

            slides.forEach(slide => {
                slide.style.flex = `0 0 ${slideWidth}px`;
                slide.style.maxWidth = `${slideWidth}px`;
            });
        }

        productosSlider.addEventListener('click', function(e) {
            const prevBtn = e.target.closest('.conexion-flecha-prev');
            const nextBtn = e.target.closest('.conexion-flecha-next');
            
            if (prevBtn && currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
            
            if (nextBtn) {
                const visiblesActual = getVisiblesActual();
                const maxIndex = Math.max(0, totalSlides - visiblesActual);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            }
        });

        updateSlideWidths();
        updateSlider();

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                updateSlideWidths();
                updateSlider();
            }, 100);
        });
    }
})();
</script>

<?php
}
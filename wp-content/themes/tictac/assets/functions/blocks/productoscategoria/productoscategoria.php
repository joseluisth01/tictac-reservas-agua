<?php
// 1. Registrar el bloque
function productoscategoria_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'productoscategoria',
            'title'             => __('Productos por Categoría', 'tictac'),
            'description'       => __('Carrusel de productos de una categoría específica', 'tictac'),
            'render_callback'   => 'productoscategoria',
            'mode'              => 'edit',
            'icon'              => 'category',
            'keywords'          => ['custom', 'productos', 'categoría', 'carrusel'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'productoscategoria_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_productoscategoria',
        'title' => 'Productos por Categoría',
        'fields' => array(
            array(
                'key' => 'field_productoscategoria_categoria',
                'label' => 'Seleccionar Categoría',
                'name' => 'productoscategoria_categoria',
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'field_type' => 'select',
                'return_format' => 'id',
                'required' => 1,
            ),
            array(
                'key' => 'field_productoscategoria_titulo',
                'label' => 'Título Principal (H2)',
                'name' => 'productoscategoria_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'NUESTROS PRODUCTOS',
            ),
            array(
                'key' => 'field_productoscategoria_tituloh3',
                'label' => 'Título Principal (H3)',
                'name' => 'productoscategoria_tituloh3',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'NUESTROS PRODUCTOS',
            ),
            array(
                'key' => 'field_productoscategoria_descripcion',
                'label' => 'Descripción',
                'name' => 'productoscategoria_descripcion',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 3,
                'default_value' => 'Descubre nuestra selección de productos de alta calidad.',
            ),
            array(
                'key' => 'field_productoscategoria_productos_por_pagina',
                'label' => 'Productos a mostrar',
                'name' => 'productoscategoria_productos_por_pagina',
                'type' => 'number',
                'required' => 1,
                'default_value' => 8,
                'min' => 1,
                'max' => 20,
            ),
            array(
                'key' => 'field_productoscategoria_productos_por_vista',
                'label' => 'Productos visibles en desktop',
                'name' => 'productoscategoria_productos_por_vista',
                'type' => 'number',
                'required' => 1,
                'default_value' => 4,
                'min' => 1,
                'max' => 6,
            ),
            array(
                'key' => 'field_flecha_izquierda_categoria',
                'label' => 'Flecha Izquierda',
                'name' => 'flecha_izquierda_categoria',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_flecha_derecha_categoria',
                'label' => 'Flecha Derecha',
                'name' => 'flecha_derecha_categoria',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/productoscategoria',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function productoscategoria_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('productoscategoria', get_stylesheet_directory_uri() . '/assets/functions/blocks/productoscategoria/productoscategoria.min.css');
    }
}
add_action('wp_enqueue_scripts', 'productoscategoria_scripts');

// 4. Función de renderizado
function productoscategoria($block)
{
    $categoria_id = get_field('productoscategoria_categoria');
    $titulo = get_field('productoscategoria_titulo');
    $titulo2 = get_field('productoscategoria_tituloh3');
    $descripcion = get_field('productoscategoria_descripcion');
    $productos_por_pagina = get_field('productoscategoria_productos_por_pagina') ?: 8;
    $productos_por_vista = get_field('productoscategoria_productos_por_vista') ?: 4;
    $flecha_izq = get_field('flecha_izquierda_categoria');
    $flecha_der = get_field('flecha_derecha_categoria');

    // Validar que se haya seleccionado una categoría
    if (!$categoria_id) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">Por favor, selecciona una categoría de productos.</p>';
        return;
    }

    // Generar ID único para este bloque
    $block_id = 'productoscategoria-' . uniqid();

    // Consulta WooCommerce: productos de la categoría seleccionada
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $productos_por_pagina,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categoria_id,
            ),
        ),
        'orderby' => 'date',
        'order'   => 'DESC'
    );

    $productos = new WP_Query($args);

    if (!$productos->have_posts()) {
        echo '<p style="padding: 20px; text-align: center;">No se encontraron productos en esta categoría.</p>';
        return;
    }
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> productoscategoria" id="<?php echo $block_id; ?>">

        <!-- Header -->
        <div class="productoscategoria-header">
            <h2 class="productoscategoria-titulo"><?= $titulo ?></h2>
            <h3 class="productoscategoria-tituloh3"><?= $titulo2 ?></h3>
            <?php if ($descripcion): ?>
                <p class="productoscategoria-descripcion"><?php echo esc_html($descripcion); ?></p>
            <?php endif; ?>
        </div>

        <!-- Carrusel de Productos -->
        <div class="productoscategoria-carrusel-wrapper">
            <!-- Contenedor del Carrusel -->
            <div class="productoscategoria-carrusel">
                <div class="productoscategoria-track" data-productos-por-vista="<?php echo $productos_por_vista; ?>">
                    <?php while ($productos->have_posts()) : $productos->the_post();
                        global $product;

                        // Datos del producto
                        $precio_regular = $product->get_regular_price();
                        $precio_oferta = $product->get_sale_price();
                        $precio_actual = $product->get_price();
                        $imagen = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $enlace = get_permalink();

                        // Calcular descuento solo si hay precio de oferta
                        $tiene_descuento = false;
                        $descuento = 0;
                        if ($precio_regular && $precio_oferta) {
                            $descuento = round((($precio_regular - $precio_oferta) / $precio_regular) * 100);
                            $tiene_descuento = true;
                        }

                        // Verificar envío gratis y montaje incluido
                        $envio_gratis = get_post_meta(get_the_ID(), '_envio_gratis', true);
                        $montaje_incluido = get_post_meta(get_the_ID(), '_montaje_incluido', true);
                    ?>
                        <div class="productoscategoria-card">
                            <!-- Badge Oferta (solo si tiene descuento) -->
                            <?php if ($tiene_descuento): ?>
                                <div class="productoscategoria-badge-oferta">OFERTA</div>
                            <?php endif; ?>

                            <!-- Imagen -->
                            <a href="<?php echo esc_url($enlace); ?>" class="productoscategoria-imagen">
                                <?php if ($imagen): ?>
                                    <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php endif; ?>
                            </a>

                            <!-- Contenido -->
                            <div class="productoscategoria-contenido">
                                <h3 class="productoscategoria-nombre">
                                    <a href="<?php echo esc_url($enlace); ?>"><?php the_title(); ?></a>
                                </h3>

                                <!-- Precios -->
                                <div class="productoscategoria-precios">
                                    <?php if ($tiene_descuento): ?>
                                        <span class="productoscategoria-precio-antiguo">
                                            <?php echo wc_price($precio_regular); ?>
                                        </span>
                                        <span class="productoscategoria-descuento">-<?php echo $descuento; ?>%</span>
                                    <?php endif; ?>
                                    <span class="productoscategoria-precio-actual <?php echo $tiene_descuento ? 'con-descuento' : ''; ?>">
                                        <?php echo wc_price($precio_actual); ?>
                                    </span>
                                </div>

                                <?php if ($precio_actual >= 200): ?>
                                    <div class="bloquetienda-badges">
                                        <span class="bloquetienda-badge">ENVÍO Y MONTAJE GRATIS EN MÁLAGA*</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Botón -->
                                <a href="<?php echo esc_url($enlace); ?>" class="productoscategoria-boton">
                                    DESCUBRIR MÁS
                                </a>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>

        <!-- Paginación con Puntitos (Solo móvil) -->
        <div class="productoscategoria-paginacion"></div>

        <!-- Controles de Navegación -->
        <div class="productoscategoria-controles">
            <!-- Flecha Izquierda -->
            <button class="productoscategoria-prev" aria-label="Anterior">
                <?php if ($flecha_izq): ?>
                    <img src="<?php echo esc_url($flecha_izq['url']); ?>"
                        alt="<?php echo esc_attr($flecha_izq['alt'] ?: 'Anterior'); ?>">
                <?php else: ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
            </button>

            <!-- Flecha Derecha -->
            <button class="productoscategoria-next" aria-label="Siguiente">
                <?php if ($flecha_der): ?>
                    <img src="<?php echo esc_url($flecha_der['url']); ?>"
                        alt="<?php echo esc_attr($flecha_der['alt'] ?: 'Siguiente'); ?>">
                <?php else: ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
            </button>
        </div>
    </div>

    <script>
        (function() {
            const blockId = '<?php echo esc_js($block_id); ?>';
            const container = document.getElementById(blockId);
            if (!container) return;

            const track = container.querySelector('.productoscategoria-track');
            const prevBtn = container.querySelector('.productoscategoria-prev');
            const nextBtn = container.querySelector('.productoscategoria-next');
            const cards = track.querySelectorAll('.productoscategoria-card');
            const paginacionContainer = container.querySelector('.productoscategoria-paginacion');

            const productosPorVista = parseInt(track.dataset.productosPorVista) || 4;
            let posicionActual = 0;

            // Obtener productos visibles según el ancho de pantalla
            function getProductosVisibles() {
                if (window.innerWidth <= 1100) return 1; // Móvil/Tablet
                return productosPorVista; // Desktop
            }

            // Crear puntitos de paginación
            function crearPaginacion() {
                paginacionContainer.innerHTML = '';
                
                // Solo crear puntitos en móvil
                if (window.innerWidth <= 1100) {
                    const totalPuntitos = cards.length;
                    
                    for (let i = 0; i < totalPuntitos; i++) {
                        const dot = document.createElement('button');
                        dot.classList.add('productoscategoria-dot');
                        dot.setAttribute('aria-label', `Ir a producto ${i + 1}`);
                        
                        if (i === 0) {
                            dot.classList.add('active');
                        }
                        
                        dot.addEventListener('click', () => {
                            posicionActual = i;
                            actualizarCarrusel();
                        });
                        
                        paginacionContainer.appendChild(dot);
                    }
                }
            }

            // Actualizar puntito activo
            function actualizarPaginacion() {
                const dots = paginacionContainer.querySelectorAll('.productoscategoria-dot');
                dots.forEach((dot, index) => {
                    if (index === posicionActual) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Calcular el desplazamiento correcto
            function actualizarCarrusel() {
                const isMobile = window.innerWidth <= 1100;
                
                if (isMobile) {
                    // En móvil: sin gap, movimiento exacto del 100%
                    track.style.transform = `translateX(-${posicionActual * 100}%)`;
                } else {
                    // En desktop: con gap, cálculo basado en ancho de card + gap
                    const cardWidth = cards[0].offsetWidth;
                    const gap = 20; // El gap definido en CSS
                    const desplazamiento = (cardWidth + gap) * posicionActual;
                    track.style.transform = `translateX(-${desplazamiento}px)`;
                }
                
                // Deshabilitar botones si es necesario
                prevBtn.disabled = posicionActual === 0;
                nextBtn.disabled = posicionActual >= cards.length - getProductosVisibles();

                // Actualizar paginación
                actualizarPaginacion();
            }

            // Navegación
            prevBtn.addEventListener('click', () => {
                if (posicionActual > 0) {
                    posicionActual--;
                    actualizarCarrusel();
                }
            });

            nextBtn.addEventListener('click', () => {
                const maxPosicion = cards.length - getProductosVisibles();
                if (posicionActual < maxPosicion) {
                    posicionActual++;
                    actualizarCarrusel();
                }
            });

            // Recalcular en resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    posicionActual = 0; // Reset al cambiar de tamaño
                    crearPaginacion();
                    actualizarCarrusel();
                }, 250);
            });

            // Inicializar
            crearPaginacion();
            actualizarCarrusel();
        })();
    </script>

<?php
}
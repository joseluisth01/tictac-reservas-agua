<?php
// 1. Registrar el bloque
function productosofertas_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'productosofertas',
            'title'             => __('Productos en Oferta', 'tictac'),
            'description'       => __('Carrusel de productos en oferta', 'tictac'),
            'render_callback'   => 'productosofertas',
            'mode'              => 'edit',
            'icon'              => 'cart',
            'keywords'          => ['custom', 'productos', 'ofertas', 'liquidación', 'carrusel'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'productosofertas_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_productosofertas',
        'title' => 'Productos en Oferta',
        'fields' => array(
            array(
                'key' => 'field_productosofertas_titulo',
                'label' => 'Título Principal (H2)',
                'name' => 'productosofertas_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'LIQUIDACIÓN Y OFERTAS FACTORY EN MÁLAGA',
            ),
            array(
                'key' => 'field_productosofertas_descripcion',
                'label' => 'Descripción',
                'name' => 'productosofertas_descripcion',
                'type' => 'textarea',
                'required' => 1,
                'rows' => 3,
                'default_value' => 'Descubre nuestra selección de muebles baratos outlet y oportunidades únicas de muebles liquidación por cierre de stock. Unidades limitadas con entrega inmediata. ¡Corre que vuelan!',
            ),
            array(
                'key' => 'field_productosofertas_productos_por_pagina',
                'label' => 'Productos a mostrar',
                'name' => 'productosofertas_productos_por_pagina',
                'type' => 'number',
                'required' => 1,
                'default_value' => 8,
                'min' => 1,
                'max' => 20,
            ),
            array(
                'key' => 'field_productosofertas_productos_por_vista',
                'label' => 'Productos visibles en desktop',
                'name' => 'productosofertas_productos_por_vista',
                'type' => 'number',
                'required' => 1,
                'default_value' => 4,
                'min' => 1,
                'max' => 6,
            ),
            array(
                'key' => 'field_flecha_izquierda_ofertas',
                'label' => 'Flecha Izquierda',
                'name' => 'flecha_izquierda_ofertas',
                'type' => 'image',
                'required' => 0,
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_flecha_derecha_ofertas',
                'label' => 'Flecha Derecha',
                'name' => 'flecha_derecha_ofertas',
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
                    'value' => 'acf/productosofertas',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function productosofertas_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('productosofertas', get_stylesheet_directory_uri() . '/assets/functions/blocks/productosofertas/productosofertas.min.css');
    }
}
add_action('wp_enqueue_scripts', 'productosofertas_scripts');

// 4. Función de renderizado
function productosofertas($block)
{
    $titulo = get_field('productosofertas_titulo');
    $descripcion = get_field('productosofertas_descripcion');
    $productos_por_pagina = get_field('productosofertas_productos_por_pagina') ?: 8;
    $productos_por_vista = get_field('productosofertas_productos_por_vista') ?: 4;
    $flecha_izq = get_field('flecha_izquierda_ofertas');
    $flecha_der = get_field('flecha_derecha_ofertas');

    // Generar ID único para este bloque
    $block_id = 'productosofertas-' . uniqid();

    // Consulta WooCommerce: productos en oferta
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $productos_por_pagina,
        'post_status'    => 'publish',
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC'
            ),
        ),
        'orderby' => 'date',
        'order'   => 'DESC'
    );

    $productos = new WP_Query($args);

    if (!$productos->have_posts()) return;
?>
    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> productosofertas" id="<?php echo $block_id; ?>">

        <!-- Header -->
        <div class="productosofertas-header">
            <h2 class="productosofertas-titulo"><?= $titulo ?></h2>
            <?php if ($descripcion): ?>
                <p class="productosofertas-descripcion"><?php echo esc_html($descripcion); ?></p>
            <?php endif; ?>
        </div>

        <!-- Carrusel de Productos -->
        <div class="productosofertas-carrusel-wrapper">
            <!-- Contenedor del Carrusel -->
            <div class="productosofertas-carrusel">
                <div class="productosofertas-track" data-productos-por-vista="<?php echo $productos_por_vista; ?>">
                    <?php while ($productos->have_posts()) : $productos->the_post();
                        global $product;

                        // Datos del producto
                        $precio_regular = $product->get_regular_price();
                        $precio_oferta = $product->get_sale_price();
                        $precio_actual = $product->get_price();
                        $imagen = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $enlace = get_permalink();

                        // Calcular descuento
                        $descuento = 0;
                        if ($precio_regular && $precio_oferta) {
                            $descuento = round((($precio_regular - $precio_oferta) / $precio_regular) * 100);
                        }

                        // Verificar envío gratis y montaje incluido (personalizar según tus meta fields)
                        $envio_gratis = get_post_meta(get_the_ID(), '_envio_gratis', true);
                        $montaje_incluido = get_post_meta(get_the_ID(), '_montaje_incluido', true);
                    ?>
                        <div class="productosofertas-card">
                            <!-- Badge Oferta -->
                            <div class="productosofertas-badge-oferta">OFERTA</div>

                            <!-- Imagen -->
                            <a href="<?php echo esc_url($enlace); ?>" class="productosofertas-imagen">
                                <?php if ($imagen): ?>
                                    <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php endif; ?>
                            </a>

                            <!-- Contenido -->
                            <div class="productosofertas-contenido">
                                <h3 class="productosofertas-nombre">
                                    <a href="<?php echo esc_url($enlace); ?>"><?php the_title(); ?></a>
                                </h3>

                                <!-- Precios -->
                                <div class="productosofertas-precios">
                                    <?php if ($precio_regular && $precio_oferta): ?>
                                        <span class="productosofertas-precio-antiguo">
                                            <?php echo wc_price($precio_regular); ?>
                                        </span>
                                        <span class="productosofertas-descuento">-<?php echo $descuento; ?>%</span>
                                    <?php endif; ?>
                                    <span class="productosofertas-precio-actual">
                                        <?php echo wc_price($precio_actual); ?>
                                    </span>
                                </div>

                                <!-- Badges -->
                                <?php if ($precio_actual >= 200): ?>
                                    <div class="bloquetienda-badges">
                                        <span class="bloquetienda-badge">ENVÍO Y MONTAJE GRATIS EN MÁLAGA*</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Botón -->
                                <a href="<?php echo esc_url($enlace); ?>" class="productosofertas-boton">
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
        <div class="productosofertas-paginacion"></div>

        <!-- Controles de Navegación -->
        <div class="productosofertas-controles">
            <!-- Flecha Izquierda -->
            <button class="productosofertas-prev" aria-label="Anterior">
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
            <button class="productosofertas-next" aria-label="Siguiente">
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

            const track = container.querySelector('.productosofertas-track');
            const prevBtn = container.querySelector('.productosofertas-prev');
            const nextBtn = container.querySelector('.productosofertas-next');
            const cards = track.querySelectorAll('.productosofertas-card');
            const paginacionContainer = container.querySelector('.productosofertas-paginacion');

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
                        dot.classList.add('productosofertas-dot');
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
                const dots = paginacionContainer.querySelectorAll('.productosofertas-dot');
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
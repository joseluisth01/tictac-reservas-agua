<?php
// 1. Registrar el bloque
function bloquetiendacategoria_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloquetiendacategoria',
            'title'             => __('Tienda por Categoría', 'tictac'),
            'description'       => __('Grid de productos con filtros y ofertas', 'tictac'),
            'render_callback'   => 'bloquetiendacategoria',
            'mode'              => 'edit',
            'icon'              => 'store',
            'keywords'          => ['tienda', 'productos', 'categoría', 'filtros'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx' => true
            ],
        ]);
    }
}
add_action('acf/init', 'bloquetiendacategoria_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_bloquetiendacategoria',
        'title' => 'Tienda por Categoría',
        'fields' => array(
            array(
                'key' => 'field_bloquetienda_categoria',
                'label' => 'Seleccionar Categoría',
                'name' => 'bloquetienda_categoria',
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'field_type' => 'select',
                'return_format' => 'id',
                'required' => 1,
            ),
            array(
                'key' => 'field_bloquetienda_titulo',
                'label' => 'Título (H2)',
                'name' => 'bloquetienda_titulo',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'OFERTAS DE COLCHONES BARATOS EN STOCK',
            ),
            array(
                'key' => 'field_bloquetienda_descripcion',
                'label' => 'Descripción',
                'name' => 'bloquetienda_descripcion',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 2,
                'default_value' => 'Selección de colchones outlet. Máxima calidad al precio más bajo de Málaga.',
            ),
            array(
                'key' => 'bloquetienda_titulo_ofertas',
                'label' => 'Título de Ofertas',
                'name' => 'bloquetienda_titulo_ofertas',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'COLCHONES EN OFERTAS',
                'instructions' => 'Título para la sección de productos en oferta del sidebar',
            ),
            array(
                'key' => 'field_bloquetienda_productos_por_pagina',
                'label' => 'Productos por página',
                'name' => 'bloquetienda_productos_por_pagina',
                'type' => 'number',
                'required' => 1,
                'default_value' => 9,
                'min' => 3,
                'max' => 24,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloquetiendacategoria',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloquetiendacategoria_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquetiendacategoria', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloquetiendacategoria/bloquetiendacategoria.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloquetiendacategoria_scripts');

// 4. Función de renderizado
function bloquetiendacategoria($block)
{
    $categoria_id = get_field('bloquetienda_categoria');
    $titulo = get_field('bloquetienda_titulo');
    $descripcion = get_field('bloquetienda_descripcion');
    $titulo_ofertas = get_field('bloquetienda_titulo_ofertas');
    $productos_por_pagina = get_field('bloquetienda_productos_por_pagina') ?: 9;

    if (!$categoria_id) {
        echo '<p style="padding: 20px; background: #f8d7da; color: #721c24;">Por favor, selecciona una categoría.</p>';
        return;
    }

    $block_id = 'bloquetienda-' . uniqid();
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Consulta principal
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $productos_por_pagina,
        'post_status'    => 'publish',
        'paged'          => $paged,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categoria_id,
            ),
        ),
    );

    $productos = new WP_Query($args);

    // Consulta de productos en oferta
    $args_ofertas = array(
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categoria_id,
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC'
            ),
        ),
    );

    $productos_ofertas = new WP_Query($args_ofertas);

    if (!$productos->have_posts()) {
        echo '<p style="padding: 20px; text-align: center;">No se encontraron productos.</p>';
        return;
    }

    // Obtener rango de precios
    $precios = array();
    $temp_query = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $categoria_id,
            ),
        ),
    ));

    while ($temp_query->have_posts()) {
        $temp_query->the_post();
        $product = wc_get_product(get_the_ID());
        $precios[] = floatval($product->get_price());
    }
    wp_reset_postdata();

    $precio_min = !empty($precios) ? floor(min($precios)) : 0;
    $precio_max = !empty($precios) ? ceil(max($precios)) : 1000;
?>

    <div class="containerancho bloquetiendacategoria" id="<?php echo $block_id; ?>">

        <!-- Migas de Pan -->
        <?php
        $categoria = get_term($categoria_id, 'product_cat');
        $categoria_nombre = $categoria ? strtoupper($categoria->name) : 'CATEGORÍA';
        ?>
        <div class="bloquetienda-breadcrumbs">
            <a href="<?php echo home_url('/'); ?>">INICIO</a>
            <span class="separator">&gt;</span>
            <span class="current"><?php echo $categoria_nombre; ?></span>
        </div>

        <div class="bloquetienda-layout">

            <!-- Grid de Productos -->
            <div class="bloquetienda-main">
                <!-- Header -->
                <div class="bloquetienda-header">
                    <h2 class="bloquetienda-titulo"><?= $titulo ?></h2>
                    <?php if ($descripcion): ?>
                        <p class="bloquetienda-descripcion"><?= $descripcion ?></p>
                    <?php endif; ?>
                </div>
                <div class="bloquetienda-grid" data-categoria="<?php echo $categoria_id; ?>">
                    <?php while ($productos->have_posts()) : $productos->the_post();
                        global $product;

                        $precio_regular = $product->get_regular_price();
                        $precio_oferta = $product->get_sale_price();
                        $precio_actual = $product->get_price();
                        $imagen = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $enlace = get_permalink();

                        $tiene_descuento = false;
                        $descuento = 0;
                        if ($precio_regular && $precio_oferta) {
                            $descuento = round((($precio_regular - $precio_oferta) / $precio_regular) * 100);
                            $tiene_descuento = true;
                        }
                    ?>
                        <div class="bloquetienda-producto" data-precio="<?php echo $precio_actual; ?>">
                            <?php if ($tiene_descuento): ?>
                                <div class="bloquetienda-badge-oferta">OFERTA</div>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($enlace); ?>" class="bloquetienda-imagen">
                                <?php if ($imagen): ?>
                                    <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php endif; ?>
                            </a>

                            <div class="bloquetienda-contenido">
                                <h3 class="bloquetienda-nombre">
                                    <a href="<?php echo esc_url($enlace); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="bloquetienda-precios">
                                    <?php if ($tiene_descuento): ?>
                                        <span class="bloquetienda-precio-antiguo">
                                            <?php echo wc_price($precio_regular); ?>
                                        </span>
                                        <span class="bloquetienda-descuento">-<?php echo $descuento; ?>%</span>
                                    <?php endif; ?>
                                    <span class="bloquetienda-precio-actual <?php echo $tiene_descuento ? 'con-descuento' : ''; ?>">
                                        <?php echo wc_price($precio_actual); ?>
                                    </span>
                                </div>

                                <?php if ($precio_actual >= 200): ?>
    <div class="bloquetienda-badges">
        <?php
        // Verificar si el producto tiene la categoría "Gallardo"
        $tiene_gallardo = has_term('gallardo', 'product_cat', get_the_ID());
        ?>
        <span class="bloquetienda-badge">
            <?php echo $tiene_gallardo ? 'PORTES NO INCLUIDOS' : 'ENVÍO Y MONTAJE GRATIS EN MÁLAGA*'; ?>
        </span>
    </div>
<?php endif; ?>

                                <a href="<?php echo esc_url($enlace); ?>" class="bloquetienda-boton">
                                    DESCUBRIR MÁS
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Paginación -->
                <?php if ($productos->max_num_pages > 1): ?>
                    <div class="bloquetienda-paginacion">
                        <?php
                        $current_page = max(1, $paged);
                        $total_pages = $productos->max_num_pages;
                        
                        // Obtener URL base correcta (la página actual, no el producto)
                        global $wp;
                        $current_url = home_url(add_query_arg(array(), $wp->request));
                        $base_url = preg_replace('/\/page\/\d+\/?/', '', $current_url);
                        $base_url = trailingslashit($base_url);
                        
                        // Iconos SVG
                        $upload_dir = wp_upload_dir();
                        $icon_left = $upload_dir['baseurl'] . '/2025/12/Vector-41.svg';
                        $icon_right = $upload_dir['baseurl'] . '/2025/12/Vector-42.svg';

                        // Flecha anterior
                        if ($current_page > 1) {
                            $prev_url = ($current_page == 2) ? $base_url : $base_url . 'page/' . ($current_page - 1) . '/';
                            echo '<a href="' . esc_url($prev_url) . '" class="bloquetienda-pagina prev"><img src="' . esc_url($icon_left) . '" alt="Anterior"></a>';
                        } else {
                            echo '<span class="bloquetienda-pagina prev disabled"><img src="' . esc_url($icon_left) . '" alt="Anterior"></span>';
                        }

                        // Números de página
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = ($i == $current_page) ? 'active' : '';
                            $page_url = ($i == 1) ? $base_url : $base_url . 'page/' . $i . '/';
                            echo '<a href="' . esc_url($page_url) . '" class="bloquetienda-pagina ' . $active . '">' . $i . '</a>';
                        }

                        // Flecha siguiente
                        if ($current_page < $total_pages) {
                            $next_url = $base_url . 'page/' . ($current_page + 1) . '/';
                            echo '<a href="' . esc_url($next_url) . '" class="bloquetienda-pagina next"><img src="' . esc_url($icon_right) . '" alt="Siguiente"></a>';
                        } else {
                            echo '<span class="bloquetienda-pagina next disabled"><img src="' . esc_url($icon_right) . '" alt="Siguiente"></span>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar con Filtros y Ofertas -->
            <aside class="bloquetienda-sidebar-container">

                <!-- Filtros -->
                <div class="bloquetienda-filtros">
                    <!-- Filtro de Precio -->
                    <div class="bloquetienda-filtro">
                        <p class="bloquetienda-filtro-titulo">PRECIO:</p>
                        <div class="bloquetienda-precio-slider">
                            <input type="range" id="precio-min-<?php echo $block_id; ?>"
                                min="<?php echo $precio_min; ?>"
                                max="<?php echo $precio_max; ?>"
                                value="<?php echo $precio_min; ?>"
                                class="slider-min">
                            <input type="range" id="precio-max-<?php echo $block_id; ?>"
                                min="<?php echo $precio_min; ?>"
                                max="<?php echo $precio_max; ?>"
                                value="<?php echo $precio_max; ?>"
                                class="slider-max">
                            <div class="bloquetienda-slider-track"></div>
                        </div>
                        <div class="bloquetienda-precio-valores">
                            <span class="precio-valor-min"><?php echo $precio_min; ?>€</span>
                            <span class="precio-valor-max"><?php echo $precio_max; ?>€</span>
                        </div>
                    </div>

                    <!-- Ordenar Por -->
                    <div class="bloquetienda-filtro">
                        <p class="bloquetienda-filtro-titulo">ORDENAR POR:</p>
                        <select class="bloquetienda-ordenar">
                            <option value="default">Precio: Más bajo primero</option>
                            <option value="price-asc">Precio: Menor a Mayor</option>
                            <option value="price-desc">Precio: Mayor a Menor</option>
                            <option value="name-asc">Nombre: A-Z</option>
                            <option value="name-desc">Nombre: Z-A</option>
                        </select>
                    </div>

                    <!-- Botón Restablecer -->
                    <button class="bloquetienda-restablecer">RESTABLECER</button>
                </div>

                <!-- Productos en Oferta -->
                <div class="bloquetienda-ofertas-sidebar">
                    <p class="bloquetienda-ofertas-titulo"><?= $titulo_ofertas ?></p>

                    <?php if ($productos_ofertas->have_posts()): ?>
                        <div class="bloquetienda-ofertas-lista">
                            <?php while ($productos_ofertas->have_posts()) : $productos_ofertas->the_post();
                                global $product;

                                $precio_regular = $product->get_regular_price();
                                $precio_oferta = $product->get_sale_price();
                                $precio_actual = $product->get_price();
                                $imagen = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                                $enlace = get_permalink();

                                $descuento = 0;
                                if ($precio_regular && $precio_oferta) {
                                    $descuento = round((($precio_regular - $precio_oferta) / $precio_regular) * 100);
                                }
                            ?>
                                <a href="<?php echo esc_url($enlace); ?>" class="bloquetienda-oferta-item">
                                    <div class="oferta-imagen">
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                        <?php endif; ?>
                                        <span class="oferta-badge">-<?php echo $descuento; ?>%</span>
                                    </div>
                                    <div class="oferta-info">
                                        <p><?php the_title(); ?></p>
                                        <div class="oferta-precios">
                                            <?php if ($precio_regular): ?>
                                                <span class="oferta-precio-viejo"><?php echo wc_price($precio_regular); ?></span>
                                            <?php endif; ?>
                                            <span class="oferta-precio-nuevo"><?php echo wc_price($precio_actual); ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </aside>
        </div>
    </div>

    <script>
        (function() {
            const blockId = '<?php echo esc_js($block_id); ?>';
            const container = document.getElementById(blockId);
            if (!container) return;

            const grid = container.querySelector('.bloquetienda-grid');
            const productos = Array.from(grid.querySelectorAll('.bloquetienda-producto'));
            const sliderMin = container.querySelector('.slider-min');
            const sliderMax = container.querySelector('.slider-max');
            const valorMin = container.querySelector('.precio-valor-min');
            const valorMax = container.querySelector('.precio-valor-max');
            const ordenarSelect = container.querySelector('.bloquetienda-ordenar');
            const btnRestablecer = container.querySelector('.bloquetienda-restablecer');
            const sliderTrack = container.querySelector('.bloquetienda-slider-track');

            const precioMinOriginal = parseInt(sliderMin.min);
            const precioMaxOriginal = parseInt(sliderMax.max);

            // Función para actualizar el color de la barra
            function actualizarColorBarra() {
                const min = parseInt(sliderMin.value);
                const max = parseInt(sliderMax.value);
                const range = precioMaxOriginal - precioMinOriginal;

                const minPercent = ((min - precioMinOriginal) / range) * 100;
                const maxPercent = ((max - precioMinOriginal) / range) * 100;

                sliderTrack.style.setProperty('--min-percent', minPercent + '%');
                sliderTrack.style.setProperty('--max-percent', maxPercent + '%');
            }

            // Actualizar sliders de precio
            function actualizarSliders() {
                const min = parseInt(sliderMin.value);
                const max = parseInt(sliderMax.value);

                if (min > max - 10) {
                    sliderMin.value = max - 10;
                }
                if (max < min + 10) {
                    sliderMax.value = min + 10;
                }

                valorMin.textContent = sliderMin.value + '€';
                valorMax.textContent = sliderMax.value + '€';

                actualizarColorBarra();
                filtrarProductos();
            }

            sliderMin.addEventListener('input', actualizarSliders);
            sliderMax.addEventListener('input', actualizarSliders);

            // Filtrar productos
            function filtrarProductos() {
                const min = parseInt(sliderMin.value);
                const max = parseInt(sliderMax.value);

                productos.forEach(producto => {
                    const precio = parseFloat(producto.dataset.precio);
                    if (precio >= min && precio <= max) {
                        producto.style.display = 'flex';
                    } else {
                        producto.style.display = 'none';
                    }
                });
            }

            // Ordenar productos
            ordenarSelect.addEventListener('change', function() {
                const orden = this.value;
                let productosOrdenados = [...productos];

                switch (orden) {
                    case 'price-asc':
                        productosOrdenados.sort((a, b) =>
                            parseFloat(a.dataset.precio) - parseFloat(b.dataset.precio)
                        );
                        break;
                    case 'price-desc':
                        productosOrdenados.sort((a, b) =>
                            parseFloat(b.dataset.precio) - parseFloat(a.dataset.precio)
                        );
                        break;
                    case 'name-asc':
                        productosOrdenados.sort((a, b) =>
                            a.querySelector('.bloquetienda-nombre').textContent.localeCompare(
                                b.querySelector('.bloquetienda-nombre').textContent
                            )
                        );
                        break;
                    case 'name-desc':
                        productosOrdenados.sort((a, b) =>
                            b.querySelector('.bloquetienda-nombre').textContent.localeCompare(
                                a.querySelector('.bloquetienda-nombre').textContent
                            )
                        );
                        break;
                }

                productosOrdenados.forEach(producto => grid.appendChild(producto));
            });

            // Restablecer filtros
            btnRestablecer.addEventListener('click', function() {
                sliderMin.value = precioMinOriginal;
                sliderMax.value = precioMaxOriginal;
                ordenarSelect.value = 'default';
                actualizarSliders();
                productos.forEach(producto => grid.appendChild(producto));
            });

            // Inicializar
            actualizarSliders();
        })();
    </script>

<?php
    wp_reset_postdata();
}

<?php
// Registrar el bloque de ACF
function bloquetienda_acf()
{
    // Verificar que ACF está activo
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'            => 'bloquetienda',
            'title'           => __('Bloque Tienda', 'theme'),
            'description'     => __('Bloque para mostrar productos de WooCommerce por categoría', 'theme'),
            'render_callback' => 'bloquetienda_render',
            'category'        => 'formatting',
            'icon'            => 'cart',
            'keywords'        => ['productos', 'tienda', 'woocommerce', 'ecológicos'],
            'supports'        => [
                'align' => true,
                'mode'  => false,
            ],
        ]);
    }
}
add_action('acf/init', 'bloquetienda_acf');

// Definir los campos ACF (esto ya lo tienes)
add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_bloquetienda',
        'title' => 'Bloque Tienda',
        'fields' => array(
            array(
                'key' => 'field_titulo_bloque',
                'label' => 'Título del Bloque',
                'name' => 'titulo_bloque',
                'type' => 'text',
                'instructions' => 'Introduce el título principal',
                'default_value' => 'NUESTROS PRODUCTOS FITOSANITARIOS ECOLÓGICOS',
                'required' => 0,
            ),
            array(
                'key' => 'field_descripcion_bloque',
                'label' => 'Descripción',
                'name' => 'descripcion_bloque',
                'type' => 'wysiwyg',
                'instructions' => 'Introduce una descripción para el bloque de tienda (opcional)',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'delay' => 0,
                'required' => 0,
            ),
            array(
                'key' => 'field_mostrar_todas_categorias',
                'label' => 'Mostrar Todas las Categorías',
                'name' => 'mostrar_todas_categorias',
                'type' => 'true_false',
                'instructions' => 'Activa esta opción para mostrar productos de todas las categorías',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Sí',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_categoria_productos',
                'label' => 'Categoría de Productos',
                'name' => 'categoria_productos',
                'type' => 'taxonomy',
                'instructions' => 'Selecciona la categoría de productos a mostrar (solo se usa si "Mostrar Todas las Categorías" está desactivado)',
                'required' => 1,
                'taxonomy' => 'product_cat',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'id',
                'multiple' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_mostrar_todas_categorias',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cantidad_productos',
                'label' => 'Cantidad de Productos',
                'name' => 'cantidad_productos',
                'type' => 'number',
                'instructions' => 'Número de productos a mostrar',
                'required' => 0,
                'default_value' => 6,
                'min' => 1,
                'max' => 12,
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/bloquetienda',
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
        'description' => '',
        'show_in_rest' => 0,
    ));
});

// Enqueue scripts para el bloque
function bloquetienda_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquetienda', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloquetienda/bloquetienda.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloquetienda_scripts');

// Función de renderizado (el resto del código se mantiene igual)
function bloquetienda_render($block)
{
    // Verificar que WooCommerce está activo
    if (!class_exists('WooCommerce')) {
        echo '<p>Este bloque requiere que WooCommerce esté instalado y activado.</p>';
        return;
    }

    // Obtener campos ACF
    $titulo_bloque = get_field('titulo_bloque');
    $descripcion_bloque = get_field('descripcion_bloque');
    $mostrar_todas = get_field('mostrar_todas_categorias');
    $categoria_id = get_field('categoria_productos');
    $cantidad = get_field('cantidad_productos') ?: 6;
    $upload_dir = wp_upload_dir();

    // Valores por defecto para ordenar
    $orden = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
    $direccion = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';

    // Configurar argumentos para la consulta de productos
    // Obtener la página actual
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Configurar argumentos para la consulta de productos
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $cantidad,
        'paged' => $paged,
    );

    $category_slug = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
$selected_category = null;

// Si hay una categoría seleccionada desde el filtro
if (!empty($category_slug)) {
    // Obtener el ID de la categoría desde el slug
    $selected_category = get_term_by('slug', $category_slug, 'product_cat');
    
    if ($selected_category && !is_wp_error($selected_category)) {
        // Sobreescribir cualquier configuración previa de categoría en el bloque
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $selected_category->term_id,
                'operator' => 'IN'
            )
        );
    }
}

    // Añadir filtro por categoría solo si no se ha seleccionado mostrar todas
    if (!$mostrar_todas && !empty($categoria_id)) {
        // Obtener información de la categoría
        $categoria = get_term($categoria_id, 'product_cat');

        // Si la categoría existe, aplicar el filtro
        if (!is_wp_error($categoria) && $categoria) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $categoria_id,
                ),
            );
        }
    }

    // Configurar orden de productos
    switch ($orden) {
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'title':
            $args['orderby'] = 'title';
            break;
        case 'rand':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
    }

    $args['order'] = $direccion;

    // Obtener productos
    $productos = new WP_Query($args);

    // Clases de alineación
    $align_class = isset($block['align']) ? ' align' . $block['align'] : '';

    // Comprobar si hay productos
    if (!$productos->have_posts() && is_admin()) {
        echo '<p>No hay productos disponibles con los criterios seleccionados.</p>';
        return;
    }

    if (!$productos->have_posts())
        return;
?>
    <div class="bloque-tienda-wrapper<?php echo esc_attr($align_class); ?>">
        <div class="bloque-tienda-container container col-12 col-xl-9">
            <?php if (!empty($titulo_bloque)): ?>
                <h2 class=""><?php echo esc_html($titulo_bloque); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($descripcion_bloque)): ?>
                <div class="bloque-tienda-descripcion"><?php echo $descripcion_bloque; ?></div>
            <?php endif; ?>

            <div class="bloque-tienda-filtros">
                <div class="bloque-tienda-filtro-izquierda">
                    <span class="bloque-tienda-categoria">HOME |
                        <?php
                        // Mostrar el nombre de la categoría si se ha seleccionado una
                        if (!$mostrar_todas && !empty($categoria_id) && isset($categoria) && !is_wp_error($categoria)) {
                            echo esc_html($categoria->name);
                        } else {
                            echo 'STORE';
                        }
                        ?>
                    </span>
                </div>
                <div class="bloque-tienda-filtro-derecha">
                    <div class="bloque-tienda-orden">
                        <select id="bloque-tienda-orden-select">
                            <option value="date-DESC">DEFAULT ORDER</option>
                            <option value="price-ASC">PRECIO ASCENDENTE</option>
                            <option value="price-DESC">PRECIO DESCENDENTE</option>
                        </select>
                        <div class="bloque-tienda-orden-flecha">
                            <img src="<?php echo $upload_dir['baseurl']; ?>/2025/05/flecha_filtro.svg" alt="">
                        </div>
                    </div>

                    <div class="bloque-tienda-orden">
                        <div id="filtro-categorias-btn" class="filtro-busqueda-btn">
                            <span class="spanfiltros">FILTERS <span class="debusqueda">OR CATEGORIES</span></span>
                        </div>
                        <div class="bloque-tienda-orden-flecha">
                            <img src="<?php echo $upload_dir['baseurl']; ?>/2025/05/item_filtro.svg" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bloque-tienda-productos">
                <?php while ($productos->have_posts()):
                    $productos->the_post();
                    global $product;
                    if (!is_a($product, 'WC_Product'))
                        continue;

                    // Obtener etiquetas de producto (eco, pack, descuento, etc.)
                    $es_eco = has_term('ecologicos', 'product_cat', $product->get_id());
                    $es_pack = has_term('pack', 'product_cat', $product->get_id());
                    $descuento = '';
                    if ($product->is_on_sale()) {
                        $regular_price = (float) $product->get_regular_price();
                        $sale_price = (float) $product->get_sale_price();
                        if ($regular_price > 0) {
                            $descuento = round(100 - ($sale_price / $regular_price * 100));
                        }
                    }

                    // Determinar si tenemos ambas etiquetas
                    $tiene_ambas = $es_eco && $es_pack;

                    // Obtener la valoración del producto
                    $valoracion = get_field('valoracion_estrellas', $product->get_id());
                    if (!$valoracion) {
                        $valoracion = 5; // Valor predeterminado si no se ha configurado
                    }

                    // URLs de las imágenes de estrellas
                    $estrella_llena_url = $upload_dir['baseurl'] . "/2025/05/extrella_completa.svg";
                    $estrella_media_url = $upload_dir['baseurl'] . "/2025/05/Group-1.svg";
                    $estrella_vacia_url = $upload_dir['baseurl'] . "/2025/05/estrella_sin_completar.svg";
                ?>
                    <div>
                        <div class="bloque-tienda-producto">
                            <div class="producto-masvendido-calificacion mb-4">
                                <?php
                                // Mostrar las estrellas según la valoración
                                $valoracion_entera = floor($valoracion);
                                $valoracion_decimal = $valoracion - $valoracion_entera;

                                // Mostrar estrellas llenas
                                for ($i = 1; $i <= $valoracion_entera; $i++): ?>
                                    <img src="<?php echo esc_url($estrella_llena_url); ?>" alt="Estrella llena"
                                        class="estrella-img">
                                <?php endfor;

                                // Mostrar estrella media si corresponde
                                if ($valoracion_decimal >= 0.5): ?>
                                    <img src="<?php echo esc_url($estrella_media_url); ?>" alt="Estrella media"
                                        class="estrella-img">
                                <?php $valoracion_entera++; // Incrementamos para calcular correctamente las estrellas vacías
                                endif;

                                // Mostrar estrellas vacías restantes
                                for ($i = $valoracion_entera + 1; $i <= 5; $i++): ?>
                                    <img src="<?php echo esc_url($estrella_vacia_url); ?>" alt="Estrella vacía"
                                        class="estrella-img">
                                <?php endfor; ?>
                            </div>

                            <div class="producto-masvendido-etiquetas">
                                <?php if ($es_eco): ?>
                                    <span class="etiqueta-eco <?php echo $tiene_ambas ? 'con-pack' : ''; ?>">ECO</span>
                                <?php endif; ?>

                                <?php if ($es_pack): ?>
                                    <span class="etiqueta-pack <?php echo $tiene_ambas ? 'con-eco' : ''; ?>">PACK</span>
                                <?php endif; ?>

                                <?php if ($descuento && !$es_pack): // Solo mostrar descuento si no es pack 
                                ?>
                                    <span class="etiqueta-descuento">-<?php echo esc_html($descuento); ?>%</span>
                                <?php endif; ?>
                            </div>

                            <div class="bloque-tienda-producto-imagen">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('woocommerce_thumbnail'); ?>
                                    <?php else: ?>
                                        <img src="<?php echo wc_placeholder_img_src('woocommerce_thumbnail'); ?>"
                                            alt="<?php the_title(); ?>" />
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="bloque-tienda-producto-precio-comprar col-12 d-flex flex-column flex-lg-row">
                                <div class="bloque-tienda-producto-precio col-5">
                                    <?php if ($product->is_on_sale()): ?>
                                        <span class="precio-original col-12"><?php echo wc_price($product->get_regular_price()); ?></span>
                                    <?php endif; ?>
                                    <span class="precio-actual col-12"
                                        data-price="<?php echo esc_attr($product->get_price()); ?>"><?php echo $product->get_price_html(); ?></span>
                                </div>
                                <div class="bloque-tienda-producto-comprar col-12 col-lg-6">
                                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="boton-comprar col-12 d-flex justify-content-center"
                                        data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-quantity="1">
                                        <span>ADD TO CART</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="bloque-tienda-producto-titulo">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <div class="bloque-tienda-paginacion">
                <?php
                $total_pages = $productos->max_num_pages;

                if ($total_pages > 1) :
                    $current_page = max(1, $paged);

                    // Calcular el número de productos mostrados en la página actual
                    $productos_mostrados = ($current_page == $total_pages) ?
                        $productos->found_posts - (($current_page - 1) * $cantidad) :
                        $cantidad;

                    echo '<div class="paginacion-info">DISPLAYING ' . $productos_mostrados . ' OUT OF ' . $productos->found_posts . ' RESULTS</div>';
                    echo '<div class="paginacion-controles">';

                    // Botón anterior
                    if ($current_page > 1) :
                        echo '<div style="display:flex;"><a href="' . get_pagenum_link($current_page - 1) . '" class="paginacion-boton anterior"><img src="' . $upload_dir['baseurl'] . '/2025/05/ARROW_PRE.svg" alt="Anterior"></a><div class="divanterior">PREVIOUS</div></div>';
                    else :
                        echo '<div style="display:flex;"><span class="paginacion-boton anterior disabled"><img src="' . $upload_dir['baseurl'] . '/2025/05/ARROW_PRE.svg" alt="Anterior"></span><div class="divanterior disabled">PREVIOUS</div></div>';
                    endif;

                 
                    // Botón siguiente
                    if ($current_page < $total_pages) :
                        echo '<div style="display:flex;"><div class="divsiguiente">NEXT</div><a href="' . get_pagenum_link($current_page + 1) . '" class="paginacion-boton siguiente"><img src="' . $upload_dir['baseurl'] . '/2025/05/ARROW_NEXT.svg" alt="NEXT"></a></div>';
                    else :
                        echo '<div style="display:flex;"><div class="divsiguiente">Siguiente</div><span class="paginacion-boton siguiente disabled"><img src="' . $upload_dir['baseurl'] . '/2025/05/ARROW_NEXT.svg" alt="NEXT"></span></div>';
                    endif;

                    echo '</div>';
                endif;
                ?>
            </div>
            <div id="modal-categorias" class="modal-categorias">
                <div class="modal-categorias-contenido">
                    <div class="modal-categorias-header">
                        <h3>Filtrar por categorías</h3>
                        <span id="cerrar-modal-categorias" class="cerrar-modal">&times;</span>
                    </div>
                    <div class="modal-categorias-body">
                        <?php
                        // Obtener todas las categorías de productos
                        $args = array(
                            'taxonomy'     => 'product_cat',
                            'orderby'      => 'name',
                            'show_count'   => 1,
                            'pad_counts'   => 0,
                            'hierarchical' => 1,
                            'title_li'     => '',
                            'hide_empty'   => 0
                        );
                        $all_categories = get_categories($args);

                        if (!empty($all_categories)) {
                            echo '<ul class="lista-categorias">';
                            // Añadir opción para "Todas las categorías"
                            echo '<li>';
                            echo '<a href="' . home_url('/nexum/todos/') . '" class="categoria-item' . (is_shop() && !is_product_category() ? ' active' : '') . '">';
                            echo 'Todas las categorías <span class="categoria-count">(' . wp_count_posts('product')->publish . ')</span>';
                            echo '</a>';
                            echo '</li>';

                            // Listar todas las categorías
                            foreach ($all_categories as $cat) {
                                if ($cat->category_parent == 0) {
                                    $category_id = $cat->term_id;
                                    $category_link = get_term_link($category_id, 'product_cat');

                                    echo '<li>';
                                    echo '<a href="' . $category_link . '" class="categoria-item' . (is_product_category($category_id) ? ' active' : '') . '">';
                                    echo $cat->name . ' <span class="categoria-count">(' . $cat->count . ')</span>';
                                    echo '</a>';

                                    // Verificar si tiene subcategorías
                                    $args_subcats = array(
                                        'taxonomy'     => 'product_cat',
                                        'child_of'     => $category_id,
                                        'parent'       => $category_id,
                                        'orderby'      => 'name',
                                        'show_count'   => 1,
                                        'pad_counts'   => 0,
                                        'hierarchical' => 1,
                                        'title_li'     => '',
                                        'hide_empty'   => 0
                                    );
                                    $subcats = get_categories($args_subcats);

                                    if ($subcats) {
                                        echo '<ul class="subcategorias">';
                                        foreach ($subcats as $subcat) {
                                            $subcat_link = get_term_link($subcat->term_id, 'product_cat');
                                            echo '<li>';
                                            echo '<a href="' . $subcat_link . '" class="categoria-item' . (is_product_category($subcat->term_id) ? ' active' : '') . '">';
                                            echo $subcat->name . ' <span class="categoria-count">(' . $subcat->count . ')</span>';
                                            echo '</a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    }

                                    echo '</li>';
                                }
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No hay categorías disponibles.</p>';
                        }
                        ?>
                    </div>
                    <div class="modal-categorias-footer">
                        <button id="borrar-filtros" class="boton-borrar">Borrar filtros</button>
                        <button id="aplicar-filtros" class="boton-aplicar">Aplicar filtros</button>
                    </div>
                </div>
            </div>
            <script>
                // Añadir al código JavaScript existente:
                jQuery(document).ready(function($) {
                    // Código existente de JS...

                    // Manejo del modal de categorías
                    const filtroBtn = document.getElementById('filtro-categorias-btn');
                    const modal = document.getElementById('modal-categorias');
                    const cerrarModal = document.getElementById('cerrar-modal-categorias');
                    const aplicarFiltros = document.getElementById('aplicar-filtros');
                    const overlay = document.createElement('div');
                    overlay.className = 'modal-overlay';
                    document.body.appendChild(overlay);

                    // Abrir modal
                    filtroBtn.addEventListener('click', function() {
                        modal.classList.add('mostrar');
                        overlay.classList.add('mostrar');
                        document.body.style.overflow = 'hidden';
                    });

                    // Cerrar modal con botón X
                    cerrarModal.addEventListener('click', function() {
                        modal.classList.remove('mostrar');
                        overlay.classList.remove('mostrar');
                        document.body.style.overflow = '';
                    });

                    // Cerrar modal al hacer clic en el overlay
                    overlay.addEventListener('click', function() {
                        modal.classList.remove('mostrar');
                        overlay.classList.remove('mostrar');
                        document.body.style.overflow = '';
                    });

                    // Aplicar filtros (por defecto cierra el modal)
                    aplicarFiltros.addEventListener('click', function() {
                        // Obtener categoría seleccionada (si existe)
                        const categoriaSeleccionada = document.querySelector('.categoria-item.active');
                        if (categoriaSeleccionada) {
                            const url = categoriaSeleccionada.getAttribute('href');

                            // Verificar si estamos en una página donde está insertado el bloque
                            if (document.querySelector('.bloque-tienda-wrapper')) {
                                // Extraer el slug de la categoría del enlace
                                const matches = url.match(/categoria-producto\/([^\/]+)/);
                                if (matches && matches[1]) {
                                    // Añadir el parámetro de categoría a la URL actual
                                    const currentUrl = new URL(window.location.href);
                                    currentUrl.searchParams.set('product_cat', matches[1]);
                                    window.location.href = currentUrl.toString();
                                    return;
                                }
                            }

                            // Si no se encuentra el bloque o no se puede extraer el slug, usar el enlace original
                            window.location.href = url;
                        } else {
                            // Si no hay categoría seleccionada, simplemente cerrar el modal
                            modal.classList.remove('mostrar');
                            overlay.classList.remove('mostrar');
                            document.body.style.overflow = '';
                        }
                    });

                    // Manejar la selección de categorías
                    const categoriasItems = document.querySelectorAll('.categoria-item');
                    categoriasItems.forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            // Quitar clase active de todos los items
                            categoriasItems.forEach(function(cat) {
                                cat.classList.remove('active');
                            });
                            // Añadir clase active al item seleccionado
                            this.classList.add('active');
                        });
                    });
                });

                // Añadir al código JavaScript existente
                const borrarFiltros = document.getElementById('borrar-filtros');

                // Borrar filtros
                borrarFiltros.addEventListener('click', function() {
                    // Quitar clase active de todos los items
                    const categoriasItems = document.querySelectorAll('.categoria-item');
                    categoriasItems.forEach(function(cat) {
                        cat.classList.remove('active');
                    });

                    // Opcionalmente, añadir clase active a "Todas las categorías"
                    const todasCategorias = document.querySelector('.lista-categorias > li:first-child .categoria-item');
                    if (todasCategorias) {
                        todasCategorias.classList.add('active');
                    }
                });
            </script>
            <style>
                .bloque-tienda-descripcion {
                    margin-bottom: 30px;
                    line-height: 1.6;
                }
                .bloque-tienda-descripcion p:last-child {
                    margin-bottom: 0;
                }
            </style>
        </div>
    </div>

    <?php if (!is_admin()): ?>
        <script>
            // Script incluido directamente en el archivo PHP pero se exportará en un archivo separado
            jQuery(document).ready(function($) {
                // Manejar la funcionalidad AJAX para añadir al carrito
                $(document).on('click', '.boton-comprar', function(e) {
                    e.preventDefault();

                    var $thisbutton = $(this);
                    var product_id = $thisbutton.data('product_id');
                    var quantity = $thisbutton.data('quantity');

                    // Solo si tenemos disponible el objeto wc_add_to_cart_params
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        $thisbutton.removeClass('added').addClass('loading');

                        var data = {
                            action: 'woocommerce_ajax_add_to_cart',
                            product_id: product_id,
                            quantity: quantity
                        };

                        // Disparar evento AJAX
                        $.ajax({
                            type: 'post',
                            url: wc_add_to_cart_params.ajax_url,
                            data: data,
                            beforeSend: function() {
                                $thisbutton.html('<span>ADDING...</span>');
                            },
                            complete: function() {
                                $thisbutton.html('<span>ADD TO CART</span>');
                                $thisbutton.removeClass('loading').addClass('added');
                            },
                            success: function(response) {
                                if (response.error && response.product_url) {
                                    window.location = response.product_url;
                                    return;
                                }

                                // Actualizar fragmentos del carrito
                                if (response.fragments) {
                                    $.each(response.fragments, function(key, value) {
                                        $(key).replaceWith(value);
                                    });
                                }

                                // Mostrar mensaje de éxito
                                var $notificacion = $('<div class="bloque-tienda-notificacion">Product added to cart <a href="/carrito" class="ver-carrito-link">look cart</a></div>');
                                $('body').append($notificacion);

                                setTimeout(function() {
                                    $notificacion.addClass('show');
                                }, 100);

                                setTimeout(function() {
                                    $notificacion.removeClass('show');
                                    setTimeout(function() {
                                        $notificacion.remove();
                                    }, 300);
                                }, 3000);

                                // Disparar evento para plugins que dependen de este evento
                                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                            }
                        });

                        return false;
                    } else {
                        // Si no está disponible wc_add_to_cart_params, usar comportamiento por defecto
                        var productUrl = $thisbutton.attr('href');
                        if (productUrl) {
                            window.location.href = productUrl;
                        }
                        return true;
                    }
                });

                // Manejar cambio en el selector de orden
                $('#bloque-tienda-orden-select').on('change', function() {
                    var selectedOption = $(this).val();
                    var orderParams = selectedOption.split('-');
                    var orderby = orderParams[0];
                    var order = orderParams[1];

                    // Ordenar productos en la misma página
                    ordenarProductos(orderby, order);
                });

                // Función para ordenar productos con JavaScript
                // Modificar la función para ordenar productos en el JavaScript
                function ordenarProductos(orderby, order) {
                    // En lugar de ordenar solo los productos, debemos ordenar los contenedores padre
                    // que incluyen tanto el producto como su título
                    var $productContainers = $('.bloque-tienda-productos > div'); // Seleccionar todos los divs hijos directos

                    $productContainers.sort(function(a, b) {
                        var valorA, valorB;

                        if (orderby === 'price') {
                            // Obtener el precio del producto
                            valorA = parseFloat($(a).find('.precio-actual').data('price')) || 0;
                            valorB = parseFloat($(b).find('.precio-actual').data('price')) || 0;
                        } else if (orderby === 'date') {
                            // Para fecha, mantener el orden original
                            return 0;
                        } else {
                            return 0;
                        }

                        // Aplicar dirección de orden
                        if (order === 'ASC') {
                            return valorA - valorB;
                        } else {
                            return valorB - valorA;
                        }
                    });

                    // Volver a añadir los contenedores ordenados al contenedor principal
                    var $container = $('.bloque-tienda-productos');
                    $productContainers.detach().appendTo($container);

                    // Actualizar la URL si es necesario
                    if (history.pushState) {
                        var newUrl = new URL(window.location.href);
                        newUrl.searchParams.set('js_orderby', orderby);
                        newUrl.searchParams.set('js_order', order);
                        window.history.pushState({
                            path: newUrl.toString()
                        }, '', newUrl.toString());
                    }
                }

                // Comportamiento hover para productos
                $('.bloque-tienda-producto').hover(
                    function() {
                        // Al pasar el ratón por encima
                        $(this).find('.boton-comprar').addClass('hover');
                    },
                    function() {
                        // Al quitar el ratón
                        $(this).find('.boton-comprar').removeClass('hover');
                    }
                );

                // Añadir estilos CSS adicionales para la notificación
                const notificacionCSS = `
                    .bloque-tienda-notificacion {
                        position: fixed;
                        bottom: -60px;
                        right: 20px;
                        background-color: #2A4E31;
                        color: white;
                        padding: 15px 25px;
                        border-radius: 4px;
                        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
                        z-index: 9999;
                        transition: bottom 0.3s ease;
                    }
                    .bloque-tienda-notificacion.show {
                        bottom: 20px;
                    }
                `;

                // Añadir el CSS a la página
                $('<style>').html(notificacionCSS).appendTo('head');
            });
        </script>
    <?php endif; ?>
<?php
}

// Añadir soporte para añadir al carrito por AJAX
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'bloquetienda_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'bloquetienda_ajax_add_to_cart');

function bloquetienda_ajax_add_to_cart()
{
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity)) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX::get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );

        wp_send_json($data);
    }

    wp_die();
}
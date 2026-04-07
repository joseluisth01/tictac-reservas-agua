<?php
/**
 * Template de Producto Individual - Ajos
 * Con botones de WooCommerce (Añadir al carrito y Comprar ahora)
 */

defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('custom-single-product', $product); ?>>

    <div class="custom-product-container">

        <!-- PARTE IZQUIERDA -->
        <div class="custom-product-left">

            <!-- PARTE SUPERIOR IZQUIERDA -->
            <div class="custom-product-top">

                <!-- Columna de Imágenes -->
                <div class="custom-images-column">

                    <!-- Imagen Principal -->
                    <div class="custom-main-image">
                        <?php
                        $attachment_ids = $product->get_gallery_image_ids();
                        $main_image_id = $product->get_image_id();

                        if ($main_image_id) {
                            echo wp_get_attachment_image($main_image_id, 'full', false, array('id' => 'mainProductImage'));
                        } else {
                            echo wc_placeholder_img('full');
                        }
                        ?>
                        <?php
                        $precio_producto = floatval($product->get_price());
                        $tiene_gallardo = has_term('gallardo', 'product_cat', get_the_ID());

                        if ($precio_producto > 200) :
                        ?>
                            <span class="etiquetaenviogratis">
                                <img src="<?php echo wp_get_upload_dir()['baseurl']; ?>/2026/01/Vector-48.svg" alt="">
                                <p><?php echo $tiene_gallardo ? 'PORTES NO INCLUIDOS' : 'ENVÍO GRATIS EN MÁLAGA'; ?></p>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Galería de Miniaturas -->
                    <div class="custom-thumbnails-gallery">
                        <?php
                        if ($main_image_id) {
                            $main_image_url = wp_get_attachment_image_url($main_image_id, 'full');
                            echo '<div class="custom-thumbnail active" data-image="' . esc_url($main_image_url) . '">';
                            echo wp_get_attachment_image($main_image_id, 'thumbnail');
                            echo '</div>';
                        }

                        if ($attachment_ids) {
                            foreach ($attachment_ids as $attachment_id) {
                                $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                                echo '<div class="custom-thumbnail" data-image="' . esc_url($image_url) . '">';
                                echo wp_get_attachment_image($attachment_id, 'thumbnail');
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>

                </div>

                <!-- Columna de Información -->
                <div class="custom-info-column">

                    <!-- Título del Producto -->
                    <h1 class="custom-product-title"><?php echo get_the_title(); ?></h1>

                    <!-- Variables Personalizadas -->
                    <?php
                    $variables_producto = get_field('variables_producto');
                    
                    if ($variables_producto && is_array($variables_producto)) :
                    ?>
                        <div class="custom-product-variables">
                            <?php foreach ($variables_producto as $variable) : 
                                $nombre_variable = $variable['nombre_variable'];
                                $opciones = $variable['opciones_variable'];
                                
                                if ($opciones && is_array($opciones)) :
                                    $variable_id = sanitize_title($nombre_variable);
                            ?>
                                <div class="custom-variable-group">
                                    <label class="custom-variable-label"><?php echo esc_html($nombre_variable); ?></label>
                                    <select class="custom-variable-select" 
                                            id="variable_<?php echo esc_attr($variable_id); ?>" 
                                            data-variable-name="<?php echo esc_attr($nombre_variable); ?>"
                                            required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($opciones as $opcion) : ?>
                                            <option value="<?php echo esc_attr($opcion['valor_opcion']); ?>">
                                                <?php echo esc_html($opcion['valor_opcion']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Precio -->
                    <div class="custom-product-price">
                        <?php
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        $price = $product->get_price();

                        if ($product->is_on_sale() && $regular_price && $sale_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                        ?>
                            <div class="custom-price-container">
                                <span class="custom-old-price"><?php echo wc_price($regular_price); ?></span>
                                <span class="custom-discount-badge">-<?php echo $discount; ?>%</span>
                                <span class="custom-sale-price"><?php echo wc_price($sale_price); ?></span>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="custom-price-container">
                                <span class="custom-regular-price"><?php echo wc_price($price); ?></span>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <!-- Descripción Corta -->
                    <div class="custom-short-description">
                        <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
                    </div>

                    <!-- Notificación de Añadido al Carrito -->
                    <div id="custom-cart-notification" class="custom-cart-notification" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>¡Producto añadido al carrito!</span>
                    </div>

                    <!-- Botones de Acción WooCommerce -->
                    <div class="custom-action-buttons">
                        <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                            
                            <!-- Botón Añadir al Carrito -->
                            <button type="button" 
                                    class="custom-btn custom-btn-add-to-cart" 
                                    id="customAddToCart"
                                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                    data-product-name="<?php echo esc_attr(get_the_title()); ?>">
                                AÑADIR AL CARRITO
                            </button>

                            <!-- Botón Comprar Ahora -->
                            <button type="button" 
                                    class="custom-btn custom-btn-buy-now" 
                                    id="customBuyNow"
                                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                    data-product-name="<?php echo esc_attr(get_the_title()); ?>">
                                COMPRAR AHORA
                            </button>

                        <?php else : ?>
                            <p class="custom-stock-info">Producto no disponible</p>
                        <?php endif; ?>
                    </div>

                </div>

            </div>

            <!-- PARTE INFERIOR IZQUIERDA: Descripción Larga -->
            <div class="custom-product-bottom">
                <div class="custom-long-description">
                    <?php the_content(); ?>
                </div>
            </div>

        </div>

        <!-- PARTE DERECHA: Productos Relacionados -->
        <div class="custom-product-sidebar">
            <h3 class="custom-sidebar-title">PRODUCTOS RELACIONADOS</h3>

            <div class="custom-related-products">
                <?php
                $related_ids = wc_get_related_products($product->get_id(), 6);

                if ($related_ids) {
                    foreach ($related_ids as $related_id) {
                        $related_product = wc_get_product($related_id);
                        if (!$related_product) continue;

                        $regular_price = $related_product->get_regular_price();
                        $sale_price = $related_product->get_sale_price();
                        $current_price = $related_product->get_price();
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($related_id), 'thumbnail');

                        $discount = 0;
                        if ($regular_price && $sale_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                        }
                ?>
                        <a href="<?php echo get_permalink($related_id); ?>" class="custom-related-item">
                            <div class="custom-related-image">
                                <?php if ($image): ?>
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($related_product->get_name()); ?>">
                                <?php endif; ?>
                                <?php if ($discount > 0): ?>
                                    <span class="custom-related-discount">-<?php echo $discount; ?>%</span>
                                <?php endif; ?>
                            </div>
                            <div class="custom-related-info">
                                <h4><?php echo $related_product->get_name(); ?></h4>
                                <div class="custom-related-prices">
                                    <?php if ($regular_price && $sale_price): ?>
                                        <span class="custom-related-old-price"><?php echo wc_price($regular_price); ?></span>
                                    <?php endif; ?>
                                    <span class="custom-related-price"><?php echo wc_price($current_price); ?></span>
                                </div>
                            </div>
                        </a>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    </div>

    <?php do_action('woocommerce_after_single_product_summary'); ?>

</div>

<script>
jQuery(document).ready(function($) {
    // ========== GESTIÓN DE VARIABLES DE PRODUCTO ==========
    
    var variablesSeleccionadas = {};

    $('.custom-variable-select').on('change', function() {
        var variableName = $(this).data('variable-name');
        var variableValue = $(this).val();
        
        if (variableValue) {
            variablesSeleccionadas[variableName] = variableValue;
        } else {
            delete variablesSeleccionadas[variableName];
        }
        
        console.log('Variables seleccionadas:', variablesSeleccionadas);
    });

    // Función para validar que se hayan seleccionado todas las variables
    function validarVariables() {
        var selectores = $('.custom-variable-select');
        var todasSeleccionadas = true;
        var mensajeError = '';

        selectores.each(function() {
            if (!$(this).val()) {
                todasSeleccionadas = false;
                var nombreVariable = $(this).data('variable-name');
                mensajeError += '- ' + nombreVariable + '\n';
                $(this).addClass('error-variable');
            } else {
                $(this).removeClass('error-variable');
            }
        });

        if (!todasSeleccionadas && selectores.length > 0) {
            alert('Por favor, selecciona las siguientes opciones:\n\n' + mensajeError);
            return false;
        }

        return true;
    }

    // Función para obtener string de variables
    function getVariablesString() {
        if (Object.keys(variablesSeleccionadas).length === 0) {
            return '';
        }

        var resultado = [];
        for (var nombre in variablesSeleccionadas) {
            resultado.push(nombre + ': ' + variablesSeleccionadas[nombre]);
        }
        return resultado.join(' | ');
    }

    // ========== FUNCIONALIDAD DE AÑADIR AL CARRITO ==========
    
    function mostrarNotificacion() {
        var $notificacion = $('#custom-cart-notification');
        $notificacion.fadeIn(300);
        
        setTimeout(function() {
            $notificacion.fadeOut(300);
        }, 3000);
    }

    // Botón Añadir al Carrito
    $('#customAddToCart').on('click', function(e) {
        e.preventDefault();
        
        // Validar variables
        if (!validarVariables()) {
            return;
        }

        var $button = $(this);
        var productId = $button.data('product-id');
        var variablesString = getVariablesString();
        
        // Deshabilitar botón mientras se procesa
        $button.prop('disabled', true).text('AÑADIENDO...');

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: productId,
                quantity: 1,
                product_variables: variablesString
            },
            success: function(response) {
                if (response.error) {
                    alert('Error al añadir al carrito');
                } else {
                    // Mostrar notificación
                    mostrarNotificacion();
                    
                    // Actualizar el carrito en el header (si existe)
                    $(document.body).trigger('wc_fragment_refresh');
                }
                
                // Restaurar botón
                $button.prop('disabled', false).text('AÑADIR AL CARRITO');
            },
            error: function() {
                alert('Error al añadir al carrito');
                $button.prop('disabled', false).text('AÑADIR AL CARRITO');
            }
        });
    });

    // Botón Comprar Ahora
    $('#customBuyNow').on('click', function(e) {
        e.preventDefault();
        
        // Validar variables
        if (!validarVariables()) {
            return;
        }

        var $button = $(this);
        var productId = $button.data('product-id');
        var variablesString = getVariablesString();
        
        // Deshabilitar botón mientras se procesa
        $button.prop('disabled', true).text('PROCESANDO...');

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: productId,
                quantity: 1,
                product_variables: variablesString
            },
            success: function(response) {
                if (response.error) {
                    alert('Error al añadir al carrito');
                    $button.prop('disabled', false).text('COMPRAR AHORA');
                } else {
                    // Redirigir al checkout
                    window.location.href = wc_add_to_cart_params.cart_url;
                }
            },
            error: function() {
                alert('Error al procesar la compra');
                $button.prop('disabled', false).text('COMPRAR AHORA');
            }
        });
    });

    // ========== GESTIÓN DE IMÁGENES ==========
    
    $('.custom-thumbnail').on('click', function(e) {
        e.preventDefault();
        var newImageUrl = $(this).data('image');

        if (newImageUrl) {
            var $mainImage = $('#mainProductImage');
            $mainImage.fadeOut(200, function() {
                $(this).attr('src', newImageUrl);
                $(this).attr('srcset', newImageUrl);
                $(this).fadeIn(200);
            });

            $('.custom-thumbnail').removeClass('active');
            $(this).addClass('active');
        }
    });

    // ========== NAVEGACIÓN DE IMÁGENES EN FULLSCREEN ==========
    
    var imagenesGaleria = [];
    var currentImageIndex = 0;

    function obtenerImagenesGaleria() {
        imagenesGaleria = [];
        $('.custom-thumbnail').each(function() {
            var imgUrl = $(this).data('image');
            if (imgUrl) {
                imagenesGaleria.push(imgUrl);
            }
        });
    }

    $('.custom-main-image').on('click', function(e) {
        if ($(e.target).closest('.etiquetaenviogratis').length) {
            return;
        }

        var $img = $('#mainProductImage');
        var imgSrc = $img.attr('src');

        obtenerImagenesGaleria();
        currentImageIndex = imagenesGaleria.indexOf(imgSrc);
        if (currentImageIndex === -1) currentImageIndex = 0;

        var $fullscreenModal = $('<div class="custom-fullscreen-modal"></div>');
        var $fullscreenContent = $('<div class="custom-fullscreen-content"></div>');
        var $fullscreenClose = $('<span class="custom-fullscreen-close">&times;</span>');
        var $fullscreenImg = $('<img src="' + imgSrc + '" alt="">');
        var $prevArrow = $('<div class="custom-fullscreen-arrow custom-fullscreen-prev">&#10094;</div>');
        var $nextArrow = $('<div class="custom-fullscreen-arrow custom-fullscreen-next">&#10095;</div>');

        if (imagenesGaleria.length <= 1) {
            $prevArrow.hide();
            $nextArrow.hide();
        }

        $fullscreenContent.append($fullscreenClose);
        $fullscreenContent.append($prevArrow);
        $fullscreenContent.append($fullscreenImg);
        $fullscreenContent.append($nextArrow);
        $fullscreenModal.append($fullscreenContent);

        $('body').append($fullscreenModal);

        setTimeout(function() {
            $fullscreenModal.addClass('active');
        }, 10);

        function cambiarImagen(direccion) {
            if (direccion === 'next') {
                currentImageIndex = (currentImageIndex + 1) % imagenesGaleria.length;
            } else {
                currentImageIndex = (currentImageIndex - 1 + imagenesGaleria.length) % imagenesGaleria.length;
            }

            var nuevaImagen = imagenesGaleria[currentImageIndex];
            
            $fullscreenImg.fadeOut(200, function() {
                $(this).attr('src', nuevaImagen);
                $(this).fadeIn(200);
            });

            $('.custom-thumbnail').removeClass('active');
            $('.custom-thumbnail').eq(currentImageIndex).addClass('active');
        }

        $prevArrow.on('click', function(e) {
            e.stopPropagation();
            cambiarImagen('prev');
        });

        $nextArrow.on('click', function(e) {
            e.stopPropagation();
            cambiarImagen('next');
        });

        $fullscreenClose.on('click', function() {
            cerrarPantallaCompleta($fullscreenModal);
        });

        $fullscreenModal.on('click', function(e) {
            if ($(e.target).hasClass('custom-fullscreen-modal')) {
                cerrarPantallaCompleta($fullscreenModal);
            }
        });

        $(document).on('keydown.fullscreen', function(e) {
            if (e.key === 'Escape') {
                cerrarPantallaCompleta($fullscreenModal);
            } else if (e.key === 'ArrowRight') {
                cambiarImagen('next');
            } else if (e.key === 'ArrowLeft') {
                cambiarImagen('prev');
            }
        });
    });

    function cerrarPantallaCompleta($modal) {
        $modal.removeClass('active');
        setTimeout(function() {
            $modal.remove();
        }, 300);
        $(document).off('keydown.fullscreen');
    }
});
</script>

<?php do_action('woocommerce_after_single_product'); ?>
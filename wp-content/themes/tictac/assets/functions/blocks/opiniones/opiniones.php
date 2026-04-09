<?php
/**
 * BLOQUE OPINIONES - 2 MODOS
 * 1. Manual (reseñas a mano)
 * 2. Trustindex Auto (lee de wp_trustindex_google_reviews + Schema JSON-LD)
 *    Con toggles de visibilidad por elemento
 *    Con opacidad por color
 */

// ===================================
// 1. REGISTRAR EL BLOQUE
// ===================================
function opiniones_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'opiniones',
            'title'             => __('Opiniones', 'tictac'),
            'description'       => __('Bloque de opiniones: Manual o Trustindex Auto', 'tictac'),
            'render_callback'   => 'opiniones_render',
            'mode'              => 'edit',
            'icon'              => 'star-filled',
            'keywords'          => ['custom', 'opiniones', 'reseñas', 'google', 'bloque'],
            'supports'          => ['align' => false, 'mode' => false, 'jsx' => true],
        ]);
    }
}
add_action('acf/init', 'opiniones_acf');

// ===================================
// HELPERS
// ===================================
function opiniones_trustindex_disponible()
{
    global $wpdb;
    $table = $wpdb->prefix . 'trustindex_google_reviews';
    $exists = $wpdb->get_var("SHOW TABLES LIKE '{$table}'");
    if (!$exists) return false;
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE hidden = 0");
    return $count > 0;
}

function opiniones_get_negocio()
{
    $details = get_option('trustindex-google-page-details');
    if (!$details || !is_array($details)) return false;
    return [
        'nombre'           => isset($details['name']) ? trim($details['name'], '"') : '',
        'puntuacion'       => isset($details['rating_score']) ? floatval($details['rating_score']) : 0,
        'total'            => isset($details['rating_number']) ? intval($details['rating_number']) : 0,
        'direccion'        => isset($details['address']) ? $details['address'] : '',
        'avatar'           => isset($details['avatar_url']) ? $details['avatar_url'] : '',
        'place_id'         => isset($details['id']) ? $details['id'] : '',
        'review_url'       => isset($details['review_url']) ? $details['review_url'] : '',
        'write_review_url' => isset($details['write_review_url']) ? $details['write_review_url'] : '',
    ];
}

function opiniones_get_resenas_trustindex()
{
    global $wpdb;
    $table = $wpdb->prefix . 'trustindex_google_reviews';
    $results = $wpdb->get_results(
        "SELECT user, user_photo, text, rating, date, reviewId 
         FROM {$table} 
         WHERE hidden = 0 
         ORDER BY date DESC",
        ARRAY_A
    );
    if (!$results) return [];

    $resenas = [];
    foreach ($results as $row) {
        $resenas[] = [
            'nombre'     => $row['user'] ?: 'Anónimo',
            'avatar'     => $row['user_photo'] ?: '',
            'texto'      => $row['text'] ?: '',
            'puntuacion' => intval(round(floatval($row['rating']))),
            'fecha'      => $row['date'] ?: '',
            'review_id'  => $row['reviewId'] ?: '',
        ];
    }
    return $resenas;
}

function opiniones_schema_jsonld($negocio, $resenas)
{
    if (!$negocio || empty($resenas)) return;

    $reviews_schema = [];
    foreach ($resenas as $r) {
        $review = [
            '@type' => 'Review',
            'reviewRating' => ['@type' => 'Rating', 'ratingValue' => $r['puntuacion'], 'bestRating' => 5],
            'author' => ['@type' => 'Person', 'name' => $r['nombre']],
        ];
        if (!empty($r['texto'])) $review['reviewBody'] = $r['texto'];
        if (!empty($r['fecha'])) $review['datePublished'] = $r['fecha'];
        $reviews_schema[] = $review;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => $negocio['nombre'],
        'aggregateRating' => [
            '@type' => 'AggregateRating', 'ratingValue' => $negocio['puntuacion'],
            'reviewCount' => $negocio['total'], 'bestRating' => 5,
        ],
        'review' => $reviews_schema,
    ];
    if (!empty($negocio['avatar'])) $schema['image'] = $negocio['avatar'];
    if (!empty($negocio['direccion'])) $schema['address'] = $negocio['direccion'];
    if (!empty($negocio['review_url'])) $schema['url'] = $negocio['review_url'];

    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// Hex + opacidad (0-100) → rgba
function opiniones_color_rgba($hex, $opacidad = 100)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $a = max(0, min(100, intval($opacidad))) / 100;

    if ($a >= 1) return '#' . $hex;
    return "rgba({$r},{$g},{$b},{$a})";
}

// ===================================
// 2. REGISTRAR CAMPOS ACF
// ===================================
if (function_exists('acf_add_local_field_group')) :

    $ti_disponible = opiniones_trustindex_disponible();
    $negocio_info = $ti_disponible ? opiniones_get_negocio() : null;
    $modo_choices = ['manual' => 'Reseñas Manuales'];
    if ($ti_disponible && $negocio_info) {
        $modo_choices['trustindex_auto'] = 'Trustindex Auto ✅ (' . $negocio_info['total'] . ' reseñas de ' . $negocio_info['nombre'] . ')';
    } else {
        $modo_choices['trustindex_auto'] = 'Trustindex Auto ⚠️ (No hay reseñas descargadas)';
    }

    acf_add_local_field_group(array(
        'key' => 'grupo_opiniones',
        'title' => 'Opiniones',
        'fields' => array(
            array(
                'key' => 'field_opiniones_modo',
                'label' => 'Modo de Opiniones',
                'name' => 'opiniones_modo',
                'type' => 'select',
                'required' => 1,
                'instructions' => $ti_disponible
                    ? 'Trustindex detectado. El modo Auto lee las reseñas de la base de datos + genera Schema SEO.'
                    : '<strong style="color:#d63638;">⚠️ Para Trustindex Auto: instala el plugin, conecta el negocio y descarga reseñas.</strong>',
                'choices' => $modo_choices,
                'default_value' => $ti_disponible ? 'trustindex_auto' : 'manual',
            ),
            array('key' => 'field_opiniones_estilo', 'label' => 'Estilo Visual', 'name' => 'opiniones_estilo', 'type' => 'select', 'choices' => array('google' => 'Estilo Google (tarjetas blancas)', 'minimalista' => 'Estilo Minimalista (tarjetas oscuras)'), 'default_value' => 'google'),
            array('key' => 'field_opiniones_titulo_seccion', 'label' => 'Título de la Sección', 'name' => 'opiniones_titulo_seccion', 'type' => 'text', 'placeholder' => 'Opiniones de nuestros clientes'),
            array('key' => 'field_opiniones_mostrar_media', 'label' => 'Mostrar Puntuación Media', 'name' => 'opiniones_mostrar_media', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'instructions' => 'En Trustindex Auto se usa la puntuación real.', 'conditional_logic' => array(array(array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),
            array('key' => 'field_opiniones_puntuacion_media', 'label' => 'Puntuación Media (solo Manual)', 'name' => 'opiniones_puntuacion_media', 'type' => 'number', 'default_value' => '4.9', 'min' => 1, 'max' => 5, 'step' => 0.1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'manual'), array('field' => 'field_opiniones_mostrar_media', 'operator' => '==', 'value' => '1')))),

            array('key' => 'field_ti_tab_visibilidad', 'label' => 'Elementos Visibles', 'name' => '', 'type' => 'tab', 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_nombre', 'label' => 'Mostrar Nombre', 'name' => 'ti_mostrar_nombre', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_avatar', 'label' => 'Mostrar Avatar', 'name' => 'ti_mostrar_avatar', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_fecha', 'label' => 'Mostrar Fecha', 'name' => 'ti_mostrar_fecha', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_estrellas', 'label' => 'Mostrar Estrellas', 'name' => 'ti_mostrar_estrellas', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_texto', 'label' => 'Mostrar Texto', 'name' => 'ti_mostrar_texto', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto')))),
            array('key' => 'field_ti_mostrar_verificado', 'label' => 'Mostrar Icono Verificado', 'name' => 'ti_mostrar_verificado', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto'), array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),
            array('key' => 'field_ti_mostrar_logo_google', 'label' => 'Mostrar Logo Google', 'name' => 'ti_mostrar_logo_google', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto'), array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),
            array('key' => 'field_ti_mostrar_link', 'label' => 'Mostrar "Leer más"', 'name' => 'ti_mostrar_link', 'type' => 'true_false', 'default_value' => 0, 'ui' => 1, 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'trustindex_auto'), array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),

            array('key' => 'field_opiniones_tab_colores', 'label' => 'Colores', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_opiniones_fondo_seccion', 'label' => 'Fondo Sección', 'name' => 'opiniones_fondo_seccion', 'type' => 'color_picker', 'default_value' => '#f5f5f5', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_fondo_seccion_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_fondo_seccion_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_fondo_tarjetas', 'label' => 'Fondo Tarjetas', 'name' => 'opiniones_fondo_tarjetas', 'type' => 'color_picker', 'default_value' => '#ffffff', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_fondo_tarjetas_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_fondo_tarjetas_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_texto', 'label' => 'Color Texto', 'name' => 'opiniones_color_texto', 'type' => 'color_picker', 'default_value' => '#212221', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_texto_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_texto_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_texto_sec', 'label' => 'Color Texto Secundario', 'name' => 'opiniones_color_texto_sec', 'type' => 'color_picker', 'default_value' => '#666666', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_texto_sec_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_texto_sec_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_nombre', 'label' => 'Color Nombre', 'name' => 'opiniones_color_nombre', 'type' => 'color_picker', 'default_value' => '#212221', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_nombre_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_nombre_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_titulo_resena', 'label' => 'Color Título Reseña', 'name' => 'opiniones_color_titulo_resena', 'type' => 'color_picker', 'default_value' => '#ffffff', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_titulo_resena_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_titulo_resena_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_titulo', 'label' => 'Color Título Sección', 'name' => 'opiniones_color_titulo', 'type' => 'color_picker', 'default_value' => '#212221', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_titulo_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_titulo_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_link', 'label' => 'Color Link', 'name' => 'opiniones_color_link', 'type' => 'color_picker', 'default_value' => '#1a73e8', 'wrapper' => array('width' => '70'), 'conditional_logic' => array(array(array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),
            array('key' => 'field_opiniones_color_link_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_link_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30'), 'conditional_logic' => array(array(array('field' => 'field_opiniones_estilo', 'operator' => '==', 'value' => 'google')))),
            array('key' => 'field_opiniones_color_estrellas', 'label' => 'Color Estrellas', 'name' => 'opiniones_color_estrellas', 'type' => 'color_picker', 'default_value' => '#FBBC05', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_estrellas_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_estrellas_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_borde', 'label' => 'Color Borde', 'name' => 'opiniones_color_borde', 'type' => 'color_picker', 'default_value' => '', 'instructions' => 'Vacío = sin borde', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_borde_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_borde_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_dots', 'label' => 'Color Punto Activo', 'name' => 'opiniones_color_dots', 'type' => 'color_picker', 'default_value' => '#1a73e8', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_dots_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_dots_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_dots_inactivo', 'label' => 'Color Punto Inactivo', 'name' => 'opiniones_color_dots_inactivo', 'type' => 'color_picker', 'default_value' => '#000000', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_dots_inactivo_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_dots_inactivo_op', 'type' => 'number', 'default_value' => 10, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_color_flechas', 'label' => 'Color Flechas', 'name' => 'opiniones_color_flechas', 'type' => 'color_picker', 'default_value' => '#212221', 'wrapper' => array('width' => '70')),
            array('key' => 'field_opiniones_color_flechas_op', 'label' => 'Opacidad (%)', 'name' => 'opiniones_color_flechas_op', 'type' => 'number', 'default_value' => 100, 'min' => 0, 'max' => 100, 'step' => 1, 'wrapper' => array('width' => '30')),
            array('key' => 'field_opiniones_flecha_prev_img', 'label' => 'Imagen Flecha Anterior', 'name' => 'opiniones_flecha_prev_img', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'instructions' => 'Opcional. Si está vacío se usa la flecha SVG por defecto.', 'wrapper' => array('width' => '50')),
            array('key' => 'field_opiniones_flecha_next_img', 'label' => 'Imagen Flecha Siguiente', 'name' => 'opiniones_flecha_next_img', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'instructions' => 'Opcional. Si está vacío se usa la flecha SVG por defecto.', 'wrapper' => array('width' => '50')),

            array('key' => 'field_opiniones_tab_carrusel', 'label' => 'Carrusel', 'name' => '', 'type' => 'tab'),
            array('key' => 'field_opiniones_loop', 'label' => 'Loop Infinito', 'name' => 'opiniones_loop', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_opiniones_per_desktop', 'label' => 'Reseñas visibles (Desktop)', 'name' => 'opiniones_per_desktop', 'type' => 'number', 'default_value' => 3, 'min' => 1, 'max' => 20),
            array('key' => 'field_opiniones_per_tablet', 'label' => 'Reseñas visibles (Tablet)', 'name' => 'opiniones_per_tablet', 'type' => 'number', 'default_value' => 2, 'min' => 1, 'max' => 10),
            array('key' => 'field_opiniones_per_movil', 'label' => 'Reseñas visibles (Móvil)', 'name' => 'opiniones_per_movil', 'type' => 'number', 'default_value' => 1, 'min' => 1, 'max' => 10),
            array('key' => 'field_opiniones_autoplay', 'label' => 'Autoplay', 'name' => 'opiniones_autoplay', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
            array('key' => 'field_opiniones_intervalo', 'label' => 'Intervalo Autoplay (ms)', 'name' => 'opiniones_intervalo', 'type' => 'number', 'default_value' => 5000, 'min' => 2000, 'max' => 10000, 'step' => 500, 'conditional_logic' => array(array(array('field' => 'field_opiniones_autoplay', 'operator' => '==', 'value' => '1')))),

            array('key' => 'field_opiniones_tab_resenas', 'label' => 'Reseñas', 'name' => '', 'type' => 'tab', 'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'manual')))),
            array(
                'key' => 'field_resenas_lista',
                'label' => 'Reseñas',
                'name' => 'resenas_lista',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Añadir Reseña',
                'conditional_logic' => array(array(array('field' => 'field_opiniones_modo', 'operator' => '==', 'value' => 'manual'))),
                'sub_fields' => array(
                    array('key' => 'field_resena_nombre', 'label' => 'Nombre', 'name' => 'resena_nombre', 'type' => 'text', 'required' => 1, 'wrapper' => array('width' => '50')),
                    array('key' => 'field_resena_avatar_foto', 'label' => 'Foto de Avatar', 'name' => 'resena_avatar_foto', 'type' => 'image', 'required' => 0, 'return_format' => 'array', 'preview_size' => 'thumbnail', 'instructions' => 'Si está vacío se mostrará la inicial.', 'wrapper' => array('width' => '50')),
                    array('key' => 'field_resena_tipo', 'label' => 'Tipo/Rol', 'name' => 'resena_tipo', 'type' => 'text', 'wrapper' => array('width' => '50')),
                    array('key' => 'field_resena_fecha', 'label' => 'Fecha', 'name' => 'resena_fecha', 'type' => 'text', 'wrapper' => array('width' => '50')),
                    array('key' => 'field_resena_puntuacion', 'label' => 'Puntuación', 'name' => 'resena_puntuacion', 'type' => 'select', 'choices' => array('5'=>'⭐⭐⭐⭐⭐','4'=>'⭐⭐⭐⭐','3'=>'⭐⭐⭐','2'=>'⭐⭐','1'=>'⭐'), 'default_value' => '5', 'wrapper' => array('width' => '50')),
                    array('key' => 'field_resena_titulo', 'label' => 'Título Reseña', 'name' => 'resena_titulo', 'type' => 'text'),
                    array('key' => 'field_resena_texto', 'label' => 'Texto', 'name' => 'resena_texto', 'type' => 'textarea', 'required' => 1, 'rows' => 4),
                    array('key' => 'field_resena_avatar_color', 'label' => 'Color Avatar (fallback)', 'name' => 'resena_avatar_color', 'type' => 'color_picker', 'default_value' => '#305E48'),
                    array('key' => 'field_resena_verificado', 'label' => 'Verificado', 'name' => 'resena_verificado', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
                    array('key' => 'field_resena_url', 'label' => 'URL Reseña', 'name' => 'resena_url', 'type' => 'url'),
                ),
            ),
        ),
        'location' => array(array(array('param' => 'block', 'operator' => '==', 'value' => 'acf/opiniones'))),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
endif;

// ===================================
// 3. ENCOLAR ESTILOS
// ===================================
function opiniones_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('opiniones', get_stylesheet_directory_uri() . '/assets/functions/blocks/opiniones/opiniones.min.css', array(), '1.1');
    }
}
add_action('wp_enqueue_scripts', 'opiniones_scripts');

// ===================================
// 4. RENDERIZADO PRINCIPAL
// ===================================
function opiniones_render($block)
{
    $modo = get_field('opiniones_modo') ?: 'manual';
    $block_class = isset($block['className']) ? esc_attr($block['className']) : '';
    $block_id = 'opiniones-' . uniqid();

    $estilo = get_field('opiniones_estilo') ?: 'google';
    $titulo = get_field('opiniones_titulo_seccion');
    $mostrar_media = get_field('opiniones_mostrar_media');

    $c_fondo_seccion     = opiniones_color_rgba(get_field('opiniones_fondo_seccion') ?: '#f5f5f5', get_field('opiniones_fondo_seccion_op') ?? 100);
    $c_fondo_tarjetas    = opiniones_color_rgba(get_field('opiniones_fondo_tarjetas') ?: '#ffffff', get_field('opiniones_fondo_tarjetas_op') ?? 100);
    $c_texto             = opiniones_color_rgba(get_field('opiniones_color_texto') ?: '#212221', get_field('opiniones_color_texto_op') ?? 100);
    $c_texto_sec         = opiniones_color_rgba(get_field('opiniones_color_texto_sec') ?: '#666666', get_field('opiniones_color_texto_sec_op') ?? 100);
    $c_nombre            = opiniones_color_rgba(get_field('opiniones_color_nombre') ?: '#212221', get_field('opiniones_color_nombre_op') ?? 100);
    $c_titulo_resena     = opiniones_color_rgba(get_field('opiniones_color_titulo_resena') ?: '#ffffff', get_field('opiniones_color_titulo_resena_op') ?? 100);
    $c_titulo            = opiniones_color_rgba(get_field('opiniones_color_titulo') ?: '#212221', get_field('opiniones_color_titulo_op') ?? 100);
    $c_link              = opiniones_color_rgba(get_field('opiniones_color_link') ?: '#1a73e8', get_field('opiniones_color_link_op') ?? 100);
    $c_estrellas         = opiniones_color_rgba(get_field('opiniones_color_estrellas') ?: '#FBBC05', get_field('opiniones_color_estrellas_op') ?? 100);
    $c_dots              = opiniones_color_rgba(get_field('opiniones_color_dots') ?: '#1a73e8', get_field('opiniones_color_dots_op') ?? 100);
    $c_dots_inactivo     = opiniones_color_rgba(get_field('opiniones_color_dots_inactivo') ?: '#000000', get_field('opiniones_color_dots_inactivo_op') ?? 10);
    $c_flechas           = opiniones_color_rgba(get_field('opiniones_color_flechas') ?: '#212221', get_field('opiniones_color_flechas_op') ?? 100);

    $color_borde_hex = get_field('opiniones_color_borde') ?: '';
    $c_borde = $color_borde_hex ? opiniones_color_rgba($color_borde_hex, get_field('opiniones_color_borde_op') ?? 100) : '';

    $loop        = get_field('opiniones_loop');
    $per_desktop = get_field('opiniones_per_desktop') ?: 3;
    $per_tablet  = get_field('opiniones_per_tablet') ?: 2;
    $per_movil   = get_field('opiniones_per_movil') ?: 1;
    $autoplay    = get_field('opiniones_autoplay');
    $intervalo   = get_field('opiniones_intervalo') ?: 5000;

    $flecha_prev_img = get_field('opiniones_flecha_prev_img');
    $flecha_next_img = get_field('opiniones_flecha_next_img');

    $ti_ver = [
        'nombre' => true, 'avatar' => true, 'fecha' => true, 'estrellas' => true,
        'texto' => true, 'verificado' => true, 'logo_google' => true, 'link' => false,
    ];
    if ($modo === 'trustindex_auto') {
        $campos_ver = [
            'nombre'      => 'ti_mostrar_nombre',
            'avatar'      => 'ti_mostrar_avatar',
            'fecha'       => 'ti_mostrar_fecha',
            'estrellas'   => 'ti_mostrar_estrellas',
            'texto'       => 'ti_mostrar_texto',
            'verificado'  => 'ti_mostrar_verificado',
            'logo_google' => 'ti_mostrar_logo_google',
            'link'        => 'ti_mostrar_link',
        ];
        foreach ($campos_ver as $key => $field_name) {
            $val = get_field($field_name);
            $default = ($key === 'link') ? false : true;
            $ti_ver[$key] = is_null($val) ? $default : (bool) $val;
        }
    }

    $resenas = [];
    $puntuacion_media = get_field('opiniones_puntuacion_media') ?: '4.9';
    $negocio = null;
    $avatar_colors = ['#305E48','#4A6FA5','#8B5E3C','#6B4C9A','#C04040','#2C7873','#D4A843'];

    if ($modo === 'trustindex_auto') {
        if (!opiniones_trustindex_disponible()) {
            echo '<div style="padding:20px;background:#fce4ec;color:#c62828;border-radius:5px;text-align:center;font-family:sans-serif;"><strong>⚠️ Trustindex Auto:</strong> No se encontraron reseñas.</div>';
            return;
        }
        $negocio = opiniones_get_negocio();
        $ti_resenas = opiniones_get_resenas_trustindex();
        $ti_resenas = array_values(array_filter($ti_resenas, function($r) { return !empty(trim($r['texto'])); }));
        $puntuacion_media = $negocio ? $negocio['puntuacion'] : '4.9';
        $review_url = ($negocio && !empty($negocio['review_url'])) ? $negocio['review_url'] : '';
        foreach ($ti_resenas as $idx => $r) {
            $resenas[] = [
                'nombre' => $r['nombre'], 'fecha' => $r['fecha'], 'tipo' => '',
                'puntuacion' => $r['puntuacion'], 'titulo' => '', 'texto' => $r['texto'],
                'avatar_color' => $avatar_colors[$idx % count($avatar_colors)],
                'avatar_url' => $r['avatar'], 'verificado' => true, 'url' => $review_url,
            ];
        }
        opiniones_schema_jsonld($negocio, $ti_resenas);
    } else {
        $resenas_raw = get_field('resenas_lista');
        if (!$resenas_raw || count($resenas_raw) === 0) {
            echo '<p style="padding:20px;background:#fff3cd;color:#856404;border-radius:5px;text-align:center;">Añade reseñas desde el panel.</p>';
            return;
        }
        $resenas_raw = array_values(array_filter($resenas_raw, function($r) { return !empty(trim($r['resena_texto'])); }));
        foreach ($resenas_raw as $r) {
            $avatar_foto = isset($r['resena_avatar_foto']) && is_array($r['resena_avatar_foto']) ? $r['resena_avatar_foto'] : null;
            $resenas[] = [
                'nombre' => $r['resena_nombre'], 'fecha' => isset($r['resena_fecha']) ? $r['resena_fecha'] : '',
                'tipo' => isset($r['resena_tipo']) ? $r['resena_tipo'] : '', 'puntuacion' => intval($r['resena_puntuacion']),
                'titulo' => isset($r['resena_titulo']) ? $r['resena_titulo'] : '', 'texto' => $r['resena_texto'],
                'avatar_color' => isset($r['resena_avatar_color']) ? $r['resena_avatar_color'] : '#305E48',
                'avatar_url' => ($avatar_foto && !empty($avatar_foto['url'])) ? $avatar_foto['url'] : '',
                'verificado' => isset($r['resena_verificado']) ? $r['resena_verificado'] : false,
                'url' => isset($r['resena_url']) ? $r['resena_url'] : '',
            ];
        }
    }

    if (empty($resenas)) return;
?>
    <style>
        #<?php echo $block_id; ?>{background-color:<?php echo $c_fondo_seccion; ?>;}
        #<?php echo $block_id; ?> .resenas-titulo{color:<?php echo $c_titulo; ?>;}
        #<?php echo $block_id; ?> .resenas-media__numero{color:<?php echo $c_titulo; ?>;}
        #<?php echo $block_id; ?> .resena-card{background-color:<?php echo $c_fondo_tarjetas; ?>;<?php if($c_borde)echo "border:2px solid ".$c_borde.";"; ?>}
        #<?php echo $block_id; ?> .resena-card__nombre{color:<?php echo $c_nombre; ?>;}
        #<?php echo $block_id; ?> .resena-card__fecha,#<?php echo $block_id; ?> .resena-card__tipo{color:<?php echo $c_texto_sec; ?>;}
        #<?php echo $block_id; ?> .resena-card__texto{color:<?php echo $c_texto; ?>;}
        #<?php echo $block_id; ?> .resena-card__titulo-resena{color:<?php echo $c_titulo_resena; ?>;}
        #<?php echo $block_id; ?> .resena-card__link{color:<?php echo $c_link; ?>;}
        #<?php echo $block_id; ?> .estrella--llena{color:<?php echo $c_estrellas; ?>;}
        #<?php echo $block_id; ?> .opiniones-dot{background-color:<?php echo $c_dots_inactivo; ?>;}
        #<?php echo $block_id; ?> .opiniones-dot.active{background-color:<?php echo $c_dots; ?>;}
        #<?php echo $block_id; ?> .opiniones-manual-arrow svg{color:<?php echo $c_flechas; ?>;}
    </style>

    <section class="opiniones opiniones--manual bloque-resenas bloque-resenas--<?php echo esc_attr($estilo); ?> <?php echo $block_class; ?>" id="<?php echo $block_id; ?>"
        data-per-desktop="<?php echo intval($per_desktop); ?>" data-per-tablet="<?php echo intval($per_tablet); ?>" data-per-movil="<?php echo intval($per_movil); ?>"
        data-autoplay="<?php echo $autoplay?'true':'false'; ?>" data-intervalo="<?php echo intval($intervalo); ?>" data-loop="<?php echo $loop?'true':'false'; ?>">
        <div class="containerancho">

            <?php if ($estilo === 'google'): ?>
                <?php if ($titulo || $mostrar_media): ?>
                    <div class="resenas-header">
                        <?php if ($titulo): ?><h2 class="resenas-titulo"><?php echo esc_html($titulo); ?></h2><?php endif; ?>
                        <?php if ($mostrar_media): ?>
                            <div class="resenas-media">
                                <div class="resenas-media__estrellas"><?php for($i=1;$i<=5;$i++): ?><span class="estrella <?php echo $i<=floor($puntuacion_media)?'estrella--llena':''; ?>">★</span><?php endfor; ?></div>
                                <span class="resenas-media__numero"><?php echo esc_html($puntuacion_media); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($titulo): ?><h2 class="resenas-titulo"><?php echo esc_html($titulo); ?></h2><?php endif; ?>
            <?php endif; ?>

            <div class="opiniones-manual-clip">
            <div class="opiniones-manual-viewport">
                <div class="opiniones-manual-track">
                    <?php foreach ($resenas as $resena):
                        $nombre     = $resena['nombre'];
                        $fecha      = $resena['fecha'];
                        $tipo       = $resena['tipo'];
                        $puntuacion = $resena['puntuacion'];
                        $titulo_r   = $resena['titulo'];
                        $texto      = $resena['texto'];
                        $avatar_color = $resena['avatar_color'];
                        $avatar_url   = $resena['avatar_url'];
                        $verificado   = $resena['verificado'];
                        $url          = $resena['url'];
                        $inicial      = mb_strtoupper(mb_substr($nombre, 0, 1));
                        $tiene_foto   = !empty($avatar_url) && strpos($avatar_url, 'default') === false && strpos($avatar_url, 'no-avatar') === false;
                    ?>
                        <div class="opiniones-manual-slide">
                            <?php if ($estilo === 'google'): ?>
                                <div class="resena-card resena-card--google">
                                    <?php if ($ti_ver['estrellas']): ?>
                                        <div class="resena-card__estrellas">
                                            <?php for($i=1;$i<=5;$i++): ?><span class="estrella <?php echo $i<=$puntuacion?'estrella--llena':''; ?>">★</span><?php endfor; ?>
                                            <?php if ($verificado && $ti_ver['verificado']): ?><span class="resena-card__verificado">✓</span><?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($ti_ver['texto'] && $texto): ?>
                                        <div class="resena-card__texto my-4"><?php echo esc_html($texto); ?></div>
                                    <?php endif; ?>
                                    <div class="resena-card__header">
                                        <?php if ($ti_ver['avatar']): ?>
                                            <?php if ($tiene_foto): ?>
                                                <div class="resena-card__avatar resena-card__avatar--foto"><img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($nombre); ?>" loading="lazy"></div>
                                            <?php else: ?>
                                                <div class="resena-card__avatar" style="background-color:<?php echo esc_attr($avatar_color); ?>;"><?php echo esc_html($inicial); ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="resena-card__info">
                                            <?php if ($ti_ver['nombre']): ?><span class="resena-card__nombre"><?php echo esc_html($nombre); ?></span><?php endif; ?>
                                            <?php if ($ti_ver['fecha'] && $fecha): ?><span class="resena-card__fecha"><?php echo esc_html($fecha); ?></span><?php endif; ?>
                                        </div>
                                        <?php if ($ti_ver['logo_google']): ?>
                                            <div class="resena-card__google-logo">
                                                <svg viewBox="0 0 24 24" width="24" height="24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($ti_ver['link'] && $url): ?>
                                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="resena-card__link">Leer más</a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="resena-card resena-card--minimalista">
                                    <div class="resena-card__top">
                                        <?php if ($titulo_r): ?><span class="resena-card__titulo-resena">"<?php echo esc_html($titulo_r); ?>"</span><?php endif; ?>
                                        <?php if ($ti_ver['estrellas']): ?>
                                            <div class="resena-card__estrellas resena-card__estrellas--mini">
                                                <?php for($i=1;$i<=5;$i++): ?><span class="estrella <?php echo $i<=$puntuacion?'estrella--llena':''; ?>">★</span><?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($ti_ver['texto'] && $texto): ?>
                                        <div class="resena-card__texto my-4">"<?php echo esc_html($texto); ?>"</div>
                                    <?php endif; ?>
                                    <div class="resena-card__autor">
                                        <?php if ($ti_ver['avatar']): ?>
                                            <?php if ($tiene_foto): ?>
                                                <div class="resena-card__avatar resena-card__avatar--foto resena-card__avatar--mini"><img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($nombre); ?>" loading="lazy"></div>
                                            <?php else: ?>
                                                <div class="resena-card__avatar resena-card__avatar--mini" style="background-color:<?php echo esc_attr($avatar_color); ?>;"><?php echo esc_html($inicial); ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($ti_ver['nombre']): ?><span class="resena-card__nombre"><?php echo esc_html($nombre); ?></span><?php endif; ?>
                                        <?php if ($tipo): ?><span class="resena-card__tipo">| <?php echo esc_html($tipo); ?></span><?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div><!-- /.opiniones-manual-track -->
            </div><!-- /.opiniones-manual-viewport -->
            </div><!-- /.opiniones-manual-clip -->

            <!-- Controles: flechas izquierda + puntos derecha -->
            <div class="opiniones-manual-controls">
                <div class="opiniones-manual-arrows">
                    <button class="opiniones-manual-arrow opiniones-manual-arrow-prev" type="button" aria-label="Anterior">
                        <?php if ($flecha_prev_img && !empty($flecha_prev_img['url'])): ?>
                            <img src="<?php echo esc_url($flecha_prev_img['url']); ?>" alt="Anterior" width="10" height="15">
                        <?php else: ?>
                            <svg width="12" height="19" viewBox="0 0 12 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.6259 17.0074L3.31446 9.41768L11.6006 1.86263C11.7328 1.75522 11.835 1.62057 11.8988 1.46989C11.9626 1.31922 11.9861 1.15686 11.9675 0.996218C11.9663 0.791222 11.8931 0.591957 11.7588 0.427708C11.6244 0.26346 11.436 0.142865 11.2211 0.0835914C11.007 -0.0021326 10.7683 -0.0225763 10.5404 0.0252809C10.3126 0.0731381 10.1076 0.186727 9.95604 0.349267L7.90665 2.20918L0 9.41768L1.63194 10.9079L3.09942 12.2364L9.91809 18.4745C10.0351 18.596 10.1824 18.6898 10.3476 18.7481C10.5127 18.8065 10.6909 18.8275 10.8669 18.8096C11.0903 18.8035 11.3059 18.7332 11.4831 18.6087C11.6602 18.4843 11.7899 18.312 11.8536 18.1164C11.9451 17.9326 11.9724 17.7275 11.9317 17.5291C11.8909 17.3306 11.7841 17.1485 11.6259 17.0074Z" fill="currentColor"/></svg>
                        <?php endif; ?>
                    </button>
                    <button class="opiniones-manual-arrow opiniones-manual-arrow-next" type="button" aria-label="Siguiente">
                        <?php if ($flecha_next_img && !empty($flecha_next_img['url'])): ?>
                            <img src="<?php echo esc_url($flecha_next_img['url']); ?>" alt="Siguiente" width="10" height="15">
                        <?php else: ?>
                            <svg width="12" height="19" viewBox="0 0 12 19" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: scaleX(-1);"><path d="M11.6259 17.0074L3.31446 9.41768L11.6006 1.86263C11.7328 1.75522 11.835 1.62057 11.8988 1.46989C11.9626 1.31922 11.9861 1.15686 11.9675 0.996218C11.9663 0.791222 11.8931 0.591957 11.7588 0.427708C11.6244 0.26346 11.436 0.142865 11.2211 0.0835914C11.007 -0.0021326 10.7683 -0.0225763 10.5404 0.0252809C10.3126 0.0731381 10.1076 0.186727 9.95604 0.349267L7.90665 2.20918L0 9.41768L1.63194 10.9079L3.09942 12.2364L9.91809 18.4745C10.0351 18.596 10.1824 18.6898 10.3476 18.7481C10.5127 18.8065 10.6909 18.8275 10.8669 18.8096C11.0903 18.8035 11.3059 18.7332 11.4831 18.6087C11.6602 18.4843 11.7899 18.312 11.8536 18.1164C11.9451 17.9326 11.9724 17.7275 11.9317 17.5291C11.8909 17.3306 11.7841 17.1485 11.6259 17.0074Z" fill="currentColor"/></svg>
                        <?php endif; ?>
                    </button>
                </div>
                <!-- Puntos de paginación -->
                <div class="opiniones-dots"></div>
            </div>

        </div>
    </section>

    <script>
    (function(){
        'use strict';
        var id = '<?php echo esc_js($block_id); ?>';
        function init() {
            var block = document.getElementById(id);
            if (!block) return;
            var track      = block.querySelector('.opiniones-manual-track');
            var slides     = block.querySelectorAll('.opiniones-manual-slide');
            var dotsWrap   = block.querySelector('.opiniones-dots');
            var total      = slides.length;
            if (total === 0) return;

            var perDesktop  = parseInt(block.getAttribute('data-per-desktop')) || 3;
            var perTablet   = parseInt(block.getAttribute('data-per-tablet'))  || 2;
            var perMovil    = parseInt(block.getAttribute('data-per-movil'))   || 1;
            var autoplay    = block.getAttribute('data-autoplay') === 'true';
            var intervalo   = parseInt(block.getAttribute('data-intervalo'))   || 5000;
            var loop        = block.getAttribute('data-loop') === 'true';
            var currentIndex = 0;
            var autoTimer    = null;
            var gap          = 32;

            function getPerPage() {
                var w = window.innerWidth;
                if (w < 600)  return perMovil;
                if (w < 1024) return perTablet;
                return perDesktop;
            }

            function getMaxIndex() { return Math.max(total - getPerPage(), 0); }

            function getPages() { return Math.max(Math.ceil(total / getPerPage()), 1); }

            function getAvailableWidth() {
                var clip = block.querySelector('.opiniones-manual-clip');
                // clip uses box-sizing:content-box with 20px padding each side
                // clientWidth = content + padding, so subtract 40px
                return clip ? clip.clientWidth - 40 : block.clientWidth;
            }

            function updateWidths() {
                var vw = getAvailableWidth();
                var pp = getPerPage();
                var totalGaps = gap * (pp - 1);
                var slideW = (vw - totalGaps) / pp;
                for (var i = 0; i < slides.length; i++) {
                    slides[i].style.width = slideW + 'px';
                    slides[i].style.flexShrink = '0';
                }
                track.style.gap = gap + 'px';
            }

            function buildDots() {
                dotsWrap.innerHTML = '';
                var pages = getPages();
                for (var i = 0; i < pages; i++) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'opiniones-dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('aria-label', 'Página ' + (i + 1));
                    dot.setAttribute('data-page', i);
                    dot.addEventListener('click', (function(idx) {
                        return function() {
                            currentIndex = idx * getPerPage();
                            updatePosition(true);
                            if (autoplay) startAutoplay();
                        };
                    })(i));
                    dotsWrap.appendChild(dot);
                }
            }

            function updateDots() {
                var dots = dotsWrap.querySelectorAll('.opiniones-dot');
                var pageActual = Math.floor(currentIndex / getPerPage());
                dots.forEach(function(d, i) {
                    d.classList.toggle('active', i <= pageActual);
                });
            }

            function updatePosition(animate) {
                var vw = getAvailableWidth();
                var pp = getPerPage();
                var totalGaps = gap * (pp - 1);
                var slideW = (vw - totalGaps) / pp;
                var maxIdx = getMaxIndex();
                if (currentIndex > maxIdx) currentIndex = maxIdx;
                var offset = currentIndex * (slideW + gap);
                track.style.transition = animate ? 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)' : 'none';
                track.style.transform = 'translateX(' + (-offset) + 'px)';
                updateDots();
                if (!loop) {
                    var prevArrow = block.querySelector('.opiniones-manual-arrow-prev');
                    var nextArrow = block.querySelector('.opiniones-manual-arrow-next');
                    if (prevArrow) { var pp2 = prevArrow.querySelector('svg path'); if (pp2) { if (currentIndex <= 0) pp2.setAttribute('opacity','0.3'); else pp2.removeAttribute('opacity'); } }
                    if (nextArrow) { var np = nextArrow.querySelector('svg path'); if (np) { if (currentIndex >= maxIdx) np.setAttribute('opacity','0.3'); else np.removeAttribute('opacity'); } }
                }
            }

            function startAutoplay() {
                if (!autoplay) return;
                if (autoTimer) clearInterval(autoTimer);
                autoTimer = setInterval(function() {
                    var pp = getPerPage();
                    var maxIdx = getMaxIndex();
                    currentIndex = currentIndex + pp;
                    if (currentIndex > maxIdx) {
                        currentIndex = loop ? 0 : maxIdx;
                        if (!loop) { clearInterval(autoTimer); return; }
                    }
                    updatePosition(true);
                }, intervalo);
            }

            var prevBtn = block.querySelector('.opiniones-manual-arrow-prev');
            var nextBtn = block.querySelector('.opiniones-manual-arrow-next');

            prevBtn.addEventListener('click', function() {
                var pp = getPerPage();
                var maxIdx = getMaxIndex();
                currentIndex = currentIndex - pp;
                if (currentIndex < 0) currentIndex = loop ? maxIdx : 0;
                updatePosition(true);
                if (autoplay) startAutoplay();
            });

            nextBtn.addEventListener('click', function() {
                var pp = getPerPage();
                var maxIdx = getMaxIndex();
                currentIndex = currentIndex + pp;
                if (currentIndex > maxIdx) currentIndex = loop ? 0 : maxIdx;
                updatePosition(true);
                if (autoplay) startAutoplay();
            });

            var resizeT;
            window.addEventListener('resize', function() {
                clearTimeout(resizeT);
                resizeT = setTimeout(function() {
                    var maxIdx = getMaxIndex();
                    if (currentIndex > maxIdx) currentIndex = maxIdx;
                    updateWidths();
                    buildDots();
                    updatePosition(false);
                }, 150);
            });

            updateWidths();
            buildDots();
            updatePosition(false);
            if (autoplay) startAutoplay();
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
    })();
    </script>
<?php
}
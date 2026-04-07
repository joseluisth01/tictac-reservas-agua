<?php
// 1. Registrar el bloque
function bloquehero_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'bloquehero',
            'title'             => __('Bloque Hero', 'tictac'),
            'description'       => __('Bloque hero con carrusel de slides', 'tictac'),
            'render_callback'   => 'bloquehero',
            'mode'              => 'edit',
            'icon'              => 'slides',
            'keywords'          => ['custom', 'hero', 'slider', 'carrusel', 'banner'],
            'supports'          => [
                'align' => false,
                'mode'  => false,
                'jsx'   => true,
            ],
        ]);
    }
}
add_action('acf/init', 'bloquehero_acf');

// 2. Registrar los campos ACF
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key'    => 'grupo_bloquehero',
        'title'  => 'Bloque Hero',
        'fields' => array(

            // ─── TÍTULO PRINCIPAL ─────────────────────────────────────────
            array(
                'key'           => 'field_bloquehero_titulo',
                'label'         => 'Título Principal (H1)',
                'name'          => 'bloquehero_titulo',
                'type'          => 'wysiwyg',
                'required'      => 1,
                'tabs'          => 'all',
                'toolbar'       => 'basic',
                'media_upload'  => 0,
                'default_value' => 'LOW FACTORY, TU TIENDA DE MUEBLES BARATOS EN MÁLAGA Y COLCHONES LOW COST',
            ),

            // ─── REPETIDOR DE PÁRRAFOS CON ICONO ─────────────────────────
            array(
                'key'          => 'field_bloquehero_parrafos',
                'label'        => 'Párrafos bajo el título',
                'name'         => 'bloquehero_parrafos',
                'type'         => 'repeater',
                'required'     => 0,
                'min'          => 0,
                'layout'       => 'block',
                'button_label' => 'Añadir Párrafo',
                'sub_fields'   => array(
                    array(
                        'key'           => 'field_parrafo_icono',
                        'label'         => 'Icono (izquierda)',
                        'name'          => 'parrafo_icono',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                        'instructions'  => 'Icono opcional que aparece a la izquierda del texto.',
                    ),
                    array(
                        'key'          => 'field_parrafo_texto',
                        'label'        => 'Texto del Párrafo',
                        'name'         => 'parrafo_texto',
                        'type'         => 'wysiwyg',
                        'required'     => 1,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),
                ),
            ),

            // ─── ALTURA DEL CARRUSEL ──────────────────────────────────────
            array(
                'key'       => 'field_bloquehero_altura_tab',
                'label'     => 'Altura del Carrusel',
                'name'      => 'bloquehero_altura_tab',
                'type'      => 'tab',
                'placement' => 'left',
                'endpoint'  => 0,
            ),

            // ── Desktop ≥ 992px ──
            array(
                'key'           => 'field_bloquehero_altura_desktop_val',
                'label'         => 'Altura Desktop (≥ 992px) — Valor',
                'name'          => 'bloquehero_altura_desktop_val',
                'type'          => 'number',
                'required'      => 0,
                'default_value' => 650,
                'min'           => 1,
                'step'          => 1,
                'instructions'  => 'Solo el número, sin unidad. Ej: 650',
            ),
            array(
                'key'           => 'field_bloquehero_altura_desktop_unidad',
                'label'         => 'Altura Desktop — Unidad',
                'name'          => 'bloquehero_altura_desktop_unidad',
                'type'          => 'select',
                'required'      => 0,
                'choices'       => array(
                    'px' => 'px  — píxeles fijos',
                    'vh' => 'vh  — % de la altura de la ventana  ← recomendado',
                    'vw' => 'vw  — % del ancho de la ventana',
                    '%'  => '%   — % del contenedor padre',
                ),
                'default_value' => 'px',
                'allow_null'    => 0,
                'return_format' => 'value',
            ),

            // ── Tablet 768px – 991px ──
            array(
                'key'           => 'field_bloquehero_altura_tablet_val',
                'label'         => 'Altura Tablet (768px – 991px) — Valor',
                'name'          => 'bloquehero_altura_tablet_val',
                'type'          => 'number',
                'required'      => 0,
                'default_value' => 520,
                'min'           => 1,
                'step'          => 1,
                'instructions'  => 'Solo el número, sin unidad. Ej: 520',
            ),
            array(
                'key'           => 'field_bloquehero_altura_tablet_unidad',
                'label'         => 'Altura Tablet — Unidad',
                'name'          => 'bloquehero_altura_tablet_unidad',
                'type'          => 'select',
                'required'      => 0,
                'choices'       => array(
                    'px' => 'px  — píxeles fijos',
                    'vh' => 'vh  — % de la altura de la ventana  ← recomendado',
                    'vw' => 'vw  — % del ancho de la ventana',
                    '%'  => '%   — % del contenedor padre',
                ),
                'default_value' => 'px',
                'allow_null'    => 0,
                'return_format' => 'value',
            ),

            // ── Móvil < 768px ──
            array(
                'key'           => 'field_bloquehero_altura_mobile_val',
                'label'         => 'Altura Móvil (< 768px) — Valor',
                'name'          => 'bloquehero_altura_mobile_val',
                'type'          => 'number',
                'required'      => 0,
                'default_value' => 400,
                'min'           => 1,
                'step'          => 1,
                'instructions'  => 'Solo el número, sin unidad. Ej: 400',
            ),
            array(
                'key'           => 'field_bloquehero_altura_mobile_unidad',
                'label'         => 'Altura Móvil — Unidad',
                'name'          => 'bloquehero_altura_mobile_unidad',
                'type'          => 'select',
                'required'      => 0,
                'choices'       => array(
                    'px' => 'px  — píxeles fijos',
                    'vh' => 'vh  — % de la altura de la ventana  ← recomendado',
                    'vw' => 'vw  — % del ancho de la ventana',
                    '%'  => '%   — % del contenedor padre',
                ),
                'default_value' => 'px',
                'allow_null'    => 0,
                'return_format' => 'value',
            ),

            // ─── SLIDES DEL CARRUSEL ──────────────────────────────────────
            array(
                'key'          => 'field_bloquehero_slides',
                'label'        => 'Slides del Carrusel',
                'name'         => 'bloquehero_slides',
                'type'         => 'repeater',
                'required'     => 1,
                'min'          => 1,
                'layout'       => 'block',
                'button_label' => 'Añadir Slide',
                'sub_fields'   => array(
                    array(
                        'key'           => 'field_bloquehero_imagen',
                        'label'         => 'Imagen de Fondo (Desktop)',
                        'name'          => 'imagen',
                        'type'          => 'image',
                        'required'      => 1,
                        'return_format' => 'array',
                        'instructions'  => 'Imagen que se mostrará en escritorio (más de 800px)',
                    ),
                    array(
                        'key'           => 'field_bloquehero_imagen_mobile',
                        'label'         => 'Imagen de Fondo (Móvil)',
                        'name'          => 'imagen_mobile',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                        'instructions'  => 'Imagen que se mostrará en móviles (800px o menos). Si no se selecciona, se usará la imagen de escritorio.',
                    ),
                    array(
                        'key'           => 'field_bloquehero_overlay',
                        'label'         => 'Activar overlay oscuro sobre imagen',
                        'name'          => 'overlay_activo',
                        'type'          => 'true_false',
                        'required'      => 0,
                        'default_value' => 1,
                        'ui'            => 1,
                        'ui_on_text'    => 'Sí',
                        'ui_off_text'   => 'No',
                        'instructions'  => 'Si está activado, se mostrará un gradiente oscuro sobre la imagen para mejorar la legibilidad del texto.',
                    ),
                    array(
                        'key'          => 'field_bloquehero_subtitulo',
                        'label'        => 'Título del Slide (H2)',
                        'name'         => 'subtitulo',
                        'type'         => 'wysiwyg',
                        'required'     => 1,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),
                    array(
                        'key'          => 'field_bloquehero_descripcion',
                        'label'        => 'Descripción',
                        'name'         => 'descripcion',
                        'type'         => 'wysiwyg',
                        'required'     => 1,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),

                    // ─── ITEMS (imagen + texto desktop + texto móvil) ──────
                    array(
                        'key'          => 'field_bloquehero_items',
                        'label'        => 'Items (imagen + texto)',
                        'name'         => 'slide_items',
                        'type'         => 'repeater',
                        'required'     => 0,
                        'min'          => 0,
                        'layout'       => 'table',
                        'button_label' => 'Añadir Item',
                        'instructions' => 'Cada item aparece en una fila: imagen a la izquierda, texto a la derecha.',
                        'sub_fields'   => array(
                            array(
                                'key'           => 'field_slide_item_imagen',
                                'label'         => 'Imagen',
                                'name'          => 'item_imagen',
                                'type'          => 'image',
                                'required'      => 1,
                                'return_format' => 'array',
                                'preview_size'  => 'thumbnail',
                            ),
                            array(
                                'key'          => 'field_slide_item_texto',
                                'label'        => 'Texto (Desktop / Tablet)',
                                'name'         => 'item_texto',
                                'type'         => 'wysiwyg',
                                'required'     => 1,
                                'tabs'         => 'all',
                                'toolbar'      => 'basic',
                                'media_upload' => 0,
                                'instructions' => 'Texto visible en pantallas ≥ 768px.',
                            ),
                            // ── NUEVO: texto alternativo para móvil ────────
                            array(
                                'key'          => 'field_slide_item_texto_mobile',
                                'label'        => 'Texto (Móvil) — opcional',
                                'name'         => 'item_texto_mobile',
                                'type'         => 'wysiwyg',
                                'required'     => 0,
                                'tabs'         => 'all',
                                'toolbar'      => 'basic',
                                'media_upload' => 0,
                                'instructions' => 'Texto visible en pantallas < 768px. Si se deja vacío, se mostrará el texto de Desktop/Tablet.',
                            ),
                        ),
                    ),

                    array(
                        'key'           => 'field_bloquehero_col_der_tipo',
                        'label'         => 'Columna derecha — Tipo de contenido',
                        'name'          => 'col_der_tipo',
                        'type'          => 'select',
                        'required'      => 0,
                        'choices'       => array(
                            ''          => '— Sin contenido —',
                            'shortcode' => 'Shortcode',
                            'boton'     => 'Botón especial (imagen izq + imagen der)',
                        ),
                        'default_value' => '',
                        'allow_null'    => 1,
                        'return_format' => 'value',
                    ),
                    array(
                        'key'               => 'field_bloquehero_shortcode',
                        'label'             => 'Shortcode',
                        'name'              => 'slide_shortcode',
                        'type'              => 'text',
                        'required'          => 0,
                        'instructions'      => 'Ej: [mi_shortcode]. Solo visible si el tipo es "Shortcode".',
                        'placeholder'       => '[mi_shortcode]',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'shortcode',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_img_izq',
                        'label'             => 'Botón especial — Imagen izquierda',
                        'name'              => 'col_der_img_izq',
                        'type'              => 'image',
                        'required'          => 0,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_img_der',
                        'label'             => 'Botón especial — Imagen derecha',
                        'name'              => 'col_der_img_der',
                        'type'              => 'image',
                        'required'          => 0,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_url',
                        'label'             => 'Botón especial — URL (opcional)',
                        'name'              => 'col_der_url',
                        'type'              => 'page_link',
                        'required'          => 0,
                        'post_type'         => array('page'),
                        'allow_null'        => 1,
                        'multiple'          => 0,
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_bg',
                        'label'             => 'Botón especial — Color de Fondo',
                        'name'              => 'col_der_bg',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'default_value'     => '#ff7700',
                        'enable_opacity'    => 1,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_border',
                        'label'             => 'Botón especial — Color de Borde',
                        'name'              => 'col_der_border',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'default_value'     => '#ff7700',
                        'enable_opacity'    => 1,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_hover_bg',
                        'label'             => 'Botón especial — Color de Fondo (Hover)',
                        'name'              => 'col_der_hover_bg',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'default_value'     => '#3b3b3b',
                        'enable_opacity'    => 1,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),
                    array(
                        'key'               => 'field_col_der_hover_border',
                        'label'             => 'Botón especial — Color de Borde (Hover)',
                        'name'              => 'col_der_hover_border',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'default_value'     => '#3b3b3b',
                        'enable_opacity'    => 1,
                        'return_format'     => 'array',
                        'conditional_logic' => array(array(array(
                            'field'    => 'field_bloquehero_col_der_tipo',
                            'operator' => '==',
                            'value'    => 'boton',
                        ))),
                    ),

                    // ── BOTÓN 1 ──────────────────────────────────────────
                    array(
                        'key'           => 'field_bloquehero_boton1_texto',
                        'label'         => 'Botón 1 — Texto',
                        'name'          => 'boton1_texto',
                        'type'          => 'wysiwyg',
                        'required'      => 1,
                        'tabs'          => 'all',
                        'toolbar'       => 'basic',
                        'media_upload'  => 0,
                        'default_value' => 'VER PACKS AHORRO PISOS',
                    ),
                    array(
                        'key'       => 'field_bloquehero_boton1_enlace',
                        'label'     => 'Botón 1 — Página de Destino',
                        'name'      => 'boton1_enlace',
                        'type'      => 'page_link',
                        'required'  => 1,
                        'post_type' => array('page'),
                        'allow_null'=> 0,
                        'multiple'  => 0,
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_icono',
                        'label'         => 'Botón 1 — Icono (derecha)',
                        'name'          => 'boton1_icono',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                        'instructions'  => 'Icono que aparece a la derecha del texto del botón.',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_icono_hover',
                        'label'         => 'Botón 1 — Icono Hover (derecha)',
                        'name'          => 'boton1_icono_hover',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                        'instructions'  => 'Icono alternativo que aparece al hacer hover sobre el botón.',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_bg',
                        'label'         => 'Botón 1 — Color de Fondo',
                        'name'          => 'boton1_bg',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ff7700',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_color',
                        'label'         => 'Botón 1 — Color del Texto',
                        'name'          => 'boton1_color',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ffffff',
                        'enable_opacity'=> 0,
                        'return_format' => 'string',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_border',
                        'label'         => 'Botón 1 — Color del Borde',
                        'name'          => 'boton1_border',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                        'instructions'  => 'Déjalo vacío para sin borde visible.',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_hover_bg',
                        'label'         => 'Botón 1 — Color de Fondo (Hover)',
                        'name'          => 'boton1_hover_bg',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#3b3b3b',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_hover_color',
                        'label'         => 'Botón 1 — Color del Texto (Hover)',
                        'name'          => 'boton1_hover_color',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ffffff',
                        'enable_opacity'=> 0,
                        'return_format' => 'string',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton1_hover_border',
                        'label'         => 'Botón 1 — Color del Borde (Hover)',
                        'name'          => 'boton1_hover_border',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                        'instructions'  => 'Déjalo vacío para sin borde visible en hover.',
                    ),

                    // ── BOTÓN 2 ──────────────────────────────────────────
                    array(
                        'key'          => 'field_bloquehero_boton2_texto',
                        'label'        => 'Botón 2 — Texto (opcional)',
                        'name'         => 'boton2_texto',
                        'type'         => 'wysiwyg',
                        'required'     => 0,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),
                    array(
                        'key'       => 'field_bloquehero_boton2_enlace',
                        'label'     => 'Botón 2 — Página de Destino',
                        'name'      => 'boton2_enlace',
                        'type'      => 'page_link',
                        'required'  => 0,
                        'post_type' => array('page'),
                        'allow_null'=> 1,
                        'multiple'  => 0,
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_icono',
                        'label'         => 'Botón 2 — Icono (derecha)',
                        'name'          => 'boton2_icono',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_icono_hover',
                        'label'         => 'Botón 2 — Icono Hover (derecha)',
                        'name'          => 'boton2_icono_hover',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_bg',
                        'label'         => 'Botón 2 — Color de Fondo',
                        'name'          => 'boton2_bg',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#3b3b3b',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_color',
                        'label'         => 'Botón 2 — Color del Texto',
                        'name'          => 'boton2_color',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ffffff',
                        'enable_opacity'=> 0,
                        'return_format' => 'string',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_border',
                        'label'         => 'Botón 2 — Color del Borde',
                        'name'          => 'boton2_border',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                        'instructions'  => 'Déjalo vacío para sin borde visible.',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_hover_bg',
                        'label'         => 'Botón 2 — Color de Fondo (Hover)',
                        'name'          => 'boton2_hover_bg',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ff7700',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_hover_color',
                        'label'         => 'Botón 2 — Color del Texto (Hover)',
                        'name'          => 'boton2_hover_color',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '#ffffff',
                        'enable_opacity'=> 0,
                        'return_format' => 'string',
                    ),
                    array(
                        'key'           => 'field_bloquehero_boton2_hover_border',
                        'label'         => 'Botón 2 — Color del Borde (Hover)',
                        'name'          => 'boton2_hover_border',
                        'type'          => 'color_picker',
                        'required'      => 0,
                        'default_value' => '',
                        'enable_opacity'=> 1,
                        'return_format' => 'array',
                        'instructions'  => 'Déjalo vacío para sin borde visible en hover.',
                    ),
                ),
            ),

            // ─── ICONOS INFERIORES ────────────────────────────────────────
            array(
                'key'          => 'field_bloquehero_iconos',
                'label'        => 'Iconos Inferiores',
                'name'         => 'bloquehero_iconos',
                'type'         => 'repeater',
                'required'     => 0,
                'min'          => 0,
                'max'          => 4,
                'layout'       => 'block',
                'button_label' => 'Añadir Icono',
                'sub_fields'   => array(
                    array(
                        'key'           => 'field_icono_imagen',
                        'label'         => 'Icono',
                        'name'          => 'icono',
                        'type'          => 'image',
                        'required'      => 1,
                        'return_format' => 'array',
                    ),
                    array(
                        'key'          => 'field_icono_texto',
                        'label'        => 'Texto',
                        'name'         => 'texto',
                        'type'         => 'wysiwyg',
                        'required'     => 1,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),
                    array(
                        'key'          => 'field_icono_subtexto',
                        'label'        => 'Subtexto',
                        'name'         => 'subtexto',
                        'type'         => 'wysiwyg',
                        'required'     => 0,
                        'tabs'         => 'all',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                    ),
                ),
            ),

            // ─── FLECHAS ──────────────────────────────────────────────────
            array(
                'key'           => 'field_flecha_izquierda',
                'label'         => 'Flecha Izquierda',
                'name'          => 'flecha_izquierda',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'array',
            ),
            array(
                'key'           => 'field_flecha_derecha',
                'label'         => 'Flecha Derecha',
                'name'          => 'flecha_derecha',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'array',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/bloquehero',
                ),
            ),
        ),
    ));

endif;

// 3. Encolar los estilos
function bloquehero_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('bloquehero', get_stylesheet_directory_uri() . '/assets/functions/blocks/bloquehero/bloquehero.min.css');
    }
}
add_action('wp_enqueue_scripts', 'bloquehero_scripts');

// =====================================================
// FILTROS PARA MÁXIMA CALIDAD DE IMAGEN
// =====================================================
add_filter('jpeg_quality',             function () { return 100; });
add_filter('wp_editor_set_quality',    function () { return 100; });
add_filter('big_image_size_threshold', '__return_false');

// =====================================================
// HELPER: color_picker ACF → string CSS
// =====================================================
function bloquehero_color_css($value) {
    if (empty($value)) return '';
    if (is_array($value)) {
        $r = isset($value['red'])   ? intval($value['red'])     : 0;
        $g = isset($value['green']) ? intval($value['green'])   : 0;
        $b = isset($value['blue'])  ? intval($value['blue'])    : 0;
        $a = isset($value['alpha']) ? floatval($value['alpha']) : 1;
        if ($a >= 1) return sprintf('#%02x%02x%02x', $r, $g, $b);
        return "rgba({$r},{$g},{$b},{$a})";
    }
    return esc_attr($value);
}

// =====================================================
// HELPER: construye valor CSS de altura
// =====================================================
function bloquehero_altura_css($val_field, $unidad_field, $fallback_val, $fallback_unidad = 'px') {
    $val    = get_field($val_field);
    $unidad = get_field($unidad_field);

    $unidades_validas = ['px', 'vh', 'vw', '%'];

    $val    = ($val !== '' && $val !== null && is_numeric($val))
                ? floatval($val)
                : floatval($fallback_val);
    $unidad = in_array($unidad, $unidades_validas, true)
                ? $unidad
                : $fallback_unidad;

    $val = ($unidad === 'px') ? max(1, intval($val)) : max(0.1, round($val, 2));

    return $val . $unidad;
}

// =====================================================
// HELPER: CSS inline de colores para un botón
// =====================================================
function bloquehero_boton_style($prefix, $slide, $idx) {
    $bg           = bloquehero_color_css($slide[$prefix . '_bg']           ?? '');
    $color        = !empty($slide[$prefix . '_color'])       ? esc_attr($slide[$prefix . '_color'])       : '';
    $border       = bloquehero_color_css($slide[$prefix . '_border']       ?? '');
    $hover_bg     = bloquehero_color_css($slide[$prefix . '_hover_bg']     ?? '');
    $hover_color  = !empty($slide[$prefix . '_hover_color']) ? esc_attr($slide[$prefix . '_hover_color']) : '';
    $hover_border = bloquehero_color_css($slide[$prefix . '_hover_border'] ?? '');

    $style = '';
    if ($bg)     $style .= "background:{$bg};";
    if ($color)  $style .= "color:{$color};";
    if ($border) $style .= "border:2px solid {$border};";
    else         $style .= "border:2px solid transparent;";

    return [
        'style'        => $style,
        'hover_bg'     => $hover_bg,
        'hover_color'  => $hover_color,
        'hover_border' => $hover_border,
        'orig_bg'      => $bg,
        'orig_color'   => $color,
        'orig_border'  => $border,
    ];
}

// 4. Función de renderizado
function bloquehero($block)
{
    $titulo     = get_field('bloquehero_titulo');
    $parrafos   = get_field('bloquehero_parrafos');
    $slides     = get_field('bloquehero_slides');
    $iconos     = get_field('bloquehero_iconos');
    $flecha_izq = get_field('flecha_izquierda');
    $flecha_der = get_field('flecha_derecha');

    $altura_desktop = bloquehero_altura_css(
        'bloquehero_altura_desktop_val',
        'bloquehero_altura_desktop_unidad',
        650, 'px'
    );
    $altura_tablet = bloquehero_altura_css(
        'bloquehero_altura_tablet_val',
        'bloquehero_altura_tablet_unidad',
        520, 'px'
    );
    $altura_mobile = bloquehero_altura_css(
        'bloquehero_altura_mobile_val',
        'bloquehero_altura_mobile_unidad',
        400, 'px'
    );

    $block_id = 'bloquehero-' . uniqid();

    if (!$slides) return;
?>
    <style>
        #<?php echo $block_id; ?> .bloquehero-slides {
            height: <?php echo esc_attr($altura_desktop); ?> !important;
        }
        @media (max-width: 991.98px) and (min-width: 768px) {
            #<?php echo $block_id; ?> .bloquehero-slides {
                height: <?php echo esc_attr($altura_tablet); ?> !important;
            }
        }
        @media (max-width: 767.98px) {
            #<?php echo $block_id; ?> .bloquehero-slides {
                height: <?php echo esc_attr($altura_mobile); ?> !important;
            }
        }
    </style>

    <div class="containerancho <?php if (isset($block['className'])) echo esc_attr($block['className']); ?> bloquehero" id="<?php echo $block_id; ?>">

        <!-- Título Principal -->
        <?php if ($titulo): ?>
            <div class="bloquehero-header text-center pt-5 px-3 pb-3">
                <div class="bloquehero-titulo"><?php echo $titulo; ?></div>
            </div>
        <?php endif; ?>

        <!-- Párrafos con icono debajo del título -->
        <?php if ($parrafos && count($parrafos) > 0): ?>
            <div class="bloquehero-parrafos px-3 pt-2 pb-3">
                <?php foreach ($parrafos as $parrafo): ?>
                    <div class="bloquehero-parrafo-item d-flex align-items-start mb-2">
                        <?php if (!empty($parrafo['parrafo_icono'])): ?>
                            <div class="bloquehero-parrafo-icono flex-shrink-0 d-flex align-items-center justify-content-center me-3">
                                <img src="<?php echo esc_url($parrafo['parrafo_icono']['url']); ?>"
                                     alt="<?php echo esc_attr($parrafo['parrafo_icono']['alt'] ?: ''); ?>"
                                     class="img-fluid">
                            </div>
                        <?php endif; ?>
                        <div class="bloquehero-parrafo-texto">
                            <?php echo $parrafo['parrafo_texto']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Carrusel de Slides -->
        <div class="bloquehero-carrusel position-relative overflow-hidden">
            <div class="bloquehero-slides position-relative w-100">
                <?php foreach ($slides as $index => $slide):
                    $img_id_desktop  = $slide['imagen']['ID'];
                    $imagen_desktop  = wp_get_attachment_image_url($img_id_desktop, 'full');
                    $srcset_desktop  = wp_get_attachment_image_srcset($img_id_desktop, 'full');

                    if (!empty($slide['imagen_mobile']) && !empty($slide['imagen_mobile']['ID'])) {
                        $img_id_mobile = $slide['imagen_mobile']['ID'];
                        $imagen_mobile = wp_get_attachment_image_url($img_id_mobile, 'full');
                        $srcset_mobile = wp_get_attachment_image_srcset($img_id_mobile, 'full');
                    } else {
                        $imagen_mobile = $imagen_desktop;
                        $srcset_mobile = $srcset_desktop;
                    }

                    $overlay_activo      = isset($slide['overlay_activo']) ? (bool) $slide['overlay_activo'] : true;
                    $b1                  = bloquehero_boton_style('boton1', $slide, $index);
                    $b2                  = bloquehero_boton_style('boton2', $slide, $index);
                    $col_tipo            = $slide['col_der_tipo'] ?? '';
                    $col_shortcode       = $slide['slide_shortcode'] ?? '';
                    $col_img_izq         = $slide['col_der_img_izq'] ?? null;
                    $col_img_der         = $slide['col_der_img_der'] ?? null;
                    $col_url             = $slide['col_der_url'] ?? '';
                    $col_bg              = bloquehero_color_css($slide['col_der_bg']           ?? '') ?: '#ff7700';
                    $col_border          = bloquehero_color_css($slide['col_der_border']       ?? '') ?: '#ff7700';
                    $col_hover_bg        = bloquehero_color_css($slide['col_der_hover_bg']     ?? '') ?: '#3b3b3b';
                    $col_hover_border    = bloquehero_color_css($slide['col_der_hover_border'] ?? '') ?: '#3b3b3b';
                    $col_tiene_contenido = ($col_tipo === 'shortcode' && !empty($col_shortcode))
                                       || ($col_tipo === 'boton'     && (!empty($col_img_izq) || !empty($col_img_der)));
                ?>
                    <div class="bloquehero-slide position-absolute w-100 h-100 d-flex align-items-center <?php echo $index === 0 ? 'activo' : ''; ?>"
                         data-index="<?php echo $index; ?>"
                         data-bg-desktop="<?php echo esc_url($imagen_desktop); ?>"
                         data-bg-mobile="<?php echo esc_url($imagen_mobile); ?>"
                         data-srcset-desktop="<?php echo esc_attr($srcset_desktop); ?>"
                         data-srcset-mobile="<?php echo esc_attr($srcset_mobile); ?>"
                         style="background-image:url('<?php echo esc_url($imagen_desktop); ?>');">

                        <img class="bloquehero-slide-preload"
                             src="<?php echo esc_url($imagen_desktop); ?>"
                             srcset="<?php echo esc_attr($srcset_desktop); ?>"
                             sizes="100vw" alt="" aria-hidden="true"
                             style="position:absolute;width:0;height:0;overflow:hidden;opacity:0;pointer-events:none;">

                        <?php if ($overlay_activo): ?>
                            <div class="bloquehero-slide-overlay position-absolute w-100 h-100"></div>
                        <?php endif; ?>

                        <div class="bloquehero-contenedor w-100 d-flex justify-content-center">
                            <div class="bloquehero-contenido pt-4 row no-gutters align-items-center align-items-lg-end justify-content-around w-100 position-relative">

                                <!-- COLUMNA IZQUIERDA -->
                                <div class="bloquehero-col-izq <?php echo $col_tiene_contenido ? 'col-11 col-lg-5' : 'col-12'; ?> d-flex flex-column justify-content-center">

                                    <div class="bloquehero-subtitulo">
                                        <?php echo $slide['subtitulo']; ?>
                                    </div>

                                    <div class="bloquehero-descripcion mt-3">
                                        <?php echo $slide['descripcion']; ?>
                                    </div>

                                    <?php if (!empty($slide['slide_items'])): ?>
                                        <div class="bloquehero-items d-flex flex-column mt-3">
                                            <?php foreach ($slide['slide_items'] as $item):
                                                // Texto móvil: usa el específico si existe, si no cae en el de desktop
                                                $texto_mobile = !empty($item['item_texto_mobile'])
                                                    ? $item['item_texto_mobile']
                                                    : $item['item_texto'];
                                                $tiene_mobile_distinto = !empty($item['item_texto_mobile']);
                                            ?>
                                                <div class="bloquehero-item d-flex align-items-center mb-2">
                                                    <?php if (!empty($item['item_imagen'])): ?>
                                                        <div class="bloquehero-item-imagen flex-shrink-0 d-flex align-items-center justify-content-center me-3">
                                                            <img src="<?php echo esc_url($item['item_imagen']['url']); ?>"
                                                                 alt="<?php echo esc_attr($item['item_imagen']['alt'] ?: ''); ?>"
                                                                 class="img-fluid">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="bloquehero-item-texto flex-grow-1">
                                                        <?php if ($tiene_mobile_distinto): ?>
                                                            <!-- Texto desktop/tablet: visible ≥ 768px -->
                                                            <div class="bloquehero-item-texto-desktop d-none d-md-block">
                                                                <?php echo $item['item_texto']; ?>
                                                            </div>
                                                            <!-- Texto móvil: visible < 768px -->
                                                            <div class="bloquehero-item-texto-mobile d-block d-md-none">
                                                                <?php echo $texto_mobile; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <!-- Sin versión móvil distinta: se muestra siempre el mismo -->
                                                            <?php echo $item['item_texto']; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="bloquehero-botones d-flex flex-column flex-lg-row flex-wrap align-items-start align-items-lg-center mt-4">

                                        <?php if (!empty($slide['boton1_texto']) && !empty($slide['boton1_enlace'])): ?>
                                            <a href="<?php echo esc_url($slide['boton1_enlace']); ?>"
                                               class="bloquehero-boton bloquehero-boton--1 d-inline-flex align-items-center me-3"
                                               style="<?php echo $b1['style']; ?>"
                                               data-hover-bg="<?php echo esc_attr($b1['hover_bg']); ?>"
                                               data-hover-color="<?php echo esc_attr($b1['hover_color']); ?>"
                                               data-hover-border="<?php echo esc_attr($b1['hover_border']); ?>"
                                               data-orig-bg="<?php echo esc_attr($b1['orig_bg']); ?>"
                                               data-orig-color="<?php echo esc_attr($b1['orig_color']); ?>"
                                               data-orig-border="<?php echo esc_attr($b1['orig_border']); ?>">
                                                <span class="bloquehero-boton-texto"><?php echo $slide['boton1_texto']; ?></span>
                                                <?php if (!empty($slide['boton1_icono'])): ?>
                                                    <span class="bloquehero-boton-icono position-relative d-inline-flex align-items-center justify-content-center flex-shrink-0 ms-2">
                                                        <img class="bloquehero-boton-icono-normal position-absolute"
                                                             src="<?php echo esc_url($slide['boton1_icono']['url']); ?>"
                                                             alt="<?php echo esc_attr($slide['boton1_icono']['alt'] ?: ''); ?>">
                                                        <?php if (!empty($slide['boton1_icono_hover'])): ?>
                                                            <img class="bloquehero-boton-icono-hover position-absolute"
                                                                 src="<?php echo esc_url($slide['boton1_icono_hover']['url']); ?>"
                                                                 alt="<?php echo esc_attr($slide['boton1_icono_hover']['alt'] ?: ''); ?>">
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($slide['boton2_texto']) && !empty($slide['boton2_enlace'])): ?>
                                            <a href="<?php echo esc_url($slide['boton2_enlace']); ?>"
                                               class="bloquehero-boton bloquehero-boton--2 d-inline-flex align-items-center"
                                               style="<?php echo $b2['style']; ?>"
                                               data-hover-bg="<?php echo esc_attr($b2['hover_bg']); ?>"
                                               data-hover-color="<?php echo esc_attr($b2['hover_color']); ?>"
                                               data-hover-border="<?php echo esc_attr($b2['hover_border']); ?>"
                                               data-orig-bg="<?php echo esc_attr($b2['orig_bg']); ?>"
                                               data-orig-color="<?php echo esc_attr($b2['orig_color']); ?>"
                                               data-orig-border="<?php echo esc_attr($b2['orig_border']); ?>">
                                                <span class="bloquehero-boton-texto"><?php echo $slide['boton2_texto']; ?></span>
                                                <?php if (!empty($slide['boton2_icono'])): ?>
                                                    <span class="bloquehero-boton-icono position-relative d-inline-flex align-items-center justify-content-center flex-shrink-0 ms-2">
                                                        <img class="bloquehero-boton-icono-normal position-absolute"
                                                             src="<?php echo esc_url($slide['boton2_icono']['url']); ?>"
                                                             alt="<?php echo esc_attr($slide['boton2_icono']['alt'] ?: ''); ?>">
                                                        <?php if (!empty($slide['boton2_icono_hover'])): ?>
                                                            <img class="bloquehero-boton-icono-hover position-absolute"
                                                                 src="<?php echo esc_url($slide['boton2_icono_hover']['url']); ?>"
                                                                 alt="<?php echo esc_attr($slide['boton2_icono_hover']['alt'] ?: ''); ?>">
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endif; ?>

                                    </div><!-- /.bloquehero-botones -->
                                </div><!-- /.bloquehero-col-izq -->

                                <!-- COLUMNA DERECHA -->
                                <?php if ($col_tiene_contenido): ?>
                                    <div class="bloquehero-col-der col-10 col-lg-6 d-flex flex-column align-items-end justify-content-center">

                                        <?php if ($col_tipo === 'shortcode'): ?>
                                            <?php echo do_shortcode($col_shortcode); ?>

                                        <?php elseif ($col_tipo === 'boton'): ?>
                                            <?php
                                            $boton_der_style = "background:{$col_bg};border:2px solid {$col_border};";
                                            $boton_der_tag   = !empty($col_url) ? 'a' : 'div';
                                            $boton_der_href  = !empty($col_url) ? ' href="' . esc_url($col_url) . '"' : '';
                                            ?>
                                            <<?php echo $boton_der_tag . $boton_der_href; ?>
                                               class="bloquehero-boton-especial d-inline-flex flex-row align-items-center justify-content-center w-100"
                                               style="<?php echo $boton_der_style; ?>"
                                               data-orig-bg="<?php echo esc_attr($col_bg); ?>"
                                               data-orig-border="<?php echo esc_attr($col_border); ?>"
                                               data-hover-bg="<?php echo esc_attr($col_hover_bg); ?>"
                                               data-hover-border="<?php echo esc_attr($col_hover_border); ?>">
                                                <?php if (!empty($col_img_izq)): ?>
                                                    <span class="bloquehero-boton-especial-img d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                                        <img src="<?php echo esc_url($col_img_izq['url']); ?>"
                                                             alt="<?php echo esc_attr($col_img_izq['alt'] ?: ''); ?>"
                                                             class="img-fluid">
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($col_img_der)): ?>
                                                    <span class="bloquehero-boton-especial-img d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                                        <img src="<?php echo esc_url($col_img_der['url']); ?>"
                                                             alt="<?php echo esc_attr($col_img_der['alt'] ?: ''); ?>"
                                                             class="img-fluid">
                                                    </span>
                                                <?php endif; ?>
                                            </<?php echo $boton_der_tag; ?>>
                                        <?php endif; ?>

                                    </div><!-- /.bloquehero-col-der -->
                                <?php endif; ?>

                            </div><!-- /.bloquehero-contenido.row -->
                        </div><!-- /.bloquehero-contenedor -->
                    </div><!-- /.bloquehero-slide -->
                <?php endforeach; ?>
            </div><!-- /.bloquehero-slides -->

            <!-- Controles del Carrusel -->
            <?php if (count($slides) > 1): ?>
                <div class="bloquehero-controles position-absolute d-flex">
                    <button class="bloquehero-prev d-flex align-items-center justify-content-center" aria-label="Anterior">
                        <?php if ($flecha_izq): ?>
                            <img src="<?php echo esc_url($flecha_izq['url']); ?>"
                                 alt="<?php echo esc_attr($flecha_izq['alt'] ?: 'Anterior'); ?>">
                        <?php else: ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <?php endif; ?>
                    </button>
                    <button class="bloquehero-next d-flex align-items-center justify-content-center" aria-label="Siguiente">
                        <?php if ($flecha_der): ?>
                            <img src="<?php echo esc_url($flecha_der['url']); ?>"
                                 alt="<?php echo esc_attr($flecha_der['alt'] ?: 'Siguiente'); ?>">
                        <?php else: ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <?php endif; ?>
                    </button>
                </div>
            <?php endif; ?>
        </div><!-- /.bloquehero-carrusel -->

        <!-- Iconos Inferiores -->
        <?php if ($iconos && count($iconos) > 0): ?>
            <div class="bloquehero-iconos py-4">
                <div class="row no-gutters">
                    <?php foreach ($iconos as $index => $icono): ?>
                        <div class="col-6 col-lg-3 bloquehero-icono-item d-flex flex-column">
                            <div class="bloquehero-icono-header d-flex align-items-center justify-content-around p-3 position-relative"
                                 style="cursor:pointer;" data-accordion-trigger="<?php echo $index; ?>">
                                <img src="<?php echo esc_url($icono['icono']['url']); ?>"
                                     alt="<?php echo esc_attr($icono['icono']['alt'] ?: ''); ?>"
                                     class="flex-shrink-0">
                                <div class="iconotitulo"><?php echo $icono['texto']; ?></div>
                            </div>
                            <?php if (!empty($icono['subtexto'])): ?>
                                <div class="bloquehero-icono-content d-flex flex-column align-items-center position-relative"
                                     data-accordion-content="<?php echo $index; ?>">
                                    <div class="bloquehero-icono-toggle-wrapper w-100 d-flex justify-content-end align-items-center pt-2">
                                        <button class="bloquehero-icono-toggle d-flex align-items-center justify-content-center flex-shrink-0" aria-label="Expandir información">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </div>
                                    <div class="bloquehero-icono-text w-100">
                                        <?php echo $icono['subtexto']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    (function() {
        const blockId = '<?php echo esc_js($block_id); ?>';
        const slider  = document.getElementById(blockId);
        if (!slider) return;

        const slides   = slider.querySelectorAll('.bloquehero-slide');
        const prevBtn  = slider.querySelector('.bloquehero-prev');
        const nextBtn  = slider.querySelector('.bloquehero-next');
        let slideActual = 0;
        let autoplayMontserratval;

        function actualizarImagenResponsive() {
            const isMobile = window.innerWidth <= 800;
            const dpr = window.devicePixelRatio || 1;
            slides.forEach(slide => {
                const bgDesktop     = slide.getAttribute('data-bg-desktop');
                const bgMobile      = slide.getAttribute('data-bg-mobile');
                const srcsetDesktop = slide.getAttribute('data-srcset-desktop');
                const srcsetMobile  = slide.getAttribute('data-srcset-mobile');
                let bgToUse         = isMobile ? bgMobile     : bgDesktop;
                const srcsetToUse   = isMobile ? srcsetMobile : srcsetDesktop;
                if (srcsetToUse) {
                    const mejorUrl = obtenerMejorImagenDeSrcset(srcsetToUse, slide.offsetWidth, dpr);
                    if (mejorUrl) bgToUse = mejorUrl;
                }
                slide.style.backgroundImage = `url('${bgToUse}')`;
            });
        }

        function obtenerMejorImagenDeSrcset(srcset, anchoContenedor, dpr) {
            if (!srcset) return null;
            const anchoNecesario = anchoContenedor * dpr;
            let mejorUrl = null, mejorAncho = 0;
            srcset.split(',').forEach(parte => {
                const tokens = parte.trim().split(/\s+/);
                if (tokens.length >= 2) {
                    const url = tokens[0];
                    const ancho = parseInt(tokens[1].replace('w', ''), 10);
                    if (ancho >= anchoNecesario && (mejorUrl === null || ancho < mejorAncho)) {
                        mejorUrl = url; mejorAncho = ancho;
                    }
                }
            });
            if (!mejorUrl) {
                srcset.split(',').forEach(parte => {
                    const tokens = parte.trim().split(/\s+/);
                    if (tokens.length >= 2) {
                        const url = tokens[0];
                        const ancho = parseInt(tokens[1].replace('w', ''), 10);
                        if (ancho > mejorAncho) { mejorUrl = url; mejorAncho = ancho; }
                    }
                });
            }
            return mejorUrl;
        }

        actualizarImagenResponsive();
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(actualizarImagenResponsive, 100);
        });

        slider.querySelectorAll('.bloquehero-boton').forEach(function(btn) {
            const hoverBg     = btn.getAttribute('data-hover-bg');
            const hoverColor  = btn.getAttribute('data-hover-color');
            const hoverBorder = btn.getAttribute('data-hover-border');
            const origBg      = btn.getAttribute('data-orig-bg');
            const origColor   = btn.getAttribute('data-orig-color');
            const origBorder  = btn.getAttribute('data-orig-border');
            btn.addEventListener('mouseenter', function() {
                if (hoverBg)     btn.style.background  = hoverBg;
                if (hoverColor)  btn.style.color        = hoverColor;
                if (hoverBorder) btn.style.borderColor  = hoverBorder;
            });
            btn.addEventListener('mouseleave', function() {
                if (origBg)    btn.style.background  = origBg;
                if (origColor) btn.style.color        = origColor;
                btn.style.borderColor = origBorder || 'transparent';
            });
        });

        slider.querySelectorAll('.bloquehero-boton-especial').forEach(function(btn) {
            const hoverBg     = btn.getAttribute('data-hover-bg');
            const hoverBorder = btn.getAttribute('data-hover-border');
            const origBg      = btn.getAttribute('data-orig-bg');
            const origBorder  = btn.getAttribute('data-orig-border');
            btn.addEventListener('mouseenter', function() {
                if (hoverBg)     btn.style.background  = hoverBg;
                if (hoverBorder) btn.style.borderColor = hoverBorder;
            });
            btn.addEventListener('mouseleave', function() {
                if (origBg)     btn.style.background  = origBg;
                if (origBorder) btn.style.borderColor = origBorder;
            });
        });

        function cambiarSlide(nuevoIndex) {
            slides[slideActual].classList.remove('activo');
            slideActual = nuevoIndex;
            slides[slideActual].classList.add('activo');
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                cambiarSlide(slideActual === 0 ? slides.length - 1 : slideActual - 1);
                reiniciarAutoplay();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                cambiarSlide(slideActual === slides.length - 1 ? 0 : slideActual + 1);
                reiniciarAutoplay();
            });
        }
        function iniciarAutoplay() {
            autoplayMontserratval = setInterval(() => {
                cambiarSlide(slideActual === slides.length - 1 ? 0 : slideActual + 1);
            }, 5000);
        }
        function reiniciarAutoplay() {
            clearInterval(autoplayMontserratval);
            iniciarAutoplay();
        }
        slider.addEventListener('mouseenter', () => clearInterval(autoplayMontserratval));
        slider.addEventListener('mouseleave', () => iniciarAutoplay());
        if (slides.length > 1) iniciarAutoplay();

        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft'  && prevBtn) prevBtn.click();
            if (e.key === 'ArrowRight' && nextBtn) nextBtn.click();
        });

        const iconosItems = slider.querySelectorAll('.bloquehero-icono-item');
        iconosItems.forEach(item => {
            const header = item.querySelector('.bloquehero-icono-header');
            const toggle = item.querySelector('.bloquehero-icono-toggle');
            if (header) header.addEventListener('click', function() { toggleAccordion(item); });
            if (toggle) toggle.addEventListener('click', function(e) { e.stopPropagation(); toggleAccordion(item); });
        });
        function toggleAccordion(item) {
            const wasActive = item.classList.contains('active');
            iconosItems.forEach(i => i.classList.remove('active'));
            if (!wasActive) item.classList.add('active');
        }
    })();
    </script>

<?php
}
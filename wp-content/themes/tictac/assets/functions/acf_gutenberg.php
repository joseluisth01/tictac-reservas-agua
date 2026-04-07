<?php
/*include("blocks/slider/slider.php");pruebaaaa45
*/

include("blocks/bloquehero/bloquehero.php");
include("blocks/serviciosagenciamarketing/serviciosagenciamarketing.php");
include("blocks/opiniones/opiniones.php");
include("blocks/pasosmarketing/pasosmarketing.php");
include("blocks/empresas/empresas.php");
include("blocks/nuestrasdiferencias/nuestrasdiferencias.php");
include("blocks/bloqueanalisis/bloqueanalisis.php");
include("blocks/mapa/mapa.php");
include("blocks/bloquefaqs/bloquefaqs.php");
include("blocks/heroseo/heroseo.php");
include("blocks/equipo/equipo.php");
include("blocks/productosofertas/productosofertas.php");
include("blocks/productoscategoria/productoscategoria.php");
include("blocks/catalogocategorias/catalogocategorias.php");
include("blocks/productospacks/productospacks.php");
include("blocks/bloquetiendacategoria/bloquetiendacategoria.php");
include("blocks/introtienda/introtienda.php");
include("blocks/garantias/garantias.php");
include("blocks/galeria_grid/galeria_grid.php");
include("blocks/textofoto/textofoto.php");
include("blocks/faqs/faqs.php");
include("blocks/parigual/parigual.php");
include("blocks/parigual2/parigual2.php");
include("blocks/porque/porque.php");
include("blocks/galeria_grid_loadmore/galeria_grid_loadmore.php");
include("blocks/conexion/conexion.php");
include("blocks/carrusel_texto_lateral/carrusel_texto_lateral.php");
// NUEVOS
include("blocks/textos/textos.php");
include("blocks/garantiasajos/garantiasajos.php");
include("blocks/garantiassalud/garantiassalud.php");
if (function_exists('acf_add_local_field_group')) :
  acf_add_local_field_group(array(
    'key' => 'group_footer_settings',
    'title' => 'General',
    'fields' => array(
      array(
        'key' => 'catalogo_helix',
        'label' => 'catalogo',
        'name' => 'catalogo',
        'type' => 'file',
        'required' => false,
      ),
      array(
        'key' => 'field_footer_telefono_1',
        'label' => 'Teléfono 1',
        'name' => 'footer_telefono_1',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'whatsapp',
        'label' => 'whatsapp',
        'name' => 'whatsapp',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'field_fax',
        'label' => 'FAX',
        'name' => 'fax',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'field_footer_email',
        'label' => 'Email',
        'name' => 'footer_email',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'twitter',
        'label' => 'Twitter',
        'name' => 'twitter',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'instagram',
        'label' => 'Instagram',
        'name' => 'instagram',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'facebook',
        'label' => 'Facebook',
        'name' => 'facebook',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'ln',
        'label' => 'Linkedin',
        'name' => 'ln',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'yt',
        'label' => 'Youtube',
        'name' => 'yt',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'direccion',
        'label' => 'direccion',
        'name' => 'direccion',
        'type' => 'link',
        'required' => false,
      ),
      array(
        'key' => 'envio_gratuito',
        'label' => 'Envio gratuito',
        'name' => 'envio_gratuito',
        'type' => 'wysiwyg',
        'required' => false,
      ),
      array(
        'key' => 'field_footer_informacion',
        'label' => 'Información',
        'name' => 'informacion_texto',
        'type' => 'wysiwyg',
        'required' => false,
      ),
      array(
        'key' => 'legal',
        'label' => 'Legal',
        'name' => 'legal',
        'type' => 'post_object',
        'post_type' => array('page'),
        'multiple' => true,
        'return_format' => 'object',
        'ui' => 1,
      ),
      array(
        'key' => 'imagen_footer',
        'label' => 'Imagen Footer',
        'name' => 'Imagen Footer',
        'type' => 'image',
        'required' => false,
      ),
      array(
        'key' => 'tictac',
        'label' => 'tictac',
        'name' => 'tictac',
        'type' => 'image',
        'required' => false,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'theme-general-settings',
        ),
      ),
    ),
  ));
/*
  acf_add_local_field_group(array(
    'key' => 'group_footer_blocks',
    'title' => 'Bloques footer',
    'fields' => array(
      array(
        'key' => 'test',
        'label' => 'Teléfono 1',
        'name' => 'test_1',
        'type' => 'link',
        'required' => false,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'bloques-footer',
        ),
      ),
    ),
  ));
*/
endif;

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_64f845ea765f0',
	'title' => 'Banner productos',
	'fields' => array(
		array(
			'key' => 'field_64f845ebe5648',
			'label' => 'Banner Productos',
			'name' => 'banner_productos',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'preview_size' => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'theme-general-settings',
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
) );
} );



function subvencion_footer()
{
    $logotipos = get_field('logotipos_subvencion','options');
    $size = 'large';
    $texto_subvencion = get_field("texto_subvencion",'options');
?>
    <div class="subvencion d-flex justify-content-center py-5">
        <div class="container row">
            <div class="col-12 col-md-6 d-flex flex-wrap justify-content-center align-items-center align-content-center">
                <?php if ($logotipos) : ?>
                    <?php foreach ($logotipos as $logotipo) : ?>
                        <img class="mx-2 mb-2" src="<?= $logotipo["sizes"]["medium"]; ?>" alt="<?= $logotipo["alt"]; ?>">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-12 col-md-6 text-center">
                <?= $texto_subvencion; ?>
            </div>
        </div>
    </div>
<?php
}

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_65f2d6c96aec1',
	'title' => 'Subtitulo producto',
	'fields' => array(
		array(
			'key' => 'field_65f2d6c91472b',
			'label' => 'Subtitulo producto',
			'name' => 'subtitulo_producto',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'product',
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
) );
} );
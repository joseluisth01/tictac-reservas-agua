<?php
if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'grupo_heroseo',
        'title' => 'Heroseo',
        'fields' => array(
            array(
                'key' => 'heroseo_imagen_fondo',
                'label' => 'Imagen Fondo (Nubes)',
                'name' => 'hero_imagen_fondo',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen de fondo con las nubes',
            ),
            array(
                'key' => 'heroseo_imagen_lupa',
                'label' => 'Imagen Lupa (Encima)',
                'name' => 'hero_imagen_lupa',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen de la lupa que va encima',
            ),
            array(
                'key' => 'heroseo_imagen_aumento',
                'label' => 'Imagen Aumento (Debajo de Lupa)',
                'name' => 'hero_imagen_aumento',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen de aumento que va debajo de la lupa',
            ),
            array(
                'key' => 'hero_imagen_movil',
                'label' => 'Imagenmovil',
                'name' => 'hero_imagen_movil',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen en movil',
            ),
            array(
                'key' => 'heroseo_titulo',
                'label' => 'Título',
                'name' => 'hero_titulo',
                'type' => 'wysiwyg',
                'required' => 1,
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
            array(
                'key' => 'heroseo_subtitulo',
                'label' => 'Subtítulo',
                'name' => 'hero_subtitulo',
                'type' => 'wysiwyg',
                'required' => 1,
            ),
            array(
                'key' => 'heroseo_shortcode_formulario',
                'label' => 'Shortcode Contact Form 7',
                'name' => 'hero_shortcode_formulario',
                'type' => 'text',
                'required' => 1,
                'instructions' => 'Introduce el shortcode del formulario de Contact Form 7',
                'placeholder' => '[contact-form-7 id="123" title="Formulario"]',
            ),
            array(
                'key' => 'heroseo_imagen_google',
                'label' => 'Imagen Google Estrellas',
                'name' => 'hero_imagen_google',
                'type' => 'image',
                'required' => 1,
                'instructions' => 'Imagen de Google con las 5 estrellas',
            ),
            array(
                'key' => 'heroseo_titulo_valoracion',
                'label' => 'Título Valoración',
                'name' => 'hero_titulo_valoracion',
                'type' => 'text',
                'required' => 1,
                'default_value' => '5/5 EXCELENTE',
            ),
            array(
                'key' => 'heroseo_subtitulo_valoracion',
                'label' => 'Subtítulo Valoración',
                'name' => 'hero_subtitulo_valoracion',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'BASADO EN 68 OPINIONES',
            ),
            array(
                'key' => 'heroseo_imagen_flecha',
                'label' => 'Imagen Flecha Scroll',
                'name' => 'hero_imagen_flecha',
                'type' => 'image',
                'required' => 0,
                'instructions' => 'GIF o imagen de flechas para hacer scroll hacia abajo',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/heroseo',
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

endif;

function heroseo_acf()
{
    acf_register_block_type([
        'name'        => 'Heroseo',
        'title'        => __('Heroseo', 'tictac'),
        'description'    => __('Heroseo principal', 'tictac'),
        'render_callback'  => 'heroseo',
        'mode'        => 'preview',
        'icon'        => 'star-filled',
        'keywords'      => ['custom', 'heroseo', 'bloque'],
    ]);
}

add_action('acf/init', 'heroseo_acf');

function heroseo_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('heroseo', get_stylesheet_directory_uri() . '/assets/functions/blocks/heroseo/heroseo.min.css');
    }
}
add_action('wp_enqueue_scripts', 'heroseo_scripts');

function heroseo($block)
{
    $imagen_fondo = get_field('heroseo_imagen_fondo');
    $imagen_lupa = get_field('heroseo_imagen_lupa');
    $imagen_aumento = get_field('heroseo_imagen_aumento');
    $titulo = get_field('heroseo_titulo');
    $subtitulo = get_field('heroseo_subtitulo');
    $shortcode_formulario = get_field('heroseo_shortcode_formulario');
    $imagen_google = get_field('heroseo_imagen_google');
    $titulo_valoracion = get_field('heroseo_titulo_valoracion');
    $subtitulo_valoracion = get_field('heroseo_subtitulo_valoracion');
    $imagen_flecha = get_field('heroseo_imagen_flecha');
    $hero_imagen_movil = get_field('hero_imagen_movil');
?>
    <div class="<?php if (isset($block['className'])) {
                    echo $block['className'];
                } ?> heroseo">
        <!-- Container imagenes width 100% sin márgenes -->
        <div class="heroseo-imagenes-full">
            <div class="heroseo-imagenes-container">
                <!-- Imagen de fondo (Nubes) -->
                <div class="heroseo-imagen-fondo">
                    <img src="<?= $imagen_fondo['url']; ?>" alt="<?= $imagen_fondo['alt']; ?>">
                </div>
                <!-- Imagen aumento (debajo de la lupa) -->
                <div class="heroseo-imagen-aumento">
                    <img src="<?= $imagen_aumento['url']; ?>" alt="<?= $imagen_aumento['alt']; ?>">
                </div>
                <!-- Imagen encima (Lupa) -->
                <div class="heroseo-imagen-lupa">
                    <img src="<?= $imagen_lupa['url']; ?>" alt="<?= $imagen_lupa['alt']; ?>">
                </div>
            </div>
        </div>

        <!-- Container con márgenes solo para contenido -->
        <div class="containerancho herodrcha">
            <div class="heroseo-contenido">
                <!-- Título -->
                <div class="heroseo-titulo">
                    <?= $titulo; ?>
                </div>

                <!-- Subtítulo -->
                <div class="heroseo-subtitulo">
                    <?= $subtitulo; ?>
                </div>

                <div class="heroimgmovil">
                    <img src="<?= $hero_imagen_movil['url']; ?>" alt="<?= $hero_imagen_movil['alt']; ?>">
                </div>


                <button type="button" class="auditoriagratuita" onclick="">
                    <span class="bloqueanalisis-boton-texto">AUDITORÍA GRATUITA</span>
                </button>

                <!-- Formulario -->
                <div class="heroseo-formulario">
                    <button type="button" class="cerrar-formulario">✕</button>
                    <?= do_shortcode($shortcode_formulario); ?>
                </div>


                <!-- Valoración Google -->
                <div class="heroseo-valoracion align-items-center">
                    <div class="valoracion-imagen me-3">
                        <img src="<?= $imagen_google['url']; ?>" alt="<?= $imagen_google['alt']; ?>" class="img-fluid">
                    </div>
                    <div class="valoracion-texto">
                        <div class="valoracion-titulo fw-bold">
                            <?= $titulo_valoracion; ?>
                        </div>
                        <div class="valoracion-subtitulo">
                            <?= $subtitulo_valoracion; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Flecha scroll -->
        <?php if ($imagen_flecha) : ?>
            <a href="#bienvenido">
                <div class="heroseo-flecha-scroll">
                    <img src="<?= $imagen_flecha['url']; ?>"
                        alt="<?= $imagen_flecha['alt'] ?: 'Scroll hacia abajo'; ?>"
                        class="flecha-animada">
                </div>
            <?php endif; ?>
            </a>
    </div>
    <div class="heroseo-valoracion valmovil align-items-center">
        <div class="valoracion-imagen me-3">
            <img src="<?= $imagen_google['url']; ?>" alt="<?= $imagen_google['alt']; ?>" class="img-fluid">
        </div>
        <div class="valoracion-texto">
            <div class="valoracion-titulo fw-bold">
                <?= $titulo_valoracion; ?>
            </div>
            <div class="valoracion-subtitulo">
                <?= $subtitulo_valoracion; ?>
            </div>
        </div>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function() {

    // Solo ejecutar en móvil (1100px o menos)
    if (!window.matchMedia("(max-width: 1100px)").matches) return;

    const boton = document.querySelector(".auditoriagratuita");
    const formulario = document.querySelector(".heroseo-formulario");
    const cerrarBtn = document.querySelector(".cerrar-formulario");

    if (!boton || !formulario || !cerrarBtn) return;

    // Abrir formulario
    boton.addEventListener("click", function() {
        formulario.classList.add("mostrar");
        boton.style.display = "none";
    });

    // Cerrar formulario
    cerrarBtn.addEventListener("click", function() {
        formulario.classList.remove("mostrar");
        boton.style.setProperty("display", "block", "important");
    });

});
    </script>



<?php
}

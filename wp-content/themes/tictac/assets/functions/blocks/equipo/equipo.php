<?php
// 1. Registrar bloque ACF
function equipo_acf()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name'              => 'equipo',
            'title'             => __('Equipo', 'tictac'),
            'description'       => __('Bloque para mostrar el equipo con efectos hover', 'tictac'),
            'render_callback'   => 'equipo',
            'icon'              => 'groups',
            'mode'              => 'edit',
            'keywords'          => ['custom', 'equipo', 'team', 'personas'],
            'supports'          => [
                'align' => false,
                'mode' => false,
                'jsx'  => true,
            ],
        ]);
    }
}
add_action('acf/init', 'equipo_acf');

// 2. ACF Campos
if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'grupo_equipo',
    'title' => 'Equipo',
    'fields' => [

        [
            'key' => 'field_equipo_titulo',
            'label' => 'Título',
            'name' => 'equipo_titulo',
            'type' => 'text',
            'default_value' => 'NUESTRO EQUIPO ES EXPERTO EN SEO',
        ],

        [
            'key' => 'field_equipo_parrafo',
            'label' => 'Párrafo',
            'name' => 'equipo_parrafo',
            'type' => 'textarea',
            'rows' => 3,
            'default_value' =>
            'Un consultor de nuestra agencia te guiará paso a paso en todo el proyecto.',
        ],

        // Nuevo: flecha izquierda
        [
            'key' => 'field_equipo_flecha_izquierda',
            'label' => 'Flecha Izquierda',
            'name' => 'equipo_flecha_izquierda',
            'type' => 'image',
            'return_format' => 'array',
            'required' => 1,
        ],

        // Nuevo: flecha derecha
        [
            'key' => 'field_equipo_flecha_derecha',
            'label' => 'Flecha Derecha',
            'name' => 'equipo_flecha_derecha',
            'type' => 'image',
            'return_format' => 'array',
            'required' => 1,
        ],

        [
            'key' => 'field_equipo_miembros',
            'label' => 'Miembros del Equipo',
            'name' => 'equipo_miembros',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Agregar Miembro',

            'sub_fields' => [
                [
                    'key' => 'field_miembro_imagen_normal',
                    'label' => 'Imagen Normal',
                    'name' => 'miembro_imagen_normal',
                    'type' => 'image',
                    'return_format' => 'array'
                ],
                [
                    'key' => 'field_miembro_imagen_hover',
                    'label' => 'Imagen Hover',
                    'name' => 'miembro_imagen_hover',
                    'type' => 'image',
                    'return_format' => 'array'
                ],
            ],
        ],

    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/equipo',
            ],
        ],
    ],
    'active' => true,
]);

endif;


// 3. Encolar CSS
function equipo_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('equipo', get_stylesheet_directory_uri() . '/assets/functions/blocks/equipo/equipo.min.css');
    }
}
add_action('wp_enqueue_scripts', 'equipo_scripts');


// 4. RENDER BLOQUE
function equipo($block)
{
    $titulo = get_field('equipo_titulo');
    $parrafo = get_field('equipo_parrafo');
    $miembros = get_field('equipo_miembros');

    // flechas nuevas
    $flecha_izq = get_field('equipo_flecha_izquierda');
    $flecha_der = get_field('equipo_flecha_derecha');
?>
    <div class="equipo <?php echo isset($block['className']) ? esc_attr($block['className']) : ''; ?>">
        <div class="containerancho">

            <!-- HEADER -->
            <div class="equipo-header">
                <h2 class="equipo-titulo"><?php echo esc_html($titulo); ?></h2>
            </div>

            <div class="equipo-contenido">

                <div class="equipo-parrafo">
                    <p><?php echo esc_html($parrafo); ?></p>
                </div>

                <!-- GRID DESKTOP -->
                <?php if (!wp_is_mobile()) : ?>
                    <div class="equipo-grid">
                        <?php foreach ($miembros as $m) : ?>
                            <div class="equipo-miembro">
                                <div class="miembro-imagen-wrapper">
                                    <img src="<?php echo esc_url($m['miembro_imagen_normal']['url']); ?>" class="miembro-imagen-normal">
                                    <img src="<?php echo esc_url($m['miembro_imagen_hover']['url']); ?>" class="miembro-imagen-hover">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <!-- CARRUSEL MÓVIL -->
<?php if (wp_is_mobile()) : ?>

    <!-- JS con imágenes hover -->
    <script>
        window.equipoHoverImages = [
            <?php foreach ($miembros as $m) {
                echo '"' . esc_url($m['miembro_imagen_hover']['url']) . '",';
            } ?>
        ];
    </script>

    <div class="equipo-carousel">

        <div class="equipo-carousel-main">
            <img id="equipo-carousel-main-img"
                src="<?php echo esc_url($miembros[0]['miembro_imagen_hover']['url']); ?>">
        </div>

        <div class="equipo-carousel-thumbs-wrapper">

            <!-- Flecha izquierda personalizada -->
            <button class="carousel-arrow carousel-prev">
                <img src="<?php echo esc_url($flecha_izq['url']); ?>" alt="flecha izquierda">
            </button>

            <div class="equipo-carousel-thumbs" id="thumbsContainer">
                <?php foreach ($miembros as $i => $miembro) : ?>
                    <div class="carousel-thumb <?php echo $i === 0 ? 'active' : '' ?>" data-index="<?php echo $i; ?>">
                        <img src="<?php echo esc_url($miembro['miembro_imagen_normal']['url']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Flecha derecha personalizada -->
            <button class="carousel-arrow carousel-next">
                <img src="<?php echo esc_url($flecha_der['url']); ?>" alt="flecha derecha">
            </button>

        </div>

        <!-- Puntos de paginación -->
        <div class="equipo-carousel-dots">
            <?php foreach ($miembros as $i => $miembro) : ?>
                <button class="carousel-dot <?php echo $i === 0 ? 'active' : '' ?>" 
                        data-index="<?php echo $i; ?>"
                        aria-label="Ir a miembro <?php echo $i + 1; ?>">
                </button>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- JS carrusel -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let currentIndex = 0;

            const main = document.getElementById("equipo-carousel-main-img");
            const thumbs = document.querySelectorAll(".carousel-thumb");
            const dots = document.querySelectorAll(".carousel-dot");
            const prevBtn = document.querySelector(".carousel-prev");
            const nextBtn = document.querySelector(".carousel-next");

            function updateCarousel(i) {
                currentIndex = i;
                main.src = window.equipoHoverImages[i];

                thumbs.forEach(t => t.classList.remove("active"));
                thumbs[i].classList.add("active");

                dots.forEach(d => d.classList.remove("active"));
                dots[i].classList.add("active");

                thumbs[i].scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                    inline: "center"
                });
            }

            thumbs.forEach((thumb, i) => {
                thumb.addEventListener("click", () => updateCarousel(i));
            });

            dots.forEach((dot, i) => {
                dot.addEventListener("click", () => updateCarousel(i));
            });

            prevBtn.addEventListener("click", () => {
                const newI = currentIndex === 0 ? thumbs.length - 1 : currentIndex - 1;
                updateCarousel(newI);
            });

            nextBtn.addEventListener("click", () => {
                const newI = currentIndex === thumbs.length - 1 ? 0 : currentIndex + 1;
                updateCarousel(newI);
            });

        });
    </script>

<?php endif; ?>

            </div>
        </div>
    </div>
<?php
}

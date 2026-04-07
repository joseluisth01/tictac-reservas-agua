<?php
if (!is_front_page()) {
    	//$enquepodemosayudarte = obtener_bloque_reutilizable(417);
    // 	$loquedicendenosotros = obtener_bloque_reutilizable(317);
    // 	$confianennosotros = obtener_bloque_reutilizable(320);
    // 	$empresacolaboradora = obtener_bloque_reutilizable(321);

    // if ($enquepodemosayudarte) {
    //     echo '<div class="mt-5"></div>';
    //     echo $enquepodemosayudarte;
    //     echo '<div class="mb-5" style="padding-bottom:20px"></div>';
    // }
    // if ($loquedicendenosotros) {
    //     echo $loquedicendenosotros;
    //     echo '<div class="mb-4"></div>';
    //     echo '<div class="mb-2"></div>';
    // }
    // if ($confianennosotros) {
    //     echo $confianennosotros;
    //     echo '<div class="mb-5"></div>';
    // }
    // if ($empresacolaboradora) {
    //     echo $empresacolaboradora;
    //     echo '<div class="mb-5"></div>';
    //     echo '<div class="mb-5"></div>';
    // }
}

//subvencion_footer(); 
?>
<footer id="footer" class="">
    <?php if (get_field("imagen_footer", "options")) {
        $bgfooter = get_field("imagen_footer", "options");
    ?>
    <?php } ?>

    <?php
    $phone = get_field("footer_telefono_1", "options");
    $whatsapp = get_field("whatsapp", "options");
    $fax = get_field("fax", "options");
    $email = get_field("footer_email", "options");
    $info = get_field("field_footer_informacion", "options");
    $instagram = get_field("instagram", "options");
    $facebook = get_field("facebook", "options");
        $twitter = get_field("twitter", "options");
    $linkedin = get_field("ln", "options");
    $direccion = get_field("direccion", "options");
            $tictac = get_field("tictac", "options");
    ?>

    <div class="containerancho2 footer-main-content  d-none d-lg-block">
        <!-- FILA 1: 4 COLUMNAS -->
        <div class="footer-row-1">
   
            <!-- COLUMNA 2: Menú CATEGORÍAS -->
            <div class="footer-col footer-col-2 align-items-start">
                <p class="footer-menu-title">MENÚ</p>
                <?php
                if (has_nav_menu('menufooter-categorias')) {
                    wp_nav_menu([
                        'theme_location' => 'menufooter-categorias',
                        'container' => false,
                        'menu_class' => 'footer-menu',
                        'fallback_cb' => false
                    ]);
                } else {
                    echo '<p class="no-menu">Menú no asignado</p>';
                }
                ?>
            </div>


         <div class="footer-logo d-flex flex-column">
        <img class="bgfooter" src="<?= $bgfooter['url']; ?>" alt="<?= $bgfooter['alt']; ?>">


<div class="footer-social-row d-flex flex-row justify-content-between mt-3">




<span class="custom-foother-span">
    SÍGUENOS:
</span>



<div class="footer-social-icons d-flex flex-row justify-content-around">

                    	 <?php if ($instagram) { ?>
						<a  class="me-2 imgSocial" href="<?= $instagram["url"]; ?>">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram.svg" alt="">
						</a>
						<?php } ?>

  	 <?php if ($twitter) { ?>
						<a  class="me-2 imgSocial" href="<?= $twitter["url"]; ?>">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/twitter.svg" alt="">
						</a>
						<?php } ?>


						<?php if ($facebook) { ?>
							<a class="me-2 imgSocial" href="<?= $facebook["url"]; ?>">
								<img  src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.svg" alt="">
							</a>
                            	<?php } ?>
</div>




                    <?php if ($email) { ?>
                        <div class="footer-contact-item footer-email">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mailf.svg" alt="">
                            <div class="footer-contact-text">
                                <a href="<?= $email["url"]; ?>" rel="noindex nofollow"><?= $email["title"]; ?></a>
                            </div>
                        </div>
                    <?php } ?>
</div>





                </div>





            <!-- COLUMNA 3: Menú ATENCIÓN AL CLIENTE -->
            <div class="footer-col footer-col-3 align-items-end">
                <p class="footer-menu-title">ENLACES</p>
                <?php
                if (has_nav_menu('menufooter-atencioncliente')) {
                    wp_nav_menu([
                        'theme_location' => 'menufooter-atencioncliente',
                        'container' => false,
                        'menu_class' => 'footer-menu',
                        'fallback_cb' => false
                    ]);
                } else {
                    echo '<p class="no-menu">Menú no asignado</p>';
                }
                ?>
            </div>

          
        </div>

        <div class="footer-bottom-wrapper">

            <!-- FILA 3: COPYRIGHT -->
            <div class="footer-row-3">
                <p class="footer-copyright">
                    © <?= date('Y'); ?> Copyright 2026 |  Diseñado y Desarrollado por <a href="https://tictac-comunicacion.es/" target="_blank">
                     <img class="" src="<?= $tictac['url']; ?>" alt="<?= $tictac['alt']; ?>">
                        
                                                </a>

                    </a>
                </p>
            </div>


            <div class="footer-row-2">
                <?php
                $featured_posts = get_field('legal', 'options');
                if ($featured_posts) : ?>
                    <ul class="footer-politicas">
                        <?php foreach ($featured_posts as $featured_post) :
                            $permalink = get_permalink($featured_post->ID);
                            $title = get_the_title($featured_post->ID);
                        ?>
                            <li>
                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>


        </div>

    </div>











    <!--version movil!!!!!!!!!!  -->




     <div class="containerancho2 footer-main-content d-block d-lg-none">



        <img class="bgfooter my-5" src="<?= $bgfooter['url']; ?>" alt="<?= $bgfooter['alt']; ?>">




        <!-- FILA 1: 4 COLUMNAS -->
        <div class="footer-row-1">
   
            <!-- COLUMNA 2: Menú CATEGORÍAS -->
            <div class="footer-col footer-col-2 align-items-center mt-3">
                <p class="footer-menu-title">MENÚ</p>
                <?php
                if (has_nav_menu('menufooter-categorias')) {
                    wp_nav_menu([
                        'theme_location' => 'menufooter-categorias',
                        'container' => false,
                        'menu_class' => 'footer-menu',
                        'fallback_cb' => false
                    ]);
                } else {
                    echo '<p class="no-menu">Menú no asignado</p>';
                }
                ?>
            </div>




       <!-- COLUMNA 3: Menú ATENCIÓN AL CLIENTE -->
            <div class="footer-col footer-col-3 align-items-center">
                <p class="footer-menu-title">ENLACES</p>
                <?php
                if (has_nav_menu('menufooter-atencioncliente')) {
                    wp_nav_menu([
                        'theme_location' => 'menufooter-atencioncliente',
                        'container' => false,
                        'menu_class' => 'footer-menu',
                        'fallback_cb' => false
                    ]);
                } else {
                    echo '<p class="no-menu">Menú no asignado</p>';
                }
                ?>
            </div>



         <div class="footer-logo d-flex flex-column">


<div class="footer-social-row d-flex flex-row justify-content-center mt-3">




<span class="custom-foother-span">
    SÍGUENOS:
</span>



<div class="footer-social-icons d-flex flex-row justify-content-around">

                    	 <?php if ($instagram) { ?>
						<a  class="me-2 imgSocial" href="<?= $instagram["url"]; ?>">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram.svg" alt="">
						</a>
						<?php } ?>

  	 <?php if ($twitter) { ?>
						<a  class="me-2 imgSocial" href="<?= $twitter["url"]; ?>">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/twitter.svg" alt="">
						</a>
						<?php } ?>


						<?php if ($facebook) { ?>
							<a class="me-2 imgSocial" href="<?= $facebook["url"]; ?>">
								<img  src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.svg" alt="">
							</a>
                            	<?php } ?>
</div>




                    <?php if ($email) { ?>
                        <div class="footer-contact-item footer-email">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mailf.svg" alt="">
                            <div class="footer-contact-text">
                                <a href="<?= $email["url"]; ?>" rel="noindex nofollow"><?= $email["title"]; ?></a>
                            </div>
                        </div>
                    <?php } ?>
</div>





                </div>





     

          
        </div>

        <div class="footer-bottom-wrapper">

            <!-- FILA 3: COPYRIGHT -->
            <div class="footer-row-3">
                <p class="footer-copyright">
                    © <?= date('Y'); ?> Copyright 2026 |  Diseñado y Desarrollado por <a href="https://tictac-comunicacion.es/" target="_blank">
                     <img class="" src="<?= $tictac['url']; ?>" alt="<?= $tictac['alt']; ?>">
                        
                                                </a>

                    </a>
                </p>
            </div>


            <div class="footer-row-2">
                <?php
                $featured_posts = get_field('legal', 'options');
                if ($featured_posts) : ?>
                    <ul class="footer-politicas">
                        <?php foreach ($featured_posts as $featured_post) :
                            $permalink = get_permalink($featured_post->ID);
                            $title = get_the_title($featured_post->ID);
                        ?>
                            <li>
                                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>


        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <title><?php wp_title(); ?></title>
  <?php wp_head(); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet preload" as="style" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <link rel="icon" type="image/x-icon" href="<?= site_url('/favicon.ico'); ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Genos:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Genos:ital,wght@0,100..900;1,100..900&display=swap');
  </style>

</head>

<?php $post_slug = get_post_field('post_name', get_post()); ?>

<body <?php
  $whatsapp = get_field("whatsapp", "options");
  body_class();
?> id="<?php echo $post_slug; ?>">


  <header id="header" class="containerancho">
    <div class="header-logo">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php
          if (function_exists('get_custom_logo')) {
            echo get_custom_logo();
          }
        ?>
      </a>
    </div>

    <a href="<?php echo esc_url(home_url('/contacto')); ?>" class="contacto-link d-flex d-lg-none justify-content-center">
      <div class="contacto d-flex justify-content-center">
        RESERVAR
        <img src="<?php echo site_url('/wp-content/uploads/2026/01/Vector-55.svg'); ?>" alt="">
      </div>
    </a>

    <!-- Botón hamburguesa (solo mobile) -->
    <button class="mobile-menu-toggle mobile-only" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <!-- Navegación: en mobile cuelga como persiana del header -->
    <div class="header-nav-wrapper">
      <div class="main-nav">
        <nav class="header-nav">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'menu-header',
            'container'      => false,
            'menu_class'     => 'main-menu'
          ));
          ?>
        </nav>
      </div>

      <a href="<?php echo esc_url(home_url('/contacto')); ?>" class="contacto-link d-none d-lg-flex">
        <div class="contacto">
        <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="23" viewBox="0 0 24 23" fill="none">
  <path d="M4.8 13.8H14.4V11.5H4.8V13.8ZM4.8 10.35H19.2V8.05H4.8V10.35ZM4.8 6.9H19.2V4.6H4.8V6.9ZM0 23V2.3C0 1.6675 0.235 1.12604 0.705 0.675625C1.175 0.225208 1.74 0 2.4 0H21.6C22.26 0 22.825 0.225208 23.295 0.675625C23.765 1.12604 24 1.6675 24 2.3V16.1C24 16.7325 23.765 17.274 23.295 17.7244C22.825 18.1748 22.26 18.4 21.6 18.4H4.8L0 23ZM3.78 16.1H21.6V2.3H2.4V17.3937L3.78 16.1Z" fill="white"/>
</svg>
          RESERVAR AHORA
          <img src="<?php echo site_url('/wp-content/uploads/2026/01/Vector-55.svg'); ?>" alt="">
        </div>
      </a>
    </div>
  </header>

  <!-- Panel submenú desktop -->
  <div id="submenu-panel" class="submenu-panel">
    <button class="submenu-panel-close" aria-label="Cerrar submenú">&times;</button>
    <div class="d-flex flex-row justify-content-between">
      <div class="submenu-panel-content"></div>
      <?php if ($whatsapp): ?>
        <section class="d-flex flex-column justify-content-end">
          <a class="btn-submenu" target="_blank" href="<?= $whatsapp['url']; ?>">
            <img class="image_contact" src="<?= get_stylesheet_directory_uri(); ?>/assets/images/btn2.svg" alt="">
            <?= $whatsapp["title"]; ?>
          </a>
        </section>
      <?php endif; ?>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggle = document.querySelector('.mobile-menu-toggle');
      const navWrapper = document.querySelector('.header-nav-wrapper');
      const header = document.getElementById('header');

      // Toggle menú hamburguesa
      if (toggle) {
        toggle.addEventListener('click', function(e) {
          e.stopPropagation();
          this.classList.toggle('active');
          navWrapper.classList.toggle('active');
          header.classList.toggle('menu-active');
          document.body.classList.toggle('menu-open');
        });
      }

      // Cerrar menú al hacer clic fuera
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1450) {
          if (!e.target.closest('#header')) {
            if (navWrapper && navWrapper.classList.contains('active')) {
              toggle.classList.remove('active');
              navWrapper.classList.remove('active');
              header.classList.remove('menu-active');
              document.body.classList.remove('menu-open');
            }
          }
        }
      });

      // Cerrar menú al hacer clic en un enlace de submenú (mobile)
      if (window.innerWidth <= 1450) {
        const menuLinks = document.querySelectorAll('.header-nav-wrapper .sub-menu a');
        menuLinks.forEach(link => {
          link.addEventListener('click', function() {
            toggle.classList.remove('active');
            navWrapper.classList.remove('active');
            header.classList.remove('menu-active');
            document.body.classList.remove('menu-open');
          });
        });
      }

      // Limpiar al cambiar tamaño
      let resizeTimer;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          if (window.innerWidth > 1450) {
            toggle.classList.remove('active');
            navWrapper.classList.remove('active');
            header.classList.remove('menu-active');
            document.body.classList.remove('menu-open');
            closeSubmenuPanel();
          }
        }, 250);
      });

      // =============================================
      // SUBMENÚ DESKTOP
      // =============================================
      const panel = document.getElementById('submenu-panel');
      const panelContent = panel.querySelector('.submenu-panel-content');
      const panelClose = panel.querySelector('.submenu-panel-close');
      let currentOpenItem = null;

      function closeSubmenuPanel() {
        panel.classList.remove('active');
        if (currentOpenItem) {
          currentOpenItem.classList.remove('submenu-open');
        }
        currentOpenItem = null;
      }

      function openSubmenuPanel(menuItem) {
        const subMenu = menuItem.querySelector(':scope > .sub-menu');
        if (!subMenu) return;

        const headerRect = header.getBoundingClientRect();
        const itemRect = menuItem.getBoundingClientRect();

   panel.style.top = (headerRect.top + 40) + 'px';
        panel.style.left = headerRect.left + 'px';
        panel.style.right = (window.innerWidth - headerRect.right) + 'px';
        panel.style.borderRadius = '0 0 ' + getComputedStyle(header).borderRadius.split(' ')[0] + ' ' + getComputedStyle(header).borderRadius.split(' ')[0];

        const contentLeft = (itemRect.left - headerRect.left) * 0.5; 
        panelContent.innerHTML = '';
        panelContent.style.paddingLeft = contentLeft + 'px';

        const clonedList = subMenu.cloneNode(true);
        panelContent.appendChild(clonedList);

        if (currentOpenItem) {
          currentOpenItem.classList.remove('submenu-open');
        }
        menuItem.classList.add('submenu-open');
        currentOpenItem = menuItem;
        panel.classList.add('active');
      }

      panelClose.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeSubmenuPanel();
      });

      if (window.innerWidth > 1450) {
        const menuItemsWithChildren = document.querySelectorAll('.main-nav .main-menu > li.menu-item-has-children');

        menuItemsWithChildren.forEach(function(menuItem) {
          const menuLink = menuItem.querySelector(':scope > a');

          menuLink.addEventListener('click', function(e) {
            if (menuItem.classList.contains('submenu-open')) {
              return;
            }
            e.preventDefault();
            openSubmenuPanel(menuItem);
          });
        });

        document.addEventListener('click', function(e) {
          if (!e.target.closest('#header') && !e.target.closest('#submenu-panel')) {
            closeSubmenuPanel();
          }
        });

        window.addEventListener('scroll', function() {
          if (currentOpenItem && panel.classList.contains('active')) {
            const headerRect = header.getBoundingClientRect();
          panel.style.top = (headerRect.top + 40) + 'px';
            panel.style.left = headerRect.left + 'px';
            panel.style.right = (window.innerWidth - headerRect.right) + 'px';
          }
        });
      }
    });
  </script>

  <style>
    @media (max-width: 1450px) {
      #submenu-panel {
        display: none !important;
      }
    }
  </style>
(function($) {
    'use strict';
    
    function initParallax() {
        const bloques = document.querySelectorAll('.bloqueanalisis-contenido');
        
        if (bloques.length === 0) return;
        
        // Detectar si es móvil
        const isMobile = window.innerWidth <= 991;
        
        if (isMobile) return; // No aplicar parallax en móvil
        
        function updateParallax() {
            bloques.forEach(function(bloque) {
                const rect = bloque.getBoundingClientRect();
                const scrollPercent = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                
                // Solo aplicar si el elemento está visible en viewport
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const yPos = -(scrollPercent * 100 - 50);
                    bloque.style.backgroundPosition = `center ${yPos}px`;
                }
            });
        }
        
        // Throttle para optimizar rendimiento
        let ticking = false;
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateParallax();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Inicializar al cargar
        updateParallax();
        
        // Reinicializar al redimensionar
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                location.reload();
            }, 250);
        });
    }
    
    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        initParallax();
    });
    
})(jQuery);
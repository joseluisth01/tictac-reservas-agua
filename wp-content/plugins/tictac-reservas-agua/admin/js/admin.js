/**
 * TicTac Reservas Agua - Admin JS
 * Por implementar en la fase de desarrollo del admin panel.
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Confirmación de eliminación
        $('.ttra-delete-btn').on('click', function(e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
                e.preventDefault();
            }
        });

        // Media uploader para imágenes
        $('.ttra-media-upload').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var frame = wp.media({
                title: 'Seleccionar imagen',
                button: { text: 'Usar esta imagen' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                button.siblings('.ttra-media-id').val(attachment.id);
                button.siblings('.ttra-media-preview').attr('src', attachment.sizes.thumbnail.url).show();
            });
            frame.open();
        });
    });
})(jQuery);

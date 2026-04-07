<?php
/**
 * Email template: recordatorio
 * Variables disponibles: $reserva, $lineas (donde aplique)
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden;">
    <div style="background: #003B6F; color: #fff; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">Reserva <?php echo esc_html( $reserva->codigo_reserva ); ?></h1>
    </div>
    <div style="padding: 30px;">
        <!-- Contenido por implementar según plantilla -->
        <p>Plantilla: recordatorio</p>
    </div>
</div>
</body>
</html>

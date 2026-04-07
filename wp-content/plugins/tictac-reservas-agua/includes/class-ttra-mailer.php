<?php
/**
 * Sistema de envío de correos del plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class TTRA_Mailer {

    private $from_name;
    private $from_email;

    public function __construct() {
        $this->from_name  = TTRA_Settings::get( 'email_from_name', get_bloginfo( 'name' ) );
        $this->from_email = TTRA_Settings::get( 'email_from_address', get_option( 'admin_email' ) );
    }

    /**
     * Email de confirmación al cliente.
     */
    public function enviar_confirmacion( $reserva ) {
        if ( ! TTRA_Settings::get( 'email_confirmacion', 1 ) ) return;

        $lineas  = TTRA_Reserva::get_lineas( $reserva->id );
        $subject = sprintf( __( 'Confirmación de reserva %s', 'tictac-reservas-agua' ), $reserva->codigo_reserva );
        $body    = $this->render_template( 'confirmacion', array(
            'reserva' => $reserva,
            'lineas'  => $lineas,
        ) );

        $sent = $this->send( $reserva->email, $subject, $body );
        $this->log( $reserva->id, $reserva->email, $subject, 'confirmacion', $sent );
    }

    /**
     * Email de cancelación al cliente.
     */
    public function enviar_cancelacion( $reserva ) {
        if ( ! TTRA_Settings::get( 'email_cancelacion', 1 ) ) return;

        $subject = sprintf( __( 'Reserva %s cancelada', 'tictac-reservas-agua' ), $reserva->codigo_reserva );
        $body    = $this->render_template( 'cancelacion', array( 'reserva' => $reserva ) );

        $sent = $this->send( $reserva->email, $subject, $body );
        $this->log( $reserva->id, $reserva->email, $subject, 'cancelacion', $sent );
    }

    /**
     * Email recordatorio al cliente (24h antes).
     */
    public function enviar_recordatorio( $reserva ) {
        if ( ! TTRA_Settings::get( 'email_recordatorio', 1 ) ) return;

        $lineas  = TTRA_Reserva::get_lineas( $reserva->id );
        $subject = sprintf( __( 'Recordatorio: tu reserva %s es mañana', 'tictac-reservas-agua' ), $reserva->codigo_reserva );
        $body    = $this->render_template( 'recordatorio', array(
            'reserva' => $reserva,
            'lineas'  => $lineas,
        ) );

        $sent = $this->send( $reserva->email, $subject, $body );
        $this->log( $reserva->id, $reserva->email, $subject, 'recordatorio', $sent );
    }

    /**
     * Notificación al admin de nueva reserva.
     */
    public function notificar_admin_nueva_reserva( $reserva ) {
        if ( ! TTRA_Settings::get( 'email_admin_nueva', 1 ) ) return;

        $admin_email = TTRA_Settings::get( 'email_admin', get_option( 'admin_email' ) );
        $lineas      = TTRA_Reserva::get_lineas( $reserva->id );
        $subject     = sprintf( __( 'Nueva reserva #%s - %s', 'tictac-reservas-agua' ), $reserva->codigo_reserva, $reserva->nombre );
        $body        = $this->render_template( 'admin-nueva-reserva', array(
            'reserva' => $reserva,
            'lineas'  => $lineas,
        ) );

        $sent = $this->send( $admin_email, $subject, $body );
        $this->log( $reserva->id, $admin_email, $subject, 'admin_nueva', $sent );
    }

    /* ─── Utilidades internas ─── */

    private function send( $to, $subject, $body ) {
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            "From: {$this->from_name} <{$this->from_email}>",
        );

        return wp_mail( $to, $subject, $body, $headers );
    }

    private function render_template( $template_name, $data = array() ) {
        extract( $data );
        ob_start();

        $template_path = TTRA_PLUGIN_DIR . "templates/emails/{$template_name}.php";
        if ( file_exists( $template_path ) ) {
            include $template_path;
        } else {
            echo '<p>' . __( 'Plantilla de email no encontrada.', 'tictac-reservas-agua' ) . '</p>';
        }

        return ob_get_clean();
    }

    private function log( $reserva_id, $destinatario, $asunto, $tipo, $sent ) {
        TTRA_DB::insert( 'email_log', array(
            'reserva_id'   => $reserva_id,
            'destinatario' => $destinatario,
            'asunto'       => $asunto,
            'tipo'         => $tipo,
            'estado'       => $sent ? 'enviado' : 'fallido',
        ) );
    }
}

<?php
/**
 * Integración con pasarela de pago Redsys.
 * Soporta: Tarjeta, Bizum, Google Pay, Apple Pay.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class TTRA_Redsys {

    private $entorno;
    private $fuc;
    private $terminal;
    private $clave_secreta;
    private $nombre_comercio;

    const URL_TEST = 'https://sis-t.redsys.es:25443/sis/realizarPago';
    const URL_PROD = 'https://sis.redsys.es/sis/realizarPago';

    public function __construct() {
        $this->entorno          = TTRA_Settings::get( 'redsys_entorno', 'test' );
        $this->fuc              = TTRA_Settings::get( 'redsys_fuc' );
        $this->terminal         = TTRA_Settings::get( 'redsys_terminal', '001' );
        $this->clave_secreta    = TTRA_Settings::get( 'redsys_clave_secreta' );
        $this->nombre_comercio  = TTRA_Settings::get( 'redsys_nombre_comercio' );
    }

    /**
     * URL del TPV según el entorno.
     */
    public function get_url() {
        return $this->entorno === 'produccion' ? self::URL_PROD : self::URL_TEST;
    }

    /**
     * Genera los parámetros del formulario de pago.
     */
    public function generar_formulario( $reserva, $metodo_pago = 'tarjeta' ) {
        $order_id = $this->generar_order_id( $reserva->id );
        $importe  = intval( $reserva->total * 100 ); // Céntimos

        $params = array(
            'DS_MERCHANT_AMOUNT'           => $importe,
            'DS_MERCHANT_ORDER'            => $order_id,
            'DS_MERCHANT_MERCHANTCODE'     => $this->fuc,
            'DS_MERCHANT_CURRENCY'         => '978', // EUR
            'DS_MERCHANT_TRANSACTIONTYPE'  => '0',   // Autorización
            'DS_MERCHANT_TERMINAL'         => $this->terminal,
            'DS_MERCHANT_MERCHANTURL'      => $this->get_notification_url(),
            'DS_MERCHANT_URLOK'            => $this->get_return_url( $reserva->codigo_reserva, 'ok' ),
            'DS_MERCHANT_URLKO'            => $this->get_return_url( $reserva->codigo_reserva, 'ko' ),
            'DS_MERCHANT_MERCHANTNAME'     => $this->nombre_comercio,
            'DS_MERCHANT_CONSUMERLANGUAGE' => '001', // Español
            'DS_MERCHANT_PRODUCTDESCRIPTION' => sprintf(
                __( 'Reserva %s', 'tictac-reservas-agua' ),
                $reserva->codigo_reserva
            ),
        );

        // Método de pago específico
        switch ( $metodo_pago ) {
            case 'bizum':
                $params['DS_MERCHANT_PAYMETHODS'] = 'z'; // Bizum
                break;
            case 'google_pay':
                $params['DS_MERCHANT_PAYMETHODS'] = 'xpay';
                break;
            case 'apple_pay':
                $params['DS_MERCHANT_PAYMETHODS'] = 'xpay';
                break;
            default:
                $params['DS_MERCHANT_PAYMETHODS'] = 'C'; // Tarjeta
                break;
        }

        $params_json    = json_encode( $params );
        $params_base64  = base64_encode( $params_json );
        $signature      = $this->firmar( $params_base64, $order_id );

        // Registrar pago pendiente en DB
        TTRA_Pago::create( array(
            'reserva_id'     => $reserva->id,
            'metodo'         => $metodo_pago,
            'estado'         => 'pendiente',
            'importe'        => $reserva->total,
            'transaccion_id' => $order_id,
        ) );

        return array(
            'url'               => $this->get_url(),
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
            'Ds_MerchantParameters' => $params_base64,
            'Ds_Signature'      => $signature,
        );
    }

    /**
     * Procesa la notificación de Redsys (callback).
     */
    public function procesar_notificacion( $post_data ) {
        if ( empty( $post_data['Ds_MerchantParameters'] ) || empty( $post_data['Ds_Signature'] ) ) {
            return new WP_Error( 'invalid', 'Datos de notificación inválidos.' );
        }

        $params_base64 = $post_data['Ds_MerchantParameters'];
        $firma_recibida = $post_data['Ds_Signature'];
        $params = json_decode( base64_decode( $params_base64 ), true );

        $order_id = $params['Ds_Order'] ?? '';
        $firma_calculada = $this->firmar( $params_base64, $order_id );

        // Verificar firma
        if ( $this->firma_segura( $firma_calculada ) !== $this->firma_segura( $firma_recibida ) ) {
            return new WP_Error( 'signature', 'Firma no válida.' );
        }

        $response_code = intval( $params['Ds_Response'] ?? 9999 );
        $auth_code     = $params['Ds_AuthorisationCode'] ?? '';
        $reserva_id    = $this->extraer_reserva_id( $order_id );

        $pago = TTRA_Pago::get_by_transaccion( $order_id );

        if ( $response_code >= 0 && $response_code <= 99 ) {
            // Pago exitoso
            if ( $pago ) {
                TTRA_Pago::update( $pago->id, array(
                    'estado'              => 'completado',
                    'codigo_autorizacion' => $auth_code,
                    'datos_respuesta'     => json_encode( $params ),
                ) );
            }

            TTRA_Reserva::marcar_pagada( $reserva_id, $pago->metodo ?? 'tarjeta', $order_id );

            // Enviar email de confirmación
            $reserva = TTRA_Reserva::get_by_id( $reserva_id );
            if ( $reserva ) {
                $mailer = new TTRA_Mailer();
                $mailer->enviar_confirmacion( $reserva );
                $mailer->notificar_admin_nueva_reserva( $reserva );
            }

            return array( 'success' => true, 'reserva_id' => $reserva_id );

        } else {
            // Pago fallido
            if ( $pago ) {
                TTRA_Pago::marcar_fallido( $pago->id, json_encode( $params ) );
            }
            return new WP_Error( 'payment_failed', "Pago rechazado. Código: $response_code" );
        }
    }

    /* ─── Utilidades internas ─── */

    private function generar_order_id( $reserva_id ) {
        // Redsys requiere 12 chars: 4 dígitos mínimo + alfanuméricos
        return str_pad( $reserva_id, 4, '0', STR_PAD_LEFT ) . strtoupper( wp_generate_password( 8, false ) );
    }

    private function extraer_reserva_id( $order_id ) {
        return intval( substr( $order_id, 0, 4 ) );
    }

    private function firmar( $params_base64, $order_id ) {
        $key = base64_decode( $this->clave_secreta );
        $key = $this->encrypt_3des( $order_id, $key );
        return base64_encode( hash_hmac( 'sha256', $params_base64, $key, true ) );
    }

    private function encrypt_3des( $data, $key ) {
        $iv = "\0\0\0\0\0\0\0\0";
        return openssl_encrypt( $data, 'DES-EDE3-CBC', $key, OPENSSL_RAW_DATA, $iv );
    }

    private function firma_segura( $firma ) {
        return strtr( $firma, '+/', '-_' );
    }

    private function get_notification_url() {
        return rest_url( 'ttra/v1/redsys/notification' );
    }

    private function get_return_url( $codigo_reserva, $resultado ) {
        $base = TTRA_Helpers::get_reservas_url();
        return add_query_arg( array(
            'ttra_result' => $resultado,
            'code'        => $codigo_reserva,
        ), $base );
    }
}

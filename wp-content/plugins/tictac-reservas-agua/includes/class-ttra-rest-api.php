<?php
/**
 * Endpoints REST API del plugin.
 * Namespace: ttra/v1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class TTRA_REST_API {

    const NAMESPACE = 'ttra/v1';

    public function register_routes() {

        // ── Actividades ──
        register_rest_route( self::NAMESPACE, '/actividades', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_actividades' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( self::NAMESPACE, '/actividades/(?P<id>\d+)', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_actividad' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Categorías ──
        register_rest_route( self::NAMESPACE, '/categorias', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_categorias' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Calendario / Disponibilidad ──
        register_rest_route( self::NAMESPACE, '/calendario/(?P<actividad_id>\d+)/(?P<anyo>\d{4})/(?P<mes>\d{1,2})', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_calendario' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( self::NAMESPACE, '/slots/(?P<actividad_id>\d+)/(?P<fecha>\d{4}-\d{2}-\d{2})', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_slots' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Reservas (crear) ──
        register_rest_route( self::NAMESPACE, '/reservas', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'crear_reserva' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( self::NAMESPACE, '/reservas/(?P<codigo>[A-Z0-9-]+)', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_reserva_publica' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Cupones ──
        register_rest_route( self::NAMESPACE, '/cupones/validar', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'validar_cupon' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Pago / Redsys ──
        register_rest_route( self::NAMESPACE, '/pago/iniciar', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'iniciar_pago' ),
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( self::NAMESPACE, '/redsys/notification', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'redsys_notification' ),
            'permission_callback' => '__return_true',
        ) );

        // ── Admin endpoints (protegidos) ──
        register_rest_route( self::NAMESPACE, '/admin/reservas', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'admin_get_reservas' ),
            'permission_callback' => array( $this, 'check_admin' ),
        ) );

        register_rest_route( self::NAMESPACE, '/admin/reservas/(?P<id>\d+)/estado', array(
            'methods'             => 'PUT',
            'callback'            => array( $this, 'admin_cambiar_estado' ),
            'permission_callback' => array( $this, 'check_admin' ),
        ) );

        register_rest_route( self::NAMESPACE, '/admin/stats', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'admin_stats' ),
            'permission_callback' => array( $this, 'check_admin' ),
        ) );
    }

    /* ══════════════════════════════════════════
       CALLBACKS PÚBLICOS
       ══════════════════════════════════════════ */

    public function get_actividades( $request ) {
        $categoria = $request->get_param( 'categoria' );
        if ( $categoria ) {
            $items = TTRA_Actividad::get_by_categoria( intval( $categoria ) );
        } else {
            $items = TTRA_Actividad::get_all_with_categoria();
        }
        return rest_ensure_response( $items );
    }

    public function get_actividad( $request ) {
        $act = TTRA_Actividad::get_by_id( $request['id'] );
        if ( ! $act ) {
            return new WP_Error( 'not_found', 'Actividad no encontrada.', array( 'status' => 404 ) );
        }
        return rest_ensure_response( $act );
    }

    public function get_categorias() {
        return rest_ensure_response( TTRA_Categoria::get_with_count() );
    }

    public function get_calendario( $request ) {
        $dias = TTRA_Calendario::get_dias_disponibles(
            $request['actividad_id'],
            $request['anyo'],
            $request['mes']
        );
        return rest_ensure_response( $dias );
    }

    public function get_slots( $request ) {
        $slots = TTRA_Calendario::get_slots( $request['actividad_id'], $request['fecha'] );
        return rest_ensure_response( $slots );
    }

    public function crear_reserva( $request ) {
        $params = $request->get_json_params();

        // Validar campos requeridos
        $required = array( 'nombre', 'email', 'telefono', 'dni_pasaporte', 'actividades' );
        foreach ( $required as $field ) {
            if ( empty( $params[ $field ] ) ) {
                return new WP_Error( 'missing_field', "Campo requerido: $field", array( 'status' => 400 ) );
            }
        }

        if ( ! is_email( $params['email'] ) ) {
            return new WP_Error( 'invalid_email', 'Email no válido.', array( 'status' => 400 ) );
        }

        // Calcular totales
        $subtotal = 0;
        $lineas_data = array();
        foreach ( $params['actividades'] as $act ) {
            $actividad = TTRA_Actividad::get_by_id( $act['actividad_id'] );
            if ( ! $actividad ) continue;

            $personas = max( 1, intval( $act['personas'] ) );
            $sesiones = max( 1, intval( $act['sesiones'] ) );
            $precio   = TTRA_Actividad::calcular_precio( $act['actividad_id'], $personas, $sesiones );

            $lineas_data[] = array(
                'actividad_id'   => $act['actividad_id'],
                'fecha'          => sanitize_text_field( $act['fecha'] ),
                'hora'           => sanitize_text_field( $act['hora'] ),
                'personas'       => $personas,
                'sesiones'       => $sesiones,
                'precio_unitario' => $actividad->precio_base,
                'precio_total'   => $precio,
            );

            $subtotal += $precio;
        }

        // Crear reserva
        $reserva = TTRA_Reserva::create( array(
            'nombre'          => sanitize_text_field( $params['nombre'] ),
            'apellidos'       => sanitize_text_field( $params['apellidos'] ?? '' ),
            'email'           => sanitize_email( $params['email'] ),
            'telefono'        => sanitize_text_field( $params['telefono'] ),
            'dni_pasaporte'   => sanitize_text_field( $params['dni_pasaporte'] ),
            'fecha_nacimiento' => sanitize_text_field( $params['fecha_nacimiento'] ?? '' ),
            'direccion'       => sanitize_textarea_field( $params['direccion'] ?? '' ),
            'notas'           => sanitize_textarea_field( $params['notas'] ?? '' ),
            'subtotal'        => $subtotal,
            'total'           => $subtotal,
            'ip_cliente'      => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ) );

        if ( ! $reserva ) {
            return new WP_Error( 'create_failed', 'Error al crear la reserva.', array( 'status' => 500 ) );
        }

        // Añadir líneas
        foreach ( $lineas_data as $linea ) {
            TTRA_Reserva::add_linea( $reserva->id, $linea );
        }

        return rest_ensure_response( array(
            'success'        => true,
            'reserva_id'     => $reserva->id,
            'codigo_reserva' => $reserva->codigo_reserva,
            'total'          => $subtotal,
        ) );
    }

    public function get_reserva_publica( $request ) {
        $reserva = TTRA_Reserva::get_by_codigo( $request['codigo'] );
        if ( ! $reserva ) {
            return new WP_Error( 'not_found', 'Reserva no encontrada.', array( 'status' => 404 ) );
        }

        $lineas = TTRA_Reserva::get_lineas( $reserva->id );

        return rest_ensure_response( array(
            'codigo'    => $reserva->codigo_reserva,
            'estado'    => $reserva->estado,
            'nombre'    => $reserva->nombre,
            'total'     => $reserva->total,
            'pagado'    => (bool) $reserva->pagado,
            'lineas'    => $lineas,
            'created_at' => $reserva->created_at,
        ) );
    }

    public function validar_cupon( $request ) {
        $params   = $request->get_json_params();
        $codigo   = sanitize_text_field( $params['codigo'] ?? '' );
        $subtotal = floatval( $params['subtotal'] ?? 0 );

        $result = TTRA_Cupon::validar( $codigo, $subtotal );
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return rest_ensure_response( $result );
    }

    public function iniciar_pago( $request ) {
        $params = $request->get_json_params();

        $reserva = TTRA_Reserva::get_by_codigo( sanitize_text_field( $params['codigo_reserva'] ?? '' ) );
        if ( ! $reserva ) {
            return new WP_Error( 'not_found', 'Reserva no encontrada.', array( 'status' => 404 ) );
        }

        $metodo = sanitize_text_field( $params['metodo_pago'] ?? 'tarjeta' );
        $redsys = new TTRA_Redsys();
        $form   = $redsys->generar_formulario( $reserva, $metodo );

        return rest_ensure_response( $form );
    }

    public function redsys_notification( $request ) {
        $redsys = new TTRA_Redsys();
        $result = $redsys->procesar_notificacion( $request->get_body_params() );

        if ( is_wp_error( $result ) ) {
            return new WP_REST_Response( array( 'error' => $result->get_error_message() ), 400 );
        }

        return new WP_REST_Response( 'OK', 200 );
    }

    /* ══════════════════════════════════════════
       CALLBACKS ADMIN (PROTEGIDOS)
       ══════════════════════════════════════════ */

    public function admin_get_reservas( $request ) {
        $args = array(
            'estado'      => $request->get_param( 'estado' ) ?? '',
            'buscar'      => $request->get_param( 'buscar' ) ?? '',
            'fecha_desde' => $request->get_param( 'fecha_desde' ) ?? '',
            'fecha_hasta' => $request->get_param( 'fecha_hasta' ) ?? '',
            'per_page'    => $request->get_param( 'per_page' ) ?? 20,
            'page'        => $request->get_param( 'page' ) ?? 1,
        );

        return rest_ensure_response( TTRA_Reserva::get_listado( $args ) );
    }

    public function admin_cambiar_estado( $request ) {
        $id     = $request['id'];
        $estado = sanitize_text_field( $request->get_param( 'estado' ) );

        $estados_validos = array( 'pendiente', 'confirmada', 'pagada', 'cancelada', 'completada', 'no_show' );
        if ( ! in_array( $estado, $estados_validos ) ) {
            return new WP_Error( 'invalid_status', 'Estado no válido.', array( 'status' => 400 ) );
        }

        TTRA_Reserva::update( $id, array( 'estado' => $estado ) );

        // Enviar email de cancelación si aplica
        if ( $estado === 'cancelada' ) {
            $reserva = TTRA_Reserva::get_by_id( $id );
            $mailer  = new TTRA_Mailer();
            $mailer->enviar_cancelacion( $reserva );
        }

        return rest_ensure_response( array( 'success' => true ) );
    }

    public function admin_stats( $request ) {
        $periodo = $request->get_param( 'periodo' ) ?? '30days';
        return rest_ensure_response( TTRA_Reserva::stats( $periodo ) );
    }

    /* ── Permission checks ── */

    public function check_admin( $request ) {
        return current_user_can( 'manage_options' );
    }
}

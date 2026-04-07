<?php
/**
 * Modelo: Actividad / Servicio.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class TTRA_Actividad {

    const TABLE = 'actividades';

    public static function get_all( $solo_activas = true ) {
        $where = $solo_activas ? "activa = 1" : "1=1";
        return TTRA_DB::get_all( self::TABLE, $where, 'orden', 'ASC' );
    }

    public static function get_by_id( $id ) {
        return TTRA_DB::get_by_id( self::TABLE, $id );
    }

    public static function create( $data ) {
        return TTRA_DB::insert( self::TABLE, $data );
    }

    public static function update( $id, $data ) {
        return TTRA_DB::update( self::TABLE, $data, array( 'id' => $id ) );
    }

    public static function delete( $id ) {
        return TTRA_DB::delete( self::TABLE, array( 'id' => $id ) );
    }

    /**
     * Actividades por categoría.
     */
    public static function get_by_categoria( $categoria_id, $solo_activas = true ) {
        $where = "categoria_id = " . intval( $categoria_id );
        if ( $solo_activas ) $where .= " AND activa = 1";
        return TTRA_DB::get_all( self::TABLE, $where, 'orden', 'ASC' );
    }

    /**
     * Actividades con info de categoría.
     */
    public static function get_all_with_categoria() {
        global $wpdb;
        $t_act = TTRA_DB::table( 'actividades' );
        $t_cat = TTRA_DB::table( 'categorias' );
        return $wpdb->get_results(
            "SELECT a.*, c.nombre as categoria_nombre, c.slug as categoria_slug
             FROM $t_act a
             LEFT JOIN $t_cat c ON c.id = a.categoria_id
             WHERE a.activa = 1
             ORDER BY c.orden ASC, a.orden ASC"
        );
    }

    /**
     * Calcula el precio para unas personas y sesiones.
     */
    public static function calcular_precio( $actividad_id, $personas, $sesiones ) {
        $act = self::get_by_id( $actividad_id );
        if ( ! $act ) return 0;

        if ( $act->precio_tipo === 'por_persona' ) {
            return $act->precio_base * $personas * $sesiones;
        }
        return $act->precio_base * $sesiones;
    }

    /**
     * Obtiene los horarios de una actividad para un día concreto.
     */
    public static function get_horarios_dia( $actividad_id, $fecha ) {
        $dia_semana = date( 'N', strtotime( $fecha ) ) - 1; // 0=lunes
        return TTRA_Horario::get_slots_disponibles( $actividad_id, $dia_semana, $fecha );
    }
}

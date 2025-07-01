<?php
// Evita el acceso directo al archivo
 defined('ABSPATH') or die('Acceso no permitido.');

/**
 * FUNCIONES DEL MODELO
 * ====================
 * Todas las funciones relacionadas con la obtención y manipulación de datos
 */

/**
 * Obtiene todos los mensajes de la tabla
 * @return array Mensajes encontrados
 */
function mpf_obtener_mensajes() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    $sql = $wpdb->prepare("SELECT * FROM %i", $tabla);
    return $wpdb->get_results($sql);
}

/**
 * Obtiene el mensaje activo actual
 * @return object|null Mensaje activo o null si no hay ninguno activo
 */
function mpf_obtener_mensaje_activo() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE activo = %d LIMIT 1", $tabla, 1));
}

/**
 * Inserta un nuevo mensaje en la tabla
 * @param string $mensaje Texto del mensaje
 * @param string $tipo_font Tipo de fuente
 * @param int $negrita Si está en negrita (0=no, 1=sí)
 * @param string $color Color del texto
 * @param int $activo Si está activo (0=no, 1=sí)
 * @return bool|int ID del registro insertado o false si falló
 */
function mpf_insertar_mensaje($mensaje, $tipo_font, $negrita, $color, $activo) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    $datos = [
        'mpf_mensaje' => $mensaje,
        'mpf_tipo_font' => $tipo_font,
        'mpf_negrita' => $negrita,
        'mpf_color' => $color,
        'activo' => $activo
    ];
    
    return $wpdb->insert($tabla, $datos);
}

/**
 * Activa un mensaje específico y desactiva los demás
 * @param int $id ID del mensaje a activar
 * @return bool True si se activó correctamente
 */
function mpf_activar_mensaje($id) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    // Primero desactivamos todos los mensajes
    $wpdb->update($tabla, ['activo' => 0], ['activo' => 1]);
    
    // Luego activamos el mensaje seleccionado
    return $wpdb->update($tabla, ['activo' => 1], ['id' => $id]);
}

/**
 * Cuenta el número de mensajes en la tabla
 * @return int Número de mensajes
 */
function mpf_contar_mensajes() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i", $tabla));
}

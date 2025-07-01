<?php
// Evita el acceso directo al archivo
 defined('ABSPATH') or die('Acceso no permitido.');

/**
 * FUNCIONES DEL CONTROLADOR
 * =========================
 * Todas las funciones relacionadas con el procesamiento de datos y formularios
 */

/**
 * Procesa las acciones del usuario (activar mensaje)
 * @return string Mensaje de éxito o error
 */
function mpf_procesar_acciones() {
    $mensaje = '';
    
    if(isset($_GET['accion']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);  // Convertir a entero para seguridad
        
        if($_GET['accion'] == 'activar') {
            // Activar el mensaje seleccionado
            $resultado = mpf_activar_mensaje($id);
            
            if ($resultado) {
                $mensaje = "<div class='updated'><p>Mensaje activado correctamente</p></div>";
            } else {
                $mensaje = "<div class='error'><p>Error al activar el mensaje</p></div>";
            }
        }
    }
    
    return $mensaje;
}

/**
 * Procesa el formulario de nuevo mensaje
 * @return string Mensaje de éxito o error
 */
function mpf_procesar_formulario() {
    $mensaje_resultado = '';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        // ⚠️ Validación de seguridad con FormHelper (evita ataques CSRF)
if (!FormHelper::verificar()) {
    wp_die('Token CSRF inválido.');
}
        // Limpiar todo el array POST de una vez
    $datos = FormHelper::limpiar_campos(['mpf_mensaje', 'mpf_tipo_font', 'mpf_negrita', 'mpf_color']);

        // Verificar campo obligatorio
        if (empty($datos['mpf_mensaje'])) {
            return "<div class='error'><p>El mensaje no puede estar vacío</p></div>";
        }

        // Procesar campos
        $mensaje = $datos['mpf_mensaje'];
        $tipo_font = $datos['mpf_tipo_font'];
        $negrita = intval($datos['mpf_negrita']);
        $color = $datos['mpf_color'];


        // Activar automáticamente si es el primero
        $activo = (mpf_contar_mensajes() == 0) ? 1 : 0;

        // Guardar en la base de datos
        $resultado = mpf_insertar_mensaje($mensaje, $tipo_font, $negrita, $color, $activo);
        
        if ($resultado) {
            $mensaje_resultado = "<div class='updated'><p>Mensaje guardado correctamente</p></div>";
        } else {
            $mensaje_resultado = "<div class='error'><p>Error al guardar el mensaje</p></div>";
        }
    }

    return $mensaje_resultado;
}

<?php
/**
 * Plugin Name: Mensaje en el Footer
 * Plugin URI: https://example.com/mensaje-footer
 * Description: Plugin para gestionar mensajes personalizados que se mostrarán en el footer de tu sitio web.
 * Funcionalidades:
 * - Añadir nuevos mensajes con diferentes estilos
 * - Ver todos los mensajes guardados
 * - Activar el mensaje que quieres mostrar en el footer
 * - Mostrar solo el mensaje activo en el sitio web
 * Version: 1.0
 * Author: kiko
 * Mi plugin footer
* Requires at least: 5.4
* Requires PHP:8.0
 */

// Evita el acceso directo al archivo
 defined('ABSPATH') or die('Acceso no permitido.');

 // En el archivo principal del plugin
require_once plugin_dir_path(__FILE__) . 'includes/FormHelper.php';

// Carga el archivo de funciones auxiliares
require_once plugin_dir_path(__FILE__) . 'includes/function.php';
// Carga los componentes MVC
require_once plugin_dir_path(__FILE__) . 'model/model.php';
require_once plugin_dir_path(__FILE__) . 'controller/controller.php';
require_once plugin_dir_path(__FILE__) . 'views/view.php';

/**
 * CONFIGURACIÓN DE RECURSOS (CSS/JS)
 * =================================
 * Cargamos los recursos necesarios para que el plugin funcione independientemente
 */
function mpf_cargar_recursos() {
    // Estilos para el panel de administración
    wp_enqueue_style('mpf-admin-styles', plugins_url('assets/css/admin-styles.css', __FILE__));
    
    // Scripts para la funcionalidad del panel de administración
    wp_enqueue_script('mpf-admin-scripts', plugins_url('assets/js/admin-scripts.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'mpf_cargar_recursos');

/**
 * FASE 1: ACTIVACIÓN DEL PLUGIN
 * ============================
 * Cuando el usuario activa el plugin, creamos la tabla en la base de datos
 */
register_activation_hook(__FILE__, 'mpf_crear_tabla');

function mpf_crear_tabla() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    $charset_collate = $wpdb->get_charset_collate();
    
    // Prepara la consulta SQL para crear la tabla con todos los campos necesarios
    $sql = "CREATE TABLE IF NOT EXISTS $tabla (
        id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        mpf_mensaje varchar(200) NOT NULL,   /* Texto del mensaje */
        mpf_tipo_font varchar(150) NOT NULL,  /* Tipo de fuente */
        mpf_negrita int(1) NOT NULL DEFAULT 0,   /* Si está en negrita (0=no, 1=sí) */
        mpf_color varchar(20) NOT NULL DEFAULT 'black', /* Color del texto */
        activo int(1) NOT NULL DEFAULT 0,    /* Si el mensaje está activo (0=no, 1=sí) */
        PRIMARY KEY  (id)
    )" . $charset_collate;

    // Incluye el archivo necesario para usar la función dbDelta
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // La función dbDelta es utilizada para crear o actualizar tablas en la base de datos
    dbDelta($sql);
}

/**
 * FASE 2: CREACIÓN DEL MENÚ DE ADMINISTRACIÓN
 * ==========================================
 * Añade un elemento al menú del panel de WordPress para acceder
 * a la página de configuración del plugin.
 */
add_action('admin_menu', 'mpf_agregar_menu');

function mpf_agregar_menu() {
    add_menu_page(
        'Mensaje Footer',                    // Título que aparece en la pestaña del navegador
        'Mensaje Footer',                    // Título que aparece en el menú lateral
        'manage_options',                    // Nivel de permiso necesario (solo administradores)
        'mensaje-footer',                    // Identificador único de la página
        'mpf_mostrar_pagina_admin',          // Función que genera el contenido de la página
        'dashicons-format-quote',            // Icono que aparece junto al título en el menú
        30                                   // Posición en el menú
    );
}

/**
 * FASE 3: MOSTRAR EL MENSAJE EN EL FOOTER
 * =====================================
 * Esta función se ejecuta cada vez que WordPress carga el footer
 * y se encarga de mostrar el mensaje configurado por el usuario.
 */
add_action('wp_footer', 'mpf_mostrar_mensaje');

function mpf_mostrar_mensaje() {
    // Obtener el mensaje activo del modelo
    $mensaje = mpf_obtener_mensaje_activo();
    
    // Si encontramos un mensaje activo, lo mostramos
    if ($mensaje) {
        // Construir los estilos CSS según la configuración guardada
        $estilo = 'font-family: ' . esc_attr($mensaje->mpf_tipo_font) . '; ';
        $estilo .= 'font-weight: ' . ($mensaje->mpf_negrita == 1 ? 'bold' : 'normal') . '; ';
        $estilo .= 'color: ' . esc_attr($mensaje->mpf_color) . '; ';
        
        // Generar el HTML con el mensaje y sus estilos
        echo '<div id="mpf-mensaje-footer" style="text-align: center; padding: 15px; ' . $estilo . '">';
        echo esc_html($mensaje->mpf_mensaje);  // esc_html para evitar inyección de código
        echo '</div>';
    }
}

/**
 * FASE 4: CREAR SHORTCODE PARA MOSTRAR EL MENSAJE
 * ============================================
 * Este shortcode permite insertar el mensaje en cualquier página o entrada
 * usando [mpf_mensaje] en el editor de WordPress.
 */
add_shortcode('mpf_mensaje', 'mpf_shortcode_mensaje');

function mpf_shortcode_mensaje() {
    // Obtener el mensaje activo del modelo
    $mensaje = mpf_obtener_mensaje_activo();
    
    if (!$mensaje) {
        return '<p>No hay mensajes activos para mostrar.</p>';
    }
    
    // Construir los estilos CSS según la configuración guardada
    $estilo = 'font-family: ' . esc_attr($mensaje->mpf_tipo_font) . '; '; 
    $estilo .= 'font-weight: ' . ($mensaje->mpf_negrita == 1 ? 'bold' : 'normal') . '; ';
    $estilo .= 'color: ' . esc_attr($mensaje->mpf_color) . '; ';
    
    // Generar el HTML con el mensaje y sus estilos
    $html = '<div class="mpf-mensaje-shortcode" style="text-align: center; padding: 15px; ' . $estilo . '">';
    $html .= esc_html($mensaje->mpf_mensaje);
    $html .= '</div>';
    
    return $html;
}


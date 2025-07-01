<?php
/** DESACTIVACION DEL PLUGIN
 * 
 * 
 * Plugin Name: Mi Plugin Básico 
 * Description: Un plugin para perder el tiempo ya que hace poca cosa.
 * Version: 1.0
 * Author: Profe Productionsç
*/

defined('ABSPATH') or die('!Sin acceso directo');


// require_once plugin_dir_path(__FILE__) . 'includes/FormHelper.php';

require_once plugin_dir_path( __FILE__ ). 'includes/function.php';
register_activation_hook(__FILE__, 'mpb_crear_tabla');

register_deactivation_hook(__FILE__, 'mpb_eliminar_tabla'); //  


// Registrar hooks para la administración
// add_action('admin_init', 'mpb_procesar_acciones');
add_action('admin_menu', 'mpb_agregar_menu');

// Función para crear la tabla al activar el plugin
function mpb_crear_tabla(){
    global $wpdb;
    $tabla = $wpdb->prefix.('mpb_datos');
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $tabla (
        id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(100) NOT NULL,
        email varchar(150) NOT NULL,
        PRIMARY KEY(id)
    )".$charset_collate;
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    dbDelta($sql);

}

// ==============================================
// FUNCIÓN NUEVA PARA ELIMINAR LA TABLA (AL DESACTIVAR)
// ==============================================
function mpb_eliminar_tabla() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpb_datos';
    $wpdb->query("DROP TABLE IF EXISTS $tabla"); // Elimina la tabla
}


// Función para agregar el menú en el admin
function mpb_agregar_menu(){
    add_menu_page(
        'Mi Pagina Publica',
        'Mi Plugin',
        'manage_options',
        'pagina_admin', //menu_slug
        'mpg_pagina_admin', // LLAMADA AL CALLBACK
        'dashicons-welcome-learn-more',
        1
    );
}

// Función para procesar acciones ANTES de mostrar cualquier contenido
// function mpb_procesar_acciones() {
//     // Esta función debe llamar al controlador para procesar las acciones
//     if (isset($_GET['page']) && $_GET['page'] == 'pagina_admin') {
//         $RUTA_AB = 'wp-content/plugins/mi-plugin-basico/';
//         require_once ABSPATH.$RUTA_AB.'controller/controller.php';
//     }
// }


// Shortcodes para vista pública
add_shortcode('mpb-vista-publica','mpbvistapublica');

function mpbvistapublica(){
    ob_start();
    mpb_listado_publico();
    return ob_get_clean();

}

add_shortcode('mpb-vista-footer','mpvfooter');

function mpvfooter(){
    return "hola mundo en el footer!!!!!";
}
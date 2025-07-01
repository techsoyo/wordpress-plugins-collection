<?php
// Evita el acceso directo al archivo
 defined('ABSPATH') or die('Acceso no permitido.');

/**
 * FUNCIONES AUXILIARES
 * ====================
 * Funciones de utilidad general para el plugin
 */

/**
 * Función para depuración
 * @param mixed $data Datos a depurar
 * @param bool $die Detener ejecución después de mostrar
 * @return void
 */
function mpf_debug($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Comprueba si la tabla existe
 * @return bool True si la tabla existe
 */
function mpf_tabla_existe() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'mpf_datos';
    
    return $wpdb->get_var("SHOW TABLES LIKE '$tabla'") === $tabla;
}

/**
 * Obtiene la configuración predeterminada
 * @return array Configuración por defecto
 */
function mpf_get_defaults() {
    return [
        'font_default' => 'Arial',
        'negrita_default' => 0,
        'color_default' => 'black',
    ];
}

/**
 * Muestra un mensaje de notificación en el admin
 * @param string $mensaje Mensaje a mostrar
 * @param string $tipo Tipo de mensaje (updated, error, warning)
 * @return void
 */
function mpf_mostrar_notificacion($mensaje, $tipo = 'updated') {
    add_action('admin_notices', function() use ($mensaje, $tipo) {
        ?>
        <div class="<?php echo esc_attr($tipo); ?>">
            <p><?php echo esc_html($mensaje); ?></p>
        </div>
        <?php
    });
}

/**
 * Registra el CSS y JS necesario para el plugin
 */
function mpf_registrar_recursos() {
    // Registra los estilos
    wp_register_style(
        'mpf-admin-styles', 
        plugins_url('/assets/css/admin-styles.css', dirname(__FILE__)),
        array(),
        '1.0'
    );
    
    // Registra los scripts
    wp_register_script(
        'mpf-admin-scripts',
        plugins_url('/assets/js/admin-script.js', dirname(__FILE__)),
        array('jquery'),
        '1.0',
        true
    );
}
add_action('init', 'mpf_registrar_recursos');

/**
 * Verifica la instalación del plugin
 * Comprueba si la tabla existe y muestra un mensaje si hay problemas
 */
function mpf_verificar_instalacion() {
    if (is_admin() && current_user_can('manage_options')) {
        if (!mpf_tabla_existe()) {
            mpf_mostrar_notificacion(
                'La tabla del plugin Mensaje Footer no existe. Por favor, desactiva y vuelve a activar el plugin.',
                'error'
            );
        }
    }
}
add_action('admin_init', 'mpf_verificar_instalacion');

/**
 * Añade enlace de configuración en la página de plugins
 * @param array $links Enlaces existentes
 * @return array Enlaces modificados
 */
function mpf_plugin_action_links($links) {
    $settings_link = '<a href="admin.php?page=mensaje-footer">Configuración</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_mi-plugin-footer/mi-plugin-footer.php', 'mpf_plugin_action_links');

/**
 * Sanitiza los datos de entrada del formulario
 * @param array $datos Datos a sanitizar
 * @return array Datos sanitizados
 */
function mpf_sanitizar_datos($datos) {
    $sanitized = [];
    
    if (isset($datos['mpf_mensaje'])) {
        $sanitized['mpf_mensaje'] = sanitize_text_field($datos['mpf_mensaje']);
    }
    
    if (isset($datos['mpf_tipo_font'])) {
        $sanitized['mpf_tipo_font'] = sanitize_text_field($datos['mpf_tipo_font']);
    }
    
    if (isset($datos['mpf_negrita'])) {
        $sanitized['mpf_negrita'] = intval($datos['mpf_negrita']);
    }
    
    if (isset($datos['mpf_color'])) {
        $sanitized['mpf_color'] = sanitize_text_field($datos['mpf_color']);
    }
    
    return $sanitized;
}
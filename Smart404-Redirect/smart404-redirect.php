<?php
/**
 * Plugin Name: Auto Redirects for Broken URLs
 * Description: Detecta URLs 404 y sugiere redirecciones automáticas basadas en contenido similar.
 * Version: 1.0.0
 * Author: Tu Nombre
 * Text Domain: auto-redirects-404
 * Requires at least: 5.6
 * Requires PHP: 7.4
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('AUTO_REDIRECTS_404_VERSION', '1.0.0');
define('AUTO_REDIRECTS_404_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AUTO_REDIRECTS_404_PLUGIN_URL', plugin_dir_url(__FILE__));

// Incluir archivos requeridos
require_once AUTO_REDIRECTS_404_PLUGIN_DIR . 'includes/class-model.php';
require_once AUTO_REDIRECTS_404_PLUGIN_DIR . 'includes/class-view.php';
require_once AUTO_REDIRECTS_404_PLUGIN_DIR . 'includes/class-controller.php';

// Inicializar el plugin
function auto_redirects_404_init() {
    // Crear instancia del controlador y inicializar
    $controller = new Auto_Redirects_404_Controller();
    $controller->init();
}
// Hook para inicializar el plugin cuando WordPress está listo
add_action('plugins_loaded', 'auto_redirects_404_init');

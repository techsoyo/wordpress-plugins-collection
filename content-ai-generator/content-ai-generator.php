<?php
/**
 * Plugin Name: Content AI Assistant
 * Description: Genera contenido con inteligencia artificial desde el editor de WordPress.
 * Version: 1.0.0
 * Author: kiko
 * Text Domain: content-ai-assistant
 */

defined('ABSPATH') or die('Acceso denegado.');

// Carga de módulos
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-generator.php';

// Activación/desactivación
register_activation_hook(__FILE__, 'caia_activate');
register_deactivation_hook(__FILE__, 'caia_deactivate');

function caia_activate() {
    // Se usará luego para guardar opciones
}

function caia_deactivate() {
    // Limpieza
}

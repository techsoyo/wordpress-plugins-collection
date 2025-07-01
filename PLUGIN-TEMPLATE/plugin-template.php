<?php
/**
 * Plugin Name: Plugin Template
 * Plugin URI: https://example.com/plugin-template
 * Description: Template for creating new WordPress plugins
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: plugin-template
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Plugin_Template
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('PLUGIN_TEMPLATE_VERSION', '1.0.0');
define('PLUGIN_TEMPLATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_TEMPLATE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_TEMPLATE_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_template() {
    require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/class-plugin-template-activator.php';
    Plugin_Template_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_template() {
    require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/class-plugin-template-deactivator.php';
    Plugin_Template_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_plugin_template');
register_deactivation_hook(__FILE__, 'deactivate_plugin_template');

/**
 * The core plugin class
 */
require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/class-plugin-template-admin.php';
require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/class-plugin-template-frontend.php';

/**
 * Begins execution of the plugin.
 */
function run_plugin_template() {
    // Initialize admin functionality
    $plugin_admin = new Plugin_Template_Admin();
    $plugin_admin->init();

    // Initialize frontend functionality
    $plugin_frontend = new Plugin_Template_Frontend();
    $plugin_frontend->init();
}

// Load text domain for internationalization
function plugin_template_load_textdomain() {
    load_plugin_textdomain('plugin-template', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'plugin_template_load_textdomain');

// Run the plugin
run_plugin_template();

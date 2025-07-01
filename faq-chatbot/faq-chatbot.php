<?php
/**
 * Plugin Name: FAQ Chatbot for eCommerce
 * Plugin URI: https://example.com/faq-chatbot
 * Description: A chatbot that automatically responds to frequently asked questions in eCommerce stores
 * Version: 1.0.0
 * Author: FAQ Chatbot Developer
 * Author URI: https://example.com
 * Text Domain: faq-chatbot
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package FAQ_Chatbot
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('FAQ_CHATBOT_VERSION', '1.0.0');
define('FAQ_CHATBOT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FAQ_CHATBOT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FAQ_CHATBOT_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-faq-chatbot-activator.php
 */
function activate_faq_chatbot() {
    require_once FAQ_CHATBOT_PLUGIN_DIR . 'includes/class-faq-chatbot-activator.php';
    FAQ_Chatbot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-faq-chatbot-deactivator.php
 */
function deactivate_faq_chatbot() {
    require_once FAQ_CHATBOT_PLUGIN_DIR . 'includes/class-faq-chatbot-deactivator.php';
    FAQ_Chatbot_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_faq_chatbot');
register_deactivation_hook(__FILE__, 'deactivate_faq_chatbot');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once FAQ_CHATBOT_PLUGIN_DIR . 'includes/class-faq-chatbot-admin.php';
require_once FAQ_CHATBOT_PLUGIN_DIR . 'includes/class-faq-chatbot-frontend.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_faq_chatbot() {
    // Initialize admin functionality
    $faq_chatbot_admin = new FAQ_Chatbot_Admin();
    $faq_chatbot_admin->init();

    // Initialize frontend functionality
    $faq_chatbot_frontend = new FAQ_Chatbot_Frontend();
    $faq_chatbot_frontend->init();
}

// Load text domain for internationalization
function faq_chatbot_load_textdomain() {
    load_plugin_textdomain('faq-chatbot', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'faq_chatbot_load_textdomain');

// Run the plugin
run_faq_chatbot();

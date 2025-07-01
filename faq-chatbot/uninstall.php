<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    FAQ_Chatbot
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load the deactivator class to use its uninstall method
require_once plugin_dir_path(__FILE__) . 'includes/class-faq-chatbot-deactivator.php';

// Check if the user wants to delete all data
$delete_data = get_option('faq_chatbot_delete_data_on_uninstall', false);

if ($delete_data) {
    // Call the uninstall method to clean up all plugin data
    FAQ_Chatbot_Deactivator::uninstall();
} else {
    // Even if not deleting all data, clean up transients and temporary data
    delete_transient('faq_chatbot_cache');
    
    // Remove any scheduled events
    wp_clear_scheduled_hook('faq_chatbot_history_cleanup');
}
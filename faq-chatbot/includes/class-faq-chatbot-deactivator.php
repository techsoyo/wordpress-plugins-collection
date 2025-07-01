<?php
/**
 * Class for plugin deactivation activities
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class that defines what happens when the plugin is deactivated.
 *
 * @since      1.0.0
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/includes
 * @author     FAQ Chatbot Developer
 */
class FAQ_Chatbot_Deactivator {

    /**
     * Deactivate the plugin.
     *
     * Clean up any temporary data, but do not delete tables or settings.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Clean up any transients
        delete_transient('faq_chatbot_cache');
        
        // We don't delete tables or options here to preserve user data
        // Tables and options are only removed if the user chooses to delete them during plugin deletion
        
        // Remove any scheduled events
        wp_clear_scheduled_hook('faq_chatbot_history_cleanup');
        
        // Log deactivation time
        update_option('faq_chatbot_deactivated', time());
        
        // Clear any caches
        wp_cache_flush();
    }

    /**
     * Handle complete uninstall (optional, used by uninstall.php)
     *
     * Removes all plugin data including tables and options.
     * This is NOT called on deactivation, only when the user chooses to delete the plugin.
     *
     * @since    1.0.0
     */
    public static function uninstall() {
        global $wpdb;
        
        // Drop tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}faq_chatbot");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}faq_chatbot_history");
        
        // Delete all options
        $options = [
            'faq_chatbot_welcome_message',
            'faq_chatbot_unknown_message',
            'faq_chatbot_support_email',
            'faq_chatbot_title',
            'faq_chatbot_button_label',
            'faq_chatbot_placeholder',
            'faq_chatbot_visibility',
            'faq_chatbot_position',
            'faq_chatbot_primary_color',
            'faq_chatbot_enable_history',
            'faq_chatbot_days_to_keep_history',
            'faq_chatbot_installed',
            'faq_chatbot_deactivated'
        ];

        foreach ($options as $option) {
            delete_option($option);
        }
        
        // Remove capabilities
        $role = get_role('administrator');
        if ($role) {
            $role->remove_cap('manage_faq_chatbot');
        }
    }
}
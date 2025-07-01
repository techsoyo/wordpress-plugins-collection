<?php
/**
 * Class for plugin activation activities
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
 * Class that defines what happens when the plugin is activated.
 *
 * @since      1.0.0
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/includes
 * @author     FAQ Chatbot Developer
 */
class FAQ_Chatbot_Activator {

    /**
     * Activate the plugin.
     *
     * Creates necessary database tables and sets default options.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;
        
        // Create FAQ table
        $table_name = $wpdb->prefix . 'faq_chatbot';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question text NOT NULL,
            answer longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Create conversation history table if enabled
        $table_name_history = $wpdb->prefix . 'faq_chatbot_history';
        
        $sql_history = "CREATE TABLE $table_name_history (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question text NOT NULL,
            answer longtext NOT NULL,
            user_ip varchar(100) NOT NULL,
            user_agent text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql_history);

        // Set default options
        $default_options = array(
            'faq_chatbot_welcome_message' => __('Hello! How can I help you today?', 'faq-chatbot'),
            'faq_chatbot_unknown_message' => __('I\'m sorry, I don\'t have an answer for that. Please contact our support team.', 'faq-chatbot'),
            'faq_chatbot_support_email' => get_option('admin_email'),
            'faq_chatbot_title' => __('FAQ Assistant', 'faq-chatbot'),
            'faq_chatbot_button_label' => __('Send', 'faq-chatbot'),
            'faq_chatbot_placeholder' => __('Type your question...', 'faq-chatbot'),
            'faq_chatbot_visibility' => 'all_pages',
            'faq_chatbot_position' => 'bottom_right',
            'faq_chatbot_primary_color' => '#3b82f6',
            'faq_chatbot_enable_history' => false,
            'faq_chatbot_days_to_keep_history' => 30
        );

        foreach ($default_options as $option_name => $option_value) {
            if (get_option($option_name) === false) {
                add_option($option_name, $option_value);
            }
        }
        
        // Insert some default FAQs if the table is empty
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        if ($count == 0) {
            $sample_faqs = array(
                array(
                    'question' => __('How long does shipping take?', 'faq-chatbot'),
                    'answer' => __('Standard shipping takes 3-5 business days. Express shipping is available at checkout and takes 1-2 business days.', 'faq-chatbot')
                ),
                array(
                    'question' => __('What is your return policy?', 'faq-chatbot'),
                    'answer' => __('We offer a 30-day return policy for all products. Items must be unused and in their original packaging.', 'faq-chatbot')
                ),
                array(
                    'question' => __('Do you ship internationally?', 'faq-chatbot'),
                    'answer' => __('Yes, we ship to most countries worldwide. International shipping times vary from 7-21 business days depending on your location.', 'faq-chatbot')
                )
            );
            
            foreach ($sample_faqs as $faq) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'question' => $faq['question'],
                        'answer' => $faq['answer']
                    )
                );
            }
        }

        // Add a timestamp option to help with cleanup and updates
        update_option('faq_chatbot_installed', time());
        
        // Clear any caches
        wp_cache_flush();

        // Add capability to administrator
        $role = get_role('administrator');
        if ($role) {
            $role->add_cap('manage_faq_chatbot');
        }
    }
}
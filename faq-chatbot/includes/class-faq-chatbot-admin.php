<?php
/**
 * Class for handling admin-specific functionality
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
 * Admin functionality for the plugin.
 *
 * Defines the plugin name, version, and hooks for admin area.
 *
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/includes
 * @author     FAQ Chatbot Developer
 */
class FAQ_Chatbot_Admin {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
    }

    /**
     * Initialize all hooks for the admin area.
     *
     * @since    1.0.0
     */
    public function init() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Add admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Add Ajax actions for admin
        add_action('wp_ajax_faq_chatbot_save_faq', array($this, 'ajax_save_faq'));
        add_action('wp_ajax_faq_chatbot_delete_faq', array($this, 'ajax_delete_faq'));
        add_action('wp_ajax_faq_chatbot_get_faq', array($this, 'ajax_get_faq'));
        add_action('wp_ajax_faq_chatbot_bulk_delete', array($this, 'ajax_bulk_delete_faq'));

        // Add cleanup scheduled event
        if (get_option('faq_chatbot_enable_history')) {
            if (!wp_next_scheduled('faq_chatbot_history_cleanup')) {
                wp_schedule_event(time(), 'daily', 'faq_chatbot_history_cleanup');
            }
            add_action('faq_chatbot_history_cleanup', array($this, 'cleanup_history'));
        } else {
            wp_clear_scheduled_hook('faq_chatbot_history_cleanup');
        }

        // Add plugin action links
        add_filter('plugin_action_links_' . FAQ_CHATBOT_PLUGIN_BASENAME, array($this, 'add_action_links'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Check if we're on our plugin page
        $screen = get_current_screen();
        if (strpos($screen->id, 'faq-chatbot') !== false) {
            wp_enqueue_style('faq-chatbot-admin', FAQ_CHATBOT_PLUGIN_URL . 'assets/css/faq-chatbot-tailwind.css', array(), FAQ_CHATBOT_VERSION, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Check if we're on our plugin page
        $screen = get_current_screen();
        if (strpos($screen->id, 'faq-chatbot') !== false) {
            wp_enqueue_script('faq-chatbot-admin', FAQ_CHATBOT_PLUGIN_URL . 'assets/js/faq-chatbot-admin.js', array('jquery'), FAQ_CHATBOT_VERSION, false);

            // Localize the script with data
            wp_localize_script('faq-chatbot-admin', 'faqChatbotAdminData', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('faq_chatbot_admin_nonce')
            ));
        }
    }

    /**
     * Add admin menu pages.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        // Main menu page
        add_menu_page(
            __('FAQ Chatbot', 'faq-chatbot'),
            __('FAQ Chatbot', 'faq-chatbot'),
            'manage_faq_chatbot',
            'faq-chatbot',
            array($this, 'render_admin_page'),
            'dashicons-format-chat',
            30
        );

        // FAQs submenu
        add_submenu_page(
            'faq-chatbot',
            __('FAQs', 'faq-chatbot'),
            __('FAQs', 'faq-chatbot'),
            'manage_faq_chatbot',
            'faq-chatbot',
            array($this, 'render_admin_page')
        );

        // Settings submenu
        add_submenu_page(
            'faq-chatbot',
            __('Settings', 'faq-chatbot'),
            __('Settings', 'faq-chatbot'),
            'manage_faq_chatbot',
            'faq-chatbot-settings',
            array($this, 'render_settings_page')
        );

        // History submenu (if enabled)
        if (get_option('faq_chatbot_enable_history')) {
            add_submenu_page(
                'faq-chatbot',
                __('Conversation History', 'faq-chatbot'),
                __('History', 'faq-chatbot'),
                'manage_faq_chatbot',
                'faq-chatbot-history',
                array($this, 'render_history_page')
            );
        }
    }

    /**
     * Register plugin settings.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register settings
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_welcome_message');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_unknown_message');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_support_email');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_title');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_button_label');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_placeholder');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_visibility');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_position');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_primary_color');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_enable_history');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_days_to_keep_history');
        register_setting('faq-chatbot-settings-group', 'faq_chatbot_auto_display');
    }

    /**
     * Render the main admin page.
     *
     * @since    1.0.0
     */
    public function render_admin_page() {
        // Check capability
        if (!current_user_can('manage_faq_chatbot')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'faq-chatbot'));
        }

        // Get FAQs from database
        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';
        $faqs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);

        // Include the admin display template
        include_once FAQ_CHATBOT_PLUGIN_DIR . 'admin/partials/faq-chatbot-admin-display.php';
    }

    /**
     * Render the settings page.
     *
     * @since    1.0.0
     */
    public function render_settings_page() {
        // Check capability
        if (!current_user_can('manage_faq_chatbot')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'faq-chatbot'));
        }

        // Include the settings template
        include_once FAQ_CHATBOT_PLUGIN_DIR . 'admin/partials/faq-chatbot-settings-display.php';
    }

    /**
     * Render the conversation history page.
     *
     * @since    1.0.0
     */
    public function render_history_page() {
        // Check capability
        if (!current_user_can('manage_faq_chatbot')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'faq-chatbot'));
        }

        // Get conversation history from database
        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot_history';
        $history = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 100", ARRAY_A);

        // Include the history template
        include_once FAQ_CHATBOT_PLUGIN_DIR . 'admin/partials/faq-chatbot-history-display.php';
    }

    /**
     * Ajax handler for saving FAQs.
     *
     * @since    1.0.0
     */
    public function ajax_save_faq() {
        // Check nonce for security
        check_ajax_referer('faq_chatbot_admin_nonce', 'nonce');

        // Check capabilities
        if (!current_user_can('manage_faq_chatbot')) {
            wp_send_json_error(__('Permission denied.', 'faq-chatbot'));
            return;
        }

        // Get and sanitize data
        $question = sanitize_text_field($_POST['question']);
        $answer = wp_kses_post($_POST['answer']);

        // Check if question and answer are not empty
        if (empty($question) || empty($answer)) {
            wp_send_json_error(__('Question and answer cannot be empty.', 'faq-chatbot'));
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';

        // Check if it's an update or new entry
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            // Update existing FAQ
            $result = $wpdb->update(
                $table_name,
                array(
                    'question' => $question,
                    'answer' => $answer,
                ),
                array('id' => $id),
                array('%s', '%s'),
                array('%d')
            );
        } else {
            // Insert new FAQ
            $result = $wpdb->insert(
                $table_name,
                array(
                    'question' => $question,
                    'answer' => $answer,
                ),
                array('%s', '%s')
            );
        }

        if ($result === false) {
            wp_send_json_error(__('Database error. Please try again.', 'faq-chatbot'));
        } else {
            wp_send_json_success(__('FAQ saved successfully.', 'faq-chatbot'));
        }
    }

    /**
     * Ajax handler for deleting FAQs.
     *
     * @since    1.0.0
     */
    public function ajax_delete_faq() {
        // Check nonce for security
        check_ajax_referer('faq_chatbot_admin_nonce', 'nonce');

        // Check capabilities
        if (!current_user_can('manage_faq_chatbot')) {
            wp_send_json_error(__('Permission denied.', 'faq-chatbot'));
            return;
        }

        // Get and validate ID
        $id = intval($_POST['id']);
        if ($id <= 0) {
            wp_send_json_error(__('Invalid FAQ ID.', 'faq-chatbot'));
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';

        // Delete the FAQ
        $result = $wpdb->delete(
            $table_name,
            array('id' => $id),
            array('%d')
        );

        if ($result === false) {
            wp_send_json_error(__('Database error. Please try again.', 'faq-chatbot'));
        } else {
            wp_send_json_success(__('FAQ deleted successfully.', 'faq-chatbot'));
        }
    }

    /**
     * Ajax handler for getting a single FAQ for editing.
     *
     * @since    1.0.0
     */
    public function ajax_get_faq() {
        // Check nonce for security
        check_ajax_referer('faq_chatbot_admin_nonce', 'nonce');

        // Check capabilities
        if (!current_user_can('manage_faq_chatbot')) {
            wp_send_json_error(__('Permission denied.', 'faq-chatbot'));
            return;
        }

        // Get and validate ID
        $id = intval($_POST['id']);
        if ($id <= 0) {
            wp_send_json_error(__('Invalid FAQ ID.', 'faq-chatbot'));
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';

        // Get the FAQ
        $faq = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

        if ($faq === null) {
            wp_send_json_error(__('FAQ not found.', 'faq-chatbot'));
        } else {
            wp_send_json_success($faq);
        }
    }

    /**
     * Ajax handler for bulk deleting FAQs.
     *
     * @since    1.0.0
     */
    public function ajax_bulk_delete_faq() {
        // Check nonce for security
        check_ajax_referer('faq_chatbot_admin_nonce', 'nonce');

        // Check capabilities
        if (!current_user_can('manage_faq_chatbot')) {
            wp_send_json_error(__('Permission denied.', 'faq-chatbot'));
            return;
        }

        // Get and validate IDs
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : array();
        if (empty($ids)) {
            wp_send_json_error(__('No FAQs selected.', 'faq-chatbot'));
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';

        // Build the query with proper escaping
        $ids_format = implode(', ', array_fill(0, count($ids), '%d'));
        $query = $wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids_format)", $ids);

        // Execute the query
        $result = $wpdb->query($query);

        if ($result === false) {
            wp_send_json_error(__('Database error. Please try again.', 'faq-chatbot'));
        } else {
            wp_send_json_success(sprintf(__('%d FAQs deleted successfully.', 'faq-chatbot'), $result));
        }
    }

    /**
     * Clean up old conversation history entries.
     *
     * @since    1.0.0
     */
    public function cleanup_history() {
        // Only proceed if history logging is enabled
        if (!get_option('faq_chatbot_enable_history')) {
            return;
        }

        $days = intval(get_option('faq_chatbot_days_to_keep_history', 30));
        if ($days <= 0) {
            return; // No cleanup needed if days is 0 or negative
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot_history';

        // Delete entries older than the specified number of days
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE created_at < %s", $date));
    }

    /**
     * Add action links to the plugins page.
     *
     * @since    1.0.0
     * @param array $links Existing plugin action links
     * @return array Modified plugin action links
     */
    public function add_action_links($links) {
        $settings_link = '<a href="admin.php?page=faq-chatbot-settings">' . __('Settings', 'faq-chatbot') . '</a>';
        $faqs_link = '<a href="admin.php?page=faq-chatbot">' . __('FAQs', 'faq-chatbot') . '</a>';

        array_unshift($links, $settings_link);
        array_unshift($links, $faqs_link);

        return $links;
    }
}

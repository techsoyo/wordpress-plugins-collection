<?php
/**
 * Class for handling frontend-specific functionality
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
 * Frontend functionality for the plugin.
 *
 * Defines the plugin name, version, and hooks for public-facing site.
 *
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/includes
 * @author     FAQ Chatbot Developer
 */
class FAQ_Chatbot_Frontend {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
    }

    /**
     * Initialize all hooks for the public-facing functionality.
     *
     * @since    1.0.0
     */
    public function init() {
        // Register public scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Register the shortcode for the chatbot
        add_shortcode('faq_chatbot', array($this, 'render_chatbot_shortcode'));

        // Add chatbot to footer if enabled globally
        add_action('wp_footer', array($this, 'maybe_add_chatbot_to_footer'));

        // Register Ajax endpoint for getting answers
        add_action('wp_ajax_faq_chatbot_get_answer', array($this, 'ajax_get_answer'));
        add_action('wp_ajax_nopriv_faq_chatbot_get_answer', array($this, 'ajax_get_answer'));

        // Register Ajax endpoint for logging conversation (if enabled)
        if (get_option('faq_chatbot_enable_history')) {
            add_action('wp_ajax_faq_chatbot_log_conversation', array($this, 'ajax_log_conversation'));
            add_action('wp_ajax_nopriv_faq_chatbot_log_conversation', array($this, 'ajax_log_conversation'));
        }
    }

    /**
     * Register the stylesheets for the public-facing side.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style('faq-chatbot-public', FAQ_CHATBOT_PLUGIN_URL . 'assets/css/faq-chatbot-tailwind.css', array(), FAQ_CHATBOT_VERSION, 'all');

        // Add custom primary color
        $primary_color = get_option('faq_chatbot_primary_color', '#3b82f6');
        $custom_css = "
            .faq-chatbot-header, .faq-chatbot-submit, .faq-chatbot-toggle {
                background-color: {$primary_color} !important;
            }
            .faq-chatbot-input:focus {
                border-color: {$primary_color} !important;
                box-shadow: 0 0 0 3px " . $this->hex2rgba($primary_color, 0.3) . " !important;
            }
        ";
        wp_add_inline_style('faq-chatbot-public', $custom_css);
    }

    /**
     * Register the JavaScript for the public-facing side.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('faq-chatbot-public', FAQ_CHATBOT_PLUGIN_URL . 'assets/js/faq-chatbot-frontend.js', array('jquery'), FAQ_CHATBOT_VERSION, true);

        // Localize the script with data
        wp_localize_script('faq-chatbot-public', 'faqChatbotData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('faq_chatbot_nonce'),
            'welcomeMessage' => esc_html(get_option('faq_chatbot_welcome_message')),
            'unknownMessage' => esc_html(get_option('faq_chatbot_unknown_message')),
            'placeholderText' => esc_html(get_option('faq_chatbot_placeholder')),
            'buttonLabel' => esc_html(get_option('faq_chatbot_button_label'))
        ));
    }

    /**
     * Render the chatbot based on shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes
     * @return   string            HTML output for the chatbot
     */
    public function render_chatbot_shortcode($atts) {
        // Extract attributes with defaults
        $atts = shortcode_atts(
            array(
                'title' => get_option('faq_chatbot_title', __('FAQ Assistant', 'faq-chatbot')),
                'position' => 'inline' // Force inline for shortcode
            ),
            $atts,
            'faq_chatbot'
        );

        // Start output buffer
        ob_start();

        // Include the template
        include FAQ_CHATBOT_PLUGIN_DIR . 'public/partials/faq-chatbot-public-display.php';

        // Return the output
        return ob_get_clean();
    }

    /**
     * Conditionally add the chatbot to the footer based on settings.
     *
     * @since    1.0.0
     */
    public function maybe_add_chatbot_to_footer() {
        // Si la opción auto_display está activada, mostrar siempre el chatbot
        if (get_option('faq_chatbot_auto_display') == 1) {
            $show_chatbot = true;
        } else {
            // Get visibility setting
            $visibility = get_option('faq_chatbot_visibility', 'all_pages');

            // Check if we should show the chatbot
            $show_chatbot = false;

            switch ($visibility) {
                case 'all_pages':
                    $show_chatbot = true;
                    break;

                case 'home_only':
                    $show_chatbot = is_front_page() || is_home();
                    break;

                case 'specific_pages':
                    $pages = get_option('faq_chatbot_specific_pages', array());
                    $current_page_id = get_the_ID();
                    $show_chatbot = in_array($current_page_id, $pages);
                    break;

                case 'shortcode_only':
                    // Don't show in footer, only via shortcode
                    $show_chatbot = false;
                    break;
            }
        }

        // Output the chatbot if needed
        if ($show_chatbot) {
            $atts = array(
                'title' => get_option('faq_chatbot_title', __('FAQ Assistant', 'faq-chatbot')),
                'position' => get_option('faq_chatbot_position', 'bottom_right')
            );

            include FAQ_CHATBOT_PLUGIN_DIR . 'public/partials/faq-chatbot-public-display.php';
        }
    }

    /**
     * Ajax handler for getting answer to question.
     *
     * @since    1.0.0
     */
    public function ajax_get_answer() {
        // Verify nonce
        check_ajax_referer('faq_chatbot_nonce', 'nonce');

        // Get and sanitize the question
        $question = sanitize_text_field($_POST['question']);

        if (empty($question)) {
            wp_send_json_error(array('message' => __('Question is empty', 'faq-chatbot')));
            return;
        }

        // Get answer from database
        $answer = $this->find_answer($question);

        if ($answer) {
            // Log the conversation if history is enabled
            if (get_option('faq_chatbot_enable_history', false)) {
                $this->log_conversation($question, $answer);
            }

            wp_send_json_success(array('answer' => $answer));
        } else {
            // No answer found
            $unknown_message = get_option(
                'faq_chatbot_unknown_message',
                __('I\'m sorry, I don\'t have an answer for that. Please contact our support team.', 'faq-chatbot')
            );

            // Log the failed question if history is enabled
            if (get_option('faq_chatbot_enable_history', false)) {
                $this->log_conversation($question, $unknown_message, false);
            }

            wp_send_json_success(array('answer' => $unknown_message));
        }
    }

    /**
     * Find the best answer for a question.
     *
     * @since    1.0.0
     * @param    string    $question    The question to find an answer for
     * @return   string|false           The answer if found, or false if not found
     */
    private function find_answer($question) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot';

        // Get all FAQs from the database
        $faqs = $wpdb->get_results("SELECT question, answer FROM $table_name", ARRAY_A);

        if (empty($faqs)) {
            return false;
        }

        // Prepare the question for matching
        $clean_question = strtolower(trim($question));
        $words = preg_split('/\s+/', $clean_question);
        $words = array_filter($words, function($word) {
            // Filter out common stop words
            $stop_words = array('a', 'an', 'the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'with', 'by', 'about', 'as');
            return !in_array($word, $stop_words) && strlen($word) > 1;
        });

        $best_match = null;
        $highest_score = 0;

        // Score each FAQ based on keyword matching
        foreach ($faqs as $faq) {
            $faq_question = strtolower(trim($faq['question']));
            $score = 0;

            // Exact match gets highest priority
            if ($clean_question === $faq_question) {
                return $faq['answer'];
            }

            // Check if the user's question contains the FAQ question
            if (strpos($clean_question, $faq_question) !== false) {
                $score += 5;
            }

            // Check if FAQ question contains the user's question
            if (strpos($faq_question, $clean_question) !== false) {
                $score += 3;
            }

            // Count matching words
            foreach ($words as $word) {
                if (strpos($faq_question, $word) !== false) {
                    $score += 1;
                }
            }

            // Update best match if this score is higher
            if ($score > $highest_score) {
                $highest_score = $score;
                $best_match = $faq['answer'];
            }
        }

        // Return best match if score is above threshold
        if ($highest_score >= 2 && $best_match) {
            return $best_match;
        }

        return false;
    }

    /**
     * Log a conversation to the database.
     *
     * @since    1.0.0
     * @param    string    $question    The user's question
     * @param    string    $answer      The bot's answer
     * @param    bool      $matched     Whether an answer was found in the FAQ database
     */
    private function log_conversation($question, $answer, $matched = true) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faq_chatbot_history';

        $wpdb->insert(
            $table_name,
            array(
                'question' => $question,
                'answer' => $answer,
                'user_ip' => $this->get_user_ip(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
            ),
            array('%s', '%s', '%s', '%s')
        );
    }

    /**
     * Ajax handler for logging conversation.
     * This is a separate endpoint that can be called after a conversation is displayed.
     *
     * @since    1.0.0
     */
    public function ajax_log_conversation() {
        // Verify nonce
        check_ajax_referer('faq_chatbot_nonce', 'nonce');

        // Log is already handled in ajax_get_answer, so this is just a placeholder for any additional logging
        wp_send_json_success();
    }

    /**
     * Get the user's IP address.
     *
     * @since    1.0.0
     * @return   string    The user's IP address
     */
    private function get_user_ip() {
        $ip = '127.0.0.1'; // Default for local testing

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return sanitize_text_field($ip);
    }

    /**
     * Convert hex color to rgba.
     *
     * @since    1.0.0
     * @param    string    $hex     The hex color code
     * @param    float     $alpha   The alpha value (0-1)
     * @return   string             RGBA color value
     */
    private function hex2rgba($hex, $alpha = 1) {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $alpha)";
    }
}

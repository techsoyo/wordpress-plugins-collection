<?php
/**
 * Public-facing display of the chatbot
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/public/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<?php
// Extract variables from $atts
$title = isset($atts['title']) ? esc_html($atts['title']) : __('FAQ Assistant', 'faq-chatbot');
$position = isset($atts['position']) ? $atts['position'] : 'bottom_right';

// Set position classes
$position_class = '';
if ($position === 'inline') {
    $position_class = 'faq-chatbot-inline';
} else {
    // For footer positions, always start hidden
    $position_class = 'faq-chatbot-position-' . $position;
}

// Get button text and placeholder from settings
$button_label = get_option('faq_chatbot_button_label', __('Send', 'faq-chatbot'));
$placeholder = get_option('faq_chatbot_placeholder', __('Type your question...', 'faq-chatbot'));
?>

<!-- FAQ Chatbot Container -->
<div class="faq-chatbot-container <?php echo esc_attr($position_class); ?>">
    <!-- Header -->
    <div class="faq-chatbot-header">
        <div class="faq-chatbot-title"><?php echo esc_html($title); ?></div>
        <div class="faq-chatbot-close">&times;</div>
    </div>
    
    <!-- Messages Area -->
    <div class="faq-chatbot-messages" aria-live="polite"></div>
    
    <!-- Input Form -->
    <div class="faq-chatbot-form">
        <div class="faq-chatbot-input-wrapper">
            <input type="text" class="faq-chatbot-input" placeholder="<?php echo esc_attr($placeholder); ?>" aria-label="<?php echo esc_attr__('Type your question', 'faq-chatbot'); ?>">
            <button type="submit" class="faq-chatbot-submit"><?php echo esc_html($button_label); ?></button>
        </div>
    </div>
</div>

<?php if ($position !== 'inline') : ?>
    <!-- Chat Toggle Button (only for footer positions) -->
    <div class="faq-chatbot-toggle" aria-label="<?php echo esc_attr__('Open chat', 'faq-chatbot'); ?>" role="button" tabindex="0">
        <div class="faq-chatbot-toggle-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>
    </div>
<?php endif; ?>
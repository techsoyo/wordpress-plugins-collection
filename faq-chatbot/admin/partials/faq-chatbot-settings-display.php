<?php
/**
 * Settings page for FAQ Chatbot
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    FAQ_Chatbot
 * @subpackage FAQ_Chatbot/admin/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap faq-chatbot-admin">
    <div class="faq-chatbot-admin-header">
        <h1 class="faq-chatbot-admin-title"><?php echo esc_html__('FAQ Chatbot - Settings', 'faq-chatbot'); ?></h1>
    </div>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields('faq-chatbot-settings-group'); ?>

        <div class="faq-chatbot-admin-form">
            <h2><?php echo esc_html__('General Settings', 'faq-chatbot'); ?></h2>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_title" class="faq-chatbot-admin-label"><?php echo esc_html__('Chatbot Title', 'faq-chatbot'); ?></label>
                <input type="text" id="faq_chatbot_title" name="faq_chatbot_title" class="faq-chatbot-admin-input" value="<?php echo esc_attr(get_option('faq_chatbot_title', __('FAQ Assistant', 'faq-chatbot'))); ?>">
                <p class="description"><?php echo esc_html__('The title displayed in the chatbot header.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_welcome_message" class="faq-chatbot-admin-label"><?php echo esc_html__('Welcome Message', 'faq-chatbot'); ?></label>
                <textarea id="faq_chatbot_welcome_message" name="faq_chatbot_welcome_message" class="faq-chatbot-admin-textarea"><?php echo esc_textarea(get_option('faq_chatbot_welcome_message', __('Hello! How can I help you today?', 'faq-chatbot'))); ?></textarea>
                <p class="description"><?php echo esc_html__('The initial message displayed when the chatbot loads.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_unknown_message" class="faq-chatbot-admin-label"><?php echo esc_html__('Unknown Question Response', 'faq-chatbot'); ?></label>
                <textarea id="faq_chatbot_unknown_message" name="faq_chatbot_unknown_message" class="faq-chatbot-admin-textarea"><?php echo esc_textarea(get_option('faq_chatbot_unknown_message', __('I\'m sorry, I don\'t have an answer for that. Please contact our support team.', 'faq-chatbot'))); ?></textarea>
                <p class="description"><?php echo esc_html__('The message displayed when no matching answer is found.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_support_email" class="faq-chatbot-admin-label"><?php echo esc_html__('Support Email', 'faq-chatbot'); ?></label>
                <input type="email" id="faq_chatbot_support_email" name="faq_chatbot_support_email" class="faq-chatbot-admin-input" value="<?php echo esc_attr(get_option('faq_chatbot_support_email', get_option('admin_email'))); ?>">
                <p class="description"><?php echo esc_html__('Email address for support inquiries.', 'faq-chatbot'); ?></p>
            </div>
        </div>

        <div class="faq-chatbot-admin-form">
            <h2><?php echo esc_html__('Display Settings', 'faq-chatbot'); ?></h2>

            <div class="faq-chatbot-admin-form-row">
                <label class="faq-chatbot-admin-label"><?php echo esc_html__('Display Chatbot On', 'faq-chatbot'); ?></label>

                <div class="faq-chatbot-admin-radio-group">
                    <label>
                        <input type="radio" name="faq_chatbot_visibility" value="all_pages" <?php checked('all_pages', get_option('faq_chatbot_visibility', 'all_pages')); ?>>
                        <?php echo esc_html__('All Pages', 'faq-chatbot'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="faq_chatbot_visibility" value="home_only" <?php checked('home_only', get_option('faq_chatbot_visibility')); ?>>
                        <?php echo esc_html__('Homepage Only', 'faq-chatbot'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="faq_chatbot_visibility" value="specific_pages" <?php checked('specific_pages', get_option('faq_chatbot_visibility')); ?>>
                        <?php echo esc_html__('Specific Pages', 'faq-chatbot'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="faq_chatbot_visibility" value="shortcode_only" <?php checked('shortcode_only', get_option('faq_chatbot_visibility')); ?>>
                        <?php echo esc_html__('Shortcode Only', 'faq-chatbot'); ?>
                    </label>
                </div>
            </div>

            <div id="faq-chatbot-pages" class="faq-chatbot-admin-form-row" style="<?php echo get_option('faq_chatbot_visibility') === 'specific_pages' ? 'display:block;' : 'display:none;'; ?>">
                <label class="faq-chatbot-admin-label"><?php echo esc_html__('Select Pages', 'faq-chatbot'); ?></label>

                <div class="faq-chatbot-admin-checkbox-group">
                    <?php
                    $selected_pages = get_option('faq_chatbot_specific_pages', array());
                    $pages = get_pages(array('sort_column' => 'post_title', 'sort_order' => 'ASC'));

                    foreach ($pages as $page) {
                        echo '<label>';
                        echo '<input type="checkbox" name="faq_chatbot_specific_pages[]" value="' . esc_attr($page->ID) . '" ' . (in_array($page->ID, (array)$selected_pages) ? 'checked' : '') . '>';
                        echo esc_html($page->post_title);
                        echo '</label><br>';
                    }
                    ?>
                </div>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label class="faq-chatbot-admin-label"><?php echo esc_html__('Chatbot Position', 'faq-chatbot'); ?></label>

                <div class="faq-chatbot-admin-radio-group">
                    <label>
                        <input type="radio" name="faq_chatbot_position" value="bottom_right" <?php checked('bottom_right', get_option('faq_chatbot_position', 'bottom_right')); ?>>
                        <?php echo esc_html__('Bottom Right', 'faq-chatbot'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="faq_chatbot_position" value="bottom_left" <?php checked('bottom_left', get_option('faq_chatbot_position')); ?>>
                        <?php echo esc_html__('Bottom Left', 'faq-chatbot'); ?>
                    </label>
                </div>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_primary_color" class="faq-chatbot-admin-label"><?php echo esc_html__('Primary Color', 'faq-chatbot'); ?></label>
                <input type="color" id="faq_chatbot_primary_color" name="faq_chatbot_primary_color" value="<?php echo esc_attr(get_option('faq_chatbot_primary_color', '#3b82f6')); ?>">
                <p class="description"><?php echo esc_html__('The main color used for the chatbot header and buttons.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_button_label" class="faq-chatbot-admin-label"><?php echo esc_html__('Button Label', 'faq-chatbot'); ?></label>
                <input type="text" id="faq_chatbot_button_label" name="faq_chatbot_button_label" class="faq-chatbot-admin-input" value="<?php echo esc_attr(get_option('faq_chatbot_button_label', __('Send', 'faq-chatbot'))); ?>">
                <p class="description"><?php echo esc_html__('The label for the send button.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_placeholder" class="faq-chatbot-admin-label"><?php echo esc_html__('Input Placeholder', 'faq-chatbot'); ?></label>
                <input type="text" id="faq_chatbot_placeholder" name="faq_chatbot_placeholder" class="faq-chatbot-admin-input" value="<?php echo esc_attr(get_option('faq_chatbot_placeholder', __('Type your question...', 'faq-chatbot'))); ?>">
                <p class="description"><?php echo esc_html__('The placeholder text for the chat input.', 'faq-chatbot'); ?></p>
            </div>
        </div>

        <div class="faq-chatbot-admin-form">
            <h2><?php echo esc_html__('Conversation History', 'faq-chatbot'); ?></h2>

            <div class="faq-chatbot-admin-form-row">
                <label class="faq-chatbot-admin-label"><?php echo esc_html__('Enable Conversation Logging', 'faq-chatbot'); ?></label>
                <label>
                    <input type="checkbox" name="faq_chatbot_enable_history" value="1" <?php checked(1, get_option('faq_chatbot_enable_history', 0)); ?>>
                    <?php echo esc_html__('Log conversations for analysis and improvement', 'faq-chatbot'); ?>
                </label>
                <p class="description"><?php echo esc_html__('Logs user questions and bot responses in the database.', 'faq-chatbot'); ?></p>
            </div>

            <div class="faq-chatbot-admin-form-row">
                <label for="faq_chatbot_days_to_keep_history" class="faq-chatbot-admin-label"><?php echo esc_html__('Days to Keep History', 'faq-chatbot'); ?></label>
                <input type="number" id="faq_chatbot_days_to_keep_history" name="faq_chatbot_days_to_keep_history" class="faq-chatbot-admin-input" min="1" max="365" value="<?php echo esc_attr(get_option('faq_chatbot_days_to_keep_history', 30)); ?>">
                <p class="description"><?php echo esc_html__('Number of days to keep conversation history before automatic deletion.', 'faq-chatbot'); ?></p>
            </div>
        </div>
        <label>
    <input type="checkbox" name="faq_chatbot_auto_display" value="1" <?php checked( get_option('faq_chatbot_auto_display'), 1 ); ?>>
    Mostrar el chatbot automáticamente en todas las páginas
</label>

        <?php submit_button(__('Save Settings', 'faq-chatbot'), 'primary', 'submit', true, array('class' => 'faq-chatbot-admin-button')); ?>
    </form>

    <div class="faq-chatbot-admin-form">
        <h2><?php echo esc_html__('Shortcode Usage', 'faq-chatbot'); ?></h2>
        <p><?php echo esc_html__('Use the following shortcode to display the chatbot on any page or post:', 'faq-chatbot'); ?></p>
        <code>[faq_chatbot]</code>
        <p><?php echo esc_html__('You can customize the title with an attribute:', 'faq-chatbot'); ?></p>
        <code>[faq_chatbot title="Product Support"]</code>
    </div>
</div>

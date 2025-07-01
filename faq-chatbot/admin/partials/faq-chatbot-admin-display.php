<?php
/**
 * Admin display page for FAQs management
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
        <h1 class="faq-chatbot-admin-title"><?php echo esc_html__('FAQ Chatbot - Manage FAQs', 'faq-chatbot'); ?></h1>
        <button type="button" id="add-new-faq" class="faq-chatbot-admin-button"><?php echo esc_html__('Add New FAQ', 'faq-chatbot'); ?></button>
    </div>
    
    <?php settings_errors(); ?>
    
    <!-- Add/Edit FAQ Form -->
    <div id="faq-form" class="faq-chatbot-admin-form hidden">
        <h2><?php echo esc_html__('Add/Edit FAQ', 'faq-chatbot'); ?></h2>
        <form method="post">
            <?php wp_nonce_field('faq_chatbot_admin_nonce', 'faq_chatbot_nonce'); ?>
            <input type="hidden" id="faq-id" name="faq_id" value="">
            
            <div class="faq-chatbot-admin-form-row">
                <label for="faq-question" class="faq-chatbot-admin-label"><?php echo esc_html__('Question', 'faq-chatbot'); ?></label>
                <input type="text" id="faq-question" name="faq_question" class="faq-chatbot-admin-input" required>
            </div>
            
            <div class="faq-chatbot-admin-form-row">
                <label for="faq-answer" class="faq-chatbot-admin-label"><?php echo esc_html__('Answer', 'faq-chatbot'); ?></label>
                <textarea id="faq-answer" name="faq_answer" class="faq-chatbot-admin-textarea" required></textarea>
            </div>
            
            <div class="faq-chatbot-admin-form-row">
                <button type="submit" class="faq-chatbot-admin-button"><?php echo esc_html__('Save FAQ', 'faq-chatbot'); ?></button>
            </div>
        </form>
    </div>
    
    <!-- Search Box -->
    <div class="faq-chatbot-admin-form-row">
        <input type="text" id="faq-search" class="faq-chatbot-admin-input" placeholder="<?php echo esc_attr__('Search FAQs...', 'faq-chatbot'); ?>">
    </div>
    
    <!-- FAQs Table -->
    <form method="post">
        <?php wp_nonce_field('faq_chatbot_admin_nonce', 'faq_chatbot_nonce'); ?>
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1"><?php echo esc_html__('Bulk Actions', 'faq-chatbot'); ?></option>
                    <option value="delete"><?php echo esc_html__('Delete', 'faq-chatbot'); ?></option>
                </select>
                <input type="submit" id="doaction" class="button action" value="<?php echo esc_attr__('Apply', 'faq-chatbot'); ?>">
            </div>
            <br class="clear">
        </div>
        
        <table id="faq-table" class="wp-list-table widefat fixed striped faq-chatbot-admin-table">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="cb-select-all-1" type="checkbox">
                    </td>
                    <th class="manage-column column-question"><?php echo esc_html__('Question', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-answer"><?php echo esc_html__('Answer', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-date"><?php echo esc_html__('Date', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-actions"><?php echo esc_html__('Actions', 'faq-chatbot'); ?></th>
                </tr>
            </thead>
            
            <tbody>
                <?php if (!empty($faqs)) : ?>
                    <?php foreach ($faqs as $faq) : ?>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" name="faq[]" value="<?php echo esc_attr($faq['id']); ?>">
                            </th>
                            <td class="column-question">
                                <?php echo esc_html($faq['question']); ?>
                            </td>
                            <td class="column-answer">
                                <?php echo wp_kses_post(wpautop(substr($faq['answer'], 0, 150) . (strlen($faq['answer']) > 150 ? '...' : ''))); ?>
                            </td>
                            <td class="column-date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($faq['created_at']))); ?>
                            </td>
                            <td class="column-actions">
                                <a href="#" class="faq-edit" data-id="<?php echo esc_attr($faq['id']); ?>"><?php echo esc_html__('Edit', 'faq-chatbot'); ?></a> | 
                                <a href="#" class="faq-delete" data-id="<?php echo esc_attr($faq['id']); ?>"><?php echo esc_html__('Delete', 'faq-chatbot'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5"><?php echo esc_html__('No FAQs found. Add your first FAQ using the "Add New FAQ" button.', 'faq-chatbot'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input id="cb-select-all-2" type="checkbox">
                    </td>
                    <th class="manage-column column-question"><?php echo esc_html__('Question', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-answer"><?php echo esc_html__('Answer', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-date"><?php echo esc_html__('Date', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-actions"><?php echo esc_html__('Actions', 'faq-chatbot'); ?></th>
                </tr>
            </tfoot>
        </table>
    </form>
    
    <!-- Documentation Section -->
    <div class="faq-chatbot-admin-form">
        <h2><?php echo esc_html__('Using the FAQ Chatbot', 'faq-chatbot'); ?></h2>
        <p><?php echo esc_html__('You can add the chatbot to any page or post using the shortcode:', 'faq-chatbot'); ?> <code>[faq_chatbot]</code></p>
        <p><?php echo esc_html__('The chatbot will automatically appear on pages based on your settings in the Settings tab.', 'faq-chatbot'); ?></p>
        <p><?php echo esc_html__('For best results, add detailed FAQs that cover common customer questions.', 'faq-chatbot'); ?></p>
    </div>
</div>
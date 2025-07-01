<?php
/**
 * Admin display page for conversation history
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
        <h1 class="faq-chatbot-admin-title"><?php echo esc_html__('FAQ Chatbot - Conversation History', 'faq-chatbot'); ?></h1>
    </div>
    
    <?php settings_errors(); ?>
    
    <div class="faq-chatbot-admin-form">
        <p><?php echo esc_html__('Below are the recent conversations between users and the chatbot. Use this data to improve your FAQs and identify common questions that need answers.', 'faq-chatbot'); ?></p>
        
        <?php if (!get_option('faq_chatbot_enable_history', false)): ?>
            <div class="notice notice-warning">
                <p><?php echo esc_html__('Conversation history is currently disabled. Enable it in the Settings tab to start recording conversations.', 'faq-chatbot'); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="faq-chatbot-admin-form-row">
            <form method="get">
                <input type="hidden" name="page" value="faq-chatbot-history">
                <label for="history-filter" class="faq-chatbot-admin-label"><?php echo esc_html__('Filter by Date:', 'faq-chatbot'); ?></label>
                <select id="history-filter" name="filter" class="faq-chatbot-admin-input">
                    <option value="all" <?php selected(isset($_GET['filter']) ? $_GET['filter'] : '', 'all'); ?>><?php echo esc_html__('All Time', 'faq-chatbot'); ?></option>
                    <option value="today" <?php selected(isset($_GET['filter']) ? $_GET['filter'] : '', 'today'); ?>><?php echo esc_html__('Today', 'faq-chatbot'); ?></option>
                    <option value="week" <?php selected(isset($_GET['filter']) ? $_GET['filter'] : '', 'week'); ?>><?php echo esc_html__('This Week', 'faq-chatbot'); ?></option>
                    <option value="month" <?php selected(isset($_GET['filter']) ? $_GET['filter'] : '', 'month'); ?>><?php echo esc_html__('This Month', 'faq-chatbot'); ?></option>
                </select>
                <button type="submit" class="button"><?php echo esc_html__('Apply', 'faq-chatbot'); ?></button>
            </form>
        </div>
        
        <!-- History Table -->
        <table class="wp-list-table widefat fixed striped faq-chatbot-admin-table">
            <thead>
                <tr>
                    <th class="manage-column column-date"><?php echo esc_html__('Date', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-question"><?php echo esc_html__('User Question', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-answer"><?php echo esc_html__('Bot Response', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-ip"><?php echo esc_html__('IP Address', 'faq-chatbot'); ?></th>
                </tr>
            </thead>
            
            <tbody>
                <?php if (!empty($history)) : ?>
                    <?php foreach ($history as $record) : ?>
                        <tr>
                            <td class="column-date">
                                <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($record['created_at']))); ?>
                            </td>
                            <td class="column-question">
                                <?php echo esc_html($record['question']); ?>
                            </td>
                            <td class="column-answer">
                                <?php echo wp_kses_post(wpautop($record['answer'])); ?>
                            </td>
                            <td class="column-ip">
                                <?php echo esc_html($record['user_ip']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4"><?php echo esc_html__('No conversation history found.', 'faq-chatbot'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <th class="manage-column column-date"><?php echo esc_html__('Date', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-question"><?php echo esc_html__('User Question', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-answer"><?php echo esc_html__('Bot Response', 'faq-chatbot'); ?></th>
                    <th class="manage-column column-ip"><?php echo esc_html__('IP Address', 'faq-chatbot'); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="faq-chatbot-admin-form">
        <h2><?php echo esc_html__('Analytics', 'faq-chatbot'); ?></h2>
        
        <?php
        // Get some basic analytics if we have history
        if (!empty($history)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'faq_chatbot_history';
            
            // Total conversations
            $total_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            
            // Unanswered questions (approximate by looking for the unknown message)
            $unknown_message = get_option('faq_chatbot_unknown_message', '');
            $unanswered_count = 0;
            if (!empty($unknown_message)) {
                $unanswered_count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_name WHERE answer LIKE %s",
                    '%' . $wpdb->esc_like($unknown_message) . '%'
                ));
            }
            
            // Success rate
            $success_rate = 0;
            if ($total_count > 0) {
                $success_rate = round(100 - (($unanswered_count / $total_count) * 100), 1);
            }
        ?>
        
        <div class="faq-chatbot-analytics">
            <div class="faq-chatbot-analytics-item">
                <h3><?php echo esc_html__('Total Conversations', 'faq-chatbot'); ?></h3>
                <div class="faq-chatbot-analytics-value"><?php echo esc_html($total_count); ?></div>
            </div>
            
            <div class="faq-chatbot-analytics-item">
                <h3><?php echo esc_html__('Success Rate', 'faq-chatbot'); ?></h3>
                <div class="faq-chatbot-analytics-value"><?php echo esc_html($success_rate); ?>%</div>
            </div>
            
            <div class="faq-chatbot-analytics-item">
                <h3><?php echo esc_html__('Unanswered Questions', 'faq-chatbot'); ?></h3>
                <div class="faq-chatbot-analytics-value"><?php echo esc_html($unanswered_count); ?></div>
            </div>
        </div>
        
        <p class="description"><?php echo esc_html__('Tip: If you notice many similar unanswered questions, consider adding new FAQs to cover these topics.', 'faq-chatbot'); ?></p>
        
        <?php } else { ?>
            <p><?php echo esc_html__('No data available for analytics. Enable conversation history and collect some data to see analytics here.', 'faq-chatbot'); ?></p>
        <?php } ?>
    </div>
    
    <div class="faq-chatbot-admin-form">
        <h2><?php echo esc_html__('Data Management', 'faq-chatbot'); ?></h2>
        <p><?php echo esc_html__('Conversation data is automatically deleted after the number of days specified in settings. You can also manually clear all data here.', 'faq-chatbot'); ?></p>
        
        <form method="post" onsubmit="return confirm('<?php echo esc_js(__('Are you sure you want to delete all conversation history? This cannot be undone.', 'faq-chatbot')); ?>');">
            <?php wp_nonce_field('faq_chatbot_clear_history_nonce', 'faq_chatbot_nonce'); ?>
            <input type="hidden" name="action" value="clear_history">
            <button type="submit" class="button button-secondary"><?php echo esc_html__('Clear All History', 'faq-chatbot'); ?></button>
        </form>
    </div>
</div>

<style>
.faq-chatbot-analytics {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}
.faq-chatbot-analytics-item {
    background: #fff;
    padding: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-align: center;
    width: 30%;
}
.faq-chatbot-analytics-value {
    font-size: 24px;
    font-weight: bold;
    color: #3b82f6;
    margin-top: 10px;
}
</style>
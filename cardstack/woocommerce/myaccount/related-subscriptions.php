<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<?php /*

<header>
    <h2><?php echo _e('Related Subscriptions', 'xa-woocommerce-subscription'); ?></h2>
</header>
<table class="related-subscriptions">
    <thead>
        <tr>
            <th class="order-number"><span><?php  _e('Subscription', 'xa-woocommerce-subscription'); ?></span></th>
            <th class="order-date"><span><?php    _e('Status', 'xa-woocommerce-subscription'); ?></span></th>
            <th class="order-status"><span><?php  _e('Next Payment', 'xa-woocommerce-subscription'); ?></span></th>
            <th class="order-total"><span><?php   _e('Total', 'xa-woocommerce-subscription'); ?></span></th>
            <th class="order-actions"><span><?php _e('Actions', 'xa-woocommerce-subscription'); ?></span></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subscriptions as $subscription_id => $subscription) : ?>
            <tr class="order">
                <td class="subscription-id order-number" data-title="<?php _e('ID', 'xa-woocommerce-subscription'); ?>">
                    <a href="<?php echo esc_url($subscription->get_view_order_url()); ?>">
                        <?php echo sprintf(esc_html_x('#%s', 'hash before order number', 'xa-woocommerce-subscription'), esc_html($subscription->get_order_number())); ?>
                    </a>
                </td>
                <td class="subscription-status order-status" style="white-space:nowrap;" data-title="<?php _e('Status', 'xa-woocommerce-subscription'); ?>">
                    <?php echo esc_attr(hforce_get_subscription_status_name($subscription->get_status())); ?>
                </td>
                <td class="subscription-next-payment order-date" data-title="<?php _e('Next Payment', 'xa-woocommerce-subscription'); ?>">
                    <?php echo esc_attr($subscription->get_date_to_display('next_payment')); ?>
                </td>
                <td class="subscription-total order-total" data-title="<?php _e('Total', 'xa-woocommerce-subscription'); ?>">
                    <?php echo wp_kses_post($subscription->get_formatted_order_total()); ?>
                </td>
                <td class="subscription-actions order-actions">
                    <a href="<?php echo esc_url($subscription->get_view_order_url()) ?>" class="button view"><?php _e('View', 'xa-woocommerce-subscription'); ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php do_action('hf_details_after_related_subscriptions_table', $subscriptions, $order_id); ?>

 */ ?> 

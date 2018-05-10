<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

die("oops"); //FIXME

$display_table_head = true;
?>

<tr class="recurring-totals">
    <th colspan="2"><?php esc_html_e('Recurring Totals', 'xa-woocommerce-subscription'); ?></th>
</tr>


<?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
    <?php if (0 == $recurring_cart->next_payment_date) : ?>
        <?php continue; ?>
    <?php endif; ?>
    <tr class="cart-subtotal recurring-total">
        <?php if ($display_table_head) : $display_table_head = false; ?>
            <th rowspan="<?php echo esc_attr($carts_with_multiple_payments); ?>"><?php esc_html_e('Subtotal', 'xa-woocommerce-subscription'); ?></th>
            <td data-title="<?php esc_attr_e('Subtotal', 'xa-woocommerce-subscription'); ?>"><?php echo hf_cart_price_string($recurring_cart->get_cart_subtotal(), $recurring_cart); ?></td>
        <?php else : ?>
            <td><?php echo hf_cart_price_string($recurring_cart->get_cart_subtotal(), $recurring_cart); ?></td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
<?php $display_table_head = true; ?>

<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
    <?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
        <?php if (0 == $recurring_cart->next_payment_date) : ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php foreach ($recurring_cart->get_coupons() as $recurring_code => $recurring_coupon) : ?>
            <?php
            if ($recurring_code !== $code) {
                continue;
            }
            ?>
            <tr class="cart-discount coupon-<?php echo esc_attr($code); ?> recurring-total">
                <?php if ($display_table_head) : $display_table_head = false; ?>
                    <th rowspan="<?php echo esc_attr($carts_with_multiple_payments); ?>"><?php wc_cart_totals_coupon_label($coupon); ?></th>
                    <td data-title="<?php wc_cart_totals_coupon_label($coupon); ?>"><?php hf_cart_totals_coupon_html($recurring_coupon, $recurring_cart); ?></td>
                <?php else : ?>
                    <td><?php hf_cart_totals_coupon_html($recurring_coupon, $recurring_cart); ?></td>
            <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php $display_table_head = true; ?>
<?php endforeach; ?>

<!-- shipping -->
            
<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
    <?php hf_cart_totals_shipping_html(); ?>
<?php endif; ?>

<!-- fee -->                        
<?php foreach (WC()->cart->get_fees() as $fee) : ?>
    <?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
        <?php if (0 == $recurring_cart->next_payment_date) : ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php foreach ($recurring_cart->get_fees() as $recurring_fee) : ?>
            <?php
            if ($recurring_fee->id !== $fee->id) {
                continue;
            }
            ?>
            <tr class="fee recurring-total">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php if (WC()->cart->tax_display_cart === 'excl') : ?>
    <?php if (get_option('woocommerce_tax_total_display') === 'itemized') : ?>

        <?php foreach (WC()->cart->get_taxes() as $tax_id => $tax_total) : ?>
            <?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
                <?php if (0 == $recurring_cart->next_payment_date) : ?>
                    <?php continue; ?>
                <?php endif; ?>
                    <?php foreach ($recurring_cart->get_tax_totals() as $recurring_code => $recurring_tax) : ?>
                        <?php
                        if (!isset($recurring_tax->tax_rate_id) || $recurring_tax->tax_rate_id !== $tax_id) {
                            continue;
                        }
                        ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($recurring_code)); ?> recurring-total">
                        <?php if ($display_table_head) : $display_table_head = false; ?>
                            <th><?php echo esc_html($recurring_tax->label); ?></th>
                            <td data-title="<?php echo esc_attr($recurring_tax->label); ?>"><?php echo wp_kses_post(hf_cart_price_string($recurring_tax->formatted_amount, $recurring_cart)); ?></td>
                    <?php else : ?>
                            <th></th>
                            <td><?php echo wp_kses_post(hf_cart_price_string($recurring_tax->formatted_amount, $recurring_cart)); ?></td>
                    <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php $display_table_head = true; ?>
        <?php endforeach; ?>

        <?php else : ?>

        <?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
                <?php if (0 == $recurring_cart->next_payment_date) : ?>
                    <?php continue; ?>
            <?php endif; ?>
            <tr class="tax-total recurring-total">
                <?php if ($display_table_head) : $display_table_head = false; ?>
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php echo wp_kses_post(hf_cart_price_string($recurring_cart->get_taxes_total(), $recurring_cart)); ?></td>
            <?php else : ?>
                    <th></th>
                    <td><?php echo wp_kses_post(hf_cart_price_string($recurring_cart->get_taxes_total(), $recurring_cart)); ?></td>
            <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php $display_table_head = true; ?>
    <?php endif; ?>
    <?php endif; ?>

<?php foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) : ?>
        <?php if (0 == $recurring_cart->next_payment_date) : ?>
            <?php continue; ?>
        <?php endif; ?>
    <tr class="order-total recurring-total">
    <?php if ($display_table_head) : $display_table_head = false; ?>
            <th rowspan="<?php echo esc_attr($carts_with_multiple_payments); ?>"><?php esc_html_e('Recurring Total', 'xa-woocommerce-subscription'); ?></th>
            <td data-title="<?php esc_attr_e('Recurring Total', 'xa-woocommerce-subscription'); ?>"><?php hf_cart_totals_order_total_html($recurring_cart); ?></td>
    <?php else : ?>
            <td><?php hf_cart_totals_order_total_html($recurring_cart); ?></td>
    <?php endif; ?>
    </tr>
<?php endforeach; ?>
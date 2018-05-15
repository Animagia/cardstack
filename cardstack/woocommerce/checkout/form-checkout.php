<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>

<p>Kupujesz:</p>

<table class="shop_table">
<?php
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item,
            $cart_item_key);

    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible',
                    true, $cart_item, $cart_item_key)) {
        ?>
        <tr class="<?php
        echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item,
                        $cart_item_key));
        ?>">
            <td style="border-top: 1px solid #646464;" class="product-name">
                <?php
                echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item,
                        $cart_item_key) . '&nbsp;';
                ?>
                <?php
                echo apply_filters('woocommerce_checkout_cart_item_quantity',
                        ' <strong class="product-quantity">' . sprintf('&times; %s',
                                $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key);
                ?>
                <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
            </td>
            <td style="border-top: 1px solid #646464;" style="border: 1px;" class="product-total">
                <?php 
                $cardstack_am_subt = apply_filters('woocommerce_cart_item_subtotal',
                        WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
                        $cart_item, $cart_item_key);
                echo str_replace('/ month', '/ miesiąc', $cardstack_am_subt);
                ?>
            </td>
        </tr>
        <?php
    }
}
?>
</table>
        
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<h3 id="order_review_heading">Podsumowanie</h3>

        <p>Sprzedaż jest zwolniona z VAT. Jeśli chcesz otrzymać fakturę, napisz do nas po dokonaniu zakupu.</p>
        
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

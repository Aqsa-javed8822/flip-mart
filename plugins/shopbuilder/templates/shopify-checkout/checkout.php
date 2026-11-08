<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global \WC_Checkout $checkout
 */

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Modules\ShopifyCheckout\ShopifyCheckoutFns;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


WC()->cart->calculate_totals();

$checkout = WC()->checkout();

$is_logged_in = ! ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() );

Fns::load_template( 'shopify-checkout/shopify-header', [] );
$billing = $checkout->get_checkout_fields( 'billing' );

$customize_billing = false;
if ( Fns::is_module_active( 'checkout_fields_editor' ) ) {
	global $checkout_editor_settings; // Define global variable.
	$customize_billing = 'on' === ( $checkout_editor_settings['modify_billing_form'] ?? 'off' );
}
// Astra Theme Support.
if ( class_exists( 'Astra_Woocommerce' ) ) {
	remove_action( 'woocommerce_checkout_billing', [ WC()->checkout(), 'checkout_form_shipping' ] );
	add_action( 'woocommerce_checkout_shipping', [ WC()->checkout(), 'checkout_form_shipping' ] );
}
do_action( 'rtsb_before_shopify_checkout_form' );
?>
	<div class="rtsb-checkout-page-full-width rtsb-shopify-checkout-form-and-summery">

		<div class="shopify-left-side rtsb-sticky-element rtsb-top-spacing-38">
			<div class="shopify-left-side-wrapper">
				<div class="rtsb-checkout-summery rtsb-mb-30 rtsb-mobile-element">
					<div id="order_review_top" class="woocommerce-checkout-review-order rtsb-shopify-order-summery">
						<?php Fns::load_template( 'shopify-checkout/shopify-coupon-form', [] ); ?>
					</div>
				</div>
				<?php Fns::woocommerce_output_all_notices(); ?>
				<div class="rtsb-checkout-page-form">
					<?php
					if ( ! $is_logged_in ) {
						woocommerce_checkout_login_form();
						echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'shopbuilder' ) ) );
					} else {
						do_action( 'rtsb/before/shopify/checkout/form' );
						woocommerce_login_form(
							[
								'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'shopbuilder' ),
								'redirect' => wc_get_checkout_url(),
								'hidden'   => true,
							]
						);
						?>
						<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
							<div id="billingAddress" class="rtsb-shopify-address-fields <?php echo esc_attr( ShopifyCheckoutFns::is_maltistep() ? 'multistep' : '' ); ?> active">
								<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
								<?php
								if ( ! is_user_logged_in() ) {
									?>
									<span class="rtsb-login-form">
										<?php
										printf(
											'%s <a href="#" class="showlogin">%s</a>',
											esc_html__( 'Already have an account?', 'shopbuilder' ),
											esc_html__( 'Log in', 'shopbuilder' )
										);
										?>
									</span>
									<?php
								}
								if ( ! $customize_billing && ! empty( $billing['billing_email'] ) ) {
									?>
									<div class="rtsb-mb-30">
										<div class="rtsb-shopify-contact-title">
											<h3><?php ShopifyCheckoutFns::print_text_setting( 'email_field_title', esc_html__( 'Contact', 'shopbuilder' ) ); ?></h3>
										</div>
										<?php
										woocommerce_form_field( 'billing_email', $billing['billing_email'], $checkout->get_value( 'billing_email' ) );
										unset( $billing['billing_email'] );
										?>
									</div>
									<?php
								}
								Fns::load_template(
									'shopify-checkout/shopify-billing-fields',
									[
										'checkout' => $checkout ,
										'billing'  => $billing ,
									]
								);
						if ( true === WC()->cart->needs_shipping_address() ) {
							?>
									</div>
									<div id="shippingAddress" class="rtsb-shopify-shipping-fields rtsb-mb-30 <?php echo esc_attr( ShopifyCheckoutFns::is_maltistep() ? 'multistep' : '' ); ?>">
								<?php } ?>
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
								<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
							</div>
						   <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) { ?>
							<div id="shippingMethod" class="rtsb-shopify-shipping-method rtsb-mb-30 <?php echo esc_attr( ShopifyCheckoutFns::is_maltistep() ? 'multistep' : '' ); ?>">
								<?php Fns::load_template( 'shopify-checkout/shopify-shipping-method', [] ); ?>
							</div>
							<?php } ?>
							<div id="makePayment" class="rtsb-shopify-payment-method <?php echo esc_attr( ShopifyCheckoutFns::is_maltistep() ? 'multistep' : '' ); ?>">
								<h3 class="rtsb-mobile-element"><?php echo esc_html__( 'Order Summery', 'shopbuilder' ); ?></h3>
								<div class="rtsb-checkout-summery rtsb-mb-30 rtsb-mobile-element">
									<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
									<div id="order_review_inner" class="woocommerce-checkout-review-order rtsb-shopify-order-summery">
										<?php Fns::load_template( 'shopify-checkout/shopify-cart-items', [] ); ?>
										<div class="shopify-order-footer">
											<?php Fns::load_template( 'shopify-checkout/order-review-footer', [] ); ?>
										</div>
									</div>
									<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
								</div>
								<h3><?php echo esc_html__( 'Payment', 'shopbuilder' ); ?></h3>
								<?php woocommerce_checkout_payment(); ?>
							</div>
							<?php do_action( 'rtsb/after/payment/section/shopify/checkout/form' ); ?>
						</form>
					<?php } ?>
				</div>
				<div class="rtsb-checkout-page-footer">
					<?php ShopifyCheckoutFns::print_footer_menu(); ?>
					<?php ShopifyCheckoutFns::print_text_setting( 'footer_text' ); ?>
				</div>
			</div>
		</div>

		<div class="rtsb-checkout-summery rtsb-xs-none rtsb-sticky-element rtsb-top-spacing-38">
			<div id="order_review" class="woocommerce-checkout-review-order rtsb-shopify-order-summery">
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<?php Fns::load_template( 'shopify-checkout/shopify-cart-items', [] ); ?>
				<?php Fns::load_template( 'shopify-checkout/shopify-coupon-form', [] ); ?>
				<div class="shopify-order-footer">
					<?php Fns::load_template( 'shopify-checkout/order-review-footer', [] ); ?>
				</div>
				<div class="shopify-order-review-extra-content">
					<?php ShopifyCheckoutFns::print_text_setting( 'extra_content' ); ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>

		</div>

	</div>

<?php do_action( 'rtsb_after_shopify_checkout_form' ); ?>

<?php Fns::load_template( 'shopify-checkout/shopify-footer', [] ); ?>

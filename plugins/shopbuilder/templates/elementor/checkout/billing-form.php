<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
$wrapper_class = ! empty( $controllers['fields_width_100'] ) ? 'rtsb-form-fields-width-100' : '';
?>
<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

<div class="rtsb-form-billing <?php echo esc_attr( $wrapper_class ); ?>">
	<?php
		wc_get_template(
			'checkout/form-billing.php',
			[
				'checkout' => WC()->checkout(),
			]
		);
		?>
</div>

<?php
if ( ! WC()->cart->needs_shipping_address() ) {
	do_action( 'woocommerce_checkout_after_customer_details' );
}


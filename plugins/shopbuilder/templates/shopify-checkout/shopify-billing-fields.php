<?php
/**
 * Template variables:
 *
 * @var $checkout
 * @var $billing
 */

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Modules\ShopifyCheckout\ShopifyCheckoutFns;

?>
<div class=" rtsb-shopify-billing-fields rtsb-mb-30">
<?php
$billing_default = false;
if ( Fns::is_module_active( 'checkout_fields_editor' ) ) {
	global $checkout_editor_settings; // Define global variable.
	$billing_default = 'on' === ( $checkout_editor_settings['modify_billing_form'] ?? 'off' );
	if ( $billing_default ) {
		do_action( 'woocommerce_checkout_billing' );
	}
}
if ( ! $billing_default ) {
	?>
	<h3><?php ShopifyCheckoutFns::print_text_setting( 'billing_address_field_title', esc_html__( 'Billing Address', 'shopbuilder' ) ); ?></h3>
	<?php
	foreach ( $billing as $field => $value ) {
		woocommerce_form_field( $field, $value, $checkout->get_value( $field ) );
	}
}

?>
</div>
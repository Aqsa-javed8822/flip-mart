<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $checkout  Object  WC()->checkout()
 * @var $is_builder  bool
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


$has_shipping = true === WC()->cart->needs_shipping_address();

if ( $is_builder && ! $has_shipping ) {
	$info_msg = __( 'Maybe your cart is empty Or The shipping address needs to enable. Please enable it first from the woocommerce settings page.', 'shopbuilder' );
	echo '<p>' . esc_html( $info_msg ) . '</p>';
}
$wrapper_class = ! empty( $controllers['fields_width_100'] ) ? 'rtsb-form-fields-width-100' : '';
?>
<?php if ( $has_shipping ) : ?>
	<div class="rtsb-form-shipping <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="woocommerce-shipping-fields">
			<h3 id="ship-to-different-address">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'shopbuilder' ); ?></span>
				</label>
			</h3>
			<div class="shipping_address">

				<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

				<div class="woocommerce-shipping-fields__field-wrapper">
					<?php
					$fields = $checkout->get_checkout_fields( 'shipping' );

					foreach ( $fields as $key => $field ) {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
					?>
				</div>

				<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

			</div>
		</div>
	</div>

	<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

<?php endif; ?>

<?php

use RadiusTheme\SB\Helpers\Fns;

$shipping_packages = WC()->shipping()->get_packages();
?>

<div class="shopify-order-footer-inner">

	<div class="cart-subtotal">
		<div class="label"><?php esc_html_e( 'Subtotal', 'shopbuilder' ); ?></div>
		<div class="value"><?php wc_cart_totals_subtotal_html(); ?></div>
	</div>
	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<div class="label" ><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
			<div class="value"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
		</div>
	<?php endforeach; ?>

	<?php
	if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() && ! empty( WC()->session->chosen_shipping_methods ) ) {
		ob_start();
		foreach ( $shipping_packages as $index => $package ) {
			$available_methods = $package['rates'];
			$chosen_method     = isset( WC()->session->chosen_shipping_methods[ $index ] ) ? WC()->session->chosen_shipping_methods[ $index ] : '';
			foreach ( $available_methods as $method ) {
				if ( ! checked( $method->id, $chosen_method, false ) ) {
					continue;
				}
				Fns::print_html( wc_cart_totals_shipping_method_label( $method ), true );
			}
		}
		$shipingData = ob_get_clean();
		?>
		<div class="shipping-summery">
			<div class="label"><?php echo esc_html__( 'Shipping: ', 'shopbuilder' ); ?></div>
			<div class="label">
				<?php
				if ( ! empty( $shipingData ) ) {
					Fns::print_html( $shipingData, true );
				} else {
					echo esc_html__( 'No shipping options available.', 'shopbuilder' );
				}
				?>
			</div>
		</div>
	<?php } ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="fee">
			<div  class="label" ><?php echo esc_html( $fee->name ); ?></div>
			<div class="value"><?php wc_cart_totals_fee_html( $fee ); ?></div>
		</div>
	<?php endforeach; ?>
	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<div  class="label" ><?php echo esc_html( $tax->label ); ?></div>
					<div class="value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="tax-total">
				<div  class="label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
				<div class="value"><?php wc_cart_totals_taxes_total_html(); ?></div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="order-total">
		<div class="label"><?php esc_html_e( 'Total', 'shopbuilder' ); ?></div>
		<div class="value"><?php wc_cart_totals_order_total_html(); ?></div>
	</div>
</div>

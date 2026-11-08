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
?>
<div class="rtsb-shipping-method">

	<table class="rtsb_woocommerce_shipping_methods">
		<?php
		WC()->cart->calculate_totals();
		if ( WC()->cart && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :
			?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php if ( ! empty( $controllers['show_title'] ) ) { ?>
			<tr>
				<td>
					<?php
					$htmltag    = ! empty( $controllers['title_html_tag'] ) ? $controllers['title_html_tag'] : 'h2';
					$title_text = ! empty( $controllers['title_text'] ) ? $controllers['title_text'] : esc_html__( 'Shipping', 'shopbuilder' );
					printf( '<%1$s class="shipping-method-title">%2$s</%1$s>', esc_html( $htmltag ), $title_text ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</td>
			</tr>
			<?php } ?>
			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

			<?php
		endif;
		?>
	</table>

</div>


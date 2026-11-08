<?php $packages = WC()->shipping()->get_packages(); ?>
	<h3 class="rtsb-checkout__section-title"><?php esc_html_e( 'Shipping Method', 'shopbuilder' ); ?></h3>
	<ul id="shipping_method" class="woocommerce-shipping-methods">
		<?php
		foreach ( $packages as $index => $package ) {
			$available_methods = $package['rates'];
			$chosen_method     = isset( WC()->session->chosen_shipping_methods[ $index ] ) ? WC()->session->chosen_shipping_methods[ $index ] : '';
			foreach ( $available_methods as $method ) {
				?>
				<li>
					<?php
					printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', absint( $index ), esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) );
					printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', absint( $index ), esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</li>
				<?php
			}
		}
		?>
	</ul>

<div class="rtsb-shopify-checkout-coupon-form">
	<?php if ( wc_coupons_enabled() ) { ?>
		<form method="post" class="coupon-wrapper" >
			<div class="coupon-form">
				<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'shopbuilder' ); ?>" id="coupon_code" value="" />
			</div>
			<div class="coupon-submit-button">
				<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'shopbuilder' ); ?>"><?php esc_html_e( 'Apply', 'shopbuilder' ); ?></button>
			</div>
		</form>
	<?php } ?>
</div>

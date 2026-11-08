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

use RadiusTheme\SB\Helpers\Fns;


$show_info_icon = ! empty( $controllers['show_info_icon'] ) ? $controllers['show_info_icon'] : '';
$the_icon       = $show_info_icon && ! empty( $controllers['info_icon'] ) ? Fns::icons_manager( $controllers['info_icon'] ) : '';

?>

<div class="rtsb-checkout-coupon-form <?php echo ! empty( $show_info_icon ) ? 'show-info-icon-yes' : ''; ?>">
	<div class="woocommerce-form-coupon-toggle rtsb-notice <?php echo ! empty( $the_icon ) ? 'print-custom-icon' : ''; ?> ">
		<?php wc_print_notice( $the_icon . apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'shopbuilder' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'shopbuilder' ) . '</a>' ), 'notice' ); ?>
	</div>
	<div class="checkout_coupon woocommerce-form-coupon"  style="display:none">
		<?php if ( ! empty( $controllers['show_description'] ) ) { ?>
			<p>
			<?php if ( ! empty( $controllers['description_text'] ) ) { ?>
				<?php echo esc_html( $controllers['description_text'] ); ?>
			 <?php } else { ?>
				<?php esc_html_e( 'If you have a coupon code, please apply it below.', 'shopbuilder' ); ?>
			<?php } ?>
			</p>

		<?php } ?>
		<div class="coupon-form-fields">
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'shopbuilder' ); ?>" id="coupon_code" value="" />
			<button type="button" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'shopbuilder' ); ?>"><?php esc_html_e( 'Apply coupon', 'shopbuilder' ); ?></button>
		</div>
	</div>
</div>

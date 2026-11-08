<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $checkout  Object  WC()->checkout()
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Controllers\Frontend\OrderReviewQuantity;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$wrapper_classes = ! empty( $controllers['table_horizontal_scroll_on_mobile'] ) ? ' rtsb-table-horizontal-scroll-on-mobile' : '';
?>
<div class="rtsb-checkout-order-review<?php echo esc_attr( $wrapper_classes ); ?>">
	<?php
	do_action( 'woocommerce_checkout_before_order_review_heading' );
	if ( ! empty( $controllers['show_title'] ) ) {
		?>
		<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'shopbuilder' ); ?></h3>
		<?php
	}
	?>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<?php
	// Marker for the update_order_review request, where the widget does not render.
	if ( ! empty( $controllers['show_quantity'] ) ) {
		?>
		<input type="hidden" name="<?php echo esc_attr( OrderReviewQuantity::MARKER_FIELD ); ?>" value="1" />
		<?php
	}
	?>

	<?php
	$addons = 'on' === ( Fns::get_options( 'modules', 'product_add_ons' )['active'] ?? '' );
	?>

	<div id="order_review" class="woocommerce-checkout-review-order<?php echo esc_attr( $addons ? ' has-product-addons' : '' ); ?>">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</div>

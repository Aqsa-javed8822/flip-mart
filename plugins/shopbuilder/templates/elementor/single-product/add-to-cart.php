<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $product_type  array Widgets/Addons Settings
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $product;
if ( empty( $product ) ) {
	return;
}

$ajax_class     = ! empty( $controllers['enable_single_ajax_add_to_cart'] ) ? ' has-ajax-cart' : ' no-ajax-cart';
$quantity_style = $controllers['quantity_style'] ?? 'default';
$visibility     = $controllers['visibility'] ?? '';
$cart_icon_html = $controllers['cart_icon_html'] ?? '';
$wrapper_class  = 'rtsb-product-add-to-cart has-cart-button quantity-' . $quantity_style . $visibility . $ajax_class;
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>" data-cart-icon="<?php echo esc_attr( $cart_icon_html ); ?>" data-product-type='<?php echo esc_attr( $product_type ); ?>'>
	<?php woocommerce_template_single_add_to_cart(); ?>
</div>

<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $product;
if ( empty( $product ) ) {
	return;
}

?>

<div class="rtsb-upsells-products has-cart-button" data-cart-icon="<?php echo esc_attr( $controllers['cart_icon_html'] ); ?>">
	<?php woocommerce_upsell_display(); ?>
</div>

<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $is_builder  boolean Is Cart Edit page
 */

defined( 'ABSPATH' ) || exit;

$wrapper_classes  = ! empty( $controllers['show_shipping_address'] ) ? 'show-shipping-address' : 'hide-shipping-address';
$wrapper_classes .= ! empty( $controllers['table_horizontal_scroll_on_mobile'] ) ? ' rtsb-table-horizontal-scroll-on-mobile' : '';
?>

<div class="rtsb-cart-totals <?php echo esc_attr( $wrapper_classes ); ?>">
	<?php woocommerce_cart_totals(); ?>
</div>

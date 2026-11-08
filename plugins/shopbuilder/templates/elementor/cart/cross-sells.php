<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $is_builder  boolean Is Cart Edit page
 * @var $limit
 * @var $columns
 * @var $order_by
 * @var $p_order
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="rtsb-cross-sell has-cart-button" data-cart-icon="<?php echo esc_attr( $controllers['cart_icon_html'] ); ?>">
	<?php woocommerce_cross_sell_display( $limit, $columns, $order_by, $p_order ); ?>
</div>

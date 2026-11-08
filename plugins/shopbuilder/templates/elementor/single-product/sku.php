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

if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
	$sku = $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'shopbuilder' );
	?>
	<div class="rtsb-product-sku">
		<?php do_action( 'woocommerce_product_meta_start' ); ?>
		<span class="sku-wrapper">
			<?php if ( ! empty( $controllers['show_label'] ) ) { ?>
				<span class="sku-label"><?php esc_html_e( 'SKU:', 'shopbuilder' ); ?></span>
			<?php } ?>
			<span class="sku-value"><?php echo esc_html( $sku ); ?></span>
		</span>
	</div>
	<?php
endif;
?>

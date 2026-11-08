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
if ( has_term( '', 'product_tag', $product->get_id() ) ) {
	?>
	<div class="rtsb-product-tags">
		<!-- show_label -->
		<?php
		if ( ! empty( $controllers['show_label'] ) ) {
			?>
			<span class="tags-title"><?php echo esc_html( sprintf( _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'shopbuilder' ) ) ); ?></span>
			<?php
		}
		echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// Tags-only widget: do NOT fire woocommerce_product_meta_end here - WooCommerce core/plugins hook brand and extra meta onto it; the Product Meta widget fires it.
		?>
	</div>
	<?php
}

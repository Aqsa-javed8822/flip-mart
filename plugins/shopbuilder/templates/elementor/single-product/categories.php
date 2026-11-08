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
if ( has_term( '', 'product_cat', $product->get_id() ) ) {
	?>
	<div class="rtsb-product-categories">
		<?php
		if ( ! empty( $controllers['show_label'] ) ) {
			?>
			<span class="categories-title"><?php echo esc_html( sprintf( _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'shopbuilder' ) ) ); ?></span>
			<?php
		}
		echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
	<?php
}

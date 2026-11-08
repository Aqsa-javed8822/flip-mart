<?php
/**
 * Template variables:
 *
 * @var $controllers array  settings as array
 * @var $classes     string visibility classes
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $product;
if ( empty( $product ) ) {
	return;
}

$cat_count = count( $product->get_category_ids() );
$tag_count = count( $product->get_tag_ids() );
$sku       = $product->get_sku();

// Each label is wrapped in its own `.rtsb-meta-label` element (sibling of the value) so the
// Label and Value typography/color controls can target separate elements. WooCommerce's
// native markup keeps the label as a bare text node inside the row wrapper, which makes the
// value inherit the label styles.
$cat_before = '<span class="posted_in"><span class="rtsb-meta-label">' . esc_html( _n( 'Category:', 'Categories:', $cat_count, 'shopbuilder' ) ) . '</span> ';
$tag_before = '<span class="tagged_as"><span class="rtsb-meta-label">' . esc_html( _n( 'Tag:', 'Tags:', $tag_count, 'shopbuilder' ) ) . '</span> ';
?>
<div class="rtsb-product-meta rtsb-is-sku rtsb-is-cat rtsb-is-brand rtsb-is-tag <?php echo esc_attr( $classes ); ?>">
	<div class="product_meta">

		<?php do_action( 'woocommerce_product_meta_start' ); ?>

		<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
			<span class="sku_wrapper"><span class="rtsb-meta-label"><?php esc_html_e( 'SKU:', 'shopbuilder' ); ?></span> <span class="sku"><?php echo esc_html( $sku ? $sku : __( 'N/A', 'shopbuilder' ) ); ?></span></span>
		<?php endif; ?>

		<?php
		// wc_get_product_*_list() returns sanitized term links; safe to print as-is.
		echo wc_get_product_category_list( $product->get_id(), ', ', $cat_before, '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wc_get_product_tag_list( $product->get_id(), ', ', $tag_before, '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>

		<?php do_action( 'woocommerce_product_meta_end' ); ?>

	</div>
</div>

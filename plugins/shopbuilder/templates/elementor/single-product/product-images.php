<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 */

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $product, $post;

if ( empty( $product ) ) {
	return;
}
$parent_class = ! empty( $controllers['sale_flash_badge'] ) ? 'rtsb-sale-flash-position-' . $controllers['flash_sale_position'] : '';
// `lightbox_icon` is already rendered to icon markup upstream (empty string when the
// icon is cleared). When the lightbox stays enabled but no icon is set, fall back to
// the default magnifier SVG so the trigger never renders empty.
$default_zoom_icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="7"/><line x1="20" y1="20" x2="16.65" y2="16.65"/><line x1="11" y1="8.5" x2="11" y2="13.5"/><line x1="8.5" y1="11" x2="13.5" y2="11"/></svg>';
$lightbox_icon     = '';
if ( ! empty( $controllers['show_zoom'] ) ) {
	$lightbox_icon = ! empty( $controllers['lightbox_icon'] ) ? $controllers['lightbox_icon'] : $default_zoom_icon;
}
$gallery_class = ! empty( Fns::get_cached_gallery_ids( $product ) ) ? ' has-product-gallery' : ' no-product-gallery';
// When the lightbox icon is disabled, WooCommerce still injects its default gallery
// trigger via JS (photoswipe init) after our JS removal runs, so hide it via CSS to
// avoid the race that leaves the default magnifier visible.
$gallery_class .= empty( $controllers['show_zoom'] ) ? ' rtsb-lightbox-disabled' : '';

?>
<div class="rtsb-product-images <?php echo esc_attr( $parent_class . $gallery_class ); ?>" data-zoom-icon="<?php echo esc_attr( $lightbox_icon ); ?>">
	<?php if ( ! empty( $controllers['sale_flash_badge'] ) ) { ?>
		<?php woocommerce_show_product_sale_flash(); ?>
	<?php } ?>
	<?php
	ob_start();
	woocommerce_show_product_images();
	$gallery_html = ob_get_clean();
	// Render the lightbox icon into WooCommerce's default gallery trigger server-side
	// so it no longer depends on front-end JS, which does not run in the Elementor
	// editor. The Variation Gallery template renders its own `.rtsb-vg-trigger` with
	// the icon already injected, so this only touches `.woocommerce-product-gallery__trigger`.
	if ( '' !== $lightbox_icon ) {
		$injected     = preg_replace_callback(
			'#(<a\b[^>]*\bwoocommerce-product-gallery__trigger\b[^>]*>).*?(</a>)#is',
			function ( $matches ) use ( $lightbox_icon ) {
				return $matches[1] . $lightbox_icon . $matches[2];
			},
			$gallery_html
		);
		$gallery_html = null !== $injected ? $injected : $gallery_html;
	}
	// WooCommerce gallery markup is produced by core; the injected icon is trusted
	// (Elementor's icon renderer or a hardcoded SVG), so echo the buffer as-is.
	echo $gallery_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>




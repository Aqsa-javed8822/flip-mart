<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
use RadiusTheme\SB\Helpers\Fns;

global $product;
if ( empty( $product ) ) {
	return;
}

ob_start();
do_action( 'woocommerce_product_additional_information', $product );
$content = ob_get_clean();

$is_edit_mode = ! empty( $is_edit_mode );
$is_demo      = false;

if ( empty( $content ) ) {
	// On the frontend, keep the original behaviour and render nothing when the
	// product has no attributes. Inside the Elementor editor, fall back to demo
	// rows so the user can preview and style the layout.
	if ( ! $is_edit_mode ) {
		return;
	}

	$is_demo    = true;
	$demo_attrs = [
		esc_html__( 'Color', 'shopbuilder' )    => esc_html__( 'Blue, Gray, Green, Red', 'shopbuilder' ),
		esc_html__( 'Size', 'shopbuilder' )     => esc_html__( 'S, M, L, XL', 'shopbuilder' ),
		esc_html__( 'Material', 'shopbuilder' ) => esc_html__( 'Cotton', 'shopbuilder' ),
		esc_html__( 'Weight', 'shopbuilder' )   => esc_html__( '0.5 kg', 'shopbuilder' ),
	];

	$rows = '';
	foreach ( $demo_attrs as $label => $value ) {
		$rows .= sprintf(
			'<tr class="woocommerce-product-attributes-item"><th class="woocommerce-product-attributes-item__label">%1$s</th><td class="woocommerce-product-attributes-item__value"><p>%2$s</p></td></tr>',
			esc_html( $label ),
			esc_html( $value )
		);
	}

	$content = '<table class="woocommerce-product-attributes shop_attributes">' . $rows . '</table>';
}

?>
<div class="rtsb-product-additional-information<?php echo $is_demo ? ' rtsb-is-demo-data' : ''; ?>">
	<?php $heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional information', 'shopbuilder' ) ); ?>
	<?php if ( $heading ) : ?>
		<h2><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $is_demo ) : ?>
		<p class="rtsb-demo-data-note"><?php echo esc_html__( 'Demo data — shown in the editor only. The real product attributes will appear on the frontend.', 'shopbuilder' ); ?></p>
	<?php endif; ?>
	<?php Fns::print_html( $content, true ); ?>
</div>




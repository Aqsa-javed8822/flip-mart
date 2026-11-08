<?php
/**
 * Template variables:
 *
 * @var $tax_type          string        Taxonomy type.
 * @var $attr_type         string        Attribute type.
 * @var $input             string        Input type.
 * @var $title             array         Filter title.
 * @var $template          string        Filter template.
 * @var $raw_settings      array         Widgets/Addons Settings
 * @var $repeater_settings array         Repeater Settings
 */

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$tax_type     = RenderHelpers::get_product_filters_tax_type( $tax_type, $attr_type );
$sale_options = [
	'onsale'  => ! empty( $repeater_settings['onsale_title'] ) ? esc_html( $repeater_settings['onsale_title'] ) : esc_html__( 'On Sale Products', 'shopbuilder' ),
	'regular' => ! empty( $repeater_settings['regular_title'] ) ? esc_html( $repeater_settings['regular_title'] ) : esc_html__( 'Regular Products', 'shopbuilder' ),
];

$tax_data     = RenderHelpers::get_all_terms( $tax_type );
$is_attribute = strpos( $tax_type, 'pa_' ) !== false;

if ( is_wp_error( $tax_data ) && 'sale_filter' !== $tax_type ) {
	Fns::print_html( RenderHelpers::filter_error_message( $title, 'rtsb-tags' ) );

	return;
}

if ( $is_attribute && empty( RenderHelpers::get_product_filters_attribute_terms( $tax_type ) ) ) {
	return;
}

$data            = [
	'taxonomy'         => $is_attribute & ! $repeater_settings['show_empty'] ? RenderHelpers::get_product_filters_attribute_terms( $tax_type ) : $tax_data,
	'is_attribute'     => $is_attribute,
	'show_empty_terms' => $repeater_settings['show_empty'],
	'show_child'       => ! empty( $repeater_settings['include_child_cats'] ),
];
$additional_data = [
	'taxonomy' => $tax_type,
	'input'    => $input,
];
$additional_args = [
	'total_count' => 'sale_filter' === $tax_type ? 2 : count( $is_attribute ? RenderHelpers::get_product_filters_attribute_terms( $tax_type ) : $tax_data ),
	'tax_data'    => $tax_data,
];

// Build the term list first so the whole filter block (including its title)
// can be skipped when it has no items to show - e.g. every term is empty in
// the current category archive and "Show Empty Terms" is off.
$rtsb_filter_list_html = '';
if ( ! empty( $tax_data ) && ! is_wp_error( $tax_data ) && 'sale_filter' !== $tax_type ) {
	$rtsb_filter_list_html = RenderHelpers::get_product_default_filter_list_html( $data, $input, $additional_data );
}

if ( 'sale_filter' !== $tax_type && '' === trim( (string) $rtsb_filter_list_html ) ) {
	return;
}
?>

<div class="rtsb-product-default-filters rtsb-tags">
	<?php
	/**
	 * Filter title.
	 */
	Fns::print_html( RenderHelpers::get_default_filter_title( $title ) );

	/**
	 * Filter content.
	 */
	echo '<div class="default-filter-content">';

	if ( 'sale_filter' === $tax_type ) {
		Fns::print_html( RenderHelpers::get_default_filter_html( $tax_type, $sale_options, $additional_data, 'radio' ), true );
	} else {
		Fns::print_html( $rtsb_filter_list_html, true );
	}

	echo '</div>';
	?>
</div>

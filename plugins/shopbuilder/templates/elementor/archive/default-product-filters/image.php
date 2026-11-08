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

$tax_type   = RenderHelpers::get_product_filters_tax_type( $tax_type, $attr_type );
$show_label = ! empty( $repeater_settings['show_label'] ) ? ' has-label' : ' no-label';
$tax_data   = RenderHelpers::get_all_terms( $tax_type );

if ( is_wp_error( $tax_data ) ) {
	Fns::print_html( RenderHelpers::filter_error_message( $title, 'rtsb-' . esc_attr( $input ) ) );
	return;
}

if ( empty( $tax_data ) ) {
	return;
}
?>

<div class="rtsb-product-default-filters rtsb-<?php echo esc_attr( $input ); ?>">
	<?php
	$additional_data = [
		'taxonomy' => $tax_type,
		'input'    => $input,
	];
	$additional_args = [
		'total_count' => count( $tax_data ),
		'tax_data'    => $tax_data,
	];

	/**
	 * Filter title.
	 */
	Fns::print_html( RenderHelpers::get_default_filter_title( $title ) );

	/**
	 * Filter content.
	 */
	echo '<div class="default-filter-content">';

	if ( ! empty( $tax_data ) && ! is_wp_error( $tax_data ) ) {
		list(
			'attribute' => $attribute,
			'term_type' => $term_type,
			'attribute_slug' => $attribute_slug,
			'filter_name' => $filter_name,
			'base_link' => $base_link
			) = RenderHelpers::get_product_filters_attribute_term_info( $tax_type );

		$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : []; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$current_filter = array_map( 'sanitize_title', $current_filter );

		$term_info = [
			'attribute'      => $attribute,
			'type'           => $term_type,
			'filter_name'    => $filter_name,
			'base_link'      => $base_link,
			'current_filter' => $current_filter,
		];
		$data      = [
			'terms'            => $tax_data,
			'term_info'        => $term_info,
			'show_empty_terms' => ! empty( $repeater_settings['show_empty'] ),
			'show_label'       => $show_label,
			'show_tooltips'    => ! empty( $repeater_settings['show_tooltips'] ),
			'count_display'    => 'none',
		];
		Fns::print_html( RenderHelpers::get_attribute_filter_list_html( $data, $input, $additional_data ), true );
	} else {
		echo '<p class="no-filter">' . esc_html__( 'Nothing found.', 'shopbuilder' ) . '</p>';
	}


	echo '</div>';
	?>
</div>

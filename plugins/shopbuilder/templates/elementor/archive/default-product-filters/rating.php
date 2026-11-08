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
 * @var $rating_icon       array         Rating icon.
 * @var $repeater_settings array         Repeater Settings
 */

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$base_url = RenderHelpers::get_base_url();
?>

<div class="rtsb-product-default-filters rtsb-ratings">
	<?php

	$default_rating = ! empty( $_GET['rating_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['rating_filter'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$active_rating  = explode( ',', $default_rating );



	/**
	 * Filter title.
	 */
	Fns::print_html( RenderHelpers::get_default_filter_title( $title ) );

	/**
	 * Filter content.
	 */
	echo '<div class="default-filter-content">';

	echo '<ul class="product-default-filters input-type-checkbox">';

	for ( $i = 5; $i >= 1; $i-- ) {
		$unique_id = substr( uniqid(), -6 );
		$checked   = in_array( (string) $i, $active_rating, true ) ? ' checked' : '';
		$active    = in_array( (string) $i, $active_rating, true ) ? ' active' : '';

		echo '<li class="rtsb-default-filter-term-item">';
		echo '<div class="rtsb-default-filter-group' . esc_attr( $active ) . '">';
		echo '<input type="checkbox" class="rtsb-default-filter-trigger rtsb-checkbox-filter' . esc_attr( $checked ) . '"  name="rtsb-filter-rating[]" id="rtsb-default-rating-filter-' . esc_attr( $unique_id ) . '" value="' . absint( $i ) . '" ' . esc_attr( $checked ) . '>';
		echo '<label class="rtsb-default-checkbox-filter-label" for="rtsb-default-rating-filter-' . esc_attr( $unique_id ) . '">';
		echo '<span class="rtsb-default-rating-star-wrapper">';
		echo '<span class="rtsb-default-rating-star inactive">';

		for ( $rating = 1; $rating <= 5; $rating++ ) {
			Fns::print_html( Fns::icons_manager( $rating_icon, 'rating-icon' ) );
		}

		echo '</span>';
		echo '<span class="rtsb-default-rating-star active">';

		for ( $rating = 1; $rating <= $i; $rating++ ) {
			Fns::print_html( Fns::icons_manager( $rating_icon, 'rating-icon' ) );
		}

		echo '</span>';
		echo '</span>';
		echo '<span class="rtsb-count">(' . absint( RenderHelpers::get_product_rating_count( $i ) ) . ')</span>';
		echo '</label>';
		echo '</div>';
		echo '</li>';
	}

	echo '</ul>';

	echo '</div>';
	?>
</div>

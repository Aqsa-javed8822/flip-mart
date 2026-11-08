<?php
/**
 * Template variables:
 *
 * @var $filters            array      Filters.
 * @var $scroll_mode        string     Scroll mode.
 * @var $reset_mode         string     Reset mode.
 * @var $toggle_class         string   Toggle class.
 * @var $scroll_attr        string     Scroll attributes.
 * @var $reset              boolean    Reset.
 * @var $reset_text         string     Reset text.
 * @var $form_action        string     Url.
 * @var $settings           array      Settings array.
 */

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>

<div class="rtsb-archive-default-filters-wrapper<?php echo esc_attr( $reset_mode . $scroll_mode ); ?>" <?php Fns::print_html( $scroll_attr ); ?>>
	<form id="rtsb-product-default-filter-form" method="GET" action="<?php echo esc_url( $form_action ); ?>">
	<?php

	/**
	 * Before archive filter items hook.
	 *
	 * @hooked RadiusTheme\SB\Controllers\Hooks::default_filter_header 10
	 * @hooked RadiusTheme\SB\Controllers\Hooks::default_filter_search 15
	 */
	do_action( 'rtsb/before/archive/default/filter/items', $settings );
	if ( ! empty( $filters ) && is_array( $filters ) ) {
		foreach ( $filters as $name => $type ) {
			 $arg['attr_type']         = '';
			 $arg['tax_type']          = $type['filter_items'];
			 $arg['title']             = [
				 'title' => $type['filter_title'],
			 ];
			 $arg['template']          = $type['template'];
			 $arg['input']             = $type['input_type'];
			 $arg['reset']             = $reset;
			 $arg['raw_settings']      = $settings;
			 $arg['repeater_settings'] = $settings['filter_types'][ $name ];

			 if ( 'product_attr' === $type['filter_items'] ) {
				 $arg['attr_type'] = $type['filter_attr'];
			 }

			 if ( 'rating_filter' === $type['filter_items'] ) {
				 $arg['rating_icon'] = $settings['filter_types'][ $name ]['rating_icon'];
				 $arg['input']       = 'rating';
			 }
			 Fns::print_html( Fns::load_template( $type['template'], $arg, true ), true );
		}
	} else {
		echo '<p>' . esc_html__( 'Please select a filter.', 'shopbuilder' ) . '</p>';
	}

	Fns::print_html( RenderHelpers::get_default_filter_reset_button( $reset_text, $settings ), true );

	/**
	 * After archive filter items hook.
	 */
	do_action( 'rtsb/after/archive/default/filter/items', $settings );

	?>
	</form>
</div>

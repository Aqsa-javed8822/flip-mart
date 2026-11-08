<?php
/**
 * ProductsSlider class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsSlider class.
 */
class RelatedProductsSlider extends RelatedProductsCustom {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Related -  Slider Layouts', 'shopbuilder' );
		$this->pro_tab   = 'layout';
	}

	protected function get_the_base() {
		return 'rtsb-related-products-slider';
	}

	/**
	 * @return string
	 */
	protected function render_template_file() {
		return 'elementor/general/slider/';
	}

	/**
	 * @return array|RelatedProductsSlider
	 */
	protected function widget_layout() {
		return Controls\LayoutFields::slider_layout( $this );
	}

	/**
	 * Controls for settings tab
	 *
	 * @return array
	 */
	protected function settings_tab() {
		$fields         = parent::settings_tab();
		$slider_control = Controls\SettingsFields::slider_settings( $this );
		$fields         = Fns::insert_controls( 'visibility_section_end', $fields, $slider_control, true );

		unset(
			$fields['slider_loop']['condition'],
			$fields['slider_lazy_load']['condition']
		);

		return $fields;
	}

	protected function pagination_navigation() {
		return Controls\StyleFields::slider_buttons( $this );
	}
}

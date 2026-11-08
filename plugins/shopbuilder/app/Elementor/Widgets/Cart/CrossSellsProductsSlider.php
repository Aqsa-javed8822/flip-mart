<?php
/**
 * ProductsSlider class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Cart;

use RadiusTheme\SB\Elementor\Widgets\Controls;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsSlider class.
 */
class CrossSellsProductsSlider extends CrossSellsProductsCustom {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Cross Sell - Slider Layouts', 'shopbuilder' );
		$this->pro_tab   = 'layout';
	}

	protected function get_the_base() {
		return 'rtsb-crosssell-product-slider';
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
		$parent = parent::settings_tab();
		$slider_control = Controls\SettingsFields::slider_settings( $this );
		$fields = Fns::insert_controls( 'visibility_section_end', $parent, $slider_control, true );

		unset(
			$fields['slider_loop']['condition'],
			$fields['slider_lazy_load']['condition']
		);

		return $fields;
	}
	/**
	 * Controls for style tab
	 *
	 * @return array
	 */
	protected function pagination_navigation() {
		return Controls\StyleFields::slider_buttons( $this );
	}
}

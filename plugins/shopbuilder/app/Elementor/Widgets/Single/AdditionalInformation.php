<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\AddInfoSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class AdditionalInformation extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Additional Information', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-additional-info';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return AddInfoSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Information', 'Additional' ] + parent::get_keywords();
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product;
		$_product    = $product;
		$controllers = $this->get_settings_for_display();
		$product     = Fns::get_product();
		$this->theme_support();
		$data = [
			'template'     => 'elementor/single-product/additional-information',
			'controllers'  => $controllers,
			'is_edit_mode' => $this->is_edit_mode(),
		];
		remove_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		if ( empty( $controllers['show_title'] ) ) {
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		}
		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}

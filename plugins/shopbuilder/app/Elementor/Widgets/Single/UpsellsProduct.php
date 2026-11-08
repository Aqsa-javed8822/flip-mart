<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Abstracts\LoopWithProductSlider;

/**
 * Product Description class
 */
class UpsellsProduct extends LoopWithProductSlider {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_base = 'rtsb-upsells-product';
		parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Upsell - Default Layout', 'shopbuilder' );
	}

	/**
	 * Apply hooks function.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		$controllers = $this->get_settings_for_display();
		parent::apply_hooks();
		add_filter( 'woocommerce_upsell_display_args', [ $this, 'products_args' ] );
		if ( empty( $controllers['show_title'] ) ) {
			add_filter( 'woocommerce_product_upsells_products_heading', '__return_false' );
		} elseif ( ! empty( $controllers['loop_title_text'] ) ) {
			add_filter(
				'woocommerce_product_upsells_products_heading',
				function( $text ) use ( $controllers ) {
					return $controllers['loop_title_text'];
				}
			);
		}

	}

	/**
	 * Widget Selector
	 *
	 * @return array
	 */
	public function template_data_arg() {
		return [
			'template' => 'elementor/single-product/upsells-product',
		];
	}

}

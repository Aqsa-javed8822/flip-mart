<?php
/**
 * Main ProductCats class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductTaxSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Categories class
 */
class ProductCats extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Categories', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-categories';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$fields = ProductTaxSettings::widget_fields( $this );
		if ( ! empty( $fields['align'] ) ) {
			$fields['align']['selectors'] = [
				$this->selectors['align'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
			];
		}
		return $fields;
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
		$this->theme_support();
		$data = [
			'template'    => 'elementor/single-product/categories',
			'controllers' => $controllers,
		];
		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}

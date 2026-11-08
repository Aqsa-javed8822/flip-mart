<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductsOrderingSettings;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductsOrdering extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Products Ordering', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-products-ordering';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ProductsOrderingSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Archive' ] + parent::get_keywords();
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();
		$this->theme_support();
		$data = [
			'template'    => 'elementor/archive/catalog-ordering',
			'controllers' => $controllers,
		];

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}
}

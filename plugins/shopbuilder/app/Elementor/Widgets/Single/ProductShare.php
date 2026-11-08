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

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductShareSettings;

/**
 * Product Description class
 */
class ProductShare extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Share', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-share';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ProductShareSettings::widget_fields( $this );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product, $post;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
        $this->theme_support();
		ob_start();
			woocommerce_template_single_sharing();
		$share_html = ob_get_clean();

		if ( empty( $share_html ) && $this->is_builder_mode() ) {
			$share_html = $this->rtsb_name;
		}

		$data        = [
			'template'    => 'elementor/single-product/product-share',
			'controllers' => $controllers,
			'content'     => $share_html,
		];

		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

}

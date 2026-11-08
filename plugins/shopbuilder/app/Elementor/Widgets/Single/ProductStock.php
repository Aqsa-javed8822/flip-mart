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
use RadiusTheme\SB\Elementor\Widgets\Controls\StockSettings;

/**
 * Product Description class
 */
class ProductStock extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Stock', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-stock';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return StockSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Availability', 'Status' ] + parent::get_keywords();
	}
	/**
	 * Set icons function
	 *
	 * @param [type] $availability status.
	 * @param [type] $obj  WC_Product class object.
	 * @return string
	 */
	public function set_icons( $availability, $obj ) {
		if ( empty( $availability ) ) {
			return '';
		}
		$controllers = $this->get_settings_for_display();
		$icon        = '';

		if ( ! $obj->is_in_stock() ) {
			$icon = Fns::icons_manager( $controllers['out_of_stock_icon'] );
		} elseif ( $obj->is_on_backorder( 1 ) ) {
			if ( $obj->managing_stock() && $obj->backorders_require_notification() ) {
				$icon = Fns::icons_manager( $controllers['stock_backorder_icon'] );
			} elseif ( ! $obj->managing_stock() ) {
				$icon = Fns::icons_manager( $controllers['stock_backorder_icon'] );
			}
		} elseif ( $obj->managing_stock() && $obj->is_in_stock() ) {
			$icon = Fns::icons_manager( $controllers['in_stock_icon'] );
		}

		$icon = apply_filters( 'rtsb/elementor/product_stock_icons', $icon, $controllers, $obj );

		// Wrap the availability text in its own element so the Typography control (e.g. text-decoration)
		// targets only the text and never paints onto the sibling icon. See ControlSelectors stock_text_typography.
		return $icon . '<span class="rtsb-stock-text">' . $availability . '</span>';
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
		add_filter( 'woocommerce_get_availability_text', [ $this, 'set_icons' ], 10, 2 );
		$data         = [
			'template'    => 'elementor/single-product/stock',
			'controllers' => $controllers,
		];
		$availability = $product->get_availability();
		if ( ! empty( $availability['availability'] ) ) {
			$data['content'] = wc_get_stock_html( $product );
		} else {
			$data['content'] = '<p class="stock in-stock"><i aria-hidden="true" class="rtsb-icon rtsb-icon-check-alt"></i><span class="rtsb-stock-text">' . esc_html__( 'In stock', 'shopbuilder' ) . '</span></p>';
		}
		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}

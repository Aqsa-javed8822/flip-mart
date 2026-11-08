<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Cart;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\CartTotalsSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CartTotals extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Cart Totals', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-cart-totals';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return CartTotalsSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Cart' ] + parent::get_keywords();
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
			'template'    => 'elementor/cart/cart-totals',
			'controllers' => $controllers,
			'is_builder'  => $this->is_builder_mode(),
		];
		if ( $data['is_builder'] ) {
			wc_load_cart();
		}
		// Calculate Shipping.
		if ( ! empty( $_POST['woocommerce-shipping-calculator-nonce'] ) && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			\WC_Shortcode_Cart::calculate_shipping();
		}
		// Re render totals div.
		WC()->cart->calculate_totals();

		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
	}
}

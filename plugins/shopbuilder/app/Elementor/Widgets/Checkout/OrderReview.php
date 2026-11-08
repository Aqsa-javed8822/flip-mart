<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Checkout;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Controllers\Frontend\OrderReviewQuantity;
use RadiusTheme\SB\Elementor\Widgets\Controls\OrderReviewSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class OrderReview extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Order Review', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-order-review';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return OrderReviewSettings::widget_fields( $this );
	}

	/**
	 * Widget Field.
	 *
	 * @param array $controllers Widget settings.
	 *
	 * @return void
	 */
	public function apply_hooks( $controllers = [] ) {
		// Editable quantity inside the review table.
		if ( ! empty( $controllers['show_quantity'] ) ) {
			OrderReviewQuantity::instance()->enable();
		}

		// Remove Payment Button.
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment' );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		// Germanized for WooCommerce.
		if ( function_exists( 'woocommerce_gzd_template_order_submit' ) ) {
			remove_action( 'woocommerce_checkout_order_review', 'woocommerce_gzd_template_order_submit', 21 );
		}
	}

	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Checkout' ] + parent::get_keywords();
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		if ( $this->has_checkout_restriction() ) {
			return;
		}
		$controllers = $this->get_settings_for_display();

		$this->apply_hooks( $controllers );
		$this->theme_support();

		$data = [
			'template'    => 'elementor/checkout/order-review',
			'controllers' => $controllers,
		];

		if ( $this->is_builder_mode() ) {
			 wc_load_cart();
		}

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}
}

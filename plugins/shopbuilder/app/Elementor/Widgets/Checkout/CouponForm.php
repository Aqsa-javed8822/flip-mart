<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Checkout;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\CheckoutInfoBoxSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CouponForm extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Coupon Form ', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-coupon-form';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return CheckoutInfoBoxSettings::widget_fields( $this );
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

		$this->theme_support();

		$data = [
			'template'    => 'elementor/checkout/coupon-form',
			'controllers' => $this->get_settings_for_display(),
		];

		if ( $this->is_builder_mode() ) {
			wc_load_cart();
		}

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}
}

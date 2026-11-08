<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Checkout;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\CheckoutLoginFormSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CheckoutLoginForm extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Checkout Login Form', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-checkout-login-form';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return CheckoutLoginFormSettings::widget_fields( $this );
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
		$this->theme_support();

		$data = [
			'template'    => 'elementor/checkout/form-login',
			'controllers' => $this->get_settings_for_display(),
			'message'     => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'shopbuilder' ),
			'redirect'    => wc_get_checkout_url(),
			'hidden'      => true,
			'is_builder'  => $this->is_builder_mode(),
		];

		if ( $this->is_builder_mode() ) {
			wc_load_cart();
		}

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}
}

<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Checkout;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\CheckoutFromSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class OrderNotes extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Additional information - Order Notes', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-order-notes';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$fields = CheckoutFromSettings::widget_fields( $this );
		unset( $fields['fields_label_style_start']['tab'] );
		unset( $fields['fields_style_start']['tab'] );
		return $fields;
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Order Notes', 'Checkout', 'Additional information' ] + parent::get_keywords();
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
			'template'    => 'elementor/checkout/order-notes',
			'controllers' => $this->get_settings_for_display(),
			'checkout'    => WC()->checkout(),
		];

		if ( $this->is_builder_mode() ) {
			wc_load_cart();
		}

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}
}

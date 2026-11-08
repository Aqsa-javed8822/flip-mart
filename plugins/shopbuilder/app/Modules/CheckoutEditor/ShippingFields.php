<?php

namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * CheckoutEditorInit
 */
class ShippingFields extends CheckoutEditorBase {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Notifications hooks.
	 */
	private function __construct() {
		parent::__construct();
		add_filter( 'woocommerce_shipping_fields', [ $this, 'shipping_fields_generator' ], 9999, 2 );
	}

	/**
	 * @param array $address_fields fields.
	 * @param array $country fields.
	 * @return array
	 */
	public function shipping_fields_generator( $address_fields, $country ) {
		return $this->render_fields( $this->shipping_settings, $address_fields );
	}
}

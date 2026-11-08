<?php

namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use PhpParser\Node\Expr\Empty_;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * CheckoutEditorInit
 */
class BillingFields extends CheckoutEditorBase {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Notifications hooks.
	 */
	private function __construct() {
		parent::__construct();
		add_filter( 'woocommerce_billing_fields', [ $this, 'billing_fields_generator' ], 99, 2 );
	}
	/**
	 * @param array $address_fields fields.
	 * @param array $country fields.
	 * @return array
	 */
	public function billing_fields_generator( $address_fields, $country ) {
		$billing_fields = $this->render_fields( $this->billing_settings, $address_fields );
		unset( $billing_fields['billing_address_1']['validate'] );
		return $billing_fields;
	}
}

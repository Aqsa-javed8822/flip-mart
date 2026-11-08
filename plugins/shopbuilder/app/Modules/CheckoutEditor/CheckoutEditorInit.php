<?php

namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\GeneralList;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * CheckoutEditorInit
 */
final class CheckoutEditorInit {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @var array
	 */
	private static $cache = [];

	/**
	 * @var array|mixed
	 */
	private array $options;

	/**
	 * Notifications hooks.
	 */
	private function __construct() {
		// If the cached result doesn't exist, fetch it from the database.
		global $checkout_editor_settings; // Define global variable.
		$this->options            = Fns::get_options( 'modules', 'checkout_fields_editor' ); // Assign options to the global variable.
		$checkout_editor_settings = $this->options;
		$this->disable_general_settings();
		GeneralCheckout::instance();
		if ( 'on' === ( $this->options['modify_billing_form'] ?? '' ) ) {
			BillingFields::instance();
		}
		if ( 'on' === ( $this->options['modify_shipping_form'] ?? '' ) ) {
			ShippingFields::instance();
		}
		if ( 'on' === ( $this->options['modify_additional_form'] ?? '' ) ) {
			 AdditionalFields::instance();
		}
		do_action( 'rtsb/checkout/editor/init' );
	}

	/**
	 * @return void
	 */
	public function disable_general_settings() {
		$elementor_list   = GeneralList::instance()->get_data();
		$billing_settings = $elementor_list['billing_form'] ?? [];
		$shipping_form    = $elementor_list['shipping_form'] ?? [];
		if ( ! empty( $billing_settings['active'] ) ) {
			Fns::set_options( 'general', 'billing_form', [ 'active' => '' ] );
		}
		if ( ! empty( $shipping_form['active'] ) ) {
			 Fns::set_options( 'general', 'shipping_form', [ 'active' => '' ] );
		}
	}
}

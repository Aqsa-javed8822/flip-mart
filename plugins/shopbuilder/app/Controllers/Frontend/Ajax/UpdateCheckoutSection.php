<?php
/**
 * Add to Cart Ajax Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Frontend\Ajax;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Add to Cart Ajax Class.
 */
class UpdateCheckoutSection {
	/**
	 * Singleton.
	 */
	use SingletonTrait;

	/**
	 * Class Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'wp_ajax_rtsb_shopify_checkout_section_update', [ $this, 'response' ] );
		add_action( 'wp_ajax_nopriv_rtsb_shopify_checkout_section_update', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		WC()->cart->calculate_totals();
		WC()->cart->calculate_shipping();
		ob_start();
		Fns::load_template( 'shopify-checkout/order-review-footer', [] );
		$calculate = ob_get_clean();
		ob_start();
		Fns::load_template( 'shopify-checkout/shopify-shipping-method', [] );
		$shipping_method = ob_get_clean();
		wp_send_json_success(
			[
				'order_calculate' => $calculate,
				'shipping_method' => $shipping_method,
			]
		);
	}
}

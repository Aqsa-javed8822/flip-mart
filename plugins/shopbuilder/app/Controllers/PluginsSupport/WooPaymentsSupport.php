<?php
/**
 * WooCommerce Payments (WooPayments) compatibility support.
 *
 * Prevents infinite checkout update loop caused by dynamic script
 * injection in the payment fragment on every update_order_review.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\PluginsSupport;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * WooCommerce Payments compatibility class.
 *
 * WooPayments appends dynamic script tags (cart totals, currency,
 * nonces) to the .woocommerce-checkout-payment fragment on every
 * update_order_review AJAX response. Because these values change
 * each request, WooCommerce always sees the fragment as "new" and
 * replaces the DOM, causing Stripe to re-mount and fire another
 * update — creating an infinite loop.
 *
 * This class strips those dynamic scripts from the fragment and
 * moves them to a separate fragment key so the payment HTML stays
 * stable between requests.
 */
class WooPaymentsSupport {

	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_filter(
			'woocommerce_update_order_review_fragments',
			[ $this, 'stabilize_payment_fragment' ],
			999
		);
	}

	/**
	 * Stabilize the payment method fragment.
	 *
	 * Extracts inline script tags from the payment fragment so its
	 * HTML stays identical between AJAX requests. The scripts are
	 * moved to a separate fragment that JS can execute after DOM
	 * replacement without triggering another update cycle.
	 *
	 * @param array $fragments Checkout AJAX fragments.
	 *
	 * @return array Modified fragments.
	 */
	public function stabilize_payment_fragment( $fragments ) {
		$key = '.woocommerce-checkout-payment';

		if ( empty( $fragments[ $key ] ) ) {
			return $fragments;
		}

		$html = $fragments[ $key ];

		// Extract all <script> tags from the payment fragment.
		$scripts = '';

		$html = preg_replace_callback(
			'#<script\b[^>]*>.*?</script>#is',
			static function ( $matches ) use ( &$scripts ) {
				$scripts .= $matches[0];
				return '';
			},
			$html
		);

		// Set the cleaned (stable) HTML back as the fragment.
		$fragments[ $key ] = $html;

		// Pass extracted scripts in a separate fragment for JS execution.
		if ( ! empty( $scripts ) ) {
			$fragments['.rtsb-checkout-payment-scripts'] = '<div class="rtsb-checkout-payment-scripts" style="display:none;">' . $scripts . '</div>';
		}

		return $fragments;
	}
}

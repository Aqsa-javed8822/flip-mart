<?php
/**
 * WooCommerce PayPal Payments (PPCP) compatibility support.
 *
 * Prevents checkout issues caused by dynamic script injection in the
 * payment fragment and ensures Advanced Card Processing fields
 * re-initialize properly after AJAX checkout updates.
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
 * WooCommerce PayPal Payments compatibility class.
 *
 * PayPal PPCP injects dynamic script tags (SDK configuration, nonces,
 * client tokens) into the .woocommerce-checkout-payment fragment on
 * every update_order_review AJAX response. This causes the fragment
 * HTML to change each request, triggering WooCommerce to replace the
 * DOM and potentially creating infinite update loops or causing
 * Advanced Card Processing hosted fields to lose their state.
 *
 * This class strips those dynamic scripts from the fragment and moves
 * them to a separate fragment key so the payment HTML stays stable
 * between requests. It also fires a custom event so PPCP card fields
 * can re-initialize after DOM replacement.
 */
class PayPalPPCPSupport {

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

		add_action( 'wp_footer', [ $this, 'render_ppcp_reinit_script' ] );
	}

	/**
	 * Stabilize the payment method fragment.
	 *
	 * Extracts inline and external script tags from the payment fragment
	 * so its HTML stays identical between AJAX requests. The scripts are
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

	/**
	 * Render inline script to re-initialize PPCP card fields after checkout update.
	 *
	 * PayPal Advanced Card Processing renders hosted card field iframes
	 * that are destroyed when WooCommerce replaces the payment fragment.
	 * This script triggers PPCP's native re-render mechanism after each
	 * updated_checkout event.
	 *
	 * @return void
	 */
	public function render_ppcp_reinit_script() {
		if ( ! BuilderFns::is_checkout() ) {
			return;
		}
		?>
		<script id="rtsb-ppcp-reinit">
		(function($) {
			if (typeof $ === 'undefined') return;

			$(document.body).on('updated_checkout', function() {
				// Trigger PPCP payment method change to force card fields re-render.
				var $selected = $('input[name="payment_method"]:checked');
				if ($selected.length && $selected.val().indexOf('ppcp') !== -1) {
					$selected.trigger('click');
				}

				// Fire custom event for PPCP SDK re-initialization.
				$(document.body).trigger('rtsb_ppcp_checkout_updated');

				// PPCP listens for payment_method_selected to render buttons/fields.
				$(document.body).trigger('payment_method_selected');
			});
		})(jQuery);
		</script>
		<?php
	}
}

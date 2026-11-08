<?php
/**
 * Add to Cart Ajax Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Frontend\Ajax;

use WC_AJAX;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Add to Cart Ajax Class.
 */
class AddToCart {
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
		\add_action( 'wp_ajax_rtsb_ajax_add_to_cart', [ $this, 'response' ] );
		\add_action( 'wp_ajax_nopriv_rtsb_ajax_add_to_cart', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_send_json_error();
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', ( isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : null ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$quantity          = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', absint( $_POST['quantity'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$variation_id      = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		// Safe fetch of the variation array.
		$raw_variation = filter_input( INPUT_POST, 'variation', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$variation     = [];
		// Build variation attributes safely.
		if ( ! empty( $raw_variation ) && is_array( $raw_variation ) ) {
			foreach ( $raw_variation as $key => $value ) {
				$variation[ $key ] = wc_clean( $value );
			}
		}
		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			WC_AJAX::get_refreshed_fragments();
		} else {
			$data = [
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			];

			wp_send_json( $data );
		}

		wp_send_json(
			[
				'error' => true,
				'msg'   => esc_html__( 'WooCommerce not installed', 'shopbuilder' ),
			]
		);
	}
}

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
class AjaxLogin {
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
		add_action( 'wp_ajax_nopriv_rtsb_ajax_login', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$checkout_url = wc_get_checkout_url();
		$has_error    = false;

		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			$response  = [
				'loggedin'     => false,
				'redirectto'   => $checkout_url,
				'message_type' => 'error',
				'message'      => esc_html__( 'Maybe You are not a real user.', 'shopbuilder' ),
			];
			$has_error = true;
		}
		if ( is_user_logged_in() ) {
			$response  = [
				'loggedin'     => true,
				'redirectto'   => $checkout_url,
				'message_type' => 'info',
				'message'      => esc_html__( 'Already Logged In.', 'shopbuilder' ),
			];
			$has_error = true;
		}

		if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$response  = [
				'loggedin'     => false,
				'redirectto'   => $checkout_url,
				'message_type' => 'error',
				'message'      => esc_html__( 'Incorrect User Or password.', 'shopbuilder' ),
			];
			$has_error = true;
		}

		if ( ! $has_error ) {
			$info['user_login']    = sanitize_text_field( $_POST['username'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$info['user_password'] = sanitize_text_field( $_POST['password'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$info['remember']      = ! empty( $_POST['rememberme'] ) ? sanitize_text_field( $_POST['rememberme'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

			$user_signon = wp_signon( $info, false );

			if ( is_wp_error( $user_signon ) ) {
				$response = [
					'loggedin'     => false,
					'redirectto'   => $checkout_url,
					'message_type' => 'error',
					'message'      => esc_html__( 'Login Failed.', 'shopbuilder' ),
				];
			} else {
				$response = [
					'loggedin'     => true,
					'redirectto'   => $checkout_url,
					'message_type' => 'success',
					'message'      => esc_html__( 'Login successful.', 'shopbuilder' ),
				];
			}
		}

		wc_add_notice( $response['message'], $response['message_type'] );
		wp_send_json( $response );

		die();
	}
}

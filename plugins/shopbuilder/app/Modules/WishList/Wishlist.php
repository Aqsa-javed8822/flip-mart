<?php

namespace RadiusTheme\SB\Modules\WishList;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\ExtraSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

final class Wishlist {

	const KEY = 'rtsb_wishlist';

	private $wishlist_db_key = 'rtsb_wishlist_db_version';

	private $wishlist_db_version = '1.0.0';

	/**
	 * @var array
	 */
	public $settings = [];

	/**
	 * SingleTon
	 */
	use SingletonTrait;

	private function __construct() {

		if ( ! ExtraSettings::instance()->get_option( $this->wishlist_db_key, get_option( $this->wishlist_db_key, false ) ) ) {
			add_action( 'wp_loaded', [ $this, 'activate' ] );
			ExtraSettings::instance()->set_option( $this->wishlist_db_key, $this->wishlist_db_version );
		}

		// if ( ! get_option( $this->wishlist_db_key, '' ) ) {
		// add_action( 'wp_loaded', [ $this, 'activate' ] );
		// update_option( $this->wishlist_db_key, $this->wishlist_db_version );
		// }

		new WishlistRouteV1();

		WishlistFrontEnd::instance();

		if ( is_user_logged_in() ) {
			$cookie_name = WishlistFns::instance()->get_cookie_name();

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( isset( $_COOKIE[ $cookie_name ] ) && is_array( json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) ) ) {
				$ids         = WishlistFns::instance()->get_wishlist_ids();
				$product_ids = [];
				foreach ( $ids as $pid ) {
					$product_ids[ $pid ] = $pid;
				}
				WishlistFns::instance()->update_user_wishlist_ids( $product_ids );
				setcookie( self::KEY, '', time() - 3600, '/' );
			}
		}

		if ( is_admin() ) {
			WishlistAdmin::instance();
		}

		do_action( 'rtsb/module/wishlist/loaded' );
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		( new WishlistInstaller() )->run();
	}
}

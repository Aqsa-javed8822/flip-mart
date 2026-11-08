<?php
/**
 * WooCommerce address book plugin support.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\PluginsSupport;

use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * WooCommerce address book plugin support
 */
class WooAddressBook {
	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_filter( 'woocommerce_edit_address_slugs', [ $this, 'change_slug' ], 99 );
		add_shortcode( 'rtsb_woo_address_book', [ $this, 'woo_address_book' ] );
	}

	/**
	 * Address book shortcode.
	 *
	 * @return string
	 */
	public function woo_address_book() {
		ob_start();

		$address = \WC_Address_Book::get_instance();
		$address->wc_address_book_page( '' );

		return ob_get_clean();
	}

	/**
	 * Change endpoint slug.
	 *
	 * @param array $slugs Page slug.
	 *
	 * @return array
	 */
	public function change_slug( $slugs ) {
		$the_slug = [];
		foreach ( $slugs as $key => $value ) {
			$the_slug[ $key ] = $key;
		}

		return $the_slug;
	}
}

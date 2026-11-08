<?php
/**
 * Products Visibility By User Roles plugin support.
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
class AddifyVisibilityByRoles {
	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		if ( ! class_exists( 'Addify_Products_Visibility_Front' ) ) {
			return;
		}

		add_action( 'rtsb/elements/render/product_query', [ $this, 'modify_query' ], 99 );
	}

	/**
	 * Modify product query.
	 *
	 * @param object $product_query Product Query.
	 *
	 * @return void
	 */
	public function modify_query( $product_query ) {
		$custom_visibility = new \Addify_Products_Visibility_Front();
		$custom_visibility->afpvu_custom_pre_get_posts_query( $product_query );
	}
}

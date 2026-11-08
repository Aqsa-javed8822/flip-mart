<?php
/**
 * Main StorefrontSupport class Only Work for storefront theme.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Storefront;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ThemeSupport {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_filter( 'rtsb/builder/wrapper/parent_class', [ $this, 'builder_wrapper_class' ] );
		remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );
	}
	/**
	 * Get class instance.
	 *
	 * @return object Instance.
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Builder wrapper class.
	 *
	 * @param array $classes Classes.
	 * @return array
	 */
	public function builder_wrapper_class( $classes ) {
		$classes[] = 'site-main';
		return $classes;
	}

}

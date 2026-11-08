<?php
/**
 * Main StorefrontSupport class Only Work for storefront theme.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Woodmart;

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
		add_action( 'wp_enqueue_scripts', [ $this, 'woodmart_enqueue_base_styles' ] );
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
	 * @return void
	 */
	public static function woodmart_enqueue_base_styles() {
		if( ! BuilderFns::is_product() ){
			return;
		}
		$version  = woodmart_get_theme_info( 'Version' );
		$minified = woodmart_get_opt( 'minified_css' ) ? '.min' : '';
		$is_rtl   = is_rtl() ? '-rtl' : '';
		$style_url = WOODMART_STYLES . '/style' . $is_rtl . '-elementor' . $minified . '.css';
		wp_enqueue_style( 'woodmart-style', $style_url, array( 'bootstrap' ), $version );

	}


}

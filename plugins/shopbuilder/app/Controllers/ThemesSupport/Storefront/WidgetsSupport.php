<?php

/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Storefront;

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class WidgetsSupport {

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	private $widgets;

	/**
	 * Construct function
	 */
	private function __construct() {}

	/**
	 * Get class instance.
	 *
	 * @return object Instance.
	 */
	public static function instance( $widgets ) {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		self::$instance->widgets = $widgets;
		return self::$instance;
	}

	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with render.
	 *
	 * @return void
	 */
	public function render_rtsb_products_archive() {
		$controllers = $this->widgets->get_settings_for_display();
		remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );

		if ( empty( $controllers['show_pagination'] ) ) {
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
		}

		remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
		remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
		remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
		remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );

		if ( ! empty( $controllers['show_pagination'] ) ) {
			if ( Fns::product_filters_has_ajax( apply_filters( 'rtsb/builder/set/current/page/type', '' ) ) ) {
				remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
			}
		}
	}
}

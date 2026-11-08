<?php

/**
 * Main AstraSupport class Only Work for astra theme.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Astra;

use Astra_Woocommerce;
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
		add_filter( 'astra_dynamic_post_structure_posttypes', [ __CLASS__, 'astra_post_types' ], 15 );
		add_filter( 'rtsb/elementor/archive/products_per_page', [ __CLASS__, 'astra_products_per_page' ] );
		remove_filter( 'woocommerce_get_stock_html', 'astra_woo_product_in_stock' );
		remove_filter( 'woocommerce_product_get_rating_html', [ Astra_Woocommerce::get_instance(), 'rating_markup' ], 10 );
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

	public static function astra_post_types( $post_types ) {
		$position = array_search( 'rtsb_builder', $post_types );
		unset( $post_types[ $position ] );
		return $post_types;
	}

	/**
	 * Astra products per page.
	 *
	 * @return int
	 */
	public static function astra_products_per_page() {
		if ( function_exists( 'astra_get_option' ) ) {
			$per_page = astra_get_option( 'shop-no-of-products' );

			return ! empty( $per_page ) ? absint( $per_page ) : 12;
		}
	}
}

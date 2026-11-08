<?php

/**
 * Radiustheme Variation Gallery support.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\PluginsSupport;

use RadiusTheme\SB\Helpers\ElementorDataMap;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Helpers\BuilderFns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class RtwpvgSupport {
	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'support_assets' ], -1 );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 16 );
		add_filter( 'rtsb/elements/elementor/widget/selectors/rtsb-product-image', [ $this, 'rtsb_product_image_selectors' ] );

	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// wp_dequeue_script( 'rtwpvg' );
		// wp_dequeue_script( 'swiper' );

		// wp_enqueue_script( 'rtwpvg' );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function support_assets() {
		if ( ! BuilderFns::is_builder_preview() ) {
			return;
		}
		$hooks = [
			'rtwpvg_disable_enqueue_scripts',
			'rtwpvg_disable_inline_style',
		];
		foreach ( $hooks as $hook ) {
			add_filter( $hook, '__return_false', 20 );
		}
	}

	/**
	 * Product Image Selector
	 *
	 * @param array $selectors Image Selector.
	 * @return array
	 */
	public function rtsb_product_image_selectors( $selectors ) {
		$selectors['image_width']         = $selectors['image_width'] . ',{{WRAPPER}} .rtsb-product-images .rtwpvg-single-image-container img';
		$selectors['image_border_radius'] = $selectors['image_width'] . ',{{WRAPPER}} .rtsb-product-images .rtwpvg-single-image-container img,{{WRAPPER}} .rtwpvg-wrapper .rtwpvg-slider-wrapper';
		return $selectors;
	}


}

<?php

/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Twentytwentythree;

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
	 * @return void
	 */
	public function render_rtsb_order_notes() {
		remove_action( 'woocommerce_checkout_before_order_review_heading', array( \WC_Twenty_Twenty_Three::class, 'before_order_review' ) );
		remove_action( 'woocommerce_checkout_after_order_review', array( \WC_Twenty_Twenty_Three::class, 'after_order_review' ) );
	}

}

// woocommerce_pagination_args 
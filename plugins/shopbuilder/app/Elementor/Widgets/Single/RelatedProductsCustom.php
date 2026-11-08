<?php
/**
 * ProductsGrid class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\AdditionalQueryCustomLayout;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsGrid class.
 */
class RelatedProductsCustom extends AdditionalQueryCustomLayout {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Related - Custom Layouts', 'shopbuilder' );
		parent::__construct( $data, $args );
	}

	/**
	 * @return string
	 */
	protected function get_the_base() {
		return 'rtsb-related-product-custom';
	}

	/**
	 * Output the related products.
	 *
	 * @param array $args Provided arguments.
	 */
	public function get_the_products( $args = [] ) {
		global $product;

		$_product = $product;
		$product  = Fns::get_product();

		if ( ! $product ) {
			return null;
		}

		$defaults = [
			'posts_per_page' => 2,
			'columns'        => 2,
			'orderby'        => 'rand',
			'order'          => 'desc',
		];

		$args = wp_parse_args( $args, $defaults );

		// Get visible related products then sort them at random.
		$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$product          = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		// Handle orderby.
		wc_set_loop_prop( 'name', 'related' );
		return wc_products_array_orderby( $related_products, $args['orderby'], $args['order'] );
	}
}

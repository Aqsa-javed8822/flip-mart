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
class UpsellsProductsCustom extends AdditionalQueryCustomLayout {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
        $this->pro_tab       = 'layout';
		parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Upsell - Custom Layouts', 'shopbuilder' );
	}

    /**
     * @return string
     */
    protected function get_the_base(){
        return 'rtsb-upsells-product-custom';
    }

    /**
     * Output the related products.
     *
     * @param array $args Provided arguments.
     */
    public function get_the_products( $args = [] ) {
        global $product, $post;

        $_product    = $product;
        $product     = Fns::get_product();
        if ( ! $product ) {
            return;
        }

        // Handle the legacy filter which controlled posts per page etc.
        $defaults = array(
            'posts_per_page' => '-1',
            'orderby'        => 'rand',
            'order'          => 'desc',
            'columns'        => 4,
        );

        $args = wp_parse_args( $args, $defaults );

        // wc_set_loop_prop( 'name', 'up-sells' );
        // wc_set_loop_prop( 'columns', isset( $args['columns'] ) ? $args['columns'] : 4 );

        // Get visible upsells then sort them at random, then limit result set.
        $upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $args['orderby'], $args['order'] );
        $upsells = $args['posts_per_page'] > 0 ? array_slice( $upsells, 0, $args['posts_per_page'] ) : $upsells;

        $product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        wc_set_loop_prop( 'name', 'up-sells' );
        // Handle orderby.
        return $upsells;

    }


}

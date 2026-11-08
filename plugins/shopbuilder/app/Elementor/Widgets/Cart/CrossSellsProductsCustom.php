<?php
/**
 * ProductsGrid class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Cart;

use RadiusTheme\SB\Abstracts\AdditionalQueryCustomLayout;

// Do not allow directly accessing this file.

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsGrid class.
 */
class CrossSellsProductsCustom extends AdditionalQueryCustomLayout {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Cross Sell - Custom Layouts', 'shopbuilder' );
	}

    /**
     * @return string
     */
    protected function get_the_base(){
        return 'rtsb-cross-sell-product-custom';
    }
    /**
     * Output the related products.
     *
     * @param array $args Provided arguments.
     */
    public function get_the_products( $args = [] ) {
        if ( $this->is_builder_mode() ) {
            wc_load_cart();
        }
        // Handle the legacy filter which controlled posts per page etc.
        $defaults = array(
            'posts_per_page' => '-1',
            'orderby'        => 'rand',
            'order'          => 'desc',
            'columns'        => 4,
        );

        $args = wp_parse_args( $args, $defaults );


        // Get visible cross sells then sort them at random.
        $cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );

        wc_set_loop_prop( 'name', 'cross-sells' );
        wc_set_loop_prop( 'columns', $args['columns'] );

        // Handle orderby and limit results.
        $cross_sells = wc_products_array_orderby( $cross_sells, $args['orderby'], $args['order'] );
        $cross_sells = $args['posts_per_page'] > 0 ? array_slice( $cross_sells, 0, $args['posts_per_page'] ) : $cross_sells;

        // Handle orderby.
        return $cross_sells;

    }

}

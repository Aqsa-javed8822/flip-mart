<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Cart;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Traits\ActionBtnTraits;
use RadiusTheme\SB\Abstracts\LoopWithProductSlider;

/**
 * Product Description class
 */
class CrossSellProduct extends LoopWithProductSlider {
	/**
	 * Action Button Traits.
	 */
	use ActionBtnTraits;
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_base = 'rtsb-cross-sells';
		parent::__construct( $data, $args );
		$this->rtsb_name = esc_html__( 'Cross Sell - Default Layout', 'shopbuilder' );
	}

	/**
	 * Apply hooks function.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		parent::apply_hooks();
		$controllers = $this->get_settings_for_display();
		if ( empty( $controllers['show_title'] ) ) {
			add_filter( 'woocommerce_product_cross_sells_products_heading', '__return_false' );
		} elseif ( ! empty( $controllers['loop_title_text'] ) ) {
			add_filter(
				'woocommerce_product_cross_sells_products_heading',
				function( $text ) use ( $controllers ) {
					return $controllers['loop_title_text'];
				}
			);
		}
		if ( $this->is_builder_mode() ) {
			wc_load_cart();
		}
	}
	/**
	 * Widget Selector
	 *
	 * @return array
	 */
	public function template_data_arg() {
		$controllers = $this->get_settings_for_display();
		$limit       = ! empty( $controllers['posts_per_page'] ) ? absint( $controllers['posts_per_page'] ) : 2;
		$columns     = ! empty( $controllers['columns'] ) ? absint( $controllers['columns'] ) : 2;
		$order_by    = ! empty( $controllers['orderby'] ) ? $controllers['orderby'] : 'rand';
		$p_order     = ! empty( $controllers['order'] ) ? $controllers['order'] : 'desc';
		return [
			'template'   => 'elementor/cart/cross-sells',
			'is_builder' => $this->is_builder_mode(),
			'limit'      => $limit,
			'columns'    => $columns,
			'order_by'   => $order_by,
			'p_order'    => $p_order,
		];
	}

}

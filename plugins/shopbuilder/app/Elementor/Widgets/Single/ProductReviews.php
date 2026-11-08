<?php

/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ReviewsSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductReviews extends ElementorWidgetBase {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Reviews', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-reviews';
		parent::__construct( $data, $args );
		$this->pro_tab = 'style';
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ReviewsSettings::widget_fields( $this );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function product_page_script() {
		?>
		<script type="text/javascript">
			jQuery(function($) {
				jQuery('.rtsb-product-comment').find('#rating')
					.hide()
					.before(
						'<p class="stars">\
								<span>\
									<a class="star-1" href="#">1</a>\
									<a class="star-2" href="#">2</a>\
									<a class="star-3" href="#">3</a>\
									<a class="star-4" href="#">4</a>\
									<a class="star-5" href="#">5</a>\
								</span>\
							</p>'
					);
			});
		</script>
		<?php
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product, $post, $comments;
		$_post       = $post;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
        $this->theme_support();
		$data        = [
			'template'    => 'elementor/single-product/reviews',
			'controllers' => $controllers,
		];
		if ( $this->is_builder_mode() ) {
			// Overriding Global.
			$post = get_post( Fns::get_prepared_product_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$this->product_page_script();
		}
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$post    = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}

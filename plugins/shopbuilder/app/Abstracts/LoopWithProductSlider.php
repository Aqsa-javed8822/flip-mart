<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Abstracts;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\ActionBtnTraits;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Elementor\Widgets\Controls\StyleFields;
use RadiusTheme\SB\Elementor\Widgets\Controls\SliderSettings;
use RadiusTheme\SB\Elementor\Widgets\Controls\ProductsSettings;
use RadiusTheme\SB\Elementor\Widgets\Controls\ReviewsStarSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
abstract class LoopWithProductSlider extends ElementorWidgetBase {
	/**
	 * Action Button Traits.
	 */
	use ActionBtnTraits;

	/**
	 * Product Loop Settings.
	 *
	 * @var object $loop_settings
	 */
	public $loop_settings;
	/**
	 * Product Loop Settings.
	 *
	 * @var object $loop_settings
	 */
	public $has_slider = true;
	/**
	 * Product Loop Settings.
	 *
	 * @var object $loop_settings
	 */
	public $has_title = true;
	/**
	 * Product Loop Settings.
	 *
	 * @var object $loop_settings
	 */
	public $has_pagination = false;
	/**
	 * This function Will overwrite by the main function'
	 *
	 * @return array
	 */
	abstract public function template_data_arg();
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$this->loop_settings = new ProductsSettings( $this );
		$slider_control      = $this->has_slider ? SliderSettings::slider_controls() : [];
		$slider_style        = $this->has_slider ? SliderSettings::slider_style( $this ) : [];
		$heading             = $this->has_title ? $this->loop_settings->heading_controls() : [];
		$pagination          = $this->has_pagination ? $this->loop_settings->pagination() : [];

		return $this->general_section() +
			$this->loop_settings->widget_icons() +
			$slider_control +
			$heading +
			$this->loop_settings->image_section() +
			$this->loop_settings->product_title() +
			$this->loop_settings->product_price() +
			$this->loop_settings->flash_sale() +
			ReviewsStarSettings::widget_fields( $this ) +
			$this->loop_settings->add_to_cart() +
			$this->loop_settings->module_button_style() +
			$slider_style +
			$pagination +
			StyleFields::not_found_notice( $this );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function general_section() {
		$fields = $this->loop_settings->general_section();
		if ( 'rtsb-related-product' === $this->rtsb_base ) {
			unset( $fields['orderby'] );
			unset( $fields['order'] );
		}
		if ( 'rtsb-products-archive' === $this->rtsb_base ) {
			unset( $fields['columns'] );
			unset( $fields['orderby'] );
			unset( $fields['order'] );
		}
		return $fields;
	}

	/**
	 * Widget Field.
	 *
	 * @return void
	 */
	public function script_init() {
		$handle = Fns::optimized_handle( 'rtsb-public' );
		wp_dequeue_script( $handle );

		wp_enqueue_script( 'swiper' );
		wp_enqueue_script( $handle );
	}

	/**
	 * Product loop start function
	 *
	 * @param string $html html.
	 *
	 * @return string
	 */
	public function product_loop_start( $html = '' ) {
		$str = explode( 'products', $html );
		if ( is_array( $str ) && count( $str ) > 1 ) {
			$html = $str[0] . ' products swiper-wrapper ' . $str[1];
		}
		$controllers = $this->get_settings_for_display();

		$swiper_data = [
			// Optional parameters.
			'wrapperClass'   => 'products',
			'slideClass'     => 'product',
			'slidesPerView'  => 4,
			'slidesPerGroup' => 3,
			'spaceBetween'   => 15,
			'loop'           => false,
		];
		$classes     = [];
		if ( ! empty( $controllers['columns'] ) ) {
			$swiper_data['slidesPerView']  = absint( $controllers['columns'] );
			$swiper_data['slidesPerGroup'] = absint( $controllers['columns'] );
		}
		if ( ! empty( $controllers['space_between'] ) ) {
			$swiper_data['spaceBetween'] = absint( $controllers['space_between'] );
		}
		if ( ! empty( $controllers['loop'] ) ) {
			$swiper_data['loop'] = boolval( $controllers['loop'] );
		}
		if ( ! empty( $controllers['autoplay'] ) ) {
			$swiper_data['autoplay']['delay']                = absint( $controllers['autoplay_delay'] );
			$swiper_data['autoplay']['pauseOnMouseEnter']    = boolval( $controllers['pauseon_mouseenter'] );
			$swiper_data['autoplay']['disableOnInteraction'] = false;
		}
		if ( ! empty( $controllers['speed'] ) ) {
			$swiper_data['speed'] = absint( $controllers['speed'] );
		}
		if ( ! empty( $controllers['show_arrows'] ) ) {
			$swiper_data['navigation'] = [
				'nextEl' => '.button-right',
				'prevEl' => '.button-left',
			];
			$classes[]                 = 'has-navigation';
		}
		if ( ! empty( $controllers['show_dots'] ) ) {
			$swiper_data['pagination'] = [
				'el'        => '.rtsb-slider-pagination',
				'type'      => 'bullets',
				'clickable' => true,
			];
			$classes[]                 = 'has-dots';
		}

		$data = [
			'template'         => 'global/slider-header',
			'controllers'      => $controllers,
			'swiper_json_data' => wp_json_encode( $swiper_data ),
			'classes'          => $classes,
		];
		$new  = Fns::load_template( $data['template'], $data, true );
		$html = $new . $html;
		return $html;
	}
	/**
	 * Product loop start function
	 *
	 * @param string $html html.
	 *
	 * @return string
	 */
	public function product_loop_end( $html ) {
		$controllers = $this->get_settings_for_display();
		$data        = [
			'template'    => 'global/slider-footer',
			'controllers' => $controllers,
			'left_arrow'  => Fns::icons_manager( $controllers['left_arrow_icon'] ),
			'right_arrow' => Fns::icons_manager( $controllers['right_arrow_icon'] ),
		];
		$new         = Fns::load_template( $data['template'], $data, true );
		$html        = $html . $new;
		return $html;
	}
	/**
	 * Product loop start function
	 *
	 * @param string $classes classes.
	 *
	 * @return string
	 */
	public function post_class( $classes ) {
		$classes[] = 'swiper-slide';
		return $classes;
	}

	/**
	 * Image Wrapper.
	 *
	 * @return void
	 */
	public function image_wrapper() {
		if ( ! RenderHelpers::is_wrapper_needed() ) {
			return;
		}
		?>
		<div class="rtsb-image-wrapper">
		<?php
	}
	/**
	 * Image Wrapper.
	 *
	 * @return void
	 */
	public function div_close() {
		if ( ! RenderHelpers::is_wrapper_needed() ) {
			return;
		}
		?>
		</div>
		<?php
	}
	/**
	 * Image Wrapper.
	 *
	 * @return void
	 */
	public function product_content_wrapper() {
		if ( ! RenderHelpers::is_wrapper_needed() ) {
			return;
		}
		?>
		<div class="rtsb-product-content">
		<?php
	}

	/**
	 * Apply hooks function.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		$controllers = $this->get_settings_for_display();

		if ( empty( $controllers['show_rating'] ) ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			add_filter( 'woocommerce_product_get_rating_html', '__return_false' );
		}
		if ( empty( $controllers['show_flash_sale'] ) ) {
			add_filter( 'woocommerce_sale_flash', '__return_false' );
		}
		if ( ! empty( $controllers['slider_activate'] ) ) {
			add_filter( 'woocommerce_product_loop_start', [ $this, 'product_loop_start' ] );
			add_filter( 'woocommerce_product_loop_end', [ $this, 'product_loop_end' ] );
			add_filter( 'woocommerce_post_class', [ $this, 'post_class' ], 10, 1 );
			$this->script_init();
		}

		if ( class_exists( 'WooProductVariationSwatches' ) ) {
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 8 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 12 );
		}

		// Remove Action Button.
		add_filter( 'rtsb/module/wishlist/show_button', '__return_false' );
		add_filter( 'rtsb/module/quick_view/show_button', '__return_false' );
		add_filter( 'rtsb/module/compare/show_button', '__return_false' );

		if ( empty( $controllers['show_quick_checkout'] ) ) {
			add_filter( 'rtsb/module/quick_checkout/show_button', '__return_false' );
		}
		if ( empty( $controllers['show_flash_sale_countdown'] ) ) {
			add_filter( 'rtsb/module/flash_sale_countdown/show_counter', '__return_false' );
		}

		// Default Link remove.
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Image Wrapper open.
		add_action( 'woocommerce_before_shop_loop_item', [ $this, 'image_wrapper' ], -1 );

		// Image link.
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
		// Image link end.

		// Title Link.
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
		// Title Link end.

		// Image Wrapper close.
		add_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'div_close' ], 12 );
		// Content Wrapper Open.
		add_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'product_content_wrapper' ], 13 );
		// Content Wrapper Close.
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'div_close' ], 99 );

		if ( $this->is_edit_mode() ) {
			if ( class_exists( Rtwpvs\Controllers\Hooks::class ) ) {
				remove_filter( 'woocommerce_ajax_variationX_threshold', [ Rtwpvs\Controllers\Hooks::class, 'ajax_variation_threshold' ], 99 );
			}
			add_filter(
				'woocommerce_ajax_variation_threshold',
				function () {
					return 1;
				},
				99
			);

		}
	}
	/**
	 * Apply hooks function.
	 *
	 * @return void
	 */
	public function apply_hooks_set_default() {

		// Default Link re active.
		add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Image Wrapper open.
		remove_action( 'woocommerce_before_shop_loop_item', [ $this, 'image_wrapper' ], -1 );

		// Image link.
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
		// Image link end.

		// Title Link.
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
		// Title Link End.

		// Image Wrapper close.
		remove_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'div_close' ], 12 );

		// Content Wrapper Open.
		remove_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'product_content_wrapper' ], 13 );
		// Content Wrapper Close.
		remove_action( 'woocommerce_after_shop_loop_item', [ $this, 'div_close' ], 99 );

		// Remove Action Button.
		remove_filter( 'rtsb/module/wishlist/show_button', '__return_false' );
		remove_filter( 'rtsb/module/quick_view/show_button', '__return_false' );
		remove_filter( 'rtsb/module/compare/show_button', '__return_false' );
	}
	/**
	 * Related Product Args function'
	 *
	 * @param [type] $args argument.
	 * @return array
	 */
	public function products_args( $args ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['posts_per_page'] ) ) {
			$args['posts_per_page'] = intval( $controllers['posts_per_page'] );
		}
		if ( ! empty( $controllers['columns'] ) ) {
			$args['columns'] = absint( $controllers['columns'] );
		}
		if ( ! empty( $controllers['orderby'] ) ) {
			$args['orderby'] = $controllers['orderby'];
		}
		if ( ! empty( $controllers['order'] ) ) {
			$args['order'] = $controllers['order'];
		}
		return $args;
	}

	/**
	 * Elementor Edit mode script
	 *
	 * @return void
	 */
	public function editor_script() {
		if ( ! $this->is_edit_mode() ) {
			return;
		}
		?>
		<script>
			<?php if ( rtsb()->has_pro() ) { ?>
				if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
					setTimeout( function (){
						window.rtsbCountdownApply();
					}, 1000 );
				} else {
					if (typeof elementorFrontend !== 'undefined') {
						window.waitForRTSB((RTSB) => {
							RTSB.modules.get('countdownPro')?.refresh();
						});
					}
				}
			<?php } ?>
		</script>
		<?php
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product, $post;
		$data = $this->template_data_arg();
		if ( empty( $data['template'] ) ) {
			return;
		}
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();

		$controllers['cart_icon_html'] = Fns::icons_manager( $controllers['cart_icon'] );

		$this->apply_hooks();
		$this->theme_support();

		$this->action_button_icon_modify();

		$data['controllers'] = $controllers;

		Fns::load_template( $data['template'], $data );

		$this->apply_hooks_set_default();
		$this->action_button_icon_set_default();
		$this->theme_support( 'render_reset' );

		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( ! empty( $controllers['slider_activate'] ) ) {
			remove_filter( 'woocommerce_product_loop_start', [ $this, 'product_loop_start' ] );
			remove_filter( 'woocommerce_product_loop_end', [ $this, 'product_loop_end' ] );
			remove_filter( 'woocommerce_post_class', [ $this, 'post_class' ], 10, 1 );
			$this->editor_slider_script();
		}

		$this->editor_script();
		$this->editor_cart_icon_script();
	}
}

<?php
/**
 * Compare Module Class
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\Compare;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Product;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Compare Module Class
 */
class CompareFrontEnd {
	use SingletonTrait;

	/**
	 * Asset Handle
	 *
	 * @var string
	 */
	private $handle = 'rtsb-compare';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 99 );
		add_action( 'rtsb/modules/compare/frontend/display', [ $this, 'button_hook' ] );
		add_action( 'rtsb/modules/compare/print_button', [ $this, 'print_button' ] );

		// ShortCode.
		add_shortcode( 'rtsb_compare_list', [ $this, 'list_shortcode' ] );
		add_shortcode( 'rtsb_compare_button', [ $this, 'button_shortcode' ] );
		add_shortcode( 'rtsb_compare_counter', [ $this, 'counter_shortcode' ] );

		$this->attach_button();
	}

	/**
	 * Enqueue assets.
	 *
	 * @return void
	 */
	public function enqueue() {
		// Enqueue assets.
		$this->handle     = Fns::enqueue_module_assets( $this->handle, 'compare' );
		$ajax_button_text = Fns::get_option( 'modules', 'compare', 'button_text', esc_html__( 'Compare', 'shopbuilder' ) );
		$params           = apply_filters(
			'rtsb/module/compare/js_params',
			[
				'product_id' => get_the_ID(),
				'resturl'    => get_rest_url(),
				'isLoggedIn' => is_user_logged_in(),
				'pageUrl'    => CompareFns::instance()->get_page_url(),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),
				'notice'     => [
					'added'               => Fns::get_option( 'modules', 'compare', 'notice_added_text', esc_html__( 'Product added!', 'shopbuilder' ) ),
					'removed'             => Fns::get_option( 'modules', 'compare', 'notice_removed_text', esc_html__( 'Product removed!', 'shopbuilder' ) ),
					'browse'              => Fns::get_option( 'modules', 'compare', 'browse_list_text', esc_html__( 'Browse compare!', 'shopbuilder' ) ),
					'error'               => esc_html__( 'Some thing went wrong!!', 'shopbuilder' ),
					'ajax_remove_compare' => esc_html__( 'Remove from list', 'shopbuilder' ),
					'ajax_button_text'    => $ajax_button_text,
				],
			]
		);

		wp_localize_script( $this->handle, 'rtsbCompareParams', $params );
	}

	/**
	 * Add the "Add to Wishlist" button. Needed to use in wp_head hook.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function attach_button() {
		$positions = apply_filters(
			'rtsb/module/compare/product_btn_positions',
			[
				'before_cart_btn'   => [
					'hook'     => 'woocommerce_before_add_to_cart_button',
					'priority' => 20,
				],
				'after_add_to_cart' => [
					'hook'     => 'woocommerce_single_product_summary',
					'priority' => 31,
				],
				'after_thumbnail'   => [
					'hook'     => 'woocommerce_product_thumbnails',
					'priority' => 21,
				],
				'after_summary'     => [
					'hook'     => 'woocommerce_after_single_product_summary',
					'priority' => 11,
				],

				'custom'            => [
					'hook'     => Fns::get_option( 'modules', 'compare', 'product_custom_hook_name', '' ),
					'priority' => Fns::get_option( 'modules', 'compare', 'product_custom_hook_priority', 10 ),
				],
			]
		);

		// Add the link "Add to wishlist".

		$product_btn_enable   = Fns::get_option( 'modules', 'compare', 'show_btn_product_page', true, 'checkbox' );
		$product_btn_position = Fns::get_option( 'modules', 'compare', 'product_btn_position', 'after_add_to_cart' );

		if ( $product_btn_enable && 'shortcode' !== $product_btn_position && isset( $positions[ $product_btn_position ]['hook'] ) ) {
			add_action(
				$positions[ $product_btn_position ]['hook'],
				[
					$this,
					'button_hook',
				],
				isset( $positions[ $product_btn_position ]['priority'] ) ? absint( $positions[ $product_btn_position ]['priority'] ) : ''
			);
		}

		// check if Add to wishlist button is enabled for loop.
		$loop_btn_enable = Fns::get_option( 'modules', 'compare', 'show_btn_on_loop', true, 'checkbox' );

		if ( ! $loop_btn_enable ) {
			return;
		}

		$positions = apply_filters(
			'rtsb/module/compare/loop_btn_position',
			[

				'before_add_to_cart' => [
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => 7,
				],
				'after_add_to_cart'  => [
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => 15,
				],
				'custom'             => [
					'hook'     => Fns::get_option( 'modules', 'compare', 'loop_custom_hook_name', '' ),
					'priority' => Fns::get_option( 'modules', 'compare', 'loop_custom_hook_priority', 10 ),
				],
			]
		);

		// Add the link "Add to wishlist" in the loop.

		$loop_btn_position = Fns::get_option( 'modules', 'compare', 'loop_btn_position', 'after_add_to_cart' );

		if ( 'shortcode' !== $loop_btn_position && isset( $positions[ $loop_btn_position ]['hook'] ) ) {
			add_action(
				$positions[ $loop_btn_position ]['hook'],
				[
					$this,
					'button_hook',
				],
				isset( $positions[ $loop_btn_position ]['priority'] ) ? $positions[ $loop_btn_position ]['priority'] : ''
			);
		}

		// Add the link "Add to wishlist" for Gutenberg blocks.
		add_filter( 'woocommerce_blocks_product_grid_item_html', [ $this, 'add_button_for_block' ], 10, 3 );
	}


	/**
	 * Add ATW button to Products block item
	 *
	 * @param string     $item_html HTML of the single block item.
	 * @param array      $data Data used to render the item.
	 * @param WC_Product $product Current product.
	 *
	 * @return string Filtered HTML.
	 */
	public function add_button_for_block( $item_html, $data, $product ) {
		// Add the link "Add to wishlist" in the loop.
		$shop_btn_position = Fns::get_option( 'modules', 'compare', 'loop_btn_position', 'after_add_to_cart' );

		ob_start();
		$this->print_button( $product->get_id() );
		$button = ob_get_clean();
		$parts  = [];

		preg_match( '/(<li class=".*?">)[\S|\s]*?(<a .*?>[\S|\s]*?<\/a>)([\S|\s]*?)(<\/li>)/', $item_html, $parts );

		if ( ! $parts || count( $parts ) < 5 ) {
			return $item_html;
		}

		// removes first match (entire match).
		array_shift( $parts );

		// removes empty parts.
		$parts = array_filter( $parts );

		// searches for index to cut parts array.
		switch ( $shop_btn_position ) {
			case 'before_add_to_cart':
				$index = 2;
				break;
			case 'after_add_to_cart':
				$index = 3;
				break;
			default:
				$index = 0;
				break;
		}

		// if index is found, stitch button in correct position.
		if ( $index ) {
			$first_set  = array_slice( $parts, 0, $index );
			$second_set = array_slice( $parts, $index );

			$parts = array_merge(
				$first_set,
				(array) $button,
				$second_set
			);

			// replace li classes.
			$parts[0] = preg_replace( '/class="(.*)"/', 'class="$1 add-to-wishlist-' . $shop_btn_position . '"', $parts[0] );
		}

		// join all parts together and return item.
		return implode( '', $parts );
	}


	/**
	 * Print "Add to compare" button
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return void
	 */
	public function print_button( $product_id = 0 ) {
		global $product;

		if ( ! $product instanceof WC_Product && $product_id ) {
			$product = wc_get_product( $product_id );
		}

		// product parent.
		$product_parent = $product->get_parent_id();

		// button class.
		$classes = [];
		// check if product is already in compare list.
		$icon_html = '<i class="rtsb-icon rtsb-icon-arrows-cw"></i>';
		$exists    = CompareFns::instance()->is_exists_in_list( $product->get_id() );
		if ( $exists ) {
			$classes[]   = 'rtsb-compare-remove';
			$button_text = esc_html__( 'Remove from list', 'shopbuilder' );

			$button_text = apply_filters( 'rtsb/modules/compare/remove_from_list_button_text', $button_text );
		} else {
			$classes[] = 'rtsb-compare-add';
			// labels & icons settings.

			$button_text = Fns::get_option( 'modules', 'compare', 'button_text', esc_html__( 'Add to Compare', 'shopbuilder' ) );

			// button text.
			$button_text = apply_filters( 'rtsb/module/compare/add_to_list_button_txt', $button_text );
		}

		// get product type.
		$product_type = $product->get_type();
		$show_button_text = ! empty( Fns::get_option( 'modules', 'compare', 'button_text', '' ) );

		if ( $show_button_text ) {
			$classes[] = 'has-text';
		}

		$params = [
			'exists'            => $exists,
			'classes'           => $classes,
			'product_id'        => $product->get_id(),
			'parent_product_id' => $product_parent ?: $product->get_id(),
			'product_type'      => $product_type,
			'button_text'       => $button_text,
			'show_button_text'  => $show_button_text,
			'left_text'         => apply_filters( 'rtsb/module/compare/button_left_text', '' ),
			'right_text'        => apply_filters( 'rtsb/module/compare/button_right_text', '' ),
		];
		$atts         = apply_filters( 'rtsb/module/compare/button_params', $params );

		$atts['icon_html'] = apply_filters( 'rtsb/module/compare/icon_html', $icon_html, $atts );

		Fns::load_template( 'compare/button', $atts );
	}


	/**
	 * Print "Add to compare" button
	 *
	 * @return void
	 */
	public function button_hook() {

		if ( ! apply_filters( 'rtsb/module/compare/show_button', true ) ) {
			return;
		}
		global $product;
		if ( $product instanceof WC_Product ) {
			do_action( 'rtsb/modules/compare/print_button', $product->get_id() );
		}
	}


	/**
	 * List Shortcode callable function
	 *
	 * @param array  $atts Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string [HTML]
	 */
	public function list_shortcode( $atts, $content = '' ) {
		$this->handle = Fns::optimized_handle( $this->handle );

		wp_enqueue_style( $this->handle );
		wp_enqueue_script( $this->handle );

		/* Fetch From option data */

		$empty_text = Fns::get_option( 'modules', 'compare', 'empty_table_text', esc_html__( 'No product found at your list.', 'shopbuilder' ) );

		/* Product and Field */
		$products  = CompareFns::instance()->get_products_data();
		$field_ids = array_merge( [ 'primary' ], CompareFns::instance()->get_list_field_ids() );

		$return_to_shop_text = Fns::get_option( 'modules', 'compare', 'return_to_shop_text', esc_html__( 'Return to shop', 'shopbuilder' ) );

		$default_atts = [
			'compare'             => self::instance(),
			'products'            => $products,
			'field_ids'           => $field_ids,
			'return_to_shop_text' => $return_to_shop_text,
			'empty_text'          => ! empty( $empty_text ) ? $empty_text : '',
		];

		$atts = shortcode_atts( $default_atts, $atts, $content );
		$args = apply_filters( 'rtsb/module/compare/list_args', $atts );

		return Fns::load_template( 'compare/list', $args, true );
	}


	/**
	 * Compare button Shortcode callable function
	 *
	 * @return string [HTML]
	 */
	public function button_shortcode() {
		$this->handle = Fns::optimized_handle( $this->handle );

		wp_enqueue_style( $this->handle );
		wp_enqueue_script( $this->handle );

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return '';
		}
		ob_start();

		do_action( 'rtsb/modules/compare/print_button', $product->get_id() );

		return ob_get_clean();
	}

	/**
	 * Compare counter Shortcode callable function
	 *
	 * @param array  $atts Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string [HTML]
	 */
	public function counter_shortcode( $atts, $content = '' ) {
		wp_enqueue_style( Fns::optimized_handle( $this->handle ) );

		$product_ids = CompareFns::instance()->get_compared_product_ids();

		$button_text = esc_html__( 'Compare', 'shopbuilder' );
		$page_url    = CompareFns::instance()->get_page_url();

		$default_atts = [
			'product_ids' => $product_ids,
			'item_count'  => count( $product_ids ),
			'page_url'    => $page_url,
			'button_text' => $button_text,
			'text'        => '',
		];

		$atts       = shortcode_atts( $default_atts, $atts, $content );
		$count_attr = apply_filters( 'rtsb/module/compare/counter_args', $atts );

		return Fns::load_template( 'compare/counter', $count_attr, true );
	}


	/**
	 * Add specific body class when the Wishlist page is opened
	 *
	 * @param array $classes Existing boy classes.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {

		$compare_page_id = Fns::get_option( 'modules', 'compare', 'page', 0, 'number' );

		if ( ! empty( $compare_page_id ) && is_page( $compare_page_id ) ) {
			$classes[] = 'rtsb-compare-page';
			$classes[] = 'woocommerce';
			$classes[] = 'woocommerce-page';
		}

		return $classes;
	}
}

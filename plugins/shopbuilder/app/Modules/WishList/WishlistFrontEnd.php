<?php
/**
 * Wishlist Module Class
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\WishList;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Product;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Wishlist Module Class
 */
class WishlistFrontEnd {

	/**
	 * Singleton Instance
	 */
	use SingletonTrait;

	/**
	 * Asset Handle
	 *
	 * @var string
	 */
	private $handle = 'rtsb-wishlist';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
		add_filter( 'rtsb/module/wishlist/show_button', [ $this, 'wishlist_button_show_hide' ], 12 );
		add_action( 'rtsb/modules/wishlist/frontend/display', [ $this, 'button_hook' ] );

		add_action( 'rtsb/modules/wishlist/print_button', [ $this, 'print_button' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 99 ); // 99 Is Working to load after main script.

		// ShortCode.
		add_shortcode( 'rtsb_wishlist', [ $this, 'list_shortcode' ] );
		add_shortcode( 'rtsb_wishlist_button', [ $this, 'button_shortcode' ] );
		add_shortcode( 'rtsb_wishlist_counter', [ $this, 'counter_shortcode' ] );

		$this->attach_button();
	}

	/**
	 * Hide Wishlist button.
	 *
	 * @param boolean $is_enable Wishlist Permission.
	 * @return boolean
	 */
	public function wishlist_button_show_hide( $is_enable ) {
		$enable_login_limit     = Fns::get_option( 'modules', 'wishlist', 'enable_login_limit', false, 'checkbox' );
		$hide_for_non_logged_in = Fns::get_option( 'modules', 'wishlist', 'hide_wishlist_non_logged_in', false, 'checkbox' );
		if ( $enable_login_limit && $hide_for_non_logged_in && ! is_user_logged_in() ) {
			$is_enable = false;
		}
		return $is_enable;
	}

	/**
	 * Script.
	 *
	 * @return void
	 */
	public function enqueue() {
		// Enqueue assets.
		$this->handle = Fns::enqueue_module_assets( $this->handle, 'wishlist' );

		if ( is_user_logged_in() && is_account_page() ) {
			wp_enqueue_script( 'wc-add-to-cart' );
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		$params = apply_filters(
			'rtsb/module/wishlist/js_params',
			[
				'product_id' => get_the_ID(),
				'resturl'    => get_rest_url(),
				'isLoggedIn' => is_user_logged_in(),
				'pageUrl'    => WishlistFns::instance()->get_page_url(),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),
				'notice'     => [
					'add_to_wishlist' => esc_html__( 'Add to wishlist', 'shopbuilder' ),
					'remove_wishlist' => esc_html__( 'Remove from list', 'shopbuilder' ),
					'button_text'     => Fns::get_option( 'modules', 'wishlist', 'button_text', esc_html__( 'Add to wishlist', 'shopbuilder' ) ),
					'added'           => Fns::get_option( 'modules', 'wishlist', 'notice_added_text', esc_html__( 'Product added!', 'shopbuilder' ) ),
					'removed'         => Fns::get_option( 'modules', 'wishlist', 'notice_removed_text', esc_html__( 'Product removed!', 'shopbuilder' ) ),
					'exist'           => Fns::get_option( 'modules', 'wishlist', 'notice_removed_text', esc_html__( 'The product is already in your wishlist!', 'shopbuilder' ) ),
					'browse'          => Fns::get_option( 'modules', 'wishlist', 'browse_list_text', esc_html__( 'Browse wishlist', 'shopbuilder' ) ),
					'error'           => esc_html__( 'Security error!', 'shopbuilder' ),
					'position'        => Fns::get_option( 'general', 'notification', 'notification_position', 'center-center' ),
				],
			]
		);

		wp_localize_script( $this->handle, 'rtsbWishlistParams', $params );
	}

	/**
	 * Add the "Add to Wishlist" button. Needed to use in wp_head hook.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function attach_button() {
		$positions = apply_filters(
			'rtsb/module/wishlist/product_btn_positions',
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
					'hook'     => Fns::get_option( 'modules', 'wishlist', 'product_custom_hook_name', '' ),
					'priority' => Fns::get_option( 'modules', 'wishlist', 'product_custom_hook_priority', 10 ),
				],
			]
		);

		// Add the link "Add to wishlist".
		$product_btn_enable   = Fns::get_option( 'modules', 'wishlist', 'show_btn_product_page', true, 'checkbox' );
		$product_btn_position = Fns::get_option( 'modules', 'wishlist', 'product_btn_position', 'after_add_to_cart' );
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
		$loop_btn_enable = Fns::get_option( 'modules', 'wishlist', 'show_btn_on_loop', false, 'checkbox' );
		if ( ! $loop_btn_enable ) {
			return;
		}

		$positions = apply_filters(
			'rtsb/module/wishlist/loop_btn_position',
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
					'hook'     => Fns::get_option( 'modules', 'wishlist', 'loop_custom_hook_name', '' ),
					'priority' => Fns::get_option( 'modules', 'wishlist', 'loop_custom_hook_priority', 10 ),
				],
			]
		);

		// Add the link "Add to wishlist" in the loop.
		$loop_btn_position = Fns::get_option( 'modules', 'wishlist', 'loop_btn_position', 'after_add_to_cart' );

		if ( 'shortcode' !== $loop_btn_position && isset( $positions[ $loop_btn_position ]['hook'] ) ) {
			add_action( $positions[ $loop_btn_position ]['hook'], [ $this, 'button_hook' ], isset( $positions[ $loop_btn_position ]['priority'] ) ? $positions[ $loop_btn_position ]['priority'] : '' );
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
		$loop_btn_position = Fns::get_option( 'modules', 'wishlist', 'shop_btn_position', 'after_add_to_cart' );
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
		switch ( $loop_btn_position ) {

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
			$parts[0] = preg_replace( '/class="(.*)"/', 'class="$1 add-to-wishlist-' . $loop_btn_position . '"', $parts[0] );
		}

		// join all parts together and return item.
		return implode( '', $parts );
	}

	/**
	 * Print "Add to compare" button
	 *
	 * @return void
	 */
	public function button_hook() {
		if ( ! apply_filters( 'rtsb/module/wishlist/show_button', true ) ) {
			return;
		}
		global $product;
		if ( ! $product instanceof WC_Product ) {
			return;
		}
		do_action( 'rtsb/modules/wishlist/print_button', $product->get_id() );
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
		// check if product is already in wishlist.
		$exists = WishlistFns::instance()->is_exists_in_wishlist( $product->get_id() );
		if ( $exists ) {
			$classes[]   = 'rtsb-wishlist-remove';
			$button_text = esc_html__( 'Remove from list', 'shopbuilder' );
			$button_text = apply_filters( 'rtsb/modules/wishlist/remove_from_list_button_text', $button_text );
			$icon_html   = '<i class="rtsb-icon rtsb-icon-heart"></i>';
		} else {
			$classes[] = 'rtsb-wishlist-add';
			$icon_html = '<i class="rtsb-icon rtsb-icon-heart-empty"></i>';
			// labels & icons settings.
			$button_text = Fns::get_option( 'modules', 'wishlist', 'button_text', esc_html__( 'Add to wishlist', 'shopbuilder' ) );
			// button text.
			$button_text = apply_filters( 'rtsb/module/wishlist/add_to_list_button_txt', $button_text );
		}

		// get product type.
		$product_type = $product->get_type();
		$show_button_text = ! empty( Fns::get_option( 'modules', 'wishlist', 'button_text', '' ) );

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
			'left_text'         => apply_filters( 'rtsb/module/wishlist/button_left_text', '' ),
			'right_text'        => apply_filters( 'rtsb/module/wishlist/button_right_text', '' ),
		];

		// let third party developer filter options.
		$atts = apply_filters( 'rtsb/module/wishlist/button_params', $params );

		// set fragment options.
		$atts['icon_html'] = apply_filters( 'rtsb/module/wishlist/icon_html', $icon_html, $atts );

		Fns::load_template( 'wishlist/button', $atts );
	}


	/**
	 * Add specific body class when the Wishlist page is opened
	 *
	 * @param array $classes Existing boy classes.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$wishlist_page_id = Fns::get_option( 'modules', 'wishlist', 'page', '', 'number' );
		if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) ) {
			$classes[] = 'woocommerce-wishlist rtsb-wishlist-page';
			$classes[] = 'woocommerce';
			$classes[] = 'woocommerce-page';
		}

		return $classes;
	}


	/**
	 * Table List Shortcode callable function
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
		$empty_text = Fns::get_option( 'modules', 'wishlist', 'empty_table_text', esc_html__( 'No product found at your wishlist.', 'shopbuilder' ) );
		/* Product and Field */
		$products  = WishlistFns::instance()->get_products_data();
		$field_ids = WishlistFns::instance()->get_field_ids();

		$custom_headings = [];

		$default_atts = [
			'products'   => $products,
			'field_ids'  => $field_ids,
			'empty_text' => ! empty( $empty_text ) ? $empty_text : '',
		];

		$atts = shortcode_atts( $default_atts, $atts, $content );
		$args = apply_filters( 'rtsb/module/wishlist/list_args', $atts );

		return Fns::load_template( 'wishlist/list', $args, true );
	}

	/**
	 * Wishlist button Shortcode callable function
	 *
	 * @return string [HTML]
	 */
	public function button_shortcode() {
		$this->handle = Fns::optimized_handle( $this->handle );

		wp_enqueue_style( $this->handle );
		wp_enqueue_script( $this->handle );

		ob_start();
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return '';
		}

		do_action( 'rtsb/modules/wishlist/print_button', $product->get_id() );

		return ob_get_clean();
	}

	/**
	 * Counter Shortcode callable function
	 *
	 * @param array  $atts Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string [HTML]
	 */
	public function counter_shortcode( $atts, $content = '' ) {
		wp_enqueue_style( Fns::optimized_handle( $this->handle ) );

		$enable_login_limit = Fns::get_option( 'modules', 'wishlist', 'enable_login_limit', false, 'checkbox' );
		$products           = WishlistFns::instance()->get_wishlist_ids();
		if ( ! is_user_logged_in() && $enable_login_limit ) {
			$button_text = esc_html__( 'Please login', 'shopbuilder' );
		} else {
			$button_text = esc_html__( 'Wishlist', 'shopbuilder' );
		}
		$icon_html    = '<i class="rtsb-icon rtsb-icon-heart"></i>';
		$page_url     = WishlistFns::instance()->get_page_url();
		$default_atts = [
			'products'           => $products,
			'item_count'         => count( $products ),
			'page_url'           => $page_url,
			'button_text'        => $button_text,
			'enable_login_limit' => $enable_login_limit,
			'text'               => '',
			'show_icon'          => '', // 'yes' for show.
		];

		$atts       = shortcode_atts( $default_atts, $atts, $content );
		$count_attr = apply_filters( 'rtsb/module/wishlist/counter_args', $atts );
		if ( 'yes' === ( $count_attr['show_icon'] ?? '' ) ) {
			$count_attr['icon_html'] = apply_filters( 'rtsb/module/wishlist/counter/icon_html', $icon_html, $count_attr );
		} else {
			$count_attr['icon_html'] = '';
		}

		return Fns::load_template( 'wishlist/counter', $count_attr, true );
	}
}

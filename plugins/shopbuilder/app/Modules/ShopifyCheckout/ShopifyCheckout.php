<?php
/**
 * Sticky add-to-cart Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\ShopifyCheckout;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class ShopifyCheckout {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @var array
	 */
	private $cache = [];
	/**
	 * @var array|mixed
	 */
	private array $options;

	/**
	 * Module Class Constructor.
	 */
	private function __construct() {
		// If the cached result doesn't exist, fetch it from the database.
		global $shopify_checkout_options; // Define global variable.
		$this->options            = Fns::get_options( 'modules', 'shopify_checkout' ); // Assign options to the global variable.
		$shopify_checkout_options = $this->options;
		add_filter( 'template_include', [ $this, 'custom_checkout_template' ], 99 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ], 99 );
		add_action( 'wp_enqueue_scripts', [ $this, 'disable_elementor_assets' ], 100 );
		add_action( 'wp_print_scripts', [ $this, 'disable_elementor_assets' ], 100 );
		do_action( 'rtsb/loaded/shopify/checkout' );
	}
	/**
	 * @param string $template path.
	 * @return mixed|string
	 */
	public function custom_checkout_template( $template ) {
		if ( defined( 'RTSBPRO_VERSION' ) && version_compare( RTSBPRO_VERSION, '1.8.0', '<' ) ) {
			return $template;
		}
		if ( ! $this->is_shopify_checkout_page() ) {
			return $template;
		}
		$shopify_template = 'shopify-checkout/checkout';
		$custom_template  = Fns::locate_template( $shopify_template );
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
		return $template;
	}

	/**
	 * Determine if the current request will render the Shopify-style checkout template.
	 *
	 * @return bool True when on the WooCommerce checkout page (not order-received, not pay page).
	 */
	public function is_shopify_checkout_page() {
		if ( ! function_exists( 'is_checkout' ) ) {
			return false;
		}
		return is_checkout() && ! is_order_received_page() && ! is_checkout_pay_page();
	}

	/**
	 * Dequeue/deregister Elementor frontend scripts on the Shopify checkout page.
	 *
	 * The Shopify-style checkout overrides the page template, so Elementor does
	 * not localize `elementorFrontendConfig`. Any Elementor frontend script that
	 * still loads (via theme header/footer or other plugins) throws
	 * "elementorFrontendConfig is not defined". Strip those handles here so the
	 * Shopify checkout stays fully independent of Elementor JS.
	 *
	 * Filterable via `rtsb/shopify_checkout/disable_elementor` (bool) and
	 * `rtsb/shopify_checkout/dequeue_handles` (array of script handles).
	 *
	 * @return void
	 */
	public function disable_elementor_assets() {
		if ( ! $this->is_shopify_checkout_page() ) {
			return;
		}
		if ( ! apply_filters( 'rtsb/shopify_checkout/disable_elementor', true ) ) {
			return;
		}
		$handles = apply_filters(
			'rtsb/shopify_checkout/dequeue_handles',
			[
				'elementor-frontend',
				'elementor-frontend-modules',
				'elementor-pro-frontend',
				'elementor-sticky',
				'elementor-waypoints',
			]
		);
		foreach ( $handles as $handle ) {
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}
	}

	/**
	 * @return void
	 */
	public function enqueue_public_scripts() {
		// Enqueue assets.
		Fns::enqueue_module_assets(
			Fns::optimized_handle( 'rtsb-shopify-checkout' ),
			'shopify-checkout',
			[
				'type' => 'css',
			]
		);

		// The Shopify-style checkout template always renders `.rtsb-sticky-element`,
		// which the frontend bundle initialises via `theiaStickySidebar()`. Ensure
		// the vendor handle is enqueued whenever it is registered (Pro is active),
		// regardless of multi-step mode, so `$().theiaStickySidebar is not a function`
		// can never occur on the Shopify checkout page.
		if ( $this->is_shopify_checkout_page() && wp_script_is( 'rtsb-sticky-sidebar', 'registered' ) ) {
			wp_enqueue_script( 'rtsb-sticky-sidebar' );
		}

		// Flag for frontend JS modules to skip Elementor-dependent code paths.
		$is_shopify_checkout = $this->is_shopify_checkout_page();
		$disable_elementor   = $is_shopify_checkout && apply_filters( 'rtsb/shopify_checkout/disable_elementor', true );
		wp_localize_script(
			Fns::optimized_handle( 'rtsb-public' ),
			'rtsbShopifyCheckoutMeta',
			[
				'isShopifyCheckout' => $is_shopify_checkout,
				'disableElementor'  => $disable_elementor,
			]
		);

		$cache_key = 'shopify_style_cache';

		// Selector variables.
		$step_menu_selector           = '.shopify-multistep-menu ul li a';
		$step_menu_active_selector    = '.shopify-multistep-menu ul li.active a';
		$footer_menu_selector         = '.rtsb-checkout-page-full-width .shopify-footer-menu a';
		$next_button_selector         = '.rtsb-checkout-page-form .checkout-step-btn a.next';
		$prev_button_selector         = '.rtsb-checkout-page-form .checkout-step-btn a.prev';
		$coupon_input_button_selector = '.rtsb-checkout-page-full-width .rtsb-shopify-order-summery .coupon-wrapper input, .rtsb-checkout-page-full-width .rtsb-shopify-order-summery .coupon-wrapper button';
		$coupon_button_selector       = '.rtsb-checkout-page-full-width .rtsb-shopify-order-summery .coupon-wrapper button';
		$place_order_button_selector  = '.rtsb-checkout-page-form #place_order';
		$login_button_selector        = '.rtsb-checkout-page-form .woocommerce-form-login__submit';
		$footer_text                  = '.rtsb-checkout-page-footer';
		$cart_icon                    = '.rtsb-checkout-page-container .rtsb-checkout-page-header .header-right-side a';

		// phpcs:disable
		// Disabled phpcs for one-liner display.
		$css_properties = [
            'header_cart_icon_color'                => [ 'selector' => $cart_icon, 'property' => 'color' ],
            'header_cart_icon_hover_color'          => [ 'selector' => "$cart_icon:hover", 'property' => 'color' ],
            'header_cart_icon_font_size'            => [ 'selector' => $cart_icon, 'property' => 'font-size', 'unit' => 'px' ],
            'shopify_cart_icon_height'              => [ 'selector' => "$cart_icon :is(svg, img)", 'property' => 'height', 'unit' => 'px' ],
            'cart_button_height'                    => [ 'selector' => $cart_icon, 'property' => 'height', 'unit' => 'px' ],
            'cart_button_width'                     => [ 'selector' => $cart_icon, 'property' => 'width', 'unit' => 'px' ],
            'cart_button_padding'                   => [ 'selector' => $cart_icon, 'property' => 'padding', 'unit' => 'px' ],
            'cart_button_border_style'              => [ 'selector' => $cart_icon, 'property' => 'border-style' ],
            'cart_button_border_width'              => [ 'selector' => $cart_icon, 'property' => 'border-width', 'unit' => 'px' ],
            'cart_button_border_color'              => [ 'selector' => $cart_icon, 'property' => 'border-color' ],
            'cart_button_border_radius'             => [ 'selector' => $cart_icon, 'property' => 'border-radius', 'unit' => 'px' ],
            'cart_button_bg_color'                  => [ 'selector' => $cart_icon, 'property' => 'background-color' ],
            'cart_button_hover_bg_color'            => [ 'selector' => "$cart_icon:hover", 'property' => 'background-color' ],
            'step_menu_color'                       => [ 'selector' => $step_menu_selector, 'property' => 'color' ],
            'step_menu_hover_color'                 => [ 'selector' => "$step_menu_selector:hover, $step_menu_active_selector", 'property' => 'color' ],
            'step_menu_font_size'                   => [ 'selector' => $step_menu_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'next_button_height'                    => [ 'selector' => $next_button_selector, 'property' => 'height', 'unit' => 'px' ],
            'next_button_width'                     => [ 'selector' => $next_button_selector, 'property' => 'min-width', 'unit' => 'px' ],
            'next_button_font_size'                 => [ 'selector' => $next_button_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'next_button_color'                     => [ 'selector' => $next_button_selector, 'property' => 'color' ],
            'next_button_hover_color'               => [ 'selector' => "$next_button_selector:hover", 'property' => 'color' ],
            'next_button_bg_color'                  => [ 'selector' => $next_button_selector, 'property' => 'background-color' ],
            'next_button_hover_bg_color'            => [ 'selector' => "$next_button_selector:hover", 'property' => 'background-color' ],
            'next_button_border_color'              => [ 'selector' => $next_button_selector, 'property' => 'border-color' ],
            'next_button_hover_border_color'        => [ 'selector' => "$next_button_selector:hover", 'property' => 'border-color' ],
            'prev_button_height'                    => [ 'selector' => $prev_button_selector, 'property' => 'height', 'unit' => 'px' ],
            'prev_button_width'                     => [ 'selector' => $prev_button_selector, 'property' => 'min-width', 'unit' => 'px' ],
            'prev_button_font_size'                 => [ 'selector' => $prev_button_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'prev_button_bg_color'                  => [ 'selector' => $prev_button_selector, 'property' => 'background-color' ],
            'prev_button_hover_bg_color'            => [ 'selector' => "$prev_button_selector:hover", 'property' => 'background-color' ],
            'prev_button_color'                     => [ 'selector' => $prev_button_selector, 'property' => 'color' ],
            'prev_button_hover_color'               => [ 'selector' => "$prev_button_selector:hover", 'property' => 'color' ],
            'prev_button_border_color'              => [ 'selector' => $prev_button_selector, 'property' => 'border-color' ],
            'prev_button_hover_border_color'        => [ 'selector' => "$prev_button_selector:hover", 'property' => 'border-color' ],
            'coupon_height'                         => [ 'selector' => $coupon_input_button_selector, 'property' => 'height', 'unit' => 'px' ],
            'coupon_button_font_size'               => [ 'selector' => $coupon_button_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'coupon_button_bg_color'                => [ 'selector' => $coupon_button_selector, 'property' => 'background-color' ],
            'coupon_button_hover_bg_color'          => [ 'selector' => "$coupon_button_selector:hover", 'property' => 'background-color' ],
            'coupon_button_color'                   => [ 'selector' => $coupon_button_selector, 'property' => 'color' ],
            'coupon_button_hover_color'             => [ 'selector' => "$coupon_button_selector:hover", 'property' => 'color' ],
            'coupon_button_border_color'            => [ 'selector' => $coupon_button_selector, 'property' => 'border-color' ],
            'coupon_button_hover_border_color'      => [ 'selector' => "$coupon_button_selector:hover", 'property' => 'border-color' ],
            'place_order_button_height'             => [ 'selector' => $place_order_button_selector, 'property' => 'height', 'unit' => 'px' ],
            'place_order_button_width'              => [ 'selector' => $place_order_button_selector, 'property' => 'min-width', 'unit' => 'px' ],
            'place_order_button_font_size'          => [ 'selector' => $place_order_button_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'place_order_button_bg_color'           => [ 'selector' => $place_order_button_selector, 'property' => 'background-color' ],
            'place_order_button_hover_bg_color'     => [ 'selector' => "$place_order_button_selector:hover", 'property' => 'background-color' ],
            'place_order_button_color'              => [ 'selector' => $place_order_button_selector, 'property' => 'color' ],
            'place_order_button_hover_color'        => [ 'selector' => "$place_order_button_selector:hover", 'property' => 'color' ],
            'place_order_button_border_color'       => [ 'selector' => $place_order_button_selector, 'property' => 'border-color' ],
            'place_order_button_hover_border_color' => [ 'selector' => "$place_order_button_selector:hover", 'property' => 'border-color' ],
            'login_button_height'                   => [ 'selector' => $login_button_selector, 'property' => 'height', 'unit' => 'px' ],
            'login_button_width'                    => [ 'selector' => $login_button_selector, 'property' => 'min-width', 'unit' => 'px' ],
            'login_button_font_size'                => [ 'selector' => $login_button_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'login_button_bg_color'                 => [ 'selector' => $login_button_selector, 'property' => 'background-color' ],
            'login_button_hover_bg_color'           => [ 'selector' => "$login_button_selector:hover", 'property' => 'background-color' ],
            'login_button_color'                    => [ 'selector' => $login_button_selector, 'property' => 'color' ],
            'login_button_hover_color'              => [ 'selector' => "$login_button_selector:hover", 'property' => 'color' ],
            'login_button_border_color'             => [ 'selector' => $login_button_selector, 'property' => 'border-color' ],
            'login_button_hover_border_color'       => [ 'selector' => "$login_button_selector:hover", 'property' => 'border-color' ],
            'footer_menu_color'                     => [ 'selector' => $footer_menu_selector, 'property' => 'color' ],
            'footer_menu_hover_color'               => [ 'selector' => "$footer_menu_selector:hover", 'property' => 'color' ],
            'footer_menu_font_size'                 => [ 'selector' => $footer_menu_selector, 'property' => 'font-size', 'unit' => 'px' ],
            'footer_text_size'                      => [ 'selector' => $footer_text, 'property' => 'font-size', 'unit' => 'px' ],
            'footer_text_color'                     => [ 'selector' => $footer_text, 'property' => 'color'  ],
		];
		// phpcs:enable
		// Generate dynamic CSS.
		Fns::dynamic_styles( $this->options, $cache_key, $css_properties );
	}
}

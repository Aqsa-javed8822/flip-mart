<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\ThemesSupport\Astra;

// Do not allow directly accessing this file.
use Rtwpvsp\Controllers\ShopPage;

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
	private function __construct() {
	}

	/**
	 * Get class instance.
	 *
	 * @param object $widgets Widgets reference object.
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
	 * Remove Astra flash sale cards buttons HTML.
	 *
	 * @param string $html Cards buttons HTML.
	 *
	 * @return string
	 */
	public function astra_flash_sale_html_remove( $html ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return '';
	}


	/**
	 * Astra Wrapper
	 *
	 * @return void
	 */
	public function astra_woo_shop_thumbnail_wrap_start() {
		echo '<div class="astra-shop-thumbnail-wrap rtsb-image-wrapper">';
	}

	/**
	 * Product Loop content Modify.
	 *
	 * @return void
	 */
	public function astra_product_loop() {
		$controllers = $this->widgets->get_settings_for_display();

		if ( ! apply_filters( 'astra_woo_shop_product_structure_override', false ) ) {
			// Remove some hooks.
			remove_action( 'woocommerce_before_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_start', 6 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

			// Adding some hooks.
			add_action( 'woocommerce_before_shop_loop_item', [ $this, 'astra_woo_shop_thumbnail_wrap_start' ], 6 );
			add_action( 'woocommerce_after_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_end', 8 );
			add_action( 'woocommerce_after_shop_loop_item', 'astra_woo_woocommerce_shop_product_content' );
			add_action( 'astra_woo_shop_before_summary_wrap', [ $this->widgets, 'product_content_wrapper' ], 6 );
			add_action( 'astra_woo_shop_after_summary_wrap', [ $this->widgets, 'div_close' ], 6 );

			if ( empty( $controllers['show_flash_sale'] ) ) {
				if ( ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! \Astra_Ext_Extension::is_active( 'woocommerce' ) ) ) {
					add_filter( 'astra_addon_shop_cards_buttons_html', [ $this, 'astra_flash_sale_html_remove' ] );
				}
			}
		}
		$this->widgets->apply_hooks_set_default();

		// Variation Swatch fix.
		if ( class_exists( 'WooProductVariationSwatches' ) ) {
			$swatches_position = rtwpvs()->get_option( 'archive_swatches_position' );
			if ( 'before_title_and_price' == $swatches_position && class_exists( ShopPage::class ) ) {
				remove_action(
					'woocommerce_before_shop_loop_item_title',
					[
						ShopPage::class,
						'archive_variation_swatches',
					],
					35
				);
				add_action(
					'astra_woo_shop_title_before',
					[
						ShopPage::class,
						'archive_variation_swatches',
					],
					35
				);
			}
		}

		// Remove Action Button.
		add_filter( 'rtsb/module/wishlist/show_button', '__return_false' );
		add_filter( 'rtsb/module/quick_view/show_button', '__return_false' );
		add_filter( 'rtsb/module/compare/show_button', '__return_false' );

		$this->maybe_fix_flash_sale_badge_overlap();
	}

	/**
	 * Astra-only: stop the Product Badges group badge from hiding Astra's flash-sale badge.
	 *
	 * On Astra Modern shop style the flash-sale badge ( `.ast-onsale-card` ) is anchored top-left. When the
	 * Product Badges module renders its group badge above the image at that same top-left corner, the group
	 * badge sits over the flash-sale badge and hides it. ONLY in that exact situation we shift the flash-sale
	 * badge to the opposite (top-right) corner so both stay visible. Every other case bails out, so existing
	 * behaviour is untouched.
	 *
	 * @return void
	 */
	private function maybe_fix_flash_sale_badge_overlap() {
		$badge_opts = \RadiusTheme\SB\Helpers\Fns::get_options( 'modules', 'product_badges' );

		// Product Badges module is off — there is no group badge to overlap.
		if ( 'on' !== ( $badge_opts['active'] ?? '' ) ) {
			return;
		}
		// "Hide Theme Badges" is on — the module already removes the flash-sale badge, nothing to reposition.
		if ( ! empty( $badge_opts['hide_woocommerce_badge'] ) ) {
			return;
		}
		// Group badge is not rendered on the image, so it cannot overlap the flash-sale badge.
		if ( 'above_image' !== ( $badge_opts['loop_group_position'] ?? '' ) ) {
			return;
		}
		// Only the top-left corner clashes with Astra's (top-left) flash-sale badge.
		if ( 'top-left' !== ( $badge_opts['group_badge_position'] ?? '' ) ) {
			return;
		}

		add_action( 'woocommerce_before_shop_loop_item', [ $this, 'flash_sale_badge_position_style' ], 1 );
	}

	/**
	 * Print, once per request, the CSS that moves Astra's flash-sale badge to the top-right corner.
	 *
	 * The sibling selector only matches products that actually carry a group badge ( `.rtsb-badge-container` ),
	 * and `.ast-onsale-card` exists only on Astra, so the rule cannot affect other themes or non-badged products.
	 *
	 * @return void
	 */
	public function flash_sale_badge_position_style() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;

		echo '<style id="rtsb-astra-flash-sale-pos">.rtsb-badge-container ~ .ast-onsale-card{left:auto !important;right:20px !important;}</style>';
	}

	/**
	 * Product Content Reset.
	 *
	 * @return void
	 */
	public function astra_product_loop_reset_default() {
		$controllers = $this->widgets->get_settings_for_display();

		if ( ! apply_filters( 'astra_woo_shop_product_structure_override', false ) ) {
			// Remove Product Content Wrapper.
			remove_action( 'astra_woo_shop_before_summary_wrap', [ $this->widgets, 'product_content_wrapper' ], 6 );
			remove_action( 'astra_woo_shop_after_summary_wrap', [ $this->widgets, 'div_close' ], 6 );
			if ( empty( $controllers['show_flash_sale'] ) ) {
				if ( ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! \Astra_Ext_Extension::is_active( 'woocommerce' ) ) ) {
					remove_filter( 'astra_addon_shop_cards_buttons_html', [ $this, 'astra_flash_sale_html_remove' ] );
				}
			}
		}
	}

	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with render.
	 *
	 * @return void
	 */
	public function render_rtsb_products_archive() {
		$this->astra_product_loop();
	}

	/**
	 *
	 * @return void
	 */
	public function render_reset_rtsb_products_archive() {
		$this->astra_product_loop_reset_default();
	}
	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with render.
	 *
	 * @return void
	 */
	public function render_rtsb_product_rating() {
		remove_filter( 'woocommerce_product_get_rating_html', [ \Astra_Woocommerce::get_instance(), 'rating_markup' ], 10 );
	}
	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with render.
	 *
	 * @return void
	 */
	public function render_rtsb_upsells_product() {
		$this->astra_product_loop();
	}

	/**
	 * Reset Hooks.
	 *
	 * @return void
	 */
	public function render_reset_rtsb_upsells_product() {
		$this->astra_product_loop_reset_default();
	}

	/**
	 * Related Product.
	 *
	 * @return void
	 */
	public function render_rtsb_related_product() {
		$this->astra_product_loop();
	}

	/**
	 * Related Product.
	 *
	 * @return void
	 */
	public function render_reset_rtsb_related_product() {
		$this->astra_product_loop_reset_default();
	}

	/**
	 * Cross Sell Product.
	 *
	 * @return void
	 */
	public function render_rtsb_cross_sells() {
		$this->astra_product_loop();
	}

	/**
	 * Cross Sell Product.
	 *
	 * @return void
	 */
	public function render_reset_rtsb_cross_sells() {
		$this->astra_product_loop_reset_default();
	}

	/**
	 * Cross Sell Product.
	 *
	 * @return void
	 */
	public function render_rtsb_product_onsale() {
		add_filter( 'woocommerce_sale_flash', [ \Astra_Woocommerce::get_instance(), 'get_sale_flash_markup' ], 10, 3 );
	}
	/**
	 * Cross Sell Product.
	 *
	 * @return void
	 */
	public function render_rtsb_product_stock() {
		remove_filter( 'woocommerce_get_stock_html', 'astra_woo_product_in_stock', 10, 2 );
	}

	/**
	 * Product Images widget: make the "Show Flash Sale?" toggle authoritative on Astra.
	 *
	 * Astra registers its own `woocommerce_sale_flash` filter that rewrites and relocates the
	 * badge markup, so the widget's template gate (OFF) and its `.onsale` position/style controls
	 * (ON) are both overridden. We hook the same filter at a late priority so the toggle wins:
	 * OFF removes the badge from every source, ON restores WooCommerce's default `.onsale` markup
	 * the widget's selectors are built around.
	 *
	 * Method name derives from the widget base "rtsb-product-image" (see ElementorWidgetBase::theme_support).
	 *
	 * @return void
	 */
	public function render_rtsb_product_image() {
		$controllers = $this->widgets->get_settings_for_display();

		if ( empty( $controllers['sale_flash_badge'] ) ) {
			add_filter( 'woocommerce_sale_flash', '__return_empty_string', 99 );
		} else {
			add_filter( 'woocommerce_sale_flash', [ $this, 'default_sale_flash_markup' ], 99 );
		}
	}

	/**
	 * Reset the Product Images sale-flash overrides after the widget renders.
	 *
	 * @return void
	 */
	public function render_reset_rtsb_product_image() {
		$controllers = $this->widgets->get_settings_for_display();

		if ( empty( $controllers['sale_flash_badge'] ) ) {
			remove_filter( 'woocommerce_sale_flash', '__return_empty_string', 99 );
		} else {
			remove_filter( 'woocommerce_sale_flash', [ $this, 'default_sale_flash_markup' ], 99 );
		}
	}

	/**
	 * Force WooCommerce's default sale-flash markup so the Product Images widget's
	 * own `.onsale` position and style controls apply on Astra.
	 *
	 * @param string $html Existing sale-flash markup (from Astra or WooCommerce).
	 *
	 * @return string
	 */
	public function default_sale_flash_markup( $html ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return '<span class="onsale">' . esc_html__( 'Sale!', 'shopbuilder' ) . '</span>';
	}

	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with widget_controls.
	 *
	 * @return void
	 */
	public function widget_controls_rtsb_product_tabs() {
		add_filter( 'rtsb/elements/elementor/widgets/controls/rtsb-product-tabs', [ $this, 'product_tabs_widget_controls_support' ] );
	}

	/**
	 * Widget Field.
	 *
	 * @param array $fields Widget fields.
	 *
	 * @return array
	 */
	public function product_tabs_widget_controls_support( $fields ) {
		$fields['nav_active_border_color']['selectors']       = array_merge(
			$fields['nav_active_border_color']['selectors'],
			[
				'{{WRAPPER}} .woocommerce-tabs ul.tabs li.active:before' => 'background: {{VALUE}}',
			]
		);
		$fields['nav_active_hover_border_color']['selectors'] = array_merge(
			$fields['nav_active_hover_border_color']['selectors'],
			[
				'{{WRAPPER}} .woocommerce-tabs ul.tabs li.active:hover:before' => 'background: {{VALUE}}',
			]
		);

		return $fields;
	}

	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with "widget_controls".
	 *
	 * @return void
	 */
	public function widget_controls_rtsb_products_archive() {
		add_filter( 'rtsb/elements/elementor/widgets/controls/rtsb-products-archive', [ $this, 'product_loop_widget_controls_support' ], 11 );
	}

	/**
	 * Widget Field.
	 *
	 * @param array $fields Widget fields.
	 *
	 * @return array
	 */
	public function product_loop_widget_controls_support( $fields ) {
		if ( $this->widgets->has_pagination ) {
			$fields['prev_icon']['default'] = [
				'value'   => 'fas fa-long-arrow-alt-left',
				'library' => 'fa-solid',
			];
			$fields['next_icon']['default'] = [
				'value'   => 'fas fa-long-arrow-alt-right',
				'library' => 'fa-solid',
			];
		}
		$astra_onsale_card = '{{WRAPPER}} .products .product .ast-onsale-card';
		if ( ! empty( $fields['flash_sale_typography']['selector'] ) ) {
			$fields['flash_sale_typography']['selector'] = $fields['flash_sale_typography']['selector'] . ',' . $astra_onsale_card;
		}
		if ( ! empty( $fields['product_flash_sale_color']['selectors'] ) ) {
			$fields['product_flash_sale_color']['selectors'][ $astra_onsale_card ] = 'color: {{VALUE}};';
		}
		if ( ! empty( $fields['flash_sale_bg_color']['selectors'] ) ) {
			$fields['flash_sale_bg_color']['selectors'][ $astra_onsale_card ] = 'background-color: {{VALUE}};';
		}
		if ( ! empty( $fields['flash_sale_badge_width']['selectors'] ) ) {
			$fields['flash_sale_badge_width']['selectors'][ $astra_onsale_card ] = 'width: {{SIZE}}{{UNIT}}; text-align: center;';
		}
		if ( ! empty( $fields['flash_sale_badge_height']['selectors'] ) ) {
			$fields['flash_sale_badge_height']['selectors'][ $astra_onsale_card ] = 'height: {{SIZE}}{{UNIT}};';
		}
		if ( ! empty( $fields['flash_sale_badge_border_radius']['selectors'] ) ) {
			$fields['flash_sale_badge_border_radius']['selectors'][ $astra_onsale_card ] = 'border-radius: {{SIZE}}{{UNIT}};';
		}
		return $fields;
	}

	/**
	 * The function name comes from the widget base name "rtsb-products-archive". This is for theme support prefix with widget_controls.
	 *
	 * @return void
	 */
	public function widget_controls_rtsb_related_product() {
		add_filter( 'rtsb/elements/elementor/widgets/controls/rtsb-related-product', [ $this, 'widget_controls_flash_sale' ] );
	}

	/**
	 * @return void
	 */
	public function widget_controls_rtsb_upsells_product() {
		add_filter( 'rtsb/elements/elementor/widgets/controls/rtsb-upsells-product', [ $this, 'widget_controls_flash_sale' ] );
	}
	/**
	 * @return void
	 */
	public function widget_controls_rtsb_cross_sells() {
		add_filter( 'rtsb/elements/elementor/widgets/controls/rtsb-cross-sells', [ $this, 'widget_controls_flash_sale' ] );
	}
	/**
	 * Widget Field.
	 *
	 * @param array $fields Widget fields.
	 *
	 * @return array
	 */
	public function widget_controls_flash_sale( $fields ) {
		$selector                                    = '{{WRAPPER}} .products .product .ast-onsale-card';
		$fields['flash_sale_typography']['selector'] = $selector;
		$fields['product_flash_sale_color']['selectors'][ $selector ]       = 'color: {{VALUE}};';
		$fields['flash_sale_bg_color']['selectors'][ $selector ]            = 'background-color: {{VALUE}};';
		$fields['flash_sale_badge_width']['selectors'][ $selector ]         = 'width: {{SIZE}}{{UNIT}};display:flex; justify-content: center;';
		$fields['flash_sale_badge_height']['selectors'][ $selector ]        = 'height: {{SIZE}}{{UNIT}};align-items:center;';
		$fields['flash_sale_badge_border_radius']['selectors'][ $selector ] = 'border-radius: {{SIZE}}{{UNIT}};';

		return $fields;
	}
}

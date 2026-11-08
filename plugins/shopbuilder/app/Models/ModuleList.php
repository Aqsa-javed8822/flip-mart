<?php
/**
 * ShopBuilder Module List.
 *
 * @package  RadiusTheme\SB
 */

namespace RadiusTheme\SB\Models;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\SvgIcons;
use RadiusTheme\SB\Models\Base\ListModel;
use RadiusTheme\SB\Modules\Badges\Badges;
use RadiusTheme\SB\Modules\CheckoutEditor\CheckoutFns;
use RadiusTheme\SB\Modules\VariationSwatches\SwatchesFns;
use RadiusTheme\SB\Modules\ShopifyCheckout\ShopifyCheckout;
use RadiusTheme\SB\Modules\CheckoutEditor\CheckoutEditorInit;
use RadiusTheme\SB\Modules\AbandonedCartRecovery\CartRecoveryInit;
use RadiusTheme\SB\Modules\VariationSwatches\VariationSwatches;
use RadiusTheme\SB\Modules\VariationGallery\VariationGalleryInit;
use RadiusTheme\SB\Modules\AbandonedCartRecovery\CartRecoveryOptions;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Modules\Compare\Compare;
use RadiusTheme\SB\Modules\WishList\Wishlist;
use RadiusTheme\SB\Modules\Compare\CompareFns;
use RadiusTheme\SB\Modules\QuickView\QuickView;
use RadiusTheme\SB\Modules\WishList\WishlistFns;
use RadiusTheme\SBPRO\Helpers\FnsPro;

defined( 'ABSPATH' ) || exit;

/**
 * ShopBuilder Module List.
 */
class ModuleList extends ListModel {

	use SingletonTrait;

	/**
	 * List ID.
	 *
	 * @var string
	 */
	protected $list_id = 'modules';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->title       = esc_html__( 'Modules', 'shopbuilder' );
		$this->description = esc_html__( 'Here you can find the list of all the modules. You can individually enable or disable the modules. Or you can do that by one click.', 'shopbuilder' );
	}

	/**
	 * Module list.
	 *
	 * @return mixed|null
	 */
	protected function raw_list() {
		$isSwatchActive  = false;
		$isGalleryActive = false;
		$variationSwatch = apply_filters(
			'rtsb/module/variation_swatches/options',
			[
				'id'           => 'variation_swatches',
				'title'        => esc_html__( 'Variation Swatches', 'shopbuilder' ),
				'badge'        => 'new',
				'package'      => 'free',
				'active'       => 'on',
				'base_class'   => VariationSwatches::class,
				'active_field' => [
					'label' => esc_html__( 'Enable Variation Swatches Module?', 'shopbuilder' ),
					'help'  => esc_html__( 'Switch on to enable variation swatches module.', 'shopbuilder' ),
				],
				'tabs'         => [
					'general'       => [
						'title' => esc_html__( 'General', 'shopbuilder' ),
					],
					'showcase_shop' => [
						'title' => esc_html__( 'Showcase / Shop', 'shopbuilder' ),
					],
					'style'         => [
						'title' => esc_html__( 'Style', 'shopbuilder' ),
					],
				],
				'fields'       => $this->get_variation_swatches_fields(),
			],
			$isSwatchActive
		);

		if ( Fns::check_plugin_active( 'woo-product-variation-swatches/woo-product-variation-swatches.php' ) ) {
			$isSwatchActive  = true;
			$variationSwatch = apply_filters(
				'rtsb/module/variation_swatches/options',
				[
					'id'                => 'variation_swatches',
					'external'          => true,
					'title'             => esc_html__( 'Variation Swatches', 'shopbuilder' ),
					'pluginSlug'        => 'woo-product-variation-swatches',
					'pluginIsInstalled' => Fns::check_plugin_installed( 'woo-product-variation-swatches/woo-product-variation-swatches.php' ),
					'pluginIsActive'    => Fns::check_plugin_active( 'woo-product-variation-swatches/woo-product-variation-swatches.php' ),
					'pluginActiveUrl'   => add_query_arg(
						[
							'_wpnonce' => wp_create_nonce( 'activate-plugin_woo-product-variation-swatches/woo-product-variation-swatches.php' ),
							'action'   => 'activate',
							'plugin'   => 'woo-product-variation-swatches/woo-product-variation-swatches.php',
						],
						admin_url( 'plugins.php' )
					),
					'help'              => __( 'Currently using an external plugin. You can disable the external plugin and enable the built-in module for better performance. You’ll need to reconfigure settings if you switch to  built-in module.', 'shopbuilder' ),
					'package'           => 'free',
					'active_field'      => [
						'disable' => false,
					],
					'fields'            => [],
				],
				$isSwatchActive
			);
		}
		$variationGallery = apply_filters(
			'rtsb/module/variation_gallery/options',
			[
				'id'           => 'variation_gallery',
				'title'        => esc_html__( 'Variation Gallery', 'shopbuilder' ),
				'badge'        => 'new',
				'package'      => 'free',
				'active'       => 'on',
				'base_class'   => VariationGalleryInit::class,
				'active_field' => [
					'label' => esc_html__( 'Enable Variation Gallery Module?', 'shopbuilder' ),
					'help'  => esc_html__( 'Switch on to enable variation gallery module.', 'shopbuilder' ),
				],
				'tabs'         => [
					'general' => [
						'title' => esc_html__( 'General', 'shopbuilder' ),
					],
					'styles'  => [
						'title' => esc_html__( 'Styles', 'shopbuilder' ),
					],
				],
				'fields'       => $this->get_variation_gallery_fields(),
			],
			$isGalleryActive
		);
		if ( Fns::check_plugin_active( 'woo-product-variation-gallery/woo-product-variation-gallery.php' ) ) {
			$isGalleryActive  = true;
			$variationGallery = apply_filters(
				'rtsb/module/variation_gallery/options',
				[
					'id'                => 'variation_gallery',
					'external'          => true,
					'title'             => esc_html__( 'Variation Gallery', 'shopbuilder' ),
					'pluginSlug'        => 'woo-product-variation-gallery',
					'pluginIsInstalled' => Fns::check_plugin_installed( 'woo-product-variation-gallery/woo-product-variation-gallery.php' ),
					'pluginIsActive'    => Fns::check_plugin_active( 'woo-product-variation-gallery/woo-product-variation-gallery.php' ),
					'pluginActiveUrl'   => add_query_arg(
						[
							'_wpnonce' => wp_create_nonce( 'activate-plugin_woo-product-variation-gallery/woo-product-variation-gallery.php' ),
							'action'   => 'activate',
							'plugin'   => 'woo-product-variation-gallery/woo-product-variation-gallery.php',
						],
						admin_url( 'plugins.php' )
					),
					'help'              => __( 'Currently using an external plugin. You can disable the external plugin and enable the built-in module for better performance. You’ll need to reconfigure settings if you switch to  built-in module.', 'shopbuilder' ),
					'package'           => 'free',
					'active_field'      => [
						'disable' => false,
					],
					'fields'            => [],
				],
				$isGalleryActive
			);
		}
		$list = [
			'quick_view'                    => apply_filters(
				'rtsb/module/quick_view/options',
				[
					'id'           => 'quick_view',
					'title'        => esc_html__( 'Quick View', 'shopbuilder' ),
					'base_class'   => QuickView::class,
					'active'       => 'on',
					'package'      => 'free',
					'active_field' => [
						'label' => esc_html__( 'Enable Quick View?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable quick view module.', 'shopbuilder' ),
					],
					'tabs'         => [
						'general' => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'style'   => [
							'title' => esc_html__( 'Style', 'shopbuilder' ),
						],
					],
					'fields'       => apply_filters(
						'rtsb/module/quick_view/fields',
						[
							'loop_btn_position'           => [
								'id'      => 'loop_btn_position',
								'type'    => 'select',
								'value'   => 'custom',
								'label'   => esc_html__( 'Showcase Button Position', 'shopbuilder' ),
								'help'    => esc_html__( 'Manage quick view button position in product showcases (Shop, Related, Upsells, Cross-sells, etc.).', 'shopbuilder' ),
								'tab'     => 'general',
								'options' => [
									'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
									'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
									'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
									'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
								],

							],
							'loop_btn_position_shortcode' => [
								'type'       => 'raw',
								'label'      => ' ',
								'html'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Choose where to show button in WooCommerce product\'s loop. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
									'<code>[rtsb_quick_view_button]</code>'
								),
								'tab'        => 'general',
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.quick_view.loop_btn_position',
											'value'    => 'shortcode',
											'operator' => '==',
										],
									],
								],
							],
							'loop_custom_hook_name'       => [
								'id'         => 'loop_custom_hook_name',
								'type'       => 'text',
								'label'      => esc_html__( 'Enter Hook Name', 'shopbuilder' ),
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Or Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
									"<br /><code>&lt;?php do_action( 'rtsb/modules/quick_view/frontend/display' ); ?&gt;</code><br />"
								),
								'tab'        => 'general',
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.quick_view.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'loop_custom_hook_priority'   => [
								'id'         => 'loop_custom_hook_priority',
								'type'       => 'number',
								'value'      => 10,
								'size'       => 'small',
								'min'        => 0,
								'max'        => 999,
								'label'      => esc_html__( 'Hook Priority', 'shopbuilder' ),
								'tab'        => 'general',
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.quick_view.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'button_text'                 => [
								'id'          => 'button_text',
								'label'       => esc_html__( 'Quick View Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter your quick view button text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => 'Quick View',
								'placeholder' => 'Quick View',
								'tab'         => 'general',
							],

							// Module Style.
							'modal_width'                 => [
								'id'    => 'modal_width',
								'label' => esc_html__( 'Modal Width (in px)', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view button modal width. Example: 950', 'shopbuilder' ),
								'type'  => 'number',
								'value' => '',
								'tab'   => 'style',
							],

							'modal_height'                => [
								'id'    => 'modal_height',
								'label' => esc_html__( 'Modal Height (in px)', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view button modal height. Example: 450', 'shopbuilder' ),
								'type'  => 'number',
								'value' => '',
								'tab'   => 'style',
							],

							'modal_wrapper_padding'       => [
								'id'    => 'modal_wrapper_padding',
								'label' => esc_html__( 'Modal Wrapper Padding', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view modal wrapper padding. Example 1: 20px, Example 2: 20px 20px 20px 20px', 'shopbuilder' ),
								'type'  => 'text',
								'value' => '',
								'tab'   => 'style',
							],
							'modal_bg_color'              => [
								'id'    => 'modal_bg_color',
								'label' => esc_html__( 'Modal Backgroud Color', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view modal background color. Example: #fff', 'shopbuilder' ),
								'type'  => 'color',
								'value' => '',
								'tab'   => 'style',
							],
							'modal_box_shadow_color'      => [
								'id'    => 'modal_box_shadow_color',
								'label' => esc_html__( 'Modal Box Shadow Color', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view modal box shadow. Example: #000', 'shopbuilder' ),
								'type'  => 'color',
								'value' => '',
								'tab'   => 'style',
							],

							'modal_overly_color'          => [
								'id'    => 'modal_overly_color',
								'label' => esc_html__( 'Modal Overly Color', 'shopbuilder' ),
								'help'  => esc_html__( 'Quick view modal overly. Example: #000', 'shopbuilder' ),
								'type'  => 'color',
								'value' => '',
								'tab'   => 'style',
							],

						]
					),
				]
			),
			'wishlist'                      => apply_filters(
				'rtsb/module/wishlist/options',
				[
					'id'           => 'wishlist',
					'title'        => esc_html__( 'Wishlist', 'shopbuilder' ),
					'package'      => 'free',
					'active'       => 'on',
					'base_class'   => Wishlist::class,
					'active_field' => [
						'label' => esc_html__( 'Enable Wishlist?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable wishlist module.', 'shopbuilder' ),
					],
					'fields'       => apply_filters(
						'rtsb/module/wishlist/fields',
						[
							'enable_login_limit'           => [
								'id'    => 'enable_login_limit',
								'type'  => 'switch',
								'label' => esc_html__( 'Limit Wishlist Use', 'shopbuilder' ),
								'help'  => esc_html__( 'Enable this option to allow only the logged-in users to use the Wishlist feature.', 'shopbuilder' ),
								'tab'   => 'general',
							],
							'hide_wishlist_non_logged_in'  => [
								'id'         => 'hide_wishlist_non_logged_in',
								'type'       => 'switch',
								'label'      => esc_html__( 'Hide Wishlist Button', 'shopbuilder' ),
								'help'       => esc_html__( 'Hide Wishlist Button For non logged-in users (guest users).', 'shopbuilder' ),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.enable_login_limit',
											'value'    => 'on',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'general',
							],
							'wishlist_shop_wrapper_heading' => [
								'id'    => 'wishlist_shop_wrapper_heading',
								'type'  => 'title',
								'label' => esc_html__( 'Product Showcase Settings', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'show_btn_on_loop'             => [
								'id'    => 'show_btn_on_loop',
								'value' => 'on',
								'type'  => 'switch',
								'label' => esc_html__( 'Show in Product Showcase', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'loop_btn_position'            => [
								'id'         => 'loop_btn_position',
								'type'       => 'select',
								'value'      => 'custom',
								'label'      => esc_html__( 'Showcase Button Position', 'shopbuilder' ),
								'help'       => esc_html__( 'Manage wishlist button position in product showcases (Shop, Related, Upsells, Cross-sells, etc.).', 'shopbuilder' ),
								'options'    => [
									'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
									'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
									'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
									'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
								],
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'loop_btn_position_shortcode'  => [
								'type'       => 'raw',
								'label'      => ' ',
								'html'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Choose where to show button in WooCommerce product\'s loop. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
									'<code>[rtsb_wishlist_button]</code>'
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.loop_btn_position',
											'value'    => 'shortcode',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'loop_custom_hook_name'        => [
								'id'         => 'loop_custom_hook_name',
								'type'       => 'text',
								'label'      => esc_html__( 'Enter Hook Name', 'shopbuilder' ),
								'tab'        => 'button',
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Or Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
									"<br /><code>&lt;?php do_action('rtsb/modules/wishlist/frontend/display' ); ?&gt;</code><br />"
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'loop_custom_hook_priority'    => [
								'id'         => 'loop_custom_hook_priority',
								'type'       => 'number',
								'value'      => 10,
								'size'       => 'small',
								'min'        => 0,
								'max'        => 999,
								'label'      => esc_html__( 'Hook Priority', 'shopbuilder' ),
								'tab'        => 'button',
								'dependency' => [
									'rules'    => [
										[
											'item'     => 'modules.wishlist.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
									'relation' => 'and',
								],
							],
							'wishlist_single_wrapper_heading' => [
								'id'    => 'wishlist_single_wrapper_heading',
								'type'  => 'title',
								'label' => esc_html__( 'Product Page Settings', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'show_btn_product_page'        => [
								'id'    => 'show_btn_product_page',
								'value' => 'on',
								'type'  => 'switch',
								'label' => esc_html__( 'Show in Product Page', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'product_btn_position'         => [
								'id'         => 'product_btn_position',
								'label'      => esc_html__( 'Product Page Button Position', 'shopbuilder' ),
								'help'       => esc_html__( 'You can manage compare button position in single product page.', 'shopbuilder' ),
								'type'       => 'select',
								'value'      => 'custom',
								'options'    => [
									'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
									'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
									'after_thumbnail'    => esc_html__( 'After Image', 'shopbuilder' ),
									'after_summary'      => esc_html__( 'After Summary', 'shopbuilder' ),
									'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
									'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
								],
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'product_btn_position_shortcode' => [
								'type'       => 'raw',
								'label'      => ' ',
								'html'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Choose where to show button on the product page. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
									'<code>[rtsb_wishlist_button]</code>'
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.product_btn_position',
											'value'    => 'shortcode',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'product_custom_hook_name'     => [
								'id'         => 'product_custom_hook_name',
								'type'       => 'text',
								'label'      => esc_html__( 'Product Hook Name', 'shopbuilder' ),
								'tab'        => 'button',
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Or Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
									"<br /><code>&lt;?php do_action('rtsb/modules/wishlist/frontend/display' ); ?&gt;</code><br />"
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.wishlist.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.product_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'product_custom_hook_priority' => [
								'id'         => 'product_custom_hook_priority',
								'type'       => 'number',
								'value'      => 10,
								'size'       => 'small',
								'min'        => 0,
								'max'        => 999,
								'label'      => esc_html__( 'Product Hook Priority', 'shopbuilder' ),
								'tab'        => 'button',
								'dependency' => [
									'rules'    => [
										[
											'item'     => 'modules.wishlist.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.wishlist.product_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
									'relation' => 'and',
								],
							],
							'button_text'                  => [
								'id'          => 'button_text',
								'label'       => esc_html__( 'Wishlist Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter your wishlist button text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => 'Add to Wishlist',
								'placeholder' => 'Add to Wishlist',
								'tab'         => 'button',
							],
							'notice_added_text'            => [
								'id'          => 'notice_added_text',
								'label'       => esc_html__( 'Product Added Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter the product added text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Product added!', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Product added!', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'notice_removed_text'          => [
								'id'          => 'notice_removed_text',
								'label'       => esc_html__( 'Product Removed Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter the product removed text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Product removed!', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Product removed!', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'browse_list_text'             => [
								'id'          => 'browse_list_text',
								'label'       => esc_html__( 'Browse Wishlist Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter a text for the "Browse wishlist" link on the product page', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Browse Wishlist', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Browse Wishlist', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'page'                         => [
								'id'      => 'page',
								'type'    => 'select',
								'label'   => esc_html__( 'Select Wishlist Page', 'shopbuilder' ),
								'help'    => sprintf(
									/* translators: 1: The shortcode.*/
									esc_html__( 'Select a page for wishlist page and make sure you add the shortcode %1$s into the page content', 'shopbuilder' ),
									'<code>[rtsb_wishlist]</code>'
								),
								'options' => Fns::get_pages(),
								'tab'     => 'page',
							],
							'page_show_fields'             => [
								'value'       => WishlistFns::instance()->get_field_ids(),
								'id'          => 'page_show_fields',
								'multiple'    => true,
								'checkbox'    => true,
								'sanitize_fn' => 'sanitize_text_field',
								'type'        => 'sortable',
								'options'     => WishlistFns::instance()->get_default_fields(),
								'label'       => esc_html__( 'Wishlist Table Fields Visibility', 'shopbuilder' ),
								'tab'         => 'page',
							],

							'shortcode_list_heading'       => [
								'id'    => 'shortcode_list_heading',
								'type'  => 'title',
								'label' => esc_html__( 'Available Shortcodes', 'shopbuilder' ),
								'tab'   => 'shortcodes',
							],
							'shortcode_wishlist_table'     => [
								'type'  => 'raw',
								'label' => esc_html__( 'Wishlist Table', 'shopbuilder' ),
								'html'  => '<code>[rtsb_wishlist]</code><p class="description">'
									. esc_html__( 'Displays the wishlist table with all added products. Use this shortcode on the wishlist page.', 'shopbuilder' )
									. '</p>',
								'tab'   => 'shortcodes',
							],
							'shortcode_wishlist_button'    => [
								'type'  => 'raw',
								'label' => esc_html__( 'Wishlist Button', 'shopbuilder' ),
								'html'  => '<code>[rtsb_wishlist_button]</code><p class="description">'
									. esc_html__( 'Displays the "Add to Wishlist" button. Use inside a product loop or on a single product page.', 'shopbuilder' )
									. '</p>',
								'tab'   => 'shortcodes',
							],
							'shortcode_wishlist_counter'   => [
								'type'  => 'raw',
								'label' => esc_html__( 'Wishlist Counter', 'shopbuilder' ),
								'html'  => '<code>[rtsb_wishlist_counter]</code><p class="description">'
									. esc_html__( 'Displays the wishlist item count. Place it in the header or menu to show total wishlist items.', 'shopbuilder' )
									. '</p>',
								'tab'   => 'shortcodes',
							],

						]
					),
					'tabs'         => [
						'general'    => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'button'     => [
							'title' => esc_html__( 'Button', 'shopbuilder' ),
						],
						'page'       => [
							'title' => esc_html__( 'Page', 'shopbuilder' ),
						],
						'shortcodes' => [
							'title' => esc_html__( 'Shortcodes', 'shopbuilder' ),
						],
					],
				]
			),
			'compare'                       => apply_filters(
				'rtsb/module/compare/options',
				[
					'id'           => 'compare',
					'base_class'   => Compare::class,
					'title'        => esc_html__( 'Product Compare', 'shopbuilder' ),
					'package'      => 'free',
					'active'       => 'on',
					'active_field' => [
						'label' => esc_html__( 'Enable Product Comparison?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable product comparison module.', 'shopbuilder' ),
					],
					'fields'       => apply_filters(
						'rtsb/module/compare/fields',
						[
							'compare_shop_wrapper_heading' => [
								'id'    => 'compare_shop_wrapper_heading',
								'type'  => 'title',
								'label' => esc_html__( 'Product Showcase Settings', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'show_btn_on_loop'             => [
								'id'    => 'show_btn_on_loop',
								'value' => 'on',
								'type'  => 'switch',
								'label' => esc_html__( 'Show in Product Showcase', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'loop_btn_position'            => [
								'id'         => 'loop_btn_position',
								'type'       => 'select',
								'value'      => 'custom',
								'label'      => esc_html__( 'Showcase Button Position', 'shopbuilder' ),
								'help'       => esc_html__( 'Manage compare button position in product showcases (Shop, Related, Upsells, Cross-sells, etc.).', 'shopbuilder' ),
								'options'    => [
									'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
									'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
									'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
									'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
								],
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'loop_btn_position_shortcode'  => [
								'type'       => 'raw',
								'label'      => ' ',
								'html'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Choose where to show button in WooCommerce product\'s loop. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
									'<code>[rtsb_compare_button]</code>'
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.loop_btn_position',
											'value'    => 'shortcode',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'loop_custom_hook_name'        => [
								'id'         => 'product_custom_hook_name',
								'type'       => 'text',
								'label'      => esc_html__( 'Enter Hook Name', 'shopbuilder' ),
								'tab'        => 'button',
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Or Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
									"<br /><code>&lt;?php do_action('rtsb/modules/compare/frontend/display' ); ?&gt;</code><br />"
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'loop_custom_hook_priority'    => [
								'id'         => 'product_custom_hook_priority',
								'type'       => 'number',
								'value'      => 10,
								'size'       => 'small',
								'min'        => 0,
								'max'        => 999,
								'label'      => esc_html__( 'Hook Priority', 'shopbuilder' ),
								'tab'        => 'button',
								'dependency' => [
									'rules'    => [
										[
											'item'     => 'modules.compare.show_btn_on_loop',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.loop_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
									'relation' => 'and',
								],
							],
							'compare_single_wrapper_heading' => [
								'id'    => 'compare_single_wrapper_heading',
								'type'  => 'title',
								'label' => esc_html__( 'Product Page Settings', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'show_btn_product_page'        => [
								'id'    => 'show_btn_product_page',
								'type'  => 'switch',
								'value' => 'on',
								'label' => esc_html__( 'Show Button in Product Page', 'shopbuilder' ),
								'tab'   => 'button',
							],
							'product_btn_position'         => [
								'id'         => 'product_btn_position',
								'label'      => esc_html__( 'Product Page Button Position', 'shopbuilder' ),
								'help'       => esc_html__( 'You can manage compare button position in single product page.', 'shopbuilder' ),
								'type'       => 'select',
								'value'      => 'custom',
								'options'    => [
									'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
									'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
									'after_thumbnail'    => esc_html__( 'After Image', 'shopbuilder' ),
									'after_summary'      => esc_html__( 'After Summary', 'shopbuilder' ),
									'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
									'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
								],
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'product_btn_position_shortcode' => [
								'type'       => 'raw',
								'label'      => ' ',
								'html'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Choose where to show button on the product page. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
									'<code>[rtsb_compare_button]</code> '
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.product_btn_position',
											'value'    => 'shortcode',
											'operator' => '==',
										],
									],
								],
								'tab'        => 'button',
							],
							'product_custom_hook_name'     => [
								'id'         => 'product_custom_hook_name',
								'type'       => 'text',
								'label'      => esc_html__( 'Product Hook Name', 'shopbuilder' ),
								'tab'        => 'button',
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Or Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
									"<br /><code>&lt;?php do_action('rtsb/modules/compare/frontend/display' ); ?&gt;</code><br />"
								),
								'dependency' => [
									'rules' => [
										[
											'item'     => 'modules.compare.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.product_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
								],
							],
							'product_custom_hook_priority' => [
								'id'         => 'product_custom_hook_priority',
								'type'       => 'number',
								'value'      => 10,
								'size'       => 'small',
								'min'        => 0,
								'max'        => 999,
								'label'      => esc_html__( 'Product Hook Priority', 'shopbuilder' ),
								'tab'        => 'button',
								'dependency' => [
									'rules'    => [
										[
											'item'     => 'modules.compare.show_btn_product_page',
											'value'    => 'on',
											'operator' => '==',
										],
										[
											'item'     => 'modules.compare.product_btn_position',
											'value'    => 'custom',
											'operator' => '==',
										],
									],
									'relation' => 'and',
								],
							],
							'button_text'                  => [
								'id'          => 'button_text',
								'label'       => esc_html__( 'Compare Button Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter your compare button text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Compare', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Compare', 'shopbuilder' ),
								'tab'         => 'button',
							],
							'notice_added_text'            => [
								'id'          => 'notice_added_text',
								'label'       => esc_html__( 'Product Added Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter the product added text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Product added!', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Product added!', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'notice_removed_text'          => [
								'id'          => 'notice_removed_text',
								'label'       => esc_html__( 'Product Removed Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter the product removed text.', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Product removed!', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Product removed!', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'browse_list_text'             => [
								'id'          => 'browse_list_text',
								'label'       => esc_html__( 'Browse Compare Text', 'shopbuilder' ),
								'help'        => esc_html__( 'Enter a text for the "Browse compare" link on the compare page', 'shopbuilder' ),
								'type'        => 'text',
								'value'       => esc_html__( 'Browse Compare', 'shopbuilder' ),
								'placeholder' => esc_html__( 'Browse Compare', 'shopbuilder' ),
								'tab'         => 'general',
							],
							'page'                         => [
								'id'         => 'page',
								'type'       => 'select',
								'help'       => sprintf(
								/* translators: 1: The shortcode.*/
									esc_html__( 'Select a page for compare page and make sure you add the shortcode %1$s into the page content', 'shopbuilder' ),
									'<code>[rtsb_compare_list]</code>'
								),
								'label'      => esc_html__( 'Select Compare Page', 'shopbuilder' ),
								'options'    => Fns::get_pages(),
								'empty'      => esc_html__( 'Select a page', 'shopbuilder' ),
								'searchable' => true,
								'tab'        => 'page',
							],
							'max_limit'                    => [
								'id'          => 'page',
								'type'        => 'number',
								'sanitize_fn' => 'absint',
								'value'       => 10,
								'help'        => esc_html__( 'You can manage your maximum compare quantity from here.', 'shopbuilder' ),
								'label'       => esc_html__( 'Maximum Compare Limit', 'shopbuilder' ),
								'tab'         => 'page',
							],
							'page_show_fields'             => [
								'value'    => CompareFns::get_list_field_ids(),
								'id'       => 'page_show_fields',
								'multiple' => true,
								'checkbox' => true,
								'type'     => 'sortable',
								'options'  => CompareFns::get_available_list_fields(),
								'label'    => esc_html__( 'Compare Table Fields Visibility', 'shopbuilder' ),
								'tab'      => 'page',
							],

						]
					),
					'tabs'         => [
						'general' => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'button'  => [
							'title' => esc_html__( 'Button', 'shopbuilder' ),
						],
						'page'    => [
							'title' => esc_html__( 'Page', 'shopbuilder' ),
						],

					],
				]
			),
			'variation_swatches'            => $variationSwatch,
			'variation_gallery'             => $variationGallery,
			'product_badges'                => apply_filters(
				'rtsb/module/product_badges/options',
				[
					'id'           => 'product_badges',
					'active'       => '',
					'title'        => esc_html__( 'Product Badges', 'shopbuilder' ),
					'package'      => 'free',
					'active_field' => [
						'label' => esc_html__( 'Enable Product Badges?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable product badges module.', 'shopbuilder' ),
					],
					'base_class'   => Badges::class,
					'fields'       => $this->product_badges_fields(),
					'tabs'         => [
						'general' => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'group'   => [
							'title' => esc_html__( 'Settings', 'shopbuilder' ),
						],
						'badges'  => [
							'title' => esc_html__( 'Create Badges', 'shopbuilder' ),
						],
					],
				]
			),
			'checkout_fields_editor'        => apply_filters(
				'rtsb/module/checkout_fields_editor/options',
				[
					'id'           => 'checkout_fields_editor',
					'active'       => '',
					'title'        => esc_html__( 'Checkout Fields Editor', 'shopbuilder' ),
					'base_class'   => CheckoutEditorInit::class,
					'active_field' => [
						'label' => esc_html__( 'Enable Checkout Fields Editor?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Checkout Fields Editor module.', 'shopbuilder' ),
					],
					'fields'       => [
						'checkout_billing_intro'          => [
							'id'   => 'checkout_billing_intro',
							'type' => 'description',
							'text' => esc_html__( 'Billing Fields Editor allows you to customize the default billing fields on your checkout page.', 'shopbuilder' ),
							'tab'  => 'billing_fields',
						],
						'modify_billing_form'             => [
							'id'    => 'modify_billing_form',
							'type'  => 'switch',
							'label' => esc_html__( 'Customize Billing Form?', 'shopbuilder' ),
							'help'  => esc_html__( 'Enable this option to edit billing Form.', 'shopbuilder' ),
							'tab'   => 'billing_fields',
						],

						'checkout_billing_fields_info'    => [
							'id'         => 'checkout_billing_fields_info',
							'type'       => 'title',
							'label'      => esc_html__( 'Billing Fields', 'shopbuilder' ),
							'tab'        => 'billing_fields',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_billing_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
						'checkout_billing_fields'         => [
							'id'         => 'checkout_billing_fields',
							'type'       => 'checkout_fields',
							'tab'        => 'billing_fields',
							'value'      => wp_json_encode( CheckoutFns::default_billing_fields() ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_billing_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
						'checkout_shipping_intro'         => [
							'id'   => 'checkout_shipping_intro',
							'type' => 'description',
							'text' => esc_html__( 'Shipping Fields Editor allows you to customize the default shipping fields on your checkout page.', 'shopbuilder' ),
							'tab'  => 'shipping_fields',
						],
						'modify_shipping_form'            => [
							'id'    => 'modify_billing_form',
							'type'  => 'switch',
							'label' => esc_html__( 'Modify Shipping Form?', 'shopbuilder' ),
							'help'  => esc_html__( 'Enable this option to edit shipping Form.', 'shopbuilder' ),
							'tab'   => 'shipping_fields',
						],
						'checkout_shipping_fields_info'   => [
							'id'         => 'checkout_shipping_fields_info',
							'type'       => 'title',
							'label'      => esc_html__( 'Shipping Fields', 'shopbuilder' ),
							'tab'        => 'shipping_fields',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_shipping_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
						'checkout_shipping_fields'        => [
							'id'         => 'checkout_shipping_fields',
							'type'       => 'checkout_fields',
							'tab'        => 'shipping_fields',
							'value'      => wp_json_encode( CheckoutFns::default_shipping_fields() ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_shipping_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
						'checkout_additional_intro'       => [
							'id'   => 'checkout_additional_intro',
							'type' => 'description',
							'text' => esc_html__( 'Additional Fields Editor allows you to customize the extra fields on your checkout page.', 'shopbuilder' ),
							'tab'  => 'additional_fields',
						],
						'modify_additional_form'          => [
							'id'    => 'modify_additional_form',
							'type'  => 'switch',
							'label' => esc_html__( 'Modify Additional Form Field?', 'shopbuilder' ),
							'help'  => esc_html__( 'Enable this option to edit Additional Form.', 'shopbuilder' ),
							'tab'   => 'additional_fields',
						],
						'checkout_additional_fields_info' => [
							'id'         => 'checkout_additional_fields_info',
							'type'       => 'title',
							'label'      => esc_html__( 'Additional Fields', 'shopbuilder' ),
							'tab'        => 'additional_fields',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_additional_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
						'checkout_additional_fields'      => [
							'id'         => 'checkout_additional_fields',
							'type'       => 'checkout_fields',
							'tab'        => 'additional_fields',
							'value'      => wp_json_encode( CheckoutFns::checkout_additional_fields() ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.checkout_fields_editor.modify_additional_form',
										'value'    => 'on',
										'operator' => '==',
									],
								],
							],
						],
					],
					'tabs'         => [
						'general'           => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'billing_fields'    => [
							'title' => esc_html__( 'Billing Fields', 'shopbuilder' ),
						],
						'shipping_fields'   => [
							'title' => esc_html__( 'Shipping Fields', 'shopbuilder' ),
						],
						'additional_fields' => [
							'title' => esc_html__( 'Additional Fields', 'shopbuilder' ),
						],
					],
				]
			),
			'shopify_checkout'              => apply_filters(
				'rtsb/module/shopify_checkout/options',
				[
					'id'           => 'shopify_checkout',
					'active'       => '',
					'title'        => esc_html__( 'Shopify Checkout', 'shopbuilder' ),
					'base_class'   => ShopifyCheckout::class,
					'active_field' => [
						'label' => esc_html__( 'Enable Shopify Checkout?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Shopify Checkout module.', 'shopbuilder' ),
					],
					'fields'       => $this->shopify_checkout_options(),
					'tabs'         => [
						'general'    => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'multi-step' => [
							'title' => esc_html__( 'Multi-Step', 'shopbuilder' ),
						],
						'styles'     => [
							'title' => esc_html__( 'General Styles', 'shopbuilder' ),
						],
						'btn-styles' => [
							'title' => esc_html__( 'Button Styles', 'shopbuilder' ),
						],
					],
				]
			),
			'mini_cart'                     => apply_filters(
				'rtsb/module/mini_cart/options',
				[
					'id'           => 'mini_cart',
					'active'       => '',
					'title'        => esc_html__( 'Mini Cart', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Mini Cart?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable mini cart module.', 'shopbuilder' ),
					],
					'is_active'    => true,
					'fields'       => Fns::pro_version_notice( '1.0.0' ),
				]
			),
			'product_size_chart'            => apply_filters(
				'rtsb/module/product_size_chart/options',
				[
					'id'           => 'product_size_chart',
					'active'       => '',
					'title'        => esc_html__( 'Product Size Chart', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Product Size Chart?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Product Size Chart module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.0.0' ),
				]
			),
			'customize_my_account'          => apply_filters(
				'rtsb/module/customize_my_account/options',
				[
					'id'           => 'customize_my_account',
					'active'       => '',
					'title'        => esc_html__( 'Customize My Account', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Customize My Account?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable customize my account module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.4.0' ),
				]
			),
			'pre_order'                     => apply_filters(
				'rtsb/module/pre_order/options',
				[
					'id'           => 'pre_order',
					'active'       => '',
					'title'        => esc_html__( 'Pre-Order', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Pre-Order?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable pre-order module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.5.0' ),
				]
			),
			'currency_switcher'             => apply_filters(
				'rtsb/module/currency_switcher/options',
				[
					'id'           => 'currency_switcher',
					'active'       => '',
					'title'        => esc_html__( 'Currency Switcher', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Currency Switcher?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable currency switcher module.', 'shopbuilder' ),
					],
					'is_active'    => true,
					'fields'       => Fns::pro_version_notice( '1.5.0' ),
				]
			),
			'product_add_ons'               => apply_filters(
				'rtsb/module/product_add_ons/options',
				[
					'id'           => 'product_add_ons',
					'active'       => '',
					'title'        => esc_html__( 'Product Add-Ons', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Product Add-Ons?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable product add-ons module.', 'shopbuilder' ),
					],
					'is_active'    => true,
					'fields'       => Fns::pro_version_notice( '1.6.0' ),
				]
			),
			'sales_notification'            => apply_filters(
				'rtsb/module/sales_notification/options',
				[
					'id'           => 'sales_notification',
					'active'       => '',
					'title'        => esc_html__( 'Sales Notification', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Sales Notification?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Sales Notification module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.0.0' ),
				]
			),
			'flash_sale_countdown'          => apply_filters(
				'rtsb/module/flash_sale_countdown/options',
				[
					'id'           => 'flash_sale_countdown',
					'active'       => '',
					'title'        => esc_html__( 'Flash Sale Countdown', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Flash Sale Countdown?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Flash Sale Countdown module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.0.0' ),
				]
			),
			'quick_checkout'                => apply_filters(
				'rtsb/module/quick_checkout/options',
				[
					'id'           => 'quick_checkout',
					'active'       => '',
					'title'        => esc_html__( 'Quick Checkout', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Quick Checkout?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable quick checkout module. Note: Currently, this is not suitable for variable products.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.1.0' ),
				]
			),
			'multi_step_checkout'           => apply_filters(
				'rtsb/module/multi_step_checkout/options',
				[
					'id'           => 'multi_step_checkout',
					'active'       => '',
					'title'        => esc_html__( 'Multi-Step Checkout', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Multi-Step Checkout?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Multi-step Checkout module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.0.0' ),
				]
			),
			'back_order'                    => apply_filters(
				'rtsb/module/back_order/options',
				[
					'id'           => 'back_order',
					'active'       => '',
					'title'        => esc_html__( 'Back-Order', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Back-Order?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable back-order module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.3.0', 'general', 'Back-Order', false ),
				]
			),
			'sticky_add_to_cart'            => apply_filters(
				'rtsb/module/sticky_add_to_cart/options',
				[
					'id'           => 'sticky_add_to_cart',
					'active'       => '',
					'title'        => esc_html__( 'Sticky Add-To-Cart', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Sticky Add-To-Cart?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable sticky add-to-cart module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.7.0', 'general', 'Sticky Add-To-Cart', false ),
				]
			),
			'smart_coupons'                 => apply_filters(
				'rtsb/module/smart_coupons/options',
				[
					'id'           => 'smart_coupons',
					'active'       => '',
					'title'        => esc_html__( 'Smart Coupons', 'shopbuilder' ),
					'package'      => $this->pro_package(),
					'active_field' => [
						'label' => esc_html__( 'Enable Smart Coupons?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable smart coupons module.', 'shopbuilder' ),
					],
					'fields'       => Fns::pro_version_notice( '1.10.0', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'bulk_discounts'                => apply_filters(
				'rtsb/module/bulk_discounts/options',
				[
					'id'           => 'bulk_discounts',
					'active'       => '',
					'title'        => esc_html__( 'Volume Discount', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Volume Discount?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable volume discount module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '1.10.0', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'bogo_bxgy_free_gift_discounts' => apply_filters(
				'rtsb/module/bogo_bxgy_free_gift_discounts/options',
				[
					'id'           => 'bogo_bxgy_free_gift_discounts',
					'active'       => '',
					'title'        => esc_html__( 'Buy X Get Y | BOGO', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Buy X Get Y | BOGO?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Buy X Get Y | BOGO module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '1.11.0', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'partial_pay'                   => apply_filters(
				'rtsb/module/partial_pay/options',
				[
					'id'           => 'partial_pay',
					'active'       => '',
					'title'        => esc_html__( 'Partial Pay', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Partial Pay?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable partial pay module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '1.12.0', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'gift_card'                     => apply_filters(
				'rtsb/module/gift_card/options',
				[
					'id'           => 'gift_card',
					'active'       => '',
					'title'        => esc_html__( 'Gift Card', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Gift Card?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Gift Card module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '1.10.0' ),
				]
			),
			'pdf_invoice'                   => apply_filters(
				'rtsb/module/pdf_invoice/options',
				[
					'id'           => 'pdf_invoice',
					'active'       => '',
					'title'        => esc_html__( 'PDF Invoice', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable PDF Invoice?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable PDF Invoice module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.0', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'extended_cross_sell'           => apply_filters(
				'rtsb/module/extended_cross_sell/options',
				[
					'id'           => 'extended_cross_sell',
					'active'       => '',
					'title'        => esc_html__( 'Extended Cross Sell', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Extended Cross Sell?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Extended Cross Sell.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.4', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'abandoned_cart_recovery'       => apply_filters(
				'rtsb/module/abandoned_cart_recovery/options',
				[
					'id'           => 'abandoned_cart_recovery',
					'active'       => '',
					'badge'        => 'new',
					'title'        => esc_html__( 'Abandoned Cart Recovery', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Abandoned Cart Recovery?', 'shopbuilder' ),
						'help'  => esc_html__( 'Cart will be considered abandoned if order is not completed in cut-off time.', 'shopbuilder' ),
					],
					'fields'       => CartRecoveryOptions::get_cart_recovery_fields(),
					'base_class'   => CartRecoveryInit::class,
					'tabs'         => [
						'general'        => [
							'title' => esc_html__( 'General', 'shopbuilder' ),
						],
						'emailTemplates' => [
							'title' => esc_html__( 'Email Templates', 'shopbuilder' ),
						],
					],
				]
			),
			'popup_builder'                 => apply_filters(
				'rtsb/module/popup_builder/options',
				[
					'id'           => 'popup_builder',
					'active'       => '',
					'title'        => esc_html__( 'Popup Builder', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Popup Builder?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Popup Builder.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.5', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'save_for_buy_later'            => apply_filters(
				'rtsb/module/save_for_buy_later/options',
				[
					'id'           => 'save_for_buy_later',
					'active'       => '',
					'title'        => esc_html__( 'Save Cart For Buy Later', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Save Cart For Buy Later?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Save Cart For Buy Later.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.8', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'catalog_mode'                  => apply_filters(
				'rtsb/module/catalog_mode/options',
				[
					'id'           => 'catalog_mode',
					'active'       => '',
					'title'        => esc_html__( 'Catalog Mode', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Catalog Mode?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Catalog Mode.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.9', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'back_in_stock_notifier'        => apply_filters(
				'rtsb/module/back_in_stock_notifier/options',
				[
					'id'           => 'back_in_stock_notifier',
					'active'       => '',
					'title'        => esc_html__( 'Back In Stock Notifier', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Back In Stock Notifier?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Back In Stock Notifier.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.11', 'general', 'ShopBuilder Pro', false ),
				]
			),
			'mega_menu'                     => apply_filters(
				'rtsb/module/mega_menu/options',
				[
					'id'           => 'mega_menu',
					'active'       => '',
					'title'        => esc_html__( 'Mega Menu', 'shopbuilder' ),
					'active_field' => [
						'label' => esc_html__( 'Enable Mega Menu?', 'shopbuilder' ),
						'help'  => esc_html__( 'Switch on to enable Mega Menu module.', 'shopbuilder' ),
					],
					'package'      => $this->pro_package(),
					'fields'       => Fns::pro_version_notice( '2.1.12', 'general', 'ShopBuilder Pro', false ),
				]
			),
		];
		// Inject icons, descriptions and links per module.
		$meta = self::module_meta();
		foreach ( $list as $key => &$item ) {
			if ( isset( $meta[ $key ] ) ) {
				$item['icon']        = SvgIcons::get( $meta[ $key ]['icon'] ?? 'default' );
				$item['description'] = $meta[ $key ]['desc'] ?? '';
			} else {
				$item['icon']        = SvgIcons::get( 'default' );
				$item['description'] = '';
			}
			$item['links'] = [
				'doc'   => '',
				'video' => '',
			];
		}
		unset( $item );

		return apply_filters( 'rtsb/core/modules/raw_list', $list );
	}

	/**
	 * Module icon and description metadata.
	 *
	 * @return array<string, array{icon: string, desc: string}>
	 */
	private static function module_meta() {
		return [
			'variation_swatches'            => [
				'icon' => 'dots',
				'desc' => 'Replace dropdowns with color, image, label, or radio swatches.',
			],
			'variation_gallery'             => [
				'icon' => 'image',
				'desc' => 'Show a unique product gallery for every variation.',
			],
			'quick_view'                    => [
				'icon' => 'eye',
				'desc' => 'Open a popup product preview from grids without a full page load.',
			],
			'wishlist'                      => [
				'icon' => 'heart',
				'desc' => 'Let customers save products to a wishlist they can revisit or share.',
			],
			'compare'                       => [
				'icon' => 'flip_box',
				'desc' => 'Side-by-side product attribute comparison with sticky header.',
			],
			'product_badges'                => [
				'icon' => 'onsale',
				'desc' => 'Sale, new, featured, low-stock, and custom badges with rules.',
			],
			'checkout_fields_editor'        => [
				'icon' => 'form',
				'desc' => 'Add, edit, reorder, or remove billing, shipping, and checkout fields.',
			],
			'shopify_checkout'              => [
				'icon' => 'cart',
				'desc' => 'Single-page Shopify-style checkout with autofill and express pay.',
			],
			'mini_cart'                     => [
				'icon' => 'cart',
				'desc' => 'Sliding cart drawer with AJAX add-to-cart and quantity edits.',
			],
			'product_size_chart'            => [
				'icon' => 'ruler',
				'desc' => 'Per-category size guides with units, conversion, and modal preview.',
			],
			'customize_my_account'          => [
				'icon' => 'user',
				'desc' => 'Build the customer dashboard with custom tabs, fields, and layouts.',
			],
			'pre_order'                     => [
				'icon' => 'countdown',
				'desc' => 'Accept orders before launch with deposit and scheduled release.',
			],
			'currency_switcher'             => [
				'icon' => 'currency',
				'desc' => 'Geo-detected switcher with live exchange rates and pricing rules.',
			],
			'product_add_ons'               => [
				'icon' => 'grid',
				'desc' => 'Optional extras like gift wrap and warranties with conditional pricing.',
			],
			'sales_notification'            => [
				'icon' => 'notification',
				'desc' => 'Real-time sales popups to create social proof and urgency.',
			],
			'flash_sale_countdown'          => [
				'icon' => 'flash',
				'desc' => 'Scheduled flash sale countdown timers with auto-pricing.',
			],
			'quick_checkout'                => [
				'icon' => 'checkout',
				'desc' => 'One-click checkout from product pages, skipping the cart.',
			],
			'multi_step_checkout'           => [
				'icon' => 'multi_step',
				'desc' => 'Break checkout into guided steps with progress indicator.',
			],
			'back_order'                    => [
				'icon' => 'document',
				'desc' => 'Allow orders on out-of-stock products with availability dates.',
			],
			'sticky_add_to_cart'            => [
				'icon' => 'button',
				'desc' => 'Sticky add-to-cart bar that follows the user on product pages.',
			],
			'smart_coupons'                 => [
				'icon' => 'discount',
				'desc' => 'Advanced coupon rules, URL coupons, and auto-apply conditions.',
			],
			'bulk_discounts'                => [
				'icon' => 'pricing',
				'desc' => 'Tiered quantity-based pricing tables for bulk purchases.',
			],
			'bogo_bxgy_free_gift_discounts' => [
				'icon' => 'star',
				'desc' => 'Buy-one-get-one, buy-X-get-Y, and free gift promotions.',
			],
			'partial_pay'                   => [
				'icon' => 'payment',
				'desc' => 'Allow customers to pay in installments or deposits.',
			],
			'gift_card'                     => [
				'icon' => 'coupon',
				'desc' => 'Digital gift cards with email delivery and balance management.',
			],
			'pdf_invoice'                   => [
				'icon' => 'note',
				'desc' => 'Auto-generated PDF invoices and packing slips for orders.',
			],
			'extended_cross_sell'           => [
				'icon' => 'grid',
				'desc' => 'Extended cross-sell recommendations across cart and checkout.',
			],
			'abandoned_cart_recovery'       => [
				'icon' => 'cart',
				'desc' => 'Automated email nudges, discount triggers, and recovery analytics.',
			],
			'popup_builder'                 => [
				'icon' => 'popup',
				'desc' => 'Create promotional popups with triggers, targeting, and templates.',
			],
			'save_for_buy_later'            => [
				'icon' => 'heart',
				'desc' => 'Save cart items for later purchase with one-click restore.',
			],
			'catalog_mode'                  => [
				'icon' => 'list',
				'desc' => 'Hide prices and add-to-cart buttons for catalog-only browsing.',
			],
			'back_in_stock_notifier'        => [
				'icon' => 'notification',
				'desc' => 'Email notifications when out-of-stock products become available.',
			],
			'mega_menu'                     => [
				'icon' => 'grid',
				'desc' => 'Multi-column mega menus with widgets, images, and icons.',
			],
		];
	}
	/**
	 * Get All Menu
	 */
	public function get_all_menu() {
		$manus     = [
			'none' => esc_html__( 'None', 'shopbuilder' ),
		];
		$nav_menus = wp_get_nav_menus();
		if ( empty( $nav_menus ) ) {
			return $manus;
		}
		foreach ( $nav_menus as $menu ) {
			 $manus[ $menu->slug ] = $menu->name;
		}
		return $manus;
	}
	/**
	 * Sticky add-to-cart module options.
	 *
	 * @return array
	 */
	public function shopify_checkout_options() {
		$notice = [];
		if ( defined( 'RTSBPRO_VERSION' ) && version_compare( RTSBPRO_VERSION, '1.8.0', '<' ) ) {
			$notice = Fns::pro_version_notice( '1.8.0', 'general', 'ShopBuilder Pro', false );
		}
		$fields = $notice + [
			'general_settings'                      => [
				'id'    => 'general_settings',
				'type'  => 'title',
				'label' => esc_html__( 'Settings', 'shopbuilder' ),
				'tab'   => 'general',
			],
			'shopify_page_logo'                     => [
				'id'    => 'shopify_page_logo',
				'type'  => 'fileupload',
				'label' => esc_html__( 'Upload Checkout Page Logo', 'shopbuilder' ),
				'help'  => esc_html__( 'Please upload your custom logo to display on the checkout page.', 'shopbuilder' ),
				'tab'   => 'general',
			],
			'shopify_logo_height'                   => [
				'id'    => 'shopify_logo_height',
				'label' => esc_html__( 'Logo Height (px)', 'shopbuilder' ),
				'help'  => esc_html__( 'Specify the height of the checkout page logo in px.', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 200,
				'unit'  => 'px',
				'value' => 40,
				'tab'   => 'general',
			],
			'cart_icon_source'                      => [
				'id'      => 'cart_icon_source',
				'label'   => esc_html__( 'Cart Icon', 'shopbuilder' ),
				'help'    => sprintf(
				// translators: %s for pro message.
					__( 'Choose a cart icon to display in your checkout\'s header. <br />%s', 'shopbuilder' ),
					! rtsb()->has_pro()
						? sprintf(
							'<a target="_blank" href="' . esc_url( rtsb()->pro_version_link() ) . '">%s </a> %s ',
							esc_html__( 'Upgrade to PRO', 'shopbuilder' ),
							esc_html__( 'to unlock custom image icon.', 'shopbuilder' )
						)
						: ''
				),
				'type'    => 'select',
				'value'   => 'none',
				'isPro'   => ! rtsb()->has_pro(),
				'options' => [
					'none'        => esc_html__( 'None', 'shopbuilder' ),
					'select_icon' => esc_html__( 'Select Icon', 'shopbuilder' ),
					'upload_icon' => esc_html__( 'Upload Icon', 'shopbuilder' ),
				],
				'tab'     => 'general',
			],
			'cart_icon'                             => [
				'id'         => 'cart_icon',
				'label'      => esc_html__( 'Select Cart Icon', 'shopbuilder' ),
				'help'       => esc_html__( 'Please choose the icon.', 'shopbuilder' ),
				'type'       => 'icon_select',
				'searchable' => true,
				'options'    => Fns::get_icons(),
				'value'      => 'cart-2',
				'tab'        => 'general',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],

			'cart_image_icon'                       => [
				'id'         => 'cart_image_icon',
				'type'       => 'fileupload',
				'label'      => esc_html__( 'Upload Custom Icon', 'shopbuilder' ),
				'help'       => esc_html__( 'Please upload your custom image icon.', 'shopbuilder' ),
				'tab'        => 'general',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'upload_icon',
							'operator' => '==',
						],
					],
				],
			],

			'email_field_title'                     => [
				'id'    => 'email_field_title',
				'type'  => 'text',
				'label' => esc_html__( 'Email Field Section Title', 'shopbuilder' ),
				'help'  => esc_html__( 'Enter the custom title to be displayed above the email field section.', 'shopbuilder' ),
				'value' => esc_html__( 'Contact', 'shopbuilder' ),
				'tab'   => 'general',
			],

			'billing_address_field_title'           => [
				'id'    => 'billing_address_field_title',
				'type'  => 'text',
				'label' => esc_html__( 'Billing Fields Section Title', 'shopbuilder' ),
				'help'  => esc_html__( 'Enter the custom title to be displayed above the billing fields section', 'shopbuilder' ),
				'value' => esc_html__( 'Billing address', 'shopbuilder' ),
				'tab'   => 'general',
			],

			'enable_multi_step'                     => [
				'id'    => 'enable_multi_step',
				'type'  => 'switch',
				'isPro' => ! rtsb()->has_pro(),
				'label' => esc_html__( 'Multi-Step Checkout', 'shopbuilder' ),
				'help'  => esc_html__( 'Enable multi-step checkout to simplify the purchasing process.', 'shopbuilder' ),
				'tab'   => 'multi-step',
			],
			'step_menu_settings'                    => [
				'id'         => 'step_menu_settings',
				'type'       => 'title',
				'label'      => esc_html__( 'Step Settings ', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'show_step_menu'                        => [
				'id'         => 'show_step_menu',
				'type'       => 'switch',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Show Step Menu', 'shopbuilder' ),
				'help'       => esc_html__( 'Enable to display the step navigation menu during checkout.', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'value'      => 'on',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'show_cart_menu'                        => [
				'id'         => 'show_cart_menu',
				'type'       => 'switch',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Show Cart Menu', 'shopbuilder' ),
				'help'       => esc_html__( 'Enable this option to display the cart item in the step menu.', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'cart_text'                             => [
				'id'         => 'cart_text',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Cart Step Title', 'shopbuilder' ),
				'help'       => esc_html__( 'Enter the cart step menu title.', 'shopbuilder' ),
				'value'      => esc_html__( 'Cart', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_cart_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'billing_text'                          => [
				'id'         => 'billing_text',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Billing Step Title', 'shopbuilder' ),
				'help'       => esc_html__( 'Enter the billing step menu title.', 'shopbuilder' ),
				'value'      => esc_html__( 'Billing', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'shipping_text'                         => [
				'id'         => 'shipping_text',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Shipping Step Title', 'shopbuilder' ),
				'help'       => esc_html__( 'Enter the shipping step menu title.', 'shopbuilder' ),
				'value'      => esc_html__( 'Shipping', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'shipping_method_text'                  => [
				'id'         => 'shipping_method_text',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Shipping Method Step Title', 'shopbuilder' ),
				'help'       => esc_html__( 'Enter the shipping method step menu title.', 'shopbuilder' ),
				'value'      => esc_html__( 'Shipping Method ', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'payment_text'                          => [
				'id'         => 'payment_text',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Payment Method Step Title', 'shopbuilder' ),
				'help'       => esc_html__( 'Enter the payment method step menu title.', 'shopbuilder' ),
				'value'      => esc_html__( 'Payment', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'next_button_settings'                  => [
				'id'         => 'next_button_settings',
				'type'       => 'title',
				'label'      => esc_html__( 'Button Settings', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'shop_continue_btn'                     => [
				'id'         => 'shop_continue_btn',
				'type'       => 'switch',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Show Next Button', 'shopbuilder' ),
				'help'       => esc_html__( 'Enable this option to display the \'Next\' button.', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'value'      => 'on',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'continue_btn_text_for_shipping'        => [
				'id'         => 'continue_btn_text_for_shipping',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Next Button Label for Shipping', 'shopbuilder' ),
				'value'      => esc_html__( 'Next', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'continue_btn_text_for_shipping_method' => [
				'id'         => 'continue_btn_text_for_shipping_method',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Next Button Label for Shipping Method', 'shopbuilder' ),
				'value'      => esc_html__( 'Next', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'continue_btn_text_for_payment'         => [
				'id'         => 'continue_btn_text_for_payment',
				'type'       => 'text',
				'label'      => esc_html__( 'Next Button Label for Payment', 'shopbuilder' ),
				'value'      => esc_html__( 'Next', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'shop_return_btn'                       => [
				'id'         => 'shop_return_btn',
				'type'       => 'switch',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Show Back Button', 'shopbuilder' ),
				'help'       => esc_html__( 'Enable this option to display the \'Back\' button.', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'value'      => 'on',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'return_btn_text_for_billing'           => [
				'id'         => 'return_btn_text_for_billing',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Back Button Label for Billing', 'shopbuilder' ),
				'value'      => esc_html__( 'Prev', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'return_btn_text_for_shipping'          => [
				'id'         => 'return_btn_text_for_shipping',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Back Button Label for Shipping', 'shopbuilder' ),
				'value'      => esc_html__( 'Prev', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'return_btn_text_for_shipping_method'   => [
				'id'         => 'return_btn_text_for_shipping_method',
				'type'       => 'text',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Back Button Label for Shipping Method', 'shopbuilder' ),
				'value'      => esc_html__( 'Prev', 'shopbuilder' ),
				'tab'        => 'multi-step',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],

			'order_review_settings'                 => [
				'id'    => 'order_review_settings',
				'type'  => 'title',
				'label' => esc_html__( 'Order Reviews', 'shopbuilder' ),
				'tab'   => 'general',
			],
			'order_review_item_link'                => [
				'id'    => 'order_review_item_link',
				'type'  => 'switch',
				'label' => esc_html__( 'Link Product Titles', 'shopbuilder' ),
				'help'  => esc_html__( 'Enable to make product titles clickable, linking to their product pages.', 'shopbuilder' ),
				'value' => '',
				'tab'   => 'general',
			],
			'extra_content'                         => [
				'id'          => 'extra_content',
				'label'       => esc_html__( 'Display Additional Data', 'shopbuilder' ),
				'help'        => esc_html__( 'Enter custom text to display below the Order Review section. You can include custom HTML content. Shortcodes are also supported here.', 'shopbuilder' ),
				'type'        => 'text_editor',
				'isPro'       => ! rtsb()->has_pro(),
				'sanitize_fn' => 'pass_all',
				'tab'         => 'general',
			],

			'footer_settings'                       => [
				'id'    => 'footer_settings',
				'type'  => 'title',
				'label' => esc_html__( 'Footer', 'shopbuilder' ),
				'tab'   => 'general',
			],

			'footer_menu'                           => [
				'id'      => 'footer_menu',
				'label'   => esc_html__( 'Footer Menu', 'shopbuilder' ),
				'help'    => esc_html__( 'Select a menu to display in the footer section of your checkout page.', 'shopbuilder' ),
				'type'    => 'select',
				'value'   => 'none',
				'options' => $this->get_all_menu(),
				'tab'     => 'general',
			],

			'footer_text'                           => [
				'id'    => 'footer_text',
				'type'  => 'text',
				'label' => esc_html__( 'Footer Text', 'shopbuilder' ),
				'help'  => esc_html__( 'Enter custom text to display in the footer section of your checkout page. You can use {year} placeholder to display the current year.', 'shopbuilder' ),
				'value' => esc_html__( 'Copyright @{year} | All rights reserved', 'shopbuilder' ),
				'tab'   => 'general',
			],

			// Place Order.
			'login_button_styles_settings'          => [
				'id'    => 'login_button_styles_settings',
				'type'  => 'title',
				'tab'   => 'btn-styles',
				'label' => esc_html__( 'Login Button Style', 'shopbuilder' ),
			],
			'login_button_height'                   => [
				'id'    => 'login_button_height',
				'label' => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 100,
				'unit'  => 'px',
				'value' => 50,
				'tab'   => 'btn-styles',
			],
			'login_button_width'                    => [
				'id'    => 'login_button_width',
				'label' => esc_html__( 'Width (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 1000,
				'unit'  => 'px',
				'tab'   => 'btn-styles',
			],
			'login_button_font_size'                => [
				'id'    => 'login_button_font_size',
				'label' => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 50,
				'unit'  => 'px',
				'value' => 15,
				'tab'   => 'btn-styles',
			],
			'login_button_bg_color'                 => [
				'id'    => 'login_button_bg_color',
				'label' => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'login_button_hover_bg_color'           => [
				'id'    => 'login_button_hover_bg_color',
				'label' => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'login_button_color'                    => [
				'id'    => 'login_button_color',
				'label' => esc_html__( 'Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'login_button_hover_color'              => [
				'id'    => 'login_button_hover_color',
				'label' => esc_html__( 'Hover Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'login_button_border_color'             => [
				'id'    => 'login_button_border_color',
				'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'login_button_hover_border_color'       => [
				'id'    => 'login_button_hover_border_color',
				'label' => esc_html__( 'Hover Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],

			// Place Order.
			'place_order_button_styles_settings'    => [
				'id'    => 'place_order_button_styles_settings',
				'type'  => 'title',
				'tab'   => 'btn-styles',
				'label' => esc_html__( 'Place Order Button Style', 'shopbuilder' ),
			],
			'place_order_button_height'             => [
				'id'    => 'place_order_button_height',
				'label' => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 100,
				'unit'  => 'px',
				'value' => 50,
				'tab'   => 'btn-styles',
			],
			'place_order_button_width'              => [
				'id'    => 'place_order_button_width',
				'label' => esc_html__( 'Width (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 1000,
				'unit'  => 'px',
				'tab'   => 'btn-styles',
			],
			'place_order_button_font_size'          => [
				'id'    => 'place_order_button_font_size',
				'label' => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 50,
				'unit'  => 'px',
				'value' => 15,
				'tab'   => 'btn-styles',
			],
			'place_order_button_bg_color'           => [
				'id'    => 'place_order_button_bg_color',
				'label' => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'place_order_button_hover_bg_color'     => [
				'id'    => 'place_order_button_hover_bg_color',
				'label' => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'place_order_button_color'              => [
				'id'    => 'place_order_button_color',
				'label' => esc_html__( 'Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'place_order_button_hover_color'        => [
				'id'    => 'place_order_button_hover_color',
				'label' => esc_html__( 'Hover Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'place_order_button_border_color'       => [
				'id'    => 'place_order_button_border_color',
				'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'place_order_button_hover_border_color' => [
				'id'    => 'place_order_button_hover_border_color',
				'label' => esc_html__( 'Hover Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],

			// Next Button.
			'next_button_styles_settings'           => [
				'id'         => 'next_button_styles_settings',
				'type'       => 'title',
				'tab'        => 'btn-styles',
				'label'      => esc_html__( 'Next Button Style', 'shopbuilder' ),
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_height'                    => [
				'id'         => 'next_button_height',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 100,
				'unit'       => 'px',
				'value'      => 50,
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_width'                     => [
				'id'         => 'next_button_width',
				'label'      => esc_html__( 'Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 1000,
				'unit'       => 'px',
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_font_size'                 => [
				'id'         => 'next_button_font_size',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 50,
				'unit'       => 'px',
				'value'      => 15,
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_bg_color'                  => [
				'id'         => 'next_button_bg_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_hover_bg_color'            => [
				'id'         => 'next_button_hover_bg_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_color'                     => [
				'id'         => 'next_button_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_hover_color'               => [
				'id'         => 'next_button_hover_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Hover Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_border_color'              => [
				'id'         => 'next_button_border_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Border Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'next_button_hover_border_color'        => [
				'id'         => 'next_button_hover_border_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Hover Border Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_continue_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_styles_settings'           => [
				'id'         => 'prev_button_styles_settings',
				'type'       => 'title',
				'tab'        => 'btn-styles',
				'label'      => esc_html__( 'Prev Button Style', 'shopbuilder' ),
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],

			],
			'prev_button_height'                    => [
				'id'         => 'prev_button_height',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 100,
				'unit'       => 'px',
				'value'      => 50,
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_width'                     => [
				'id'         => 'prev_button_width',
				'label'      => esc_html__( 'Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 1000,
				'unit'       => 'px',
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_font_size'                 => [
				'id'         => 'prev_button_font_size',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 50,
				'unit'       => 'px',
				'value'      => 15,
				'tab'        => 'btn-styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_bg_color'                  => [
				'id'         => 'prev_button_bg_color',
				'isPro'      => ! rtsb()->has_pro(),
				'label'      => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_hover_bg_color'            => [
				'id'         => 'prev_button_hover_bg_color',
				'label'      => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_color'                     => [
				'id'         => 'prev_button_color',
				'label'      => esc_html__( 'Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_hover_color'               => [
				'id'         => 'prev_button_hover_color',
				'label'      => esc_html__( 'Hover Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_border_color'              => [
				'id'         => 'prev_button_border_color',
				'label'      => esc_html__( 'Border Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'prev_button_hover_border_color'        => [
				'id'         => 'prev_button_hover_border_color',
				'label'      => esc_html__( 'Hover Border Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'btn-styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.shop_return_btn',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'coupon_button_styles_settings'         => [
				'id'    => 'coupon_button_styles_settings',
				'type'  => 'title',
				'tab'   => 'btn-styles',
				'label' => esc_html__( 'Apply Coupon Button Style', 'shopbuilder' ),
			],
			'coupon_height'                         => [
				'id'    => 'coupon_height',
				'label' => esc_html__( 'Coupon Field And Button Height (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 100,
				'unit'  => 'px',
				'value' => 50,
				'tab'   => 'btn-styles',
			],
			'coupon_button_font_size'               => [
				'id'    => 'coupon_button_font_size',
				'label' => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 50,
				'unit'  => 'px',
				'value' => 15,
				'tab'   => 'btn-styles',
			],
			'coupon_button_bg_color'                => [
				'id'    => 'coupon_button_bg_color',
				'label' => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'coupon_button_hover_bg_color'          => [
				'id'    => 'coupon_button_hover_bg_color',
				'label' => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'coupon_button_color'                   => [
				'id'    => 'coupon_button_color',
				'label' => esc_html__( 'Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'coupon_button_hover_color'             => [
				'id'    => 'coupon_button_hover_color',
				'label' => esc_html__( 'Hover Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'coupon_button_border_color'            => [
				'id'    => 'coupon_button_border_color',
				'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],
			'coupon_button_hover_border_color'      => [
				'id'    => 'coupon_button_hover_border_color',
				'label' => esc_html__( 'Hover Border Color', 'shopbuilder' ),
				'tab'   => 'btn-styles',
				'type'  => 'color',
			],

			'header_cart_icon_styles'               => [
				'id'         => 'header_cart_icon_styles',
				'type'       => 'title',
				'tab'        => 'styles',
				'label'      => esc_html__( 'Header Cart Icon Style', 'shopbuilder' ),
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'none',
							'operator' => '!=',
						],
					],
				],
			],

			'shopify_cart_icon_height'              => [
				'id'         => 'shopify_cart_icon_height',
				'label'      => esc_html__( 'Cart Icon Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 200,
				'unit'       => 'px',
				'value'      => 40,
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'upload_icon',
							'operator' => '==',
						],
					],
				],
			],

			'header_cart_icon_color'                => [
				'id'         => 'step_menu_color',
				'label'      => esc_html__( 'Color', 'shopbuilder' ),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'header_cart_icon_hover_color'          => [
				'id'         => 'header_cart_icon_hover_color',
				'label'      => esc_html__( 'Hover Color', 'shopbuilder' ),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'header_cart_icon_font_size'            => [
				'id'         => 'header_cart_icon_font_size',
				'label'      => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 50,
				'unit'       => 'px',
				'value'      => 15,
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_styles_settings'           => [
				'id'         => 'cart_button_styles_settings',
				'type'       => 'title',
				'tab'        => 'styles',
				'label'      => esc_html__( 'Cart Button Style', 'shopbuilder' ),
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_height'                    => [
				'id'         => 'cart_button_height',
				'label'      => esc_html__( 'Button Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 200,
				'unit'       => 'px',
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_width'                     => [
				'id'         => 'cart_button_width',
				'label'      => esc_html__( 'Button Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 200,
				'unit'       => 'px',
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_padding'                   => [
				'id'         => 'cart_button_padding',
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 0,
				'max'        => 50,
				'unit'       => 'px',
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_border_style'              => [
				'id'         => 'cart_button_border_style',
				'label'      => esc_html__( 'Border Style', 'shopbuilder' ),
				'type'       => 'select',
				'options'    => [
					'none'   => esc_html__( 'None', 'shopbuilder' ),
					'solid'  => esc_html__( 'Solid', 'shopbuilder' ),
					'dashed' => esc_html__( 'Dashed', 'shopbuilder' ),
					'dotted' => esc_html__( 'Dotted', 'shopbuilder' ),
				],
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_border_width'              => [
				'id'         => 'cart_button_border_width',
				'label'      => esc_html__( 'Border Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 0,
				'max'        => 10,
				'unit'       => 'px',
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_border_color'              => [
				'id'         => 'cart_button_border_color',
				'label'      => esc_html__( 'Border Color', 'shopbuilder' ),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_border_radius'             => [
				'id'         => 'cart_button_border_radius',
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'min'        => 0,
				'max'        => 100,
				'unit'       => 'px',
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_bg_color'                  => [
				'id'         => 'cart_button_bg_color',
				'label'      => esc_html__( 'Background Color', 'shopbuilder' ),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'cart_button_hover_bg_color'            => [
				'id'         => 'cart_button_hover_bg_color',
				'label'      => esc_html__( 'Hover Background Color', 'shopbuilder' ),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.cart_icon_source',
							'value'    => 'select_icon',
							'operator' => '==',
						],
					],
				],
			],
			'footer_menu_styles_settings'           => [
				'id'    => 'footer_menu_styles_settings',
				'type'  => 'title',
				'tab'   => 'styles',
				'label' => esc_html__( 'Footer Style', 'shopbuilder' ),
			],
			'footer_menu_color'                     => [
				'id'    => 'footer_menu_color',
				'label' => esc_html__( 'Menu Color', 'shopbuilder' ),
				'tab'   => 'styles',
				'type'  => 'color',
			],
			'footer_menu_hover_color'               => [
				'id'    => 'footer_menu_hover_color',
				'label' => esc_html__( 'Menu Hover Color', 'shopbuilder' ),
				'tab'   => 'styles',
				'type'  => 'color',
			],
			'footer_menu_font_size'                 => [
				'id'    => 'footer_menu_font_size',
				'label' => esc_html__( 'Menu Font Size (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 50,
				'unit'  => 'px',
				'value' => 15,
				'tab'   => 'styles',
			],
			'footer_text_size'                      => [
				'id'    => 'footer_text_size',
				'label' => esc_html__( 'Footer Text Font Size (px)', 'shopbuilder' ),
				'type'  => 'slider',
				'min'   => 10,
				'max'   => 50,
				'unit'  => 'px',
				'value' => 15,
				'tab'   => 'styles',
			],
			'footer_text_color'                     => [
				'id'    => 'footer_text_color',
				'label' => esc_html__( 'Footer Text Color', 'shopbuilder' ),
				'tab'   => 'styles',
				'type'  => 'color',
			],
			'step_menu_styles_settings'             => [
				'id'         => 'step_menu_styles_settings',
				'type'       => 'title',
				'tab'        => 'styles',
				'label'      => esc_html__( 'Step Menu Style', 'shopbuilder' ),
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'step_menu_color'                       => [
				'id'         => 'step_menu_color',
				'label'      => esc_html__( 'Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'step_menu_hover_color'                 => [
				'id'         => 'step_menu_hover_color',
				'label'      => esc_html__( 'Hover Color', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'tab'        => 'styles',
				'type'       => 'color',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
			'step_menu_font_size'                   => [
				'id'         => 'step_menu_font_size',
				'label'      => esc_html__( 'Font Size (px)', 'shopbuilder' ),
				'isPro'      => ! rtsb()->has_pro(),
				'type'       => 'slider',
				'min'        => 10,
				'max'        => 50,
				'unit'       => 'px',
				'value'      => 15,
				'tab'        => 'styles',
				'dependency' => [
					'rules' => [
						[
							'item'     => 'modules.shopify_checkout.enable_multi_step',
							'value'    => 'on',
							'operator' => '==',
						],
						[
							'item'     => 'modules.shopify_checkout.show_step_menu',
							'value'    => 'on',
							'operator' => '==',
						],
					],
				],
			],
		];
		return $fields;
	}
	/**
	 * @return array
	 */
	public function product_badges_fields() {
		return apply_filters(
			'rtsb/module/product_badges/fields',
			[
				'hide_woocommerce_badge'            => [
					'id'    => 'hide_woocommerce_badge',
					'value' => '',
					'type'  => 'switch',
					'label' => esc_html__( 'Hide Theme Badges', 'shopbuilder' ),
					'help'  => esc_html__( 'Switch on to hide theme default badges.', 'shopbuilder' ),
					'tab'   => 'general',
				],
				// Hide "On sale".
				'hide_on_sale'                      => [
					'id'         => 'hide_on_sale',
					'type'       => 'select',
					'value'      => 'all_products',
					'isPro'      => ! rtsb()->has_pro(),
					'label'      => esc_html__( 'Applicable Products', 'shopbuilder' ),
					'options'    => [
						'all_products'               => esc_html__( 'All products', 'shopbuilder' ),
						'where_custom_badge_applied' => esc_html__( 'Only for products which have custom badge enabled', 'shopbuilder' ),
					],
					'help'       => esc_html__( 'Choose where to hide the default badges.', 'shopbuilder' ),
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.hide_woocommerce_badge',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],

				'group_badge_display_as'            => [
					'id'      => 'group_badge_display_as',
					'type'    => 'select',
					'value'   => 'horizontal',
					'isPro'   => ! rtsb()->has_pro(),
					'label'   => esc_html__( 'Group Badge Display Type', 'shopbuilder' ),
					'help'    => sprintf(
					// translators: %s for pro message.
						esc_html__( 'Please choose the group display type. %s', 'shopbuilder' ),
						! rtsb()->has_pro()
							? sprintf(
								'<a target="_blank" href="' . esc_url( rtsb()->pro_version_link() ) . '">%s </a> %s ',
								esc_html__( 'Upgrade to PRO', 'shopbuilder' ),
								esc_html__( 'for vertical layout.', 'shopbuilder' )
							)
							: ''
					),
					'options' => [
						'horizontal' => esc_html__( 'Horizontal', 'shopbuilder' ),
						'vertical'   => esc_html__( 'Vertical', 'shopbuilder' ),
					],
					'tab'     => 'group',
				],
				'group_badge_gap'                   => [
					'id'    => 'group_badge_gap',
					'type'  => 'text',
					'value' => '10px',
					'label' => esc_html__( 'Group Badge Gap/Spacing', 'shopbuilder' ),
					'help'  => esc_html__( 'Example: 10px', 'shopbuilder' ),
					'tab'   => 'group',
				],
				'general_options'                   => [
					'id'    => 'general_options',
					'type'  => 'title',
					'label' => esc_html__( 'Shop Page Settings', 'shopbuilder' ),
					'tab'   => 'group',
				],
				'loop_group_position'               => [
					'id'      => 'loop_group_position',
					'type'    => 'select',
					'value'   => 'above_image',
					'isPro'   => ! rtsb()->has_pro(),
					'label'   => esc_html__( 'Shop Page Group Position', 'shopbuilder' ),
					'help'    => esc_html__( 'Choose the group badges position in Shop page.', 'shopbuilder' ),
					'options' => [
						'above_image'          => esc_html__( 'Floating Above Image', 'shopbuilder' ),
						'before_product_title' => esc_html__( 'Before Product Title', 'shopbuilder' ),
						'after_product_title'  => esc_html__( 'After Product Title', 'shopbuilder' ),
						'before_add_to_cart'   => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
						'after_add_to_cart'    => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
						'custom'               => esc_html__( 'Custom Position', 'shopbuilder' ),
					],
					'tab'     => 'group',
				],
				'loop_custom_hook_name'             => [
					'id'         => 'loop_custom_hook_name',
					'type'       => 'text',
					'label'      => esc_html__( 'Enter Hook Name', 'shopbuilder' ),
					'tab'        => 'group',
					'help'       => sprintf(
					/* translators: 1: The shortcode.*/
						__( 'Or Copy the php code <br />%1$s<br /> and paste it in your product query where you want to show the button.', 'shopbuilder' ),
						"<code>&lt;?php do_action( 'rtsb/modules/product_badges/frontend/display' ); ?&gt;</code> OR Use shortcode <code>&lt;?php echo do_shortcode( '[rtsb_badges]' ); ?&gt;</code>"
					),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.loop_group_position',
								'value'    => 'custom',
								'operator' => '==',
							],
						],
					],
				],
				'group_position_hook_priority'      => [
					'id'         => 'group_position_hook_priority',
					'type'       => 'number',
					'size'       => 'small',
					'min'        => 0,
					'max'        => 999,
					'label'      => esc_html__( 'Badge Position Priority', 'shopbuilder' ),
					'help'       => esc_html__( 'It\'s depend on your theme functionality. Example: 20', 'shopbuilder' ),
					'tab'        => 'group',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.loop_group_position',
								'value'    => 'custom',
								'operator' => '==',
							],
						],
					],
				],
				'group_badge_position'              => [
					'id'         => 'group_badge_position',
					'label'      => esc_html__( 'Position Above Image', 'shopbuilder' ),
					'type'       => 'text_select',
					'value'      => 'top-left',
					'options'    => [
						'top-left'     => esc_html__( 'Top Left', 'shopbuilder' ),
						'top-right'    => esc_html__( 'Top Right', 'shopbuilder' ),
						'bottom-left'  => esc_html__( 'Bottom Left', 'shopbuilder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'shopbuilder' ),
					],
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.loop_group_position',
								'value'    => 'above_image',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'group',
				],
				'product_options'                   => [
					'id'    => 'product_options',
					'type'  => 'title',
					'label' => esc_html__( 'Product Page Settings', 'shopbuilder' ),
					'tab'   => 'group',
				],
				'product_page_group_position'       => [
					'id'      => 'product_page_group_position',
					'type'    => 'select',
					'value'   => 'above_image',
					'isPro'   => ! rtsb()->has_pro(),
					'label'   => esc_html__( 'Product Page Group Position', 'shopbuilder' ),
					'help'    => esc_html__( 'Choose the group badges position in product page.', 'shopbuilder' ),
					'options' => [
						'above_image'        => esc_html__( 'Floating Above Image', 'shopbuilder' ),
						'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
						'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
						'after_summary'      => esc_html__( 'After Summary', 'shopbuilder' ),
						'after_short_desc'   => esc_html__( 'After Short Description', 'shopbuilder' ),
						'shortcode'          => esc_html__( 'Use Shortcode', 'shopbuilder' ),
						'custom'             => esc_html__( 'Custom Position', 'shopbuilder' ),
					],
					'tab'     => 'group',
				],
				'product_page_badges_shortcode'     => [
					'type'       => 'raw',
					'label'      => ' ',
					'html'       => sprintf(
					/* translators: 1: The shortcode.*/
						esc_html__( 'Choose where to show button on the product page. Copy this shortcode %1$s and paste it where you want to show the button.', 'shopbuilder' ),
						'<code>[rtsb_badges]</code>'
					),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.product_page_group_position',
								'value'    => 'shortcode',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'group',
				],
				'product_page_custom_hook_name'     => [
					'id'         => 'product_page_custom_hook_name',
					'type'       => 'text',
					'label'      => esc_html__( 'Enter Hook Name', 'shopbuilder' ),
					'tab'        => 'group',
					'help'       => sprintf(
					/* translators: 1: The shortcode.*/
						__( 'Or Copy the php code <br />%1$s<br /> and paste it in your product query where you want to show the button.', 'shopbuilder' ),
						"<code>&lt;?php do_action( 'rtsb/modules/product_badges/frontend/display' ); ?&gt;</code>"
					),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.product_page_group_position',
								'value'    => 'custom',
								'operator' => '==',
							],
						],
					],
				],
				'product_page_group_hook_priority'  => [
					'id'         => 'product_page_group_hook_priority',
					'type'       => 'number',
					'size'       => 'small',
					'min'        => 0,
					'max'        => 999,
					'label'      => esc_html__( 'Badge Position Priority', 'shopbuilder' ),
					'help'       => esc_html__( 'It\'s depend on your theme functionality. Example: 20', 'shopbuilder' ),
					'tab'        => 'group',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.product_page_group_position',
								'value'    => 'custom',
								'operator' => '==',
							],
						],
					],

				],
				'product_page_group_badge_position' => [
					'id'         => 'product_page_group_badge_position',
					'label'      => esc_html__( 'Position Above Image', 'shopbuilder' ),
					'type'       => 'text_select',
					'value'      => 'top-left',
					'options'    => [
						'top-left'     => esc_html__( 'Top Left', 'shopbuilder' ),
						'top-right'    => esc_html__( 'Top Right', 'shopbuilder' ),
						'bottom-left'  => esc_html__( 'Bottom Left', 'shopbuilder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'shopbuilder' ),
					],
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.product_badges.product_page_group_position',
								'value'    => 'above_image',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'group',
				],

				'badges_field_intro'                => [
					'id'   => 'badges_field_intro',
					'type' => 'description',
					'text' => esc_html__( 'To add new product badges, simply click on the \'Add New\' button below.', 'shopbuilder' ),
					'tab'  => 'badges',
				],
				'badges_field'                      => [
					'id'     => 'badges_field',
					'type'   => 'repeaters',
					'label'  => '',
					'tab'    => 'badges',
					'repeat' => [
						'title'                    => [
							'id'          => 'title',
							'label'       => esc_html__( 'Badge Name', 'shopbuilder' ),
							'help'        => esc_html__( 'Enter badge name.', 'shopbuilder' ),
							'type'        => 'text',
							'placeholder' => esc_html__( 'Badge Name', 'shopbuilder' ),
							'value'       => esc_html__( 'Badge Name', 'shopbuilder' ),
						],
						'badge_condition'          => [
							'id'      => 'badge_condition',
							'type'    => 'select',
							'value'   => 'dynamic',
							'isPro'   => ! rtsb()->has_pro(),
							'label'   => esc_html__( 'Badge Type', 'shopbuilder' ),
							'help'    => sprintf(
							// translators: %s for pro message.
								esc_html__( 'Select badge type. %s', 'shopbuilder' ),
								! rtsb()->has_pro()
									? sprintf(
										'<a target="_blank" href="' . esc_url( rtsb()->pro_version_link() ) . '">%s</a> %s',
										esc_html__( 'Upgrade to PRO', 'shopbuilder' ),
										esc_html__( 'unlock custom badge creation.', 'shopbuilder' )
									)
									: ''
							),
							'options' => [
								'dynamic' => esc_html__( 'Dynamic', 'shopbuilder' ),
								'custom'  => esc_html__( 'Custom', 'shopbuilder' ),
							],
						],
						'badge_for'                => [
							'id'         => 'badge_for',
							'type'       => 'select',
							'value'      => 'on_sale',
							'label'      => esc_html__( 'Dynamic Badge Condition', 'shopbuilder' ),
							'help'       => esc_html__( 'Specify dynamic badge condition', 'shopbuilder' ),
							'options'    => $this->dynamic_badge_list(),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badge_condition',
										'value'    => 'dynamic',
										'operator' => '==',
									],
								],
							],
						],
						'badge_for_pre_order'      => [
							'type'       => 'raw',
							'label'      => ' ',
							'html'       => esc_html__( 'Please ensure that Pre-Order module is activated and properly configured.', 'shopbuilder' ),
							'tab'        => 'general',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badge_condition',
										'value'    => 'dynamic',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badge_for',
										'value'    => 'pre_order',
										'operator' => '==',
									],
								],
							],
						],
						'minimum_sale_count'       => [
							'id'         => 'minimum_sale_count',
							'type'       => 'number',
							'label'      => esc_html__( 'Minimum Sale Count', 'shopbuilder' ),
							'help'       => esc_html__( 'Enter the number of Sale you want to designate for products to be labeled as \'Best Selling.\'', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badge_condition',
										'value'    => 'dynamic',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badge_for',
										'value'    => 'best_selling',
										'operator' => '==',
									],
								],
							],
						],
						'new_arrival_days'         => [
							'id'         => 'new_arrival_days',
							'type'       => 'number',
							'label'      => esc_html__( 'Specify Number of Days', 'shopbuilder' ),
							'help'       => esc_html__( 'Enter the number of days you want to designate for products to be labeled as \'New Arrivals.\'', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badge_for',
										'value'    => 'new_arrival',
										'operator' => '==',
									],
								],
							],
						],
						'badges_type'              => [
							'id'      => 'badges_type',
							'type'    => 'select',
							'value'   => 'text',
							'isPro'   => ! rtsb()->has_pro(),
							'label'   => esc_html__( 'Show Badge As:', 'shopbuilder' ),
							'help'    => sprintf(
							// translators: %s for pro message.
								esc_html__( 'Specify the badge display as Image Or Text. %s', 'shopbuilder' ),
								! rtsb()->has_pro()
									? sprintf(
										'<a target="_blank" href="' . esc_url( rtsb()->pro_version_link() ) . '">%s</a> %s',
										esc_html__( 'Upgrade to PRO', 'shopbuilder' ),
										esc_html__( 'unlock Image badge creation.', 'shopbuilder' )
									)
									: ''
							),
							'options' => [
								'image' => esc_html__( 'Image', 'shopbuilder' ),
								'text'  => esc_html__( 'Text', 'shopbuilder' ),
							],
						],

						'badge_preset'             => [
							'id'         => 'badge_preset',
							'label'      => esc_html__( 'Text Badge Preset', 'shopbuilder' ),
							'help'       => esc_html__( 'Choose the badge appearance', 'shopbuilder' ),
							'type'       => 'image_select',
							'value'      => 'preset1',
							'options'    => [
								'preset1' => [
									'label' => esc_html__( 'Preset 1', 'shopbuilder' ),
									'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-1.png' ) ),
								],

								'preset2' => [
									'label' => esc_html__( 'Preset 2', 'shopbuilder' ),
									'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-2.png' ) ),
								],

								'preset3' => [
									'label' => esc_html__( 'Preset 3', 'shopbuilder' ),
									'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-3.png' ) ),
								],

								'preset4' => [
									'title' => esc_html__( 'Preset 4', 'shopbuilder' ),
									'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-4.png' ) ),
								],
							],
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'badges_text'              => [
							'id'          => 'badges_text',
							'label'       => esc_html__( 'Enter Badge Text', 'shopbuilder' ),
							'help'        => esc_html__( 'The badge text will be displayed when the dynamic percentage switch is turned off.', 'shopbuilder' ),
							'type'        => 'text',
							'placeholder' => 'Badge Text',
							'value'       => esc_html__( 'New', 'shopbuilder' ),
							'dependency'  => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'show_badges_percent_text' => [
							'id'         => 'show_badges_percent_text',
							'type'       => 'switch',
							'label'      => esc_html__( 'Show Badges Percentage (%)', 'shopbuilder' ),
							'help'       => esc_html__( 'Switch on to show badge as dynamic percentage instead of text.', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badge_for',
										'value'    => 'on_sale',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badge_condition',
										'value'    => 'dynamic',
										'operator' => '==',
									],

								],
							],
						],

						'upload_image'             => [
							'id'         => 'upload_image',
							'type'       => 'fileupload',
							'label'      => esc_html__( 'Upload Image', 'shopbuilder' ),
							'help'       => esc_html__( 'Upload badge image.', 'shopbuilder' ),
							'tab'        => 'general',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'image',
										'operator' => '==',
									],
								],
							],
						],

						'apply_for'                => [
							'id'      => 'apply_for',
							'type'    => 'select',
							'value'   => 'product',
							'isPro'   => ! rtsb()->has_pro(),
							'label'   => esc_html__( 'Applicable For', 'shopbuilder' ),
							'options' => [
								'product'     => esc_html__( 'Products', 'shopbuilder' ),
								'product_cat' => esc_html__( 'Product Categories', 'shopbuilder' ),
							],
							'help'    => esc_html__( 'Badges will apply for selected products, or categories.', 'shopbuilder' ),
						],
						'applicable_products'      => [
							'id'              => 'applicable_products',
							'type'            => 'search_and_multi_select',
							'label'           => esc_html__( 'Applicable Products', 'shopbuilder' ),
							'help'            => esc_html__( 'Choose products to include. Leave blank to apply in all product.', 'shopbuilder' ),
							'placeholder'     => esc_html__( 'Search Products', 'shopbuilder' ),
							'func_with_param' => [ Fns::class, 'get_post_types', [ 'post_type' => 'product' ] ],
							'options'         => Fns::get_post_types( null, [ 'post_type' => 'product' ] ),
							'dependency'      => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.apply_for',
										'value'    => 'product',
										'operator' => '==',
									],
								],
							],
						],
						'applicable_categories'    => [
							'id'              => 'applicable_categories',
							'type'            => 'search_and_multi_select',
							'label'           => esc_html__( 'Applicable Categories', 'shopbuilder' ),
							'help'            => esc_html__( 'Choose categories to include. Leave blank to apply in all categories.', 'shopbuilder' ),
							'placeholder'     => esc_html__( 'Search Category', 'shopbuilder' ),
							'func_with_param' => [
								Fns::class,
								'products_category_query',
							],
							'options'         => Fns::products_category_query(),
							'dependency'      => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.apply_for',
										'value'    => 'product_cat',
										'operator' => '==',
									],
								],
							],
						],
						'exclude_product'          => [
							'id'              => 'exclude_product',
							'type'            => 'search_and_multi_select',
							'isPro'           => ! rtsb()->has_pro(),
							'label'           => esc_html__( 'Exclude Products', 'shopbuilder' ),
							'help'            => esc_html__( 'Choose products to exclude. Leave blank to exclude none.', 'shopbuilder' ),
							'placeholder'     => esc_html__( 'Search Products', 'shopbuilder' ),
							'func_with_param' => [ Fns::class, 'get_post_types', [ 'post_type' => 'product' ] ],
							'options'         => Fns::get_post_types( null, [ 'post_type' => 'product' ] ),
						],

						/**
						 * Style Settings
						 */
						'styles_settings'          => [
							'id'    => 'styles_settings',
							'type'  => 'title',
							'label' => esc_html__( 'Style Settings', 'shopbuilder' ),
						],
						'badge_text_color'         => [
							'id'         => 'badge_text_color',
							'label'      => esc_html__( 'Text Color', 'shopbuilder' ),
							'type'       => 'color',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'badge_bg_color'           => [
							'id'         => 'badge_bg_color',
							'label'      => esc_html__( 'Background Color', 'shopbuilder' ),
							'type'       => 'color',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badge_preset',
										'value'    => [ 'preset1', 'preset2' ],
										'operator' => 'in',
									],
								],
							],
						],
						'badge_border_color'       => [
							'id'         => 'badge_border_color',
							'label'      => esc_html__( 'Border Color', 'shopbuilder' ),
							'type'       => 'color',
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
									[
										'item'     => 'modules.product_badges.badges_field.badge_preset',
										'value'    => [ 'preset3', 'preset4' ],
										'operator' => 'in',
									],
								],
							],
						],
						'badge_font_size'          => [
							'id'         => 'badge_font_size',
							'type'       => 'text',
							'label'      => esc_html__( 'Font Size', 'shopbuilder' ),
							'help'       => esc_html__( 'Example: 14px', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'badge_font_width'         => [
							'id'         => 'badge_font_width',
							'type'       => 'select',
							'value'      => '400',
							'label'      => esc_html__( 'Font Weight', 'shopbuilder' ),
							'options'    => [
								'100'     => '100',
								'200'     => '200',
								'300'     => '300',
								'400'     => '400',
								'500'     => '500',
								'600'     => '600',
								'700'     => '700',
								'800'     => '800',
								'900'     => '900',
								'normal'  => 'normal',
								'bold'    => 'bold',
								'bolder'  => 'bolder',
								'lighter' => 'lighter',
								'initial' => 'initial',
								'inherit' => 'inherit',
							],
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'badge_width'              => [
							'id'         => 'badge_width',
							'type'       => 'text',
							'label'      => esc_html__( 'Width', 'shopbuilder' ),
							'help'       => esc_html__( 'Example: 150px', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'image',
										'operator' => '==',
									],
								],
							],
						],
						'badge_height'             => [
							'id'         => 'badge_height',
							'type'       => 'text',
							'label'      => esc_html__( 'Height', 'shopbuilder' ),
							'help'       => esc_html__( 'Example: 50px', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'image',
										'operator' => '==',
									],
								],
							],
						],
						'border_radius'            => [
							'id'         => 'border_radius',
							'type'       => 'text',
							'size'       => 'small',
							'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
							'help'       => esc_html__( 'Example: 20px 20px 20px 20px Or 20px', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
						'badge_padding'            => [
							'id'         => 'badge_padding',
							'type'       => 'text',
							'size'       => 'small',
							'label'      => esc_html__( 'Padding', 'shopbuilder' ),
							'help'       => esc_html__( 'Example: 10px 15px 10px 15px', 'shopbuilder' ),
							'dependency' => [
								'rules' => [
									[
										'item'     => 'modules.product_badges.badges_field.badges_type',
										'value'    => 'text',
										'operator' => '==',
									],
								],
							],
						],
					],
				],

			]
		);
	}

	/**
	 * Dynamic badge list.
	 *
	 * @return array
	 */
	public function dynamic_badge_list() {
		$items = [
			'on_sale'      => esc_html__( 'Products On Sale', 'shopbuilder' ),
			'featured'     => esc_html__( 'Featured Products', 'shopbuilder' ),
			'out_of_stock' => esc_html__( 'Out Of Stock Products', 'shopbuilder' ),
			'best_selling' => esc_html__( 'Best Selling Products', 'shopbuilder' ),
			'new_arrival'  => esc_html__( 'New Arrival Products', 'shopbuilder' ),
		];

		if ( rtsb()->has_pro() && method_exists( FnsPro::class, 'is_module_active' ) && FnsPro::is_module_active( 'pre_order' ) ) {
			$items['pre_order'] = esc_html__( 'Pre-Order Products', 'shopbuilder' );
		}

		return $items;
	}
	/**
	 * @return array
	 */
	public function get_variation_swatches_fields() {
		return apply_filters(
			'rtsb/module/variation_swatches/fields',
			[
				'show_tooltip'                      => [
					'id'    => 'show_tooltip',
					'value' => 'on',
					'type'  => 'switch',
					'label' => esc_html__( 'Enable Tooltip', 'shopbuilder' ),
					'help'  => esc_html__( 'Enable / Disable plugin default tooltip on each product attribute.', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'shape_style_checkmark'             => [
					'id'      => 'shape_style_checkmark',
					'type'    => 'switch',
					'isPro'   => ! rtsb()->has_pro(),
					'default' => false,
					'label'   => esc_html__( 'Checkmark Selected Term ', 'shopbuilder' ),
					'help'    => esc_html__( 'Shape style will add checkmark icon. Note: This option will not be applied to radio attributes.', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'default_dropdown_convert'          => [
					'id'      => 'default_dropdown_convert',
					'label'   => esc_html__( 'Default Dropdown Convert', 'shopbuilder' ),
					'help'    => esc_html__( 'Dropdown Convert To Button Or Image', 'shopbuilder' ),
					'isPro'   => ! rtsb()->has_pro(),
					'type'    => 'select',
					'value'   => 'button',
					'options' => [
						'select' => esc_html__( 'Select ( Default, Not Convert )', 'shopbuilder' ),
						'button' => esc_html__( 'Button', 'shopbuilder' ),
						'image'  => esc_html__( 'Image', 'shopbuilder' ),
					],
					'tab'     => 'general',
				],
				'show_term_name_beside_label'       => [
					'id'      => 'show_term_name_beside_label',
					'type'    => 'switch',
					'isPro'   => ! rtsb()->has_pro(),
					'default' => false,
					'label'   => esc_html__( 'Show Selected Term Name', 'shopbuilder' ),
					'help'    => esc_html__( 'Displays the selected attribute value next to its label (e.g. "Color: Red").', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'disabled_attribute_behavior'       => [
					'id'      => 'disabled_attribute_behavior',
					'label'   => esc_html__( 'Disabled Attribute behavior', 'shopbuilder' ),
					'help'    => esc_html__( 'Disabled attribute will be hide / blur.', 'shopbuilder' ) . ' <span>' . __( ' Note: This feature will be operational for variation quantities below the "Ajax variation threshold.', 'shopbuilder' ) . '</span>',
					'type'    => 'select',
					'value'   => 'blur-cross',
					'options' => [
						'blur-cross'    => esc_html__( 'Blur And Cross', 'shopbuilder' ),
						'blur-no-cross' => esc_html__( 'Only Blur', 'shopbuilder' ),
						'hide'          => esc_html__( 'Hide', 'shopbuilder' ),
					],
					'tab'     => 'general',
				],
				'attr_display_limit'                => [
					'id'    => 'attr_display_limit',
					'type'  => 'number',
					'isPro' => ! rtsb()->has_pro(),
					'value' => 0,
					'size'  => 'small',
					'min'   => 0,
					'max'   => 999,
					'label' => esc_html__( 'Product Page Attribute display limit', 'shopbuilder' ),
					'help'  => esc_html__( 'Catalog mode attribute display limit. Default is 0. Means no limit.', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'attribute_image_size'              => [
					'id'      => 'attribute_image_size',
					'label'   => esc_html__( 'Attribute image size', 'shopbuilder' ),
					'help'    => __( 'Choose attribute image size.', 'shopbuilder' ) . '<a target="_blank" href="' . esc_url( admin_url( 'options-media.php' ) ) . '"> ' . __( 'Media Settings', 'shopbuilder' ) . '</a>',
					'type'    => 'select',
					'value'   => 'thumbnail',
					'options' => Fns::get_image_sizes(),
					'tab'     => 'general',
				],

				'tooltip_title'                     => [
					'id'         => 'tooltip_title',
					'type'       => 'title',
					'label'      => esc_html__( 'Tooltip Options', 'shopbuilder' ),
					'help'       => esc_html__( 'Tooltip settings for single page and catalog mode on shop / archive pages', 'shopbuilder' ),
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.show_tooltip',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],
				'tooltip_image_size'                => [
					'id'         => 'tooltip_image_size',
					'label'      => esc_html__( 'Tooltip image size', 'shopbuilder' ),
					'help'       => __( 'Choose Tooltip image size.', 'shopbuilder' ) . '<a target="_blank" href="' . esc_url( admin_url( 'options-media.php' ) ) . '"> ' . __( 'Media Settings', 'shopbuilder' ) . '</a>',
					'type'       => 'select',
					'isPro'      => ! rtsb()->has_pro(),
					'value'      => 'thumbnail',
					'options'    => Fns::get_image_sizes(),
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.show_tooltip',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],
				'advanced_title'                    => [
					'id'    => 'advanced_title',
					'type'  => 'title',
					'label' => esc_html__( 'Advanced', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'disable_out_of_stock'              => [
					'id'      => 'disable_out_of_stock',
					'type'    => 'switch',
					'default' => true,
					'label'   => esc_html__( 'Disable Out of Stock', 'shopbuilder' ),
					'help'    => __( 'Disable out of stock variation attribute items.', 'shopbuilder' ),
					'tab'     => 'general',
				],

				'enable_variation_url'              => [
					'id'      => 'enable_variation_url',
					'type'    => 'switch',
					'default' => false,
					'isPro'   => ! rtsb()->has_pro(),
					'label'   => esc_html__( 'Variation URL', 'shopbuilder' ),
					'help'    => esc_html__( 'Generate a URL based on the selected variation attributes.', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'enable_showcase_swatches'          => [
					'id'      => 'enable_showcase_swatches',
					'type'    => 'switch',
					'default' => false,
					'isPro'   => ! rtsb()->has_pro(),
					'label'   => esc_html__( 'Enable Swatches', 'shopbuilder' ),
					'help'    => esc_html__( 'Show swatches on archive / shop page.', 'shopbuilder' ),
					'tab'     => 'showcase_shop',
				],
				'showcase_product_wrapper_selector' => [
					'id'         => 'showcase_product_wrapper_selector',
					'type'       => 'text',
					'isPro'      => ! rtsb()->has_pro(),
					'label'      => esc_html__( 'Product Wrapper Selector', 'shopbuilder' ),
					'help'       => esc_html__( 'Archive product Wrapper Selector (.rtwpvs-product,.tmb-woocommerce)', 'shopbuilder' ),
					'value'      => '.rtsb-vs-product,.product-item',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'showcase_swatches_image_selector'  => [
					'id'         => 'showcase_swatches_image_selector',
					'type'       => 'text',
					'isPro'      => ! rtsb()->has_pro(),
					'label'      => esc_html__( 'Image Selector', 'shopbuilder' ),
					'help'       => esc_html__( 'Archive product image selector to show variation image. You can also use multiple selectors separated by comma (.attachment-woocommerce_thumbnail, .wp-post-image) ', 'shopbuilder' ),
					'value'      => '.wp-post-image, .attachment-woocommerce_thumbnail, .rtsb-product-image',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'showcase_image_size'               => [
					'id'         => 'showcase_image_size',
					'label'      => esc_html__( 'Showcase Image Size', 'shopbuilder' ),
					'help'       => __( 'Choose showcase variation image size.', 'shopbuilder' ) ,
					'type'       => 'select',
					'isPro'      => ! rtsb()->has_pro(),
					'value'      => 'woocommerce_thumbnail',
					'options'    => array_diff_key( Fns::get_image_sizes(), [ 'rtsb_custom' => '' ] ),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'showcase_swatches_position'        => [
					'id'         => 'showcase_swatches_position',
					'label'      => esc_html__( 'Swatches Position', 'shopbuilder' ),
					'help'       => esc_html__( 'Display showcase swatches in a specific position.', 'shopbuilder' ) . '<br/>' . __( 'Note: Some themes remove or override default WooCommerce hooks. If the swatches do not appear as expected, you may need to manually set a custom position.', 'shopbuilder' ),
					'isPro'      => ! rtsb()->has_pro(),
					'type'       => 'select',
					'value'      => 'before_add_to_cart',
					'options'    => apply_filters(
						'rtsb_showcase_swatches_positions',
						[
							'custom_hook'        => esc_html__( 'Custom Hook', 'shopbuilder' ),
							'before_add_to_cart' => esc_html__( 'Above Add To Cart', 'shopbuilder' ),
							'after_add_to_cart'  => esc_html__( 'Below Add To Cart', 'shopbuilder' ),
						]
					),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'custom_hook_name'                  => [
					'type'       => 'description',
					'text'       => sprintf(
					/* translators: 1: The shortcode.*/
						esc_html__( 'Copy the php code %1$s and paste it in your product query where you want to show the button.', 'shopbuilder' ),
						"<code style='padding: 5px' >&lt;?php do_action( 'rtsb/vs/showcase/variation' ); ?&gt;</code>"
					),
					'id'         => 'custom_hook_name',
					'tab'        => 'showcase_shop',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
							[
								'item'     => 'modules.variation_swatches.showcase_swatches_position',
								'value'    => 'custom_hook',
								'operator' => '==',
							],
						],
					],
				],
				'showcase_swatches_align'           => [
					'id'         => 'showcase_swatches_align',
					'label'      => esc_html__( 'Swatches Align', 'shopbuilder' ),
					'help'       => esc_html__( 'Swatches align on archive page.', 'shopbuilder' ),
					'isPro'      => ! rtsb()->has_pro(),
					'type'       => 'select',
					'value'      => 'left',
					'options'    => apply_filters(
						'rtsb_get_showcase_swatches_aligns',
						[
							'left'   => esc_html__( 'Left', 'shopbuilder' ),
							'right'  => esc_html__( 'Right', 'shopbuilder' ),
							'center' => esc_html__( 'Center', 'shopbuilder' ),
						]
					),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],

				'show_clear_on_showcase'            => [
					'id'         => 'show_clear_on_showcase',
					'isPro'      => ! rtsb()->has_pro(),
					'type'       => 'switch',
					'label'      => esc_html__( 'Show clear link', 'shopbuilder' ),
					'help'       => esc_html__( 'Show clear link on archive, shop page, any other showcase.', 'shopbuilder' ),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'showcase_special_attribute_title'  => [
					'id'    => 'showcase_special_attribute_title',
					'type'  => 'title',
					'label' => esc_html__( 'Special Attribute', 'shopbuilder' ),
					'tab'   => 'showcase_shop',
				],
				'showcase_single_attribute'         => [
					'id'    => 'showcase_single_attribute',
					'isPro' => ! rtsb()->has_pro(),
					'type'  => 'switch',
					'label' => esc_html__( 'Show Single Attribute', 'shopbuilder' ),
					'help'  => esc_html__( 'Show single attribute taxonomies on archive page.', 'shopbuilder' ),
					'tab'   => 'showcase_shop',
				],
				'chose_showcase_single_attribute'   => [
					'id'         => 'chose_showcase_single_attribute',
					'label'      => esc_html__( 'Chose Attribute', 'shopbuilder' ),
					'help'       => esc_html__( 'Choose an attribute to show on catalog mode', 'shopbuilder' ),
					'isPro'      => ! rtsb()->has_pro(),
					'type'       => 'select',
					'options'    => SwatchesFns::get_wc_attributes( esc_html__( ' - Choose Attribute - ', 'shopbuilder' ) ),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.showcase_single_attribute',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],

				'showcase_swatches_display_event'   => [
					'id'         => 'showcase_swatches_display_event',
					'label'      => esc_html__( 'Catalog Mode Display Event', 'shopbuilder' ),
					'help'       => esc_html__( 'Show catalog mode image display event.', 'shopbuilder' ),
					'isPro'      => ! rtsb()->has_pro(),
					'type'       => 'select',
					'value'      => 'click',
					'options'    => [
						'click' => esc_html__( 'on Click', 'shopbuilder' ),
						'hover' => esc_html__( 'on Hover', 'shopbuilder' ),
					],
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],

				'showcase_swatches_display_limit'   => [
					'id'         => 'showcase_swatches_display_limit',
					'type'       => 'number',
					'isPro'      => ! rtsb()->has_pro(),
					'value'      => 0,
					'size'       => 'small',
					'min'        => 0,
					'max'        => 999,
					'label'      => esc_html__( 'Attribute display limit', 'shopbuilder' ),
					'help'       => esc_html__( 'Catalog mode attribute display limit. Default is 0. Means no limit.', 'shopbuilder' ),
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_swatches.enable_showcase_swatches',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
					'tab'        => 'showcase_shop',
				],
				'checkmark_size'                    => [
					'id'    => 'checkmark_size',
					'type'  => 'title',
					'label' => esc_html__( 'Checkmark', 'shopbuilder' ),
					'tab'   => 'style',
				],
				'checkmark_bg_color'                => [
					'id'    => 'checkmark_bg_color',
					'label' => esc_html__( 'Checkmark Background Color', 'shopbuilder' ),
					'isPro' => ! rtsb()->has_pro(),
					'type'  => 'color',
					'value' => '#333333',
					'tab'   => 'style',
				],
				'attribute_checkmark_width'         => [
					'id'    => 'attribute_checkmark_width',
					'label' => esc_html__( 'Checkmark Width', 'shopbuilder' ),
					'isPro' => ! rtsb()->has_pro(),
					'help'  => esc_html__( 'Set the width (in px) for selected attribute checkmark,', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 15,
					'max'   => 200,
					'unit'  => 'px',
					'value' => 18,
					'tab'   => 'style',
				],
				'attribute_checkmark_height'        => [
					'id'    => 'attribute_checkmark_height',
					'label' => esc_html__( 'Checkmark Height', 'shopbuilder' ),
					'isPro' => ! rtsb()->has_pro(),
					'help'  => esc_html__( 'Set the height (in px) for selected attribute checkmark,', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 15,
					'max'   => 200,
					'unit'  => 'px',
					'value' => 18,
					'tab'   => 'style',
				],
				'attribute_checkmark_icon_size'     => [
					'id'    => 'attribute_checkmark_icon_size',
					'isPro' => ! rtsb()->has_pro(),
					'label' => esc_html__( 'Checkmark Icon Size', 'shopbuilder' ),
					'help'  => esc_html__( 'Checkmark Icon Size.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 13,
					'tab'   => 'style',
				],
				'attribute_style'                   => [
					'id'    => 'attribute_style',
					'type'  => 'title',
					'label' => esc_html__( 'Attribute Style', 'shopbuilder' ),
					'tab'   => 'style',
				],
				'attribute_border_color'            => [
					'id'    => 'attribute_border_color',
					'label' => esc_html__( 'Attribute Border Color', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#D1D5DB',
					'tab'   => 'style',
				],
				'attribute_border_color_hover'      => [
					'id'    => 'attribute_border_color_hover',
					'label' => esc_html__( 'Attribute Border Color Hover/Active', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#333333',
					'tab'   => 'style',
				],
				'attribute_border_radius'           => [
					'id'    => 'attribute_border_radius',
					'label' => esc_html__( 'Attribute Border Radius', 'shopbuilder' ),
					'isPro' => ! rtsb()->has_pro(),
					'help'  => esc_html__( 'Set the border radius (in px) for attribute items. Use a small value for squared corners or a large value (e.g. 100+) for fully rounded / circular swatches.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 0,
					'max'   => 200,
					'unit'  => 'px',
					'value' => 4,
					'tab'   => 'style',
				],
				'details_page_attribute_size'       => [
					'id'    => 'details_page_attribute_size',
					'type'  => 'title',
					'label' => esc_html__( 'Details Page Attributes', 'shopbuilder' ),
					'tab'   => 'style',
				],
				'details_page_attr_label_color'     => [
					'id'    => 'details_page_attr_label_color',
					'label' => esc_html__( 'Product Page Attribute Label Color', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#333333',
					'tab'   => 'style',
				],
				'details_page_attr_label_font_size' => [
					'id'    => 'details_page_attr_label_font_size',
					'label' => esc_html__( 'Product Page Attribute Label Font Size', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 8,
					'max'   => 50,
					'unit'  => 'px',
					'value' => 16,
					'tab'   => 'style',
				],
				'details_page_attribute_width'      => [
					'id'    => 'details_page_attribute_width',
					'label' => esc_html__( 'Variation Item Width', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the width (in px) for each variation attribute option, Note: This option will not be applied to radio and button attributes.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 150,
					'unit'  => 'px',
					'value' => 30,
					'tab'   => 'style',
				],
				'details_page_attribute_height'     => [
					'id'    => 'details_page_attribute_height',
					'label' => esc_html__( 'Variation Item Height', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the height (in px) for each variation attribute option, Note: This option will not be applied to radio attributes.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 150,
					'unit'  => 'px',
					'value' => 30,
					'tab'   => 'style',
				],
				'details_page_attribute_font_size'  => [
					'id'    => 'details_page_attribute_font_size',
					'label' => esc_html__( 'Variation Item Font Size', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the font size (in px) for text within variation attribute options, such as labels on buttons.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 8,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 16,
					'tab'   => 'style',
				],
				'details_page_radio_size'           => [
					'id'    => 'details_page_radio_size',
					'label' => esc_html__( 'Radio Attribute Size', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the size (in px) for radio-button variation attributes. Independent from the Variation Item Width/Height settings.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 80,
					'unit'  => 'px',
					'value' => 25,
					'tab'   => 'style',
				],
				'showcase_attribute_size'           => [
					'id'    => 'showcase_attribute_size',
					'type'  => 'title',
					'label' => esc_html__( 'Shop Page / Product Showcase Attributes Size', 'shopbuilder' ),
					'tab'   => 'style',
				],
				'showcase_attribute_width'          => [
					'id'    => 'showcase_attribute_width',
					'label' => esc_html__( 'Variation Item Width', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the width (in px) for each variation attribute option,  Note: This option will not be applied to radio and button attributes.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 200,
					'unit'  => 'px',
					'value' => 30,
					'tab'   => 'style',
				],
				'showcase_attribute_height'         => [
					'id'    => 'showcase_attribute_height',
					'label' => esc_html__( 'Variation Item Height', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the height (in px) for each variation attribute option,  Note: This option will not be applied to radio and button attributes.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 200,
					'unit'  => 'px',
					'value' => 30,
					'tab'   => 'style',
				],
				'showcase_attribute_font_size'      => [
					'id'    => 'showcase_attribute_font_size',
					'label' => esc_html__( 'Variation Item Font Size', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the font size (in px) for text within variation attribute options, such as labels on buttons.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 8,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 16,
					'tab'   => 'style',
				],
				'showcase_radio_size'               => [
					'id'    => 'showcase_radio_size',
					'label' => esc_html__( 'Radio Attribute Size', 'shopbuilder' ),
					'help'  => esc_html__( 'Set the size (in px) for radio-button variation attributes in shop/showcase contexts.', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 10,
					'max'   => 80,
					'unit'  => 'px',
					'value' => 25,
					'tab'   => 'style',
				],
				'tooltip_style'                     => [
					'id'    => 'tooltip_style',
					'type'  => 'title',
					'label' => esc_html__( 'Tooltip Style', 'shopbuilder' ),
					'tab'   => 'style',
				],
				'tooltip_text_color'                => [
					'id'    => 'tooltip_text_color',
					'label' => esc_html__( 'Tooltip Text Color', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#ffffff',
					'tab'   => 'style',
				],
				'tooltip_bg_color'                  => [
					'id'    => 'tooltip_bg_color',
					'label' => esc_html__( 'Tooltip Background Color', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#333333',
					'tab'   => 'style',
				],
				'tooltip_images_padding'            => [
					'id'    => 'tooltip_images_padding',
					'label' => esc_html__( 'Tooltip Images Padding', 'shopbuilder' ),
					'type'  => 'slider',
					'min'   => 0,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 3,
					'tab'   => 'style',
				],
				'tooltip_image_bg_color'            => [
					'id'    => 'tooltip_image_bg_color',
					'label' => esc_html__( 'Tooltip Image Background Color', 'shopbuilder' ),
					'type'  => 'color',
					'value' => '#333333',
					'tab'   => 'style',
				],

			]
		); // @phpstan-ignore-line
	}
	/**
	 * @return array
	 */
	public function get_variation_gallery_fields() {
		return apply_filters(
			'rtsb/module/variation_gallery/fields',
			[
				'main_image_controls_title'        => [
					'id'    => 'main_image_controls_title',
					'type'  => 'title',
					'label' => esc_html__( 'Main Image Controls', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'image_zoom'                       => [
					'id'      => 'image_zoom',
					'type'    => 'switch',
					'default' => false,
					'label'   => esc_html__( 'Zoom Gallery image', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'lightBox'                         => [
					'id'      => 'lightBox',
					'type'    => 'switch',
					'default' => false,
					'label'   => esc_html__( 'Enable Images lightBox', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'lightBox_button_position'         => [
					'id'      => 'lightBox_button_position',
					'type'    => 'select',
					'value'   => 'top-right',
					'label'   => esc_html__( 'Zoom Button Position', 'shopbuilder' ),
					'isPro'   => ! rtsb()->has_pro(),
					'options' => [
						'top-right'    => esc_html__( 'Top Right', 'shopbuilder' ),
						'top-left'     => esc_html__( 'Top Left', 'shopbuilder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'shopbuilder' ),
						'bottom-left'  => esc_html__( 'Bottom Left', 'shopbuilder' ),
					],
					'tab'     => 'general',
				],

				'adaptive_height'                  => [
					'id'         => 'adaptive_height',
					'type'       => 'switch',
					'default'    => false,
					'label'      => esc_html__( 'Main Images Slider Adaptive Height', 'shopbuilder' ),
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_gallery.gallery_style',
								'value'    => 'grid',
								'operator' => '!=',
							],
						],
					],
				],
				'main_slider_alignment'            => [
					'id'         => 'main_slider_alignment',
					'type'       => 'switch',
					'value'      => 'center',
					'label'      => esc_html__( 'Main Image Middle Position', 'shopbuilder' ),
					'help'       => esc_html__( 'The main image area will align vertically in the center if the inner content is shorter than the adjacent content.', 'shopbuilder' ),
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_gallery.gallery_style',
								'value'    => [ 'left', 'right' ],
								'operator' => 'in',
							],
						],
					],
				],
				'main_image_transition_effect'     => [
					'id'         => 'main_image_transition_effect',
					'type'       => 'select',
					'value'      => 'slide',
					'label'      => esc_html__( 'Transition Effect For Main Image Carousel', 'shopbuilder' ),
					'isPro'      => ! rtsb()->has_pro(),
					'options'    => [
						'slide' => esc_html__( 'Slide', 'shopbuilder' ),
						'fade'  => esc_html__( 'Fade', 'shopbuilder' ),
					],
					'tab'        => 'general',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_gallery.gallery_style',
								'value'    => 'grid',
								'operator' => '!=',
							],
						],
					],
				],
				'preloader'                        => [
					'id'      => 'preloader',
					'type'    => 'switch',
					'default' => false,
					'label'   => esc_html__( 'Preloader', 'shopbuilder' ),
					'help'    => esc_html__( 'Show a loading animation while the gallery images are being loaded.', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'preloader_image'                  => [
					'id'          => 'preloader_image',
					'type'        => 'fileupload',
					'label'       => esc_html__( 'Preloader Image', 'shopbuilder' ),
					'help'        => esc_html__( 'Upload a custom preloader image to replace the default loading animation. Recommended size: 60x60 pixels.', 'shopbuilder' ),
					'tab'         => 'general',
					// Preserve the { id, source } media array on save (the default text
					// sanitizer would flatten it to an empty string and drop the upload).
					'sanitize_fn' => [ Fns::class, 'sanitize_fileupload_value' ],
					'dependency'  => [
						'rules' => [
							[
								'item'     => 'modules.variation_gallery.preloader',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],
				'gallery_change_effect'            => [
					'id'      => 'gallery_change_effect',
					'type'    => 'select',
					'value'   => 'blur',
					'label'   => esc_html__( 'Gallery Change Effect', 'shopbuilder' ),
					'help'    => esc_html__( 'Choose the visual effect applied to the gallery when switching between variations.', 'shopbuilder' ),
					'options' => [
						'blur' => esc_html__( 'Blur', 'shopbuilder' ),
						'fade' => esc_html__( 'Fade', 'shopbuilder' ),
						'gray' => esc_html__( 'Gray', 'shopbuilder' ),
						'none' => esc_html__( 'No Effect', 'shopbuilder' ),
					],
					'tab'     => 'general',
				],
				'gallery_thumbnail_controls_title' => [
					'id'    => 'gallery_thumbnail_controls_title',
					'type'  => 'title',
					'label' => esc_html__( 'Gallery / Thumbnail Item Controls', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'gallery_style'                    => [
					'id'      => 'gallery_style',
					'type'    => 'select',
					'value'   => 'bottom',
					'label'   => esc_html__( 'Thumbnail Position/Gallery Style', 'shopbuilder' ),
					// Bottom, left and right are free. Grid needs the pro templates, so
					// it is only offered when pro is active; a value saved while pro was
					// active degrades to bottom via GalleryFns::get_gallery_style().
					'options' => array_merge(
						[
							'bottom' => esc_html__( 'Thumbnail Position Bottom', 'shopbuilder' ),
							'left'   => esc_html__( 'Thumbnail Position Left', 'shopbuilder' ),
							'right'  => esc_html__( 'Thumbnail Position Right', 'shopbuilder' ),
						],
						rtsb()->has_pro() ? [ 'grid' => esc_html__( 'Grid View Images', 'shopbuilder' ) ] : []
					),
					'tab'     => 'general',
				],
				'thumbnails_columns'               => [
					'id'    => 'thumbnails_columns',
					'type'  => 'slider',
					'label' => esc_html__( 'Thumbnail Items', 'shopbuilder' ),
					'help'  => esc_html__( 'Add slides per view, or items per row if the slider is not activated.', 'shopbuilder' ),
					'min'   => 2,
					'max'   => 8,
					'unit'  => '',
					'value' => 4,
					'tab'   => 'general',
				],
				'thumbnails_gap'                   => [
					'id'    => 'thumbnails_gap',
					'type'  => 'slider',
					'label' => esc_html__( 'Thumbnails Gap', 'shopbuilder' ),
					'help'  => esc_html__( 'Product Thumbnails Gap In Pixel. Default value is: 0. Limit: 0-50.', 'shopbuilder' ),
					'min'   => 0,
					'max'   => 50,
					'unit'  => 'px',
					'value' => 10,
					'tab'   => 'general',
				],
				'main_image_border_color'          => [
					'id'         => 'main_image_border_color',
					'label'      => esc_html__( 'Main Image Border Color', 'shopbuilder' ),
					'tab'        => 'styles',
					'type'       => 'color',
					'dependency' => [
						'rules' => [
							[
								'item'     => 'modules.variation_gallery.gallery_style',
								'value'    => 'grid',
								'operator' => '!=',
							],
						],
					],
				],
				'thumbnail_item_border_color'      => [
					'id'    => 'thumbnail_item_border_color',
					'label' => esc_html__( 'Thumbnail item Border Color', 'shopbuilder' ),
					'tab'   => 'styles',
					'type'  => 'color',
				],
				'thumbnail_active_border_color'    => [
					'id'    => 'thumbnail_active_border_color',
					'label' => esc_html__( 'Active Thumbnail Border Color', 'shopbuilder' ),
					'help'  => esc_html__( 'Border color of the currently selected thumbnail.', 'shopbuilder' ),
					'tab'   => 'styles',
					'type'  => 'color',
				],
				'thumbnail_item_inner_padding'     => [
					'id'    => 'thumbnail_item_inner_padding',
					'type'  => 'slider',
					'label' => esc_html__( 'Thumbnail Item Inner Padding', 'shopbuilder' ),
					'min'   => 0,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 10,
					'tab'   => 'styles',
				],
				'thumbnail_item_border_radius'     => [
					'id'    => 'thumbnail_item_border_radius',
					'type'  => 'slider',
					'label' => esc_html__( 'Thumbnail Item Border Radius', 'shopbuilder' ),
					'min'   => 0,
					'max'   => 30,
					'unit'  => 'px',
					'value' => 5,
					'tab'   => 'styles',
				],
			]
		);
	}
}

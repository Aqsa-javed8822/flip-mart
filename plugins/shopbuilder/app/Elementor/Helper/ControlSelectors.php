<?php
/**
 * BuilderFns class
 *
 * The  builder.
 *
 * @package  RadiusTheme\SB
 * @since    1.0.0
 */

namespace RadiusTheme\SB\Elementor\Helper;

use RadiusTheme\SB\Elementor\Widgets\Archive\ProductFilters;
use RadiusTheme\SB\Elementor\Widgets\General\{AdvancedHeading,
	DropCaps,
	ShopBuilderButton,
	CallToAction,
	InfoBox,
	FlipBox,
	Counter,
	CountDown,
	PricingTable,
	ProgressBar,
	ImageAccordion,
	ShopBuilderFaq,
	LogoSliderAndGrid,
	Testimonial,
	TeamMember,
	PostList,
	PostGrid
};

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * BuilderFns class
 */
class ControlSelectors {

	/**
	 * Get Widget Selectors.
	 *
	 * @param object $widget Widget object.
	 *
	 * @return array
	 */
	public static function get_widget_selectors( $widget ): array {
		$selectors        = [];
		$widget_selectors = self::widget_selectors();
		if ( ! empty( $widget_selectors[ $widget->rtsb_base ] ) ) {
			$selectors = $widget_selectors[ $widget->rtsb_base ];
		}

		return apply_filters( 'rtsb/elements/elementor/widget/selectors/' . $widget->rtsb_base, $selectors, $widget );
	}

	/**
	 * Widget Control Selectors.
	 *
	 * @return array
	 */
	private static function widget_selectors(): array {
		$selectors = array_merge(
			self::product_widget_selectors(),
			self::archive_widget_selectors(),
			self::cart_widget_selectors(),
			self::checkout_widget_selectors(),
			self::general_widget_selectors()
		);

		return apply_filters( 'rtsb/elements/elementor/widget/selectors', $selectors );
	}

	/**
	 * Carousel Selectors.
	 *
	 * @return array
	 */
	public static function carousel_selector(): array {
		$defaults = [
			'slider_btn'               => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn',
			'slider_wrapper'           => '{{WRAPPER}} .rtsb-carousel-slider .swiper-wrapper',
			'slider_pagination'        => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet',
			'slider_pagination_active' => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
		];

		return apply_filters( 'rtsb/elements/elementor/product_carousel_selectors', $defaults );
	}

	/**
	 * Carousel Selectors.
	 *
	 * @return array
	 */
	public static function review_star_icon_selector(): array {
		$defaults = [
			'review_star_icon_default_color' => '{{WRAPPER}} .star-rating::before, {{WRAPPER}} p.stars a:before, {{WRAPPER}} p.stars a:hover~a:before, {{WRAPPER}} p.stars.selected a.active~a:before',
			'review_star_icon_color'         => '{{WRAPPER}} .star-rating span::before, {{WRAPPER}} p.stars.selected a.active:before, {{WRAPPER}} p.stars:hover a:before, {{WRAPPER}} p.stars.selected a:not(.active):before, {{WRAPPER}} p.stars.selected a.active:before',
			'review_star_icon_size'          => '{{WRAPPER}} .star-rating',
			'review_star_icon_specing'       => '{{WRAPPER}} .star-rating',
			'review_star_icon_margin'        => '{{WRAPPER}} .woocommerce-product-rating',
		];

		return apply_filters( 'rtsb/elements/elementor/review_star_icon_selector', $defaults );
	}

	/**
	 * Carousel Selectors.
	 *
	 * @return array
	 */
	public static function button_settings_selector(): array {
		$defaults = [
			'button_typography'         => '{{WRAPPER}} .button',
			'button_height'             => '{{WRAPPER}} .button',
			'button_width'              => '{{WRAPPER}} .button',
			'button_text_color_normal'  => '{{WRAPPER}} .button,{{WRAPPER}} .rtsb-myacount-page  .woocommerce-table  tbody td a.button,{{WRAPPER}} .rtsb-myacount-page  .woocommerce-orders-table tbody td a.button,{{WRAPPER}} .rtsb-myacount-page .woocommerce-pagination a.button',
			'button_bg_color_normal'    => '{{WRAPPER}} .button',
			'button_border'             => '{{WRAPPER}} .button',
			'button_text_color_hover'   => '{{WRAPPER}} .button:hover,{{WRAPPER}} .rtsb-myacount-page  .woocommerce-table  tbody td a.button:hover,{{WRAPPER}} .rtsb-myacount-page .woocommerce-pagination a.button:hover,{{WRAPPER}} .rtsb-myacount-page  .woocommerce-orders-table tbody td a.button:hover',
			'button_bg_color_hover'     => '{{WRAPPER}} .button:hover',
			'button_border_hover_color' => '{{WRAPPER}} .button:hover',
			'button_border_radius'      => '{{WRAPPER}} .button',
			'button_padding'            => '{{WRAPPER}} .button',
			'button_margin'             => '{{WRAPPER}} .button',
		];

		return apply_filters( 'rtsb/elements/elementor/button_settings_selector', $defaults );
	}

	/**
	 * Carousel Selectors.
	 *
	 * @return array
	 */
	public static function reviews_settings_selecotor(): array {
		$defaults = array_merge(
			self::review_star_icon_selector(),
			[
				'review_heading_typography'       => '{{WRAPPER}} #reviews .woocommerce-Reviews-title',
				'review_heading_color'            => '{{WRAPPER}} #reviews .woocommerce-Reviews-title',
				'review_title_margin'             => '{{WRAPPER}} #reviews .woocommerce-Reviews-title',

				'form_heading_typography'         => '{{WRAPPER}} #reviews #reply-title',
				'form_heading_color'              => '{{WRAPPER}} #reviews #reply-title',
				'noform_heading_color'            => '{{WRAPPER}} #reviews .woocommerce-noreviews',
				'form_title_margin'               => '{{WRAPPER}} #reviews #reply-title',
				'input_label_typography'          => '{{WRAPPER}} #commentform label',
				'review_label_color'              => '{{WRAPPER}} #commentform label',
				'review_label_required'           => '{{WRAPPER}} #commentform .required',

				'review_input_color'              => '{{WRAPPER}} #review_form #respond .comment-form :is(input:not([type=checkbox], .submit), textarea)',
				'review_input_border_color'       => '{{WRAPPER}} #review_form #respond .comment-form :is(textarea, input:not(.submit))',
				'review_input_border_color_focus' => '{{WRAPPER}} #review_form #respond .comment-form :is(textarea, input):focus',
				'label_input_text_typography'     => '{{WRAPPER}} #review_form #respond .comment-form :is(input:not([type=checkbox], [type=submit] ), textarea)',
				'review_comment_field_height'     => '{{WRAPPER}} #review_form #respond .comment-form :is(textarea)',
				'review_form_rating_size'         => '{{WRAPPER}} p.stars a',
				'review_field_spacing'            => [
					'margin-0'      => '{{WRAPPER}} #review_form #respond :is(.comment-form)',
					'margin-buttom' => '{{WRAPPER}} #review_form #respond .comment-form :is(.comment-notes, .comment-form-rating, .comment-form-comment, .comment-form-author, .comment-form-email, .comment-form-cookies-consent)',
				],
				'review_input_border_radius'      => '{{WRAPPER}} #review_form #respond .comment-form :is(input:not([type=submit]), textarea )',
				'review_input_padding'            => '{{WRAPPER}} #review_form #respond .comment-form :is(textarea, input:not(#wp-comment-cookies-consent, .submit))',

				'button_typography'               => '{{WRAPPER}} #review_form #respond .comment-form :is(.submit)',
				'submit_button_alignment'         => [
					'text-align' => '{{WRAPPER}} #review_form #respond .comment-form .form-submit',
					'float'      => '{{WRAPPER}} #review_form #respond .comment-form .form-submit input#submit',
				],
				'submit_button_height'            => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_text_color_normal'        => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_bg_color_normal'          => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_border'                   => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_text_color_hover'         => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit):hover',
				'button_bg_color_hover'           => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit):hover',
				'button_border_hover_color'       => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit):hover',
				'button_border_radius'            => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_padding'                  => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',
				'button_margin'                   => '{{WRAPPER}}  #review_form #respond .comment-form :is(.submit)',

				'review_meta_color'               => '{{WRAPPER}} #reviews #comments ol.commentlist li .meta',
				'description_color'               => '{{WRAPPER}} #reviews .description, {{WRAPPER}} #reviews .description p',
				'review_border'                   => '{{WRAPPER}} #reviews #comments ol.commentlist li .comment-text',
				'review_meta_typography'          => '{{WRAPPER}} #reviews #comments ol.commentlist li .meta',
				'review_desc_typography'          => '{{WRAPPER}} #reviews .description, {{WRAPPER}} #reviews .description p',
				'review_single_spacing'           => '{{WRAPPER}} #reviews #comments ol.commentlist li',
				'review_padding'                  => '{{WRAPPER}} #reviews #comments ol.commentlist li .comment-text',

			]
		);

		return apply_filters( 'rtsb/elements/elementor/reviews_settings_selecotor', $defaults );
	}

	/**
	 * Module Button Selectors.
	 *
	 * @return array
	 */
	public static function module_btn_selector(): array {
		$defaults = [
			'module_width'              => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_height'             => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_text_color_normal'  => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_bg_color_normal'    => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_border'             => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_text_color_hover'   => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover',
			'module_bg_color_hover'     => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover',
			'module_border_hover_color' => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover',
			'module_border_radius'      => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
			'module_item_gap'           => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button )',
			'module_wrapper_margin'     => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button )',
			'module_item_alignment'     => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button )',
			'icon_size'                 => [
				'icon' => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
				'svg'  => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) svg',
			],
		];

		return apply_filters( 'rtsb/elements/elementor/module_btn_selector', $defaults );
	}

	/**
	 * Products Loop Default Selectors
	 *
	 * @return array
	 */
	public static function products_loop_default_selector(): array {
		$defaults = [
			// Product Image.
			'product_image_bg_color'         => '{{WRAPPER}} .products .product img',
			'product_image_height'           => '{{WRAPPER}} .products .product img',
			'product_image_auto_fit'         => '{{WRAPPER}} .products .product img',
			'product_image_padding'          => '{{WRAPPER}} .products .product img',
			// Product Title.
			'product_title_typography'       => '{{WRAPPER}} .products .product .woocommerce-loop-product__title',
			'product_title_color'            => '{{WRAPPER}} .products .product .woocommerce-loop-product__title',
			'product_title_hover_color'      => '{{WRAPPER}} .products .product .woocommerce-loop-product__title:hover',
			'product_title_padding'          => '{{WRAPPER}} .products .product .woocommerce-loop-product__title',

			// Product Price.
			'product_price_typography'       => '{{WRAPPER}} .products .product .price, {{WRAPPER}} .products .product ins .woocommerce-Price-amount',
			'product_price_color'            => '{{WRAPPER}} .products .product .price',
			'product_reguler_price_color'    => '{{WRAPPER}} .products .product .price :is(del, del span, del .amount)',
			'product_price_padding'          => '{{WRAPPER}} .products .product .price',
			// FLash Sale.
			'flash_sale_typography'          => '{{WRAPPER}} .products .product .onsale',
			'product_flash_sale_color'       => '{{WRAPPER}} .products .product .onsale',
			'flash_sale_bg_color'            => '{{WRAPPER}} .products .product .onsale',
			'flash_sale_badge_width'         => '{{WRAPPER}} .products .product .onsale',
			'flash_sale_badge_height'        => '{{WRAPPER}} .products .product .onsale',
			'flash_sale_badge_border_radius' => '{{WRAPPER}} .products .product .onsale',
			// Cart Button.
			'cart_button_typography'         => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_button_text_color_normal'  => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_button_bg_color_normal'    => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_button_border'             => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_button_text_color_hover'   => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ):hover',
			'cart_button_bg_color_hover'     => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ):hover',
			'cart_button_border_hover_color' => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ):hover',
			'cart_button_border_radius'      => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'add_cart_button_padding'        => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'add_cart_button_margin'         => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_height'                    => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'icon_align'                     => [
				'align'          => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
				'flex-direction' => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ), {{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ) :is(i, span)',
			],
			'cart_icon_gap'                  => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped )',
			'cart_icon_size'                 => '{{WRAPPER}} .products .product :is(a.add_to_cart_button, a.product_type_simple, a.product_type_grouped ) :is(i, span, svg):not(.ahfb-svg-iconset):not(.ast-card-action-tooltip)',
			// Slider.
			'slider_arrow_icon_size'         => [
				'icon' => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn 1',
				'svg'  => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn svg',
			],
			'slider_arrow_size'              => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn',
			'arrows_color'                   => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn',
			'arrows_bg_color'                => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn',
			'arrows_border_color'            => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn',
			'arrows_hover_color'             => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn:hover',
			'arrows_hover_bg_color'          => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn:hover',
			'arrows_hover_border_color'      => '{{WRAPPER}} .rtsb-carousel-slider .rtsb-slider-btn:hover',
			'dots_gap'                       => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet',
			'dots_size'                      => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet',
			'dot_color'                      => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet',
			'dot_active_color'               => '{{WRAPPER}} .rtsb-slider-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
			// Pagination.
			'pagination_typography'          => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'align'                          => '{{WRAPPER}} .woocommerce-pagination',
			'pagination_gap'                 => '{{WRAPPER}} .woocommerce-pagination > .page-numbers',
			'pagination_text_color_normal'   => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_bg_color_normal'     => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_border'              => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_text_color_hover'    => '{{WRAPPER}} .page-numbers:not(ul,div):hover,{{WRAPPER}} .woocommerce-pagination .page-numbers.current',
			'pagination_bg_color_hover'      => '{{WRAPPER}} .page-numbers:not(ul,div):hover, {{WRAPPER}} .woocommerce-pagination .page-numbers.current',
			'pagination_border_hover_color'  => '{{WRAPPER}} .page-numbers:not(ul,div):hover,{{WRAPPER}} .woocommerce-pagination .page-numbers.current',
			'pagination_border_radius'       => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_padding'             => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_margin'              => '{{WRAPPER}} .woocommerce-pagination',
			'pagination_width'               => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_height'              => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div)',
			'pagination_icon_size'           => [
				'icon' => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div) i',
				'svg'  => '{{WRAPPER}} .woocommerce-pagination .page-numbers:not(ul,div) svg',
			],
			// Review Star.
			'review_star_icon_default_color' => '{{WRAPPER}} .products .product .star-rating::before, {{WRAPPER}} p.stars a:before, {{WRAPPER}} p.stars a:hover~a:before, {{WRAPPER}} p.stars.selected a.active~a:before',
			'review_star_icon_color'         => '{{WRAPPER}} .products .product .star-rating span::before, {{WRAPPER}} p.stars.selected a.active:before, {{WRAPPER}} p.stars:hover a:before, {{WRAPPER}} p.stars.selected a:not(.active):before, {{WRAPPER}} p.stars.selected a.active:before',
			'review_star_icon_size'          => '{{WRAPPER}} .products .product .star-rating',
			'review_star_icon_specing'       => '{{WRAPPER}} .products .product .star-rating',
			'review_star_icon_margin'        => '{{WRAPPER}} .products .product .star-rating',
			'not_found_notice'               => self::not_found_notice_selectors(),
		];
		$defaults = array_merge( self::review_star_icon_selector(), self::module_btn_selector(), $defaults );

		return apply_filters( 'rtsb/elements/elementor/product_loop_default_selectors', $defaults );
	}


	/**
	 * General Widget Selectors.
	 *
	 * @return array
	 */
	private static function general_widget_selectors(): array {
		$selectors = [
			'rtsb-product-breadcrumbs'        => [
				'breadcrumbs_align'      => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb',
				'text_color'             => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb',
				'link_color'             => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb a',
				'link_hover_color'       => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb a:hover',
				'item_spacing'           => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb .breadcrumb-separator',
				'icon_size'              => [
					'icon' => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb .breadcrumb-separator',
					'svg'  => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb .breadcrumb-separator svg',
				],
				'breadcrumbs_typography' => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb',
				'icon_color'             => '{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb .breadcrumb-separator i,{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb .breadcrumb-separator',
			],
			'rtsb-wc-notice'                  => [
				'notice_typography'            => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner)',
				'notice_icon_size'             => [
					'icon' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message::before, .woocommerce-error::before)',
					'svg'  => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner > svg',
				],
				'notice_bg_color'              => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner)',
				'notice_text_color'            => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner)',
				'notice_icon_color'            => [
					'icon' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message::before, .woocommerce-error::before)',
					'svg'  => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner > svg',
				],
				'notice_icon_bg_color'         => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner > svg',
				'notice_border_color'          => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner)',
				'notice_success_bg_color'      => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner.is-success)',
				'notice_success_text_color'    => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner.is-success)',
				'notice_success_icon_color'    => [
					'icon' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message::before, .woocommerce-error::before)',
					'svg'  => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-success > svg',
				],
				'notice_success_icon_bg_color' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-success > svg',
				'notice_success_border_color'  => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner.is-success)',
				'notice_error_bg_color'        => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-error',
				'notice_error_text_color'      => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-error',
				'notice_error_icon_color'      => [
					'icon' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message::before, .woocommerce-error::before)',
					'svg'  => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-error > svg',
				],
				'notice_error_icon_bg_color'   => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-error > svg',
				'notice_error_border_color'    => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner.is-error)',
				'notice_info_bg_color'         => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-info',
				'notice_info_text_color'       => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-info',
				'notice_info_icon_color'       => [
					'icon' => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message::before, .woocommerce-error::before)',
					'svg'  => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-info > svg',
				],
				'notice_info_icon_bg_color'    => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error), {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner.is-info > svg',
				'notice_info_border_color'     => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner.is-info)',
				'notice_padding'               => '{{WRAPPER}} .rtsb-notice :is(.woocommerce-message, .woocommerce-error, .wc-block-components-notice-banner)',
				'button_height'                => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_width'                 => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_typography'            => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_text_color_normal'     => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_bg_color_normal'       => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_border'                => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_text_color_hover'      => '{{WRAPPER}} .rtsb-notice a.button:hover, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward:hover',
				'button_bg_color_hover'        => '{{WRAPPER}} .rtsb-notice a.button:hover, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward:hover',
				'button_border_hover_color'    => '{{WRAPPER}} .rtsb-notice a.button:hover, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward:hover',
				'button_border_radius'         => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_padding'               => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'button_margin'                => '{{WRAPPER}} .rtsb-notice a.button, {{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'notice_button_alignment'      => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner>.wc-block-components-notice-banner__content .wc-forward',
				'notice_gap_multiple'          => '{{WRAPPER}} .rtsb-notice .woocommerce-notices-wrapper, {{WRAPPER}} .rtsb-notice',
				'notice_icon_gap'              => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner',
				'notice_border_radius'         => '{{WRAPPER}} .rtsb-notice .wc-block-components-notice-banner',
			],
			'rtsb-products-grid'              => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),
			'rtsb-products-list'              => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),
			'rtsb-products-slider'            => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors(),
				self::general_slider_selectors(),
			),
			'rtsb-product-categories-general' => array_merge(
				self::general_common_selectors(),
				self::general_cat_selectors()
			),
			'rtsb-products-single-category'   => array_merge(
				self::general_common_selectors(),
				self::general_cat_selectors()
			),
			'rtsb-social-share'               => array_merge(
				self::social_share(),
			),
			'rtsb-wishlist'                   => [
				'table_border'               => '{{WRAPPER}} .rtsb-wishlist-content table td,{{WRAPPER}} .rtsb-wishlist-content table th',
				'table_heading_cell_padding' => '{{WRAPPER}} .rtsb-wishlist-content table th,{{WRAPPER}} .rtsb-wishlist-content table td',
				'table_header_typo'          => '{{WRAPPER}} .rtsb-wishlist-content table thead th',
				'table_header_align'         => '{{WRAPPER}} .rtsb-wishlist-content table thead th',
				'table_header_color'         => '{{WRAPPER}} .rtsb-wishlist-content table thead th',
				'table_header_bg_color'      => '{{WRAPPER}} .rtsb-wishlist-content table thead th',
				'table_item_typo'            => '{{WRAPPER}} .rtsb-wishlist-content table td,{{WRAPPER}} .rtsb-wishlist-content table td a,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-partial-pay-avail-note span,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-message,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-details-message,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-privacy-checkbox label span',
				'table_item_align'           => '{{WRAPPER}} .rtsb-wishlist-content table td',
				'table_item_color'           => '{{WRAPPER}} .rtsb-wishlist-content table td,{{WRAPPER}} .rtsb-wishlist-content table td a,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-partial-pay-avail-note span,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-message,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-details-message,{{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-privacy-checkbox label span',
				'table_item_bg_color'        => '{{WRAPPER}} .rtsb-wishlist-content table td',
				'column_width'               => '{{WRAPPER}} .rtsb-wishlist-content table thead th,{{WRAPPER}} .rtsb-wishlist-content table tbody td',
				'table_item_text_decoration' => '{{WRAPPER}} .rtsb-wishlist-content table td, {{WRAPPER}} .rtsb-wishlist-content table td a, {{WRAPPER}} .rtsb-wishlist-content table td a span, {{WRAPPER}} .rtsb-wishlist-content table td .rtsb-partial-pay-avail-note span, {{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-message, {{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-out-of-stock-details-message, {{WRAPPER}} .rtsb-wishlist-content table td .rtsb-bis-privacy-checkbox label span',
			],
			'rtsb-advanced-heading'           => AdvancedHeading\Selectors::get_selectors(),
			'rtsb-dropcaps'                   => DropCaps\Selectors::get_selectors(),
			'rtsb-shopbuilder-button'         => ShopBuilderButton\Selectors::get_selectors(),
			'rtsb-call-to-action'             => CallToAction\Selectors::get_selectors(),
			'rtsb-info-box'                   => InfoBox\Selectors::get_selectors(),
			'rtsb-pricing-table'              => PricingTable\Selectors::get_selectors(),
			'rtsb-flip-box'                   => FlipBox\Selectors::get_selectors(),
			'rtsb-counter'                    => Counter\Selectors::get_selectors(),
			'rtsb-countdown-addon'            => CountDown\Selectors::get_selectors(),
			'rtsb-progress-bar-addon'         => ProgressBar\Selectors::get_selectors(),
			'rtsb-image-accordion-addon'      => ImageAccordion\Selectors::get_selectors(),
			'rtsb-shopbuilder-faq'            => ShopBuilderFaq\Selectors::get_selectors(),
			'rtsb-logo-slider-and-grid'       => LogoSliderAndGrid\Selectors::get_selectors(),
			'rtsb-testimonial'                => Testimonial\Selectors::get_selectors(),
			'rtsb-team-member'                => TeamMember\Selectors::get_selectors(),
			'rtsb-post-list'                  => PostList\Selectors::get_selectors(),
			'rtsb-post-grid'                  => PostGrid\Selectors::get_selectors(),
		];

		return apply_filters( 'rtsb/elements/elementor/widget/general/selectors', $selectors );
	}

	/**
	 * Product Page Selectors.
	 *
	 * @return array
	 */
	private static function product_widget_selectors(): array {
		// Swatch size belongs to the Variation Swatches plugin once it is active: it
		// prints its own Color & Image width/height rule, which this widget's size
		// controls would outrank (element-scoped Elementor CSS, and these controls
		// carry a default so they always print). Dropping the plugin's terms from
		// the size selectors leaves that setting in charge, while the controls keep
		// sizing ShopBuilder's own markup — the dropdowns and the built-in module.
		$swatch_size_target = defined( 'RTWPVS_VERSION' )
			? ''
			: '.rtwpvs  {{WRAPPER}} .rtwpvs-terms-wrapper .rtwpvs-term:not(.rtwpvs-radio-term), ';

		$selectors = [
			'rtsb-related-product'         => array_merge(
				self::products_loop_default_selector(),
				self::carousel_selector(),
				[
					'section_heading_typography' => '{{WRAPPER}} .rtsb-related-products .related > h2',
					'section_heading_color'      => '{{WRAPPER}} .rtsb-related-products .related > h2',
					'section_title_margin'       => '{{WRAPPER}} .rtsb-related-products .related > h2',
					'section_title_align'        => '{{WRAPPER}} .rtsb-related-products .related > h2',
				]
			),
			'rtsb-related-product-custom'  => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),
			'rtsb-related-products-slider' => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors(),
				self::general_slider_selectors(),
			),

			'rtsb-upsells-product'         => array_merge(
				self::products_loop_default_selector(),
				self::carousel_selector(),
				[
					'section_heading_typography' => '{{WRAPPER}} .rtsb-upsells-products :is(.up-sells, .upsells )  > h2',
					'section_heading_color'      => '{{WRAPPER}} .rtsb-upsells-products :is(.up-sells, .upsells ) > h2',
					'section_title_margin'       => '{{WRAPPER}} .rtsb-upsells-products :is(.up-sells, .upsells ) > h2',
					'section_title_align'        => '{{WRAPPER}} .rtsb-upsells-products :is(.up-sells, .upsells ) > h2',
				],
			),
			'rtsb-upsells-product-custom'  => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),

			'rtsb-upsells-product-slider'  => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors(),
				self::general_slider_selectors(),
			),

			'rtsb-actions-button'          => [
				'module_width'              => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon ',
				'module_height'             => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon',
				'module_text_color_normal'  => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn)',
				'module_bg_color_normal'    => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon',
				'module_border'             => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon',
				'module_text_color_hover'   => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover',
				'module_bg_color_hover'     => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover .icon',
				'module_border_hover_color' => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn):hover .icon',
				'module_border_radius'      => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon',
				'module_item_gap'           => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button ) ',
				'module_wrapper_margin'     => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button )',
				'module_item_alignment'     => '{{WRAPPER}} :is( .action-button-wrapper, .rtsb-actions-button )',
				'icon_size'                 => [
					'icon' => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon',
					'svg'  => '{{WRAPPER}} a:is(.rtsb-wishlist-btn,.rtsb-compare-btn,.rtsb-quick-view-btn) .icon svg',
				],

				'separator_color_normal'    => '{{WRAPPER}} .rtsb-actions-button .button-separator',
				'separator_typography'      => '{{WRAPPER}} .rtsb-actions-button .button-separator',
				'typo'                      => '{{WRAPPER}} .rtsb-actions-button .button-text',
				'align'                     => '{{WRAPPER}} .rtsb-actions-button .button-text',
				'text_color'                => '{{WRAPPER}} .rtsb-actions-button .button-text',
				'text_hover_color'          => '{{WRAPPER}} .rtsb-actions-button .button-text:hover',
				'text_shadow'               => '{{WRAPPER}} .rtsb-actions-button .button-text',
				'text_margin'               => '{{WRAPPER}} .rtsb-actions-button .button-text',

			],
			'rtsb-product-additional-info' => [
				'heading_typography' => '{{WRAPPER}} .rtsb-product-additional-information h2',
				'heading_color'      => '{{WRAPPER}} .rtsb-product-additional-information h2',
				'heading_margin'     => '{{WRAPPER}} .rtsb-product-additional-information h2',
				// Target the label + value cells (and the value's <p>) directly, by their WooCommerce
				// classes, so Content Typography (font-weight, text-transform, …) reliably overrides the
				// theme's higher-specificity `.woocommerce-js table.shop_attributes th` (0,2,2) rule and
				// applies UNIFORMLY. Styling only `.shop_attributes` (the table) let the theme's direct
				// th/cell rules win the cascade, so labels and values ended up styled inconsistently.
				'content_typography' => '{{WRAPPER}} .shop_attributes th.woocommerce-product-attributes-item__label, {{WRAPPER}} .shop_attributes td.woocommerce-product-attributes-item__value, {{WRAPPER}} .shop_attributes td.woocommerce-product-attributes-item__value p',
				'content_color'      => '{{WRAPPER}} .shop_attributes',
				'content_border'     => '{{WRAPPER}} .shop_attributes td, {{WRAPPER}} .shop_attributes th',
				'content_padding'    => '{{WRAPPER}} .shop_attributes td, {{WRAPPER}} .shop_attributes th',
			],
			'rtsb-product-add-to-cart'     => [
				'button_height'                     => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_width'                      => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'cart_icon_align'                   => [
					'icon' => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button i',
					'svg'  => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button svg',
				],
				'cart_icon_gap'                     => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_typography'                 => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_text_color_normal'          => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_bg_color_normal'            => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_border'                     => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_text_color_hover'           => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button:hover',
				'button_bg_color_hover'             => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button:hover',
				'button_border_hover_color'         => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button:hover',
				'button_border_radius'              => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_padding'                    => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'button_margin'                     => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button',
				'cart_icon_size'                    => [
					'icon' => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button i',
					'svg'  => '{{WRAPPER}} .rtsb-product-add-to-cart .cart .button svg',
				],
				'quantity_height'                   => [
					'full' => '{{WRAPPER}} .rtsb-product-add-to-cart input[type=number], {{WRAPPER}} .rtsb-quantity-box-group:is(.rtsb-quantity-box-group-style-1,.rtsb-quantity-box-group-style-2) .rtsb-quantity-btn',
					'half' => '{{WRAPPER}} .rtsb-quantity-box-group:is(.rtsb-quantity-box-group-style-3,.rtsb-quantity-box-group-style-4) .rtsb-quantity-btn',
				],
				'quantity_increment_button_padding' => '{{WRAPPER}} .rtsb-product-add-to-cart .rtsb-quantity-box-group .rtsb-quantity-btn.rtsb-quantity-plus',
				'quantity_decrement_button_padding' => '{{WRAPPER}} .rtsb-product-add-to-cart .rtsb-quantity-box-group .rtsb-quantity-btn.rtsb-quantity-minus',
				'quantity_input_width'              => '{{WRAPPER}} .rtsb-product-add-to-cart input.qty',
				'quantity_button_width'             => '{{WRAPPER}} .rtsb-product-add-to-cart .rtsb-quantity-box-group .rtsb-quantity-btn',

				'icon_size'                         => [
					'icon' => '{{WRAPPER}} .rtsb-product-add-to-cart .rtsb-quantity-box-group .rtsb-quantity-btn',
					'svg'  => '{{WRAPPER}} .rtsb-product-add-to-cart .rtsb-quantity-box-group .rtsb-quantity-btn svg',
				],
				'quantity_icon_color'               => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity :is(i, svg)',
				'quantity_icon_hover_color'         => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity .rtsb-quantity-btn:hover :is(i, svg)',
				'text_typography'                   => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'quantity_number_color'             => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'quantity_background_color'         => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'quantity_border'                   => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'quantity_radius'                   => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'qunatity_padding'                  => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity input',
				'quantity_wrapper_background_color' => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity .rtsb-quantity-box-group',
				'qunatity_wrapper_padding'          => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity .rtsb-quantity-box-group',
				'quantity_wrapper_radius'           => '{{WRAPPER}} .rtsb-product-add-to-cart .quantity .rtsb-quantity-box-group',
				'variation_padding'                 => '{{WRAPPER}} .rtsb-product-add-to-cart .variations select, .rtwpvs  {{WRAPPER}} .rtwpvs-terms-wrapper .rtwpvs-term:not(.rtwpvs-radio-term).rtwpvs-button-term, {{WRAPPER}} .rtsb-product-add-to-cart .rtsb-terms-wrapper .rtsb-term:not(.rtsb-radio-term).rtsb-button-term',
				'variation_label_margin'            => '{{WRAPPER}} .rtsb-product-add-to-cart  table.variations tr .label label',
				'variation_item_margin'             => '{{WRAPPER}} .rtsb-product-add-to-cart table.variations tr:not(:last-child) .value',
				'variation_border'                  => '{{WRAPPER}} .rtsb-product-add-to-cart .variations select, {{WRAPPER}} .rtsb-product-add-to-cart .rtsb-terms-wrapper .rtsb-term:not(.rtsb-radio-term)',
				'price_typography'                  => '.single-product {{WRAPPER}}  .single_variation .price',
				'price_color'                       => '.single-product {{WRAPPER}}  .single_variation :is(.price, .price .amount, .price ins)',
				'price_margin'                      => '.single-product {{WRAPPER}}  .single_variation :is(.price, .price .amount, .price ins)',
				'variation_height'                  => $swatch_size_target . '{{WRAPPER}} .rtsb-product-add-to-cart .variations select, {{WRAPPER}} .rtsb-product-add-to-cart .rtsb-terms-wrapper .rtsb-term:not(.rtsb-radio-term)',
				'variation_width'                   => $swatch_size_target . '{{WRAPPER}} .rtsb-product-add-to-cart .variations select, {{WRAPPER}} .rtsb-product-add-to-cart .rtsb-terms-wrapper .rtsb-term:not(.rtsb-radio-term)',
				'variation_border_radius'           => '.rtwpvs  {{WRAPPER}} .rtwpvs-terms-wrapper .rtwpvs-term:not(.rtwpvs-radio-term), {{WRAPPER}} .rtsb-product-add-to-cart .variations select, {{WRAPPER}} .rtsb-product-add-to-cart .rtsb-terms-wrapper .rtsb-term:not(.rtsb-radio-term)',
				'variation_label_typography'        => '{{WRAPPER}} .rtsb-product-add-to-cart  table.variations tr .label label',
				'variation_stock_typography'        => '{{WRAPPER}} .rtsb-product-add-to-cart  .woocommerce-variation-availability .stock',
				'variation_label_color'             => '{{WRAPPER}} .rtsb-product-add-to-cart  table.variations tr .label label',
				'variation_stock_color'             => '{{WRAPPER}} .rtsb-product-add-to-cart  .woocommerce-variation-availability .stock',
				'variation_outofstock_color'        => '{{WRAPPER}} .rtsb-product-add-to-cart  .woocommerce-variation-availability .stock.out-of-stock',
				'variation_swatch_border_color'     => '{{WRAPPER}} .rtsb-product-add-to-cart',
				'variation_swatch_selected_color'   => '{{WRAPPER}} .rtsb-product-add-to-cart',
				'variation_checkmark_bg_color'      => '{{WRAPPER}} .rtsb-product-add-to-cart',
				'variation_checkmark_size'          => '{{WRAPPER}} .rtsb-product-add-to-cart',
				'variation_checkmark_icon_size'     => '{{WRAPPER}} .rtsb-product-add-to-cart',
				'variation_clear_margin'            => '{{WRAPPER}} .rtsb-product-add-to-cart .reset_variations',
				'variation_clear_padding'           => '{{WRAPPER}} .rtsb-product-add-to-cart .reset_variations',

			],
			'rtsb-product-categories'      => [
				'align'                  => '{{WRAPPER}} .rtsb-product-categories',
				'label_typo'             => '{{WRAPPER}} .rtsb-product-categories .categories-title',
				'meta_label_color'       => '{{WRAPPER}} .rtsb-product-categories .categories-title',
				'value_typo'             => '{{WRAPPER}} .rtsb-product-categories a',
				'meta_value_color'       => '{{WRAPPER}} .rtsb-product-categories a',
				'meta_value_hover_color' => '{{WRAPPER}} .rtsb-product-categories a:hover',
			],
			'rtsb-product-description'     => [
				'typo'        => '{{WRAPPER}} .rtsb-description, {{WRAPPER}} .rtsb-description p',
				'align'       => '{{WRAPPER}}',
				'text_color'  => '{{WRAPPER}} :is(.rtsb-description , p)',
				'text_shadow' => '{{WRAPPER}} .rtsb-description , {{WRAPPER}} .rtsb-description p',
			],
			'rtsb-product-tags'            => [
				'align'                  => '{{WRAPPER}} .rtsb-product-tags',
				'label_typo'             => '{{WRAPPER}} .rtsb-product-tags .tags-title',
				'meta_label_color'       => '{{WRAPPER}} .rtsb-product-tags .tags-title',
				'value_typo'             => '{{WRAPPER}} .rtsb-product-tags a',
				'meta_value_color'       => '{{WRAPPER}} .rtsb-product-tags a',
				'meta_value_hover_color' => '{{WRAPPER}} .rtsb-product-tags a:hover',
			],

			'rtsb-product-image'           => [
				'image_width'                    => '{{WRAPPER}} .rtsb-product-images .woocommerce-product-gallery__image a > img',
				'image_border'                   => '{{WRAPPER}} .rtwpvg-wrapper .rtwpvg-slider-wrapper,{{WRAPPER}} .rtsb-product-images .woocommerce-product-gallery__image, {{WRAPPER}} .rtsb-product-images .product-vg-gallery',
				'image_border_radius'            => '{{WRAPPER}} .rtsb-product-images .woocommerce-product-gallery__image',
				'gallery_thumbs_column'          => '{{WRAPPER}} div.images .flex-control-thumbs li',
				'thumb_border'                   => '{{WRAPPER}} .rtsb-vg-main-slider-wrapper .rtsb-vs-thumb-item, {{WRAPPER}} .rtsb-product-images div.images .flex-control-thumbs li img, {{WRAPPER}} .rtsb-product-images .rtwpvg-thumbnail-wrapper .rtwpvg-thumbnail-slider .rtwpvg-thumbnail-image, {{WRAPPER}} .rtsb-product-images .rtwpvg-grid-wrapper .rtwpvg-grid-layout .rtwpvg-gallery-image',
				'thumbs_border_radius'           => '{{WRAPPER}} .rtsb-vg-main-slider-wrapper :is(.rtsb-vs-thumb-item, .rtsb-vs-thumb-item img), {{WRAPPER}} .rtsb-product-images div.images .flex-control-thumbs li img, {{WRAPPER}} .rtsb-product-images .rtwpvg-thumbnail-wrapper .rtwpvg-thumbnail-slider :is(.rtwpvg-thumbnail-image, img), {{WRAPPER}} .rtsb-product-images .rtwpvg-grid-wrapper .rtwpvg-grid-layout :is(.rtwpvg-gallery-image, img)',
				'gallery_thumbs_padding'         => '{{WRAPPER}} .rtsb-vg-main-slider-wrapper .rtsb-vs-thumb-item, {{WRAPPER}} .rtsb-product-images div.images .flex-control-thumbs li img, {{WRAPPER}} .rtsb-product-images .rtwpvg-thumbnail-wrapper .rtwpvg-thumbnail-slider .rtwpvg-thumbnail-image, {{WRAPPER}} .rtsb-product-images .rtwpvg-grid-wrapper .rtwpvg-grid-layout .rtwpvg-gallery-image',
				'flash_sale_typography'          => '{{WRAPPER}} .rtsb-product-images .onsale',
				'product_flash_sale_color'       => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_bg_color'            => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_badge_width'         => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_badge_height'        => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_badge_border_radius' => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_position_horizontal' => '{{WRAPPER}} .rtsb-product-images .onsale',
				'flash_sale_position_vertical'   => '{{WRAPPER}} .rtsb-product-images .onsale',
				'zoom_color'                     => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',

				'zoom_bg_color'                  => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',
				'zoom_icon_size'                 => [
					'icon' => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger :is(span, i)',
					'svg'  => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger svg, {{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger svg',
				],
				'zoom_badge_width'               => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',
				'zoom_badge_height'              => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',
				'zoom_badge_border_radius'       => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',
				'zoom_padding'                   => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger,{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger',
				'zoom_position_horizontal'       => [
					'zoom_position' => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger',
					'rtwpvg-tr-br'  => '{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger:is(.rtwpvg-trigger-position-top-right, .rtwpvg-trigger-position-bottom-right)',
					'rtwpvg-tl-bl'  => '{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger:is(.rtwpvg-trigger-position-top-left,.rtwpvg-trigger-position-bottom-left)',
				],
				'zoom_position_vertical'         => [
					'zoom-icon-top'    => '{{WRAPPER}} .rtsb-product-images .images .woocommerce-product-gallery__trigger, {{WRAPPER}} .rtsb-product-images .rtwpvg-trigger',
					'zoom-icon-bottom' => '{{WRAPPER}} .rtsb-product-images .rtwpvg-wrapper .rtwpvg-slider-wrapper .rtwpvg-trigger:is(.rtwpvg-trigger-position-bottom-right, .rtwpvg-trigger-position-bottom-left)',
				],
			],
			'rtsb-product-meta'            => [
				'meta_layout'            => '{{WRAPPER}} .rtsb-product-meta .product_meta',
				'align'                  => '{{WRAPPER}} .product_meta',
				'row_gap'                => '{{WRAPPER}} .rtsb-product-meta .product_meta',
				'column_gap'             => '{{WRAPPER}} .rtsb-product-meta .product_meta',
				'meta_padding'           => '{{WRAPPER}} .rtsb-product-meta .product_meta :is(.sku_wrapper, .posted_in, .tagged_as)',
				'label_typo'             => '{{WRAPPER}} .rtsb-product-meta .product_meta .rtsb-meta-label',
				'meta_label_color'       => '{{WRAPPER}} .rtsb-product-meta .product_meta .rtsb-meta-label',
				'value_typo'             => '{{WRAPPER}} .rtsb-product-meta .product_meta :is(.sku, .posted_in a, .tagged_as a)',
				'meta_value_color'       => '{{WRAPPER}} .rtsb-product-meta .product_meta :is(.sku, .posted_in a, .tagged_as a)',
				'meta_value_hover_color' => '{{WRAPPER}} .rtsb-product-meta .product_meta :is(.posted_in a, .tagged_as a):hover',
			],
			'rtsb-product-price'           => [
				'price_align'      => '{{WRAPPER}}',
				'space_between'    => [
					'del_price'     => '{{WRAPPER}} .rtsb-product-price .price del',
					'del_price_rtl' => '.rtl {{WRAPPER}} .rtsb-product-price .price del',
				],
				'price_typo'       => '{{WRAPPER}} .rtsb-product-price :is(.price, .price .amount, .price ins)',
				'price_color'      => '{{WRAPPER}} .rtsb-product-price :is(.price, .price .amount, .price ins)',
				'sale_price_typo'  => '{{WRAPPER}} .rtsb-product-price .price ins .amount',
				'sale_price_color' => '{{WRAPPER}} .rtsb-product-price .price ins .amount',
			],
			// New Convension.
			'rtsb-product-rating'          => array_merge(
				self::review_star_icon_selector(),
				[
					'rating_align'                 => '{{WRAPPER}}',
					'rating_text_link_color'       => '{{WRAPPER}} .woocommerce-review-link',
					'rating_text_link_hover_color' => '{{WRAPPER}} .woocommerce-review-link:hover',
					'link_typography'              => '{{WRAPPER}} .woocommerce-review-link',
					'rating_space_between'         => [
						'margin-right' => 'body:not(.rtl) {{WRAPPER}} .star-rating',
						'margin-left'  => 'body.rtl {{WRAPPER}} .star-rating',
					],
				]
			),
			'rtsb-product-reviews'         => self::reviews_settings_selecotor(),
			'rtsb-product-sku'             => [
				'align'                  => '{{WRAPPER}} .rtsb-product-sku',
				'label_typo'             => '{{WRAPPER}} .rtsb-product-sku .sku-label',
				'meta_label_color'       => '{{WRAPPER}} .rtsb-product-sku .sku-label',
				'value_typo'             => '{{WRAPPER}} .rtsb-product-sku .sku-value',
				'meta_value_color'       => '{{WRAPPER}} .rtsb-product-sku .sku-value',
				'meta_value_hover_color' => '{{WRAPPER}} .rtsb-product-sku .sku-value:hover',
			],
			'rtsb-product-stock'           => [
				'stock_text_typography' => '{{WRAPPER}} .rtsb-product-stock .stock .rtsb-stock-text',
				'in_stock_color'        => '{{WRAPPER}} .rtsb-product-stock .stock.in-stock .rtsb-stock-text',
				'outof_stock_color'     => '{{WRAPPER}} .rtsb-product-stock .stock.out-of-stock .rtsb-stock-text',
				'backorder_stock_color' => '{{WRAPPER}} .rtsb-product-stock .stock.available-on-backorder .rtsb-stock-text',
				'icon_size'             => [
					'icon' => '{{WRAPPER}} .rtsb-product-stock .stock i',
					'svg'  => '{{WRAPPER}} .rtsb-product-stock .stock svg',
				],
				'icon_gap'              => '{{WRAPPER}} .rtsb-product-stock .stock :is(i, svg)',
				'stock_alignment'       => '{{WRAPPER}} .rtsb-product-stock > .stock',
				'stock_padding'         => '{{WRAPPER}} .rtsb-product-stock > .stock',
				'stock_margin'          => '{{WRAPPER}} .rtsb-product-stock > .stock',
			],
			'rtsb-product-tabs'            => array_merge(
				self::reviews_settings_selecotor(),
				[
					'nav_typography'                => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li a',
					'nav_position'                  => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs.tabs-custom-layout2 ul.wc-tabs',
					'nav_color'                     => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li a',
					'nav_bg_color'                  => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li a',
					'nav_border'                    => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li',

					'nav_active_color'              => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active a',
					'nav_active_bg_color'           => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active a',
					'nav_active_border_color'       => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active',
					'nav_hover_color'               => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li:not(.active):hover a',
					'nav_hover_bg_color'            => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li:not(.active):hover a',
					'nav_active_hover_color'        => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active:hover a',
					'nav_active_hover_bg_color'     => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active:hover a',
					'nav_active_hover_border_color' => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs ul.tabs li.active:hover a',
					'nav_padding'                   => '#rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs  .woocommerce-tabs ul.tabs li a ',
					'content_padding'               => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs  .woocommerce-tabs .wc-tab ',

					'show_title'                    => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel > h2:first-child,{{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel .comment-reply-title, {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel  .woocommerce-Reviews-title',
					'tab_title_typography'          => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .wc-tab h2',
					'tab_title_gap'                 => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .wc-tab h2',
					'tab_content_title_color'       => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel > h2:first-child, {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel .woocommerce-Reviews-title',

					'additional_info_typography'    => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs .woocommerce-tabs .wc-tab .shop_attributes :is(th, td, td p)',
					'attributes_gap'                => '.rtsb-builder-content {{WRAPPER}} .rtsb-product-tabs table.shop_attributes td,{{WRAPPER}} .rtsb-product-tabs table.shop_attributes th',
				]
			),
			'rtsb-product-title'           => [
				'title_typo'        => '{{WRAPPER}} .product_title',
				'title_align'       => '{{WRAPPER}}',
				'title_color'       => '{{WRAPPER}} .product_title',
				'title_text_stroke' => '{{WRAPPER}} .product_title',
				'title_text_shadow' => '{{WRAPPER}} .product_title',
				'title_margin'      => '{{WRAPPER}} .product_title',
				'title_padding'     => '{{WRAPPER}} .product_title',
				'title_border'      => '{{WRAPPER}} .product_title',
			],
			'rtsb-short-description'       => [
				'typo'        => '{{WRAPPER}} .rtsb-short-description, {{WRAPPER}} .rtsb-short-description p',
				'align'       => '{{WRAPPER}}',
				'text_color'  => '{{WRAPPER}} :is(.rtsb-short-description , p)',
				'text_shadow' => '{{WRAPPER}} .rtsb-short-description, {{WRAPPER}} .rtsb-short-description p',
			],
			// The widget badge lives inside `{{WRAPPER}} .rtsb-product-onsale`; the Badges
			// module also renders group badges on the main product gallery (above-image
			// position). Style both from the same controls by pairing the widget selector
			// with a gallery-scoped selector for the module group badges. The gallery
			// scope (`.woocommerce-product-gallery`/`.rtsb-product-images`) keeps related
			// product card badges untouched.
			'rtsb-product-onsale'          => [
				'flash_sale_typography'          => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'product_flash_sale_color'       => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'flash_sale_bg_color'            => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'flash_sale_border_color'        => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), {{WRAPPER}} .rtsb-tag-outline.angle-right::after, :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'flash_sale_badge_width'         => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'flash_sale_badge_height'        => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'flash_sale_badge_border_radius' => '{{WRAPPER}} .rtsb-product-onsale :is(.onsale, .rtsb-tag-fill, .rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
				'product_badges'                 => [
					// Match BOTH the free wrapper (.rtsb-product-onsale, from
					// templates/elementor/single-product/product-badges.php) AND the pro wrapper
					// (.rtsb-product-badges-widgets, from the pro template override). Scoping only to
					// `.rtsb-product-onsale` made every badge style control (typography/color/bg/
					// border/padding/margin) silently no-op on pro sites, where that div is absent.
					// `:is()` matches the most specific arg, preserving the original (0,3,0) specificity.
					// Padding/margin also target the Badges-module group badges on the product gallery.
					'typography'    => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill, .rtsb-tag-outline)',
					'color'         => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline)',
					'bg_color'      => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline)',
					'border_color'  => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline),{{WRAPPER}} .rtsb-tag-outline.angle-right::after',
					'border_radius' => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline)',
					'padding'       => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
					'margin'        => '{{WRAPPER}} :is(.rtsb-product-onsale, .rtsb-product-badges-widgets) :is(.rtsb-tag-fill,.rtsb-tag-outline), :is(.woocommerce-product-gallery, .rtsb-product-images) .rtsb-badge-group-style :is(.rtsb-tag-fill, .rtsb-tag-outline)',
					'position_x'    => '',
					'position_y'    => '',
				],
				'badges_module'                  => [
					// Compound (no descendant combinator): `.rtsb-badge-group-style` and `.rtsb-promotion`
					// are emitted on the SAME element by BadgesFrontEnd::group_badges_html(). The pro
					// single-product template drops the outer `.rtsb-promotion` wrapper, so a descendant
					// selector matches nothing and the Direction/Alignment controls silently no-op.
					'direction'      => '{{WRAPPER}} .rtsb-promotion.rtsb-badge-group-style',
					'alignment'      => '{{WRAPPER}} .rtsb-promotion.rtsb-badge-group-style',
					'alignment_wrap' => '{{WRAPPER}} .rtsb-badge-container .rtsb-promotion:not(.rtsb-badge-group-style)',
				],
			],
		];

		return apply_filters( 'rtsb/elements/elementor/widget/product/selectors', $selectors );
	}

	/**
	 * Archive Selectors.
	 *
	 * @return array
	 */
	private static function archive_widget_selectors(): array {
		$selectors = [

			'rtsb-products-archive'        => array_merge(
				self::products_loop_default_selector(),
				[
					'column_per_row'   => '{{WRAPPER}} .rtsb-product-catalog.product-catalog-grid-view .products .product',
					'column_gap'       => '{{WRAPPER}} .rtsb-product-catalog.product-catalog-grid-view .products',
					'row_gap'          => '{{WRAPPER}} .rtsb-product-catalog .products',
					'list_image_width' => '{{WRAPPER}} .rtsb-product-catalog.product-catalog-list-view .rtsb-image-wrapper',
					'image_gap'        => [
						'margin-right'  => '{{WRAPPER}} .rtsb-product-catalog.product-catalog-list-view .rtsb-image-wrapper',
						'margin-bottom' => '{{WRAPPER}} .rtsb-product-catalog.product-catalog-grid-view .rtsb-image-wrapper',
					],
				],
			),

			'rtsb-products-archive-custom' => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),

			'rtsb-archive-title'           => [
				'archive_title_align' => '{{WRAPPER}}',
				'archive_title_color' => '{{WRAPPER}} .archive-title',
				'title_typo'          => '{{WRAPPER}} .archive-title',
				'text_stroke'         => '{{WRAPPER}} .archive-title',
				'text_shadow'         => '{{WRAPPER}} .archive-title',
			],
			'rtsb-archive-description'     => [
				'typo'        => '{{WRAPPER}} .rtsb-archive-description',
				'align'       => '{{WRAPPER}}',
				'text_color'  => '{{WRAPPER}}',
				'text_shadow' => '{{WRAPPER}} .rtsb-archive-description',
			],
			'rtsb-archive-product-mode'    => [
				'icon_size'                      => [
					'icon' => '{{WRAPPER}} .rtsb-archive-view-mode i',
					'svg'  => '{{WRAPPER}} .rtsb-archive-view-mode svg',
				],
				'mode_button_align'              => '{{WRAPPER}}',
				'mode_button_height'             => '{{WRAPPER}} .rtsb-archive-view-mode a',
				'mode_button_width'              => '{{WRAPPER}} .rtsb-archive-view-mode a',
				'mode_button_gap'                => '{{WRAPPER}} .rtsb-archive-view-mode',
				'mode_button_bg_color'           => '{{WRAPPER}} .rtsb-archive-view-mode a',
				'text_color'                     => '{{WRAPPER}} .rtsb-archive-view-mode a',
				'mode_button_border'             => '{{WRAPPER}} .rtsb-archive-view-mode a',
				'mode_button_bg_hover_color'     => '{{WRAPPER}} .rtsb-archive-view-mode a:hover,{{WRAPPER}} .rtsb-archive-view-mode a.active',
				'text_hover_color'               => '{{WRAPPER}} .rtsb-archive-view-mode a:hover,{{WRAPPER}} .rtsb-archive-view-mode a.active',
				'mode_button_border_hover_color' => '{{WRAPPER}} .rtsb-archive-view-mode a:hover,{{WRAPPER}} .rtsb-archive-view-mode a.active',
				'mode_button_radius'             => '{{WRAPPER}} .rtsb-archive-view-mode a',
			],
			'rtsb-archive-result-count'    => [
				'align'       => '{{WRAPPER}}',
				'text_color'  => '{{WRAPPER}}',
				// Also target the inner <p> (woocommerce_result_count output): text-decoration
				// is not inherited and does not draw through the descendant block, so it must
				// hit the text element directly. Other typography keeps working via the div.
				'typo'        => '{{WRAPPER}} .rtsb-archive-result-count, {{WRAPPER}} .rtsb-archive-result-count .woocommerce-result-count',
				'text_shadow' => '{{WRAPPER}} .rtsb-archive-result-count',
			],
			'rtsb-products-ordering'       => [
				'typo'                       => '{{WRAPPER}} .rtsb-archive-catalog-ordering :is( .woocommerce-ordering, select )',
				'align'                      => '{{WRAPPER}}',
				'orderby_height'             => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'orderby_width'              => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering,{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'ordering_padding'           => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'orderby_bg_color'           => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'text_color'                 => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'orderby_border'             => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
				'orderby_bg_hover_color'     => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby:hover',
				'text_hover_color'           => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby:hover',
				'orderby_border_hover_color' => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby:hover',
				'orderby_radius'             => '{{WRAPPER}} .rtsb-archive-catalog-ordering .woocommerce-ordering .orderby',
			],
			'rtsb-product-filters'         => ProductFilters\Selectors::get_selectors(),
		];

		return apply_filters( 'rtsb/elements/elementor/widget/archive/selectors', $selectors );
	}

	/**
	 * Cart Selectors.
	 *
	 * @return array
	 */
	private static function cart_widget_selectors(): array {
		$selectors = [
			'rtsb-cross-sells'               => array_merge(
				self::products_loop_default_selector(),
				self::carousel_selector(),
				[
					'section_heading_typography' => '{{WRAPPER}} .rtsb-cross-sell .cross-sells > h2',
					'section_heading_color'      => '{{WRAPPER}} .rtsb-cross-sell .cross-sells > h2',
					'section_title_margin'       => '{{WRAPPER}} .rtsb-cross-sell .cross-sells > h2',
					'section_title_align'        => '{{WRAPPER}} .rtsb-cross-sell .cross-sells > h2',
				],
			),

			'rtsb-cross-sell-product-custom' => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors()
			),

			'rtsb-crosssell-product-slider'  => array_merge(
				self::general_common_selectors(),
				self::general_product_selectors(),
				self::general_slider_selectors(),
			),

			'rtsb-product-cart-totals'       => [
				'show_title'                     => '{{WRAPPER}} .rtsb-cart-totals h2',
				'table_min_width'                => '.rtsb-builder-content {{WRAPPER}} .rtsb-table-horizontal-scroll-on-mobile table.shop_table',
				// Title Style.
				'title_align'                    => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_typo'                     => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_color'                    => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_text_stroke'              => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_text_shadow'              => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_margin'                   => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_padding'                  => '{{WRAPPER}} .rtsb-cart-totals h2',
				'title_border'                   => '{{WRAPPER}} .rtsb-cart-totals h2',
				// Table.
				'table_heading_cell_border'      => '#rtsb-builder-content {{WRAPPER}} .rtsb-cart-totals th,#rtsb-builder-content {{WRAPPER}} .rtsb-cart-totals td',
				'table_heading_cell_padding'     => '{{WRAPPER}} .rtsb-cart-totals th,{{WRAPPER}} .rtsb-cart-totals td',
				'table_heading_typography'       => '{{WRAPPER}} .rtsb-cart-totals th',
				'table_heading_color'            => '{{WRAPPER}} .rtsb-cart-totals th',
				'table_heading_background_color' => '{{WRAPPER}} .rtsb-cart-totals th',
				'table_heading_align'            => '{{WRAPPER}} .rtsb-cart-totals th',
				'total_heading_width'            => '{{WRAPPER}} .rtsb-cart-totals th',
				'table_cell_typography'          => '{{WRAPPER}} .rtsb-cart-totals td *',
				'table_cell_color'               => '{{WRAPPER}} .rtsb-cart-totals td',
				'table_cell_background_color'    => '{{WRAPPER}} .rtsb-cart-totals td',
				'table_cell_align'               => '{{WRAPPER}} .rtsb-cart-totals td, .woocommerce .rtsb-builder-content {{WRAPPER}} ul#shipping_method li',
				'total_cell_heading_width'       => '{{WRAPPER}} .rtsb-cart-totals td',
				'table_wrapper_border'           => '{{WRAPPER}} .rtsb-cart-totals .shop_table',
				'table_wrapper_padding'          => '{{WRAPPER}} .rtsb-cart-totals .shop_table',
				'button_typography'              => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_height'                  => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_width'                   => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_text_color_normal'       => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_bg_color_normal'         => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_border'                  => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_text_color_hover'        => '{{WRAPPER}} .rtsb-cart-totals .button:hover',
				'button_bg_color_hover'          => '{{WRAPPER}} .rtsb-cart-totals .button:hover',
				'button_border_hover_color'      => '{{WRAPPER}} .rtsb-cart-totals .button:hover',
				'button_border_radius'           => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_padding'                 => '{{WRAPPER}} .rtsb-cart-totals .button',
				'button_margin'                  => '{{WRAPPER}} .rtsb-cart-totals .button',

			],
			'rtsb-product-carttable'         => [
				'button_typography'                     => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_height'                         => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_width'                          => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_text_color_normal'              => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_bg_color_normal'                => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_border'                         => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_text_color_hover'               => '{{WRAPPER}} .woocommerce-cart-form .button:hover',
				'button_bg_color_hover'                 => '{{WRAPPER}} .woocommerce-cart-form .button:hover',
				'button_border_hover_color'             => '{{WRAPPER}} .woocommerce-cart-form .button:hover',
				'button_border_radius'                  => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_padding'                        => '{{WRAPPER}} .woocommerce-cart-form .button',
				'button_margin'                         => '{{WRAPPER}} .woocommerce-cart-form .button',
				'coupon_typography'                     => '{{WRAPPER}} .woocommerce-cart-form .coupon input',
				'coupon_input_height'                   => '{{WRAPPER}} .woocommerce-cart-form .coupon input',
				'coupon_input_width'                    => '{{WRAPPER}} .woocommerce-cart-form .coupon input',
				'coupon_padding'                        => '{{WRAPPER}} .woocommerce-cart-form .coupon input',
				'coupon_border_radius'                  => '{{WRAPPER}} .woocommerce-cart-form .coupon input',
				'coupon_input_border'                   => '{{WRAPPER}} .woocommerce-cart-form .coupon .input-text',
				'input_text_color'                      => '{{WRAPPER}} .woocommerce-cart-form input, {{WRAPPER}} .woocommerce-cart-form input::placeholder',
				'input_bg_color'                        => '{{WRAPPER}} .woocommerce-cart-form input',

				'return_shop_button_typography'         => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_height'             => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_text_color_normal'  => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_bg_color_normal'    => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_border'             => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_text_color_hover'   => '{{WRAPPER}} .return-to-shop .button:hover',
				'return_shop_button_bg_color_hover'     => '{{WRAPPER}} .return-to-shop .button:hover',
				'return_shop_button_border_hover_color' => '{{WRAPPER}} .return-to-shop .button:hover',
				'return_shop_button_border_radius'      => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_padding'            => '{{WRAPPER}} .return-to-shop .button',
				'return_shop_button_margin'             => '{{WRAPPER}} .return-to-shop .button',

				'notice_typography'                     => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner',
				'notice_padding'                        => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner',
				'notice_bg_color'                       => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner',
				'notice_text_color'                     => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner',
				'notice_border_color'                   => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner',
				'icon_color'                            => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner svg',
				'notice_icon_size'                      => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner svg',
				'icon_bg_color'                         => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner svg',
				'icon_margin'                           => '{{WRAPPER}} .rtsb-cart-table .wc-block-components-notice-banner svg',

				'table_thumbnail_width'                 => '{{WRAPPER}} .shop_table :is( .product-thumbnail, .product-data ) img',
				'table_thumbnail_border_radius'         => '{{WRAPPER}} .shop_table :is( .product-thumbnail, .product-data ) img',
				'table_thumbnail_padding'               => '{{WRAPPER}} .shop_table :is( .product-thumbnail, .product-data )',
				'cart_remove_icon_size'                 => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a',
				'cart_remove_button_size'               => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a',
				'cart_icon_color'                       => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a',
				'cart_icon_bg_color'                    => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a',
				'cart_icon_hover_color'                 => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a:hover',
				'cart_icon_hover_bg_color'              => '{{WRAPPER}} .woocommerce table.shop_table tbody tr td.product-remove a:hover',
				'table_column_header_padding'           => '{{WRAPPER}} .woocommerce table.shop_table thead tr th',
				'cart_element_alignment'                => '#rtsb-builder-content {{WRAPPER}} .woocommerce table.shop_table th, #rtsb-builder-content {{WRAPPER}} .woocommerce table.shop_table td, #rtsb-builder-content {{WRAPPER}} .woocommerce table.shop_table .table-column-wrapper .product-attributes-wrapper .product-attributes li',
				'cart_table_border'                     => '{{WRAPPER}} .woocommerce table.shop_table',

				'table_wrapper_margin'                  => '{{WRAPPER}} .woocommerce :is(table.shop_table, form )',
				'element_alignment'                     => '#rtsb-builder-content {{WRAPPER}} th.product-subtotal, #rtsb-builder-content {{WRAPPER}} td.product-subtotal',
				'action_button_padding'                 => '{{WRAPPER}} .woocommerce :is(table.shop_table, from ) .actions-button-wrapper',

				'cart_subtotal_price_typo'              => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td .amount',
				'cart_price_color'                      => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td .amount',

				'cart_title_element_alignment'          => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data), #rtsb-builder-content {{WRAPPER}} .woocommerce table.shop_table .table-column-wrapper .product-attributes-wrapper .product-attributes li',
				'cart_product_title_color'              => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data) a',
				'cart_product_title_typo'               => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data) a',
				'cart_product_title_padding'            => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data) .rtsb-product-content, {{WRAPPER}} .rtsb-cart-table table.shop_table tr th:is(.product-name, .product-data) .table-column-wrapper',

				'cart_product_title_meta_typo'          => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data) .product-attributes li',
				'cart_product_title_meta_color'         => '{{WRAPPER}} .rtsb-cart-table table.shop_table tr td:is(.product-name, .product-data) .product-attributes li',

				'cart_subtotal_button_typo'             => '{{WRAPPER}} td.product-subtotal .subtotal-action-button-wrapper a',
				'cart_subtotal_price_padding'           => '{{WRAPPER}} td.product-subtotal .table-column-wrapper .amount',
				'cart_subtotal_price_margin'            => '{{WRAPPER}} td.product-subtotal .table-column-wrapper .amount',
				'cart_subtotal_button_gap'              => '{{WRAPPER}} td.product-subtotal .table-column-wrapper .subtotal-action-button-wrapper',
				'cart_subtotal_button_wrapper_padding'  => '{{WRAPPER}} td.product-subtotal .table-column-wrapper .subtotal-action-button-wrapper',
				'cart_subtotal_col_wrapper_padding'     => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr td.product-subtotal',

				'table_min_width'                       => '.rtsb-builder-content {{WRAPPER}} .rtsb-table-horizontal-scroll-on-mobile table.shop_table',
				'cart_table_cell_width'                 => [
					'th' => '{{WRAPPER}} .shop_table.cart tr th',
					'td' => '{{WRAPPER}} .shop_table.cart tr td',
				],

				'clear_cart_button_bg_color'            => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items',
				'clear_cart_button_text_color'          => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items',
				'clear_cart_button_border_color'        => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items',
				'clear_cart_button_bg_color_hover'      => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items:hover',
				'clear_cart_button_text_color_hover'    => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items:hover',
				'clear_cart_button_border_color_hover'  => '{{WRAPPER}} .woocommerce-cart-form .button.rtsb-clear-cart-items:hover',

				// Table.
				'table_row_border'                      => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table :is( thead, tbody ) tr',

				'cart_table_header_typography'          => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr th',
				'cart_heading_element_alignment'        => '#rtsb-builder-content {{WRAPPER}} .woocommerce table.shop_table th',
				'cart_table_header_bg_color'            => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table thead',
				'cart_table_header_text_color'          => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr th',

				'cart_table_odd_row_bg_color'           => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr:nth-child(odd)',
				'cart_table_odd_row_text_color'         => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr:nth-child(odd) td',

				'cart_table_even_row_bg_color'          => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr:nth-child(even)',
				'cart_table_even_row_text_color'        => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr:nth-child(even) td',

				'cart_table_col_padding'                => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr td:not(.product-remove)',
				'cart_table_col_attributes_padding'     => '{{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr td .product-attributes-wrapper',
				'cart_table_row_padding'                => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tbody tr',

				'cart_link_color'                       => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr td a',
				'cart_link_hover_color'                 => '.rtsb-builder-content {{WRAPPER}} .rtsb-cart-table table.shop_table tr td a:hover',

				// Cart Quantity.
				'quantity_input_width'                  => '{{WRAPPER}} .woocommerce-cart-form input.qty',
				'quantity_height'                       => [
					'full' => '{{WRAPPER}} .woocommerce-cart-form input[type=number], {{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group:is( .rtsb-quantity-box-group-style-1, .rtsb-quantity-box-group-style-2 ) .rtsb-quantity-btn',
					'half' => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group:is( .rtsb-quantity-box-group-style-3, .rtsb-quantity-box-group-style-4 ) .rtsb-quantity-btn',
				],
				'quantity_increment_button_padding'     => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group .rtsb-quantity-btn.rtsb-quantity-plus',
				'quantity_decrement_button_padding'     => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group .rtsb-quantity-btn.rtsb-quantity-minus',

				'quantity_button_width'                 => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group .rtsb-quantity-btn',
				'icon_size'                             => [
					'icon' => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group .rtsb-quantity-btn',
					'svg'  => '{{WRAPPER}} .woocommerce-cart-form .rtsb-quantity-box-group .rtsb-quantity-btn svg',
				],
				'quantity_icon_color'                   => '{{WRAPPER}} .woocommerce-cart-form .quantity :is(i, svg)',
				'quantity_icon_hover_color'             => '{{WRAPPER}} .woocommerce-cart-form .quantity .rtsb-quantity-btn:hover :is(i, svg)',
				'text_typography'                       => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'quantity_number_color'                 => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'quantity_background_color'             => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'quantity_border'                       => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'quantity_radius'                       => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'qunatity_padding'                      => '{{WRAPPER}} .woocommerce-cart-form .quantity input',
				'quantity_wrapper_background_color'     => '{{WRAPPER}} .woocommerce-cart-form .quantity .rtsb-quantity-box-group',
				'qunatity_wrapper_padding'              => '{{WRAPPER}} .woocommerce-cart-form .quantity .rtsb-quantity-box-group',
				'quantity_wrapper_radius'               => '{{WRAPPER}} .woocommerce-cart-form .quantity .rtsb-quantity-box-group',
			],
		];

		return apply_filters( 'rtsb/elements/elementor/widget/cart/selectors', $selectors );
	}

	/**
	 * Checkout Selectors.
	 *
	 * @return array
	 */
	private static function checkout_widget_selectors(): array {
		$selectors = [
			'rtsb-billing-form'        => [
				// Title.
				'show_title'                  => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_typo'                  => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_align'                 => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_color'                 => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_text_stroke'           => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_text_shadow'           => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_margin'                => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_padding'               => '{{WRAPPER}} .woocommerce-billing-fields h3',
				'title_border'                => '{{WRAPPER}} .woocommerce-billing-fields h3',
				// Label.
				'fields_label_typo'           => '{{WRAPPER}} .woocommerce-billing-fields label',
				'fields_label_color'          => '{{WRAPPER}} .woocommerce-billing-fields label',
				'fields_label_reguired_color' => '{{WRAPPER}} .woocommerce-billing-fields label .required',
				'fields_label_margin'         => '{{WRAPPER}} .woocommerce-billing-fields label:not(.radio)',

				// Input Fields.
				'textarea_fields_height'      => '{{WRAPPER}} .woocommerce-billing-fields textarea',
				'fields_height'               => '{{WRAPPER}} .woocommerce-billing-fields input:not([type=checkbox],[type=radio]), {{WRAPPER}} .rtsb-form-billing .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select',
				'fields_border'               => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea), {{WRAPPER}} .woocommerce-billing-fields .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select',

				'fields_border_radius'        => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]), {{WRAPPER}} .woocommerce-billing-fields .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select',

				'fields_text_color'           => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]), {{WRAPPER}} .woocommerce-billing-fields .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select',
				'fields_bg_color'             => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]), {{WRAPPER}} .woocommerce-billing-fields .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select',

				// Hover.
				'fields_hover_border'         => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]):is(:hover, :focus, :active), {{WRAPPER}} .woocommerce-billing-fields .select2-container--open .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select:is(:hover, :focus, :active,:focus-visible)',

				'fields_border_radius_hover'  => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]):is(:hover, :focus, :active), {{WRAPPER}} .woocommerce-billing-fields .select2-container--open .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields select:is(:hover, :focus, :active,:focus-visible)',

				'fields_hover_text_color'     => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]):is(:hover, :focus, :active), {{WRAPPER}} , {{WRAPPER}} .woocommerce-billing-fields .select2-container--open .select2-selection--single',
				'fields_hover_bg_color'       => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]):is(:hover, :focus, :active), {{WRAPPER}} .woocommerce-billing-fields .select2-container--open .select2-selection--single',
				'fields_padding'              => '{{WRAPPER}} .woocommerce-billing-fields :is(input, textarea):not([type=checkbox],[type=radio]),{{WRAPPER}} .rtsb-form-billing .select2-container--default .select2-selection--single, {{WRAPPER}} .woocommerce-billing-fields :is( select, .select2-selection--single )',

				'form_row_margin'             => '{{WRAPPER}} .woocommerce-billing-fields p.form-row',
				'form_wrapper_margin'         => '{{WRAPPER}} .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper',

			],
			'rtsb-shipping-form'       => [
				// Label.
				'form_heading_gap'            => '{{WRAPPER}} .rtsb-form-shipping #ship-to-different-address',
				'form_heading_color'          => '{{WRAPPER}} .rtsb-form-shipping #ship-to-different-address',
				'form_heading_typo'           => '{{WRAPPER}} .rtsb-form-shipping #ship-to-different-address',

				'fields_label_typo'           => '{{WRAPPER}} .rtsb-form-shipping .shipping_address label',
				'fields_label_color'          => '{{WRAPPER}} .rtsb-form-shipping .shipping_address label',
				'fields_label_reguired_color' => '{{WRAPPER}} .rtsb-form-shipping .shipping_address label .required',
				'fields_label_margin'         => '{{WRAPPER}} .rtsb-form-shipping .shipping_address label:not(.radio)',

				// Input Fields.
				'textarea_fields_height'      => '{{WRAPPER}} .rtsb-form-shipping textarea',
				'fields_height'               => '{{WRAPPER}} .rtsb-form-shipping input:not([type=checkbox],[type=radio]), {{WRAPPER}} .rtsb-form-shipping .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select',
				'fields_border'               => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ), {{WRAPPER}} .rtsb-form-shipping .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select',

				'fields_border_radius'        => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ), {{WRAPPER}} .rtsb-form-shipping .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select',

				'fields_text_color'           => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ), {{WRAPPER}} .rtsb-form-shipping .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select',
				'fields_bg_color'             => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ), {{WRAPPER}} .rtsb-form-shipping .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select',

				// Hover.
				'fields_hover_border'         => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active), {{WRAPPER}} .rtsb-form-shipping .select2-container--open .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select:is(:hover, :focus, :active,:focus-visible)',

				'fields_border_radius_hover'  => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active), {{WRAPPER}} .rtsb-form-shipping .select2-container--open .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping select:is(:hover, :focus, :active,:focus-visible)',

				'fields_hover_text_color'     => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active), {{WRAPPER}} , {{WRAPPER}} .rtsb-form-shipping .select2-container--open .select2-selection--single',
				'fields_hover_bg_color'       => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active), {{WRAPPER}} .rtsb-form-shipping .select2-container--open .select2-selection--single',
				'fields_padding'              => '{{WRAPPER}} .rtsb-form-shipping :is( input:not([type=checkbox],[type=radio]), textarea ),{{WRAPPER}} .rtsb-form-billing .select2-container--default .select2-selection--single, {{WRAPPER}} .rtsb-form-shipping :is( select, .select2-selection--single )',
				'form_row_margin'             => '{{WRAPPER}} .rtsb-form-shipping p.form-row',
				'form_wrapper_margin'         => '{{WRAPPER}} .rtsb-form-shipping .shipping_address',

			],
			'rtsb-order-notes'         => [
				'show_title'                  => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_typo'                  => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_align'                 => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_color'                 => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_text_stroke'           => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_text_shadow'           => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_margin'                => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_padding'               => '{{WRAPPER}} .rtsb-form-order-note h3',
				'title_border'                => '{{WRAPPER}} .rtsb-form-order-note h3',

				'fields_label_typo'           => '{{WRAPPER}} .rtsb-form-order-note label',
				'fields_label_color'          => '{{WRAPPER}} .rtsb-form-order-note label',
				'fields_label_reguired_color' => '{{WRAPPER}} .rtsb-form-order-note label .required',
				'fields_label_margin'         => '{{WRAPPER}} .rtsb-form-order-note label:not(.radio)',

				// Input Fields.
				'textarea_fields_height'      => '{{WRAPPER}} .rtsb-form-order-note textarea',
				'fields_height'               => '{{WRAPPER}} .rtsb-form-order-note input:not([type=checkbox],[type=radio]), {{WRAPPER}} .rtsb-form-order-note .select2-selection--single, {{WRAPPER}} .rtsb-form-order-note select',
				'fields_border'               => '{{WRAPPER}} .rtsb-form-order-note :is( textarea, input, select, .select2-selection--multiple )',
				'fields_border_radius'        => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea )',
				'fields_text_color'           => '{{WRAPPER}} .rtsb-form-order-note :is( textarea, input, select, .select2-selection--multiple )',
				'fields_bg_color'             => '{{WRAPPER}} .rtsb-form-order-note :is( textarea, input, select, .select2-selection--multiple )',

				// Hover.
				'fields_hover_border'         => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active)',

				'fields_border_radius_hover'  => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active)',
				'fields_hover_text_color'     => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active)',
				'fields_hover_bg_color'       => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea ):is(:hover, :focus, :active)',

				'fields_padding'              => '{{WRAPPER}} .rtsb-form-order-note :is( input:not([type=checkbox],[type=radio]), textarea )',
				'form_row_margin'             => '{{WRAPPER}} .rtsb-form-order-note .woocommerce-input-wrapper',
				'form_wrapper_margin'         => '{{WRAPPER}} .rtsb-form-order-note .woocommerce-input-wrapper',

			],

			'rtsb-order-review'        => [
				// Title.
				'show_title'                  => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'table_min_width'             => '.rtsb-builder-content {{WRAPPER}} .rtsb-table-horizontal-scroll-on-mobile table.shop_table',
				'title_typo'                  => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_align'                 => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_color'                 => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_text_stroke'           => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_text_shadow'           => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_margin'                => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_padding'               => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'title_border'                => '#rtsb-builder-content {{WRAPPER}} .rtsb-checkout-order-review #order_review_heading',
				'table_wrapper_border'        => '#rtsb-builder-content {{WRAPPER}} #order_review',
				'table_wrapper_padding'       => '#rtsb-builder-content {{WRAPPER}} #order_review',
				'table_wrapper_margin'        => '#rtsb-builder-content {{WRAPPER}} #order_review',
				'table_border'                => '{{WRAPPER}} #order_review table :is( td, th )',
				'label_typo'                  => '{{WRAPPER}} #order_review thead th',
				'label_color'                 => '{{WRAPPER}} #order_review thead th',
				'label_bg_color'              => '{{WRAPPER}} #order_review thead th',
				'label_item_padding'          => '{{WRAPPER}} #order_review thead th',
				// Table Body.
				'body_text_typo'              => '{{WRAPPER}} #order_review tbody td',
				'body_text_color'             => '{{WRAPPER}} #order_review tbody td',
				'body_item_padding'           => '{{WRAPPER}} #order_review tbody td',
				'table_body_bg_color'         => '{{WRAPPER}} #order_review tbody td',
				// Counter.
				'counter_text_color'          => '{{WRAPPER}} .product-quantity',
				'counter_style'               => '{{WRAPPER}} .product-quantity',
				// Quantity selector.
				'qty_align'                   => '{{WRAPPER}} .rtsb-order-review-quantity',
				'qty_wrapper_margin'          => '{{WRAPPER}} .rtsb-order-review-quantity',
				'qty_box_border'              => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-group',
				'qty_box_radius'              => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-group',
				'qty_box_bg_color'            => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-group',
				'qty_box_height'              => '{{WRAPPER}} .rtsb-order-review-quantity :is( .rtsb-order-qty-btn, .rtsb-order-qty-input )',
				'qty_input_width'             => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-input',
				'qty_input_typo'              => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-input',
				'qty_input_color'             => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-input',
				'qty_input_bg_color'          => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-input',
				'qty_input_border_color'      => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-input',
				'qty_btn_width'               => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn',
				'qty_icon_size'               => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn svg',
				'qty_btn_color'               => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn',
				'qty_btn_bg_color'            => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn',
				'qty_btn_hover_color'         => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn:hover',
				'qty_btn_hover_bg_color'      => '{{WRAPPER}} .rtsb-order-review-quantity .rtsb-order-qty-btn:hover',
				// Table Footer.
				'table_footer_label_typo'     => '{{WRAPPER}} #order_review tfoot :is( th, td )',
				'table_footer_label_color'    => '{{WRAPPER}} #order_review tfoot th',
				'table_footer_text_color'     => '{{WRAPPER}} #order_review tfoot td',
				'table_footer_label_bg_color' => '{{WRAPPER}} #order_review tfoot :is( th, td )',
				'table_footer_item_padding'   => '{{WRAPPER}} #order_review tfoot td, {{WRAPPER}} #order_review tfoot th',

			],
			'rtsb-checkout-payment'    => [
				'payment_method_wrapper_bg_color'       => '{{WRAPPER}}  #payment ul.payment_methods',
				'payment_link_color'                    => '{{WRAPPER}} .rtsb-checkout-payment a',
				'payment_link_hover_color'              => '{{WRAPPER}} .rtsb-checkout-payment a:hover',

				'payment_label_typography'              => '{{WRAPPER}} .rtsb-checkout-payment .wc_payment_method label',
				'payment_label_color'                   => '{{WRAPPER}} .rtsb-checkout-payment .wc_payment_method label, {{WRAPPER}} #payment .payment_methods li input[type=radio]:first-child+label:before',
				'payment_label_active_hover_color'      => '{{WRAPPER}} .rtsb-checkout-payment .wc_payment_method label:hover, {{WRAPPER}} #payment .payment_methods li input[type=radio]:first-child:checked+label:before, {{WRAPPER}} #payment .payment_methods li input[type=radio]:first-child:checked+label',
				'payment_label_bg_color'                => '{{WRAPPER}} .rtsb-checkout-payment .wc_payment_method label',
				'payment_method_space_between'          => '{{WRAPPER}} #payment ul.payment_methods li input',
				'payment_method_bacs_padding'           => '{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods>.woocommerce-PaymentMethod>label,{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods>.wc_payment_method>label',
				'payment_method_wrap_padding'           => '{{WRAPPER}} #payment ul.payment_methods',
				'payment_method_bacs_margin'            => '{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods>.woocommerce-PaymentMethod>label,{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods>.wc_payment_method>label',

				'payment_description_typography'        => '{{WRAPPER}} .rtsb-checkout-payment p',
				'payment_description_text_color'        => '{{WRAPPER}} .rtsb-checkout-payment p',
				'payment_description_bg_color'          => '{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods > li .payment_box',
				'payment_description_border'            => '{{WRAPPER}} .rtsb-checkout-payment #payment ul.payment_methods',
				'payment_method_desc_padding'           => '{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods li .payment_box',
				'payment_method_desc_margin'            => '{{WRAPPER}} .rtsb-checkout-payment #payment .payment_methods li .payment_box',

				'payment_button_description_typography' => '{{WRAPPER}} .rtsb-checkout-payment #payment .place-order p',
				'payment_button_description_text_color' => '{{WRAPPER}} .rtsb-checkout-payment #payment .place-order  p',
				'payment_button_description_bg_color'   => '{{WRAPPER}} .rtsb-checkout-payment #payment .place-order',
				'payment_button_wrapper_padding'        => '{{WRAPPER}} .rtsb-checkout-payment #payment .place-order',
				'payment_button_wrapper_margin'         => '{{WRAPPER}} .rtsb-checkout-payment #payment .place-order',

				// Button section.
				'button_height'                         => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_width'                          => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_typography'                     => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'submit_button_alignment'               => [
					'text-align' => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
					'float'      => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				],
				'button_text_color_normal'              => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_bg_color_normal'                => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_border'                         => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_text_color_hover'               => '{{WRAPPER}} .rtsb-checkout-payment #place_order:hover',
				'button_bg_color_hover'                 => '{{WRAPPER}} .rtsb-checkout-payment #place_order:hover',
				'button_border_hover_color'             => '{{WRAPPER}} .rtsb-checkout-payment #place_order:hover',
				'button_border_radius'                  => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_padding'                        => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
				'button_margin'                         => '{{WRAPPER}} .rtsb-checkout-payment #place_order',
			],
			'rtsb-coupon-form'         => array_merge(
				self::button_settings_selector(),
				[
					'typo'                            => '{{WRAPPER}} p',
					'align'                           => '{{WRAPPER}} p',
					'text_color'                      => '{{WRAPPER}} p',
					'text_shadow'                     => '{{WRAPPER}} p',
					'text_margin'                     => '{{WRAPPER}} p',

					// Input Fields.
					'fields_height'                   => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',
					'fields_border'                   => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',
					'fields_text_color'               => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',
					'fields_bg_color'                 => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',
					'form_fields_border_radius'       => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',

					// Hover.
					'fields_hover_border'             => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text:is(:hover, :focus, :active)',
					'fields_hover_text_color'         => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text:is(:hover, :focus, :active)',
					'fields_hover_bg_color'           => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text:is(:hover, :focus, :active)',
					'form_fields_border_radius_hover' => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text:is(:hover, :focus, :active)',

					'fields_padding'                  => '{{WRAPPER}} .rtsb-checkout-coupon-form input.input-text',
					'fields_gap'                      => '{{WRAPPER}} .rtsb-checkout-coupon-form .coupon-form-fields',

					'infobox_typography'              => '{{WRAPPER}} .rtsb-checkout-coupon-form :is(.woocommerce-info, .wc-block-components-notice-banner__content )',
					'infobox_bg_color'                => '{{WRAPPER}} .rtsb-checkout-coupon-form :is(.woocommerce-info, .wc-block-components-notice-banner )',
					'infobox_text_color'              => '{{WRAPPER}} .rtsb-checkout-coupon-form :is(.woocommerce-info, .wc-block-components-notice-banner__content )',

					'infobox_text_link_color'         => '{{WRAPPER}} .rtsb-checkout-coupon-form a',
					'infobox_border_color'            => '{{WRAPPER}} .rtsb-checkout-coupon-form :is(.woocommerce-info, .wc-block-components-notice-banner ) ',
					'infobox_icon_size'               => [
						'icon' => '{{WRAPPER}} :is( .woocommerce-info::before, .woocommerce-info i), {{WRAPPER}} .wc-block-components-notice-banner__content i',
						'svg'  => '{{WRAPPER}} :is( .woocommerce-info svg, .wc-block-components-notice-banner__content svg )',
					],

					'infobox_icon_margin'             => '{{WRAPPER}} .woocommerce-info::before, {{WRAPPER}} .woocommerce-info :is( i, svg ), {{WRAPPER}} .wc-block-components-notice-banner__content :is( i, svg ) ',

					'infobox_icon_color'              => '{{WRAPPER}} :is( .woocommerce-info::before, .woocommerce-info i, .woocommerce-info svg ), {{WRAPPER}} :is( .wc-block-components-notice-banner__content svg, .wc-block-components-notice-banner__content i )',

					'infobox_wrapper_border'          => '{{WRAPPER}} .rtsb-checkout-coupon-form ',
					'infobox_border_radius'           => '{{WRAPPER}} .rtsb-checkout-coupon-form  .wc-block-components-notice-banner',
					'infobox_padding'                 => '{{WRAPPER}} .rtsb-checkout-coupon-form :is( .woocommerce-info, .wc-block-components-notice-banner__content )',
					'form_margin'                     => '{{WRAPPER}} .rtsb-checkout-coupon-form .coupon-form-fields',
					'form_area_padding'               => '{{WRAPPER}} .rtsb-checkout-coupon-form .woocommerce-form-coupon',
					'form_area_margin'                => '{{WRAPPER}} .rtsb-checkout-coupon-form .woocommerce-form-coupon',

					'form_area_wrapper_border'        => '{{WRAPPER}} .rtsb-checkout-coupon-form .woocommerce-form-coupon',
					'form_area_border_radius'         => '{{WRAPPER}} .rtsb-checkout-coupon-form .woocommerce-form-coupon',

				]
			),
			'rtsb-checkout-login-form' => array_merge(
				self::button_settings_selector(),
				[
					'typo'                            => '{{WRAPPER}} p',
					'align'                           => '{{WRAPPER}} p',
					'text_color'                      => '{{WRAPPER}} p',
					'text_shadow'                     => '{{WRAPPER}} p',
					'text_margin'                     => '{{WRAPPER}} p',

					// Input Fields.
					'fields_height'                   => '{{WRAPPER}} input.input-text',
					'fields_border'                   => '{{WRAPPER}} input.input-text',
					'form_fields_border_radius'       => '{{WRAPPER}} input.input-text',
					'fields_text_color'               => '{{WRAPPER}} input.input-text',
					'fields_bg_color'                 => '{{WRAPPER}} input.input-text',

					// Hover.
					'fields_hover_border'             => '{{WRAPPER}} input.input-text:is(:hover, :focus, :active)',
					'form_fields_border_radius_hover' => '{{WRAPPER}} input.input-text:is(:hover, :focus, :active)',
					'fields_hover_text_color'         => '{{WRAPPER}} input.input-text:is(:hover, :focus, :active)',
					'fields_hover_bg_color'           => '{{WRAPPER}} input.input-text:is(:hover, :focus, :active)',

					'fields_padding'                  => '{{WRAPPER}} .rtsb-checkout-login-form input.input-text',
					'fields_gap'                      => '{{WRAPPER}} .rtsb-checkout-login-form .login-form-fields',
					'label_gap'                       => '{{WRAPPER}} .rtsb-checkout-login-form .field-wrapper label',
					// Info Box.
					'infobox_typography'              => '{{WRAPPER}} .rtsb-checkout-login-form :is(.woocommerce-info, .wc-block-components-notice-banner__content )',
					'infobox_bg_color'                => '{{WRAPPER}} .rtsb-checkout-login-form :is(.woocommerce-info, .wc-block-components-notice-banner.is-info )',
					'infobox_text_color'              => '{{WRAPPER}} .rtsb-checkout-login-form :is(.woocommerce-info, .wc-block-components-notice-banner__content )',

					'infobox_text_link_color'         => '{{WRAPPER}} .rtsb-checkout-login-form a',

					'infobox_border_color'            => '{{WRAPPER}} .rtsb-checkout-login-form :is(.woocommerce-info, .wc-block-components-notice-banner.is-info )',
					'infobox_icon_size'               => [
						'icon' => '{{WRAPPER}} :is( .woocommerce-info::before, .woocommerce-info i), {{WRAPPER}} .wc-block-components-notice-banner__content i',
						'svg'  => '{{WRAPPER}} :is( .woocommerce-info svg, .wc-block-components-notice-banner__content svg )',
					],

					'infobox_icon_margin'             => '{{WRAPPER}} .woocommerce-info::before, {{WRAPPER}} .woocommerce-info :is( i, svg ), {{WRAPPER}} .wc-block-components-notice-banner :is( i, svg ) ',

					'infobox_icon_color'              => '{{WRAPPER}} :is( .woocommerce-info::before, .woocommerce-info i, .woocommerce-info svg ), {{WRAPPER}} :is( .wc-block-components-notice-banner svg, .wc-block-components-notice-banner i )',

					'infobox_wrapper_border'          => '{{WRAPPER}} .rtsb-checkout-login-form',
					'infobox_border_radius'           => '{{WRAPPER}} .rtsb-checkout-login-form .wc-block-components-notice-banner',
					'infobox_padding'                 => '{{WRAPPER}} .rtsb-checkout-login-form :is( .woocommerce-info, .wc-block-components-notice-banner__content ) ',
					'form_margin'                     => '{{WRAPPER}} .rtsb-checkout-login-form .login-form-fields',
					'form_area_padding'               => '{{WRAPPER}} .rtsb-checkout-login-form .woocommerce-form-login',
					'form_area_margin'                => '{{WRAPPER}} .rtsb-checkout-login-form .woocommerce-form-login',
					'form_area_wrapper_border'        => '{{WRAPPER}} .rtsb-checkout-login-form .woocommerce-form-login',
					'form_area_border_radius'         => '{{WRAPPER}} .rtsb-checkout-login-form  .woocommerce-form-login',

				]
			),
			'rtsb-shipping-method'     => [

				// General.
				'general_cart_table_border' => '{{WRAPPER}} .rtsb_woocommerce_shipping_methods :is( td )',
				'general_cart_table_bg'     => '{{WRAPPER}} .rtsb_woocommerce_shipping_methods :is( td )',
				'general_table_padding'     => '{{WRAPPER}} .rtsb_woocommerce_shipping_methods :is( td )',

				// Title.
				'show_title'                => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_typo'                => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_align'               => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_color'               => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_text_stroke'         => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_text_shadow'         => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_margin'              => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_padding'             => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				'title_border'              => '{{WRAPPER}} .rtsb-shipping-method .shipping-method-title',
				// Label.
				'label_title_typo'          => '{{WRAPPER}} .rtsb-shipping-method .woocommerce-shipping-methods label',
				'label_title_color'         => '{{WRAPPER}} .rtsb-shipping-method .woocommerce-shipping-methods label',
				'label_space_between'       => '{{WRAPPER}}  .woocommerce .rtsb-builder-content ul#shipping_method li',
				'label_item_space'          => '{{WRAPPER}}  .woocommerce .rtsb-builder-content ul#shipping_method',
			],
		];

		return apply_filters( 'rtsb/elements/elementor/widget/checkout/selectors', $selectors );
	}

	/**
	 * Common CSS Selectors.
	 *
	 * @return array
	 */
	private static function general_common_selectors(): array {
		return apply_filters(
			'rtsb/elementor/common_selectors',
			[
				'columns'        => [
					'cols'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
					'masonry'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row.rtsb-masonry',
					'list_cols'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row[class*="list-layout"]',
					'list_masonry'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row[class*="list-layout"].rtsb-masonry',
					'grid_gap'           => [
						'padding'        => '{{WRAPPER}} .rtsb-elementor-container [class*=rtsb-col-]',
						'margin'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
						'slider_layout3' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-slider-layout3',
						'slider_layout9' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-slider-layout9',
					],
					'image_width'        => [
						'image' => '{{WRAPPER}} .rtsb-product .rtsb-list-item .rtsb-product-img, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-layout6 .rtsb-content-left',
					],
					'v_action_btn_width' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-list-layout3 .rtsb-product .rtsb-action-buttons.after-content',
					'image_gap'          => '{{WRAPPER}} .rtsb-product .rtsb-list-item,{{WRAPPER}} .rtsb-elementor-container .rtsb-list-layout6 .rtsb-product .rtsb-list-item .rtsb-product-content',
					'grid_alignment'     => [
						'text_align'      => '{{WRAPPER}} .rtsb-elementor-container, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout4 .rtsb-product-content',
						'justify_content' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-category, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-list, {{WRAPPER}} .rtsb-elementor-container .price-wrapper, {{WRAPPER}} .rtsb-elementor-container [class*=list-layout] .rtsb-product-category, {{WRAPPER}} .rtsb-elementor-container .rtwpvs-archive-variation-wrapper, {{WRAPPER}} .rtsb-elementor-container .product-rating, {{WRAPPER}} .rtsb-elementor-container .category-title-with-count, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product-content .rtsb-product-category,{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout4 .rtsb-product-content .rtsb-product-category,{{WRAPPER}} .rtsb-grid-layout4 .rtsb-content-footer.show-cart-text .rtsb-action-button-list',
					],
				],
				'color_scheme'   => [
					'primary_color'   => '{{WRAPPER}}',
					'secondary_color' => '{{WRAPPER}}',
				],
				'count'          => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
					'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .product-count, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .product-count',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .product-count, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .product-count',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid:hover .single-category-area .product-count, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid:hover .category-wrapper .product-count',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid:hover .single-category-area .product-count, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid:hover .category-wrapper .product-count',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .product-count',
				],
				'product_badges' => [
					'typography'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline',
					'color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline',
					'bg_color'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline',
					'border_color'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline.angle-right::after',
					'border_radius' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline',
					'padding'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline, {{WRAPPER}} .rtsb-elementor-container .rtsb-badge.type-image',
					'margin'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-tag-fill, {{WRAPPER}} .rtsb-elementor-container .rtsb-tag-outline, {{WRAPPER}} .rtsb-elementor-container .rtsb-badge.type-image',
					'position_x'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img > .rtsb-promotion',
					'position_y'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img > .rtsb-promotion',
				],
				'image'          => [
					'overlay'              => '{{WRAPPER}} .rtsb-elementor-container.has-overlay [class*=category-single-layout] .single-category-area .rtsb-img-link::before',
					'hover_overlay'        => '{{WRAPPER}} .rtsb-elementor-container.has-overlay .single-category-area .rtsb-img-link::after',
					'img_bg_color'         => '{{WRAPPER}} .rtsb-product-img .rtsb-product-image, {{WRAPPER}} .rtsb-product-img .default-img',
					'img_wrapper_bg_color' => '{{WRAPPER}} .rtsb-product-img figure',
					'border'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img figure',
					'width'                => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item .rtsb-product-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item .default-img, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .rtsb-product-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .default-img, {{WRAPPER}} .rtsb-elementor-container .rtsb-product-img .default-img, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item .rtsb-product-image',
					'max_width'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item .rtsb-product-img figure, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image .rtsb-product-img figure,{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img figure, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item .rtsb-product-img figure',
					'border_radius'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img figure, {{WRAPPER}} .rtsb-elementor-container.has-overlay [class*=category-single-layout] .single-category-area::before, {{WRAPPER}} .rtsb-elementor-container.has-overlay [class*=category-single-layout] .single-category-area::after',
					'margin'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-img',

				],
				'advanced'       => [
					'wrapper_padding'              => '{{WRAPPER}} .rtsb-elementor-container > .rtsb-row',
					'content_padding'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image',
					'element_margin'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid',
					'element_padding'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item, {{WRAPPER}} .rtsb-elementor-container .single-category-area, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .category-wrapper, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .fade-action-button, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-layout3 .rtsb-product .rtsb-action-buttons.after-content',
					'content_bg_color'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image',
					'element_bg_color'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item, {{WRAPPER}} .rtsb-elementor-container .single-category-area, {{WRAPPER}} .rtsb-elementor-container .category-wrapper,{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product .fade-action-button,{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product .product-fade-content',
					'rtsb_el_element_shadow'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item, {{WRAPPER}} .rtsb-elementor-container .single-category-area, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .category-wrapper, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product .product-fade-content',
					'content_hover_bg_color'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image:hover',
					'element_hover_bg_color'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item:hover, {{WRAPPER}} .rtsb-elementor-container .single-category-area:hover, {{WRAPPER}} .rtsb-elementor-container .category-wrapper:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product:hover :is( .product-fade-content, .fade-action-button )',
					'rtsb_el_element_hover_shadow' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item:hover, {{WRAPPER}} .rtsb-elementor-container .single-category-area:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .category-wrapper:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .rtsb-category-grid:hover .category-title-with-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-grid-layout3 .rtsb-product:hover .product-fade-content',
					'rtsb_el_wrapper_border'       => '{{WRAPPER}} .rtsb-elementor-container > .rtsb-row',
					'wrapper_border_radius'        => '{{WRAPPER}} .rtsb-elementor-container > .rtsb-row',
					'rtsb_el_element_border'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item, {{WRAPPER}} .rtsb-elementor-container .single-category-area, {{WRAPPER}} .rtsb-elementor-container .category-wrapper',
					'element_border_hover_color'   => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item:hover, {{WRAPPER}} .rtsb-elementor-container .single-category-area:hover, {{WRAPPER}} .rtsb-elementor-container .category-wrapper:hover',
					'element_border_radius'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-grid-item, {{WRAPPER}} .rtsb-elementor-container .rtsb-list-item, {{WRAPPER}} .rtsb-elementor-container .single-category-area, {{WRAPPER}} .rtsb-elementor-container .category-wrapper',
					'rtsb_el_content_border'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image',
					'content_border_hover_color'   => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image:hover',
					'content_border_radius'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-row:not(.rtsb-category-layout2) .rtsb-category-content, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image',
				],
				'badges_module'  => [
					'direction'      => '{{WRAPPER}} .rtsb-promotion .rtsb-badge-group-style',
					'alignment'      => '{{WRAPPER}} .rtsb-promotion .rtsb-badge-group-style',
					'alignment_wrap' => '{{WRAPPER}} .rtsb-badge-container .rtsb-promotion:not(.rtsb-badge-group-style)',
				],
			]
		);
	}

	/**
	 * Common CSS Selectors.
	 *
	 * @return array
	 */
	public static function general_slider_selectors(): array {
		return [
			'slider_buttons' => [
				'arrow_size'          => [
					'icon' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow i',
					'svg'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow svg',
				],
				'arrow_width'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'arrow_height'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'arrow_line_height'   => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow i',
				'dot_width'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_height'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_spacing'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'color'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'bg_color'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'hover_color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover',
				'hover_bg_color'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover',
				'dot_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_active_color'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet-active',
				'border'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'border_hover_color'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet:hover',
				'active_border_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet-active',
				'border_radius'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'wrapper_padding'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-nav, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination',
			],
		];
	}

	/**
	 * Category CSS Selectors.
	 *
	 * @return array
	 */
	private static function general_product_selectors(): array {
		return apply_filters(
			'rtsb/elementor/general_product_selectors',
			[
				'section_title'       => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title:hover',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-section-title',
				],
				'product_title'       => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .product-title',
					'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .product-title',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .product-title',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper,{{WRAPPER}} .rtsb-elementor-container .product-title',
					// Layout 2 (grid/slider) reveals a hover button that overlays the title wrapper and captures :hover, so the
					// wrapper's own :hover never fires; the extra `.rtsb-product:hover .product-title-with-btn` clause re-applies the
					// hover text/bg/border color on product-card hover. NOTE: layout 2 fades the title text out on hover, so the
					// hover *text* color is not visibly noticeable there — the wrapper background and border remain visible.
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper:hover .product-title, {{WRAPPER}} .rtsb-elementor-container .rtsb-product:hover .product-title-with-btn .product-title',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper:hover,{{WRAPPER}} .rtsb-elementor-container .product-title:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-product:hover .product-title-with-btn .rtsb-product-title-wrapper',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-product:hover .product-title-with-btn .rtsb-product-title-wrapper',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper,{{WRAPPER}} .rtsb-elementor-container .product-title',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-title-wrapper,{{WRAPPER}} .rtsb-elementor-container .product-title',
				],
				'short_description'   => array_fill_keys(
					[
						'typography',
						'alignment',
						'color',
						'bg_color',
						'border',
						'padding',
						'margin',
					],
					'{{WRAPPER}} .rtsb-elementor-container .product-short-description'
				),
				'action_buttons'      => [
					'action_btn_alignment' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product .rtsb-action-buttons.horizontal-floating-btn .rtsb-action-button-list',
					'action_btn_gap'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-action-button-list',
					'icon_gap'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-action-btn.has-text',
				],
				'product_price'       => [
					'typography'              => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper .price > .woocommerce-Price-amount, {{WRAPPER}} .rtsb-elementor-container .price-wrapper del',
					'sale_typography'         => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper ins',
					'regular_text_decoration' => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper .price > .woocommerce-Price-amount, {{WRAPPER}} .rtsb-elementor-container .price-wrapper del',
					'sale_text_decoration'    => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper ins',
					'alignment'               => '{{WRAPPER}} .rtsb-elementor-container .product-price',
					'color'                   => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper :is( ins, ins span bdi)',
					'regular_color'           => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper :is( bdi, del bdi), {{WRAPPER}} .rtsb-elementor-container.products .price-wrapper del',
					'crossed_regular_color'   => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper :is(del bdi), {{WRAPPER}} .rtsb-elementor-container.products .price-wrapper del',
					'border'                  => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper',
					'border_hover_color'      => '{{WRAPPER}} .rtsb-elementor-container .price-wrapper:hover',
					'padding'                 => '{{WRAPPER}} .rtsb-elementor-container .product-price',
					'margin'                  => '{{WRAPPER}} .rtsb-elementor-container .product-price',
				],
				'product_rating'      => [
					'typography'      => '{{WRAPPER}} .rtsb-elementor-container .star-rating',
					'alignment'       => '{{WRAPPER}} .rtsb-elementor-container .product-rating',
					'color'           => '{{WRAPPER}} .rtsb-elementor-container :is(.star-rating span):before',
					'bg_color'        => '{{WRAPPER}} .rtsb-elementor-container  :is(.star-rating):before',
					'padding'         => '{{WRAPPER}} .rtsb-elementor-container .star-rating',
					'spacing_preset2' => '{{WRAPPER}} .rtsb-elementor-container .product-rating.preset2, {{WRAPPER}} .rtsb-elementor-container .product-rating.preset2 .inner-wrapper',
					'margin'          => '{{WRAPPER}} .rtsb-elementor-container .product-rating',
				],
				'product_categories'  => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content .rtsb-product-category',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-list li > a',
					'wrapper_margin'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-category',
				],
				'product_brands'      => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-product-content .rtsb-brand-category',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-list li > a',
					'wrapper_margin'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-brand-category',
				],
				'pagination'          => [
					'typography'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button,  {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'alignment'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
					'width'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'height'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'color'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'bg_color'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'active_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
					'active_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
					'hover_color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
					'hover_bg_color'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
					'border'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'border_hover_color'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button:hover',
					'border_active_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span',
					'border_radius'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li a, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list li span, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'spacing'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
					'padding'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap .rtsb-load-more button, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap .rtsb-load-more button',
					'margin'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-pagination ul.pagination-list, {{WRAPPER}} .rtsb-elementor-container .rtsb-pagination-wrap, {{WRAPPER}} .rtsb-elementor-container .rtsb-archive-pagination-wrap',
				],
				'product_add_to_cart' => [
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'icon_size'          => [
						'font_size' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn .icon',
						'width'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn .icon svg',
					],
					'cart_width'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'cart_height'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'icon_color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn .icon',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn:hover',
					'hover_icon_color'   => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn:hover .icon',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-cart .rtsb-action-btn',
				],
				'product_compare'     => [
					'icon_size'          => [
						'font_size' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn .icon',
						'width'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn .icon svg',
					],
					'width'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'height'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-compare .rtsb-action-btn',
				],
				'product_wishlist'    => [
					'icon_size'          => [
						'font_size' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn .icon',
						'width'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn .icon svg',
					],
					'width'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'height'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-wishlist .rtsb-action-btn',
				],
				'product_quick_view'  => [
					'icon_size'          => [
						'font_size' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn .icon',
						'width'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn .icon svg',
					],
					'width'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'height'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn:hover',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .rtsb-quick-view .rtsb-action-btn',
				],
				'hover_icon_button'   => [
					// Include `.text` (the label span): the button is display:inline-flex, so its
					// `.text` child is a flex item and `text-decoration` set on the <a> does NOT
					// propagate to it (the Text Decoration control would silently do nothing).
					// Other typography props inherit fine; decoration must hit `.text` directly.
					'typography'         => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a, {{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a .text',
					'icon_size'          => [
						'font_size' => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a .icon',
						'svg'       => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a .icon svg',
					],
					'hover_icon_spacing' => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a .text + .icon',
					'color'              => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
					'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
					'icon_color'         => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a .icon',
					'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a:hover',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a:hover',
					'hover_icon_color'   => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a:hover i',
					'border'             => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
					'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a:hover',
					'border_radius'      => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
					'padding'            => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
					'margin'             => '{{WRAPPER}} .rtsb-elementor-container .product-title-with-btn .btn-wrap a',
				],
				'not_found_notice'    => self::not_found_notice_selectors(),
			]
		);
	}

	/**
	 * Not Found Notice Selectors.
	 *
	 * @return array
	 */
	public static function not_found_notice_selectors() {
		return array_fill_keys(
			[
				'typography',
				'alignment',
				'color',
				'bg_color',
				'border',
				'padding',
				'margin',
			],
			'{{WRAPPER}} .rtsb-elementor-container .woocommerce-no-products-found .woocommerce-info, {{WRAPPER}} .rtsb-product-catalog .woocommerce-no-products-found .woocommerce-info'
		);
	}

	/**
	 * Category CSS Selectors.
	 *
	 * @return array
	 */
	private static function general_cat_selectors(): array {
		return [
			'category_single_layout' => [
				'cat_alignment' => [
					'text_align'      => '{{WRAPPER}} .rtsb-elementor-container',
					'justify_content' => '{{WRAPPER}} .rtsb-elementor-container [class*=list-layout] .rtsb-product-category, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .category-title-with-count',
				],
				'cat_height'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .rtsb-product-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-grid .default-img',
			],
			'category_multi_layout'  => [
				'cat_height' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout1 .rtsb-product-img .rtsb-product-image, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout1 .rtsb-product-img .default-img, {{WRAPPER}} .rtsb-elementor-container .rtsb-category-layout2 .category-title-with-image',
			],
			'cat_count_settings'     => [
				'count_display_type' => '{{WRAPPER}} .rtsb-elementor-container .category-title-with-count',
			],
			'cat_title'              => [
				'typography'         => '{{WRAPPER}} .rtsb-elementor-container .category-title',
				'alignment'          => '{{WRAPPER}} .rtsb-elementor-container .category-title',
				'color'              => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-title, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-title, {{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-title-with-count',
				'bg_color'           => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-title, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-title',
				'hover_color'        => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-title:hover, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-title:hover',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-title:hover, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-title:hover',
				'border'             => '{{WRAPPER}} .rtsb-elementor-container .category-title',
				'border_hover_color' => '{{WRAPPER}} .rtsb-elementor-container .category-title:hover',
				'padding'            => '{{WRAPPER}} .rtsb-elementor-container .category-title',
				'margin'             => '{{WRAPPER}} .rtsb-elementor-container .category-title',
			],
			'cat_description'        => [
				'typography' => '{{WRAPPER}} .rtsb-elementor-container .category-description',
				'alignment'  => '{{WRAPPER}} .rtsb-elementor-container .category-description',
				'color'      => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-description, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-description',
				'bg_color'   => '{{WRAPPER}} .rtsb-elementor-container [class*=category-single-layout] .rtsb-category-grid .single-category-area .category-description, {{WRAPPER}} .rtsb-elementor-container [class*=rtsb-category-layout] .rtsb-category-grid .category-wrapper .category-description',
				'border'     => '{{WRAPPER}} .rtsb-elementor-container .category-description',
				'padding'    => '{{WRAPPER}} .rtsb-elementor-container .category-description',
				'margin'     => '{{WRAPPER}} .rtsb-elementor-container .category-description',
			],
		];
	}

	/**
	 * Social Share CSS Selectors.
	 *
	 * @return array
	 */
	private static function social_share(): array {
		return apply_filters(
			'rtsb/elements/elementor/social_share_selectors',
			[
				'share_items'  => [
					'share_icons_spacing'         => '{{WRAPPER}} .rtsb-social-share',
					'share_items_min_width'       => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
					'social_items_color'          => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn svg, {{WRAPPER}} .rtsb-social-share .rtsb-share-btn .rtsb-share-label',
					'social_items_bg_color'       => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
					'facebook_color'              => '{{WRAPPER}} .rtsb-social-share .facebook svg, {{WRAPPER}} .rtsb-social-share .facebook .rtsb-share-label',
					'twitter_color'               => '{{WRAPPER}} .rtsb-social-share .twitter svg, {{WRAPPER}} .rtsb-social-share .twitter .rtsb-share-label',
					'linkedin_color'              => '{{WRAPPER}} .rtsb-social-share .linkedin svg, {{WRAPPER}} .rtsb-social-share .linkedin .rtsb-share-label',
					'pinterest_color'             => '{{WRAPPER}} .rtsb-social-share .pinterest svg, {{WRAPPER}} .rtsb-social-share .pinterest .rtsb-share-label',
					'skype_color'                 => '{{WRAPPER}} .rtsb-social-share .skype svg, {{WRAPPER}} .rtsb-social-share .skype .rtsb-share-label',
					'whatsapp_color'              => '{{WRAPPER}} .rtsb-social-share .whatsapp svg, {{WRAPPER}} .rtsb-social-share .whatsapp .rtsb-share-label',
					'reddit_color'                => '{{WRAPPER}} .rtsb-social-share .reddit svg, {{WRAPPER}} .rtsb-social-share .reddit .rtsb-share-label',
					'telegram_color'              => '{{WRAPPER}} .rtsb-social-share .telegram svg, {{WRAPPER}} .rtsb-social-share .telegram .rtsb-share-label',
					'facebook_bg_color'           => '{{WRAPPER}} .rtsb-social-share .facebook',
					'twitter_bg_color'            => '{{WRAPPER}} .rtsb-social-share .twitter',
					'linkedin_bg_color'           => '{{WRAPPER}} .rtsb-social-share .linkedin',
					'pinterest_bg_color'          => '{{WRAPPER}} .rtsb-social-share .pinterest',
					'skype_bg_color'              => '{{WRAPPER}} .rtsb-social-share .skype',
					'whatsapp_bg_color'           => '{{WRAPPER}} .rtsb-social-share .whatsapp',
					'reddit_bg_color'             => '{{WRAPPER}} .rtsb-social-share .reddit',
					'telegram_bg_color'           => '{{WRAPPER}} .rtsb-social-share .telegram',
					'social_items_hover_color'    => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover svg, {{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-label',
					'social_items_hover_bg_color' => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover',
					'facebook_hover_color'        => '{{WRAPPER}} .rtsb-social-share .facebook:hover svg, {{WRAPPER}} .rtsb-social-share .facebook:hover .rtsb-share-label',
					'twitter_hover_color'         => '{{WRAPPER}} .rtsb-social-share .twitter:hover svg, {{WRAPPER}} .rtsb-social-share .twitter:hover .rtsb-share-label',
					'linkedin_hover_color'        => '{{WRAPPER}} .rtsb-social-share .linkedin:hover svg, {{WRAPPER}} .rtsb-social-share .linkedin:hover .rtsb-share-label',
					'pinterest_hover_color'       => '{{WRAPPER}} .rtsb-social-share .pinterest:hover svg, {{WRAPPER}} .rtsb-social-share .pinterest:hover .rtsb-share-label',
					'skype_hover_color'           => '{{WRAPPER}} .rtsb-social-share .skype:hover svg, {{WRAPPER}} .rtsb-social-share .skype:hover .rtsb-share-label',
					'whatsapp_hover_color'        => '{{WRAPPER}} .rtsb-social-share .whatsapp:hover svg, {{WRAPPER}} .rtsb-social-share .whatsapp:hover .rtsb-share-label',
					'reddit_hover_color'          => '{{WRAPPER}} .rtsb-social-share .reddit:hover svg, {{WRAPPER}} .rtsb-social-share .reddit:hover .rtsb-share-label',
					'telegram_hover_color'        => '{{WRAPPER}} .rtsb-social-share .telegram:hover svg, {{WRAPPER}} .rtsb-social-share .telegram:hover .rtsb-share-label',
					'facebook_hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .facebook:hover',
					'twitter_hover_bg_color'      => '{{WRAPPER}} .rtsb-social-share .twitter:hover',
					'linkedin_hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .linkedin:hover',
					'pinterest_hover_bg_color'    => '{{WRAPPER}} .rtsb-social-share .pinterest:hover',
					'skype_hover_bg_color'        => '{{WRAPPER}} .rtsb-social-share .skype:hover',
					'whatsapp_hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .whatsapp:hover',
					'reddit_hover_bg_color'       => '{{WRAPPER}} .rtsb-social-share .reddit:hover',
					'telegram_hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .telegram:hover',
					'border'                      => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
					'border_hover_color'          => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover',
					'border_radius'               => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
					'padding'                     => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
					'margin'                      => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn',
				],
				'share_icons'  => [
					'share_icons_width'  => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn svg',
					'share_icons_height' => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn svg',
					'color'              => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn svg',
					'bg_color'           => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn .rtsb-share-icon',
					'hover_color'        => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover svg',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-icon',
					'border'             => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-icon',
					'border_hover_color' => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-icon',
					'padding'            => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-icon',
					'margin'             => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-icon',
				],
				'share_text'   => [
					'typography'         => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'alignment'          => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'color'              => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'bg_color'           => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'hover_color'        => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-label',
					'hover_bg_color'     => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-label',
					'border'             => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'border_hover_color' => '{{WRAPPER}} .rtsb-social-share .rtsb-share-btn:hover .rtsb-share-label',
					'padding'            => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
					'margin'             => '{{WRAPPER}} .rtsb-social-share .rtsb-share-icon-label .rtsb-share-label',
				],
				'share_header' => [
					'typography'        => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
					'share_header_type' => '{{WRAPPER}} .rtsb-social-share-container',
					'color'             => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
					'bg_color'          => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
					'border'            => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
					'padding'           => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
					'margin'            => '{{WRAPPER}} .rtsb-social-share-container .rtsb-social-header p',
				],
			]
		);
	}
}

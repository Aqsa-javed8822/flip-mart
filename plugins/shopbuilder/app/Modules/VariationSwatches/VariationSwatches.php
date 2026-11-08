<?php

namespace RadiusTheme\SB\Modules\VariationSwatches;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Variation Swatches
 */
final class VariationSwatches {

	/**
	 * SingleTon
	 */
	use SingletonTrait;

	/**
	 * Asset Handle
	 *
	 * @var string
	 */
	private $handle = 'rtsb-variation-swatches';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		$already_active = Fns::check_plugin_active( 'woo-product-variation-swatches/woo-product-variation-swatches.php' );
		if ( ! $already_active ) {
			$this->scripts();
			$this->init();
			do_action( 'rtsb/variation/swatches/init' );
		}
	}
	/**
	 * Initialize Functionality.
	 *
	 * @return void
	 */
	private function scripts() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_assets' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_backend_assets' ], 1 );
	}
	/**
	 * Initialize Functionality.
	 *
	 * @return void
	 */
	private function init() {
		SwatchesHooks::instance();
		SwatchesTerms::instance();
	}
	/**
	 * Assets.
	 *
	 * @return void
	 */
	public function frontend_assets() {
		// Enqueue assets.
		$this->handle = Fns::enqueue_module_assets(
			$this->handle,
			'variation-swatches',
			[
				'context' => ( function_exists( 'rtsbpro' ) && rtsb()->has_pro() ) ? rtsbpro() : rtsb(),
				'version' => rtsb()->has_pro() ? RTSBPRO_VERSION : RTSB_VERSION,
			]
		);

		$this->frontend_dynamic_css();
		wp_localize_script(
			$this->handle,
			'rtsbVsParams',
			apply_filters(
				'rtsb/vs/js/object',
				[
					'is_product_page'      => is_product(),
					'ajax_url'             => WC()->ajax_url(),
					'hasPro'               => rtsb()->has_pro(),
					rtsb()->nonceId        => wp_create_nonce( rtsb()->nonceText ),
					'attr_beside_label'    => 'on' === SwatchesFns::get_options( 'show_term_name_beside_label' ),
					// In the future it also come from settings.
					'enable_variation_url' => 'on' === SwatchesFns::get_options( 'enable_variation_url' ),
					'url_link_selector'    => 'a.woocommerce-LoopProduct-link, a.add_to_cart_button',
					'has_wc_bundles'       => false,
				]
			)
		);
	}

	/**
	 * @return void
	 */
	private function frontend_dynamic_css() {
		$options     = SwatchesFns::get_options();
		$dynamic_css = ':root{';
		if ( ! empty( $options['details_page_attr_label_font_size'] ) ) {
			$dynamic_css .= '--details-page-attr-label-font-size:' . $options['details_page_attr_label_font_size'] . 'px;';
		}
		if ( ! empty( $options['details_page_attr_label_color'] ) ) {
			$dynamic_css .= '--details-page-attr-label-color:' . $options['details_page_attr_label_color'] . ';';
		}

		if ( ! empty( $options['attribute_border_color'] ) ) {
			$dynamic_css .= '--attribute-border-color:' . $options['attribute_border_color'] . ';';
		}
		if ( ! empty( $options['attribute_border_color_hover'] ) ) {
			$dynamic_css .= '--attribute-border-color-hover:' . $options['attribute_border_color_hover'] . ';';
		}
		// Checkmark.
		if ( ! empty( $options['attribute_checkmark_width'] ) ) {
			$dynamic_css .= '--checkmark-width:' . $options['attribute_checkmark_width'] . 'px;';
		}
		if ( ! empty( $options['attribute_checkmark_height'] ) ) {
			$dynamic_css .= '--checkmark-height:' . $options['attribute_checkmark_height'] . 'px;';
		}
		if ( ! empty( $options['attribute_checkmark_icon_size'] ) ) {
			$dynamic_css .= '--checkmark-font-size:' . $options['attribute_checkmark_icon_size'] . 'px;';
		}
		if ( ! empty( $options['checkmark_bg_color'] ) ) {
			$dynamic_css .= '--checkmark-bg-color:' . $options['checkmark_bg_color'] . ';';
		}

		// Tooltip.
		if ( ! empty( $options['tooltip_bg_color'] ) ) {
			$dynamic_css .= '--tooltip-bg-color:' . $options['tooltip_bg_color'] . ';';
		}
		if ( ! empty( $options['tooltip_image_bg_color'] ) ) {
			$dynamic_css .= '--tooltip-image-bg-color:' . $options['tooltip_image_bg_color'] . ';';
		}

		if ( ! empty( $options['tooltip_text_color'] ) ) {
			$dynamic_css .= '--tooltip-text-color:' . $options['tooltip_text_color'] . ';';
		}

		// Product Page.
		if ( ! empty( $options['details_page_attribute_width'] ) ) {
			$dynamic_css .= '--details-page-attr-width:' . $options['details_page_attribute_width'] . 'px;';
		}
		if ( ! empty( $options['details_page_attribute_height'] ) ) {
			$dynamic_css .= '--details-page-attr-height:' . $options['details_page_attribute_height'] . 'px;';
		}
		if ( ! empty( $options['details_page_attribute_font_size'] ) ) {
			$dynamic_css .= '--details-page-attr-font-size:' . $options['details_page_attribute_font_size'] . 'px;';
		}
		if ( ! empty( $options['details_page_radio_size'] ) ) {
			$dynamic_css .= '--details-page-radio-size:' . $options['details_page_radio_size'] . 'px;';
		}
		// Showcase.
		if ( ! empty( $options['showcase_attribute_width'] ) ) {
			$dynamic_css .= '--showcase-attr-width:' . $options['showcase_attribute_width'] . 'px;';
		}
		if ( ! empty( $options['showcase_attribute_height'] ) ) {
			$dynamic_css .= '--showcase-attr-height:' . $options['showcase_attribute_height'] . 'px;';
		}
		if ( ! empty( $options['showcase_attribute_font_size'] ) ) {
			$dynamic_css .= '--showcase-attr-font-size:' . $options['showcase_attribute_font_size'] . 'px;';
		}
		if ( ! empty( $options['showcase_radio_size'] ) ) {
			$dynamic_css .= '--showcase-radio-size:' . $options['showcase_radio_size'] . 'px;';
		}
		// Tooltips.
		if ( ! empty( $options['tooltip_images_padding'] ) ) {
			$dynamic_css .= '--tooltip-image-padding:' . $options['tooltip_images_padding'] . 'px;';
		}
		// Attribute border radius (use isset so a saved value of 0 still emits the variable).
		if ( isset( $options['attribute_border_radius'] ) && '' !== $options['attribute_border_radius'] ) {
			$dynamic_css .= '--variation-attr-border-radius:' . absint( $options['attribute_border_radius'] ) . 'px;';
		}

		$dynamic_css .= '}';
		if ( ! empty( $dynamic_css ) ) {
			$dynamic_css = apply_filters( 'rtsb/variation/swatches/dynamic/css', $dynamic_css, $options );
			wp_add_inline_style( $this->handle, $dynamic_css );
		}
	}
	/**
	 * Assets.
	 *
	 * @return void
	 */
	public function register_backend_assets() {
		global $post;
		$current_screen = get_current_screen();
		$screen_id      = $current_screen ? $current_screen->id : '';
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			$ajaxurl = admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			$ajaxurl = admin_url( 'admin-ajax.php' );
		}
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ( 'product' === sanitize_text_field( wp_unslash( $_GET['post_type'] ?? '' ) ) && isset( $_GET['taxonomy'] ) ) || 'product' === $screen_id ) {
			wp_register_script( 'rtsb-variation-swatch-admin', rtsb()->get_assets_uri( 'js/backend/variation-swatch-admin.js' ), '', '1.0', true );
			wp_localize_script(
				'rtsb-variation-swatch-admin',
				'rtsbVsParams',
				[
					'ajaxurl'         => esc_url( $ajaxurl ),
					'media_title'     => esc_html__( 'Choose an Image', 'shopbuilder' ),
					'button_title'    => esc_html__( 'Use Image', 'shopbuilder' ),
					'add_media'       => esc_html__( 'Add Media', 'shopbuilder' ),
					'reset_notice'    => esc_html__( 'Are you sure to reset', 'shopbuilder' ),
					rtsb()->nonceId   => wp_create_nonce( rtsb()->nonceText ),
					'post_id'         => 'product' === $screen_id ? $post->ID : null,
					'attribute_types' => SwatchesFns::get_available_attributes_types(),
				]
			);
		}
	}
}

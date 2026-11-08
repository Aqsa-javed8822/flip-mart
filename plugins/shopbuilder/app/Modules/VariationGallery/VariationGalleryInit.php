<?php

namespace RadiusTheme\SB\Modules\VariationGallery;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Variation Swatches
 */
final class VariationGalleryInit {

	/**
	 * SingleTon
	 */
	use SingletonTrait;

	/**
	 * Asset Handle
	 *
	 * @var string
	 */
	private $handle = 'rtsb-variation-gallery';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		$already_active = Fns::check_plugin_active( 'woo-product-variation-gallery/woo-product-variation-gallery.php' );
		if ( ! $already_active ) {
			$this->scripts();
			$this->init();
			do_action( 'rtsb/variation/gallery/init' );
		}
	}
	/**
	 * Initialize Functionality.
	 *
	 * @return void
	 */
	private function scripts() {
		add_filter( 'rtsb/optimizer/scripts/deps', [ $this, 'extend_shared_dependencies' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_assets' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_backend_assets' ], 1 );
	}
	/**
	 * Initialize Functionality.
	 *
	 * @return void
	 */
	private function init() {
		GalleryAdmin::instance();
		GalleryFrontEnd::instance();
		GalleryMigration::instance();
	}
	/**
	 * Shared dependencies.
	 *
	 * @param array $deps Dependencies.
	 *
	 * @return array
	 */
	public function extend_shared_dependencies( $deps ) {
		if ( is_product() || BuilderFns::is_product() ) {
			$deps[] = 'swiper';
		}
		return $deps;
	}

	/**
	 * Assets.
	 *
	 * @return void
	 */
	public function frontend_assets() {
		wp_dequeue_script( 'wc-flexslider' );
		// Enqueue assets.
		$this->handle = Fns::enqueue_module_assets(
			$this->handle,
			'variation-gallery',
			[
				'context' => ( function_exists( 'rtsbpro' ) && rtsb()->has_pro() ) ? rtsbpro() : rtsb(),
				'version' => rtsb()->has_pro() ? RTSBPRO_VERSION : RTSB_VERSION,
				'type'    => 'css',
			]
		);
		$this->handle = Fns::enqueue_module_assets(
			$this->handle,
			'variation-gallery',
			[
				'context' => rtsb(),
				'version' => RTSB_VERSION,
				'type'    => 'js',
			]
		);
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			$ajaxurl = admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			$ajaxurl = admin_url( 'admin-ajax.php' );
		}
		// The fileupload control stores [ 'id' => attachment ID, 'source' => URL ],
		// but it can come back from the options store as a JSON string, so decode it
		// before reading the URL; a raw JSON string must never be used as the URL.
		$preloader_image = GalleryFns::get_options( 'preloader_image' );
		if ( is_string( $preloader_image ) && '' !== trim( $preloader_image ) ) {
			$decoded = json_decode( wp_unslash( $preloader_image ), true );
			if ( is_array( $decoded ) ) {
				$preloader_image = $decoded;
			}
		}
		$preloader_image_url = '';
		if ( is_array( $preloader_image ) && ! empty( $preloader_image['source'] ) ) {
			$preloader_image_url = $preloader_image['source'];
		} elseif ( is_string( $preloader_image ) && filter_var( $preloader_image, FILTER_VALIDATE_URL ) ) {
			$preloader_image_url = $preloader_image;
		}
		wp_localize_script(
			$this->handle,
			'rtsbVgParams',
			[
				'ajaxurl'        => esc_url( $ajaxurl ),
				'admin_url'      => esc_url( admin_url() ),
				'pro_version'    => defined( 'RTSBPRO_VERSION' ) ? RTSBPRO_VERSION : false,
				'preloader'      => GalleryFns::get_options( 'preloader' ) ? 'on' : '',
				'preloaderImage' => $preloader_image_url ? esc_url( $preloader_image_url ) : '',
				'changeEffect'   => GalleryFns::get_options( 'gallery_change_effect', 'blur' ),
				rtsb()->nonceId  => wp_create_nonce( rtsb()->nonceText ),
			]
		);
		$this->frontend_dynamic_css();
	}

	/**
	 * @return void
	 */
	private function frontend_dynamic_css() {
		$options      = GalleryFns::get_options();
		$dynamic_css  = ':root{';
		$alignment    = 'on' === ( $options['main_slider_alignment'] ?? '' ) ? 'center' : 'flex-start';
		$col          = GalleryFns::get_options( 'thumbnails_columns', 3 );
		$dynamic_css .= '--vg-main-slider-v-alignment:' . $alignment . ';';
		$dynamic_css .= '--vg-grid-column:' . absint( $col ) . ';';
		$dynamic_css .= '--vg-main-slider-border-color:' . ( ! empty( $options['main_image_border_color'] ) ? $options['main_image_border_color'] : 'transparent' ) . ';';
		$dynamic_css .= '--vg-thumb-border-color:' . ( ! empty( $options['thumbnail_item_border_color'] ) ? $options['thumbnail_item_border_color'] : '#eee' ) . ';';
		$dynamic_css .= '--vg-thumb-active-border-color:' . ( ! empty( $options['thumbnail_active_border_color'] ) ? $options['thumbnail_active_border_color'] : '#444' ) . ';';
		$dynamic_css .= '--vg-thumb-item-inner-padding:' . ( isset( $options['thumbnail_item_inner_padding'] ) && '' !== $options['thumbnail_item_inner_padding'] ? absint( $options['thumbnail_item_inner_padding'] ) : '8' ) . 'px;';
		$dynamic_css .= '--vg-thumb-border-radius:' . ( ! empty( $options['thumbnail_item_border_radius'] ) ? absint( $options['thumbnail_item_border_radius'] ) : '3' ) . 'px;';
		$dynamic_css .= '--vg-thumb-gap:' . ( isset( $options['thumbnails_gap'] ) && '' !== $options['thumbnails_gap'] ? absint( $options['thumbnails_gap'] ) : '10' ) . 'px;';
		$dynamic_css .= '}';
		if ( ! empty( $dynamic_css ) ) {
			$dynamic_css = apply_filters( 'rtsb/variation/gallery/dynamic/css', $dynamic_css, $options );
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
			wp_register_style( 'rtsb-variation-gallery-admin', rtsb()->get_assets_uri( 'css/backend/variation-gallery-admin.css' ), '', '1.0' );
			wp_register_script( 'rtsb-variation-gallery-admin', rtsb()->get_assets_uri( 'js/backend/variation-gallery-admin.js' ), '', '1.0', true );
			wp_enqueue_script( 'rtsb-variation-gallery-admin' );
			wp_enqueue_style( 'rtsb-variation-gallery-admin' );
			wp_localize_script(
				'rtsb-variation-gallery-admin',
				'rtsbVgParams',
				[
					'ajaxurl'      => esc_url( $ajaxurl ),
					'admin_url'    => esc_url( admin_url() ),
					'pro_version'  => defined( 'RTSBPRO_VERSION' ) ? RTSBPRO_VERSION : false,
					'nonce'        => wp_create_nonce( 'rtsb_vg_nonce' ),
					'media_title'  => esc_html__( 'Choose an Image', 'shopbuilder' ),
					'button_title' => esc_html__( 'Use Image', 'shopbuilder' ),
					'add_media'    => esc_html__( 'Add Media', 'shopbuilder' ),
					'add_video'    => esc_html__( 'Add Video', 'shopbuilder' ),
					'sure_txt'     => esc_html__( 'Are you sure to delete?', 'shopbuilder' ),
					'choose_image' => esc_html__( 'Choose an Image', 'shopbuilder' ),
					'add_image'    => esc_html__( 'Add Image', 'shopbuilder' ),
					'edit_image'   => esc_html__( 'Edit Image', 'shopbuilder' ),
					'update_image' => esc_html__( 'Update Image', 'shopbuilder' ),
					'remove_image' => esc_html__( 'Remove', 'shopbuilder' ),
				]
			);
		}
	}
}

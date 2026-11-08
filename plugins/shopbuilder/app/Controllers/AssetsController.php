<?php
/**
 * Assets Controller Class
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\Settings;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Assets Controller Class
 */
class AssetsController {

	use SingletonTrait;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Ajax URL
	 *
	 * @var string
	 */
	private static $ajaxurl;

	/**
	 * Styles.
	 *
	 * @var array
	 */
	private $styles = [];

	/**
	 * Scripts.
	 *
	 * @var array
	 */
	private $scripts = [];

	/**
	 * Cached bundled assets.
	 *
	 * @var array|null
	 */
	private $cached_bundled_assets = null;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTSB_VERSION;

		// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			self::$ajaxurl = admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			self::$ajaxurl = admin_url( 'admin-ajax.php' );
		}
		add_filter( 'rtsb/optimizer/scripts/deps', [ $this, 'extend_shared_dependencies' ], 99 );
		/**
		 * Admin scripts.
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'register_backend_assets' ], 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_backend_scripts' ], 15 );

		/**
		 * Public scripts.
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ], 25 );
		/**
		 * General scripts.
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_general_public_scripts' ], 30 );
	}
	/**
	 * Shared dependencies.
	 *
	 * @param array $deps Dependencies.
	 *
	 * @return array
	 */
	public function extend_shared_dependencies( $deps ) {
		if ( Fns::is_elementor_widget_active( 'products_slider' ) ) {
			$deps[] = 'swiper';
		}
		return array_unique( $deps );
	}
	/**
	 * Get all frontend scripts.
	 *
	 * @return void
	 */
	private function get_public_assets() {
		$this->get_public_styles()->get_public_scripts();
	}

	/**
	 * Get public styles.
	 *
	 * @return object
	 */
	private function get_public_styles() {
		$rtl_suffix = is_rtl() ? '-rtl' : '';
		$rtl_dir    = is_rtl() ? trailingslashit( 'rtl' ) : trailingslashit( 'css' );

		$this->styles[] = [
			'handle' => 'rtsb-odometer',
			'src'    => rtsb()->get_assets_uri( 'vendor/odometer/css/odometer.min.css' ),
		];

		$this->styles[] = [
			'handle' => 'rtsb-fonts',
			'src'    => rtsb()->get_assets_uri( 'css/frontend/rtsb-fonts.css' ),
		];
		if ( rtsb()->has_pro() ) {
			$this->styles[] = [
				'handle' => 'rtsb-noui-slider',
				'src'    => rtsbpro()->get_assets_uri( 'vendors/nouislider/nouislider.min.css' ),
			];
		}

		$pro_version     = defined( 'RTSBPRO_VERSION' ) ? RTSBPRO_VERSION : '0';
		$addon_condition = version_compare( $pro_version, '1.9.0', '<' );

		$optimized = [];

		if ( ! rtsb()->has_pro() && Fns::is_optimization_enabled() ) {
			$optimized = AssetRegistry::instance()->load_optimized_assets();
		}

		// An empty `src` means the bundle could not be built. Registering it would
		// produce a handle WordPress never prints, so use the unbundled assets.
		if ( ! empty( $optimized['src'] ) ) {
			$this->styles[] = $optimized;
		} else {
			if ( $addon_condition ) {
				$this->styles[] = [
					'handle' => 'rtsb-general-addons',
					'src'    => rtsb()->get_assets_uri( $rtl_dir . 'frontend/general-addons' . $rtl_suffix . '.css' ),
				];
			}
			$this->styles[] = [
				'handle' => 'rtsb-frontend',
				'src'    => rtsb()->get_assets_uri( $rtl_dir . 'frontend/frontend' . $rtl_suffix . '.css' ),
			];
		}
		if ( BuilderFns::is_builder_preview() && 'elementor' == Fns::page_edit_with( get_the_ID() ) ) {
			$this->styles[] = [
				'handle' => 'photoswipe',
				'src'    => plugins_url( 'assets/css/photoswipe/photoswipe.min.css', WC_PLUGIN_FILE ),
			];

			$this->styles[] = [
				'handle' => 'photoswipe-default-skin',
				'src'    => plugins_url( 'assets/css/photoswipe/default-skin/default-skin.min.css', WC_PLUGIN_FILE ),
			];

			// Load only for elementor editor page and fix some issue.
			$this->styles[] = [
				'handle' => 'elementor-editor-style-fix',
				'src'    => rtsb()->get_assets_uri( 'css/backend/elementor-editor-style-fix.css' ),
			];
		}

		return $this;
	}

	/**
	 * Get public scripts.
	 *
	 * @return object
	 */
	private function get_public_scripts() {
		$this->scripts[] = [
			'handle' => 'swiper',
			'src'    => esc_url( $this->get_swiper_url() ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];
		$this->scripts[] = [
			'handle' => 'rtsb-waypoints',
			'src'    => rtsb()->get_assets_uri( 'vendor/waypoints/js/jquery.waypoints.min.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];
		$this->scripts[] = [
			'handle' => 'rtsb-odometer',
			'src'    => rtsb()->get_assets_uri( 'vendor/odometer/js/odometer.min.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'rtsb-tipsy',
			'src'    => rtsb()->get_assets_uri( 'vendor/tipsy/tipsy.min.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		if ( BuilderFns::is_builder_preview() && 'elementor' == Fns::page_edit_with( get_the_ID() ) ) {
			$this->scripts[] = [
				'handle' => 'wc-flexslider',
				'src'    => plugins_url( 'assets/js/flexslider/jquery.flexslider.js', WC_PLUGIN_FILE ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'photoswipe',
				'src'    => plugins_url( 'assets/js/photoswipe/photoswipe.js', WC_PLUGIN_FILE ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'wc-zoom',
				'src'    => plugins_url( 'assets/js/zoom/jquery.zoom.js', WC_PLUGIN_FILE ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'wc-photoswipe-ui-default',
				'src'    => plugins_url( 'assets/js/photoswipe/photoswipe-ui-default.js', WC_PLUGIN_FILE ),
				'deps'   => [ 'jquery', 'photoswipe' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'wc-single-product',
				'src'    => plugins_url( 'assets/js/frontend/single-product.js', WC_PLUGIN_FILE ),
				'deps'   => [ 'jquery', 'wc-flexslider', 'photoswipe', 'wc-photoswipe-ui-default', 'wc-zoom' ],
				'footer' => true,
			];

		}

		if ( rtsb()->has_pro() ) {
			$this->scripts[] = [
				'handle' => 'rtsb-noui-slider',
				'src'    => rtsbpro()->get_assets_uri( 'vendors/nouislider/nouislider.min.js' ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'rtsb-sticky-sidebar',
				'src'    => rtsbpro()->get_assets_uri( 'vendors/sticky-sidebar/sticky-sidebar.min.js' ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'rtsb-popper',
				'src'    => rtsbpro()->get_assets_uri( 'vendors/tippy/popper.min.js' ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$this->scripts[] = [
				'handle' => 'rtsb-tippy',
				'src'    => rtsbpro()->get_assets_uri( 'vendors/tippy/tippy.min.js' ),
				'deps'   => [ 'jquery', 'rtsb-popper' ],
				'footer' => true,
			];
		}

		$optimized = [];

		if ( ! rtsb()->has_pro() && Fns::is_optimization_enabled() ) {
			$optimized = AssetRegistry::instance()->load_optimized_assets( 'js' );
		}

		// See get_public_styles(): an empty `src` means the bundle build failed.
		if ( ! empty( $optimized['src'] ) ) {
			$this->scripts[] = $optimized;
		} else {
			$this->scripts[] = [
				'handle' => 'rtsb-public',
				'src'    => rtsb()->get_assets_uri( 'js/frontend/frontend.js' ),
				'deps'   => [ 'jquery', 'imagesloaded', 'swiper' ],
				'footer' => true,
			];
		}

		return $this;
	}

	/**
	 * Register public scripts.
	 *
	 * @return void
	 */
	public function register_public_scripts() {
		$this->get_public_assets();
		$this->styles  = array_filter( $this->styles );
		$this->scripts = array_filter( $this->scripts );

		// Register public styles.
		foreach ( $this->styles as $style ) {
			wp_register_style( $style['handle'], $style['src'], '', $this->version );
		}

		// Register public scripts.
		foreach ( $this->scripts as $script ) {
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->version, $script['footer'] );
		}
	}

	/**
	 * Enqueues public scripts.
	 *
	 * @return void
	 */
	public function enqueue_public_scripts() {
		/**
		 * Register scripts.
		 */
		$this->register_public_scripts();

		/**
		 * Enqueue scripts.
		 */
		if ( BuilderFns::is_builder_preview() && 'elementor' === Fns::page_edit_with( get_the_ID() ) ) {
			/**
			 * Styles.
			 */
			wp_enqueue_style( 'photoswipe' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_style( 'elementor-editor-style-fix' );
			wp_enqueue_style( 'woocommerce-general' );

			/**
			 * Scripts.
			 */
			$handle = Fns::optimized_handle( 'rtsb-public' );

			wp_enqueue_script( 'wc-flexslider' );
			wp_enqueue_script( 'wc-single-product' );
			wp_dequeue_script( $handle );
			wp_enqueue_script( 'swiper' );

			if ( Fns::is_optimization_enabled() ) {
				Fns::enqueue_optimized_assets();
			} else {
				wp_enqueue_script( $handle );
			}
		}

		wp_enqueue_script( 'rtsb-tipsy' );

		if ( Fns::is_optimization_enabled() ) {
			Fns::enqueue_optimized_assets();
		} else {
			wp_enqueue_style( 'rtsb-fonts' );
			wp_enqueue_style( 'rtsb-frontend' );
			wp_enqueue_script( 'rtsb-public' );
		}

		/**
		 * Localize script.
		 */
		self::localizeData();
	}

	/**
	 * Localized Data.
	 *
	 * @static
	 * @return void
	 */
	public static function localizeData() {
		$handle  = Fns::optimized_handle( 'rtsb-public' );
		$ai_data = AIFns::activated_ai_data();
		wp_localize_script(
			$handle,
			'rtsbPublicParams',
			[
				'ajaxUrl'               => esc_url( self::$ajaxurl ),
				'aiActivated'           => ! empty( $ai_data ),
				'homeurl'               => home_url(),
				'shopUrl'               => function_exists( 'wc_get_page_permalink' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : home_url( '/' ),
				'wcCartUrl'             => wc_get_cart_url(),
				'addedToCartText'       => esc_html__( 'Product Added', 'shopbuilder' ),
				'singleCartToastrText'  => esc_html__( 'Successfully Added', 'shopbuilder' ),
				'singleCartBtnText'     => apply_filters( 'rtsb/global/single_add_to_cart_success', esc_html__( 'Added to Cart', 'shopbuilder' ) ),
				'browseCartText'        => esc_html__( 'Browse Cart', 'shopbuilder' ),
				'noProductsText'        => apply_filters( 'rtsb/global/no_products_text', esc_html__( 'No more products to load', 'shopbuilder' ) ),
				'isOptimizationEnabled' => Fns::is_optimization_enabled(),
				'isLoaderEnabled'       => Fns::enable_loader(),
				'notice'                => [
					'position' => Fns::get_option( 'general', 'notification', 'notification_position', 'center-center' ),
					'timeOut'  => absint( Fns::get_option( 'general', 'notification', 'notification_timeOut', 5 ) ),
				],
				rtsb()->nonceId         => wp_create_nonce( rtsb()->nonceText ),
			]
		);
	}

	/**
	 * Registers Admin scripts.
	 *
	 * @return void
	 */
	public function register_backend_assets() {
		/**
		 * Styles.
		 */
		wp_register_style( 'rtsb-admin-app', rtsb()->get_assets_uri( 'css/backend/admin-settings.css' ), '', $this->version );
		wp_register_style( 'rtsb-fonts', rtsb()->get_assets_uri( 'css/frontend/rtsb-fonts.css' ), '', $this->version );

		$current_screen = get_current_screen();

		if ( 'edit-rtsb_builder' === $current_screen->id ) {
			if ( ! function_exists( 'woocommerce_get_asset_url' ) ) {
				include_once WC_ABSPATH . 'includes/wc-template-functions.php';
			}

			wp_deregister_style( 'select2' );
			wp_register_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}
		wp_register_style( 'rtsb-templatebuilder', rtsb()->get_assets_uri( 'css/backend/template-builder.css' ), '', $this->version );

		/**
		 * Scripts.
		 */
		wp_register_script( 'rtsb-admin-app', rtsb()->get_assets_uri( 'js/backend/admin-settings.js' ), '', $this->version, true );
		wp_register_script( 'rtsb-templatebuilder', rtsb()->get_assets_uri( 'js/backend/template-builder.js' ), '', $this->version, true );
		wp_localize_script(
			'rtsb-admin-app',
			'rtsbParams',
			[
				'ajaxurl'               => esc_url( self::$ajaxurl ),
				'homeurl'               => home_url(),
				'adminLogo'             => rtsb()->get_assets_uri( 'images/icon/ShopBuilder-Logo.svg' ),
				'blackFriday'           => rtsb()->get_assets_uri( 'images/black-friday-ribbon.svg' ),
				'restApiUrl'            => esc_url_raw( rest_url() ),
				'rest_nonce'            => wp_create_nonce( 'wp_rest' ),
				'nonce'                 => wp_create_nonce( rtsb()->nonceText ),
				'pages'                 => Fns::get_pages(),
				'hasPro'                => rtsb()->has_pro() ? 'yes' : 'no',
				'validLicence'          => Fns::has_valid_license() ? 'yes' : 'no',
				'proVersion'            => defined( 'RTSBPRO_VERSION' ) ? RTSBPRO_VERSION : '0',
				'updateRates'           => esc_html__( 'Update All Rates', 'shopbuilder' ),
				'sections'              => Settings::instance()->get_sections(),
				'userRoles'             => Fns::get_all_user_roles(),
				'hasElementor'          => defined( 'ELEMENTOR_VERSION' ),
				'loadElementor'         => Fns::should_load_elementor_scripts(),
				'isOptimizationEnabled' => Fns::is_optimization_enabled(),
			]
		);
		wp_localize_script(
			'rtsb-admin-app',
			'rtsbCaAbandonment',
			[
				'mainTitle'           => esc_html__( 'Abandoned Carts Follow Up', 'shopbuilder' ),
				'followUpColumnTitle' => [
					'UserName'    => esc_html__( 'Name', 'shopbuilder' ),
					'EmailTo'     => esc_html__( 'Email To', 'shopbuilder' ),
					'CartTotal'   => esc_html__( 'Cart Total', 'shopbuilder' ),
					'OrderStatus' => esc_html__( 'Order Status', 'shopbuilder' ),
					'DateTime'    => esc_html__( 'Date / Time', 'shopbuilder' ),
					'Action'      => esc_html__( 'Action', 'shopbuilder' ),
				],
			]
		);
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @return void
	 */
	public function enqueue_backend_scripts() {
		ob_start(); ?>
			#adminmenu .toplevel_page_rtsb .wp-menu-image img {
				width: auto;
				height: 22px;
				padding: 4px 0;
				box-sizing: content-box;
			}
			.post-type-rtsb_builder li#wp-admin-bar-WPML_ALS_all,
			.post-type-rtsb_builder li.language_all{
				display: none;
			}

		<?php
		$admin_global_style = ob_get_clean();
		// Speed Optimization.
		wp_add_inline_style( 'admin-menu', $admin_global_style );

		global $pagenow;

		$whitelisted_pages = [ 'rtsb-settings', 'rtsb-get-help', 'rtsb-themes' ];
		$current_page      = ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && in_array( $current_page, $whitelisted_pages, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/**
			 * Styles.
			 */
			wp_enqueue_style( 'rtsb-admin-app' );
			wp_enqueue_style( 'rtsb-fonts' );
		}

		if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && 'rtsb-settings' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// Elementor Console Error Fixed For "rtsb-settings" Page.
			wp_dequeue_script( 'elementor-admin-top-bar' );
			wp_dequeue_script( 'elementor-common' );
			wp_dequeue_script( 'elementor-dev-tools' );
			wp_dequeue_script( 'elementor-web-cli' );
			wp_dequeue_script( 'elementor-import-export-admin' );
			wp_dequeue_script( 'elementor-app-loader' );
			wp_dequeue_script( 'elementor-admin-modules' );
			wp_dequeue_script( 'elementor-ai-media-library' );
			wp_dequeue_script( 'elementor-admin' );

			wp_dequeue_style( 'elementor-admin-top-bar' );
			wp_dequeue_style( 'elementor-admin' );
			wp_dequeue_style( 'e-theme-ui-light' );
			wp_dequeue_style( 'elementor-common' );

			/**
			 * Scripts.
			 */
			wp_enqueue_media();
			wp_enqueue_script( 'updates' );
			wp_enqueue_script( 'rtsb-admin-app' );
		} else {
			$current_screen = get_current_screen();

			if ( 'edit-rtsb_builder' === $current_screen->id ) {
				if ( ! function_exists( 'woocommerce_get_asset_url' ) ) {
					include_once WC_ABSPATH . 'includes/wc-template-functions.php';
				}
				/**
				 * Styles.
				 */
				wp_enqueue_style( 'rtsb-templatebuilder' );

				wp_enqueue_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

				wp_enqueue_script( 'selectWoo' );
				/**
				 * Scripts.
				 */
				wp_enqueue_script( 'rtsb-templatebuilder' );

				wp_localize_script(
					'rtsb-templatebuilder',
					'rtsbParams',
					[
						'ajaxurl'        => esc_url( self::$ajaxurl ),
						'homeurl'        => home_url(),
						rtsb()->nonceId  => wp_create_nonce( rtsb()->nonceText ),
						'hasPro'         => rtsb()->has_pro() ? 'yes' : 'no',
						'singletonTypes' => apply_filters(
							'rtsb/builder/singleton_types',
							array_keys( \RadiusTheme\SB\Helpers\BuilderFns::builder_page_types() )
						),
					]
				);
			}
		}
	}

	/**
	 * Enqueues general public scripts.
	 *
	 * @return void
	 */
	public function enqueue_general_public_scripts() {
		$notification_color        = Fns::get_option( 'general', 'notification', 'notification_color', '#004BFF' );
		$notification_bgcolor      = Fns::get_option( 'general', 'notification', 'notification_bg', '#F5F8FF' );
		$notification_button_color = Fns::get_option( 'general', 'notification', 'notification_btn_color', '#0039C0' );

		$dynamic_css = '';

		if ( ! empty( $notification_color ) ) {
			$dynamic_css .= ".rtsb-shopbuilder-plugin #toast-container .toast-success{color:{$notification_color}}";
			$dynamic_css .= ".rtsb-shopbuilder-plugin #toast-container .toast-success:before{background-color:{$notification_color}}";
			$dynamic_css .= ".rtsb-shopbuilder-plugin #toast-container .toast-close-button{color:{$notification_color}}";
		}

		if ( ! empty( $notification_bgcolor ) ) {
			$dynamic_css .= ".rtsb-shopbuilder-plugin #toast-container .toast-success{background-color:{$notification_bgcolor}}";
		}

		if ( ! empty( $notification_button_color ) ) {
			$dynamic_css .= ".rtsb-shopbuilder-plugin #toast-container .toast-success a{color:{$notification_button_color}}";
		}

		if ( ! empty( $dynamic_css ) ) {
			wp_add_inline_style( Fns::optimized_handle( 'rtsb-frontend' ), $dynamic_css );
		}
	}

	/**
	 * Get the appropriate Swiper JS file URL.
	 *
	 * @return string
	 */
	private function get_swiper_url() {
		$default_swiper_url = rtsb()->get_assets_uri( 'vendor/swiper/js/swiper-bundle.min.js' );

		if ( defined( 'ELEMENTOR_ASSETS_PATH' ) && defined( 'ELEMENTOR_ASSETS_URL' ) ) {
			$experiment = get_option( 'elementor_experiment-e_swiper_latest' );

			$el_relative_path = ( 'active' === $experiment || 'default' === $experiment )
				? 'lib/swiper/v8/swiper.min.js'
				: 'lib/swiper/swiper.min.js';

			$el_full_path = ELEMENTOR_ASSETS_PATH . $el_relative_path;

			if ( file_exists( $el_full_path ) ) {
				return ELEMENTOR_ASSETS_URL . $el_relative_path;
			}
		}

		return $default_swiper_url;
	}
}

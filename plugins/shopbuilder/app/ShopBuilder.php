<?php
/**
 * Main initialization class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB;

use RadiusTheme\SB\AI\AIinit;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Migration;
use RadiusTheme\SB\Helpers\Installation;
use RadiusTheme\SB\Modules\ModuleManager;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Controllers\Shortcodes;
use RadiusTheme\SB\Controllers\Dependencies;
use RadiusTheme\SB\Controllers\CacheController;
use RadiusTheme\SB\Controllers\Admin\AdminInit;
use RadiusTheme\SB\Controllers\Admin\PluginRow;
use RadiusTheme\SB\Controllers\AssetsController;
use RadiusTheme\SB\Controllers\BuilderController;
use RadiusTheme\SB\Controllers\SupportController;
use RadiusTheme\SB\Controllers\Hooks\FilterHooks;
use RadiusTheme\SB\Controllers\Hooks\ActionHooks;
use RadiusTheme\SB\Controllers\Frontend\Ajax\AddToCart;
use RadiusTheme\SB\Controllers\Frontend\Ajax\AjaxLogin;
use RadiusTheme\SB\Controllers\Frontend\OrderReviewQuantity;
use RadiusTheme\SB\Controllers\Frontend\Ajax\UpdateCheckoutSection;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Main initialization class.
 */
final class ShopBuilder {

	/**
	 * Nonce id
	 *
	 * @var string
	 */
	public $nonceId = '__rtsb_wpnonce';

	/**
	 * Nonce Text
	 *
	 * @var string
	 */
	public $nonceText = 'rtsb_nonce';

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	public $post_type;

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	public $current_theme;
	/**
	 * Singleton
	 */

	/**
	 * URL for fetch API
	 *
	 * @var string
	 */
	public $BASE_API = 'https://shopbuilderwp.com/demo/wp-json/rtsb/v1/layouts/';

	use SingletonTrait;

	/**
	 * Class Constructor
	 */
	private function __construct() {
		$this->post_type     = 'product';
		$this->current_theme = wp_get_theme()->get( 'Template' ) ? wp_get_theme()->get( 'Template' ) : ( wp_get_theme()->get( 'TextDomain' ) ? wp_get_theme()->get( 'TextDomain' ) : strtolower( str_replace( ' ', '', wp_get_theme()->get( 'Name' ) ) ) );
		add_action( 'plugins_loaded', [ $this, 'init' ], 15 );
		add_action( 'upgrader_process_complete', [ $this, 'maybe_run_after_update' ], 10, 2 );

		// Runs before anything reads or writes the object cache: Installation is
		// on init:5 and ModuleManager on init:15. Registered here rather than in
		// init() so version tracking stays accurate even while a dependency is
		// missing and the rest of the plugin is dormant.
		add_action( 'init', [ Cache::class, 'maybe_rotate_group' ], 1 );

		// Register Plugin Active Hook.
		register_activation_hook( RTSB_FILE, [ Installation::class, 'activate' ] );

		// Register Plugin Deactivate Hook.
		register_deactivation_hook( RTSB_FILE, [ Installation::class, 'deactivation' ] );

		if ( Dependencies::instance()->check() ) {
			SupportController::instance();
		}
		// HPOS Declare compatibility.
		add_action(
			'before_woocommerce_init',
			function () {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', RTSB_FILE, true );
				}
			}
		);
	}

	/**
	 * Assets url generate with given assets file
	 *
	 * @param string $file File.
	 *
	 * @return string
	 */
	public function get_assets_uri( $file ) {
		$file = ltrim( $file, '/' );

		return trailingslashit( RTSB_URL . '/assets' ) . $file;
	}

	/**
	 * Assets path.
	 *
	 * @param string $file File.
	 *
	 * @return string
	 */
	public function get_assets_path( $file ) {
		$file = ltrim( $file, '/' );

		return trailingslashit( RTSB_PATH . 'assets' ) . $file;
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function get_template_path() {
		return apply_filters( 'rtsb/template/path', 'shopbuilder/' );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( RTSB_FILE ) );
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		// Plugin row links (Settings/Demo/Documentation/Get Pro) must register
		// regardless of dependency state so users can always reach Settings or
		// docs even when WooCommerce/Elementor is inactive.
		if ( is_admin() ) {
			PluginRow::instance();
		}

		if ( ! Dependencies::instance()->check() ) {
			return;
		}

		try {
			do_action( 'rtsb/before/loaded' );
			CacheController::instance();
			// Include File.
			AssetsController::instance();
			ModuleManager::instance();

			// Ajax.
			AddToCart::instance();
			AjaxLogin::instance();
			UpdateCheckoutSection::instance();

			// Checkout.
			OrderReviewQuantity::instance();

			BuilderController::instance();
			FilterHooks::init_hooks();
			ActionHooks::init_hooks();

			Installation::init();
			Migration::init();
			AIinit::instance();

			if ( is_admin() ) {
				AdminInit::instance();
			}

			Shortcodes::instance();

			do_action( 'rtsb/after/loaded' );
		} catch ( \Throwable $e ) {
			error_log( 'ShopBuilder init error: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	/**
	 * Maybe Run After Update
	 *
	 * @param object $upgrader_object Upgrader Object.
	 * @param array  $options Options.
	 *
	 * @return void
	 */
	public function maybe_run_after_update( $upgrader_object, $options ) {
		if ( 'plugin' !== $options['type'] || ! in_array( $options['action'], [ 'install', 'update' ], true ) ) {
			return;
		}

		$basename = plugin_basename( RTSB_FILE );
		$slug     = dirname( $basename );

		// Bulk update flow: WordPress passes every plugin in the batch.
		if ( ! empty( $options['plugins'] ) ) {
			if ( in_array( $basename, (array) $options['plugins'], true ) ) {
				Cache::clear_all_cache();
			}
			return;
		}

		// Single update flow: WordPress passes the basename under `plugin`.
		if ( ! empty( $options['plugin'] ) ) {
			if ( $basename === $options['plugin'] ) {
				Cache::clear_all_cache();
			}
			return;
		}

		// Upload-based installation: only the destination folder name is known.
		if (
			! empty( $upgrader_object->result['destination_name'] ) &&
			$slug === $upgrader_object->result['destination_name']
		) {
			Cache::clear_all_cache();
		}
	}

	/**
	 * Checks if Pro version installed.
	 *
	 * @return boolean
	 */
	public function has_pro() {
		return function_exists( 'rtsbpro' ); // Fns::is_pro_authorized() will add in the future for checking pro Version Compatibility.
	}

	/**
	 * PRO Version URL.
	 *
	 * @return string
	 */
	public function pro_version_link() {
		return 'https://shopbuilderwp.com/';
	}
}

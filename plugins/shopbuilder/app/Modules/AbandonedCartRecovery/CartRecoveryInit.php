<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;


defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class CartRecoveryInit {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
		$this->api_hooks();
		$this->cart_activity();
		$this->assets();
		$this->cron_hooks();
		$this->order_activity();
		$this->admin_related_hooks();
	}

	/**
	 * Everything already in init hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		CartRecoveryTable::instance(); // Already In init hook.
		do_action( 'rtsb/cart/recovery/init' );
	}

	/**
	 * @return void
	 */
	public function admin_related_hooks() {
		// Adding menu to view cart abandonment report.
		add_action( 'admin_menu', [ CartAbandonedTracking::instance(), 'abandoned_cart_tracking_menu' ], 999 );
	}
	/**
	 * @return void
	 */
	public function assets() {
		add_action( 'wp_enqueue_scripts', [ CartRecoveryAssets::instance(), 'frontend_assets' ], 99 );
	}
	/**
	 * @return void
	 */
	public function api_hooks() {
		add_action( 'rest_api_init', [ ApiCartRecovery::instance(), 'register_routes' ] );
	}
	/**
	 * @return void
	 */
	public function cart_activity() {
		// Quick view AJAX.
		add_action( 'wp_ajax_rtsb_abandoned_cart_recovery', [ CartActivity::instance(), 'abandoned_cart_update' ] );
		add_action( 'wp_ajax_nopriv_rtsb_abandoned_cart_recovery', [ CartActivity::instance(), 'abandoned_cart_update' ] );
		add_action( 'wp', [ CartActivity::instance(), 'restore_cart_from_abandonment' ] );
		add_action( 'wp', [ CartActivity::instance(), 'unsubscribe_cart_from_abandonment' ] );
		add_action( 'woocommerce_before_calculate_totals', [ CartActivity::instance(), 'before_calculate_totals' ], 999 );
		add_action( 'woocommerce_store_api_cart_totals', [ CartActivity::instance(), 'before_calculate_totals' ], 999 ); // Gutenberg.
	}
	/**
	 * Cron hooks.
	 */
	public function cron_hooks() {
		$cron = CartRecoveryCron::instance();
		// Note: the 'cron_schedules' filter is registered globally in ActionHooks::init_hooks()
		// so custom schedules exist regardless of this module's active state.
		$cron->run_cron(); // Run cron on init hook.
		add_action( 'rtsb_abandoned_cart_recovery_action', [ $cron, 'detect_abandoned_cart_recovery' ] );
		add_action( 'rtsb_send_abandoned_cart_emails', [ $cron, 'process_abandoned_cart_emails' ] );
	}
	/**
	 * @return void
	 */
	public function order_activity() {
		add_action( 'woocommerce_order_status_changed', [ CartActivity::instance(), 'cart_abandonment_on_status_change' ], 99, 4 );
		add_action( 'woocommerce_checkout_create_order_line_item', [ CartActivity::instance(), 'checkout_create_order_line_item' ], 10, 3 );
		add_filter( 'woocommerce_order_item_get_formatted_meta_data', [ CartActivity::instance(), 'get_order_meta' ], 10 );
	}
}

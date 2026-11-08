<?php
/**
 * Plugin Name:     Buy Now Button for WooCommerce
 * Plugin URI:
 * Description:     Buy your product only one step in the Product Detail page & shop catalog.
 * Author:          Codeixer
 * Author URI:      https://codeixer.com/
 * Text Domain:     buy-now-woo
 * Domain Path:     /languages
 * Version:         1.1.4
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Tested up to: 7.1
 * WC requires at least: 4.9
 * WC tested up to: 11.1
 * Requires Plugins: woocommerce
 *
 * @package         Woo_Buy_Now
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * First, we need autoload via Composer to make everything works.
 */
require __DIR__ . '/vendor/autoload.php';

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\\Automattic\\WooCommerce\\Utilities\\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);

// Include the loader.
require_once __DIR__ . '/loader.php';
require __DIR__ . '/includes/usage-tracking/Client.php';

function cdx_init_tracker_buy_now_woo() {

	$client = new NS7_UT\Client(
		'Z9ZrI6VuUH8FJZNud9fo',
		'Buy Now Button for WooCommerce',
		__FILE__
	);

	// Active insights
	$client->insights()->add_plugin_data()->init();
}

cdx_init_tracker_buy_now_woo();

/**
 * Only works with PHP 7.4 or later.
 */
if ( version_compare( phpversion(), '7.4', '<' ) ) {
	/**
	 * Adds a message for outdated PHP version.
	 */
	function buy_now_woo_php_upgrade_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: %s is the current PHP version */
			esc_html__( 'WooCommerce Simple Buy Now requires at least PHP version 7.4 to work, you are running version %s. Please contact your administrator to upgrade PHP version!', 'buy-now-woo' ),
			esc_html( phpversion() )
		);

		printf( '<div class="error"><p>%s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		// Deactivate plugin securely.
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	}
	add_action( 'admin_notices', 'buy_now_woo_php_upgrade_notice' );
	return;
}

if ( defined( 'BUY_NOW_WOO_VERSION' ) ) {
	return;
}
define( 'BUY_NOW_WOO_VERSION', get_file_data( __FILE__, array( 'Version' => 'Version' ) )['Version'] );
define( 'BUY_NOW_DEV_MODE', false );
define( 'BUY_NOW_WOO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUY_NOW_WOO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BUY_NOW_WOO_BASE_FILE', plugin_basename( __FILE__ ) );
/**
 * Admin notice: Dev mode is enabled. make sure turn this off before release.
 */
if ( BUY_NOW_DEV_MODE === true ) {
	add_action( 'admin_notices', 'buy_now_woo_dev_mode_notice' );
	function buy_now_woo_dev_mode_notice() {
		$plugin_name = 'Buy Now Button for WooCommerce';
		printf( '<div class="error"><p>%s</p></div>', esc_html__( 'Dev mode is enabled. make sure turn this off before release. Plugin Name: ' . $plugin_name . ' Version: ' . BUY_NOW_WOO_VERSION, 'buy-now-woo' ) );
	}
}

/**
 * Admin notice: Require WooCommerce.
 */
function buy_now_woo_admin_notice() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$woo_url  = esc_url( 'https://wordpress.org/plugins/woocommerce/' );
		$woo_link = '<a href="' . $woo_url . '" target="_blank" rel="noopener noreferrer">WooCommerce</a>';
		// translators: %s is the link to WooCommerce plugin.
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Buy Now for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'buy-now-woo' ), $woo_link ) . '</strong></p></div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( 'WooCommerce' ) ) {
			if ( ! class_exists( 'Buy_Now_Woo\\Plugin' ) ) {
				return;
			}
			$GLOBALS['buy_now_woo'] = Buy_Now_Woo\Plugin::get_instance();
		}
		add_action( 'admin_notices', 'buy_now_woo_admin_notice', 4 );
	}
);

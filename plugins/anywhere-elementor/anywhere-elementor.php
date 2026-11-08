<?php
/**
 * Plugin Name: Dynific Addons for Elementor
 * Description: Free version of Dynific Addons for Elementor.
 * Plugin URI: https://www.wpbuilderaddons.com/dynific-addons-elementor/
 * Author: WPVibes
 * Version: 1.3
 * Author URI: https://wpvibes.com/
 * Elementor tested up to: 4.0
 * Elementor Pro tested up to: 4.0
 * License:      GNU General Public License v2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ae-pro
 * Domain Path: includes/languages/
 *
 * @package Aepro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// @aep-free-only-start
// If Pro version is active, deactivate free to avoid conflicts.
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$aep_pro_plugin = 'anywhere-elementor-pro/anywhere-elementor-pro.php';
if ( is_plugin_active( $aep_pro_plugin ) ) {
	if ( is_admin() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		update_option( 'aep_free_deactivated_by_pro', 1 );

		add_action(
			'admin_notices',
			function() {
				if ( ! get_option( 'aep_free_deactivated_by_pro' ) ) {
					return;
				}

				delete_option( 'aep_free_deactivated_by_pro' );

				printf(
					'<div class="notice notice-info"><p>%s</p></div>',
					esc_html__( 'Dynific Addons for Elementor Pro is active. The free version was deactivated to prevent conflicts.', 'ae-pro' )
				);
			}
		);
	}

	return;
}
// @aep-free-only-end


define( 'AE_PRO_VERSION', '1.3' );
define( 'AE_PLUGIN_NAME', 'Dynific Addons for Elementor' );
define( 'AE_PLUGIN_VERSION', '1.3' );
define( 'AE_PLUGIN_LEGACY_NAME', '' );
define( 'AE_PRO_URL', plugins_url( '/', __FILE__ ) );
define( 'AE_PRO_PATH', plugin_dir_path( __FILE__ ) );
define( 'AE_PRO_BASE', plugin_basename( __FILE__ ) );
define( 'AE_PRO_FILE', __FILE__ );

define( 'AE_PRO_SCRIPT_SUFFIX', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );

define( 'AEP_PHP_VERSION_REQUIRED', '5.6' );

require_once __DIR__ . '/includes/core.php';


if ( version_compare( PHP_VERSION, AEP_PHP_VERSION_REQUIRED, '<' ) ) {

	add_action( 'admin_notices', 'aep_php_fail' );
	add_action( 'admin_init', 'aep_deactivate' );
	return;
}

/**
 * Handles admin notice for PHP version requirements
 *
 * @since 0.1.0
 */
if ( ! function_exists( 'aep_php_fail' ) ) {
function aep_php_fail() {
	global $php_version_required;

	$class = 'notice notice-error';
	/* translators: %s: PHP version Error */
	$message = sprintf( __( 'Dynific Addons for Elementor Pro needs at least PHP version %s to work properly. We deactivated the plugin for now.', 'ae-pro' ), AEP_PHP_VERSION_REQUIRED );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
}

/**
 * Handles plugin deactivate actions.
 *
 * @since 1.0
 */
if ( ! function_exists( 'aep_deactivate' ) ) {
function aep_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
}


/**
 * Handles plugin activation actions.
 *
 * @since 1.0
 */
if ( ! function_exists( 'aep_activate' ) ) {
function aep_activate() {

	if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
		return;
	}
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
}
register_activation_hook( __FILE__, 'aep_activate' );




global $ae_template;
$ae_template = get_option( 'template' );


/**
 * Loads text domain
 *
 * @since 1.0
 */
if ( ! function_exists( 'ae_pro_load_plugin_textdomain' ) ) {
function ae_pro_load_plugin_textdomain() {
	load_plugin_textdomain( 'ae-pro' );
}
}
add_action( 'init', 'ae_pro_load_plugin_textdomain' );

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	/**
	 * Check if Elementor is installed
	 *
	 * @return bool
	 */
	function _is_elementor_installed() {
		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


if ( ! function_exists( 'disable_selective_loading' ) ) {
	add_action( 'elementor/element/before_parse_css', 'disable_selective_loading' );
	function disable_selective_loading() {
		add_filter( 'ae/skin_control_loading', 'ae_return_true' );
	}
}

if ( ! function_exists( 'enable_selective_loading' ) ) {
	add_action( 'elementor/element/parse_css', 'enable_selective_loading' );
	function enable_selective_loading() {
		remove_filter( 'ae/skin_control_loading', 'ae_return_true' );
	}
}

if ( ! function_exists( 'ae_return_true' ) ) {
	function ae_return_true() {
		return true;
	}
}


if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
} else {
	require_once AE_PRO_PATH . 'vendor/autoload.php';

	require AE_PRO_PATH . 'includes/bootstrap.php';

	if ( file_exists( AE_PRO_PATH . 'pro/aepro.php' ) ) {
		require_once AE_PRO_PATH . 'pro/aepro.php';
	}
}

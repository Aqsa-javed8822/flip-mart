<?php
/**
 * Shortcodes Class.
 *
 * This class contains all the Shortcodes.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers;

use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * CacheController Class.
 */
class CacheController {
	/**
	 * Singleton Trait
	 */
	use SingletonTrait;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		// Hook to add custom item to admin bar.
		add_action( 'admin_bar_menu', [ $this, 'admin_bar_menu' ], 9999 );
		add_action( 'init', [ $this, 'admin_bar_menu_action' ] );
		add_action( 'admin_notices', [ $this, 'cache_notice' ] );
	}

	/**
	 * @param object $wp_admin_bar hello.
	 * @return void
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
		// Only add for administrators.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// Add a top-level item.
		$wp_admin_bar->add_node(
			[
				'id'    => 'shop_builder',
				'title' => 'ShopBuilder',
			]
		);
		// Add submenu items.
		$wp_admin_bar->add_node(
			[
				'id'     => 'clear_all_cache',
				'parent' => 'shop_builder', // Set the parent to the top-level item ID.
				'title'  => esc_html__( 'Purge All Cache', 'shopbuilder' ),
				'href'   => wp_nonce_url( add_query_arg( 'rtsb_clear_cache', 'all', add_query_arg( [] ) ), rtsb()->nonceText ),
			]
		);

		if ( Fns::is_optimization_enabled() ) {
			$wp_admin_bar->add_node(
				[
					'id'     => 'clear_asset_cache',
					'parent' => 'shop_builder',
					'title'  => esc_html__( 'Purge Asset Cache', 'shopbuilder' ),
					'href'   => wp_nonce_url( add_query_arg( 'rtsb_clear_cache', 'asset', add_query_arg( [] ) ), rtsb()->nonceText ),
				]
			);
		}

		$wp_admin_bar->add_node(
			[
				'id'     => 'clear_object_cache',
				'parent' => 'shop_builder',
				'title'  => esc_html__( 'Purge Object Cache', 'shopbuilder' ),
				'href'   => wp_nonce_url( add_query_arg( 'rtsb_clear_cache', 'data', add_query_arg( [] ) ), rtsb()->nonceText ),
			]
		);
		$wp_admin_bar->add_node(
			[
				'id'     => 'clear_template_cache',
				'parent' => 'shop_builder',
				'title'  => esc_html__( 'Purge Template Cache', 'shopbuilder' ),
				'href'   => wp_nonce_url( add_query_arg( 'rtsb_clear_cache', 'template', add_query_arg( [] ) ), rtsb()->nonceText ),
			]
		);
	}


	/**
	 *
	 * @return mixed
	 */
	public function admin_bar_menu_action() {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) ), rtsb()->nonceText ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$clear_cache = sanitize_text_field( wp_unslash( $_GET['rtsb_clear_cache'] ?? '' ) );
		if ( empty( $clear_cache ) ) {
			return;
		}

		switch ( $clear_cache ) {
			case 'all':
				Cache::clear_all_cache();
				break;
			case 'data':
				Cache::clear_data_cache();
				break;
			case 'template':
				Cache::clear_template_cache();
				break;
			case 'asset':
				Cache::clear_asset_cache();
				break;
		}
		// Set the notice flag.
		set_transient( 'rtsb_cache_cleared_notice', true, 30 );
		$referrer = wp_get_referer();  // Get the referrer URL.
		if ( $referrer ) {
			// Rebuild the URL with the original path and query string.
			$redirect_url = $referrer;
			// Redirect to the referrer URL with its query string intact.
			wp_safe_redirect( $redirect_url );
			exit;
		}
		// Fallback: if referrer is not available, redirect to home_url().
		wp_safe_redirect( home_url() );
		exit;
	}

	/**
	 * @return void
	 */
	public function cache_notice() {
		if ( get_transient( 'rtsb_cache_cleared_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible">';
			echo '<p>' . esc_html__( 'Shopbuilder cache has been successfully cleared.', 'shopbuilder' ) . '</p>';
			echo '</div>';
			delete_transient( 'rtsb_cache_cleared_notice' );
		}
	}
}

<?php

namespace RadiusTheme\SB\Controllers;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


class Dependencies {

	const MINIMUM_PHP_VERSION = '7.4';

	const PLUGIN_NAME = 'ShopBuilder - Woocommerce Builder for Elementor';

	use SingletonTrait;

	/**
	 * @var array
	 */
	private $missing = [];
	/**
	 * @var bool
	 */
	private $allOk = true;

	/**
	 * @return bool
	 */
	public function check() {

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'minimum_php_version' ] );
			$this->allOk = false;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( ! function_exists( 'wp_create_nonce' ) ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}

		// Check WooCommerce.
		$woocommerce = 'woocommerce/woocommerce.php';
		if ( ! is_plugin_active( $woocommerce ) ) {
			if ( $this->is_plugins_installed( $woocommerce ) ) {
				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce );
				$message        = sprintf(
					'<strong>%s</strong> requires <strong>WooCommerce</strong> plugin to be active. Please activate WooCommerce to continue.',
					esc_html( self::PLUGIN_NAME ),
				);
				$button_text    = 'Activate WooCommerce';
			} else {
				$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
				$message        = sprintf(
					'<strong>%s</strong> requires <strong>WooCommerce</strong> plugin to be active. Please activate WooCommerce to continue.',
					esc_html( self::PLUGIN_NAME ),
				);
				$button_text    = 'Install WooCommerce';
			}
			$this->missing['woocommerce'] = [
				'name'       => 'WooCommerce',
				'slug'       => 'woocommerce',
				'file_name'  => $woocommerce,
				'url'        => $activation_url,
				'message'    => $message,
				'button_txt' => $button_text,
			];
			$this->allOk                  = false;
		}

		if ( ! empty( $this->missing ) ) {
			add_action( 'admin_notices', [ $this, '_missing_plugins_warning' ] );
		}

		return $this->allOk;
	}

	/**
	 * Admin Notice For Required PHP Version
	 */
	public function minimum_php_version() {
		// Protect plugin Activation with error.
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html( '"%1$s" requires "%2$s" version %3$s or greater.' ),
			'<strong>ShopBuilder</strong>',
			'<strong>PHP</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Adds admin notice.
	 */
	public function _missing_plugins_warning() {
		$missingPlugins = '';
		$counter        = 0;
		foreach ( $this->missing as $plugin ) {
			$counter++;
			if ( $counter == count( $this->missing ) ) {
				$sep = '';
			} elseif ( $counter == count( $this->missing ) - 1 ) {
				$sep = ' and ';
			} else {
				$sep = ', ';
			}
			if ( current_user_can( 'activate_plugins' ) ) {
				$button = ! empty( $plugin['url'] ) ? '<p><a href="' . esc_url( $plugin['url'] ) . '" class="button-primary">' . esc_html( $plugin['button_txt'] ) . '</a></p>' : '';
				printf( '<div class="error"><p>%1$s</p>%2$s</div>', $plugin['message'], $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				$missingPlugins .= '<strong>' . esc_html( $plugin['name'] ) . '</strong>' . $sep;
			}
		}
	}

	/**
	 * @param $plugin_file_path
	 *
	 * @return bool
	 */
	public function is_plugins_installed( $plugin_file_path = null ) {
		$installed_plugins_list = get_plugins();

		return isset( $installed_plugins_list[ $plugin_file_path ] );
	}
}

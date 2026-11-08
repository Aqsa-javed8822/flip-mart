<?php

namespace RadiusTheme\SB\Controllers;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\ElementorDataMap;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Plugin Support
 */
class SupportController {
	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'plugins_support' ], 9 );
		add_action( 'init', [ $this, 'themes_support' ], 9 );
		add_filter( 'woocommerce_locate_template', [ $this, 'locate_template' ], 10, 2 );
		if ( defined( 'RTSB_DEVELOPER_MODE' ) && RTSB_DEVELOPER_MODE ) {
			$this->developer_support();
		}
	}

	/**
	 * @return void
	 */
	public function developer_support() {
		add_filter( 'rtsb/builder/preview/access', '__return_false', 5 );
	}

	/**
	 * Theme Support
	 *
	 * @return void
	 */
	public function plugins_support() {
		// Check for plugin using plugin name.
		$active_plugin = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$supports      = [
			'woo-address-book/woocommerce-address-book.php' => PluginsSupport\WooAddressBook::class,
			'woo-product-variation-gallery/woo-product-variation-gallery.php' => PluginsSupport\RtwpvgSupport::class,
			'woo-product-variation-swatches-pro/woo-product-variation-swatches-pro.php' => PluginsSupport\RtwpvsSupport::class,
			'products-visibility-by-user-roles/addify_product_visibility.php' => PluginsSupport\AddifyVisibilityByRoles::class,
			'woocommerce-payments/woocommerce-payments.php' => PluginsSupport\WooPaymentsSupport::class,
			'woocommerce-paypal-payments/woocommerce-paypal-payments.php' => PluginsSupport\PayPalPPCPSupport::class,
		];
		$supports      = apply_filters( 'rtsb/plugins/support', $supports );
		foreach ( $supports as $active_path => $class ) {
			$is_active = in_array( $active_path, $active_plugin, true );
			if ( $is_active && class_exists( $class ) && method_exists( $class, 'instance' ) ) {
				$class::instance();
			}
		}
	}
	/**
	 * Theme Support
	 *
	 * @return void
	 */
	public function themes_support() {
		$themeClass = ucwords( str_replace( [ '_', '-', ' ' ], '', rtsb()->current_theme ) );

		$supportClass = apply_filters(
			'RadiusTheme/SB/ThemesSupport/Class', // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase
			[
				'ThemesSupport' => 'RadiusTheme\SB\Controllers\ThemesSupport\\' . $themeClass . '\\ThemeSupport',
				'TheTheme'      => '\ThemeSupport',
			]
		);

		foreach ( $supportClass as $key => $theClass ) {
			$themeSupport = get_theme_file_path( '/shopbuilder/ThemeSupport.php' );
			if ( 'TheTheme' === $key && file_exists( $themeSupport ) ) {
				require_once $themeSupport;
			}
			if ( class_exists( $theClass ) && method_exists( $theClass, 'instance' ) ) {
				$theClass::instance();
			}
		}
	}

	/**
	 * Filters and overrides the default WooCommerce template paths for specific templates
	 * based on builder context and available Elementor widgets.
	 *
	 * - Replaces the checkout review order template if a custom widget is present.
	 * - Replaces the single product description tab template if on a product page.
	 *
	 * @param string $template      The path to the template to include.
	 * @param string $template_name The name of the template.
	 *
	 * @return string Modified or original template path.
	 */
	public function locate_template( $template, $template_name ) {
		if ( 'single-product/tabs/description.php' === $template_name && BuilderFns::is_product() ) {
			$template = Fns::locate_template( 'elementor/single-product/tab-description' );
		}
		return $template;
	}
}

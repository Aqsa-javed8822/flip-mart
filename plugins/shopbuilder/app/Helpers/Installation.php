<?php
/**
 * Activation Helpers class
 *
 * @package  RadiusTheme\SB
 */

namespace RadiusTheme\SB\Helpers;

use WC_Product_Simple;
use RadiusTheme\SB\Models\Settings;
use RadiusTheme\SB\Models\ExtraSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Activation Helpers class
 */
class Installation {
	/**
	 * Init.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'check_version' ], 5 );
	}

	/**
	 * Check version.
	 *
	 * @return int|void
	 */
	public static function check_version() {
		$version = ExtraSettings::instance()->get_option( 'rtsb_version', '' ) ?? get_option( 'rtsb_version' );
		if ( version_compare( $version, RTSB_VERSION, '<' ) ) {
			if ( ! $version ) {
				self::set_default_options();
				if ( ! Fns::get_product() ) {
					return self::create_wc_product();
				}
			}
			self::update_rtsb_version();
		}
	}

	/**
	 * Activation.
	 *
	 * @return void
	 */
	public static function activate() {
		if ( ! is_blog_installed() ) {
			return;
		}

		if ( ExtraSettings::instance()->get_option( 'first_instilled_version', false ) ) {
			ExtraSettings::instance()->set_option( 'first_instilled_version', RTSB_VERSION );
		}

		if ( ! ExtraSettings::instance()->get_option( 'rtsb_plugin_activation_time', false ) ) {
			ExtraSettings::instance()->set_option( 'rtsb_plugin_activation_time', strtotime( 'now' ) );
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'rtsb_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'rtsb_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		delete_transient( 'rtsb_installing' );
	}

	/**
	 * Update version.
	 *
	 * @return void
	 */
	private static function update_rtsb_version() {
		ExtraSettings::instance()->set_option( 'rtsb_version', RTSB_VERSION );
	}

	/**
	 * Deactivation.
	 *
	 * @return void
	 */
	public static function deactivation() {
		delete_option( 'shopbuilder_permalinks_flushed' );
		self::clear_scheduled_events();
	}
	/**
	 * Clear Scheduled Events
	 *
	 * @return void
	 */
	public static function clear_scheduled_events() {
		$schedule = get_option( 'rtsb_cron_schedule_free', [] );
		if ( empty( $schedule ) ) {
			return;
		}
		foreach ( $schedule as $v ) {
			wp_clear_scheduled_hook( $v );
		}
	}
	/**
	 * Set default options.
	 *
	 * @return void
	 */
	public static function set_default_options() {
		$sections = Settings::instance()->get_sections();
		foreach ( $sections as $sections_id  => $section ) {
			if ( ! empty( $section['list'] ) ) {
				foreach ( $section['list'] as $blocks_id  => $blocks ) {
					$rawOptions = [];
					if ( ! rtsb()->has_pro() && 'free' !== ( $blocks['package'] ?? '' ) ) {
						continue;
					}
					$rawOptions['active'] = $blocks['active'] ?? null;

					if ( ! empty( $blocks['fields'] ) ) {
						foreach ( $blocks['fields'] as $field_id  => $field ) {
							$type                    = 'array' === gettype( $field['value'] ) ? [] : '';
							$rawOptions[ $field_id ] = ! empty( $field['value'] ) ? $field['value'] : $type;
						}
					}
					Fns::set_options( $sections_id, $blocks_id, $rawOptions );
				}
			}
		}
	}

	/**
	 * Create product.
	 *
	 * @return int
	 */
	public static function create_wc_product() {
		$product = new WC_Product_Simple();
		$product->set_name( 'Confirm at least one product is created before deleting' );
		$product->set_description( 'This is a ShopBuilder demo preview product' );
		$product->set_short_description( 'This is a ShopBuilder product' );
		$product->set_status( 'draft' );

		$product->set_regular_price( 50 );
		$product->set_sale_price( 49 );
		$product->set_price( 49 );

		$product->set_sku( '' );
		return $product->save();
	}
}

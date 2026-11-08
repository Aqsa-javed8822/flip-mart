<?php

namespace RadiusTheme\SB\Helpers;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Models\ExtraSettings;
use RadiusTheme\SB\Models\TemplateSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class Migration {
	private static $first_instilled;

	public static function init() {
		self::$first_instilled = ExtraSettings::instance()->get_option( 'first_instilled_version', false );
		add_action( 'init', [ __CLASS__, 'the_migration' ], 5 );
	}

	/**
	 * @return void
	 */
	public static function the_migration() {

		if ( self::$first_instilled && version_compare( self::$first_instilled, RTSB_VERSION, '==' ) ) {
			return;
		}
		$v_2_0_4 = ExtraSettings::instance()->get_option( 'settings_migration_v_2_0_4', false );
		if ( ! $v_2_0_4 ) {
			self::settings_migration_plugin_v_2_0_4();
			ExtraSettings::instance()->set_option( 'settings_migration_v_2_0_4', RTSB_VERSION );
			return;
		}

		$v_2_1_16 = ExtraSettings::instance()->get_option( 'template_settings_migration_v_2_1_16', false );
		if ( ! $v_2_1_16 ) {
			self::template_settings_migration_plugin_v_2_1_16();
			ExtraSettings::instance()->set_option( 'template_settings_migration_v_2_1_16', RTSB_VERSION );
			return;
		}
		// return;
	}
	/**
	 * @return void
	 */
	private static function remove_option_for_missing_builder_v_2_0_4( $name ) {
		$array   = explode( '_', $name );
		$post_id = end( $array );
		if ( ! is_numeric( $post_id ) ) {
			return;
		}
		if ( get_post_status( $post_id ) ) {
			return;
		}
		TemplateSettings::instance()->delete_option( $name );
	}
	/**
	 * @return void
	 */
	public static function settings_migration_plugin_v_2_0_4() {
		global $wpdb;
		// Prefixes to search for.
		$prefixes = [
			'rtsb_tb_template',
			'rtsb_template_specific',
			'rtsb_template_for',
		];

		// Array to store matching options.
		// Loop through each prefix.
		foreach ( $prefixes as $prefix ) {
			// Run a direct database query to fetch option names and values.
			$query               = $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( $prefix ) . '%' );
			$options_with_prefix = $wpdb->get_results( $query, OBJECT ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
			if ( $options_with_prefix ) {
				foreach ( $options_with_prefix as $option ) {
					$value = maybe_unserialize( $option->option_value );
					if ( ! empty( $value ) ) {
						 TemplateSettings::instance()->set_option( $option->option_name, $value );
					}
					delete_option( $option->option_name );
					self::remove_option_for_missing_builder_v_2_0_4( $option->option_name );
				}
			}
		}

		// Extra Setings.
		$extra_prefixes = [
			'rtsb_version',
			'rtsb_wishlist_db_version',
			'rtsb_compare_db_version',
			'rtsbpro_version',
		];
		// Array to store matching options.
		// Loop through each prefix.
		foreach ( $extra_prefixes as $prefix ) {
			// Run a direct database query to fetch option names and values.
			$query               = $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( $prefix ) . '%' );
			$options_with_prefix = $wpdb->get_results( $query, OBJECT ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
			if ( $options_with_prefix ) {
				foreach ( $options_with_prefix as $option ) {
					$value = maybe_unserialize( $option->option_value );
					if ( ! empty( $value ) ) {
						ExtraSettings::instance()->set_option( $option->option_name, $value );
					}
					delete_option( $option->option_name );
				}
			}
		}
	}

	/**
	 * @return void
	 */
	private static function template_settings_migration_plugin_v_2_1_16() {
		$args     = [
			'post_type'   => BuilderFns::$post_type_tb,
			'numberposts' => -1,
			'fields'      => 'ids',
		];
		$posts_id = get_posts( $args );
		if ( ! empty( $posts_id ) ) {
			foreach ( $posts_id as $id ) {
				$template_type = BuilderFns::builder_type( $id );
				$options_name  = BuilderFns::option_name_by_template_id( $id );
				$product_ids   = TemplateSettings::instance()->get_option( $options_name );
				if ( 'product' === $template_type && ! empty( $product_ids ) ) {
					update_post_meta( $id, '_is_product_page_template_for', 'specific_products' );
				}
			}
		}
	}
}

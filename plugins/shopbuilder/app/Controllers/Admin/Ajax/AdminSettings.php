<?php

namespace RadiusTheme\SB\Controllers\Admin\Ajax;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Models\Settings;
use RadiusTheme\SB\Models\DataModel;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings Page.
 */
class AdminSettings {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'wp_ajax_rtsb_save_settings_data', [ $this, 'save_settings_data' ] );
		add_action( 'wp_ajax_rtsb_toggle_modules_activation', [ $this, 'toggle_modules' ] );
		add_action( 'wp_ajax_rtsb_get_multiselect_data', [ $this, 'get_multiselect_data' ] );
	}

	/**
	 * Get settings fields
	 *
	 * @return void
	 */
	public function get_multiselect_data() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Security error: Insufficient permissions.', 'shopbuilder' ) );
		}

		$func_with_param = sanitize_text_field( $_REQUEST['func_with_param'] ?? '' ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$s               = sanitize_text_field( $_REQUEST['s'] ?? '' ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$decodedString   = stripslashes( $func_with_param );
		$functionArray   = json_decode( $decodedString, true );
		$className       = $functionArray[0];
		$methodName      = $functionArray[1];
		$argument        = $functionArray[2] ?? [];
		$result          = [];
		$params          = [];
		if ( class_exists( $className ) && method_exists( $className, $methodName ) ) {
			$params[] = $s;
			$params[] = $argument;
			$result   = call_user_func_array( [ $className, $methodName ], $params );
		}
		wp_send_json_success( $result );
	}

	/**
	 * Get settings Data
	 *
	 * @return void
	 */
	public function get_settings_data() {
		$data = Settings::instance()->get_data();
		wp_send_json_success( $data );
	}

	/**
	 * Save settings data
	 *
	 * @return void
	 */
	public function save_settings_data() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Security error: Insufficient permissions.', 'shopbuilder' ) );
		}

		$section_id = isset( $_POST['section_id'] ) ? sanitize_text_field( wp_unslash( $_POST['section_id'] ) ) : '';
		$block_id   = isset( $_POST['block_id'] ) ? sanitize_text_field( wp_unslash( $_POST['block_id'] ) ) : '';
		// Fns::set_options Sanitized all array values Before saving.
		$rawOptions = $_POST['options'] ?? []; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		$sections = Settings::instance()->get_sections();
		$options  = [];
		if ( ! empty( $sections[ $section_id ]['list'][ $block_id ]['fields'] ) ) {
			$fields     = $sections[ $section_id ]['list'][ $block_id ]['fields'];
			$db_options = Fns::get_options( $section_id, $block_id );
			foreach ( $fields as $field_id  => $field ) {
				if ( ! array_key_exists( $field_id, $db_options ) ) {
					$type                 = 'array' === gettype( $field['value'] ) ? [] : '';
					$options[ $field_id ] = ! empty( $field['value'] ) ? $field['value'] : $type;
				}
			}
		}

		$rawOptions = wp_parse_args( $rawOptions, $options );

		$status = Fns::set_options( $section_id, $block_id, $rawOptions );

		if ( boolval( $status['status'] ) ) {
			do_action( 'rtsb/after/saved/settings/success/' . $section_id . '/' . $block_id, $rawOptions );
			do_action( 'rtsb/after/saved/settings/success', $section_id, $block_id, $rawOptions );

			Cache::clear_asset_cache();

			wp_send_json_success( $status );
		} else {
			wp_send_json_error( $status );
		}
	}

	/**
	 * Toggle modules
	 *
	 * @return void
	 */
	public function toggle_modules() {

		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Security error: Insufficient permissions.', 'shopbuilder' ),
				]
			);
		}

		$section_id = isset( $_POST['section_id'] ) ? sanitize_text_field( wp_unslash( $_POST['section_id'] ) ) : '';
		$module_ids = isset( $_POST['module_ids'] ) ? array_map( 'sanitize_text_field', $_POST['module_ids'] ) : []; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$type       = isset( $_POST['type'] ) && 'active' === sanitize_text_field( wp_unslash( $_POST['type'] ) ) ? 'active' : false;

		if ( ! $section_id || empty( $module_ids ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Section , block or options may be empty', 'shopbuilder' ),
				]
			);
		}

		$sections = Settings::instance()->get_sections();
		if ( empty( $sections[ $section_id ] ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'No section found with given data', 'shopbuilder' ),
				]
			);
		}
		$options = DataModel::source()->get_option( $section_id, [] );
		foreach ( $module_ids as $module_id ) {
			if ( isset( $sections[ $section_id ]['list'][ $module_id ] ) ) {
				if ( isset( $sections[ $section_id ]['list'][ $module_id ]['package'] ) && 'pro-disabled' === $sections[ $section_id ]['list'][ $module_id ]['package'] ) {
					continue;
				}
				if ( 'active' === $type ) {
					$options[ $module_id ]['active']                         = 'on';
					$sections[ $section_id ]['list'][ $module_id ]['active'] = 'on';
				} else {
					$options[ $module_id ]['active']                         = '';
					$sections[ $section_id ]['list'][ $module_id ]['active'] = '';
				}
			}
			do_action( 'rtsb/before/save/options', $section_id, $module_id, $options[ $module_id ] ?? [] );
		}

		DataModel::source()->set_option( $section_id, $options );
		Cache::clear_asset_cache();

		wp_send_json_success(
			[
				'message'  => esc_html__( 'Successfully Saved', 'shopbuilder' ),
				'sections' => $sections,
			]
		);
	}
}

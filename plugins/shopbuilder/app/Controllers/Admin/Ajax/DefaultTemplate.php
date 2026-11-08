<?php

namespace RadiusTheme\SB\Controllers\Admin\Ajax;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\TemplateSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Default Template Switch.
 */
class DefaultTemplate {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'wp_ajax_rtsb_default_template', [ $this, 'response' ] );
	}

	/**
	 * Set Default Template.
	 *
	 * @return void
	 */
	public function response() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_send_json_error();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		$page_type       = isset( $_POST['template_type'] ) ? sanitize_text_field( wp_unslash( $_POST['template_type'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$default_page_id = isset( $_POST['set_default_page_id'] ) && 'publish' === get_post_status( $_POST['set_default_page_id'] ) ? absint( wp_unslash( $_POST['set_default_page_id'] ) ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$page_id         = isset( $_POST['page_id'] ) && 'publish' === get_post_status( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		Cache::clear_transient_cache();
		$the_cat_option     = BuilderFns::archive_option_name_by_template_id( $page_id );
		$the_cats           = TemplateSettings::instance()->get_option( $the_cat_option );
		$option_name        = BuilderFns::option_name( $page_type );
		$product_page_for   = get_post_meta( $page_id, '_is_product_page_template_for', true );
		$is_can_set_default = true;
		switch ( $page_type ) {
			case 'product':
				$product_page_for = empty( $product_page_for ) ? 'all_products' : $product_page_for;
				if ( 'all_products' !== $product_page_for ) {
					$is_can_set_default = null;
				}
				break;
			case 'archive':
				if ( ! empty( $the_cats ) ) {
					$is_can_set_default = null;
				}
				break;
			default:
		}

		if ( $is_can_set_default ) {
			$singleton_types = apply_filters(
				'rtsb/builder/singleton_types',
				array_keys( BuilderFns::builder_page_types() )
			);

			if ( in_array( $page_type, $singleton_types, true ) ) {
				// Singleton path: one enabled template per type — overwrite the type-wide option.
				TemplateSettings::instance()->set_option( $option_name, $default_page_id );
			} elseif ( $page_id ) {
				// Pool path: multi-enabled type — flip per-post meta flag, don't touch the type-wide option.
				if ( ! empty( $default_page_id ) ) {
					update_post_meta( $page_id, '_rtsb_tb_enabled_in_pool', 'on' );
				} else {
					delete_post_meta( $page_id, '_rtsb_tb_enabled_in_pool' );
				}
			}
		}

		do_action( 'rtsb/set/builder/default/template', $_POST, $page_id );

		$return = [
			'post_id'   => $default_page_id ,
			'page_type' => $page_type,
		];
		wp_send_json_success( $return );
		wp_die();
	}
}

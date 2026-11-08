<?php

namespace RadiusTheme\SB\Controllers\Admin\Ajax;

use Elementor\Plugin;
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
class CreateTemplate {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_action( 'wp_ajax_rtsb_builder_create_template', [ $this, 'response' ] );
	}

	/**
	 * Create template
	 *
	 * @return void
	 */
	public function response() {
		Cache::clear_transient_cache();
		$page_type        = isset( $_POST['page_type'] ) ? sanitize_text_field( wp_unslash( $_POST['page_type'] ) ) : null; // rtsb_tb_template_type - it represent to template type.
		$page_id          = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : null;
		$page_name        = isset( $_POST['page_name'] ) ? sanitize_text_field( wp_unslash( $_POST['page_name'] ) ) : null;
		$hasPro           = isset( $_POST['hasPro'] ) ? sanitize_text_field( wp_unslash( $_POST['hasPro'] ) ) : null;
		$edit_with        = isset( $_POST['template_edit_with'] ) ? sanitize_text_field( wp_unslash( $_POST['template_edit_with'] ) ) : null;
		$default_template = isset( $_POST['default_template'] ) ? sanitize_text_field( wp_unslash( $_POST['default_template'] ) ) : null;
		$import_layout    = isset( $_POST['import_default_layout'] ) ? sanitize_text_field( $_POST['import_default_layout'] ) : null; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$layoutJson       = isset( $_POST['demoData'] ) ? $_POST['demoData'] : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$product_id       = isset( $_POST['preview_product_id'] ) ? absint( $_POST['preview_product_id'] ) : null;
		$url              = '#';

		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) || ! $page_type ) {
			$return = [
				'success' => false,
				'post_id' => $page_id,
			];
			wp_send_json( $return );

		}
		if ( ! current_user_can( 'manage_options' ) ) {
			$return = [
				'success' => false,
				'post_id' => $page_id,
			];
			wp_send_json( $return );
		}

		// Nonce verified above.
		$can_save = apply_filters( 'rtsb/builder/create_template/can_save', true, $page_type, $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( true !== $can_save ) {
			wp_send_json(
				[
					'success' => false,
					'post_id' => $page_id,
					'message' => is_string( $can_save ) ? $can_save : esc_html__( 'Template cannot be saved.', 'shopbuilder' ),
				]
			);
		}

		add_filter(
			'pre_option_elementor_unfiltered_files_upload',
			function () {
				return '1';
			},
			99
		);

		Plugin::$instance->files_manager->clear_cache();
		add_filter( 'rtsb_import_status', '__return_true' );

		$option_name = BuilderFns::option_name( $page_type );

		$post_data = [
			'ID'         => $page_id,
			'post_title' => $page_name,
			'meta_input' => [
				BuilderFns::template_type_meta_key() => $page_type,
			],
		];
		if ( 'elementor' == $edit_with ) {
			$post_data['meta_input']['_elementor_edit_mode'] = 'builder';
		} elseif ( 'gutenberg' == $edit_with ) {
			$post_data['meta_input']['_elementor_edit_mode'] = '';
		}

		if ( $page_id ) {
			$page_id  = wp_update_post( $post_data );
			$new_page = false;
		} else {
			unset( $post_data['ID'] );
			$post_data['post_type']   = BuilderFns::$post_type_tb;
			$post_data['post_status'] = 'publish';
			$page_id                  = wp_insert_post( $post_data );
			$new_page                 = true;
			if ( 'elementor' == $edit_with ) {
				update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
				update_post_meta( $page_id, '_elementor_version', ELEMENTOR_VERSION );
			}
		}

		$edit_by = '';
		if ( $page_id ) {
			$edit_by           = Fns::page_edit_with( $page_id );
			$template_for      = sanitize_text_field( wp_unslash( $_POST['product_page_for'] ?? '' ) );
			$selected_category = array_unique( isset( $_POST['selected_category'] ) && is_array( $_POST['selected_category'] ) ? array_map( 'sanitize_text_field', $_POST['selected_category'] ) : [] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			if ( 'product' == $page_type ) {
				update_post_meta( $page_id, BuilderFns::$product_template_meta, $product_id );
				update_post_meta( $page_id, '_is_product_page_template_for', $template_for );
			}

			$has_default     = TemplateSettings::instance()->get_option( $option_name );
			$singleton_types = apply_filters(
				'rtsb/builder/singleton_types',
				array_keys( BuilderFns::builder_page_types() )
			);
			$is_pool_type    = ! in_array( $page_type, $singleton_types, true );
			if ( ( 'product' === $page_type && 'all_products' !== $template_for ) || ( 'archive' === $page_type && count( $selected_category ) ) ) {
				if ( ! $has_default ) {
					TemplateSettings::instance()->set_option( $option_name, '' );
				}
			} else {
				if ( 'default_template' === $default_template ) {
					if ( $is_pool_type ) {
						update_post_meta( $page_id, '_rtsb_tb_enabled_in_pool', 'on' );
					} else {
						TemplateSettings::instance()->set_option( $option_name, $page_id );
					}
				} else {
					if ( $is_pool_type ) {
						delete_post_meta( $page_id, '_rtsb_tb_enabled_in_pool' );
					} else {
						if ( ! $has_default ) {
							TemplateSettings::instance()->set_option( $option_name, '' );
						}
					}
				}
			}

			$action = 'edit';
			if ( 'elementor' === $edit_by ) {
				$action = 'elementor';
			}
			$url = add_query_arg(
				[
					'post'   => $page_id,
					'action' => $action,
				],
				admin_url( 'post.php' )
			);

			if ( ! empty( $layoutJson ) && is_array( $layoutJson ) ) {
				$content = wp_slash( wp_json_encode( $layoutJson ) );
				update_post_meta( $page_id, '_elementor_data', $content );
				update_post_meta( $page_id, '_rtsb_import_id', $import_layout );
			}
		}

		do_action( 'rtsb/after/create/and/edit/builder/template', $_POST, $page_id );

		$return = [
			'success'       => true,
			'post_id'       => $page_id,
			'post_edit_url' => $url,
			'new_page'      => $new_page,

		];
		if ( $edit_by ) {
			$return['edit_btn_text'] = sprintf(
				/* translators: %s: Edit */
				esc_html__( 'Edit with %s', 'shopbuilder' ),
				esc_html( $edit_by )
			);
		}
		wp_send_json( $return );
		wp_die();
	}
}

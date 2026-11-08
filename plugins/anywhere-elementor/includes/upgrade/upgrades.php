<?php
namespace Aepro\Upgrade;

use Elementor\Core\Base\Document;
use Elementor\Core\Upgrade\Updater;
use Elementor\Core\Upgrade\Upgrades as Core_Upgrades;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Upgrades {
	//phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	public static function _v_2_21_0( $updater ) {

		return self::repeater_widget_migration( $updater );
	}

	public static function repeater_widget_migration( $updater ) {

		global $wpdb;

		$widget_id = 'ae-acf';
		$post_ids  = $updater->query_col(
			'SELECT `post_id`
					FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = "_elementor_data"
					AND `meta_value` LIKE \'%"widgetType":"' . $widget_id . '"%\';'
		);

		if ( empty( $post_ids ) ) {
			return false;
		}

		error_log( $post_ids[0] . '  --  ' . end( $post_ids ) );
		foreach ( $post_ids as $post_id ) {
			$do_update = false;

			$document = Plugin::instance()->documents->get( $post_id );

			if ( ! $document ) {
				continue;
			}

			$data = $document->get_elements_data();

			if ( empty( $data ) ) {
				continue;
			}

			error_log( 'Json Before  - Post ID - ' . $post_id . ' --> ' . wp_slash( wp_json_encode( $data ) ) );

			$data = Plugin::$instance->db->iterate_data(
				$data,
				function( $element ) use ( &$do_update ) {
					if ( empty( $element['widgetType'] ) || $element['widgetType'] !== 'ae-acf' ) {
						return $element;
					}

					if ( ! empty( $element['settings']['is_sub_field'] ) && $element['settings']['is_sub_field'] == 'repeater' ) {

						// This ACF field widget is used for Repeater Sub Field
						if ( ! empty( $element['settings']['parent_field'] ) ) {
							$element['settings']['repeater_field'] = $element['settings']['parent_field'];
							$do_update                             = true;
						}

						if ( ! empty( $element['settings']['field_name'] ) ) {
							$element['settings']['repeater_sub_field'] = $element['settings']['field_name'];
							$do_update                                 = true;
						}
					}

					return $element;
				}
			);

			// Only update if needed.
			if ( ! $do_update ) {
				continue;
			}

			// We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
			$json_value = wp_slash( wp_json_encode( $data ) );
			error_log( 'Json Updated - Post ID - ' . $post_id . ' --> ' . $json_value );
			error_log( '---------------------------' );

			update_metadata( 'post', $post_id, '_elementor_data', $json_value );

		} // End foreach().

		return $updater->should_run_again( $post_ids );
	}
}

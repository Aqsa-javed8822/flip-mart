<?php

namespace Aepro\Admin;

use Aepro\Aepro;
use Aepro\Helper;

class TemplateSettingsSchema {

	public static function get_render_mode_choices() {
		$helper = self::get_helper();

		$render_modes = $helper->get_ae_render_mode_hook();
		$locked_modes = function_exists( 'aepro_get_locked_render_modes' ) ? (array) aepro_get_locked_render_modes() : [];
		$choices      = [];

		foreach ( $render_modes as $mode_key => $mode_label ) {
			$choices[ $mode_key ] = in_array( $mode_key, $locked_modes, true ) ? $mode_label . ' [Pro]' : $mode_label;
		}

		return $choices;
	}

	public static function get_usage_area_choices() {
		$hook_positions = Aepro::instance()->get_hook_positions();
		return apply_filters( 'ae_pro_filter_hook_positions', $hook_positions );
	}

	public static function get_apply_on_choices() {
		return [
			'single'  => __( 'Single Post', 'ae-pro' ),
			'archive' => __( 'Archive', 'ae-pro' ),
			'search'  => __( 'Search', 'ae-pro' ),
			'404'     => __( '404', 'ae-pro' ),
			'home'    => __( 'Home', 'ae-pro' ),
		];
	}

	public static function get_post_type_choices() {
		return self::get_helper()->get_rule_post_types();
	}

	public static function get_taxonomy_choices() {
		return self::get_helper()->get_rules_taxonomies();
	}

	public static function get_post_type_archive_choices() {
		return self::get_helper()->get_post_types_with_archive();
	}

	public static function get_page_layout_choices() {
		return [
			''    => __( 'Default', 'ae-pro' ),
			'ec'  => __( 'Elementor Canvas', 'ae-pro' ),
			'ehf' => __( 'Elementor Full Width', 'ae-pro' ),
		];
	}

	public static function get_repeater_location_choices() {
		return [
			'post'   => __( 'Post', 'ae-pro' ),
			'option' => __( 'Option', 'ae-pro' ),
		];
	}

	public static function get_acf_field_type_choices() {
		return [
			'repeater'         => __( 'Repeater', 'ae-pro' ),
			'flexible_content' => __( 'Flexible Content', 'ae-pro' ),
		];
	}

	public static function get_shared_meta_keys() {
		return [
			'render_mode'       => 'ae_render_mode',
			'usage_area'        => 'ae_usage',
			'custom_usage_area' => 'ae_custom_usage_area',
			'rule_post_type'    => 'ae_rule_post_type',
			'preview_post'      => 'ae_preview_post_ID',
			'rule_taxonomy'     => 'ae_rule_taxonomy',
			'preview_term'      => 'ae_preview_term',
			'rule_post_archive' => 'ae_rule_post_type_archive',
			'apply_global'      => 'ae_apply_global',
			'hook_apply_on'     => 'ae_hook_apply_on',
			'hook_post_types'   => 'ae_hook_post_types',
			'hook_posts_selected' => 'ae_hook_posts_selected',
			'hook_posts_excluded' => 'ae_hook_posts_excluded',
			'hook_taxonomies'   => 'ae_hook_taxonomies',
			'hook_terms_selected' => 'ae_hook_terms_selected',
			'hook_terms_excluded' => 'ae_hook_terms_excluded',
			'page_layout'       => 'ae_elementor_template',
			'repeater_loc'      => 'ae_repeater_loc',
			'acf_field_type'    => 'ae_acf_field_type',
			'acf_repeater'      => 'ae_acf_repeater_name',
			'acf_flexible'      => 'ae_acf_flexible_name',
		];
	}

	public static function get_edit_render_mode_field_visibility() {
		return [
			'normal'                     => [ 'ae_usage' ],
			'post_template'              => [ 'ae_preview_post_ID', 'ae_apply_global', 'ae_rule_post_type', 'ae_elementor_template' ],
			'archive_template'           => [ 'ae_apply_global', 'ae_rule_taxonomy', 'ae_elementor_template', 'ae_preview_term' ],
			'post_type_archive_template' => [ 'ae_rule_post_type_archive', 'ae_apply_global', 'ae_elementor_template' ],
			'block_layout'               => [ 'ae_rule_post_type', 'ae_preview_post_ID', 'ae_elementor_template' ],
			'404'                        => [ 'ae_elementor_template' ],
			'search'                     => [ 'ae_elementor_template' ],
			'author_template'            => [ 'ae_apply_global', 'ae_elementor_template', 'ae_preview_author' ],
			'date_template'              => [ 'ae_elementor_template' ],
			'acf_repeater_layout'        => [ 'ae_rule_post_type', 'ae_elementor_template', 'ae_acf_field_type', 'ae_repeater_loc' ],
		];
	}

	public static function get_modal_render_mode_field_visibility() {
		return [
			'normal'                     => [ 'usage_area', 'custom_usage_area', 'apply_global', 'hook_apply_on', 'hook_post_types', 'hook_posts_selected', 'hook_posts_excluded', 'hook_taxonomies', 'hook_terms_selected', 'hook_terms_excluded' ],
			'post_template'              => [ 'rule_post_type', 'preview_post', 'apply_global', 'page_layout' ],
			'archive_template'           => [ 'apply_global', 'rule_taxonomy', 'preview_term', 'page_layout' ],
			'post_type_archive_template' => [ 'rule_post_type', 'apply_global', 'page_layout' ],
			'block_layout'               => [ 'rule_post_type', 'preview_post', 'page_layout' ],
			'404'                        => [ 'page_layout' ],
			'search'                     => [ 'page_layout' ],
			'author_template'            => [ 'apply_global', 'page_layout' ],
			'date_template'              => [ 'page_layout' ],
			'acf_repeater_layout'        => [ 'rule_post_type', 'preview_post', 'repeater_loc', 'acf_field_type', 'acf_repeater', 'acf_flexible', 'page_layout' ],
		];
	}

	public static function get_template_type_options() {
		$type_definitions = [
			'normal' => [
				'label'       => __( 'Normal', 'ae-pro' ),
				'description' => __( 'Use this for reusable sections and custom template placements.', 'ae-pro' ),
				'render_mode' => 'normal',
				'learn_more'   => 'https://qikly.ink/normal-template',
			],
			'single' => [
				'label'       => __( 'Single Post Template', 'ae-pro' ),
				'description' => __( 'Configure the settings for this single post template.', 'ae-pro' ),
				'render_mode' => 'post_template',
				'learn_more'   => 'https://qikly.ink/single-post',
			],
			'taxonomy_archive' => [
				'label'       => __( 'Taxonomy Archive Template', 'ae-pro' ),
				'description' => __( 'Use this on taxonomy archive pages.', 'ae-pro' ),
				'render_mode' => 'archive_template',
				'learn_more'   => 'https://qikly.ink/taxonomy-archive',
			],
			'post_type_archive' => [
				'label'       => __( 'Post Type Archive Template', 'ae-pro' ),
				'description' => __( 'Use this on post type archive pages.', 'ae-pro' ),
				'render_mode' => 'post_type_archive_template',
				'learn_more'   => 'https://qikly.ink/post-type-archive',
			],
			'block_layout' => [
				'label'       => __( 'Block Layout', 'ae-pro' ),
				'description' => __( 'Create block-style layouts for listing widgets.', 'ae-pro' ),
				'render_mode' => 'block_layout',
				'learn_more'   => 'https://qikly.ink/block',
			],
			'404' => [
				'label'       => __( '404 Template', 'ae-pro' ),
				'description' => __( 'Template for not found pages.', 'ae-pro' ),
				'render_mode' => '404',
				'learn_more'   => 'https://qikly.ink/404-template',
			],
			'search' => [
				'label'       => __( 'Search Template', 'ae-pro' ),
				'description' => __( 'Template for search results pages.', 'ae-pro' ),
				'render_mode' => 'search',
				'learn_more'   => 'https://qikly.ink/search-template',
			],
			'author' => [
				'label'       => __( 'Author Archive', 'ae-pro' ),
				'description' => __( 'Template for author archive pages.', 'ae-pro' ),
				'render_mode' => 'author_template',
				'learn_more'   => 'https://qikly.ink/author-archive',
			],
			'date' => [
				'label'       => __( 'Date Archive', 'ae-pro' ),
				'description' => __( 'Template for date archive pages.', 'ae-pro' ),
				'render_mode' => 'date_template',
				'learn_more'   => 'https://qikly.ink/date-archive',
			],
			'acf_blocks' => [
				'label'       => __( 'ACF Blocks (Repeater / Flexible)', 'ae-pro' ),
				'description' => __( 'Template for ACF repeater and flexible blocks.', 'ae-pro' ),
				'render_mode' => 'acf_repeater_layout',
				'learn_more'   => 'https://qikly.ink/repeater-template',

			],
		];

		$modal_field_map = self::get_modal_render_mode_field_visibility();
		$options         = [];

		foreach ( $type_definitions as $type_key => $definition ) {
			$render_mode = $definition['render_mode'];
			$field_keys  = isset( $modal_field_map[ $render_mode ] ) ? $modal_field_map[ $render_mode ] : [];
			$fields      = [];

			foreach ( $field_keys as $field_key ) {
				$fields[ $field_key ] = true;
			}

			$options[ $type_key ] = [
				'label'       => $definition['label'],
				'description' => $definition['description'],
				'render_mode' => $render_mode,
				'fields'      => $fields,
				'is_locked'   => false,
				'learn_more'   => isset( $definition['learn_more'] ) ? $definition['learn_more'] : '',
			];
		}

		if ( ! aepro_is_pro_active() && function_exists( 'aepro_get_locked_render_modes' ) ) {
			$locked_render_modes = (array) aepro_get_locked_render_modes();
			foreach ( $options as $option_key => $option ) {
				$options[ $option_key ]['is_locked'] = in_array( $option['render_mode'], $locked_render_modes, true );
			}
		}

		return $options;
	}

	public static function sanitize_post_type( $value, $default = 'post' ) {
		return self::sanitize_key_from_choices( $value, self::get_post_type_choices(), $default );
	}

	public static function sanitize_post_type_list( $values ) {
		return self::sanitize_key_array_from_choices( $values, self::get_post_type_choices() );
	}

	public static function sanitize_taxonomy( $value, $default = '' ) {
		return self::sanitize_key_from_choices( $value, self::get_taxonomy_choices(), $default );
	}

	public static function sanitize_taxonomy_list( $values ) {
		return self::sanitize_key_array_from_choices( $values, self::get_taxonomy_choices() );
	}

	public static function sanitize_usage_area( $value, $default = '' ) {
		return self::sanitize_key_from_choices( $value, self::get_usage_area_choices(), $default );
	}

	public static function sanitize_apply_on_values( $values ) {
		return self::sanitize_key_array_from_choices( $values, self::get_apply_on_choices() );
	}

	public static function sanitize_page_layout( $value, $default = '' ) {
		return self::sanitize_key_from_choices( $value, self::get_page_layout_choices(), $default );
	}

	public static function sanitize_repeater_location( $value, $default = 'post' ) {
		return self::sanitize_key_from_choices( $value, self::get_repeater_location_choices(), $default );
	}

	public static function sanitize_acf_field_type( $value, $default = 'repeater' ) {
		return self::sanitize_key_from_choices( $value, self::get_acf_field_type_choices(), $default );
	}

	private static function sanitize_key_from_choices( $value, $choices, $default ) {
		$sanitized = sanitize_key( wp_unslash( (string) $value ) );
		if ( isset( $choices[ $sanitized ] ) ) {
			return $sanitized;
		}

		return $default;
	}

	private static function sanitize_key_array_from_choices( $values, $choices ) {
		if ( ! is_array( $values ) ) {
			return [];
		}

		$sanitized_values = [];
		foreach ( $values as $value ) {
			$sanitized_key = sanitize_key( wp_unslash( (string) $value ) );
			if ( isset( $choices[ $sanitized_key ] ) ) {
				$sanitized_values[ $sanitized_key ] = $sanitized_key;
			}
		}

		return array_values( $sanitized_values );
	}

	private static function get_helper() {
		if ( Aepro::$_helper instanceof Helper ) {
			return Aepro::$_helper;
		}

		return new Helper();
	}
}

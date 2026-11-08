<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PostGrid;

use RadiusTheme\SB\Elementor\Helper\PostHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings class.
 *
 * @package RadiusTheme\SB
 */
class Controls {
	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content( $obj ) {
		return array_merge(
			self::preset( $obj ),
			PostHelpers::query( $obj ),
			PostHelpers::pagination( $obj ),
			PostHelpers::content_visibility( $obj ),
			PostHelpers::image( $obj ),
			PostHelpers::post_title( $obj ),
			PostHelpers::post_excerpt( $obj ),
			PostHelpers::links( $obj ),
			PostHelpers::meta_icon_settings( $obj ),
			PostHelpers::readmore_button( $obj ),
		);
	}

	/**
	 * Style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function style( $obj ) {
		return array_merge(
			PostHelpers::post_list_item_style( $obj ),
			PostHelpers::image_styles( $obj ),
			PostHelpers::title_style( $obj ),
			PostHelpers::content_style( $obj ),
			PostHelpers::meta_style( $obj ),
			PostHelpers::taxonomy_style( $obj ),
			PostHelpers::button_style( $obj ),
			PostHelpers::post_pagination_style_settings( $obj ),
		);
	}

	/**
	 * Preset section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function preset( $obj ) {
		$fields['sb_post_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']    = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']   = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_post_grid(),
			'default' => 'rtsb-post-grid-layout1',
		];
		$fields['cols']           = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '1',
			'mobile_default' => '1',
			'required'       => true,
			'render_type'    => 'template',
			'selectors'      => [
				$obj->selectors['columns']['cols'] => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
			],
			'separator'      => 'before',
		];
		$fields['rows']           = [
			'type'           => 'select2',
			'label'          => esc_html__( 'Number of Product Rows', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of slide rows. Slide Rows represents how many rows of slides will be displayed at once.', 'shopbuilder' ),
			'options'        => [
				1 => esc_html__( 'Layout Default (1 Row)', 'shopbuilder' ),
				2 => esc_html__( '2 Rows', 'shopbuilder' ),
				3 => esc_html__( '3 Rows', 'shopbuilder' ),
				4 => esc_html__( '4 Rows', 'shopbuilder' ),
				5 => esc_html__( '5 Rows', 'shopbuilder' ),
			],
			'label_block'    => true,
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
			'required'       => true,
			'condition'      => [ 'activate_slider_item' => 'yes' ],
		];

		$fields['cols_group']         = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Slides Per Group', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of slides to show per group. Slides Per Group indicates how many slides will be transitioned at a time.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'condition'      => [ 'activate_slider_item' => 'yes' ],
		];
		$fields['grid_gap']           = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Grid Gap / Spacing (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 30,
			],
			'description' => esc_html__( 'Please select the grid gap in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['grid_gap']['padding'] => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
				$obj->selectors['columns']['grid_gap']['margin'] => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
				$obj->selectors['columns']['grid_gap']['bottom'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['sb_post_preset_end'] = $obj->end_section();
		return $fields;
	}
}

<?php
/**
 * TableSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Helper\ControlHelper;


if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class TableSettings {

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function settings( $widget ) {
		return self::table_settings( $widget ) +
			   self::button_settings( $widget );
	}

	/**
	 * Table Widget Field
	 *
	 * @return array
	 */
	public static function table_settings( $widget ) {
		return [
			'table_section_start'        => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Table', 'shopbuilder' ),
			],
			'table_border'               => [
				'mode'      => 'group',
				'type'      => 'border',
				'label'     => esc_html__( 'Border', 'shopbuilder' ),
				'selector'  => $widget->selectors['table_border'],
				'separator' => 'before',
			],
			'table_heading_cell_padding' => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$widget->selectors['table_heading_cell_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'table_header_label'         => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Table Heading', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'table_header_typo'          => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['table_header_typo'],
			],
			'table_header_align'         => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$widget->selectors['table_header_align'] => 'text-align: {{VALUE}}!important;',
				],
			],
			'table_header_color'         => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_header_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'table_header_bg_color'      => [
				'label'     => esc_html__( 'Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_header_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'table_items_label'          => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Table Items', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'table_item_typo'            => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['table_item_typo'],
			],
			'table_item_align'           => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$widget->selectors['table_item_align'] => 'text-align: {{VALUE}}!important;',
				],
			],
			'table_item_color'           => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_item_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'table_item_bg_color'        => [
				'label'     => esc_html__( 'Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_item_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'table_heading_end'          => [
				'mode' => 'section_end',
			],
		];
	}

	/**
	 * Button Widget Field
	 *
	 * @return array
	 */
	public static function button_settings( $widget ) {
		return ButtonSettings::style_settings( $widget );
	}

}

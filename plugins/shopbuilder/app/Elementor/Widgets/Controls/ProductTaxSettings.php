<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductTaxSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'meta_content'                   => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Styles', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'show_label'                     => [
				'label'        => esc_html__( 'Label', 'shopbuilder' ),
				'type'         => 'switch',
				'label_on'     => esc_html__( 'Show', 'shopbuilder' ),
				'label_off'    => esc_html__( 'Hide', 'shopbuilder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'default',
			],
			'align'                          => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'selectors' => [
					$widget->selectors['align'] => 'text-align: {{VALUE}};',
				],
			],
			'meta_label_heading'             => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Label', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
				'condition'       => [
					'show_label' => 'yes',
				],
			],
			'label_typo'                     => [
				'mode'      => 'group',
				'type'      => 'typography',
				'selector'  => $widget->selectors['label_typo'],
				'condition' => [
					'show_label' => 'yes',
				],
			],
			'meta_label_color'               => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'condition' => [
					'show_label' => 'yes',
				],
				'selectors' => [
					$widget->selectors['meta_label_color'] => 'color: {{VALUE}};',
				],
			],
			'shopbuilder_meta_value_heading' => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Value', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'value_typo'                     => [
				'mode'     => 'group',
				'type'     => 'typography',
				'selector' => $widget->selectors['value_typo'],
			],
			'meta_value_color'               => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['meta_value_color'] => 'color: {{VALUE}};',
				],
			],
			'meta_value_hover_color'         => [
				'label'     => esc_html__( 'Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['meta_value_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'sec_general_end'                => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

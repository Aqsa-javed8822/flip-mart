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
class AddInfoSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget instance.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'general_section'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General Section', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'show_title'          => [
				'label'       => esc_html__( 'Show Title?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Title.', 'shopbuilder' ),
				'default'     => 'yes',
				'separator'   => 'default',
			],
			'general_section_end' => [
				'mode' => 'section_end',
			],
			'style_section'       => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Heading Style', 'shopbuilder' ),
				'tab'       => 'style',
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'heading_typography'  => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['heading_typography'],
			],
			'heading_color'       => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['heading_color'] => 'color: {{VALUE}}',
				],
			],
			'heading_margin'      => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '20',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					$widget->selectors['heading_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'style_section_end'   => [
				'mode' => 'section_end',
			],
			'content_section'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Content', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'content_typography'  => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['content_typography'],
			],
			'content_color'       => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['content_color'] => 'color: {{VALUE}}',
				],
			],
			'content_border'      => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => $widget->selectors['content_border'],
				'size_units'     => [ 'px' ],
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'unit'     => 'px',
							'isLinked' => true,
						],
					],
					'color'  => [
						'default' => '#e5e7eb',
					],
				],
			],
			'content_padding'     => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'default'    => [
					'top'      => '12',
					'right'    => '18',
					'bottom'   => '12',
					'left'     => '18',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					$widget->selectors['content_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'content_section_end' => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

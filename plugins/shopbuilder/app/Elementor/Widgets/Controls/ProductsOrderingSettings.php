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
class ProductsOrderingSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'section_style'              => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Style', 'shopbuilder' ),
			],
			'typo'                       => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['typo'],
			],
			'align'                      => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'default'   => 'right',
				'selectors' => [
					$widget->selectors['align'] => 'text-align: {{VALUE}};',
				],
			],
			'orderby_height'             => [
				'label'      => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 40,
				],
				'selectors'  => [
					$widget->selectors['orderby_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'orderby_width'              => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Width', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors'  => [
					$widget->selectors['orderby_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'ordering_padding'           => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['ordering_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'order_tabs_start'           => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'order_normal'               => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'orderby_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['orderby_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'text_color'                 => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['text_color'] => 'color: {{VALUE}};',
				],
			],
			'orderby_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $widget->selectors['orderby_border'],
				'size_units' => [ 'px' ],
			],
			'order_normal_end'           => [
				'mode' => 'tab_end',
			],
			'order_hover'                => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'orderby_bg_hover_color'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['orderby_bg_hover_color'] => 'background-color: {{VALUE}};',
				],
			],
			'text_hover_color'           => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['text_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'orderby_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['orderby_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'order_hover_end'            => [
				'mode' => 'tab_end',
			],
			'order_tabs_end'             => [
				'mode' => 'tabs_end',
			],
			'orderby_radius'             => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['orderby_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'section_style_end'          => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}


}

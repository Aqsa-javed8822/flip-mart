<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ButtonSettings {

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function style_settings( $widget ) {

		$fields = [
			'button_section_start'      => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Button', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'button_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['button_typography'],
			],
			'button_height'             => [
				'label'     => esc_html__( 'Height', 'shopbuilder' ),
				'type'      => 'slider',
				'mode'      => 'responsive',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					$widget->selectors['button_height'] => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			],

			'button_width'              => [
				'label'      => esc_html__( 'Width', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'mode'       => 'responsive',
				'type'       => 'slider',
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 900,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					$widget->selectors['button_width'] => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			],

			'button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			'button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'button_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$widget->selectors['button_text_color_normal'] => 'color: {{VALUE}}!important;',
				],
			],
			'button_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$widget->selectors['button_bg_color_normal'] => 'background-color: {{VALUE}} !important;',
				],
			],

			'button_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $widget->selectors['button_border'],
				'size_units' => [ 'px' ],
			],
			'button_normal_end'         => [
				'mode' => 'tab_end',
			],
			'button_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'button_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$widget->selectors['button_text_color_hover'] => 'color: {{VALUE}}!important;',
				],
			],
			'button_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$widget->selectors['button_bg_color_hover']  => 'background-color: {{VALUE}} !important;',
				],
			],
			'button_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$widget->selectors['button_border_hover_color']  => 'border-color: {{VALUE}}!important;',
				],
			],
			'button_hover_end'          => [
				'mode' => 'tab_end',
			],
			'button_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'button_border_radius'      => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'default'    => [
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px' ],
				'separator'  => 'before',
				'selectors'  => [
					$widget->selectors['button_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			],
			'button_padding'            => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator'  => 'before',
			],

			'button_margin'             => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['button_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'button_section_end'        => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

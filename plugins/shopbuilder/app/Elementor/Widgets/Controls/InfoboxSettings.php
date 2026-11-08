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
class InfoboxSettings {

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function style_settings( $widget ) {
		$fields = [
			'infobox_style'  => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Infobox style', 'shopbuilder' ),
			],
			'notice_infobox' => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Infobox', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],

			'infobox_typography'      => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['infobox_typography'],
			],
			'infobox_bg_color'        => [
				'label' => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'  => 'color',

				'selectors' => [
					$widget->selectors['infobox_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'infobox_text_color'      => [
				'label' => esc_html__( 'Color', 'shopbuilder' ),
				'type'  => 'color',

				'selectors' => [
					$widget->selectors['infobox_text_color'] => 'color: {{VALUE}};',
				],
			],
			'infobox_text_link_color' => [
				'label' => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'  => 'color',

				'selectors' => [
					$widget->selectors['infobox_text_link_color'] => 'color: {{VALUE}};',
				],
			],
			'infobox_border_color'    => [
				'label'          => esc_html__( 'Infobox Border', 'shopbuilder' ),
				'mode'           => 'group',
				'type'           => 'border',
				'fields_options' => [
					'border' => [
						'label' => esc_html__( 'Infobox Border', 'shopbuilder' ),
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'selector'       => $widget->selectors['infobox_border_color'],
			],

			'infobox_border_radius'  => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['infobox_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'infobox_padding'        => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['infobox_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'infobox_wrapper_border' => [
				'label'          => esc_html__( 'Infobox Wrapper Border', 'shopbuilder' ),
				'mode'           => 'group',
				'type'           => 'border',
				'fields_options' => [
					'border' => [
						'label' => esc_html__( 'Infobox Wrapper Border', 'shopbuilder' ),
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'selector'       => $widget->selectors['infobox_wrapper_border'],
			],

			'notice_icon_box'     => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Icon Box', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'infobox_icon_size'   => [
				'label'     => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					$widget->selectors['infobox_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$widget->selectors['infobox_icon_size']['svg']  => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'infobox_icon_margin' => [
				'label'      => esc_html__( 'Icon Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['infobox_icon_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'infobox_icon_color'  => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['infobox_icon_color'] => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_info_icon' => [ 'yes' ],
				],
			],

			'infobox_style_end' => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ArchiveViewModeSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget Elementor widget instance.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'mode_button_style'              => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Icon Settings', 'shopbuilder' ),
			],
			'view_mode'                      => [
				'label'     => esc_html__( 'Default Selected View', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'grid' => [
						'title' => esc_html__( 'Grid', 'shopbuilder' ),
						'icon'  => 'eicon-posts-grid',
					],
					'list' => [
						'title' => esc_html__( 'List', 'shopbuilder' ),
						'icon'  => 'eicon-post-list',
					],
				],
				'default'   => 'grid',
				'separator' => 'default',
			],
			'icon_size'                      => [
				'label'      => esc_html__( 'Icon Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$widget->selectors['icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'mode_button_align'              => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'selectors' => [
					$widget->selectors['mode_button_align'] => 'text-align: {{VALUE}};',
				],
			],
			'mode_button_height'             => [
				'label'      => esc_html__( 'Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 40,
				],
				'selectors'  => [
					$widget->selectors['mode_button_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'mode_button_width'              => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Width', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors'  => [
					$widget->selectors['mode_button_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'mode_button_gap'                => [
				'label'      => esc_html__( 'Gap (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 5,
				],
				'selectors'  => [
					$widget->selectors['mode_button_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],

			],
			'mode_button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'mode_button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'mode_button_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['mode_button_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'text_color'                     => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['text_color'] => 'color: {{VALUE}};',
				],
			],
			'mode_button_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $widget->selectors['mode_button_border'],
				'size_units' => [ 'px' ],
			],
			'mode_button_normal_end'         => [
				'mode' => 'tab_end',
			],
			'mode_button_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'mode_button_bg_hover_color'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['mode_button_bg_hover_color'] => 'background-color: {{VALUE}};',
				],
			],
			'text_hover_color'               => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['text_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'mode_button_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['mode_button_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'mode_button_hover_end'          => [
				'mode' => 'tab_end',
			],
			'mode_button_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'mode_button_radius'             => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['mode_button_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'mode_button_grid_icon'          => [
				'label'            => esc_html__( 'Grid View Icon', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'rtsb-icon rtsb-icon-grid',
					'library' => 'rtsb-fonts',
				],
			],
			'mode_button_list_icon'          => [
				'label'            => esc_html__( 'List View Icon', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'rtsb-icon rtsb-icon-list',
					'library' => 'rtsb-fonts',
				],
			],
			'mode_button_button_style_end'   => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

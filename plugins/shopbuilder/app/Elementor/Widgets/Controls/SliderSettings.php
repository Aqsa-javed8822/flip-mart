<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlSelectors;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class SliderSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget instance.
	 *
	 * @return array
	 */
	public static function slider_style( $widget ) {
		$selector = $widget->selectors;
		$fields   = [
			'slider_style_start'        => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Slider Style', 'shopbuilder' ),
				'condition' => [
					'slider_activate!' => '',
				],
			],
			'slider_arrows_style'       => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Arrows', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
				'condition'       => [
					'show_arrows!' => '',
				],
			],

			'slider_arrow_icon_size'    => [
				'label'      => esc_html__( 'Icon Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors'  => [
					$selector['slider_arrow_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$selector['slider_arrow_icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_arrows!' => '',
				],
			],
			'slider_arrow_size'         => [
				'label'      => esc_html__( 'Arrow Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors'  => [
					$selector['slider_arrow_size'] => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_arrows!' => '',
				],
			],

			'arrows_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'arrows_normal'             => [
				'mode'      => 'tab_start',
				'label'     => esc_html__( 'Normal', 'shopbuilder' ),
				'condition' => [
					'show_arrows!' => '',
				],
			],
			'arrows_color'              => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['arrows_color'] => 'color: {{VALUE}};',
				],
			],
			'arrows_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['arrows_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'arrows_border_color'       => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['arrows_border_color'] => 'border-color: {{VALUE}};',
				],
			],
			'arrows_normal_end'         => [
				'mode' => 'tab_end',
			],
			'arrows_hover'              => [
				'mode'      => 'tab_start',
				'label'     => esc_html__( 'Hover', 'shopbuilder' ),
				'condition' => [
					'show_arrows!' => '',
				],
			],
			'arrows_hover_color'        => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['arrows_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'arrows_hover_bg_color'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['arrows_hover_color'] => 'background-color: {{VALUE}};',
				],
			],
			'arrows_hover_border_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['arrows_hover_border_color'] => 'border-color: {{VALUE}};',
				],
			],
			'arrows_hover_end'          => [
				'mode' => 'tab_end',
			],
			'arrows_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'slider_dots_style'         => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Dot\'s', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
				'condition'       => [
					'show_arrows!' => '',
				],
			],
			'dots_gap'                  => [
				'label'      => esc_html__( 'Dot\'s Spacing (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					$selector['dots_gap'] => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);',
				],
				'condition'  => [
					'show_dots!' => '',
				],
			],
			'dots_size'                 => [
				'label'      => esc_html__( 'Dot\'s Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors'  => [
					$selector['dots_size'] => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_dots!' => '',
				],
			],
			'dot_color'                 => [
				'label'     => esc_html__( 'Dot\'s Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['dot_color'] => 'background: {{VALUE}};',
				],
			],
			'dot_active_color'          => [
				'label'     => esc_html__( 'Dot\'s Active Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['dot_active_color'] => 'background: {{VALUE}};',
				],
			],
			'slider_style_end'          => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function slider_controls() {
		$fields = [
			'slider_section_start' => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Slider', 'shopbuilder' ),
				'condition' => [
					'slider_activate!' => '',
				],
			],
			'space_between'        => [
				'label'     => esc_html__( 'Space between', 'shopbuilder' ),
				'type'      => 'number',
				'separator' => 'default',
				'default'   => 10,
				'min'       => 1,
				'max'       => 100,
			],
			'loop'                 => [
				'label'        => esc_html__( 'Loop', 'shopbuilder' ),
				'type'         => 'switch',
				'label_on'     => esc_html__( 'Yes', 'shopbuilder' ),
				'label_off'    => esc_html__( 'No', 'shopbuilder' ),
				'default'      => '',
				'return_value' => 'yes',
			],
			'speed'                => [
				'label'   => esc_html__( 'Slide Speed', 'shopbuilder' ),
				'type'    => 'number',
				'default' => 2000,
				'min'     => 0,
				'max'     => 10000,
			],
			'autoplay'             => [
				'label'        => esc_html__( 'Autoplay', 'shopbuilder' ),
				'type'         => 'switch',
				'label_on'     => esc_html__( 'Yes', 'shopbuilder' ),
				'label_off'    => esc_html__( 'No', 'shopbuilder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			],
			'autoplay_delay'       => [
				'label'     => esc_html__( 'Slide Delay', 'shopbuilder' ),
				'type'      => 'number',
				'default'   => 3000,
				'min'       => 0,
				'max'       => 10000,
				'condition' => [
					'autoplay' => 'yes',
				],
			],

			'pauseon_mouseenter'   => [
				'label'        => esc_html__( 'Pause On Mouse Enter', 'shopbuilder' ),
				'type'         => 'switch',
				'label_on'     => esc_html__( 'Yes', 'shopbuilder' ),
				'label_off'    => esc_html__( 'No', 'shopbuilder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => [
					'autoplay' => 'yes',
				],
			],
			'show_arrows'          => [
				'label'        => esc_html__( 'Show Arrows', 'shopbuilder' ),
				'type'         => 'switch',
				'label_off'    => esc_html__( 'Show', 'shopbuilder' ),
				'label_on'     => esc_html__( 'Hide', 'shopbuilder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			],
			'show_dots'            => [
				'label'        => esc_html__( 'Show Dots', 'shopbuilder' ),
				'type'         => 'switch',
				'label_off'    => esc_html__( 'Show', 'shopbuilder' ),
				'label_on'     => esc_html__( 'Hide', 'shopbuilder' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			],
			'left_arrow_icon'      => [
				'label'            => esc_html__( 'Left Arrow', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'show_arrows' => 'yes',
				],
			],
			'right_arrow_icon'     => [
				'label'            => esc_html__( 'Right Arrow', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'show_arrows' => 'yes',
				],
			],

			'slider_section_end'   => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class NoticeSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];
	/**
	 * Widget
	 *
	 * @var array
	 */
	private static $widget;
	/**
	 * Widget Field
	 *
	 * @param object $widget object.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = $widget->selectors;
		return self::notice_style() + self::button_style();
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function notice_style() {
		return [
			'notice_section'                 => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Notice', 'shopbuilder' ),
			],
			'notice_typo_note'               => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Typography', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'notice_typography'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['notice_typography'],
			],
			'notice_icon_size'               => [
				'label'     => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['notice_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}} !important;',
					self::$selectors['notice_icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'notice_color_note'              => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Colors', 'shopbuilder' )
				),
				'separator'       => 'before-short',
				'content_classes' => 'elementor-panel-heading-title',
			],
			'notice_color_tab'               => [
				'mode' => 'tabs_start',
			],
			'notice_common_color_tab_start'  => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Common', 'shopbuilder' ),
			],
			'notice_bg_color'                => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_text_color'              => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'notice_border_color'            => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_border_color'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'notice_icon_color'              => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_icon_color']['icon'] => 'color: {{VALUE}} !important;',
					self::$selectors['notice_icon_color']['svg'] => 'fill: {{VALUE}} !important;',
				],
			],
			'notice_icon_bg_color'           => [
				'label'     => esc_html__( 'Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_icon_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_common_color_tab_end'    => [
				'mode' => 'tab_end',
			],
			'notice_success_color_tab_start' => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Success', 'shopbuilder' ),
			],
			'notice_success_bg_color'        => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_success_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_success_text_color'      => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_success_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'notice_success_border_color'    => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_success_border_color'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'notice_success_icon_color'      => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_success_icon_color']['icon'] => 'color: {{VALUE}} !important;',
					self::$selectors['notice_success_icon_color']['svg'] => 'fill: {{VALUE}} !important;',
				],
			],
			'notice_success_icon_bg_color'   => [
				'label'     => esc_html__( 'Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_success_icon_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_success_color_tab_end'   => [
				'mode' => 'tab_end',
			],
			'notice_error_color_tab_start'   => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Error', 'shopbuilder' ),
			],
			'notice_error_bg_color'          => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['notice_error_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_error_text_color'        => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['notice_error_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'notice_error_border_color'      => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_error_border_color'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'notice_error_icon_color'        => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_error_icon_color']['icon'] => 'color: {{VALUE}} !important;',
					self::$selectors['notice_error_icon_color']['svg'] => 'fill: {{VALUE}} !important;',
				],
			],
			'notice_error_icon_bg_color'     => [
				'label'     => esc_html__( 'Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_error_icon_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_error_color_tab_end'     => [
				'mode' => 'tab_end',
			],
			'notice_info_color_tab_start'    => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Info', 'shopbuilder' ),
			],
			'notice_info_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_info_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_info_text_color'         => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_info_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'notice_info_border_color'       => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_info_border_color'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'notice_info_icon_color'         => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_info_icon_color']['icon'] => 'color: {{VALUE}} !important;',
					self::$selectors['notice_info_icon_color']['svg'] => 'fill: {{VALUE}} !important;',
				],
			],
			'notice_info_icon_bg_color'      => [
				'label'     => esc_html__( 'Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['notice_info_icon_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'notice_info_color_tab_end'      => [
				'mode' => 'tab_end',
			],
			'notice_color_tab_end'           => [
				'mode' => 'tabs_end',
			],
			'notice_spacing_note'            => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Spacings', 'shopbuilder' )
				),
				'separator'       => 'before-short',
				'content_classes' => 'elementor-panel-heading-title',
			],
			'notice_gap_multiple'            => [
				'label'       => esc_html__( 'Spacing for Multiple Notice', 'shopbuilder' ),
				'description' => esc_html__( 'This spacing will apply between multiple notices.', 'shopbuilder' ),
				'type'        => 'slider',
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					self::$selectors['notice_gap_multiple'] => 'display: flex; flex-direction: column; gap: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'notice_icon_gap'                => [
				'label'     => esc_html__( 'Icon Spacing', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['notice_icon_gap'] => 'gap: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'notice_padding'                 => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['notice_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'notice_border_radius'           => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['notice_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'notice_section_end'             => [
				'mode' => 'section_end',
			],
		];
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function button_style() {
		$fields = ButtonSettings::style_settings( self::$widget );

		unset( $fields['button_section_start']['tab'] );

		$extra_controls = [];

		$extra_controls['notice_button_alignment'] = [
			'type'      => 'choose',
			'label'     => esc_html__( 'Text Alignment', 'shopbuilder' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default'   => 'left',
			'toggle'    => true,
			'selectors' => [
				self::$selectors['notice_button_alignment'] => 'justify-content: {{VALUE}} !important;',
			],
		];

		return Fns::insert_controls( 'button_height', $fields, $extra_controls );
	}
}

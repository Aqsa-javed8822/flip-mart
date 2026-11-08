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
class CheckoutInfoBoxSettings {

	/**
	 * Widget Control Selectors
	 *
	 * @var object
	 */
	private static $widget = [];
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];

	/**
	 * Controls Settings
	 *
	 * @param object $widget widget object.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = self::$widget->selectors;
		return array_merge(
			self::general_settings(),
			InfoboxSettings::style_settings( $widget ),
			self::text_style_settings(),
			self::fields_settings(),
			ButtonSettings::style_settings( $widget )
		);
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function text_style_settings() {
		$fields                               = TextStyleSettings::widget_fields( self::$widget );
		$fields['section_style']['label']     = esc_html__( 'Description Style', 'shopbuilder' );
		$fields['section_style']['tab']       = 'style';
		$fields['section_style']['condition'] = [
			'show_description!' => '',
		];

		$extra_controls['text_margin'] = [
			'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => [
				self::$selectors['text_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
			],
		];

		$fields = Fns::insert_controls( 'section_style_end', $fields, $extra_controls );

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings() {
		$fields = [
			'section_general'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'show_info_icon'      => [
				'label'       => esc_html__( 'Show Custom Icon?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Custom Icon.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'info_icon'           => [
				'label'       => esc_html__( 'Icon', 'shopbuilder' ),
				'type'        => 'icons',
				'description' => esc_html__( 'Change Icons.', 'shopbuilder' ),
				'condition'   => [
					'show_info_icon' => 'yes',
				],
			],
			'show_description'    => [
				'label'       => esc_html__( 'Show Description?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Description.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'description_text'    => [
				'label'       => esc_html__( 'Description Text', 'shopbuilder' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'Write Description Text. Leave Empty for default text.', 'shopbuilder' ),
				'condition'   => [
					'show_description' => 'yes',
				],
			],
			'section_general_end' => [
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
	public static function fields_settings() {
		$fields = [
			'fields_style_start'              => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Form Area', 'shopbuilder' ),
			],
			'fields_height'                   => [
				'label'     => esc_html__( 'Fields Height', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					self::$selectors['fields_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'fields_tabs_start'               => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'fields_normal'                   => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'fields_border'                   => [
				'mode'           => 'group',
				'type'           => 'border',
				'fields_options' => [
					'border' => [
						'label' => esc_html__( 'Field Border', 'shopbuilder' ),
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'selector'       => self::$selectors['fields_border'],
				'size_units'     => [ 'px' ],
			],
			'form_fields_border_radius'       => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_fields_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_text_color'               => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['fields_text_color'] => 'color: {{VALUE}};',
				],
			],
			'fields_bg_color'                 => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['fields_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'fields_normal_end'               => [
				'mode' => 'tab_end',
			],
			'fields_hover'                    => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover & Focus', 'shopbuilder' ),
			],
			'fields_hover_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['fields_hover_border'],
				'size_units' => [ 'px' ],
			],
			'form_fields_border_radius_hover' => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_fields_border_radius_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_hover_text_color'         => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['fields_hover_text_color'] => 'color: {{VALUE}};',
				],
			],
			'fields_hover_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['fields_hover_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'fields_hover_end'                => [
				'mode' => 'tab_end',
			],
			'fields_tabs_end'                 => [
				'mode' => 'tabs_end',
			],

			'fields_padding'                  => [
				'label'      => esc_html__( 'Fields Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['fields_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_gap'                      => [
				'label'     => esc_html__( 'Fields Gap', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['fields_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'form_margin'                     => [
				'label'      => esc_html__( 'Form Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'form_area_wrapper_border'        => [
				'mode'           => 'group',
				'type'           => 'border',
				'fields_options' => [
					'border' => [
						'label' => esc_html__( 'Form Area Border', 'shopbuilder' ),
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'selector'       => self::$selectors['form_area_wrapper_border'],
				'size_units'     => [ 'px' ],
			],

			'form_area_padding'               => [
				'label'      => esc_html__( 'Form Area Padding (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_area_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'form_area_margin'                => [
				'label'      => esc_html__( 'Form Area Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_area_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'form_area_border_radius'         => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_area_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'fields_style_end'                => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

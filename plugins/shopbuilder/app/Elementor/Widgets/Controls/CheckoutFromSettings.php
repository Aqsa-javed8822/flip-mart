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
class CheckoutFromSettings {

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
		$fields          = [];
		if ( 'rtsb-shipping-form' !== $widget->rtsb_base ) {
			$fields = self::general_settings() + TitleSettings::title_settings( self::$widget );
		}
		$fields = $fields + self::fields_settings();

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings() {
		// The function will Overwrite.
		$fields                            = TitleSettings::general_settings();
		$fields['show_title']['selectors'] = [
			self::$selectors['show_title'] => 'display:block;',
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
			'fields_label_style_start'    => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Form Label', 'shopbuilder' ),
			],
			'fields_label_typo'           => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Label Typography', 'shopbuilder' ),
				'selector' => self::$selectors['fields_label_typo'],
			],
			'fields_label_color'          => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['fields_label_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'fields_label_reguired_color' => [
				'label'     => esc_html__( 'Label Required Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['fields_label_reguired_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'fields_label_margin'         => [
				'label'      => esc_html__( 'Label Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['fields_label_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_label_style_end'      => [
				'mode' => 'section_end',
			],
			'fields_style_start'          => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Form Fields', 'shopbuilder' ),
			],
			'primary_color'               => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Primary Settings', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'fields_primary_color'        => [
				'label'     => esc_html__( 'Fields Primary Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .rtsb-input-field, {{WRAPPER}} #ship-to-different-address' => '--rtsb-fields-primary-color: {{VALUE}}',
				],
			],

			'from_fields_settings'        => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'From Fields Settings', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],

			'fields_height'               => [
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
			'textarea_fields_height'      => [
				'label'     => esc_html__( 'Textarea Fields Height', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'selectors' => [
					self::$selectors['textarea_fields_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'fields_width_100'            => [
				'label'       => esc_html__( 'Full Width Fields?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to set all fields to a 100% width..', 'shopbuilder' ),
				'default'     => '',
			],

			'fields_tabs_start'           => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'fields_normal'               => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'fields_border'               => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['fields_border'],
				'size_units' => [ 'px' ],
			],
			'fields_border_radius'        => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['fields_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_text_color'           => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['fields_text_color'] => 'color: {{VALUE}};',
				],
			],
			'fields_bg_color'             => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['fields_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'fields_normal_end'           => [
				'mode' => 'tab_end',
			],
			'fields_hover'                => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover & Focus', 'shopbuilder' ),
			],

			'fields_hover_border'         => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['fields_hover_border'],
				'size_units' => [ 'px' ],
			],
			'fields_border_radius_hover'  => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['fields_border_radius_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'fields_hover_text_color'     => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['fields_hover_text_color'] => 'color: {{VALUE}};',
				],
			],
			'fields_hover_bg_color'       => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['fields_hover_bg_color'] => 'background-color: {{VALUE}};',
				],
			],

			'fields_hover_end'            => [
				'mode' => 'tab_end',
			],
			'fields_tabs_end'             => [
				'mode' => 'tabs_end',
			],

			'fields_padding'              => [
				'label'      => esc_html__( 'Fields Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['fields_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'form_row_margin'             => [
				'label'      => esc_html__( 'Form Row Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_row_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],

			'form_wrapper_margin'         => [
				'label'      => esc_html__( 'Form Wrapper Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'fields_style_end'            => [
				'mode' => 'section_end',
			],
		];

		if ( 'rtsb-shipping-form' === self::$widget->rtsb_base ) {

			$insert_array = [
				'form_heading_label' => [
					'type'            => 'html',
					'raw'             => sprintf(
						'<h3 class="rtsb-elementor-group-heading">%s</h3>',
						esc_html__( 'Form Heading', 'shopbuilder' )
					),
					'content_classes' => 'elementor-panel-heading-title',
					'separator'       => 'default',
				],
				'form_heading_typo'  => [
					'mode'     => 'group',
					'type'     => 'typography',
					'label'    => esc_html__( 'Form Heading Typography', 'shopbuilder' ),
					'selector' => self::$selectors['form_heading_typo'],
				],
				'form_heading_color' => [
					'label'     => esc_html__( 'Form Heading Color', 'shopbuilder' ),
					'type'      => 'color',
					'selectors' => [
						self::$selectors['form_heading_color'] => 'color: {{VALUE}} !important;',
					],
				],
				'form_heading_gap'   => [
					'label'     => esc_html__( 'Form Heading Gap', 'shopbuilder' ),
					'type'      => 'slider',
					'range'     => [
						'px' => [
							'min' => 10,
							'max' => 100,
						],
					],
					'selectors' => [
						self::$selectors['form_heading_gap'] => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
					],
				],
				'form_field_label'   => [
					'type'            => 'html',
					'raw'             => sprintf(
						'<h3 class="rtsb-elementor-group-heading">%s</h3>',
						esc_html__( 'Form Label', 'shopbuilder' )
					),
					'content_classes' => 'elementor-panel-heading-title',
					'separator'       => 'default',
				],
			];
			$fields       = Fns::insert_controls( 'fields_label_style_start', $fields, $insert_array, true );
		}

		return $fields;
	}
}

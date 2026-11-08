<?php
/**
 * Main ReviewsSettings class.
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
 * Product Review Settings class
 */
class ReviewsSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $widget;
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];
	/**
	 * Widget Field
	 *
	 * @param object $widget widget object.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = $widget->selectors;
		$fields          = self::heading_controls() +
		self::review_style() +
		ReviewsStarSettings::widget_fields( $widget ) +
		self::review_form() +
		self::form_button();
		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function form_button() {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
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
				'selector' => self::$selectors['button_typography'],
			],
			'submit_button_height'      => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Submit button height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 300,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors'  => [
					self::$selectors['submit_button_height'] => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			],
			'submit_button_alignment'   => [
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'default'   => 'left',
				'selectors' => [
					self::$selectors['submit_button_alignment']['text-align'] => 'text-align: {{VALUE}} !important;',
					self::$selectors['submit_button_alignment']['float'] => 'float: none;',
				],
			],
			'button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'button_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					self::$selectors['button_text_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'button_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['button_bg_color_normal'] => 'background-color: {{VALUE}};',
				],
			],
			'button_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['button_border'],
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
					self::$selectors['button_text_color_hover'] => 'color: {{VALUE}};',
				],
			],
			'button_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['button_bg_color_hover'] => 'background-color: {{VALUE}};',
				],
			],
			'button_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['button_border_hover_color'] => 'border-color: {{VALUE}};',
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
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['button_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'button_padding'            => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'button_margin'             => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['button_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'button_section_end'        => [
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
	public static function review_form() {
		$fields = [
			'form_section_start'              => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Review Form Heading', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'form_heading_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['form_heading_typography'],
			],
			'form_heading_color'              => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['form_heading_color'] => 'color: {{VALUE}};',
				],
			],
			'noform_heading_text_color'       => [
				'label'     => esc_html__( 'No Form Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['noform_heading_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'noform_heading_color'            => [
				'label'     => esc_html__( 'No Form Text Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['noform_heading_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'form_title_margin'               => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['form_title_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; padding: 0;',
				],
				'separator'  => 'before',
			],
			'formheading_section_end'         => [
				'mode' => 'section_end',
			],
			'review_form_section_start'       => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Review Form', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'review_label_heading'            => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Input Label', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'input_label_typography'          => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['input_label_typography'],
				'exclude'  => [ 'font_family', 'text_decoration', 'font_style' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			],
			'review_label_color'              => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_label_color'] => 'color: {{VALUE}} ',
				],
			],
			'review_label_required'           => [
				'label'     => esc_html__( 'Required Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_label_required'] => 'color: {{VALUE}}',
				],
			],
			'review_input_heading'            => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Form Input', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'label_input_text_typography'     => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['label_input_text_typography'],
			],
			'review_input_color'              => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_input_color'] => 'color: {{VALUE}};',
				],
			],
			'review_input_border_color'       => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_input_border_color'] => 'border-color: {{VALUE}};',
				],
			],
			'review_input_border_color_focus' => [
				'label'     => esc_html__( 'Focus Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_input_border_color_focus'] => 'border-color: {{VALUE}} !important;outline-color: {{VALUE}} !important;',
				],
			],
			'review_comment_field_height'     => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Comment field height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 300,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors'  => [
					self::$selectors['review_comment_field_height'] => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			],
			'review_form_rating_size'         => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Rating icon size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					self::$selectors['review_form_rating_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				],
			],
			'review_field_spacing'            => [
				'label'      => esc_html__( 'Field Spacing (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					self::$selectors['review_field_spacing']['margin-0']      => 'margin: 0;',
					self::$selectors['review_field_spacing']['margin-buttom']  => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
			],
			'review_input_border_radius'      => [
				'label'      => esc_html__( 'Inputs Border Radius (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors'  => [
					self::$selectors['review_input_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			],
			'review_input_padding'            => [
				'label'      => esc_html__( 'Inputs Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['review_input_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'review_form_section_end'         => [
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
	public static function heading_controls() {
		$fields = [
			'heading_section_start'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Review Heading', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'review_heading_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['review_heading_typography'],
			],
			'review_heading_color'      => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['review_heading_color'] => 'color: {{VALUE}};',
				],
			],
			'review_title_margin'       => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['review_title_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; padding: 0;',
				],
				'separator'  => 'before',
			],
			'heading_section_end'       => [
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
	public static function review_style() {
		$fields = [
			'review_style_start'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Review Style', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'review_meta_color'      => [
				'label'       => esc_html__( 'Meta Color', 'shopbuilder' ),
				'type'        => 'color',
				'separator'   => 'default',
				'description' => esc_html__( 'Date, Author', 'shopbuilder' ),
				'selectors'   => [
					self::$selectors['review_meta_color'] => 'color: {{VALUE}};',
				],
			],
			'description_color'      => [
				'label'     => esc_html__( 'Description Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['description_color'] => 'color: {{VALUE}};',
				],
			],
			'review_border'          => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['review_border'],
				'size_units' => [ 'px' ],
			],
			'review_meta_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Meta Typography', 'shopbuilder' ),
				'selector' => self::$selectors['review_meta_typography'],
			],
			'review_desc_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Description Typography', 'shopbuilder' ),
				'selector' => self::$selectors['review_desc_typography'],
				'exclude'  => [ 'font_family', 'text_decoration', 'font_style' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			],
			'review_padding'         => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['review_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'review_single_spacing'  => [
				'label'      => esc_html__( 'Single Review Spacing (px)', 'shopbuilder' ),
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
					self::$selectors['review_single_spacing'] => 'margin-bottom: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'review_style_end'       => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

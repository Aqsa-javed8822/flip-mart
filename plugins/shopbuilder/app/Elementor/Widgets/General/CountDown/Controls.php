<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CountDown;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings class.
 *
 * @package RadiusTheme\SB
 */
class Controls {
	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content( $obj ) {
		return array_merge(
			self::preset( $obj ),
			self::countdown_settings( $obj ),
			self::countdown_content_settings( $obj ),
		);
	}

	/**
	 * Style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function style( $obj ) {
		return array_merge(
			self::content_box_style( $obj ),
			self::digits_style( $obj ),
			self::labels_style( $obj ),
			self::countdown_circle_style( $obj ),
		);
	}

	/**
	 * Preset section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function preset( $obj ) {
		$fields['countdown_preset']     = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']          = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']         = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_countdown_layouts(),
			'default' => 'rtsb-countdown-layout1',
		];
		$fields['countdown_preset_end'] = $obj->end_section();
		return $fields;
	}


	/**
	 * Countdown section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function countdown_settings( $obj ) {
		$fields['countdown_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Timer Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['countdown_date_time']          = [
			'label'       => esc_html__( 'Countdown Date Time', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the countdown date time.', 'shopbuilder' ),
			'type'        => 'date_time',
		];
		$fields['countdown_settings_sec_end']   = $obj->end_section();

		return $fields;
	}

	/**
	 * Content Settings
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function countdown_content_settings( $obj ) {
		$fields['countdown_content_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Content Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['display_days']                         = [
			'label'       => esc_html__( 'Display Days', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show countdown days.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['countdown_days_label']                 = [
			'label'       => esc_html__( 'Label Days', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => esc_html__( 'Days', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'display_days' => 'yes',
			],
		];
		$fields['display_hours']                        = [
			'label'       => esc_html__( 'Display Hours', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show countdown hours.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
			'separator'   => 'before',
		];
		$fields['countdown_hours_label']                = [
			'label'       => esc_html__( 'Label Hours', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => esc_html__( 'Hours', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'display_hours' => 'yes',
			],
		];
		$fields['display_minutes']                      = [
			'label'       => esc_html__( 'Display Minutes', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show countdown minutes.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
			'separator'   => 'before',
		];
		$fields['countdown_minutes_label']              = [
			'label'       => esc_html__( 'Label Minutes', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => esc_html__( 'Minutes', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'display_minutes' => 'yes',
			],
		];
		$fields['display_seconds']                      = [
			'label'       => esc_html__( 'Display Seconds', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show countdown seconds.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
			'separator'   => 'before',
		];
		$fields['countdown_seconds_label']              = [
			'label'       => esc_html__( 'Label Seconds', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => esc_html__( 'Seconds', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'display_seconds' => 'yes',
			],
		];
		$fields['countdown_content_settings_sec_end']   = $obj->end_section();

		return $fields;
	}
	/**
	 * Countdown Circle Settings
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function countdown_circle_style( $obj ) {
		$condition                                = [
			'layout_style' => [ 'rtsb-countdown-layout5' ],
		];
		$fields['countdown_circle_style_section'] = $obj->start_section(
			esc_html__( 'Circle Style', 'shopbuilder' ),
			'style',
			[],
			$condition
		);
		$fields['countdown_circle_gap']           = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Circle Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['countdown_circle']['gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
		];
		$fields['countdown_circle_stroke']        = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Circle Stroke', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min' => 10,
					'max' => 100,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 10,
			],
			'render_type' => 'template',
			'selectors'   => [ $obj->selectors['countdown_circle']['stroke'] => 'stroke-width: {{SIZE}};' ],
		];
		$fields['down_track_color']               = [
			'type'      => 'color',
			'label'     => esc_html__( 'Down Track Color', 'shopbuilder' ),
			'selectors' => [ $obj->selectors['countdown_circle']['down_track_color'] => 'stroke: {{VALUE}};' ],
		];
		$fields['up_track_color']                 = [
			'type'      => 'color',
			'label'     => esc_html__( 'Up Track Color', 'shopbuilder' ),
			'selectors' => [ $obj->selectors['countdown_circle']['up_track_color'] => 'stroke: {{VALUE}};' ],
		];

		$fields['countdown_circle_style_section_end'] = $obj->end_section();

		return $fields;
	}


	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function content_box_style( $obj ) {
		$css_selectors = $obj->selectors['content_box_style'];
		$title         = esc_html__( 'Content Box Style', 'shopbuilder' );
		$condition     = [
			'layout_style!' => [ 'rtsb-countdown-layout5','rtsb-countdown-layout6' ],
		];
		$selectors     = [

			'box_shadow'    => $css_selectors['box_shadow'],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'bg_overlay'    => $css_selectors['bg_overlay'],
			'border'        => $css_selectors['border'],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'countdown_gap' => [ $css_selectors['countdown_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'circle_width'  => [ $css_selectors['circle_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'circle_height' => [ $css_selectors['circle_height'] => 'height: {{SIZE}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'countdown_content_box_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['countdown_content_box_style_color'],
			$fields['countdown_content_box_style_bg_color'],
			$fields['countdown_content_box_style_padding'],
			$fields['countdown_content_box_style_alignment'],
			$fields['countdown_content_box_style_typo_note'],
			$fields['rtsb_el_countdown_content_box_style_typography'],
			$fields['countdown_content_box_style_color_tabs'],
			$fields['countdown_content_box_style_color_tab'],
			$fields['countdown_content_box_style_color_tab_end'],
			$fields['countdown_content_box_style_hover_color_tab'],
			$fields['countdown_content_box_style_hover_color'],
			$fields['countdown_content_box_style_hover_bg_color'],
			$fields['countdown_content_box_style_hover_color_tab_end'],
			$fields['countdown_content_box_style_color_tabs_end'],
			$fields['countdown_content_box_style_border_hover_color'],
			$fields['countdown_content_box_style_margin']
		);
		$fields['countdown_content_box_style_border_note']['separator'] = 'default';
		$fields['countdown_content_box_style_color_note']['separator']  = 'default';
		$extra_controls['countdown_content_box_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'countdown_content_box_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['countdown_content_box_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['countdown_content_box_style_bg_overlay']  = [
			'label'          => esc_html__( 'Background Overlay', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['bg_overlay'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background Type', 'shopbuilder' ),
				],
			],
		];
		$extra_controls2['countdown_content_box_style_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields                                     = Fns::insert_controls( 'countdown_content_box_style_color_note', $fields, $extra_controls2, true );
		$extra_controls3['countdown_gap']           = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => $selectors['countdown_gap'],
		];
		$extra_controls3['countdown_circle_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Width', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 40,
					'max' => 600,
				],
			],
			'selectors'  => $selectors['circle_width'],
		];
		$extra_controls3['countdown_circle_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 40,
					'max' => 600,
				],
			],
			'selectors'  => $selectors['circle_height'],
		];
		$fields                                     = Fns::insert_controls( 'countdown_content_box_style_spacing_note', $fields, $extra_controls3, true );
		return $fields;
	}
	/**
	 * Digits style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function digits_style( $obj ) {
		$css_selectors = $obj->selectors['digits_style'];
		$title         = esc_html__( 'Digits Style', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'digits_gap'         => [ $css_selectors['digits_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'digits_wrapper_gap' => [ $css_selectors['digits_wrapper_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'digits_width'       => [ $css_selectors['digits_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'digits_height'      => [ $css_selectors['digits_height'] => 'height: {{SIZE}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'countdown_digits_style', $title, $obj, [], $selectors );

		unset(
			$fields['countdown_digits_style_alignment'],
			$fields['rtsb_el_countdown_digits_style_border'],
			$fields['countdown_digits_style_color_tabs'],
			$fields['countdown_digits_style_color_tab'],
			$fields['countdown_digits_style_color_tab_end'],
			$fields['countdown_digits_style_hover_color_tab'],
			$fields['countdown_digits_style_hover_color'],
			$fields['countdown_digits_style_hover_bg_color'],
			$fields['countdown_digits_style_hover_color_tab_end'],
			$fields['countdown_digits_style_color_tabs_end'],
			$fields['countdown_digits_style_border_hover_color']
		);
		$fields['countdown_digits_style_bg_color']['condition']    = [
			'layout_style' => [ 'rtsb-countdown-layout6' ],
		];
		$fields['countdown_digits_style_padding']['condition']     = [
			'layout_style' => [ 'rtsb-countdown-layout6' ],
		];
		$fields['countdown_digits_style_border_note']['condition'] = [
			'layout_style' => [ 'rtsb-countdown-layout6' ],
		];
		$extra_controls['countdown_digits_border_radius']          = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout6' ],
			],
		];
		$fields                                     = Fns::insert_controls( 'countdown_digits_style_border_note', $fields, $extra_controls, true );
		$extra_controls3['digits_gap']              = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => $selectors['digits_gap'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout6' ],
			],
		];
		$extra_controls3['digits_wrapper_gap']      = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Wrapper Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => $selectors['digits_wrapper_gap'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout6' ],
			],
		];
		$extra_controls3['countdown_digits_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Width', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 40,
					'max' => 600,
				],
			],
			'selectors'  => $selectors['digits_width'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout6' ],
			],
		];
		$extra_controls3['countdown_digits_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 40,
					'max' => 600,
				],
			],
			'selectors'  => $selectors['digits_height'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout6' ],
			],
		];
		$fields                                     = Fns::insert_controls( 'countdown_digits_style_spacing_note', $fields, $extra_controls3, true );
		return $fields;
	}
	/**
	 * Label style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function labels_style( $obj ) {
		$css_selectors = $obj->selectors['labels_style'];
		$title         = esc_html__( 'Labels Style', 'shopbuilder' );
		$selectors     = [
			'typography'    => $css_selectors['typography'],
			'color'         => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'      => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'margin'        => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'countdown_labels_style', $title, $obj, [], $selectors );
		$fields['countdown_labels_style_bg_color']['condition']    = [
			'layout_style' => [ 'rtsb-countdown-layout3' ],
		];
		$fields['countdown_labels_style_border_note']['condition'] = [
			'layout_style' => [ 'rtsb-countdown-layout3' ],
		];
		unset(
			$fields['countdown_labels_style_alignment'],
			$fields['rtsb_el_countdown_labels_style_border'],
			$fields['countdown_labels_style_color_tabs'],
			$fields['countdown_labels_style_color_tab'],
			$fields['countdown_labels_style_color_tab_end'],
			$fields['countdown_labels_style_hover_color_tab'],
			$fields['countdown_labels_style_hover_color'],
			$fields['countdown_labels_style_hover_bg_color'],
			$fields['countdown_labels_style_hover_color_tab_end'],
			$fields['countdown_labels_style_color_tabs_end'],
			$fields['countdown_labels_style_border_hover_color']
		);
		$extra_controls['countdown_text_border_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
			'condition'  => [
				'layout_style' => [ 'rtsb-countdown-layout3' ],
			],
		];
		$fields = Fns::insert_controls( 'countdown_labels_style_border_note', $fields, $extra_controls, true );
		return $fields;
	}
}

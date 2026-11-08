<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ProgressBar;

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
			self::progress_bar_settings( $obj ),
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
			self::progress_bar_style( $obj ),
			self::labels_style( $obj ),
			self::count_number_style( $obj ),
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
		$fields['progress_bar_preset']     = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']             = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']            = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_progress_bar_layouts(),
			'default' => 'rtsb-progress-bar-layout1',
		];
		$fields['progress_bar_preset_end'] = $obj->end_section();
		return $fields;
	}


	/**
	 * Countdown section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function progress_bar_settings( $obj ) {
		$fields['progress_bar_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Progress Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['display_progress_count']          = [
			'label'       => esc_html__( 'Progress Count', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show progress count.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['display_progress_stripe']         = [
			'label'       => esc_html__( 'Progress Stripe', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show progress bar stripe.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'condition'   => [
				'layout_style' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4' ],
			],
		];
		$fields['display_progress_label']          = [
			'label'       => esc_html__( 'Progress Label', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show progress label.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['progress_count']                  = [
			'label'       => esc_html__( 'Progress Count Number', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the count number.', 'shopbuilder' ),
			'type'        => 'number',
			'min'         => 0,
			'max'         => 100,
			'default'     => 80,
			'condition'   => [
				'display_progress_count' => 'yes',
			],
		];
		$fields['progress_label']                  = [
			'label'       => esc_html__( 'Progress Label', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => esc_html__( 'Progress Bar', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [
				'display_progress_label' => 'yes',
			],
		];
		$fields['progress_size']                   = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 600,
				],
			],
			'selectors'  => [ $obj->selectors['progress_bar']['progress_size'] => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};' ],
			'condition'  => [
				'layout_style!' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout3','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['half_circle_size']                = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 600,
				],
			],
			'selectors'  => [
				$obj->selectors['progress_bar']['half_circle_size']['full'] => 'width: {{SIZE}}{{UNIT}};height: calc({{SIZE}}{{UNIT}}/2)' ,
				$obj->selectors['progress_bar']['half_circle_size']['half'] => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}' ,
			],
			'condition'  => [
				'layout_style' => [ 'rtsb-progress-bar-layout3' ],
			],
		];
		$fields['progress_stroke']                 = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Stroke', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['progress_bar']['progress_stroke'] => 'border-width: {{SIZE}}{{UNIT}};' ],
			'condition'  => [
				'layout_style!' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['progress_height']                 = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Progress Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['progress_bar']['height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'condition'  => [
				'layout_style' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['progress_left_position']          = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Progress Position', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['progress_bar']['left_position'] => 'left: {{SIZE}}{{UNIT}};' ],
			'condition'  => [
				'layout_style' => [ 'rtsb-progress-bar-layout4' ],
			],
		];
		$fields['progress_bar_transition']         = [
			'label'       => esc_html__( 'Animation Duration', 'shopbuilder' ),
			'description' => esc_html__( 'This time calculate with milliseconds.', 'shopbuilder' ),
			'type'        => 'number',
			'default'     => '1500',
		];
		$fields['progress_bar_settings_sec_end']   = $obj->end_section();

		return $fields;
	}
	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function progress_bar_style( $obj ) {
		$fields['progress_bar_style_section']     = $obj->start_section(
			esc_html__( 'Progress Bar Style', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['bg_color']                       = [
			'type'      => 'background',
			'mode'      => 'group',
			'exclude'   => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
			'selector'  => $obj->selectors['progress_bar_style']['bg_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-progress-bar-layout2','rtsb-progress-bar-layout3' ],
			],
		];
		$fields['fill_color']                     = [
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'label'          => esc_html__( 'Fill Color', 'shopbuilder' ),
			'selector'       => $obj->selectors['progress_bar_style']['fill_color'] ,
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Fill Color', 'shopbuilder' ),
				],
			],
			'condition'      => [
				'layout_style' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['fill_border_color']              = [
			'type'      => 'color',
			'label'     => esc_html__( 'Fill Color', 'shopbuilder' ),
			'selectors' => [
				$obj->selectors['progress_bar_style']['fill_border_color'] => 'border-color: {{VALUE}}',
			],
			'condition' => [
				'layout_style' => [ 'rtsb-progress-bar-layout2', 'rtsb-progress-bar-layout3' ],
			],
		];
		$fields['stroke_color']                   = [
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'label'          => esc_html__( 'Stroke Color', 'shopbuilder' ),
			'selector'       => $obj->selectors['progress_bar_style']['stroke_color'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Stroke Color', 'shopbuilder' ),
				],
			],
			'condition'      => [
				'layout_style' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['stroke_fill_color']              = [
			'type'      => 'color',
			'label'     => esc_html__( 'Stroke Color', 'shopbuilder' ),
			'selectors' => [
				$obj->selectors['progress_bar_style']['stroke_fill_color'] => 'border-color: {{VALUE}}',
			],
			'condition' => [
				'layout_style' => [ 'rtsb-progress-bar-layout2', 'rtsb-progress-bar-layout3' ],
			],
		];
		$fields['progress_bar_border_radius']     = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => [
				$obj->selectors['progress_bar_style']['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'layout_style' => [ 'rtsb-progress-bar-layout1','rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields['progress_bar_border_padding']    = [
			'label'              => esc_html__( 'Padding', 'shopbuilder' ),
			'type'               => 'dimensions',
			'mode'               => 'responsive',
			'size_units'         => [ 'px' ],
			'allowed_dimensions' => [ 'top', 'bottom' ],
			'selectors'          => [
				$obj->selectors['progress_bar_style']['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'          => [
				'layout_style' => [ 'rtsb-progress-bar-layout4' ],
			],
		];
		$fields['progress_bar_style_section_end'] = $obj->end_section();
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
		$condition     = [
			'display_progress_label' => 'yes',
		];
		$css_selectors = $obj->selectors['labels_style'];
		$title         = esc_html__( 'Labels Style', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'progress_bar_labels_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['progress_bar_labels_style_alignment'],
			$fields['rtsb_el_progress_bar_labels_style_border'],
			$fields['progress_bar_labels_style_color_tabs'],
			$fields['progress_bar_labels_style_color_tab'],
			$fields['progress_bar_labels_style_color_tab_end'],
			$fields['progress_bar_labels_style_border_note'],
			$fields['progress_bar_labels_style_hover_color_tab'],
			$fields['progress_bar_labels_style_hover_color'],
			$fields['progress_bar_labels_style_bg_color'],
			$fields['progress_bar_labels_style_hover_bg_color'],
			$fields['progress_bar_labels_style_hover_color_tab_end'],
			$fields['progress_bar_labels_style_color_tabs_end'],
			$fields['progress_bar_labels_style_border_hover_color'],
			$fields['progress_bar_labels_style_padding']
		);

		return $fields;
	}
	/**
	 * Count style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function count_number_style( $obj ) {
		$condition     = [
			'display_progress_count' => 'yes',
		];
		$css_selectors = $obj->selectors['count_number_style'];
		$title         = esc_html__( 'Count Number Style', 'shopbuilder' );
		$selectors     = [
			'typography'              => $css_selectors['typography'],
			'color'                   => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'progress_count_bg_color' => [
				$css_selectors['progress_count_bg_color']['bg_color']     => 'background-color: {{VALUE}};',
				$css_selectors['progress_count_bg_color']['border_color'] => 'border-top-color: {{VALUE}};',
			],
			'margin'                  => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'                 => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'progress_bar_count_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['progress_bar_count_style_alignment'],
			$fields['rtsb_el_progress_bar_count_style_border'],
			$fields['progress_bar_count_style_color_tabs'],
			$fields['progress_bar_count_style_color_tab'],
			$fields['progress_bar_count_style_color_tab_end'],
			$fields['progress_bar_count_style_border_note'],
			$fields['progress_bar_count_style_bg_color'],
			$fields['progress_bar_count_style_hover_color_tab'],
			$fields['progress_bar_count_style_hover_color'],
			$fields['progress_bar_count_style_hover_bg_color'],
			$fields['progress_bar_count_style_hover_color_tab_end'],
			$fields['progress_bar_count_style_color_tabs_end'],
			$fields['progress_bar_count_style_border_hover_color'],
		);
		$fields['progress_bar_count_style_padding']['condition'] = [
			'layout_style' => [ 'rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
		];
		$extra_fields['progress_count_bg_color']                 = [
			'type'      => 'color',
			'label'     => esc_html__( 'Background', 'shopbuilder' ),
			'selectors' => $selectors['progress_count_bg_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-progress-bar-layout4','rtsb-progress-bar-layout5' ],
			],
		];
		$fields = Fns::insert_controls( 'progress_bar_count_style_color', $fields, $extra_fields, true );
		return $fields;
	}
}

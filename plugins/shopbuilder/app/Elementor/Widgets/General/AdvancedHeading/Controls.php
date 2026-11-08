<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\AdvancedHeading;

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
			self::heading( $obj ),
			self::sub_heading( $obj ),
			self::description( $obj ),
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
			self::heading_style( $obj ),
			self::separator_style( $obj ),
			self::sub_heading_style( $obj ),
			self::description_style( $obj ),
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
		$fields['ah_preset']   = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note'] = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );

		$fields['layout_style'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_advanced_title_layouts(),
			'default' => 'rtsb-advanced-heading-layout1',
		];

		$fields['ah_element_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => ControlHelper::alignment(),
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};' ],
		];

		$fields['ah_preset_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Heading section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function heading( $obj ) {
		$fields['ah_heading'] = $obj->start_section(
			esc_html__( 'Heading', 'shopbuilder' ),
			'content'
		);

		$fields['heading_title_html_tag'] = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];

		$fields['heading_title_text'] = [
			'label'       => esc_html__( 'Heading Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the heading text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'default'     => esc_html__( 'ShopBuilder for Easy Store Customization', 'shopbuilder' ),
			'label_block' => true,
		];

		$fields['display_separator_note'] = $obj->el_heading(
			esc_html__( 'Separator', 'shopbuilder' ),
			'before',
		);

		$fields['display_separator'] = [
			'label'       => esc_html__( 'Show Separator?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show separator.', 'shopbuilder' ),
			'type'        => 'switch',
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['rtsb_separator_position'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Separator Position', 'shopbuilder' ),
			'default'   => 'bottom',
			'options'   => [
				'top'    => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'toggle'    => true,
			'condition' => [
				'display_separator' => 'yes',
			],
		];

		$fields['ah_heading_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Sub-Heading section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sub_heading( $obj ) {
		$condition = [
			'display_subheading' => 'yes',
		];

		$fields['ah_sub_heading'] = $obj->start_section(
			esc_html__( 'Sub-Heading', 'shopbuilder' ),
			'content'
		);

		$fields['display_subheading'] = [
			'label'       => esc_html__( 'Show Sub-Heading?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show sub-heading.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];

		$fields['subheading_html_tag'] = [
			'label'       => esc_html__( 'Sub-Heading Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the sub-heading tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'span',
			'label_block' => true,
			'condition'   => $condition,
		];

		$fields['subheading_text'] = [
			'label'       => esc_html__( 'Sub Heading Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the sub-heading text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'default'     => esc_html__( 'Transform Ideas into Sales', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => $condition,
		];

		$fields['rtsb_subheading_position'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Sub Heading Position', 'shopbuilder' ),
			'options'   => [
				'top'    => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-bottom',
				],

			],
			'toggle'    => true,
			'default'   => 'top',
			'condition' => $condition,
		];

		$fields['subheading_bars_note'] = $obj->el_heading(
			esc_html__( 'Sub-Heading Bars', 'shopbuilder' ),
			'before',
			[],
			$condition
		);

		$fields['display_subheading_left_bar'] = [
			'label'       => esc_html__( 'Show Left Bar?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show left bar.', 'shopbuilder' ),
			'type'        => 'switch',
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => $condition,
		];

		$fields['display_subheading_right_bar'] = [
			'label'       => esc_html__( 'Show Right Bar?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show right bar.', 'shopbuilder' ),
			'type'        => 'switch',
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => $condition,
		];

		$fields['ah_sub_heading_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Description section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function description( $obj ) {
		$fields['ah_description'] = $obj->start_section(
			esc_html__( 'Description', 'shopbuilder' ),
			'content'
		);

		$fields['display_description'] = [
			'label'       => esc_html__( 'Show Description?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show description.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'type'        => 'switch',
		];

		$fields['description_text'] = [
			'label'       => esc_html__( 'Description Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the description text.', 'shopbuilder' ),
			'type'        => 'wysiwyg',
			'label_block' => true,
			'default'     => esc_html__( 'ShopBuilder is a versatile plugin designed for Elementor Page Builder. This plugin helps you design and customize your online store easily, letting you create attractive product pages and improve the shopping experience for your customers.', 'shopbuilder' ),
			'condition'   => [
				'display_description' => 'yes',
			],
		];

		$fields['ah_description_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Heading style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function heading_style( $obj ) {
		$css_selectors = $obj->selectors['heading_style'];
		$title         = esc_html__( 'Heading Style', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'alignment'  => [ $css_selectors['alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};' ],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'   => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'ah_heading_style', $title, $obj, [], $selectors );

		unset(
			$fields['ah_heading_style_color_tabs'],
			$fields['ah_heading_style_color_tab'],
			$fields['ah_heading_style_color_tab_end'],
			$fields['ah_heading_style_hover_color_tab'],
			$fields['ah_heading_style_hover_color'],
			$fields['ah_heading_style_hover_bg_color'],
			$fields['ah_heading_style_hover_color_tab_end'],
			$fields['ah_heading_style_color_tabs_end'],
			$fields['ah_heading_style_border_hover_color']
		);

		return $fields;
	}

	/**
	 * Separator style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function separator_style( $obj ) {
		$css_selectors = $obj->selectors['separator_style'];
		$title         = esc_html__( 'Separator Style', 'shopbuilder' );
		$condition     = [ 'display_separator' => 'yes' ];
		$selectors     = [
			'width'  => [ $css_selectors['width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'height' => [ $css_selectors['height'] => 'border-width: {{SIZE}}{{UNIT}};' ],
			'color'  => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin' => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'ah_separator_style', $title, $obj, $condition, $selectors );

		$fields['ah_separator_style_typo_note']['raw'] = '<h3 class="rtsb-elementor-group-heading">' . esc_html__( 'Dimension', 'shopbuilder' ) . '</h3>';

		unset(
			$fields['rtsb_el_ah_separator_style_typography'],
			$fields['ah_separator_style_alignment'],
			$fields['ah_separator_style_color_tabs'],
			$fields['ah_separator_style_color_tab'],
			$fields['ah_separator_style_color_tab_end'],
			$fields['ah_separator_style_hover_color_tab'],
			$fields['ah_separator_style_bg_color'],
			$fields['ah_separator_style_hover_color'],
			$fields['ah_separator_style_hover_bg_color'],
			$fields['ah_separator_style_hover_color_tab_end'],
			$fields['ah_separator_style_color_tabs_end'],
			$fields['ah_separator_style_border_hover_color'],
			$fields['ah_separator_style_border_note'],
			$fields['rtsb_el_ah_separator_style_border'],
			$fields['ah_separator_style_padding']
		);

		$extra_controls['ah_separator_width'] = [
			'label'      => esc_html__( 'Width', 'shopbuilder' ),
			'type'       => 'slider',
			'mode'       => 'responsive',
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'selectors'  => $selectors['width'],
		];

		$extra_controls['ah_separator_height'] = [
			'label'      => esc_html__( 'Height', 'shopbuilder' ),
			'type'       => 'slider',
			'mode'       => 'responsive',
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'selectors'  => $selectors['height'],
		];

		return Fns::insert_controls( 'ah_separator_style_typo_note', $fields, $extra_controls, true );
	}

	/**
	 * Sub-Heading style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sub_heading_style( $obj ) {
		$css_selectors = $obj->selectors['sub_heading_style'];
		$title         = esc_html__( 'Sub-Heading Style', 'shopbuilder' );
		$condition     = [ 'display_subheading' => 'yes' ];
		$bar_condition = [
			'relation' => 'and',
			'terms'    => [
				[
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'display_subheading_left_bar',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_subheading_right_bar',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			],
		];
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'alignment'  => [ $css_selectors['alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};' ],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'   => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'bar_width'  => [ $css_selectors['bar_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'bar_height' => [ $css_selectors['bar_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'bar_gap'    => [ $css_selectors['bar_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'bar_color'  => [ $css_selectors['bar_color'] => 'background-color: {{VALUE}};' ],
			'bar_radius' => [ $css_selectors['bar_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'ah_sub_heading_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['ah_sub_heading_style_color_tabs'],
			$fields['ah_sub_heading_style_color_tab'],
			$fields['ah_sub_heading_style_color_tab_end'],
			$fields['ah_sub_heading_style_hover_color_tab'],
			$fields['ah_sub_heading_style_hover_color'],
			$fields['ah_sub_heading_style_hover_bg_color'],
			$fields['ah_sub_heading_style_hover_color_tab_end'],
			$fields['ah_sub_heading_style_color_tabs_end'],
			$fields['ah_sub_heading_style_border_hover_color']
		);

		$extra_controls['ah_sub_heading_bar_note'] = $obj->el_heading(
			esc_html__( 'Sub-Heading Bars', 'shopbuilder' ),
			'before',
			$bar_condition
		);

		$extra_controls['ah_sub_heading_bar_width'] = [
			'label'      => esc_html__( 'Width', 'shopbuilder' ),
			'type'       => 'slider',
			'mode'       => 'responsive',
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
			],
			'conditions' => $bar_condition,
			'selectors'  => $selectors['bar_width'],
		];

		$extra_controls['ah_sub_heading_bar_height'] = [
			'label'      => esc_html__( 'Height', 'shopbuilder' ),
			'type'       => 'slider',
			'mode'       => 'responsive',
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
			],
			'conditions' => $bar_condition,
			'selectors'  => $selectors['bar_height'],
		];

		$extra_controls['ah_sub_heading_bar_gap'] = [
			'label'      => esc_html__( 'Gap', 'shopbuilder' ),
			'type'       => 'slider',
			'mode'       => 'responsive',
			'conditions' => $bar_condition,
			'selectors'  => $selectors['bar_gap'],
		];

		$extra_controls['ah_sub_heading_bar_color'] = [
			'label'      => esc_html__( 'Color', 'shopbuilder' ),
			'type'       => 'color',
			'conditions' => $bar_condition,
			'selectors'  => $selectors['bar_color'],
		];

		$extra_controls['sub_heading_bar_radius'] = [
			'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
			'type'       => 'dimensions',
			'size_units' => [ 'px' ],
			'conditions' => $bar_condition,
			'selectors'  => $selectors['bar_radius'],
		];

		return Fns::insert_controls( 'ah_sub_heading_style_margin', $fields, $extra_controls, true );
	}

	/**
	 * Description style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function description_style( $obj ) {
		$css_selectors = $obj->selectors['description_style'];
		$title         = esc_html__( 'Description Style', 'shopbuilder' );
		$condition     = [ 'display_description' => 'yes' ];
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'alignment'  => [ $css_selectors['alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};' ],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'   => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'ah_description_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['ah_description_style_color_tabs'],
			$fields['ah_description_style_color_tab'],
			$fields['ah_description_style_color_tab_end'],
			$fields['ah_description_style_hover_color_tab'],
			$fields['ah_description_style_hover_color'],
			$fields['ah_description_style_hover_bg_color'],
			$fields['ah_description_style_hover_color_tab_end'],
			$fields['ah_description_style_color_tabs_end'],
			$fields['ah_description_style_border_hover_color']
		);

		return $fields;
	}
}

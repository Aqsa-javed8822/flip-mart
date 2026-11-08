<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Counter;

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
			self::counter_count( $obj ),
			self::counter_icon( $obj ),
			self::counter_title( $obj ),
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
			self::icon_box_style( $obj ),
			self::counter_count_style( $obj ),
			self::title_style( $obj ),
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
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['counter_preset']            = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']               = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']              = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_counter_layouts(),
			'default' => 'rtsb-counter-layout1',
		];
		$fields['counter_element_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => $alignment_options,
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};justify-content: {{VALUE}};' ],
		];
		$fields['counter_preset_end']        = $obj->end_section();
		return $fields;
	}

	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function counter_title( $obj ) {
		$fields['counter_title_sec_start'] = $obj->start_section(
			esc_html__( 'Title', 'shopbuilder' ),
			'content'
		);
		$fields['counter_title_html_tag']  = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['counter_title']           = [
			'label'       => esc_html__( 'Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter title text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'label_block' => true,
			'default'     => __( 'Happy Customers', 'shopbuilder' ),
		];

		$fields['counter_title_sec_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Count section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function counter_count( $obj ) {
		$fields['counter_count_sec_start'] = $obj->start_section(
			esc_html__( 'Count', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['counter_count']           = [
			'label'       => esc_html__( 'Count Number', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the count number.', 'shopbuilder' ),
			'type'        => 'number',
			'default'     => '1000',
		];
		$fields['counter_transition']      = [
			'label'       => esc_html__( 'Animation Duration', 'shopbuilder' ),
			'description' => esc_html__( 'This time calculate with milliseconds.', 'shopbuilder' ),
			'type'        => 'number',
			'default'     => '2000',
		];
		$fields['display_prefix']          = [
			'label'       => esc_html__( 'Prefix', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show prefix.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['count_prefix']            = [
			'label'       => esc_html__( 'Number Prefix', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => '+',
			'label_block' => true,
			'condition'   => [
				'display_prefix' => 'yes',
			],
		];
		$fields['display_suffix']          = [
			'label'       => esc_html__( 'Suffix', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show suffix.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['count_suffix']            = [
			'label'       => esc_html__( 'Number Suffix', 'shopbuilder' ),
			'type'        => 'text',
			'default'     => '+',
			'label_block' => true,
			'condition'   => [
				'display_suffix' => 'yes',
			],
		];
		$fields['counter_count_sec_end']   = $obj->end_section();

		return $fields;
	}

	/**
	 * Content Icon
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function counter_icon( $obj ) {
		$fields['counter_content_icon_sec_start'] = $obj->start_section(
			esc_html__( 'Icon', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['counter_icon_type']              = [
			'label'   => __( 'Icon Type', 'shopbuilder' ),
			'type'    => 'select',
			'default' => 'icon',
			'options' => [
				'icon'  => __( 'Icon', 'shopbuilder' ),
				'image' => __( 'Image', 'shopbuilder' ),
			],
		];
		$fields['counter_icon']                   = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'far fa-smile',
				'library' => 'fa-solid',
			],
			'condition' => [
				'counter_icon_type' => [ 'icon' ],
			],
		];
		$fields['counter_image']                  = [
			'type'      => 'media',
			'label'     => esc_html__( 'Upload Image', 'shopbuilder' ),
			'default'   => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
			'condition' => [
				'counter_icon_type' => [ 'image' ],
			],
		];
		$fields['counter_image_size']             = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
			'condition'       => [
				'counter_icon_type' => [ 'image' ],
			],
		];
		$fields['counter_img_dimension']          = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'show_label'  => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'conditions'  => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'counter_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
					[
						'name'     => 'counter_icon_type',
						'operator' => '==',
						'value'    => 'image',
					],
				],
			],
		];
		$fields['counter_img_crop']               = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'counter_icon_type'  => [ 'image' ],
				'counter_image_size' => [ 'rtsb_custom' ],
			],
		];

		$fields['counter-img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'counter_icon_type'  => [ 'image' ],
				'counter_image_size' => [ 'rtsb_custom' ],
			],

		];
		$fields['counter_icon_position'] = [
			'mode'            => 'responsive',
			'type'            => 'choose',
			'separator'       => 'before',
			'label'           => esc_html__( 'Icon Position', 'shopbuilder' ),
			'options'         => [
				'left'   => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'    => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-bottom',
				],

			],
			'render_template' => 'true',
			'toggle'          => true,
		];
		$fields['counter_icon_vertical_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'separator' => 'before',
			'label'     => esc_html__( 'Icon Vertical Alignment', 'shopbuilder' ),
			'options'   => [
				'start'  => [
					'title' => esc_html__( 'Start', 'shopbuilder' ),
					'icon'  => 'eicon-justify-start-v',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-justify-center-v',
				],
				'end'    => [
					'title' => esc_html__( 'End', 'shopbuilder' ),
					'icon'  => 'eicon-justify-end-v',
				],

			],
			'selectors' => [ $obj->selectors['layout']['vertical_alignment'] => 'align-items: {{VALUE}};' ],
			'toggle'    => true,
			'condition' => [
				'counter_icon_position!' => [ 'top','bottom' ],
			],
		];
		$fields['counter_content_sec_icon_end'] = $obj->end_section();
		return $fields;
	}


	/**
	 * Title style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function title_style( $obj ) {
		$css_selectors = $obj->selectors['title_style'];
		$title         = esc_html__( 'Title Style', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'counter_title_style', $title, $obj, [], $selectors );

		unset(
			$fields['counter_title_style_alignment'],
			$fields['counter_title_style_color_tabs'],
			$fields['counter_title_style_color_tab'],
			$fields['counter_title_style_bg_color'],
			$fields['counter_title_style_color_tab_end'],
			$fields['counter_title_style_hover_color_tab'],
			$fields['counter_title_style_hover_color'],
			$fields['counter_title_style_hover_bg_color'],
			$fields['counter_title_style_hover_color_tab_end'],
			$fields['counter_title_style_color_tabs_end'],
			$fields['counter_title_style_border_hover_color']
		);
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
		$selectors     = [
			'box_shadow'         => $css_selectors['box_shadow'],
			'gradient_bg'        => $css_selectors['gradient_bg'],
			'hover_gradient_bg'  => $css_selectors['hover_gradient_bg'],
			'hover_bg_overlay'   => $css_selectors['hover_bg_overlay'],
			'hover_box_shadow'   => $css_selectors['hover_box_shadow'],
			'bg_overlay'         => $css_selectors['bg_overlay'],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'counter_content_box_style', $title, $obj, [], $selectors );

		unset(
			$fields['counter_content_box_style_color'],
			$fields['counter_content_box_style_bg_color'],
			$fields['counter_content_box_style_alignment'],
			$fields['counter_content_box_style_typo_note'],
			$fields['rtsb_el_counter_content_box_style_typography'],
			$fields['counter_content_box_style_hover_color'],
			$fields['counter_content_box_style_hover_bg_color'],
			$fields['counter_content_box_style_hover_color_tab_end'],
			$fields['counter_content_box_style_margin']
		);
		$fields['counter_content_box_style_border_note']['separator'] = 'default';
		$fields['counter_content_box_style_color_note']['separator']  = 'default';
		$extra_controls['counter_content_box_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'counter_content_box_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['counter_content_box_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['counter_content_box_style_bg_overlay']  = [
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
		$extra_controls2['counter_content_box_style_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'counter_content_box_style_color_tab', $fields, $extra_controls2, true );
		$extra_controls3['counter_content_box_style_hover_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['hover_gradient_bg'],
		];
		$extra_controls3['counter_content_box_style_hover_bg_overlay']  = [
			'label'          => esc_html__( 'Background Overlay', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['hover_bg_overlay'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background Type', 'shopbuilder' ),
				],
			],
		];
		$extra_controls3['counter_content_box_style_hover_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['hover_box_shadow'],
		];
		$fields = Fns::insert_controls( 'counter_content_box_style_hover_color_tab', $fields, $extra_controls3, true );

		return $fields;
	}

	/**
	 * Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function icon_box_style( $obj ) {
		$css_selectors = $obj->selectors['counter_icon_style'];
		$title         = esc_html__( 'Icon Style', 'shopbuilder' );
		$selectors     = [
			'icon_size'     => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'box_shadow'    => $css_selectors['box_shadow'],
			'icon_width'    => [ $css_selectors['icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'icon_height'   => [ $css_selectors['icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'         => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'border'        => $css_selectors['border'],
			'margin'        => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'counter_icon_style', $title, $obj, [], $selectors );
		unset(
			$fields['counter_icon_style_color_tabs'],
			$fields['counter_icon_style_color_tab'],
			$fields['counter_icon_style_typo_note'],
			$fields['rtsb_el_counter_icon_style_typography'],
			$fields['counter_icon_style_bg_color'],
			$fields['counter_icon_style_hover_color'],
			$fields['counter_icon_style_border_hover_color'],
			$fields['counter_icon_style_hover_bg_color'],
			$fields['counter_icon_style_color_tab_end'],
			$fields['counter_icon_style_hover_color_tab'],
			$fields['counter_icon_style_alignment'],
			$fields['counter_icon_style_padding'],
			$fields['counter_icon_style_color_tabs_end'],
		);
		$extra_controls['counter_icon_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$extra_controls['counter_icon_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'counter_icon_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['counter_icon_style_icon_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Width', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_width'],
		];
		$extra_controls2['counter_icon_style_icon_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Height', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_height'],
		];

		$extra_controls2['counter_icon_style_icon_size'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_size'],
		];

		$fields = Fns::insert_controls( 'counter_icon_style_spacing_note', $fields, $extra_controls2, true );
		$extra_controls3['counter_icon_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$fields = Fns::insert_controls( 'counter_icon_style_color', $fields, $extra_controls3, true );

		return $fields;
	}

	/**
	 * Counter style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function counter_count_style( $obj ) {
		$css_selectors = $obj->selectors['counter_count_style'];
		$title         = esc_html__( 'Counter Style', 'shopbuilder' );
		$selectors     = [
			'typography'   => $css_selectors['typography'],
			'color'        => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'suffix_color' => [ $css_selectors['suffix_color'] => 'color: {{VALUE}};' ],
			'prefix_color' => [ $css_selectors['prefix_color'] => 'color: {{VALUE}};' ],
			'margin'       => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'counter_count_style', $title, $obj, [], $selectors );

		// Typography text-decoration set on the inline-flex `.rtsb-counter-number-wrap` does not reach the
		// flex items / Odometer digit spans, so it lands on nothing. Re-target the leaf text elements
		// (prefix/suffix + the Odometer `.odometer-value` digits and `.odometer-formatting-mark` commas).
		$fields['rtsb_el_counter_count_style_typography']['fields_options'] = [
			'text_decoration' => [
				'selectors' => [
					$css_selectors['decoration'] => 'text-decoration: {{VALUE}};',
				],
			],
		];

		unset(
			$fields['counter_count_style_alignment'],
			$fields['counter_count_style_border_note'],
			$fields['counter_count_style_padding'],
			$fields['rtsb_el_counter_count_style_border'],
			$fields['counter_count_style_color_tabs'],
			$fields['counter_count_style_color_tab'],
			$fields['counter_count_style_bg_color'],
			$fields['counter_count_style_color_tab_end'],
			$fields['counter_count_style_hover_color_tab'],
			$fields['counter_count_style_hover_color'],
			$fields['counter_count_style_hover_bg_color'],
			$fields['counter_count_style_hover_color_tab_end'],
			$fields['counter_count_style_color_tabs_end'],
			$fields['counter_count_style_border_hover_color']
		);
		$fields['counter_count_style_color']['label']             = esc_html__( 'Number Color', 'shopbuilder' );
		$extra_controls['counter_count_style_count_prefix_color'] = [
			'label'     => esc_html__( 'Prefix Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['prefix_color'],
		];
		$extra_controls['counter_count_style_count_suffix_color'] = [
			'label'     => esc_html__( 'Suffix Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['suffix_color'],
		];

		$fields = Fns::insert_controls( 'counter_count_style_color_note', $fields, $extra_controls, true );
		return $fields;
	}
}

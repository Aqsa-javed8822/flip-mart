<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\InfoBox;

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
			self::info_box_icon( $obj ),
			self::info_box_title( $obj ),
			self::info_box_count( $obj ),
			self::info_box_content( $obj ),
			self::info_box_button( $obj ),
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
			self::title_style( $obj ),
			self::content_style( $obj ),
			self::button_style( $obj, 'primary' ),
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
		$fields['info_box_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']     = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']    = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_info_box_layouts(),
			'default' => 'rtsb-info-box-layout1',
		];

		$fields['sb_button_hover_effect_note'] = $obj->el_heading( esc_html__( 'Button Hover Effect Preset', 'shopbuilder' ), 'before' );
		$fields['button_hover_effects']        = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_button_hover_effect_preset(),
			'default' => 'rtsb-sb-button-hover-effect-default',
		];
		$fields['hover_animation']             = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Hover Animation', 'shopbuilder' ),
			'options'   => [
				'left'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'   => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'toggle'    => true,
			'default'   => 'left',
			'condition' => [
				'button_hover_effects' => [ 'rtsb-sb-button-hover-effect-preset2' ],
			],
		];
		$fields['info_box_preset_end'] = $obj->end_section();
		return $fields;
	}

	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function info_box_title( $obj ) {
		$fields['info_box_title_sec_start'] = $obj->start_section(
			esc_html__( 'Title', 'shopbuilder' ),
			'content'
		);
		$fields['info_box_title_html_tag']  = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['info_box_title']           = [
			'label'       => esc_html__( 'Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the title text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'label_block' => true,
			'default'     => __( 'ShopBuilder for Easy Store Customization', 'shopbuilder' ),
		];

		$fields['info_box_title_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function info_box_content( $obj ) {
		$condition                            = [
			'display_content' => 'yes',
		];
		$fields['info_box_content_sec_start'] = $obj->start_section(
			esc_html__( 'Description', 'shopbuilder' ),
			'content'
		);
		$fields['display_content']            = [
			'label'       => esc_html__( 'Show Content?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show content.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		 $fields['info_box_content']          = [
			 'label'       => esc_html__( 'Content', 'shopbuilder' ),
			 'description' => esc_html__( 'Please enter the call to action description text.', 'shopbuilder' ),
			 'type'        => 'wysiwyg',
			 'label_block' => true,
			 'default'     => sprintf( '<p>%s</p>', esc_html__( 'ShopBuilder is a versatile plugin designed for Elementor Page Builder. This plugin helps you design and customize your online store easily, letting you create attractive product pages and improve the shopping experience for your customers.', 'shopbuilder' ) ),
			 'condition'   => $condition,
		 ];
		 $fields['info_box_content_sec_end']  = $obj->end_section();

		 return $fields;
	}
	/**
	 * Count section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function info_box_count( $obj ) {
		$condition                          = [
			'display_count' => 'yes',
		];
		$fields['info_box_count_sec_start'] = $obj->start_section(
			esc_html__( 'Count', 'shopbuilder' ),
			'content',
			[],
			[
				'layout_style' => [ 'rtsb-info-box-layout4' ],
			]
		);
		$fields['display_count']            = [
			'label'       => esc_html__( 'Show Count?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show count.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['info_box_count']           = [
			'label'       => esc_html__( 'Count Number', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the count number for info box count.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => '01',
			'condition'   => $condition,
		];
		$fields['info_box_count_sec_end']   = $obj->end_section();

		return $fields;
	}
	/**
	 * Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function info_box_button( $obj ) {
		$condition                           = [
			'display_button' => 'yes',
		];
		$fields['info_box_button_sec_start'] = $obj->start_section(
			esc_html__( 'Button', 'shopbuilder' ),
			'content'
		);
		$fields['display_button']            = [
			'label'       => esc_html__( 'Show Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_button_icon']            = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $condition,
		];
		$fields['sb_button_icon_position']   = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Position', 'shopbuilder' ),
			'options'   => [
				'left'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'toggle'    => true,
			'default'   => 'right',
			'condition' => $condition,
		];

		$fields['sb_button_content'] = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Read More', 'shopbuilder' ),
			'condition'   => $condition,
		];
		$fields['sb_button_link']    = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'default'     => [ 'url' => '#' ],
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $condition,
		];

		$fields['info_box_button_sec_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Content Icon
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function info_box_icon( $obj ) {
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['info_box_content_icon_sec_start'] = $obj->start_section(
			esc_html__( 'Icon', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['info_box_icon_type']              = [
			'label'   => __( 'Icon Type', 'shopbuilder' ),
			'type'    => 'select',
			'default' => 'icon',
			'options' => [
				'icon'  => __( 'Icon', 'shopbuilder' ),
				'image' => __( 'Image', 'shopbuilder' ),
			],
		];
		$fields['info_box_icon']                   = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-code',
				'library' => 'fa-solid',
			],
			'condition' => [
				'info_box_icon_type' => [ 'icon' ],
			],
		];
		$fields['info_box_image']                  = [
			'type'      => 'media',
			'label'     => esc_html__( 'Upload Image', 'shopbuilder' ),
			'default'   => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
			'condition' => [
				'info_box_icon_type' => [ 'image' ],
			],
		];
		$fields['info_box_image_size']             = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
			'condition'       => [
				'info_box_icon_type' => [ 'image' ],
			],
		];
		$fields['info_box_img_dimension']          = [
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
						'name'     => 'info_box_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
					[
						'name'     => 'info_box_icon_type',
						'operator' => '==',
						'value'    => 'image',
					],
				],
			],
		];
		$fields['info_box_img_crop']               = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'info_box_icon_type' => [ 'image' ],
			],
		];

		$fields['info_box-img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'info_box_icon_type'  => [ 'image' ],
				'info_box_image_size' => 'rtsb_custom',
			],

		];
		$fields['info_box_icon_position'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'separator' => 'before',
			'label'     => esc_html__( 'Icon Position', 'shopbuilder' ),
			'options'   => [
				'float: left; margin-right: 30px;'        => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'float: none; display: block; margin: 0;' => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'float: right !important; margin-right: 0; margin-left: 30px;' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'selectors' => [ $obj->selectors['icon']['position'] => '{{VALUE}};' ],
			'toggle'    => true,
			'default'   => 'top',
			'condition' => [
				'layout_style!' => [ 'rtsb-info-box-layout4' ],
			],
		];
		$fields['info_box_element_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => $alignment_options,
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};' ],
			'condition' => [
				'layout_style!' => [ 'rtsb-info-box-layout4' ],
			],
		];

		$fields['info_box_content_sec_icon_end'] = $obj->end_section();
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

		$fields = ControlHelper::general_elementor_style( 'info_box_title_style', $title, $obj, [], $selectors );

		unset(
			$fields['info_box_title_style_alignment'],
			$fields['info_box_title_style_color_tabs'],
			$fields['info_box_title_style_color_tab'],
			$fields['info_box_title_style_bg_color'],
			$fields['info_box_title_style_color_tab_end'],
			$fields['info_box_title_style_hover_color_tab'],
			$fields['info_box_title_style_hover_color'],
			$fields['info_box_title_style_hover_bg_color'],
			$fields['info_box_title_style_hover_color_tab_end'],
			$fields['info_box_title_style_color_tabs_end'],
			$fields['info_box_title_style_border_hover_color']
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
			'typography'       => $css_selectors['typography'],
			'box_shadow'       => $css_selectors['box_shadow'],
			'hover_box_shadow' => $css_selectors['hover_box_shadow'],
			'gradient_bg'      => $css_selectors['gradient_bg'],
			'bg_overlay'       => $css_selectors['bg_overlay'],
			'count_color'      => [ $css_selectors['count_color'] => 'color: {{VALUE}};' ],
			'border'           => $css_selectors['border'],
			'padding'          => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'    => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'info_box_content_box_style', $title, $obj, [], $selectors );

		unset(
			$fields['info_box_content_box_style_color'],
			$fields['info_box_content_box_style_bg_color'],
			$fields['info_box_content_box_style_alignment'],
			$fields['info_box_content_box_style_color_tabs'],
			$fields['info_box_content_box_style_color_tab'],
			$fields['info_box_content_box_style_color_tab_end'],
			$fields['info_box_content_box_style_hover_color_tab'],
			$fields['info_box_content_box_style_hover_color'],
			$fields['info_box_content_box_style_hover_bg_color'],
			$fields['info_box_content_box_style_hover_color_tab_end'],
			$fields['info_box_content_box_style_color_tabs_end'],
			$fields['info_box_content_box_style_border_hover_color'],
			$fields['info_box_content_box_style_margin']
		);
		$fields['info_box_content_box_style_border_note']['separator'] = 'default';
		$fields['info_box_content_box_style_color_note']['separator']  = 'default';
		$fields['info_box_content_box_style_typo_note']['raw']         = sprintf(
			'<h3 class="rtsb-elementor-group-heading">%s</h3>',
			esc_html__( 'Count Typography', 'shopbuilder' )
		);

		$fields['info_box_content_box_style_typo_note']['condition']          = [
			'layout_style'  => [ 'rtsb-info-box-layout4' ],
			'display_count' => 'yes',
		];
		$fields['rtsb_el_info_box_content_box_style_typography']['condition'] = [
			'layout_style'  => [ 'rtsb-info-box-layout4' ],
			'display_count' => 'yes',
		];
		$extra_controls['info_box_content_box_style_border_radius']           = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'info_box_content_box_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['info_box_content_box_style_count_color']      = [
			'label'     => esc_html__( 'Count Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['count_color'],
			'condition' => [
				'layout_style'  => [ 'rtsb-info-box-layout4' ],
				'display_count' => 'yes',
			],
		];
		$extra_controls2['info_box_content_box_style_gradient_bg']      = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['info_box_content_box_style_bg_overlay']       = [
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
		$extra_controls2['info_box_content_box_style_box_shadow']       = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$extra_controls2['info_box_content_box_style_hover_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Hover Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['hover_box_shadow'],
		];
		$fields = Fns::insert_controls( 'info_box_content_box_style_color_note', $fields, $extra_controls2, true );

		return $fields;
	}
	/**
	 * Content style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function content_style( $obj ) {
		$condition     = [
			'display_content' => 'yes',
		];
		$css_selectors = $obj->selectors['content_style'];
		$title         = esc_html__( 'Description Style', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'info_box_content_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['info_box_content_style_alignment'],
			$fields['info_box_content_style_color_tabs'],
			$fields['info_box_content_style_color_tab'],
			$fields['info_box_content_style_bg_color'],
			$fields['info_box_content_style_color_tab_end'],
			$fields['info_box_content_style_hover_color_tab'],
			$fields['info_box_content_style_hover_color'],
			$fields['info_box_content_style_hover_bg_color'],
			$fields['info_box_content_style_hover_color_tab_end'],
			$fields['info_box_content_style_color_tabs_end'],
			$fields['info_box_content_style_border_hover_color']
		);

		return $fields;
	}
	/**
	 * Button style section
	 *
	 * @param object $obj  Reference object.
	 * @param string $type Button type.
	 *
	 * @return array
	 */
	private static function button_style( $obj, $type = 'primary' ) {
		$condition     = [
			'display_button' => 'yes',
		];
		$css_selectors = $obj->selectors[ $type . '_button_style' ];
		$title         = esc_html__( 'Button Style', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'icon_size'          => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'icon_gap'           => [ $css_selectors['icon_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'btn_width'          => [ $css_selectors['btn_width'] => 'min-width: {{SIZE}}{{UNIT}};' ],
			'btn_height'         => [ $css_selectors['btn_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'gradient_bg'        => $css_selectors['gradient_bg'],
			'hover_gradient_bg'  => $css_selectors['hover_gradient_bg'],
			'border'             => $css_selectors['border'],
			'box_shadow'         => $css_selectors['box_shadow'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'sb_' . $type . '_button_style', $title, $obj, $condition, $selectors );

		// The global `.rtsb-elementor-container a { text-decoration: none !important; }` shield
		// ( _el-global.scss ) outranks the typography group's text-decoration on the button <a>.
		// Re-emit it with !important so underline / overline / line-through actually apply.
		$typography_key = 'rtsb_el_sb_' . $type . '_button_style_typography';
		if ( isset( $fields[ $typography_key ] ) ) {
			$fields[ $typography_key ]['fields_options'] = [
				'text_decoration' => [
					'selectors' => [
						$css_selectors['typography'] => 'text-decoration: {{VALUE}} !important;',
					],
				],
			];
		}
		unset(
			$fields[ 'sb_' . $type . '_button_style_bg_color' ],
			$fields[ 'sb_' . $type . '_button_style_alignment' ],
			$fields[ 'sb_' . $type . '_button_style_hover_bg_color' ],
		);
		$extra_controls[ 'sb_' . $type . '_button_style_border_radius' ] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_spacing_note', $fields, $extra_controls );
		$extra_controls2[ 'sb_' . $type . '_button_style_btn_width' ]  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
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
			'selectors'  => $selectors['btn_width'],
		];
		$extra_controls2[ 'sb_' . $type . '_button_style_btn_height' ] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Height', 'shopbuilder' ),
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
			'selectors'  => $selectors['btn_height'],
		];

		$extra_controls2[ 'sb_' . $type . '_button_style_icon_size' ]  = [
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
		$extra_controls2[ 'sb_' . $type . '_button_style_icon_gap' ]   = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['icon_gap'],
		];
		$extra_controls2[ 'sb_' . $type . '_button_style_box_shadow' ] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_spacing_note', $fields, $extra_controls2, true );

		$extra_controls3[ 'sb_' . $type . '_button_style_gradient_bg' ] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_color', $fields, $extra_controls3, true );

		$extra_controls4[ 'sb_' . $type . '_button_style_hover_gradient_bg' ] = [
			'label'    => esc_html__( 'Hover Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['hover_gradient_bg'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_hover_color', $fields, $extra_controls4, true );

		return $fields;
	}
	/**
	 * Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function icon_box_style( $obj ) {
		$css_selectors = $obj->selectors['info_box_icon_style'];
		$title         = esc_html__( 'Icon Style', 'shopbuilder' );
		$selectors     = [
			'icon_size'          => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'box_shadow'         => $css_selectors['box_shadow'],
			'icon_width'         => [ $css_selectors['icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'icon_height'        => [ $css_selectors['icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'border_top_color'   => [ $css_selectors['border_top_color'] => '--rtsb-info-box-border-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'gradient_bg'        => $css_selectors['gradient_bg'],
			'hover_gradient_bg'  => $css_selectors['hover_gradient_bg'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'border'             => $css_selectors['border'],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'info_box_icon_style', $title, $obj, [], $selectors );
		unset(
			$fields['info_box_icon_style_typo_note'],
			$fields['rtsb_el_info_box_icon_style_typography'],
			$fields['info_box_icon_style_bg_color'],
			$fields['info_box_icon_style_hover_bg_color'],
			$fields['info_box_icon_style_hover_gradient_bg'],
			$fields['info_box_icon_style_alignment'],
			$fields['info_box_icon_style_padding'],
		);
		$extra_controls['info_box_icon_border_layer'] = [
			'label'     => esc_html__( 'Border Layer', 'shopbuilder' ),
			'type'      => 'choose',
			'options'   => [
				'color-left'   => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'color-top'    => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-top',
				],
				'color-bottom' => [
					'title' => esc_html__( 'Bottom', 'shopbuilder' ),
					'icon'  => 'eicon-v-align-bottom',
				],
				'color-right'  => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'toggle'    => true,
			'default'   => 'color-top',
			'condition' => [
				'layout_style' => [ 'rtsb-info-box-layout3' ],
			],
		];
		$extra_controls['info_box_icon_style_border_top_color'] = [
			'label'     => esc_html__( 'Border Layer Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['border_top_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-info-box-layout3' ],
			],
		];
		$extra_controls['info_box_icon_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$extra_controls['info_box_icon_style_box_shadow']       = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'info_box_icon_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['info_box_icon_style_icon_width']  = [
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
		$extra_controls2['info_box_icon_style_icon_height'] = [
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

		$extra_controls2['info_box_icon_style_icon_size'] = [
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

		$fields = Fns::insert_controls( 'info_box_icon_style_spacing_note', $fields, $extra_controls2, true );
		$extra_controls3['info_box_icon_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$fields = Fns::insert_controls( 'info_box_icon_style_color', $fields, $extra_controls3, true );

		$extra_controls4['info_box_icon_style_hover_gradient_bg'] = [
			'label'    => esc_html__( 'Hover Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['hover_gradient_bg'],
		];
		$fields = Fns::insert_controls( 'info_box_icon_style_hover_color', $fields, $extra_controls4, true );

		return $fields;
	}
}

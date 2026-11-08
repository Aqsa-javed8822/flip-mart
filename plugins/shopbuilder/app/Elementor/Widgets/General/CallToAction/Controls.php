<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CallToAction;

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
			self::cta_title( $obj ),
			self::cta_content( $obj ),
			self::cta_button( $obj ),
			self::cta_image( $obj ),
			self::cta_parallax_effect( $obj ),
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
			self::image_style( $obj ),
			self::title_style( $obj ),
			self::content_style( $obj ),
			self::button_style( $obj, 'primary' ),
			self::button_style( $obj, 'secondary' ),
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
		$fields['cta_preset']   = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']  = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_cta_layouts(),
			'default' => 'rtsb-cta-layout1',
		];

		$fields['sb_button_hover_effect_note'] = $obj->el_heading( esc_html__( 'Button Hover Effect Preset', 'shopbuilder' ), 'before' );
		$fields['button_hover_effects']        = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_button_hover_effect_preset(),
			'default' => 'rtsb-sb-button-hover-effect-default',
		];

		$fields['cta_element_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => $alignment_options,
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};' ],
			'condition' => [
				'layout_style' => [ 'rtsb-cta-layout1' ],
			],
		];
		$fields['hover_animation']       = [
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
		$fields['cta_column_gap'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Column Gap', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],
			],
			'selectors'  => [
				$obj->selectors['image_style']['column_gap'] => 'gap: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'layout_style' => [ 'rtsb-cta-layout3' ],
			],

		];
		$fields['cta_preset_end'] = $obj->end_section();
		return $fields;
	}

	/**
	 * Title section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function cta_title( $obj ) {
		$fields['cta_title_sec_start'] = $obj->start_section(
			esc_html__( 'Title', 'shopbuilder' ),
			'content'
		);
		$fields['cta_title_html_tag']  = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['cta_title']           = [
			'label'       => esc_html__( 'Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the call to action title text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'label_block' => true,
			'default'     => __( 'ShopBuilder for Easy Store Customization', 'shopbuilder' ),
		];

		$fields['cta_title_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function cta_content( $obj ) {
		$condition                       = [
			'display_content' => 'yes',
		];
		$fields['cta_content_sec_start'] = $obj->start_section(
			esc_html__( 'Description', 'shopbuilder' ),
			'content'
		);
		$fields['display_content']       = [
			'label'       => esc_html__( 'Show Content?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show content.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		 $fields['cta_content']          = [
			 'label'       => esc_html__( 'Content', 'shopbuilder' ),
			 'description' => esc_html__( 'Please enter the call to action description text.', 'shopbuilder' ),
			 'type'        => 'wysiwyg',
			 'label_block' => true,
			 'default'     => sprintf( '<p>%s</p>', esc_html__( 'ShopBuilder is a versatile plugin designed for Elementor Page Builder. This plugin helps you design and customize your online store easily, letting you create attractive product pages and improve the shopping experience for your customers.', 'shopbuilder' ) ),
			 'condition'   => $condition,
		 ];
		 $fields['cta_content_sec_end']  = $obj->end_section();

		 return $fields;
	}
	/**
	 * Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function cta_button( $obj ) {
		$condition                         = [
			'display_button' => 'yes',
		];
		$condition2                        = [
			'display_button'  => 'yes',
			'display_button2' => 'yes',
		];
		$fields['cta_button_sec_start']    = $obj->start_section(
			esc_html__( 'Button', 'shopbuilder' ),
			'content'
		);
		$fields['display_button']          = [
			'label'       => esc_html__( 'Show Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_button_icon']          = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $condition,
		];
		$fields['sb_button_icon_position'] = [
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
		$fields['sb_button_button1_note'] = $obj->el_heading( esc_html__( 'Primary Button', 'shopbuilder' ), 'default', [], $condition );
		$fields['sb_button_content']      = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Primary Button', 'shopbuilder' ),
			'condition'   => $condition,
		];
		$fields['sb_button_link']         = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $condition,
		];
		$fields['display_button2']        = [
			'label'       => esc_html__( 'Show Secondary Button', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show secondary button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'condition'   => $condition,
		];
		$fields['sb_button_button2_note'] = $obj->el_heading( esc_html__( 'Secondary Button', 'shopbuilder' ), 'default', [], $condition2 );
		$fields['sb_button2_content']     = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Secondary Button', 'shopbuilder' ),
			'condition'   => $condition2,
		];
		$fields['sb_button2_link']        = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $condition2,
		];
		$fields['cta_button_sec_end']     = $obj->end_section();

		return $fields;
	}
	/**
	 * Content Parallax Effect
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function cta_parallax_effect( $obj ) {
		$condition                                = [ 'parallax_effect' => [ 'yes' ] ];
		$fields['cta_content_parallax_sec_start'] = $obj->start_section(
			esc_html__( 'Parallax', 'shopbuilder' ),
			'content'
		);
		$fields['parallax_effect']                = [
			'label'       => esc_html__( 'Enable Background Parallax Effect?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable background image parallax effect.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['parallax_speed']                 = [
			'label'       => esc_html__( 'Speed', 'shopbuilder' ),
			'type'        => 'number',
			'description' => esc_html__( 'Please enter the parallax speed.', 'shopbuilder' ),
			'min'         => 0.1,
			'max'         => 5,
			'step'        => 0.1,
			'default'     => 0.5,
			'condition'   => $condition,
		];
		$fields['parallax_effect_note']           = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				__( 'The parallax effect is only available when a background image is uploaded. <br /><br />Please ensure a background image is selected to enable this feature.', 'shopbuilder' )
			),
			'condition' => $condition,
		];
		$fields['container_width']                = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Container Width', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1920,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$obj->selectors['content_box_style']['container_width'] => 'max-width:{{SIZE}}{{UNIT}};margin:0 auto;',
			],
			'condition'  => $condition,
		];
		$fields['cta_content_sec_parallax_end']   = $obj->end_section();

		return $fields;
	}
	/**
	 * Content Image
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function cta_image( $obj ) {
		$condition                             = [
			'layout_style' => [ 'rtsb-cta-layout3' ],
		];
		$fields['cta_content_image_sec_start'] = $obj->start_section(
			esc_html__( 'Image', 'shopbuilder' ),
			'content',
			[],
			$condition
		);
		$fields['cta_image']                   = [
			'type'    => 'media',
			'label'   => esc_html__( 'Upload Image', 'shopbuilder' ),
			'default' => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
		];
		$fields['cta_image_size']              = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
		];
		$fields['cta_img_dimension']           = [
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
						'name'     => 'cta_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
				],
			],
		];
		$fields['cta_img_crop']                = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'conditions'  => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'cta_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
				],
			],
		];

		$fields['cta-img_custom_dimension_note'] = [
			'type'       => 'html',
			'raw'        => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'conditions' => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'cta_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
				],
			],

		];
		$fields['cta_img_linkable']          = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Image Linkable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable image linking.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => 'before',
		];
		$fields['cta_img_link']              = [
			'type'        => 'link',
			'label'       => esc_html__( 'Image Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter an alternate custom link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => [
				'cta_img_linkable' => [ 'yes' ],
			],
		];
		$fields['cta_content_sec_image_end'] = $obj->end_section();

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

		$fields = ControlHelper::general_elementor_style( 'cta_title_style', $title, $obj, [], $selectors );

		unset(
			$fields['cta_title_style_alignment'],
			$fields['cta_title_style_color_tabs'],
			$fields['cta_title_style_color_tab'],
			$fields['cta_title_style_bg_color'],
			$fields['cta_title_style_color_tab_end'],
			$fields['cta_title_style_hover_color_tab'],
			$fields['cta_title_style_hover_color'],
			$fields['cta_title_style_hover_bg_color'],
			$fields['cta_title_style_hover_color_tab_end'],
			$fields['cta_title_style_color_tabs_end'],
			$fields['cta_title_style_border_hover_color']
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
	private static function image_style( $obj ) {
		$condition     = [
			'layout_style' => [ 'rtsb-cta-layout3' ],
		];
		$css_selectors = $obj->selectors['image_style'];
		$title         = esc_html__( 'Image Style', 'shopbuilder' );
		$selectors     = [
			'box_shadow'      => $css_selectors['box_shadow'],
			'border'          => $css_selectors['border'],
			'margin'          => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'   => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'image_width'     => [ $css_selectors['image_width'] => 'width: {{SIZE}}{{UNIT}};flex: 0 0 {{SIZE}}{{UNIT}};' ],
			'image_max_width' => [ $css_selectors['image_max_width'] => 'max-width: {{SIZE}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'cta_image_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['cta_image_style_typo_note'],
			$fields['rtsb_el_cta_image_style_typography'],
			$fields['cta_image_style_color'],
			$fields['cta_image_style_bg_color'],
			$fields['cta_image_style_alignment'],
			$fields['cta_image_style_color_tabs'],
			$fields['cta_image_style_color_tab'],
			$fields['cta_image_style_color_tab_end'],
			$fields['cta_image_style_hover_color_tab'],
			$fields['cta_image_style_hover_color'],
			$fields['cta_image_style_hover_bg_color'],
			$fields['cta_image_style_hover_color_tab_end'],
			$fields['cta_image_style_color_tabs_end'],
			$fields['cta_image_style_border_hover_color'],
			$fields['cta_image_style_padding']
		);
		$fields['cta_image_style_border_note']['separator'] = 'default';
		$fields['cta_image_style_color_note']['separator']  = 'default';
		$extra_controls['cta_image_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields                                        = Fns::insert_controls( 'cta_image_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['cta_image_style_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields                                        = Fns::insert_controls( 'cta_image_style_color_note', $fields, $extra_controls2, true );

		$extra_controls3['image_width_heading'] = $obj->el_heading( esc_html__( 'Dimension', 'shopbuilder' ), 'default' );
		$extra_controls3['image_styles_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Image Width', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['image_width'],

		];
		$extra_controls3['image_styles_max_width'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Image Max-Width', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],

			],
			'selectors'  => $selectors['image_max_width'],
		];
		$fields = Fns::insert_controls( 'cta_image_style_color_note', $fields, $extra_controls3 );
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
			'box_shadow'    => $css_selectors['box_shadow'],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'bg_overlay'    => $css_selectors['bg_overlay'],
			'border'        => $css_selectors['border'],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'cta_content_box_style', $title, $obj, [], $selectors );

		unset(
			$fields['cta_content_box_style_typo_note'],
			$fields['rtsb_el_cta_content_box_style_typography'],
			$fields['cta_content_box_style_color'],
			$fields['cta_content_box_style_bg_color'],
			$fields['cta_content_box_style_alignment'],
			$fields['cta_content_box_style_color_tabs'],
			$fields['cta_content_box_style_color_tab'],
			$fields['cta_content_box_style_color_tab_end'],
			$fields['cta_content_box_style_hover_color_tab'],
			$fields['cta_content_box_style_hover_color'],
			$fields['cta_content_box_style_hover_bg_color'],
			$fields['cta_content_box_style_hover_color_tab_end'],
			$fields['cta_content_box_style_color_tabs_end'],
			$fields['cta_content_box_style_border_hover_color'],
			$fields['cta_content_box_style_margin']
		);
		$fields['cta_content_box_style_border_note']['separator'] = 'default';
		$fields['cta_content_box_style_color_note']['separator']  = 'default';
		$extra_controls['cta_content_box_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'cta_content_box_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['cta_content_box_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['cta_content_box_style_bg_overlay']  = [
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
		$extra_controls2['cta_content_box_style_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'cta_content_box_style_color_note', $fields, $extra_controls2, true );

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

		$fields = ControlHelper::general_elementor_style( 'cta_content_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['cta_content_style_alignment'],
			$fields['cta_content_style_color_tabs'],
			$fields['cta_content_style_color_tab'],
			$fields['cta_content_style_bg_color'],
			$fields['cta_content_style_color_tab_end'],
			$fields['cta_content_style_hover_color_tab'],
			$fields['cta_content_style_hover_color'],
			$fields['cta_content_style_hover_bg_color'],
			$fields['cta_content_style_hover_color_tab_end'],
			$fields['cta_content_style_color_tabs_end'],
			$fields['cta_content_style_border_hover_color']
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
		$condition = [
			'display_button' => 'yes',
		];

		if ( 'secondary' === $type ) {
			$condition = [
				'display_button'  => 'yes',
				'display_button2' => 'yes',
			];
		}
		$css_selectors = $obj->selectors[ $type . '_button_style' ];
		$title         = sprintf(
			// Translators: %s is replaced with the button type, e.g., "Primary", "Secondary", etc.
			esc_html__( '%s Button Style', 'shopbuilder' ),
			ucfirst( $type )
		);
		$selectors = [
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
}

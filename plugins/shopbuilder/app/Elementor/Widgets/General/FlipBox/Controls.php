<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\FlipBox;

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
			self::flip_settings( $obj ),
			self::front_back_settings( $obj, 'front' ),
			self::front_back_settings( $obj, 'back' ),
			self::flip_box_button( $obj ),
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
			self::button_style( $obj, 'front' ),
			self::button_style( $obj, 'back' ),
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

		// Remove 'justify' from alignment options.
		unset( $alignment_options['justify'] );
		$fields['flip_box_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']     = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']    = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_flip_box_layouts(),
			'default' => 'rtsb-flip-box-layout1',
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
		$fields['flip_box_element_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => $alignment_options,
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};' ],
		];
		$fields['flip_box_preset_end']        = $obj->end_section();
		return $fields;
	}
	/**
	 * Flip Settings section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function flip_settings( $obj ) {
		$fields['flip_box_flip_settings_start']               = $obj->start_section(
			esc_html__( 'General Settings', 'shopbuilder' ),
			'content'
		);
		$fields['flip_behavior']                              = [
			'type'        => 'select',
			'label'       => esc_html__( 'Flip Behavior', 'shopbuilder' ),
			'label_block' => true,
			'options'     => [
				'hover' => esc_html__( 'Hover', 'shopbuilder' ),
				'click' => esc_html__( 'Click', 'shopbuilder' ),
			],
			'default'     => 'hover',
		];
		$fields['flip_direction']                             = [
			'type'        => 'select',
			'label'       => esc_html__( 'Flip Direction', 'shopbuilder' ),
			'label_block' => true,
			'options'     => ControlHelper::flip_box_widget_flip_direction(),
			'default'     => 'sb-flip-3d-left',
		];
		$fields['flip_selected_side']                         = [
			'type'        => 'select',
			'label'       => esc_html__( 'Selected Side', 'shopbuilder' ),
			'label_block' => true,
			'options'     => [
				'front' => esc_html__( 'Front', 'shopbuilder' ),
				'back'  => esc_html__( 'Back', 'shopbuilder' ),
			],
			'default'     => 'front',
		];
		$fields['flip_transition']                            = [
			'type'        => 'number',
			'label'       => esc_html__( 'Transition', 'shopbuilder' ),
			'selectors'   => [ $obj->selectors['flip_box']['transition'] => 'transition-duration: {{VALUE}}ms;' ],
			'description' => esc_html__( 'This time calculate with milliseconds and this transition value is not working 3d,zoom in,zoom out and fade in effect', 'shopbuilder' ),
		];
		$fields['flip_box_content_box_style_flip_box_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Flip Box Width', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 2000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['flip_box']['flip_box_width'] => 'max-width: {{SIZE}}{{UNIT}};' ],
		];
		$fields['flip_box_content_box_style_flip_box_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Flip Box Height', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 2000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [ $obj->selectors['flip_box']['flip_box_height'] => 'height: {{SIZE}}{{UNIT}};' ],
		];
		$fields['flip_box_flip_settings_end']                 = $obj->end_section();
		return $fields;
	}
	/**
	 * Front Settings section
	 *
	 * @param object $obj    Reference object.
	 * @param string $prefix Field prefix (front/back).
	 *
	 * @return array
	 */
	private static function front_back_settings( $obj, $prefix ) {
		$title_condition                                     = [
			'display_' . $prefix . '_title' => 'yes',
		];
		$content_condition                                   = [
			'display_' . $prefix . '_content' => 'yes',
		];
		$icon_condition                                      = [
			'display_' . $prefix . '_icon' => 'yes',
		];
		$fields[ 'flip_box_' . $prefix . '_settings_start' ] = $obj->start_section(
			sprintf(
			// Translators: %s is the prefix for the side title (e.g., 'Front', 'Back').
				esc_html__( '%s Side', 'shopbuilder' ),
				ucfirst( $prefix )
			),
			'content'
		);
		$fields[ 'flip_box_' . $prefix . '_title_heading' ]   = $obj->el_heading( esc_html__( 'Title', 'shopbuilder' ), 'default' );
		$fields[ 'display_' . $prefix . '_title' ]            = [
			'label'       => esc_html__( 'Show Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields[ 'flip_box_' . $prefix . '_title_html_tag' ]  = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
			'condition'   => $title_condition,
		];
		$fields[ 'flip_box_' . $prefix . '_title' ]           = [
			'label'       => esc_html__( 'Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the title text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'label_block' => true,
			'default'     => __( 'ShopBuilder for Easy Store Customization', 'shopbuilder' ),
			'condition'   => $title_condition,
		];
		$fields[ 'flip_box_' . $prefix . '_content_heading' ] = $obj->el_heading( esc_html__( 'Content', 'shopbuilder' ), 'default' );
		$fields[ 'display_' . $prefix . '_content' ]          = [
			'label'       => esc_html__( 'Show Content?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show content.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields[ 'flip_box_' . $prefix . '_content' ]         = [
			'label'       => esc_html__( 'Content', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the call to action description text.', 'shopbuilder' ),
			'type'        => 'wysiwyg',
			'label_block' => true,
			'default'     => sprintf( '<p>%s</p>', esc_html__( 'ShopBuilder is a versatile plugin designed for Elementor Page Builder. This plugin helps you design and customize your online store easily, letting you create attractive product pages and improve the shopping experience for your customers.', 'shopbuilder' ) ),
			'condition'   => $content_condition,
		];
		$fields[ 'flip_box_' . $prefix . '_icon_heading' ]    = $obj->el_heading( esc_html__( 'Icon', 'shopbuilder' ), 'default' );
		$fields[ 'display_' . $prefix . '_icon' ]             = [
			'label'       => esc_html__( 'Show Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];

		$fields[ 'flip_box_' . $prefix . '_icon_type' ] = [
			'label'     => __( 'Icon Type', 'shopbuilder' ),
			'type'      => 'select',
			'default'   => 'icon',
			'options'   => [
				'icon'  => __( 'Icon', 'shopbuilder' ),
				'image' => __( 'Image', 'shopbuilder' ),
			],
			'condition' => $icon_condition,
		];

		$fields[ 'flip_box_' . $prefix . '_icon' ] = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-code',
				'library' => 'fa-solid',
			],
			'condition' => [
				'flip_box_' . $prefix . '_icon_type' => [ 'icon' ],
				'display_' . $prefix . '_icon'       => 'yes',
			],

		];

		$fields[ 'flip_box_' . $prefix . '_image' ] = [
			'type'      => 'media',
			'label'     => esc_html__( 'Upload Image', 'shopbuilder' ),
			'default'   => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
			'condition' => [
				'flip_box_' . $prefix . '_icon_type' => [ 'image' ],
				'display_' . $prefix . '_icon'       => 'yes',
			],
		];

		$fields[ 'flip_box_' . $prefix . '_image_size' ] = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
			'condition'       => [
				'flip_box_' . $prefix . '_icon_type' => [ 'image' ],
				'display_' . $prefix . '_icon'       => 'yes',
			],
		];

		$fields[ 'flip_box_' . $prefix . '_img_dimension' ] = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'conditions'  => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'flip_box_' . $prefix . '_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
					[
						'name'     => 'flip_box_' . $prefix . '_icon_type',
						'operator' => '==',
						'value'    => 'image',
					],
					[
						'name'     => 'display_' . $prefix . '_icon',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		];

		$fields[ 'flip_box_' . $prefix . '_img_crop' ] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'flip_box_' . $prefix . '_icon_type'  => [ 'image' ],
				'display_' . $prefix . '_icon'        => 'yes',
				'flip_box_' . $prefix . '_image_size' => 'rtsb_custom',
			],
		];

		$fields[ 'flip_box_' . $prefix . '_img_custom_dimension_note' ] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'flip_box_' . $prefix . '_icon_type'  => [ 'image' ],
				'display_' . $prefix . '_icon'        => 'yes',
				'flip_box_' . $prefix . '_image_size' => 'rtsb_custom',
			],
		];
		$fields[ 'flip_box_' . $prefix . '_settings_end' ]              = $obj->end_section();
		return $fields;
	}


	/**
	 * Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function flip_box_button( $obj ) {
		$front_condition                         = [
			'display_front_button' => 'yes',
		];
		$back_condition                          = [
			'display_back_button' => 'yes',
		];
		$fields['flip_box_button_sec_start']     = $obj->start_section(
			esc_html__( 'Button', 'shopbuilder' ),
			'content'
		);
		$fields['flip_box_front_button_heading'] = $obj->el_heading( esc_html__( 'Front', 'shopbuilder' ), 'default' );
		$fields['display_front_button']          = [
			'label'       => esc_html__( 'Show Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_front_button_icon']          = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $front_condition,
		];
		$fields['sb_front_button_icon_position'] = [
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
			'condition' => $front_condition,
		];

		$fields['sb_front_button_content']      = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'ShopBuilder Button', 'shopbuilder' ),
			'condition'   => $front_condition,
		];
		$fields['sb_front_button_link']         = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $front_condition,
		];
		$fields['flip_box_back_button_heading'] = $obj->el_heading( esc_html__( 'Back', 'shopbuilder' ), 'default' );
		$fields['display_back_button']          = [
			'label'       => esc_html__( 'Show Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_back_button_icon']          = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $back_condition,
		];
		$fields['sb_back_button_icon_position'] = [
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
			'condition' => $back_condition,
		];

		$fields['sb_back_button_content'] = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'ShopBuilder Button', 'shopbuilder' ),
			'condition'   => $back_condition,
		];
		$fields['sb_back_button_link']    = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $back_condition,
		];

		$fields['flip_box_button_sec_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 *  Content style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_style( $obj ) {
		$css_selectors                              = $obj->selectors['content_style'];
		$selectors                                  = [
			'front_typography' => $css_selectors['front_typography'],
			'front_color'      => [
				$css_selectors['front_color']           => 'color: {{VALUE}};',
				$css_selectors['front_list_icon_color'] => 'background-color: {{VALUE}};',
			],
			'front_border'     => $css_selectors['front_border'],
			'front_padding'    => [ $css_selectors['front_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'front_margin'     => [ $css_selectors['front_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'back_typography'  => $css_selectors['back_typography'],
			'back_color'       => [
				$css_selectors['back_color']           => 'color: {{VALUE}};',
				$css_selectors['back_list_icon_color'] => 'background-color: {{VALUE}};',
			],
			'back_border'      => $css_selectors['back_border'],
			'back_padding'     => [ $css_selectors['back_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_margin'      => [ $css_selectors['back_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];
		$fields['flip_box_content_style_sec_start'] = $obj->start_section(
			esc_html__( 'Content Style', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['flipbox_content_tabs']             = $obj->start_tab_group();
		$fields['flipbox_content_front_tab']        = $obj->start_tab( esc_html__( 'Front', 'shopbuilder' ) );
		$fields['front_content_typo_note']          = $obj->el_heading( esc_html__( 'Typography', 'shopbuilder' ), 'default' );
		$fields['front_content_typography']         = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => $selectors['front_typography'],
		];
		$fields['front_content_color_note']         = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['front_content_color']              = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['front_color'],
			'separator' => 'default',
		];
		$fields['front_content_border_note']        = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['front_content_border']             = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['front_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['front_content_spacing_note']       = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['front_content_padding']            = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_padding'],
			'separator'  => 'default',
		];
		$fields['front_content_margin']             = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_margin'],
		];
		$fields['flipbox_content_front_tab_end']    = $obj->end_tab();

		$fields['flipbox_content_back_tab']     = $obj->start_tab( esc_html__( 'Back', 'shopbuilder' ) );
		$fields['back_content_typo_note']       = $obj->el_heading( esc_html__( 'Typography', 'shopbuilder' ), 'default' );
		$fields['back_content_typography']      = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => $selectors['back_typography'],
		];
		$fields['back_content_color_note']      = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['back_content_color']           = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['back_color'],
			'separator' => 'default',
		];
		$fields['back_content_border_note']     = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['back_content_border']          = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['back_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['back_content_spacing_note']    = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['back_content_padding']         = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_padding'],
			'separator'  => 'default',
		];
		$fields['back_content_margin']          = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_margin'],
		];
		$fields['flipbox_content_back_tab_end'] = $obj->end_tab();

		$fields['flipbox_content_tabs_end']       = $obj->end_tab_group();
		$fields['flip_box_content_style_sec_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 *  Title style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function title_style( $obj ) {
		$css_selectors                            = $obj->selectors['title_style'];
		$selectors                                = [
			'front_typography'       => $css_selectors['front_typography'],
			'front_color'            => [ $css_selectors['front_color'] => 'color: {{VALUE}};' ],
			'front_bg_color'         => [ $css_selectors['front_bg_color'] => 'background: {{VALUE}};' ],
			'front_title_box_shadow' => $css_selectors['front_title_box_shadow'],
			'front_border'           => $css_selectors['front_border'],
			'front_padding'          => [ $css_selectors['front_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'front_margin'           => [ $css_selectors['front_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'front_border_radius'    => [ $css_selectors['front_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'back_typography'        => $css_selectors['back_typography'],
			'back_color'             => [ $css_selectors['back_color'] => 'color: {{VALUE}};' ],
			'back_bg_color'          => [ $css_selectors['back_bg_color'] => 'background: {{VALUE}};' ],
			'back_title_box_shadow'  => $css_selectors['back_title_box_shadow'],
			'back_border'            => $css_selectors['back_border'],
			'back_padding'           => [ $css_selectors['back_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_border_radius'     => [ $css_selectors['back_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'back_margin'            => [ $css_selectors['back_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];
		$fields['flip_box_title_style_sec_start'] = $obj->start_section(
			esc_html__( 'Title Style', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['flipbox_title_tabs']             = $obj->start_tab_group();
		$fields['flipbox_title_front_tab']        = $obj->start_tab( esc_html__( 'Front', 'shopbuilder' ) );
		$fields['front_typo_note']                = $obj->el_heading( esc_html__( 'Typography', 'shopbuilder' ), 'default' );
		$fields['front_title_typography']         = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => $selectors['front_typography'],
		];
		$fields['front_title_color_note']         = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['front_title_color']              = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['front_color'],
			'separator' => 'default',
		];
		$fields['front_title_bg_color']           = [
			'type'      => 'color',
			'label'     => esc_html__( 'Background', 'shopbuilder' ),
			'selectors' => $selectors['front_bg_color'],
			'separator' => 'default',
		];
		$fields['front_title_box_shadow']         = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['front_title_box_shadow'],
		];
		$fields['front_title_border_note']        = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['front_title_border']             = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['front_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['front_title_border_radius']      = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_border_radius'],
			'separator'  => 'default',
		];
		$fields['front_title_spacing_note']       = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['front_title_padding']            = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_padding'],
			'separator'  => 'default',
		];
		$fields['front_title_margin']             = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_margin'],
		];
		$fields['flipbox_title_front_tab_end']    = $obj->end_tab();

		$fields['flipbox_title_back_tab']     = $obj->start_tab( esc_html__( 'Back', 'shopbuilder' ) );
		$fields['back_title_typo_note']       = $obj->el_heading( esc_html__( 'Typography', 'shopbuilder' ), 'default' );
		$fields['back_title_typography']      = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => $selectors['back_typography'],
		];
		$fields['back_title_color_note']      = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['back_title_color']           = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['back_color'],
			'separator' => 'default',
		];
		$fields['back_title_bg_color']        = [
			'type'      => 'color',
			'label'     => esc_html__( 'Background', 'shopbuilder' ),
			'selectors' => $selectors['back_bg_color'],
			'separator' => 'default',
		];
		$fields['back_title_box_shadow']      = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['back_title_box_shadow'],
		];
		$fields['back_title_border_note']     = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['back_title_border']          = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['back_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['back_title_border_radius']   = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_border_radius'],
			'separator'  => 'default',
		];
		$fields['back_title_spacing_note']    = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['back_title_padding']         = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_padding'],
			'separator'  => 'default',
		];
		$fields['back_title_margin']          = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_margin'],
		];
		$fields['flipbox_title_back_tab_end'] = $obj->end_tab();

		$fields['flipbox_title_tabs_end']       = $obj->end_tab_group();
		$fields['flip_box_title_style_sec_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 *  Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function icon_box_style( $obj ) {
		$css_selectors = $obj->selectors['flip_box_icon_style'];
		$selectors     = [
			'front_icon_size'     => [
				$css_selectors['front_icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['front_icon_size']['svg'] => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'front_box_shadow'    => $css_selectors['front_box_shadow'],
			'front_icon_width'    => [ $css_selectors['front_icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'front_icon_height'   => [ $css_selectors['front_icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'front_color'         => [ $css_selectors['front_color'] => 'color: {{VALUE}};' ],
			'front_gradient_bg'   => $css_selectors['front_gradient_bg'],
			'front_border'        => $css_selectors['front_border'],
			'front_margin'        => [ $css_selectors['front_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'front_border_radius' => [ $css_selectors['front_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_icon_size'      => [
				$css_selectors['back_icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['back_icon_size']['svg'] => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'back_box_shadow'     => $css_selectors['back_box_shadow'],
			'back_icon_width'     => [ $css_selectors['back_icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'back_icon_height'    => [ $css_selectors['back_icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'back_color'          => [ $css_selectors['back_color'] => 'color: {{VALUE}};' ],
			'back_gradient_bg'    => $css_selectors['back_gradient_bg'],
			'back_border'         => $css_selectors['back_border'],
			'back_margin'         => [ $css_selectors['back_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_border_radius'  => [ $css_selectors['back_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],

		];
		$fields['flip_box_icon_style_sec_start']  = $obj->start_section(
			esc_html__( 'Icon Style', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['flipbox_icon_tabs']              = $obj->start_tab_group();
		$fields['flipbox_icon_front_tab']         = $obj->start_tab( esc_html__( 'Front', 'shopbuilder' ) );
		$fields['front_color_note']               = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['front_icon_color']               = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['front_color'],
			'separator' => 'default',
		];
		$fields['front_icon_style_gradient_bg']   = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['front_gradient_bg'],
		];
		$fields['front_icon_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['front_box_shadow'],
		];
		$fields['front_border_note']              = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['front_icon_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['front_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['front_icon_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['front_border_radius'],
		];
		$fields['front_spacing_note']             = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['front_icon_style_icon_width']    = [
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
			'selectors'  => $selectors['front_icon_width'],
		];
		$fields['front_icon_style_icon_height']   = [
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
			'selectors'  => $selectors['front_icon_height'],
		];
		$fields['front_icon_style_icon_size']     = [
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
			'selectors'  => $selectors['front_icon_size'],
		];
		$fields['front_icon_margin']              = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_margin'],
		];
		$fields['flipbox_icon_front_tab_end']     = $obj->end_tab();

		$fields['flipbox_icon_back_tab']         = $obj->start_tab( esc_html__( 'Back', 'shopbuilder' ) );
		$fields['back_color_note']               = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['back_icon_color']               = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => $selectors['back_color'],
			'separator' => 'default',
		];
		$fields['back_icon_style_gradient_bg']   = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['back_gradient_bg'],
		];
		$fields['back_icon_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['back_box_shadow'],
		];
		$fields['back_border_note']              = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['back_icon_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['back_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['back_icon_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['back_border_radius'],
		];
		$fields['back_spacing_note']             = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['back_icon_style_icon_width']    = [
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
			'selectors'  => $selectors['back_icon_width'],
		];
		$fields['back_icon_style_icon_height']   = [
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
			'selectors'  => $selectors['back_icon_height'],
		];
		$fields['back_icon_style_icon_size']     = [
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
			'selectors'  => $selectors['back_icon_size'],
		];
		$fields['back_icon_margin']              = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_margin'],
		];
		$fields['flipbox_icon_back_tab_end']     = $obj->end_tab();

		$fields['flipbox_icon_tabs_end']       = $obj->end_tab_group();
		$fields['flip_box_icon_style_sec_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function content_box_style( $obj ) {
		$css_selectors                                   = $obj->selectors['content_box_style'];
		$selectors                                       = [
			'front_box_shadow'    => $css_selectors['front_box_shadow'],
			'front_gradient_bg'   => $css_selectors['front_gradient_bg'],
			'front_bg_overlay'    => $css_selectors['front_bg_overlay'],
			'front_border'        => $css_selectors['front_border'],
			'front_padding'       => [ $css_selectors['front_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'front_border_radius' => [ $css_selectors['front_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_box_shadow'     => $css_selectors['back_box_shadow'],
			'back_gradient_bg'    => $css_selectors['back_gradient_bg'],
			'back_bg_overlay'     => $css_selectors['back_bg_overlay'],
			'back_border'         => $css_selectors['back_border'],
			'back_padding'        => [ $css_selectors['back_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'back_border_radius'  => [ $css_selectors['back_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];
		$fields['content_box_style_sec_start']           = $obj->start_section(
			esc_html__( 'Content Box Style', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['content_box_tabs']                      = $obj->start_tab_group();
		$fields['content_box_front_tab']                 = $obj->start_tab( esc_html__( 'Front', 'shopbuilder' ) );
		$fields['front_content_box_color_note']          = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['front_content_box_style_gradient_bg']   = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['front_gradient_bg'],
		];
		$fields['front_content_box_style_bg_overlay']    = [
			'label'          => esc_html__( 'Background Overlay', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['front_bg_overlay'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background Type', 'shopbuilder' ),
				],
			],
		];
		$fields['front_content_box_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['front_box_shadow'],
		];
		$fields['front_content_box_border_note']         = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['front_content_box_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['front_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['front_content_box_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['front_border_radius'],
		];
		$fields['content_box_front_spacing_note']        = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );

		$fields['front_content_box_padding'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['front_padding'],
		];
		$fields['content_box_front_tab_end'] = $obj->end_tab();

		$fields['content_box_back_tab'] = $obj->start_tab( esc_html__( 'Back', 'shopbuilder' ) );

		$fields['back_content_box_color_note']          = $obj->el_heading( esc_html__( 'Color', 'shopbuilder' ), 'default' );
		$fields['back_content_box_style_gradient_bg']   = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['back_gradient_bg'],
		];
		$fields['back_content_box_style_bg_overlay']    = [
			'label'          => esc_html__( 'Background Overlay', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['back_bg_overlay'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background Type', 'shopbuilder' ),
				],
			],
		];
		$fields['back_content_box_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['back_box_shadow'],
		];
		$fields['back_content_box_border_note']         = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'default' );
		$fields['back_content_box_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['back_border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['back_content_box_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['back_border_radius'],
		];
		$fields['back_content_box_spacing_note']        = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'default' );
		$fields['back_content_box_padding']             = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['back_padding'],
			'separator'  => 'default',
		];

		$fields['content_box_back_tab_end'] = $obj->end_tab();

		$fields['content_box_tabs_end']  = $obj->end_tab_group();
		$fields['content_box_style_end'] = $obj->end_section();
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
	private static function button_style( $obj, $type = 'front' ) {
		$condition     = [
			'display_' . $type . '_button' => 'yes',
		];
		$css_selectors = $obj->selectors[ $type . '_button_style' ];
		$title         = sprintf(
		// Translators: %s is replaced with the button type, e.g., "Front", "Back", etc.
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
			// Also target the .text/.hover-text spans: text-swap hover effects reveal an
			// absolutely-positioned .hover-text that an ancestor <a>'s decoration cannot reach.
			$td_base     = $css_selectors['typography'];
			$td_selector = $td_base . ',' . $td_base . ' .text,' . $td_base . ' .hover-text';

			$fields[ $typography_key ]['fields_options'] = [
				'text_decoration' => [
					'selectors' => [
						$td_selector => 'text-decoration: {{VALUE}} !important;',
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

<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderButton;

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
			self::button_content( $obj ),
			self::connector_content( $obj )
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
		$fields['sb_button_preset']            = $obj->start_section(
			esc_html__( 'Preset', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']                 = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']                = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_button_layouts(),
			'default' => 'rtsb-sb-button-layout1',
		];
		$fields['sb_button_hover_effect_note'] = $obj->el_heading( esc_html__( 'Hover Effect Preset', 'shopbuilder' ), 'before' );
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

		$fields['sb_button_end'] = $obj->end_section();
		return $fields;
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
			self::button_style( $obj, 'primary' ),
			self::button_style( $obj, 'secondary' ),
			self::connector_style( $obj )
		);
	}

	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function button_content( $obj ) {
		$condition                          = [
			'layout_style' => [ 'rtsb-sb-button-layout2' ],
		];
		$fields['sb_button_content_start']  = $obj->start_section(
			esc_html__( 'Button', 'shopbuilder' ),
			'content'
		);
		$fields['sb_button_button1_note']   = $obj->el_heading( esc_html__( 'Button 1', 'shopbuilder' ), 'default', [], [ 'layout_style' => 'rtsb-sb-button-layout2' ] );
		$fields['sb_button__content']       = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'ShopBuilder Button', 'shopbuilder' ),
		];
		$fields['sb_button_link']           = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
		];
		$fields['sb_button1_icon']          = [
			'label'   => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'    => 'icons',
			'default' => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
		];
		$fields['sb_button1_icon_position'] = [
			'mode'    => 'responsive',
			'type'    => 'choose',
			'label'   => esc_html__( 'Position', 'shopbuilder' ),
			'options' => [
				'left'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],

			],
			'toggle'  => true,
			'default' => 'right',
		];
		$fields['sb_button_button2_note']   = $obj->el_heading( esc_html__( 'Button 2', 'shopbuilder' ), 'default', [], $condition );
		$fields['sb_button2__content']      = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'ShopBuilder Button', 'shopbuilder' ),
			'condition'   => $condition,
		];
		$fields['sb_button2_link']          = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $condition,
		];
		$fields['sb_button2_icon']          = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $condition,
		];
		$fields['sb_button2_icon_position'] = [
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
			'condition' => $condition,
			'toggle'    => true,
			'default'   => 'right',
		];
		$fields['sb_button_content_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Connector section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function connector_content( $obj ) {
		$condition                              = [
			'layout_style' => [ 'rtsb-sb-button-layout2' ],
		];
		$fields['sb_button_connector_start']    = $obj->start_section(
			esc_html__( 'Connector', 'shopbuilder' ),
			'content',
			[],
			$condition
		);
		$fields['display_connector']            = [
			'label'       => esc_html__( 'Show Connector?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show connector.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['connector_type']               = [
			'label'     => esc_html__( 'Connector Type', 'shopbuilder' ),
			'type'      => 'choose',
			'options'   => [
				'text' => [
					'title' => esc_html__( 'Text', 'shopbuilder' ),
					'icon'  => 'eicon-text',
				],
				'icon' => [
					'title' => esc_html__( 'Icon', 'shopbuilder' ),
					'icon'  => 'eicon-nerd',
				],
			],
			'default'   => 'text',
			'condition' => [
				'display_connector' => [ 'yes' ],
			],
		];
		$fields['sb_button_connect_text']       = [
			'label'       => esc_html__( 'Text', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the button text.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'OR', 'shopbuilder' ),
			'condition'   => [
				'display_connector' => [ 'yes' ],
				'connector_type'    => [ 'text' ],
			],
		];
		$fields['sb_button_connect_icon']       = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => [
				'display_connector' => [ 'yes' ],
				'connector_type'    => [ 'icon' ],
			],
		];
		$fields['sb_button_connector_position'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Connector Position', 'shopbuilder' ),
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'default'    => [
				'unit' => '%',
			],
			'selectors'  => [
				$obj->selectors['connector_style']['connector_position'] => 'left: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['sb_button_gap']                = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Gap', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$obj->selectors['connector_style']['button_gap'] => 'gap: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['sb_button_connector_end']      = $obj->end_section();

		return $fields;
	}
	/**
	 * Description style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function button_style( $obj, $type = 'primary' ) {
		$condition = [];
		if ( 'secondary' === $type ) {
			$condition = [
				'layout_style' => [ 'rtsb-sb-button-layout2' ],
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
			'alignment'          => [ $css_selectors['alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};' ],
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
			'hover_box_shadow'   => $css_selectors['hover_box_shadow'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'sb_' . $type . '_button_style', $title, $obj, $condition, $selectors );
		unset(
			$fields[ 'sb_' . $type . '_button_style_bg_color' ],
			$fields[ 'sb_' . $type . '_button_style_hover_bg_color' ],
		);
		if ( 'secondary' === $type ) {
			unset( $fields[ 'sb_' . $type . '_button_style_alignment' ] );
		}
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
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_spacing_note', $fields, $extra_controls2, true );
		$extra_controls3[ 'sb_' . $type . '_button_style_gradient_bg' ] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls3[ 'sb_' . $type . '_button_style_box_shadow' ]  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_color', $fields, $extra_controls3, true );
		$extra_controls4[ 'sb_' . $type . '_button_style_hover_gradient_bg' ] = [
			'label'    => esc_html__( 'Hover Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['hover_gradient_bg'],
		];
		$extra_controls4[ 'sb_' . $type . '_button_style_hover_box_shadow' ]  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Hover Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['hover_box_shadow'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_hover_color', $fields, $extra_controls4, true );
		return $fields;
	}
	/**
	 * Connector style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function connector_style( $obj ) {
		$css_selectors = $obj->selectors['connector_style'];
		$title         = esc_html__( 'Connector Style', 'shopbuilder' );
		$condition     = [
			'display_connector' => 'yes',
			'layout_style'      => 'rtsb-sb-button-layout2',
		];
		$selectors     = [
			'typography'       => $css_selectors['typography'],
			'icon_size'        => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'color'            => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'         => [ $css_selectors['bg_color'] => 'background: {{VALUE}};' ],
			'border'           => $css_selectors['border'],
			'box_shadow'       => $css_selectors['box_shadow'],
			'border_radius'    => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'connector_width'  => [ $css_selectors['connector_width'] => 'width: {{SIZE}}{{UNIT}}' ],
			'connector_height' => [ $css_selectors['connector_height'] => 'height: {{SIZE}}{{UNIT}}' ],
		];

		$fields = ControlHelper::general_elementor_style( 'sb_button_connector_style', $title, $obj, $condition, $selectors );
		$extra_fields['sb_button_connector_style_icon_size'] = [
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
			'condition'  => [
				'connector_type' => [ 'icon' ],
			],
		];
		$fields = Fns::insert_controls( 'rtsb_el_sb_button_connector_style_typography', $fields, $extra_fields, true );
		$extra_fields2['sb_button_connector_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$extra_fields2['sb_button_connector_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'rtsb_el_sb_button_connector_style_border', $fields, $extra_fields2, true );
		$extra_controls3['sb_button_connector_style_size_note'] = $obj->el_heading(
			esc_html__( 'Size', 'shopbuilder' ),
			'before',
		);
		$extra_controls3['sb_button_style_connector_width']     = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Width', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['connector_width'],
		];
		$extra_controls3['sb_button_style_connector_height']    = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['connector_height'],
		];
		$fields = Fns::insert_controls( 'sb_button_connector_style_box_shadow', $fields, $extra_controls3, true );
		unset(
			$fields['sb_button_connector_style_alignment'],
			$fields['sb_button_connector_style_color_tabs'],
			$fields['sb_button_connector_style_color_tab'],
			$fields['sb_button_connector_style_color_tab_end'],
			$fields['sb_button_connector_style_hover_color_tab'],
			$fields['sb_button_connector_style_hover_color'],
			$fields['sb_button_connector_style_hover_bg_color'],
			$fields['sb_button_connector_style_hover_color_tab_end'],
			$fields['sb_button_connector_style_color_tabs_end'],
			$fields['sb_button_connector_style_border_hover_color'],
			$fields['sb_button_connector_style_padding'],
			$fields['sb_button_connector_style_spacing_note'],
			$fields['sb_button_connector_style_margin'],
		);

		return $fields;
	}
}

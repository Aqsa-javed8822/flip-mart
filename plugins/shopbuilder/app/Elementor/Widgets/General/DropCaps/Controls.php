<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\DropCaps;

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
			self::dropcaps_content( $obj ),
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
			self::content_style( $obj ),
			self::dropcap_letter_style( $obj ),
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
		$fields['dropcaps_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']     = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']    = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_dropcaps_layouts(),
			'default' => 'rtsb-dropcaps-layout1',
		];

		$fields['dropcaps_preset_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 * Content section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function dropcaps_content( $obj ) {
		$fields['dc_description']                 = $obj->start_section(
			esc_html__( 'General', 'shopbuilder' ),
			'content'
		);
		$fields['drop_content']                   = [
			'label'       => esc_html__( 'Content', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the dropcaps text.', 'shopbuilder' ),
			'type'        => 'wysiwyg',
			'label_block' => true,
			'default'     => sprintf( '<p>%s</p>', esc_html( __( 'ShopBuilder is a versatile plugin designed for Elementor Page Builder. This plugin helps you design and customize your online store easily, letting you create attractive product pages and improve the shopping experience for your customers.', 'shopbuilder' ) ) ),
		];
		$fields['enable_list_content_count']      = [
			'label'       => esc_html__( 'List Content Counter', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'render_type' => 'template',
			'separator'   => 'before',
		];
		$fields['enable_list_content_count_note'] = [
			'type' => 'html',
			'raw'  => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'To use the List Counter feature, you need to provide the content as a list.', 'shopbuilder' )
			),

		];
		$fields['cols'] = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row.', 'shopbuilder' ),
			'options'        => [
				1 => esc_html__( '1 Column', 'shopbuilder' ),
				2 => esc_html__( '2 Columns', 'shopbuilder' ),
				3 => esc_html__( '3 Columns', 'shopbuilder' ),
				4 => esc_html__( '4 Columns', 'shopbuilder' ),
				5 => esc_html__( '5 Columns', 'shopbuilder' ),
				6 => esc_html__( '6 Columns', 'shopbuilder' ),
			],
			'label_block'    => true,
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'selectors'      => [
				$obj->selectors['columns']['cols'] => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
			],
			'condition'      => [
				'enable_list_content_count' => [ 'yes' ],
			],
		];

		$fields['dc_description_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Description style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function content_style( $obj ) {
		$css_selectors = $obj->selectors['content_style'];
		$title         = esc_html__( 'Content Style', 'shopbuilder' );
		$selectors     = [
			'typography'    => $css_selectors['typography'],
			'color'         => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'      => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'        => $css_selectors['border'],
			'box_shadow'    => $css_selectors['box_shadow'],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'        => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'list_gap'      => [ $css_selectors['list_gap'] => 'grid-gap: {{SIZE}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'dc_content_style', $title, $obj, [], $selectors );

		unset(
			$fields['dc_content_style_alignment'],
			$fields['dc_content_style_color_tabs'],
			$fields['dc_content_style_color_tab'],
			$fields['dc_content_style_color_tab_end'],
			$fields['dc_content_style_hover_color_tab'],
			$fields['dc_content_style_hover_color'],
			$fields['dc_content_style_hover_bg_color'],
			$fields['dc_content_style_hover_color_tab_end'],
			$fields['dc_content_style_color_tabs_end'],
			$fields['dc_content_style_border_hover_color']
		);
		$extra_controls['dc_content_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$extra_controls['dc_content_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields                                       = Fns::insert_controls( 'dc_content_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['dc_content_style_list_gap'] = [
			'label'       => esc_html__( 'List Item Gap', 'shopbuilder' ),
			'type'        => 'slider',
			'mode'        => 'responsive',
			'size_units'  => [ 'px', '%', 'em' ],
			'range'       => [
				'px' => [
					'min' => 10,
					'max' => 200,
				],
			],
			'selectors'   => $selectors['list_gap'],
			'description' => esc_html__( 'If you are using list content then it will be working', 'shopbuilder' ),
		];
		return Fns::insert_controls( 'dc_content_style_spacing_note', $fields, $extra_controls2, true );
	}
	/**
	 * Dropcap Letter style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function dropcap_letter_style( $obj ) {
		$css_selectors = $obj->selectors['dropcap_letter_style'];
		$title         = esc_html__( 'Dropcap Letter', 'shopbuilder' );
		$selectors     = [
			'typography'    => $css_selectors['typography'],
			'text_shadow'   => $css_selectors['text_shadow'],
			'color'         => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'border'        => $css_selectors['border'],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'        => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'dc_dropcap_letter_style', $title, $obj, [], $selectors );

		unset(
			$fields['dc_dropcap_letter_style_alignment'],
			$fields['dc_dropcap_letter_style_color_tabs'],
			$fields['dc_dropcap_letter_style_bg_color'],
			$fields['dc_dropcap_letter_style_color_tab'],
			$fields['dc_dropcap_letter_style_color_tab_end'],
			$fields['dc_dropcap_letter_style_hover_color_tab'],
			$fields['dc_dropcap_letter_style_hover_color'],
			$fields['dc_dropcap_letter_style_hover_bg_color'],
			$fields['dc_dropcap_letter_style_hover_color_tab_end'],
			$fields['dc_dropcap_letter_style_color_tabs_end'],
			$fields['dc_dropcap_letter_style_border_hover_color']
		);
		$extra_controls['dc_dropcap_letter_style_text_shadow'] = [
			'type'     => 'text-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Letter Shadow', 'shopbuilder' ),
			'selector' => $selectors['text_shadow'],
		];
		$fields = Fns::insert_controls( 'rtsb_el_dc_dropcap_letter_style_typography', $fields, $extra_controls, true );
		$extra_controls2['dc_dropcap_letter_style_gradient_bg'] = [
			'label'     => esc_html__( 'Background', 'shopbuilder' ),
			'type'      => 'background',
			'mode'      => 'group',
			'selector'  => $selectors['gradient_bg'],
			'condition' => [
				'layout_style!' => [ 'rtsb-dropcaps-layout2' ],
			],
		];
		$fields = Fns::insert_controls( 'dc_dropcap_letter_style_color', $fields, $extra_controls2, true );
		$extra_controls3['dc_dropcap_letter_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
			'condition'  => [
				'layout_style!' => [ 'rtsb-dropcaps-layout2' ],
			],
		];
		$fields = Fns::insert_controls( 'dc_dropcap_letter_style_spacing_note', $fields, $extra_controls3 );
		$fields['dc_dropcap_letter_style_border_note']['condition']    = [
			'layout_style!' => [ 'rtsb-dropcaps-layout2' ],
		];
		$fields['rtsb_el_dc_dropcap_letter_style_border']['condition'] = [
			'layout_style!' => [ 'rtsb-dropcaps-layout2' ],
		];
		return $fields;
	}
}

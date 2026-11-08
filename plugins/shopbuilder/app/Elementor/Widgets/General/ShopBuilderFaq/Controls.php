<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderFaq;

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
			self::general_settings( $obj ),
			self::tab_icon_settings( $obj )
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
			self::accordion_style( $obj ),
			self::count_number_style( $obj ),
			self::title_style( $obj ),
			self::tab_icon_style( $obj ),
			self::content_style( $obj )
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
		$fields['sb_accordion_preset']     = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']             = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']            = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_sb_faq_layouts(),
			'default' => 'rtsb-sb-faq-layout1',
		];
		$fields['sb_accordion_preset_end'] = $obj->end_section();
		return $fields;
	}


	/**
	 * General section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function general_settings( $obj ) {
		$fields['general_settings_sec_start'] = $obj->start_section(
			esc_html__( 'General Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['sb_faq_type']                = [
			'type'      => 'choose',
			'label'     => esc_html__( 'FAQ Type', 'shopbuilder' ),
			'options'   => [
				'sb-accordion' => [
					'title' => esc_html__( 'Accordion', 'shopbuilder' ),
					'icon'  => 'eicon-accordion',
				],
				'sb-toggle'    => [
					'title' => esc_html__( 'Toggle', 'shopbuilder' ),
					'icon'  => 'eicon-toggle',
				],

			],
			'toggle'    => true,
			'default'   => 'sb-accordion',
			'condition' => [
				'layout_style' => [ 'rtsb-sb-faq-layout2', 'rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
			],
		];
		$fields['display_count'] = [
			'label'       => esc_html__( 'Display Count', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show count number.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
			'condition'   => [
				'layout_style!' => [ 'rtsb-sb-faq-layout2', 'rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
			],
		];
		$fields['sb_faq_items']  = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Faq Item', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ sb_faq_title }}}',
			'separator'   => 'after',
			'fields'      => [
				'sb_faq_title'   => [
					'label'       => esc_html__( 'Faq Title', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
					'default'     => __( 'Faq Item', 'shopbuilder' ),
				],
				'sb_faq_content' => [
					'label'       => esc_html__( 'Faq Content', 'shopbuilder' ),
					'type'        => 'wysiwyg',
					'label_block' => true,
					'default'     => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),
				],
				'sb_faq_count'   => [
					'label'       => esc_html__( 'Faq Count', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
				],
				'count_note'     => [
					'type' => 'html',
					'raw'  => sprintf(
						'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
						__( 'This count value will not show on accordion type FAQ.', 'shopbuilder' )
					),
				],

			],
			'default'     => [
				[
					'sb_faq_title'   => esc_html__( 'FAQ Item', 'shopbuilder' ),
					'sb_faq_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],
				[
					'sb_faq_title'   => esc_html__( 'FAQ Item', 'shopbuilder' ),
					'sb_faq_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],
				[
					'sb_faq_title'   => esc_html__( 'FAQ Item', 'shopbuilder' ),
					'sb_faq_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],

			],

		];
		$fields['sb_faq_title_html_tag']    = [
			'label'       => esc_html__( 'Faq Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['general_settings_sec_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Tab Icon section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function tab_icon_settings( $obj ) {
		$condition                    = [
			'layout_style' => [ 'rtsb-sb-faq-layout2','rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
		];
		$fields['tab_icon_sec_start'] = $obj->start_section(
			esc_html__( 'Tab Icon', 'shopbuilder' ),
			'content',
			[],
			$condition
		);
		$fields['display_tab_icon']   = [
			'label'       => esc_html__( 'Tab Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show title icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_tab_icon']        = [
			'label'     => esc_html__( 'Tab Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-chevron-down',
				'library' => 'fa-solid',
			],
			'condition' => [
				'display_tab_icon' => 'yes',
			],
		];
		$fields['sb_expanded_icon']   = [
			'label'     => esc_html__( 'Expanded Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-chevron-up',
				'library' => 'fa-solid',
			],
			'condition' => [
				'display_tab_icon' => 'yes',
			],
		];
		$fields['tab_icon_position']  = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Icon Position', 'shopbuilder' ),
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
			'default'   => 'left',
			'condition' => [
				'display_tab_icon' => 'yes',
			],
		];

		$fields['tab_icon_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function accordion_style( $obj ) {
		$condition     = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1' ],
		];
		$css_selectors = $obj->selectors['accordion_style'];
		$title         = esc_html__( 'FAQ Item', 'shopbuilder' );
		$selectors     = [
			'box_shadow'         => $css_selectors['box_shadow'],
			'gradient_bg'        => $css_selectors['gradient_bg'],
			'active_gradient_bg' => $css_selectors['active_gradient_bg'],
			'border'             => $css_selectors['border'],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'accordion_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['accordion_style_typo_note'],
			$fields['rtsb_el_accordion_style_typography'],
			$fields['accordion_style_color'],
			$fields['accordion_style_bg_color'],
			$fields['accordion_style_alignment'],
			$fields['accordion_style_color_tabs'],
			$fields['accordion_style_color_tab'],
			$fields['accordion_style_color_tab_end'],
			$fields['accordion_style_hover_color_tab'],
			$fields['accordion_style_hover_color'],
			$fields['accordion_style_hover_bg_color'],
			$fields['accordion_style_hover_color_tab_end'],
			$fields['accordion_style_color_tabs_end'],
			$fields['accordion_style_border_hover_color'],
			$fields['accordion_style_margin']
		);
		$fields['accordion_style_border_note']['separator'] = 'default';
		$fields['accordion_style_color_note']['separator']  = 'default';
		$extra_controls['accordion_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'accordion_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['accordion_style_gradient_bg']        = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['accordion_style_active_gradient_bg'] = [
			'label'          => esc_html__( 'Active Background Type', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'selector'       => $selectors['active_gradient_bg'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Active Background Type', 'shopbuilder' ),
				],
			],
			'condition'      => [
				'layout_style!' => [ 'rtsb-sb-faq-layout3', 'rtsb-sb-faq-layout5' ],
			],
		];
		$extra_controls2['accordion_style_box_shadow']         = [
			'type'      => 'box-shadow',
			'mode'      => 'group',
			'label'     => esc_html__( 'Wrapper Box Shadow', 'shopbuilder' ),
			'selector'  => $selectors['box_shadow'],
			'condition' => [
				'layout_style' => [ 'rtsb-sb-faq-layout3', 'rtsb-sb-faq-layout5' ],
			],
		];
		$fields = Fns::insert_controls( 'accordion_style_color_note', $fields, $extra_controls2, true );
		return $fields;
	}
	/**
	 * Count Number style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function count_number_style( $obj ) {
		$condition     = [
			'display_count' => 'yes',
			'layout_style!' => [ 'rtsb-sb-faq-layout2','rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
		];
		$css_selectors = $obj->selectors['count_number_style'];
		$title         = esc_html__( 'Count', 'shopbuilder' );
		$selectors     = [
			'box_shadow'         => $css_selectors['box_shadow'],
			'typography'         => $css_selectors['typography'],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'             => $css_selectors['border'],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'count_number_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['count_number_style_alignment'],
		);
		$fields['count_number_style_border_note']['separator'] = 'default';
		$fields['count_number_style_color_note']['separator']  = 'default';
		$extra_controls['count_number_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'count_number_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['count_number_style_box_shadow'] = [
			'type'      => 'box-shadow',
			'mode'      => 'group',
			'label'     => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector'  => $selectors['box_shadow'],
			'condition' => [
				'layout_style' => [ 'rtsb-sb-faq-layout5' ],
			],
		];
		$fields = Fns::insert_controls( 'count_number_style_border_note', $fields, $extra_controls2 );
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
		$title         = esc_html__( 'Title', 'shopbuilder' );
		$selectors     = [
			'typography'  => $css_selectors['typography'],
			'color'       => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'hover_color' => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'margin'      => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'title_style', $title, $obj, [], $selectors );
		unset(
			$fields['title_style_alignment'],
			$fields['rtsb_el_title_style_border'],
			$fields['title_style_border_note'],
			$fields['title_style_bg_color'],
			$fields['title_style_hover_bg_color'],
			$fields['title_style_border_hover_color'],
			$fields['title_style_padding']
		);
		$fields['title_style_hover_color_tab']['label']         = esc_html__( 'Active', 'shopbuilder' );
		$fields['title_style_hover_color']['label']             = esc_html__( 'Active Color', 'shopbuilder' );
		$fields['title_style_color_tabs']['condition']          = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_color_tab']['condition']           = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_color_tab_end']['condition']       = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_hover_color_tab']['condition']     = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_hover_color']['condition']         = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_hover_color_tab_end']['condition'] = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
		$fields['title_style_color_tabs_end']['condition']      = [
			'layout_style!' => [ 'rtsb-sb-faq-layout1', 'rtsb-sb-faq-layout3','rtsb-sb-faq-layout5' ],
		];
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
		$css_selectors = $obj->selectors['content_style'];
		$title         = esc_html__( 'Content', 'shopbuilder' );
		$selectors     = [
			'typography'  => $css_selectors['typography'],
			'color'       => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'gradient_bg' => $css_selectors['gradient_bg'],
			'margin'      => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'     => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'content_style', $title, $obj, [], $selectors );
		unset(
			$fields['content_style_alignment'],
			$fields['rtsb_el_content_style_border'],
			$fields['content_style_color_tabs'],
			$fields['content_style_color_tab'],
			$fields['content_style_color_tab_end'],
			$fields['content_style_border_note'],
			$fields['content_style_hover_color_tab'],
			$fields['content_style_hover_color'],
			$fields['content_style_bg_color'],
			$fields['content_style_hover_bg_color'],
			$fields['content_style_hover_color_tab_end'],
			$fields['content_style_color_tabs_end'],
			$fields['content_style_border_hover_color'],
		);
		$fields['content_style_padding']['condition'] = [
			'layout_style' => [ 'rtsb-sb-faq-layout2','rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
		];
		$extra_controls['content_style_gradient_bg']  = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$fields                                       = Fns::insert_controls( 'content_style_color', $fields, $extra_controls, true );
		return $fields;
	}
	/**
	 * Tab Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function tab_icon_style( $obj ) {
		$condition     = [
			'display_tab_icon' => 'yes',
			'layout_style'     => [ 'rtsb-sb-faq-layout2','rtsb-sb-faq-layout4','rtsb-sb-faq-layout6' ],
		];
		$css_selectors = $obj->selectors['tab_icon_style'];
		$title         = esc_html__( 'Tab Icon', 'shopbuilder' );
		$selectors     = [
			'tab_icon_size'       => [
				$css_selectors['tab_icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['tab_icon_size']['svg'] => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'color'               => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'            => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_bg_color'      => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'tab_icon_gap'        => [ $css_selectors['tab_icon_gap'] => 'gap: {{SIZE}}{{UNIT}};' ],
			'icon_width'          => [ $css_selectors['icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'icon_height'         => [ $css_selectors['icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'hover_color'         => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'border'              => $css_selectors['border'],
			'active_border_color' => [ $css_selectors['active_border_color'] => 'border-color: {{VALUE}};' ],
			'border_radius'       => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],

		];

		$fields = ControlHelper::general_elementor_style( 'tab_icon_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['tab_icon_style_alignment'],
			$fields['rtsb_el_tab_icon_style_typography'],
			$fields['tab_icon_style_border_hover_color'],
			$fields['tab_icon_style_padding'],
			$fields['tab_icon_style_margin']
		);
		$fields['tab_icon_style_hover_color_tab']['label'] = esc_html__( 'Active', 'shopbuilder' );
		$fields['tab_icon_style_hover_color']['label']     = esc_html__( 'Active Color', 'shopbuilder' );
		$fields['tab_icon_style_hover_bg_color']['label']  = esc_html__( 'Active Background', 'shopbuilder' );
		$extra_controls['tab_icon_size']                   = [
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
			'selectors'  => $selectors['tab_icon_size'],
		];
		$fields                             = Fns::insert_controls( 'tab_icon_style_color_note', $fields, $extra_controls );
		$extra_controls2['tab_icon_gap']    = [
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
			'selectors'  => $selectors['tab_icon_gap'],
		];
		$extra_controls2['tab_icon_width']  = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Width', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 30,
					'max' => 200,
				],
			],
			'selectors'  => $selectors['icon_width'],
		];
		$extra_controls2['tab_icon_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 30,
					'max' => 200,
				],
			],
			'selectors'  => $selectors['icon_height'],
		];
		$fields                             = Fns::insert_controls( 'tab_icon_style_spacing_note', $fields, $extra_controls2, true );
		$extra_controls3['tab_icon_active_border_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Active Border Color', 'shopbuilder' ),
			'selectors' => $selectors['active_border_color'],
		];
		$extra_controls3['tab_icon_border_radius']       = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'tab_icon_style_spacing_note', $fields, $extra_controls3 );
		return $fields;
	}
}

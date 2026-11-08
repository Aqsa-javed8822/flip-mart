<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion;

use Elementor\Utils;
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
			self::title_style( $obj ),
			self::content_style( $obj ),
			self::link_style( $obj ),
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
			'options' => ControlHelper::general_widgets_image_accordion_layouts(),
			'default' => 'rtsb-image-accordion-layout1',
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
		$fields['general_settings_note']      = $obj->el_heading( esc_html__( 'Accordion Settings', 'shopbuilder' ) );
		$fields['image_accordion_style']      = [
			'type'    => 'select',
			'label'   => esc_html__( 'Accordion Style', 'shopbuilder' ),
			'options' => [
				'horizontal' => esc_html__( 'Horizontal', 'shopbuilder' ),
				'vertical'   => esc_html__( 'Vertical', 'shopbuilder' ),
			],
			'default' => 'vertical',
		];
		$fields['accordion_active_type']      = [
			'type'    => 'select',
			'label'   => esc_html__( 'Active Type', 'shopbuilder' ),
			'options' => [
				'hover' => esc_html__( 'Hover', 'shopbuilder' ),
				'click' => esc_html__( 'Click', 'shopbuilder' ),
			],
			'default' => 'hover',
		];
		$fields['active_item']                = [
			'label'       => esc_html__( 'Active Item', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to active item', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['active_item_number']         = [
			'label'       => esc_html__( 'Item Number', 'shopbuilder' ),
			'description' => esc_html__( 'This value will be the active item number. ( item number must be equal or lower than total number ) ', 'shopbuilder' ),
			'type'        => 'number',
			'condition'   => [
				'active_item' => 'yes',
			],
			'default'     => 1,
		];
		$fields['accordion_title_html_tag']   = [
			'label'       => esc_html__( 'Accordion Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['accordion_note']             = $obj->el_heading( esc_html__( 'Accordion Items', 'shopbuilder' ), 'before' );
		$fields['image_accordion_items']      = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Accordion Items', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ accordion_title }}}',
			'separator'   => 'after',
			'fields'      => [
				'accordion_image'     => [
					'type'    => 'media',
					'label'   => esc_html__( 'Upload Image', 'shopbuilder' ),
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				],
				'accordion_title'     => [
					'label'       => esc_html__( 'Accordion Title', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
					'default'     => __( 'ShopBuilder Accordion Item', 'shopbuilder' ),
				],
				'enable_popup'        => [
					'label'       => esc_html__( 'Enable', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to active image popup.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'type'        => 'switch',
					'default'     => 'yes',
				],
				'popup_icon'          => [
					'label'     => esc_html__( 'Popup Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'fas fa-plus',
						'library' => 'fa-solid',
					],
					'condition' => [
						'enable_popup' => 'yes',
					],
				],
				'enable_project_link' => [
					'label'       => esc_html__( 'Enable Project Link', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to show project link.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'type'        => 'switch',
					'default'     => 'yes',
				],
				'project_link'        => [
					'label'       => esc_html__( 'Project Link', 'shopbuilder' ),
					'type'        => 'url',
					'label_block' => true,
					'condition'   => [
						'enable_project_link' => 'yes',
					],
				],
				'project_link_icon'   => [
					'label'     => esc_html__( 'Project Link Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'fas fa-link',
						'library' => 'fa-solid',
					],
					'condition' => [
						'enable_project_link' => 'yes',
					],
				],
				'accordion_content'   => [
					'label'       => esc_html__( 'Accordion Content', 'shopbuilder' ),
					'type'        => 'wysiwyg',
					'label_block' => true,
					'default'     => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),
				],

			],
			'default'     => [
				[
					'accordion_title'   => esc_html__( 'Accordion Item', 'shopbuilder' ),
					'accordion_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],
				[
					'accordion_title'   => esc_html__( 'Accordion Item', 'shopbuilder' ),
					'accordion_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],
				[
					'accordion_title'   => esc_html__( 'Accordion Item', 'shopbuilder' ),
					'accordion_content' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy', 'shopbuilder' ),

				],

			],

		];
		$fields['general_settings_sec_end'] = $obj->end_section();

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
		$css_selectors = $obj->selectors['accordion_style'];
		$title         = esc_html__( 'Accordion', 'shopbuilder' );
		$selectors     = [
			'overlay_color'        => $css_selectors['overlay_color'],
			'active_overlay_color' => $css_selectors['active_overlay_color'],
			'border'               => $css_selectors['border'],
			'border_radius'        => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'accordion_gap'        => [
				$css_selectors['accordion_gap']['gap']    => 'gap: {{SIZE}}{{UNIT}};',
				$css_selectors['accordion_gap']['margin'] => 'margin-bottom:{{SIZE}}{{UNIT}};',
			],
			'accordion_height'     => [ $css_selectors['accordion_height'] => 'height: {{SIZE}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'accordion_style', $title, $obj, [], $selectors );

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
			$fields['accordion_style_margin'],
			$fields['accordion_style_padding']
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
		$extra_controls2['accordion_style_overlay_color']        = [
			'label'          => esc_html__( 'Background', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['overlay_color'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background', 'shopbuilder' ),
				],
			],
		];
		$extra_controls2['accordion_style_active_overlay_color'] = [
			'label'          => esc_html__( 'Active Background Type', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['active_overlay_color'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Active Overlay Background', 'shopbuilder' ),
				],
			],
		];
		$fields = Fns::insert_controls( 'accordion_style_color_note', $fields, $extra_controls2, true );
		$extra_controls3['accordion_style_accordion_height'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Accordion Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 2000,
				],
			],
			'selectors'  => $selectors['accordion_height'],
		];
		$extra_controls3['accordion_style_spacing_gap']      = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Accordion Gap', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => $selectors['accordion_gap'],
		];
		$fields = Fns::insert_controls( 'accordion_style_spacing_note', $fields, $extra_controls3, true );
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
			'typography'      => $css_selectors['typography'],
			'number_typo'     => $css_selectors['number_typo'],
			'title_icon_size' => [
				$css_selectors['title_icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['title_icon_size']['svg'] => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'title_icon_gap'  => [ $css_selectors['title_icon_gap'] => 'margin-right: {{SIZE}}{{UNIT}};' ],
			'color'           => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'number_color'    => [ $css_selectors['number_color'] => 'color: {{VALUE}};' ],
			'icon_color'      => [ $css_selectors['icon_color'] => 'color: {{VALUE}};' ],
			'margin'          => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'title_style', $title, $obj, [], $selectors );
		unset(
			$fields['title_style_alignment'],
			$fields['rtsb_el_title_style_border'],
			$fields['title_style_border_note'],
			$fields['title_style_color_tabs'],
			$fields['title_style_color_tab'],
			$fields['title_style_color_tab_end'],
			$fields['title_style_hover_color_tab_end'],
			$fields['title_style_color_tabs_end'],
			$fields['title_style_bg_color'],
			$fields['title_style_hover_color'],
			$fields['title_style_hover_color_tab'],
			$fields['title_style_hover_bg_color'],
			$fields['title_style_border_hover_color'],
			$fields['title_style_padding']
		);
		$extra_controls['icon_color']       = [
			'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['icon_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-image-accordion-layout2' ],
			],
		];
		$extra_controls['number_color']     = [
			'label'     => esc_html__( 'Number Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['number_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-image-accordion-layout2' ],
			],
		];
		$fields                             = Fns::insert_controls( 'title_style_color', $fields, $extra_controls );
		$extra_controls2['title_icon_size'] = [
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
			'condition'  => [
				'layout_style' => [ 'rtsb-image-accordion-layout2' ],
			],
			'selectors'  => $selectors['title_icon_size'],
		];
		$fields                             = Fns::insert_controls( 'title_style_color_note', $fields, $extra_controls2 );
		$extra_controls3['title_icon_gap']  = [
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
			'condition'  => [
				'layout_style' => [ 'rtsb-image-accordion-layout2' ],
			],
			'selectors'  => $selectors['title_icon_gap'],
		];
		$fields                             = Fns::insert_controls( 'title_style_spacing_note', $fields, $extra_controls3, true );
		$extra_controls4['number_typo']     = [
			'label'     => esc_html__( 'Number Typography', 'shopbuilder' ),
			'mode'      => 'group',
			'type'      => 'typography',
			'condition' => [
				'layout_style' => [ 'rtsb-image-accordion-layout2' ],
			],
			'selector'  => $selectors['number_typo'],
		];
		$fields                             = Fns::insert_controls( 'rtsb_el_title_style_typography', $fields, $extra_controls4, true );
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
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
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
			$fields['content_style_padding'],
		);

		return $fields;
	}
	/**
	 * Content style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function link_style( $obj ) {
		$css_selectors = $obj->selectors['link_style'];
		$title         = esc_html__( 'Link', 'shopbuilder' );
		$selectors     = [
			'typography'     => $css_selectors['typography'],
			'icon_size'      => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'color'          => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'border'         => $css_selectors['border'],
			'hover_color'    => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'bg_color'       => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_bg_color' => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'margin'         => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'padding'        => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'border_radius'  => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields                                     = ControlHelper::general_elementor_style( 'link_style', $title, $obj, [], $selectors );
		$extra_controls['link_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields                                     = Fns::insert_controls( 'link_style_spacing_note', $fields, $extra_controls );
		unset(
			$fields['link_style_alignment'],
			$fields['link_style_border_hover_color'],
		);

		// Dedicated Icon Size control for the link icon — works for BOTH Font Awesome (`.link i` font-size)
		// and SVG (`.link svg` width/height), so the icon is sized independently of the link Typography.
		$extra_controls_icon['link_style_icon_size'] = [
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

		$fields = Fns::insert_controls( 'rtsb_el_link_style_typography', $fields, $extra_controls_icon, true );

		// The link is icon-only (no text label), so the Typography control + its heading are not needed.
		unset(
			$fields['link_style_typo_note'],
			$fields['rtsb_el_link_style_typography'],
		);

		return $fields;
	}
}

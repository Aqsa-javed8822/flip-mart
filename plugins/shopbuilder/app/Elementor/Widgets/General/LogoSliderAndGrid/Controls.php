<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\LogoSliderAndGrid;

use Elementor\Utils;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Widgets\Controls\SettingsFields;
use RadiusTheme\SB\Elementor\Widgets\Controls\StyleFields as StyleFieldsFree;

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
			self::slider_settings( $obj ),
			self::logo_items( $obj ),
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
			self::logo_item_style( $obj ),
			self::image_styles( $obj ),
			self::title_style( $obj ),
			self::logo_slider_buttons( $obj ),
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
		$status                        = ! rtsb()->has_pro();
		$fields['sb_accordion_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']         = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']        = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_logo_slider_grid_layouts(),
			'default' => 'rtsb-logo-layout1',
		];
		$fields['cols']                = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '5',
			'tablet_default' => '3',
			'mobile_default' => '2',
			'required'       => true,
			'render_type'    => 'template',
			'selectors'      => [
				$obj->selectors['columns']['cols'] => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
			],
			'separator'      => 'before',
		];
		$fields['rows']                = [
			'type'           => 'select2',
			'label'          => esc_html__( 'Number of Product Rows', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of slide rows. Slide Rows represents how many rows of slides will be displayed at once.', 'shopbuilder' ),
			'options'        => [
				1 => esc_html__( 'Layout Default (1 Row)', 'shopbuilder' ),
				2 => esc_html__( '2 Rows', 'shopbuilder' ),
				3 => esc_html__( '3 Rows', 'shopbuilder' ),
				4 => esc_html__( '4 Rows', 'shopbuilder' ),
				5 => esc_html__( '5 Rows', 'shopbuilder' ),
			],
			'label_block'    => true,
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
			'required'       => true,
			'classes'        => $obj->pro_class(),
			'is_pro'         => $status,
			'condition'      => [ 'activate_slider_item' => 'yes' ],
		];

		$fields['cols_group']              = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Slides Per Group', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of slides to show per group. Slides Per Group indicates how many slides will be transitioned at a time.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'condition'      => [ 'activate_slider_item' => 'yes' ],
		];
		$fields['element_height']          = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Element Height', 'shopbuilder' ),
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'description' => esc_html__( 'Please select the element height.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['element']['element_height'] => 'height: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['grid_gap']                = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Grid Gap / Spacing (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 30,
			],
			'description' => esc_html__( 'Please select the grid gap in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['grid_gap']['padding'] => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
				$obj->selectors['columns']['grid_gap']['margin'] => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
				$obj->selectors['columns']['grid_gap']['slider_layout'] => '--rtsb-logo-slider-spacing: {{SIZE}}{{UNIT}};',
				$obj->selectors['columns']['grid_gap']['bottom'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
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
		$fields['general_settings_sec_start']     = $obj->start_section(
			esc_html__( 'General Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['logo_settings_note']             = $obj->el_heading( esc_html__( 'Logo Settings', 'shopbuilder' ) );
		$fields['logo_gray_scale']                = [
			'label'       => esc_html__( 'Gray Scale Logo', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to logo gray scale effect.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['display_logo_name']              = [
			'label'       => esc_html__( 'Show Logo Brand Name?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show logo name.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['activate_slider_item']           = [
			'label'       => esc_html__( 'Activate Logo Slider?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to activate logo slider.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['logo_image_size_dimension_note'] = $obj->el_heading( esc_html__( 'Image Settings', 'shopbuilder' ), 'before' );
		$fields['logo_image_size']                = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
		];
		$fields['logo_img_dimension']             = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'condition'   => [
				'logo_image_size' => 'rtsb_custom',
			],
		];
		$fields['logo_img_crop']                  = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'logo_image_size' => 'rtsb_custom',
			],
		];
		$fields['logo_img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'logo_image_size' => 'rtsb_custom',
			],

		];
		$fields['general_settings_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Logo Items
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function logo_items( $obj ) {
		$fields['logo_items_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Logo Items', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['sb_logo_items']                 = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Logo Items', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ logo_name }}}',
			'fields'      => [
				'logo_image'      => [
					'type'    => 'media',
					'label'   => esc_html__( 'Upload Logo Image', 'shopbuilder' ),
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				],
				'logo_name'       => [
					'label'       => esc_html__( 'Enter Logo Brand Name', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
					'default'     => esc_html__( 'ShopBuilder', 'shopbuilder' ),
					'separator'   => 'before-short',
				],
				'enable_img_link' => [
					'label'       => esc_html__( 'Enable Image Link?', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to enable image link.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'type'        => 'switch',
					'separator'   => 'before-short',
				],
				'img_link'        => [
					'label'       => esc_html__( 'Enter URL', 'shopbuilder' ),
					'type'        => 'url',
					'label_block' => true,
					'condition'   => [
						'enable_img_link' => 'yes',
					],
				],

			],
		];
		$fields['logo_items_settings_sec_end'] = $obj->end_section();
		return $fields;
	}

	/**
	 * Slider section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function slider_settings( $obj ) {
		$fields = SettingsFields::slider_settings( $obj );
		unset( $fields['slider_nav_position'] );
		$fields['slider_control_section']['condition'] = [
			'activate_slider_item' => 'yes',
		];
		$fields['slider_control_section']['tab']       = 'content';
		return $fields;
	}

	/**
	 * Content Box style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function logo_item_style( $obj ) {

		$css_selectors = $obj->selectors['logo_item_style'];
		$title         = esc_html__( 'Logo Item', 'shopbuilder' );
		$selectors     = [
			'box_shadow'    => $css_selectors['box_shadow'],
			'border'        => $css_selectors['border'],
			'bg_color'      => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'logo_item_style', $title, $obj, [], $selectors );

		unset(
			$fields['logo_item_style_typo_note'],
			$fields['rtsb_el_logo_item_style_typography'],
			$fields['logo_item_style_color'],
			$fields['logo_item_style_alignment'],
			$fields['logo_item_style_color_tabs'],
			$fields['logo_item_style_color_tab'],
			$fields['logo_item_style_color_tab_end'],
			$fields['logo_item_style_hover_color_tab'],
			$fields['logo_item_style_hover_color'],
			$fields['logo_item_style_hover_bg_color'],
			$fields['logo_item_style_hover_color_tab_end'],
			$fields['logo_item_style_color_tabs_end'],
			$fields['logo_item_style_border_hover_color'],
			$fields['logo_item_style_margin']
		);
		$fields['logo_item_style_border_note']['separator'] = 'default';
		$fields['logo_item_style_color_note']['separator']  = 'default';
		$extra_controls['logo_item_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'logo_item_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['logo_item_style_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields                                        = Fns::insert_controls( 'logo_item_style_border_note', $fields, $extra_controls2 );

		// Layout 2 (Two-tone split card) has two separate background targets, so the
		// default full-wrapper "Background Color" is hidden for Layout 2 and replaced
		// with a "Logo Background" (logo image wrapper) and a "Brand Name Background"
		// (footer strip). Padding / Border-radius keep targeting the full wrapper.
		$fields['logo_item_style_bg_color']['condition'] = [ 'layout_style!' => [ 'rtsb-logo-layout2' ] ];

		$layout2_bg_controls = [
			'logo_item_style_logo_bg'       => [
				'type'      => 'color',
				'label'     => esc_html__( 'Logo Background', 'shopbuilder' ),
				'condition' => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors' => [ $css_selectors['logo_bg'] => 'background-color: {{VALUE}};' ],
				'separator' => 'default',
			],
			'logo_item_style_brand_name_bg' => [
				'type'      => 'color',
				'label'     => esc_html__( 'Brand Name Background', 'shopbuilder' ),
				'condition' => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors' => [ $css_selectors['brand_name_bg'] => 'background-color: {{VALUE}};' ],
				'separator' => 'default',
			],
		];
		$fields              = Fns::insert_controls( 'logo_item_style_bg_color', $fields, $layout2_bg_controls, true );

		// Layout 2 brand-name (footer) hover + border controls (separate, conditional).
		// The green hover stays as the default until these are set.
		$brand_name_extra = [
			'logo_item_style_brand_name_hover_bg'     => [
				'type'      => 'color',
				'label'     => esc_html__( 'Brand Name Hover Background Color', 'shopbuilder' ),
				'condition' => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors' => [ $css_selectors['brand_name_hover'] => 'background-color: {{VALUE}};' ],
				'separator' => 'default',
			],
			'logo_item_style_brand_name_hover_color'  => [
				'type'      => 'color',
				'label'     => esc_html__( 'Brand Name Hover Text Color', 'shopbuilder' ),
				'condition' => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors' => [ $css_selectors['brand_name_hover'] => 'color: {{VALUE}};' ],
				'separator' => 'default',
			],
			'logo_item_style_brand_name_border'       => [
				'mode'           => 'group',
				'type'           => 'border',
				'label'          => esc_html__( 'Brand Name Border', 'shopbuilder' ),
				'condition'      => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selector'       => $css_selectors['brand_name_bg'],
				'fields_options' => [
					'color' => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'separator'      => 'default',
			],
			'logo_item_style_brand_name_hover_border' => [
				'type'      => 'color',
				'label'     => esc_html__( 'Brand Name Hover Border Color', 'shopbuilder' ),
				'condition' => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors' => [ $css_selectors['brand_name_hover'] => 'border-color: {{VALUE}};' ],
				'separator' => 'default',
			],
		];
		$fields           = Fns::insert_controls( 'logo_item_style_brand_name_bg', $fields, $brand_name_extra, true );

		// Layout 2 padding targets the logo wrapper and the brand-name footer
		// together (not the full wrapper). Layout 1 keeps padding on the full wrapper.
		$fields['logo_item_style_padding']['condition'] = [ 'layout_style!' => [ 'rtsb-logo-layout2' ] ];

		$layout2_padding = [
			'logo_item_style_l2_padding' => [
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'size_units' => [ 'px', '%', 'em' ],
				'condition'  => [ 'layout_style' => 'rtsb-logo-layout2' ],
				'selectors'  => [
					$css_selectors['logo_bg']       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$css_selectors['brand_name_bg'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'default',
			],
		];
		$fields          = Fns::insert_controls( 'logo_item_style_padding', $fields, $layout2_padding, true );

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
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'title_style', $title, $obj, [], $selectors );
		unset(
			$fields['title_style_alignment'],
			$fields['rtsb_el_title_style_border'],
			$fields['title_style_border_note'],
			$fields['title_style_color_tabs'],
			$fields['title_style_color_tab'],
			$fields['title_style_color_tab_end'],
			$fields['title_style_hover_color_tab'],
			$fields['title_style_bg_color'],
			$fields['title_style_hover_color'],
			$fields['title_style_hover_bg_color'],
			$fields['title_style_border_hover_color'],
			$fields['title_style_padding'],
			$fields['title_style_hover_color_tab_end'],
			$fields['title_style_color_tabs_end'],
		);
		return $fields;
	}
	/**
	 * Image style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function image_styles( $obj ) {
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['image_style_sec_start']       = $obj->start_section(
			esc_html__( 'Image', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['image_styles_dimension_note'] = $obj->el_heading( esc_html__( 'Dimension', 'shopbuilder' ) );

		$fields['image_styles_width']     = [
			'type'       => 'slider',
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
			'selectors'  => [
				$obj->selectors['image_style']['width'] => 'width: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_styles_max_width'] = [
			'type'       => 'slider',
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
			'selectors'  => [
				$obj->selectors['image_style']['max_width'] => 'max-width: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_border_note']         = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'before' );
		$fields['image_border']              = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $obj->selectors['image_style']['border'],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];
		$fields['image_styles_radius']       = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				$obj->selectors['image_style']['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];
		$fields['image_styles_spacing_note'] = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ) );
		$fields['image_styles_margin']       = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				$obj->selectors['image_style']['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];
		$fields['image_sec_end']             = $obj->end_section();

		return $fields;
	}
	/**
	 * Slider Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function logo_slider_buttons( $obj ) {
		$slider_btn_settings = StyleFieldsFree::slider_buttons( $obj );
		$slider_btn_settings['slider_buttons_style_section']['condition'] = [
			'activate_slider_item' => 'yes',
		];
		return $slider_btn_settings;
	}
}

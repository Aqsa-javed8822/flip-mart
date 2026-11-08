<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Testimonial;

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
			self::testimonial_items( $obj ),
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
			self::testimonial_item_style( $obj ),
			self::image_styles( $obj ),
			self::title_style( $obj ),
			self::rating_style( $obj ),
			self::content_style( $obj ),
			self::designation_style( $obj ),
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
		$status                          = ! rtsb()->has_pro();
		$fields['sb_testimonial_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']           = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']          = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_testimonial(),
			'default' => 'rtsb-testimonial-layout1',
		];
		$fields['cols']                  = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '3',
			'mobile_default' => '2',
			'required'       => true,
			'render_type'    => 'template',
			'selectors'      => [
				$obj->selectors['columns']['cols'] => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
			],
			'separator'      => 'before',
		];
		$fields['rows']                  = [
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

		$fields['cols_group']                = [
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
		$fields['element_height']            = [
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
		$fields['grid_gap']                  = [
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
				$obj->selectors['columns']['grid_gap']['slider_layout'] => ' --rtsb-testimonial-slider-spacing: {{SIZE}}{{UNIT}};',
				$obj->selectors['columns']['grid_gap']['bottom'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['sb_testimonial_preset_end'] = $obj->end_section();
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
		$fields['general_settings_sec_start']       = $obj->start_section(
			esc_html__( 'General Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['general_settings_note']            = $obj->el_heading( esc_html__( 'Testimonial Settings', 'shopbuilder' ) );
		$fields['author_name_html_tag']             = [
			'label'       => esc_html__( 'Author Name Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the name HTML tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['display_quote_icon']               = [
			'label'       => esc_html__( 'Show Quote Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display quote icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'separator'   => 'before',
			'condition'   => [
				'layout_style!' => [ 'rtsb-testimonial-layout4' ],
			],
		];
		$fields['display_author_image']             = [
			'label'       => esc_html__( 'Show Author Image?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show author image.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['display_author_designation']       = [
			'label'       => esc_html__( 'Show Author Designation?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show author designation.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['display_author_rating']            = [
			'label'       => esc_html__( 'Show Author Rating?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show author rating.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['activate_slider_item']             = [
			'label'       => esc_html__( 'Activate Testimonial Slider', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to activate testimonial slider.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['author_image_size_dimension_note'] = $obj->el_heading( esc_html__( 'Image Settings', 'shopbuilder' ), 'before' );
		$fields['author_image_size']                = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
		];
		$fields['author_img_dimension']             = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'condition'   => [
				'author_image_size' => 'rtsb_custom',
			],
		];
		$fields['author_img_crop']                  = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'author_image_size' => 'rtsb_custom',
			],
		];
		$fields['author_img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'author_image_size' => 'rtsb_custom',
			],

		];
		$fields['general_settings_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Testimonial items
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function testimonial_items( $obj ) {
		$fields['testimonial_items_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Testimonial Items', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['testimonial_items']                    = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Testimonial Items', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ author_name }}}',
			'fields'      => [
				'author_image'       => [
					'type'    => 'media',
					'label'   => esc_html__( 'Upload Author Image', 'shopbuilder' ),
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				],
				'author_name'        => [
					'label'       => esc_html__( 'Author Name', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
					'default'     => esc_html__( 'Kristin Watson', 'shopbuilder' ),
					'separator'   => 'before-short',
				],
				'author_designation' => [
					'label'       => esc_html__( 'Author Designation', 'shopbuilder' ),
					'type'        => 'text',
					'label_block' => true,
					'default'     => esc_html__( 'Plugin Developer', 'shopbuilder' ),
				],
				'author_rating'      => [
					'label'   => esc_html__( 'Author Rating', 'shopbuilder' ),
					'type'    => 'select',
					'options' => [
						'1' => esc_html__( 'Rating 1', 'shopbuilder' ),
						'2' => esc_html__( 'Rating 2', 'shopbuilder' ),
						'3' => esc_html__( 'Rating 3', 'shopbuilder' ),
						'4' => esc_html__( 'Rating 4', 'shopbuilder' ),
						'5' => esc_html__( 'Rating 5', 'shopbuilder' ),
					],
					'default' => '5',
				],
				'author_description' => [
					'label'       => esc_html__( 'Author Description', 'shopbuilder' ),
					'type'        => 'wysiwyg',
					'label_block' => true,
					'default'     => esc_html__( 'Theme is nice, i almost finished integrating it in our nft guild website, need to dev one custom plugin then some adjustment and i’m done.', 'shopbuilder' ),
					'separator'   => 'before-short',
				],
			],
			'default'     => [
				[
					'author_name'        => esc_html__( 'Kristin Watson', 'shopbuilder' ),
					'author_designation' => __( 'Senior Artist', 'shopbuilder' ),
					'author_description' => __( 'I love how they keep updating it. even though i am not using the website, i can say after buying many themes, this one was best.', 'shopbuilder' ),

				],
				[
					'author_name'        => esc_html__( 'Leslie Alexander', 'shopbuilder' ),
					'author_designation' => __( 'Software Developer', 'shopbuilder' ),
					'author_description' => __( 'Theme is nice, i almost finished integrating it in our nft guild website, need to dev one custom plugin then some adjustment and i’m done.', 'shopbuilder' ),

				],
				[
					'author_name'        => esc_html__( 'Darrell Steward', 'shopbuilder' ),
					'author_designation' => __( 'Software Developer', 'shopbuilder' ),
					'author_description' => __( 'Theme is nice, i almost finished integrating it in our nft guild website, need to dev one custom plugin then some adjustment and i’m done.', 'shopbuilder' ),

				],
				[
					'author_name'        => esc_html__( 'John Doe', 'shopbuilder' ),
					'author_designation' => __( 'Software Developer', 'shopbuilder' ),
					'author_description' => __( 'Theme is nice, i almost finished integrating it in our nft guild website, need to dev one custom plugin then some adjustment and i’m done.', 'shopbuilder' ),
				],

			],
		];
		$fields['testimonial_items_settings_sec_end'] = $obj->end_section();

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
	private static function testimonial_item_style( $obj ) {

		$css_selectors = $obj->selectors['testimonial_item_style'];
		$title         = esc_html__( 'Testimonial Item', 'shopbuilder' );
		$selectors     = [
			'box_shadow'    => $css_selectors['box_shadow'],
			'border'        => $css_selectors['border'],
			'bg_color'      => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'testimonial_item_style', $title, $obj, [], $selectors );

		unset(
			$fields['testimonial_item_style_typo_note'],
			$fields['rtsb_el_testimonial_item_style_typography'],
			$fields['testimonial_item_style_color'],
			$fields['testimonial_item_style_alignment'],
			$fields['testimonial_item_style_color_tabs'],
			$fields['testimonial_item_style_color_tab'],
			$fields['testimonial_item_style_color_tab_end'],
			$fields['testimonial_item_style_hover_color_tab'],
			$fields['testimonial_item_style_hover_color'],
			$fields['testimonial_item_style_hover_bg_color'],
			$fields['testimonial_item_style_hover_color_tab_end'],
			$fields['testimonial_item_style_color_tabs_end'],
			$fields['testimonial_item_style_border_hover_color'],
			$fields['testimonial_item_style_margin']
		);
		$fields['testimonial_item_style_border_note']['separator'] = 'default';
		$fields['testimonial_item_style_color_note']['separator']  = 'default';
		$extra_controls['testimonial_item_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'testimonial_item_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['rtsb_testimonial_item_style_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'testimonial_item_style_border_note', $fields, $extra_controls2 );
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
	 * Rating style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function rating_style( $obj ) {
		$css_selectors = $obj->selectors['rating_style'];
		$title         = esc_html__( 'Rating', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'empty_rating_color' => [ $css_selectors['empty_rating_color'] => 'color: {{VALUE}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'rating_style', $title, $obj, [], $selectors );
		unset(
			$fields['rating_style_alignment'],
			$fields['rtsb_el_rating_style_border'],
			$fields['rating_style_border_note'],
			$fields['rating_style_color_tabs'],
			$fields['rating_style_color_tab'],
			$fields['rating_style_color_tab_end'],
			$fields['rating_style_hover_color_tab'],
			$fields['rating_style_bg_color'],
			$fields['rating_style_hover_color'],
			$fields['rating_style_hover_bg_color'],
			$fields['rating_style_border_hover_color'],
			$fields['rating_style_padding'],
			$fields['rating_style_hover_color_tab_end'],
			$fields['rating_style_color_tabs_end'],
		);
		$fields['rtsb_el_rating_style_typography']['exclude'] = [ 'font_family','font_weight','text_transform', 'text_decoration', 'font_style', 'word_spacing' ]; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		$extra_controls['rating_empty_color']                 = [
			'label'     => __( 'Empty Rating Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['empty_rating_color'],
		];
		$fields = Fns::insert_controls( 'rating_style_color', $fields, $extra_controls, true );
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
			'typography'       => $css_selectors['typography'],
			'quote_icon_size'  => [ $css_selectors['quote_icon_size'] => 'font-size:{{SIZE}}{{UNIT}}' ],
			'color'            => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'quote_icon_bg'    => [ $css_selectors['quote_icon_bg'] => 'background-color: {{VALUE}};' ],
			'quote_icon_color' => [ $css_selectors['quote_icon_color'] => 'color: {{VALUE}};' ],
			'margin'           => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'content_style', $title, $obj, [], $selectors );
		unset(
			$fields['content_style_alignment'],
			$fields['rtsb_el_content_style_border'],
			$fields['content_style_border_note'],
			$fields['content_style_color_tabs'],
			$fields['content_style_color_tab'],
			$fields['content_style_color_tab_end'],
			$fields['content_style_hover_color_tab'],
			$fields['content_style_bg_color'],
			$fields['content_style_hover_color'],
			$fields['content_style_hover_bg_color'],
			$fields['content_style_border_hover_color'],
			$fields['content_style_padding'],
			$fields['content_style_hover_color_tab_end'],
			$fields['content_style_color_tabs_end'],
		);
		$quote_icon_size_control = [
			'content_style_quote_icon_size' => [
				'type'       => 'slider',
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Quote Icon Size', 'shopbuilder' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors'  => $selectors['quote_icon_size'],
				'condition'  => [
					'layout_style!'      => [ 'rtsb-testimonial-layout4' ],
					'display_quote_icon' => 'yes',
				],
			],
		];
		$fields                  = Fns::insert_controls( 'content_style_color_note', $fields, $quote_icon_size_control );

		$quote_icon_color_controls = [
			'content_style_quote_icon_color'    => [
				'type'      => 'color',
				'label'     => esc_html__( 'Quote Icon Color', 'shopbuilder' ),
				'selectors' => $selectors['quote_icon_color'],
				'condition' => [
					'layout_style!'      => [ 'rtsb-testimonial-layout4' ],
					'display_quote_icon' => 'yes',
				],
			],
			'content_style_quote_icon_bg_color' => [
				'type'      => 'color',
				'label'     => esc_html__( 'Quote Icon Background', 'shopbuilder' ),
				'selectors' => $selectors['quote_icon_bg'],
				'condition' => [
					'layout_style'       => [ 'rtsb-testimonial-layout5' ],
					'display_quote_icon' => 'yes',
				],
			],
		];
		$fields                    = Fns::insert_controls( 'content_style_color', $fields, $quote_icon_color_controls, true );
		return $fields;
	}
	/**
	 * Designation style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function designation_style( $obj ) {
		$css_selectors = $obj->selectors['designation_style'];
		$title         = esc_html__( 'Designation', 'shopbuilder' );
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'designation_style', $title, $obj, [], $selectors );
		unset(
			$fields['designation_style_alignment'],
			$fields['rtsb_el_designation_style_border'],
			$fields['designation_style_border_note'],
			$fields['designation_style_color_tabs'],
			$fields['designation_style_color_tab'],
			$fields['designation_style_color_tab_end'],
			$fields['designation_style_hover_color_tab'],
			$fields['designation_style_bg_color'],
			$fields['designation_style_hover_color'],
			$fields['designation_style_hover_bg_color'],
			$fields['designation_style_border_hover_color'],
			$fields['designation_style_padding'],
			$fields['designation_style_hover_color_tab_end'],
			$fields['designation_style_color_tabs_end'],
		);
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
	/**
	 * Image style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function image_styles( $obj ) {
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
					'max'  => 500,
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
					'max'  => 500,
					'step' => 1,
				],

			],
			'selectors'  => [
				$obj->selectors['image_style']['max_width'] => 'max-width: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_border_note']   = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'before' );
		$fields['image_border']        = [
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
		$fields['image_styles_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				$obj->selectors['image_style']['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];
		$fields['image_sec_end']       = $obj->end_section();

		return $fields;
	}
}

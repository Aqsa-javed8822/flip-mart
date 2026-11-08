<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\TeamMember;

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
			self::team_member_items( $obj ),
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
			self::team_item_style( $obj ),
			self::image_styles( $obj ),
			self::title_style( $obj ),
			self::content_style( $obj ),
			self::designation_style( $obj ),
			self::social_icon_style( $obj ),
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
		$status                   = ! rtsb()->has_pro();
		$fields['sb_team_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']    = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']   = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_team_member(),
			'default' => 'rtsb-team-layout1',
		];
		$fields['cols']           = [
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
		$fields['rows']           = [
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

		$fields['cols_group']         = [
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
		$fields['element_height']     = [
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
		$fields['grid_gap']           = [
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
				$obj->selectors['columns']['grid_gap']['slider_layout'] => ' --rtsb-team-slider-spacing: {{SIZE}}{{UNIT}};',
				$obj->selectors['columns']['grid_gap']['bottom'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		];
		$fields['sb_team_preset_end'] = $obj->end_section();
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
		$fields['social_heading']                   = $obj->el_heading( esc_html__( 'Team Member Settings', 'shopbuilder' ) );
		$fields['member_name_html_tag']             = [
			'label'       => esc_html__( 'Member Name Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the name tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['display_member_designation']       = [
			'label'       => esc_html__( 'Show Team Designation?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show member designation.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
			'separator'   => 'before',
		];
		$fields['display_member_description']       = [
			'label'       => esc_html__( 'Show Team Description?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show member description.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'separator'   => 'before',
		];
		$fields['display_social_icon']              = [
			'label'       => esc_html__( 'Show Social Icons?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show member social icons.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['image_linkable']                   = [
			'label'       => esc_html__( 'Image Linkable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to activate image linkable.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['member_name_linkable']             = [
			'label'       => esc_html__( 'Team Name Linkable?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to activate member name linkable.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['activate_slider_item']             = [
			'label'       => esc_html__( 'Activate Team Slider?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to activate logo slider.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['member_image_size_dimension_note'] = $obj->el_heading( esc_html__( 'Image Settings', 'shopbuilder' ), 'before' );
		$fields['member_image_size']                = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
		];
		$fields['member_img_dimension']             = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'default'     => [
				'width'  => 400,
				'height' => 400,
			],
			'condition'   => [
				'member_image_size' => 'rtsb_custom',
			],
		];
		$fields['member_img_crop']                  = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'member_image_size' => 'rtsb_custom',
			],
		];
		$fields['member_img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'member_image_size' => 'rtsb_custom',
			],
		];
		$fields['general_settings_sec_end']         = $obj->end_section();

		return $fields;
	}
	/**
	 * Team items
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function team_member_items( $obj ) {
		$fields['team_items_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Team Members', 'shopbuilder' ),
			'content',
			[],
		);
		$repeater_fields                         = [
			'member_image'       => [
				'type'    => 'media',
				'label'   => esc_html__( 'Upload Member Image', 'shopbuilder' ),
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			],
			'member_name'        => [
				'label'       => esc_html__( 'Member Name', 'shopbuilder' ),
				'type'        => 'text',
				'label_block' => true,
				'default'     => esc_html__( 'Kristin Watson', 'shopbuilder' ),
				'separator'   => 'before-short',
			],
			'member_designation' => [
				'label'       => esc_html__( 'Member Designation', 'shopbuilder' ),
				'type'        => 'text',
				'label_block' => true,
				'default'     => esc_html__( 'Plugin Developer', 'shopbuilder' ),
			],
			'member_bio'         => [
				'label'       => esc_html__( 'Member Bio', 'shopbuilder' ),
				'type'        => 'wysiwyg',
				'label_block' => true,
				'default'     => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting has been the industry standard dummy text ever since.', 'shopbuilder' ),
				'separator'   => 'before-short',
			],
			'link_heading'       => $obj->el_heading( esc_html__( 'Team Member Link', 'shopbuilder' ), 'before-short' ),
			'image_link'         => [
				'label' => esc_html__( 'Image Link', 'shopbuilder' ),
				'type'  => 'url',
			],
			'member_name_link'   => [
				'label' => esc_html__( 'Title Link', 'shopbuilder' ),
				'type'  => 'url',
			],
			'social_heading'     => $obj->el_heading( esc_html__( 'Social Links', 'shopbuilder' ), 'before-short' ),

		];
		$repeater_fields                      = array_merge(
			$repeater_fields,
			ControlHelper::social_media_field()
		);
		$fields['general_settings_sec_start'] = $obj->start_section(
			esc_html__( 'Settings', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['team_member_items']          = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Team Members', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ member_name }}}',
			'separator'   => 'after',
			'fields'      => $repeater_fields,
			'default'     => [
				[
					'member_name'        => esc_html__( 'Kristin Watson', 'shopbuilder' ),
					'member_designation' => __( 'Senior Artist', 'shopbuilder' ),
					'member_bio'         => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting has been the industry standard dummy text ever since.', 'shopbuilder' ),
				],
				[
					'member_name'        => esc_html__( 'Leslie Alexander', 'shopbuilder' ),
					'member_designation' => __( 'Software Developer', 'shopbuilder' ),
					'member_bio'         => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting has been the industry standard dummy text ever since.', 'shopbuilder' ),
				],
				[
					'member_name'        => esc_html__( 'Darrell Steward', 'shopbuilder' ),
					'member_designation' => __( 'UX Designer', 'shopbuilder' ),
					'member_bio'         => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting has been the industry standard dummy text ever since.', 'shopbuilder' ),
				],
				[
					'member_name'        => esc_html__( 'John Doe', 'shopbuilder' ),
					'member_designation' => __( 'Junior Executive', 'shopbuilder' ),
					'member_bio'         => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting has been the industry standard dummy text ever since.', 'shopbuilder' ),
				],

			],
		];
		$fields['team_items_settings_sec_end'] = $obj->end_section();
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
	private static function team_item_style( $obj ) {

		$css_selectors = $obj->selectors['team_item_style'];
		$title         = esc_html__( 'Team Member Item', 'shopbuilder' );
		$selectors     = [
			'box_shadow'    => $css_selectors['box_shadow'],
			'border'        => $css_selectors['border'],
			'bg_color'      => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'team_item_style', $title, $obj, [], $selectors );

		unset(
			$fields['team_item_style_typo_note'],
			$fields['rtsb_el_team_item_style_typography'],
			$fields['team_item_style_color'],
			$fields['team_item_style_alignment'],
			$fields['team_item_style_color_tabs'],
			$fields['team_item_style_color_tab'],
			$fields['team_item_style_color_tab_end'],
			$fields['team_item_style_hover_color_tab'],
			$fields['team_item_style_hover_color'],
			$fields['team_item_style_hover_bg_color'],
			$fields['team_item_style_hover_color_tab_end'],
			$fields['team_item_style_color_tabs_end'],
			$fields['team_item_style_border_hover_color'],
			$fields['team_item_style_margin']
		);
		$fields['team_item_style_border_note']['separator'] = 'default';
		$fields['team_item_style_color_note']['separator']  = 'default';
		$extra_controls['team_item_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'team_item_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['rtsb_team_item_style_box_shadow'] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'team_item_style_border_note', $fields, $extra_controls2 );
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
			'color'           => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'separator_color' => [ $css_selectors['separator_color'] => 'background-color: {{VALUE}};' ],
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
			$fields['title_style_hover_color_tab'],
			$fields['title_style_bg_color'],
			$fields['title_style_hover_color'],
			$fields['title_style_hover_bg_color'],
			$fields['title_style_border_hover_color'],
			$fields['title_style_padding'],
			$fields['title_style_hover_color_tab_end'],
			$fields['title_style_color_tabs_end'],
		);
		$extra_controls['title_style_separator_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Separator Color', 'shopbuilder' ),
			'selectors' => $selectors['separator_color'],
			'condition' => [
				'layout_style' => [ 'rtsb-team-layout4' ],
			],
		];
		$fields                                        = Fns::insert_controls( 'title_style_spacing_note', $fields, $extra_controls );
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
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['image_style_sec_start']       = $obj->start_section(
			esc_html__( 'Image', 'shopbuilder' ),
			'style',
			[],
		);
		$fields['image_hover_effect']          = [
			'type'        => 'select',
			'label'       => esc_html__( 'Image Hover Effect', 'shopbuilder' ),
			'options'     => self::team_member_image_hover_effect(),
			'default'     => 'rtsb-gw-img-zoom-in',
			'label_block' => true,
		];
		$fields['image_alignment']             = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Image Alignment', 'shopbuilder' ),
			'options'   => $alignment_options,
			'separator' => 'before',
			'selectors' => [ $obj->selectors['image_style']['alignment'] => 'text-align: {{VALUE}};justify-content: {{VALUE}};' ],
			'condition' => [
				'layout_style!' => [ 'rtsb-team-layout3','rtsb-team-layout4' ],
			],
		];
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
				$obj->selectors['image_style']['flex_width'] => 'flex: 0 0 {{SIZE}}{{UNIT}};',
			],
		];
		$fields['image_styles_height']    = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Image Height', 'shopbuilder' ),
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
				$obj->selectors['image_style']['height'] => 'height: {{SIZE}}{{UNIT}};object-fit:cover;',
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
	 * Social Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function social_icon_style( $obj ) {
		$css_selectors = $obj->selectors['social_icon_style'];
		$title         = esc_html__( 'Social Icon', 'shopbuilder' );
		$selectors     = [
			'icon_size'          => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'color'              => [ $css_selectors['color'] => 'fill: {{VALUE}};' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'fill: {{VALUE}};' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;' ],
			'icon_width'         => [ $css_selectors['icon_width'] => 'width: {{SIZE}}{{UNIT}}' ],
			'icon_height'        => [ $css_selectors['icon_height'] => 'height: {{SIZE}}{{UNIT}}' ],
			'icon_gap'           => [ $css_selectors['icon_gap'] => 'gap: {{SIZE}}{{UNIT}}' ],
		];

		$fields = ControlHelper::general_elementor_style( 'social_icon_style', $title, $obj, [], $selectors );
		unset(
			$fields['social_icon_style_alignment'],
			$fields['rtsb_el_social_icon_style_typography'],
			$fields['social_icon_style_padding'],
		);
		$extra_controls['social_icon_size'] = [
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
		$fields                             = Fns::insert_controls( 'social_icon_style_typo_note', $fields, $extra_controls, true );
		$extra_fields2['social_icon_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'rtsb_el_social_icon_style_border', $fields, $extra_fields2, true );
		$extra_controls3['social_icon_style_size_note'] = $obj->el_heading(
			esc_html__( 'Size', 'shopbuilder' ),
			'before',
		);
		$extra_controls3['social_icon_style_width']     = [
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
			'selectors'  => $selectors['icon_width'],
		];
		$extra_controls3['social_icon_style_height']    = [
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
			'selectors'  => $selectors['icon_height'],
		];
		$fields                                        = Fns::insert_controls( 'social_icon_style_spacing_note', $fields, $extra_controls3 );
		$extra_controls4['social_icon_style_icon_gap'] = [
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
		$fields                                        = Fns::insert_controls( 'social_icon_style_spacing_note', $fields, $extra_controls4, true );
		return $fields;
	}


	/**
	 * Retrieves the available image hover effects for team member widgets.
	 *
	 * The effects include options such as 'None', 'Scale In', 'Scale Out', 'Slide Up', 'Slide Down',
	 * 'Slide Right', and 'Slide Left'. These effects are customizable through the 'rtsb/general/widget/team_member_image_hover_effect' filter.
	 *
	 * @return array Associative array of image hover effect keys.
	 */
	public static function team_member_image_hover_effect() {
		return apply_filters(
			'rtsb/general/widget/team_member_image_hover_effect',
			[
				'rtsb-gw-img-effect-none' => __( 'None', 'shopbuilder' ),
				'rtsb-gw-img-zoom-in'     => __( 'Scale In', 'shopbuilder' ),
				'rtsb-gw-img-zoom-out'    => __( 'Scale Out', 'shopbuilder' ),
				'rtsb-gw-img-slide-up'    => __( 'Slide Up', 'shopbuilder' ),
				'rtsb-gw-img-slide-down'  => __( 'Slide Down', 'shopbuilder' ),
				'rtsb-gw-img-slide-right' => __( 'Slide Right', 'shopbuilder' ),
				'rtsb-gw-img-slide-left'  => __( 'Slide Left', 'shopbuilder' ),
			]
		);
	}
}

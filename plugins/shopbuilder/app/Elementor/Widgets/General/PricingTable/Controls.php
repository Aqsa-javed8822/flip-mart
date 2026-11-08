<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PricingTable;

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
			self::sb_pricing_table_general_section( $obj ),
			self::sb_pricing_table_feature_section( $obj ),
			self::pricing_table_icon( $obj ),
			self::sb_pricing_table_pricing_section( $obj ),
			self::pricing_table_button( $obj ),
			self::sb_pricing_table_badge_section( $obj ),
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
			self::pricing_table_price_style( $obj ),
			self::pricing_table_feature_style( $obj ),
			self::button_style( $obj, 'primary' ),
			self::pricing_table_badge_style( $obj )
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
		$fields['sb_pricing_table_preset'] = $obj->start_section(
			esc_html__( 'Layout', 'shopbuilder' ),
			'content'
		);
		$fields['layout_note']             = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );
		$fields['layout_style']            = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::general_widgets_pricing_table_layouts(),
			'default' => 'rtsb-pricing-table-layout1',
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
		$fields['sb_pricing_table_preset_end'] = $obj->end_section();
		return $fields;
	}

	/**
	 * General section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sb_pricing_table_general_section( $obj ) {
		$fields['sb_pricing_table_general_sec_start'] = $obj->start_section(
			esc_html__( 'General', 'shopbuilder' ),
			'content'
		);
		$fields['sb_pricing_table_title_html_tag']    = [
			'label'       => esc_html__( 'Title Tag', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the title tag.', 'shopbuilder' ),
			'type'        => 'select',
			'options'     => ControlHelper::heading_tags(),
			'default'     => 'h2',
			'label_block' => true,
		];
		$fields['sb_pricing_table_title']             = [
			'label'       => esc_html__( 'Title', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the title text.', 'shopbuilder' ),
			'type'        => 'textarea',
			'label_block' => true,
			'default'     => __( 'Standard', 'shopbuilder' ),
		];
		$fields['display_content']                    = [
			'label'       => esc_html__( 'Show Content?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show content.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_pricing_table_content']           = [
			'label'       => esc_html__( 'Content', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the pricing table description text.', 'shopbuilder' ),
			'type'        => 'wysiwyg',
			'label_block' => true,
			'default'     => sprintf( '<p>%s</p>', esc_html__( 'Pricing tables makes it easy to create and publish beautiful pricing tables.', 'shopbuilder' ) ),
			'condition'   => [
				'display_content' => 'yes',
			],
		];
		$fields['sb_pricing_table_content_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
			'options'   => ControlHelper::alignment(),
			'separator' => 'before',
			'selectors' => [ $obj->selectors['layout']['alignment'] => 'text-align: {{VALUE}};' ],
		];
		$fields['sb_pricing_table_general_sec_end']   = $obj->end_section();

		return $fields;
	}
	/**
	 * Feature section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sb_pricing_table_feature_section( $obj ) {
		$fields['sb_pricing_table_feature_sec_start'] = $obj->start_section(
			esc_html__( 'Features', 'shopbuilder' ),
			'content'
		);

		$fields['sb_pricing_table_feature_items'] = [
			'type'        => 'repeater',
			'label'       => esc_html__( 'Add Features', 'shopbuilder' ),
			'mode'        => 'repeater',
			'title_field' => '{{{ sb_pricing_table_feature_text }}}',
			'fields'      => [
				'sb_pricing_table_list_style'         => [
					'label'       => esc_html__( 'List Style', 'shopbuilder' ),
					'type'        => 'select',
					'options'     => [
						'icon'   => esc_html__( 'Icon', 'shopbuilder' ),
						'bullet' => esc_html__( 'Bullet', 'shopbuilder' ),
						'number' => esc_html__( 'Number', 'shopbuilder' ),
					],
					'default'     => 'icon',
					'label_block' => true,
				],
				'sb_pricing_table_feature_icon'       => [
					'label'     => esc_html__( 'Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
					'separator' => 'before-short',
					'condition' => [
						'sb_pricing_table_list_style' => 'icon',
					],
				],
				'sb_pricing_table_feature_text'       => [
					'label'       => esc_html__( 'Feature Text', 'shopbuilder' ),
					'type'        => 'textarea',
					'label_block' => true,
					'separator'   => 'before-short',
					'default'     => __( 'Unlimited calls', 'shopbuilder' ),
				],
				'sb_pricing_table_feature_cross_text' => [
					'label'       => esc_html__( 'Enable Cross Out Text', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to enable cross out text.', 'shopbuilder' ),
					'separator'   => 'before-short',
					'type'        => 'switch',
				],

			],
			'default'     => [
				[
					'sb_pricing_table_feature_text' => esc_html__( 'Unlimited calls', 'shopbuilder' ),
					'sb_pricing_table_list_style'   => 'icon',
					'sb_pricing_table_feature_icon' => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
				],
				[
					'sb_pricing_table_feature_text' => esc_html__( 'Free hosting', 'shopbuilder' ),
					'sb_pricing_table_list_style'   => 'icon',
					'sb_pricing_table_feature_icon' => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
				],
				[
					'sb_pricing_table_feature_text' => esc_html__( '24/7 support', 'shopbuilder' ),
					'sb_pricing_table_list_style'   => 'icon',
					'sb_pricing_table_feature_icon' => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
				],
				[
					'sb_pricing_table_feature_text' => esc_html__( '500 MB Bandwidth', 'shopbuilder' ),
					'sb_pricing_table_list_style'   => 'icon',
					'sb_pricing_table_feature_icon' => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
				],
				[
					'sb_pricing_table_feature_text' => esc_html__( 'Create your Own Store', 'shopbuilder' ),
					'sb_pricing_table_feature_icon' => [
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					],
				],
			],

		];
		$fields['sb_pricing_table_feature_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Content Icon
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function pricing_table_icon( $obj ) {
		$alignment_options = ControlHelper::alignment();
		unset( $alignment_options['justify'] );
		$fields['sb_pricing_table_content_icon_sec_start'] = $obj->start_section(
			esc_html__( 'Icon', 'shopbuilder' ),
			'content',
			[],
		);
		$fields['sb_pricing_table_display_icon']           = [
			'label'       => esc_html__( 'Show Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['sb_pricing_table_icon_type']              = [
			'label'     => __( 'Icon Type', 'shopbuilder' ),
			'type'      => 'select',
			'default'   => 'icon',
			'options'   => [
				'icon'  => __( 'Icon', 'shopbuilder' ),
				'image' => __( 'Image', 'shopbuilder' ),
			],
			'condition' => [
				'sb_pricing_table_display_icon' => 'yes',
			],
		];
		$fields['sb_pricing_table_icon']                   = [
			'label'     => esc_html__( 'Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-code',
				'library' => 'fa-solid',
			],
			'condition' => [
				'sb_pricing_table_icon_type'    => [ 'icon' ],
				'sb_pricing_table_display_icon' => 'yes',
			],
		];
		$fields['sb_pricing_table_image']                  = [
			'type'      => 'media',
			'label'     => esc_html__( 'Upload Image', 'shopbuilder' ),
			'default'   => [
				'url' => \Elementor\Utils::get_placeholder_image_src(),
			],
			'condition' => [
				'sb_pricing_table_icon_type'    => [ 'image' ],
				'sb_pricing_table_display_icon' => 'yes',
			],
		];
		$fields['sb_pricing_table_image_size']             = [
			'type'            => 'select2',
			'label'           => esc_html__( 'Select Image Size', 'shopbuilder' ),
			'description'     => esc_html__( 'Please select the image size.', 'shopbuilder' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'full',
			'label_block'     => true,
			'content_classes' => 'elementor-descriptor',
			'condition'       => [
				'sb_pricing_table_icon_type'    => [ 'image' ],
				'sb_pricing_table_display_icon' => 'yes',
			],
		];
		$fields['sb_pricing_table_img_dimension']          = [
			'type'        => 'image-dimensions',
			'label'       => esc_html__( 'Enter Custom Image Size', 'shopbuilder' ),
			'label_block' => true,
			'show_label'  => true,
			'default'     => [
				'width'  => 100,
				'height' => 100,
			],
			'conditions'  => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'sb_pricing_table_image_size',
						'operator' => '==',
						'value'    => 'rtsb_custom',
					],
					[
						'name'     => 'sb_pricing_table_icon_type',
						'operator' => '==',
						'value'    => 'image',
					],
					[
						'name'     => 'sb_pricing_table_display_icon',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		];
		$fields['sb_pricing_table_img_crop']               = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Image Crop', 'shopbuilder' ),
			'description' => esc_html__( 'Please click on "Apply" to update the image.', 'shopbuilder' ),
			'options'     => [
				'soft' => esc_html__( 'Soft Crop', 'shopbuilder' ),
				'hard' => esc_html__( 'Hard Crop', 'shopbuilder' ),
			],
			'default'     => 'hard',
			'condition'   => [
				'sb_pricing_table_icon_type'    => [ 'image' ],
				'sb_pricing_table_display_icon' => 'yes',
				'sb_pricing_table_image_size'   => 'rtsb_custom',
			],
		];

		$fields['sb_pricing_table-img_custom_dimension_note'] = [
			'type'      => 'html',
			'raw'       => sprintf(
				'<span style="display: block; background: #fffbf1; padding: 10px; font-weight: 500; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a;">%s</span>',
				esc_html__( 'Please note that, if you enter image size larger than the actual image itself, the image sizes will fallback to the full image dimension.', 'shopbuilder' )
			),
			'condition' => [
				'sb_pricing_table_icon_type'    => [ 'image' ],
				'sb_pricing_table_display_icon' => 'yes',
				'sb_pricing_table_image_size'   => 'rtsb_custom',
			],

		];

		$fields['sb_pricing_table_content_sec_icon_end'] = $obj->end_section();
		return $fields;
	}
	/**
	 * Price section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sb_pricing_table_pricing_section( $obj ) {
		$fields['sb_pricing_table_pricing_sec_start'] = $obj->start_section(
			esc_html__( 'Price', 'shopbuilder' ),
			'content'
		);
		$fields['sb_pricing_table_price']             = [
			'label'       => esc_html__( 'Price', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the pricing table price.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( '40', 'shopbuilder' ),
		];
		$fields['sale_price']                         = [
			'label'       => esc_html__( 'On Sale?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to On Sale Price.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['sb_pricing_table_sale_price']        = [
			'label'       => esc_html__( 'Sale Price', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the pricing table sale price.', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( '20', 'shopbuilder' ),
			'condition'   => [
				'sale_price' => 'yes',
			],
		];
		$fields['sb_pricing_table_offer_text']        = [
			'label'       => esc_html__( 'Discount Text', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Save Up To 50%', 'shopbuilder' ),
			'condition'   => [
				'sale_price'   => 'yes',
				'layout_style' => [ 'rtsb-pricing-table-layout3' ],
			],
		];
		$fields['sb_pricing_table_currency']          = [
			'label'       => esc_html__( 'Currency', 'shopbuilder' ),
			'description' => esc_html__( 'Currency sign eg. $', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( '$', 'shopbuilder' ),
		];
		$fields['sb_pricing_table_currency_position'] = [
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
			'default' => 'left',
		];
		$fields['sb_pricing_table_unit']            = [
			'label'       => esc_html__( 'Unit Name', 'shopbuilder' ),
			'description' => esc_html__( 'eg. month or year. Keep empty if you don\'t want to show unit', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'mo', 'shopbuilder' ),
		];
		$fields['sb_pricing_table_unit_separator']  = [
			'label'       => esc_html__( 'Unit Separator', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( '/', 'shopbuilder' ),
		];
		$fields['sb_pricing_table_pricing_sec_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function pricing_table_button( $obj ) {
		$condition                                = [
			'display_button' => 'yes',
		];
		$fields['pricing_table_button_sec_start'] = $obj->start_section(
			esc_html__( 'Button', 'shopbuilder' ),
			'content'
		);
		$fields['display_button']                 = [
			'label'       => esc_html__( 'Show Button?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
			'default'     => 'yes',
		];
		$fields['sb_button_icon']                 = [
			'label'     => esc_html__( 'Button Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'default'   => [
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			],
			'condition' => $condition,
		];
		$fields['sb_button_icon_position']        = [
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
			'default'     => __( 'Buy Now', 'shopbuilder' ),
			'condition'   => $condition,
		];
		$fields['sb_button_link']    = [
			'type'        => 'link',
			'label'       => esc_html__( 'Link', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter a button link.', 'shopbuilder' ),
			'placeholder' => esc_html__( 'https://custom-link.com', 'shopbuilder' ),
			'options'     => [ 'url', 'is_external', 'nofollow' ],
			'condition'   => $condition,
		];

		$fields['pricing_table_button_sec_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Badge section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function sb_pricing_table_badge_section( $obj ) {
		$fields['sb_pricing_table_badge_sec_start'] = $obj->start_section(
			esc_html__( 'Badge', 'shopbuilder' ),
			'content'
		);
		$fields['sb_pricing_table_badge']           = [
			'label'       => esc_html__( 'Display Badge?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to On Sale Price.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'type'        => 'switch',
		];
		$fields['sb_pricing_table_badge_style']     = [
			'type'      => 'rtsb-image-selector',
			'options'   => ControlHelper::general_widgets_pricing_table_badge_preset(),
			'default'   => 'rtsb-pricing-table-badge-preset1',
			'condition' => [
				'sb_pricing_table_badge' => 'yes',
			],
		];
		$fields['sb_pricing_table_badge_text']      = [
			'label'       => esc_html__( 'Badge Text', 'shopbuilder' ),
			'type'        => 'text',
			'label_block' => true,
			'default'     => __( 'Featured', 'shopbuilder' ),
			'condition'   => [
				'sb_pricing_table_badge'        => 'yes',
				'sb_pricing_table_badge_style!' => 'rtsb-pricing-table-badge-preset4',
			],
		];
		$fields['sb_pricing_table_badge_position']  = [
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
			'condition' => [
				'sb_pricing_table_badge' => 'yes',
			],
		];
		$fields['badge_horizontal_position']      = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Horizontal Position', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => -100,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$obj->selectors['pricing_table_badge_style']['vertical_horizontal_position'] => '--rtsb-badge-horizontal-position: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'sb_pricing_table_badge' => 'yes',
			],
		];
		$fields['badge_vertical_position']        = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Vertical Position', 'shopbuilder' ),
			'size_units' => [ 'px','%' ],
			'range'      => [
				'px' => [
					'min' => -100,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$obj->selectors['pricing_table_badge_style']['vertical_horizontal_position'] => '--rtsb-badge-vertical-position: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'sb_pricing_table_badge' => 'yes',
			],
		];
		$fields['sb_pricing_table_badge_sec_end'] = $obj->end_section();
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

		$fields = ControlHelper::general_elementor_style( 'pricing_table_title_style', $title, $obj, [], $selectors );

		unset(
			$fields['pricing_table_title_style_alignment'],
			$fields['pricing_table_title_style_color_tabs'],
			$fields['pricing_table_title_style_color_tab'],
			$fields['pricing_table_title_style_bg_color'],
			$fields['pricing_table_title_style_color_tab_end'],
			$fields['pricing_table_title_style_hover_color_tab'],
			$fields['pricing_table_title_style_hover_color'],
			$fields['pricing_table_title_style_hover_bg_color'],
			$fields['pricing_table_title_style_hover_color_tab_end'],
			$fields['pricing_table_title_style_color_tabs_end'],
			$fields['pricing_table_title_style_border_hover_color']
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
			'typography'         => $css_selectors['typography'],
			'box_shadow'         => $css_selectors['box_shadow'],
			'hover_box_shadow'   => $css_selectors['hover_box_shadow'],
			'gradient_bg'        => $css_selectors['gradient_bg'],
			'hover_gradient_bg'  => $css_selectors['hover_gradient_bg'],
			'bg_overlay'         => $css_selectors['bg_overlay'],
			'hover_bg_overlay'   => $css_selectors['hover_bg_overlay'],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'pricing_table_content_box_style', $title, $obj, [], $selectors );

		unset(
			$fields['pricing_table_content_box_style_typo_note'],
			$fields['rtsb_el_pricing_table_content_box_style_typography'],
			$fields['pricing_table_content_box_style_color'],
			$fields['pricing_table_content_box_style_bg_color'],
			$fields['pricing_table_content_box_style_alignment'],
			$fields['pricing_table_content_box_style_hover_color'],
			$fields['pricing_table_content_box_style_hover_bg_color'],
			$fields['pricing_table_content_box_style_margin']
		);
		$fields['pricing_table_content_box_style_border_note']['separator'] = 'default';
		$fields['pricing_table_content_box_style_color_note']['separator']  = 'default';
		$extra_controls['pricing_table_content_box_style_border_radius']    = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$fields = Fns::insert_controls( 'pricing_table_content_box_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['pricing_table_content_box_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['pricing_table_content_box_style_bg_overlay']  = [
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
		$extra_controls2['pricing_table_content_box_style_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'pricing_table_content_box_style_color_tab', $fields, $extra_controls2, true );
		$extra_controls3['pricing_table_content_box_style_hover_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['hover_gradient_bg'],
		];
		$extra_controls3['pricing_table_content_box_style_hover_bg_overlay']  = [
			'label'          => esc_html__( 'Background Overlay', 'shopbuilder' ),
			'type'           => 'background',
			'mode'           => 'group',
			'exclude'        => [ 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector'       => $selectors['hover_bg_overlay'],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Overlay Background Type', 'shopbuilder' ),
				],
			],
		];
		$extra_controls3['pricing_table_content_box_style_hover_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['hover_box_shadow'],
		];
		$fields = Fns::insert_controls( 'pricing_table_content_box_style_hover_color_tab', $fields, $extra_controls3, true );

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

		$fields = ControlHelper::general_elementor_style( 'pricing_table_content_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['pricing_table_content_style_alignment'],
			$fields['pricing_table_content_style_color_tabs'],
			$fields['pricing_table_content_style_color_tab'],
			$fields['pricing_table_content_style_bg_color'],
			$fields['pricing_table_content_style_color_tab_end'],
			$fields['pricing_table_content_style_hover_color_tab'],
			$fields['pricing_table_content_style_hover_color'],
			$fields['pricing_table_content_style_hover_bg_color'],
			$fields['pricing_table_content_style_hover_color_tab_end'],
			$fields['pricing_table_content_style_color_tabs_end'],
			$fields['pricing_table_content_style_border_hover_color']
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
			'line_color'         => [ $css_selectors['line_color'] => 'background-color: {{VALUE}};' ],
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

		$extra_controls2[ 'sb_' . $type . '_button_style_icon_size' ]        = [
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
		$extra_controls2[ 'sb_' . $type . '_button_style_icon_gap' ]         = [
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
		$extra_controls2[ 'sb_' . $type . '_button_style_box_shadow' ]       = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$extra_controls2[ 'sb_' . $type . '_button_style_hover_box_shadow' ] = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Hover Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['hover_box_shadow'],
		];
		$fields = Fns::insert_controls( 'sb_' . $type . '_button_style_spacing_note', $fields, $extra_controls2, true );

		$extra_controls3[ 'sb_' . $type . '_button_style_gradient_bg' ] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls3[ 'sb_' . $type . '_button_line_color' ]        = [
			'label'     => esc_html__( 'Line Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['line_color'] ,
			'condition' => [
				'layout_style' => [ 'rtsb-pricing-table-layout3' ],
			],
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
	private static function icon_box_style( $obj ) {
		$condition     = [
			'sb_pricing_table_display_icon' => 'yes',
		];
		$css_selectors = $obj->selectors['pricing_table_icon_style'];
		$title         = esc_html__( 'Icon Style', 'shopbuilder' );
		$selectors     = [
			'icon_size'     => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'box_shadow'    => $css_selectors['box_shadow'],
			'icon_width'    => [ $css_selectors['icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'icon_height'   => [ $css_selectors['icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'         => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'border'        => $css_selectors['border'],
			'margin'        => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'pricing_table_icon_style', $title, $obj, $condition, $selectors );
		unset(
			$fields['pricing_table_icon_style_typo_note'],
			$fields['rtsb_el_pricing_table_icon_style_typography'],
			$fields['pricing_table_icon_style_color_tabs'],
			$fields['pricing_table_icon_style_color_tab'],
			$fields['pricing_table_icon_style_color_tab_end'],
			$fields['pricing_table_icon_style_hover_color_tab'],
			$fields['pricing_table_icon_style_bg_color'],
			$fields['pricing_table_icon_style_hover_bg_color'],
			$fields['pricing_table_icon_style_alignment'],
			$fields['pricing_table_icon_style_padding'],
			$fields['pricing_table_icon_style_hover_color'],
			$fields['pricing_table_icon_style_border_hover_color'],
		);
		$extra_controls['pricing_table_icon_style_border_radius'] = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
		];
		$extra_controls['pricing_table_icon_style_box_shadow']    = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'pricing_table_icon_style_spacing_note', $fields, $extra_controls );
		$extra_controls2['pricing_table_icon_style_icon_width']  = [
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
		$extra_controls2['pricing_table_icon_style_icon_height'] = [
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

		$extra_controls2['pricing_table_icon_style_icon_size'] = [
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

		$fields = Fns::insert_controls( 'pricing_table_icon_style_spacing_note', $fields, $extra_controls2, true );
		$extra_controls3['pricing_table_icon_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$fields = Fns::insert_controls( 'pricing_table_icon_style_color', $fields, $extra_controls3, true );

		return $fields;
	}
	/**
	 * Icon style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function pricing_table_price_style( $obj ) {

		$css_selectors = $obj->selectors['price_style'];
		$title         = esc_html__( 'Price Style', 'shopbuilder' );
		$selectors     = [
			'typography'       => $css_selectors['typography'],
			'offer_typo'       => $css_selectors['offer_typo'],
			'unit_typo'        => $css_selectors['unit_typo'],
			'color'            => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'offer_color'      => [ $css_selectors['offer_color'] => 'color: {{VALUE}};' ],
			'offer_bg_color'   => [ $css_selectors['offer_bg_color'] => 'background-color: {{VALUE}};' ],
			'sale_price_color' => [ $css_selectors['sale_price_color'] => 'color: {{VALUE}};' ],
			'price_unit_color' => [ $css_selectors['price_unit_color'] => 'color: {{VALUE}};' ],
			'border'           => $css_selectors['border'],
			'padding'          => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'           => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'pricing_table_price_style', $title, $obj, [], $selectors );

		// The price number lives in `.rtsb-price` inside `.rtsb-orginal-price`/`.rtsb-sale-price`
		// (display:inline-flex), and an ancestor's text-decoration cannot reach an inline-flex
		// descendant, so a decoration on `.rtsb-price-wrap` lands beside the number. Point it at
		// the actual price text spans so the decoration draws across the number (+ currency).
		$td = $css_selectors['typography'];

		$fields['rtsb_el_pricing_table_price_style_typography']['fields_options'] = [
			'text_decoration' => [
				'selectors' => [
					$td . ' .rtsb-price,' . $td . ' .rtsb-currency' => 'text-decoration: {{VALUE}};',
				],
			],
		];

		unset(
			$fields['pricing_table_price_style_alignment'],
			$fields['pricing_table_price_style_color_tabs'],
			$fields['pricing_table_price_style_color_tab'],
			$fields['pricing_table_price_style_bg_color'],
			$fields['pricing_table_price_style_color_tab_end'],
			$fields['pricing_table_price_style_hover_color_tab'],
			$fields['pricing_table_price_style_hover_color'],
			$fields['pricing_table_price_style_hover_bg_color'],
			$fields['pricing_table_price_style_hover_color_tab_end'],
			$fields['pricing_table_price_style_color_tabs_end'],
			$fields['pricing_table_price_style_border_hover_color']
		);
		$extra_controls['pricing_table_price_style_unit_typo']  = [
			'mode'     => 'group',
			'type'     => 'typography',
			'label'    => __( 'Price Unit Typography', 'shopbuilder' ),
			'selector' => $selectors['unit_typo'],
		];
		$extra_controls['pricing_table_price_style_offer_typo'] = [
			'mode'      => 'group',
			'type'      => 'typography',
			'label'     => __( 'Offer Text Typography', 'shopbuilder' ),
			'selector'  => $selectors['offer_typo'],
			'condition' => [
				'sale_price'   => 'yes',
				'layout_style' => [ 'rtsb-pricing-table-layout3' ],
			],
		];
		$fields = Fns::insert_controls( 'rtsb_el_pricing_table_price_style_typography', $fields, $extra_controls, true );

		$extra_controls2['pricing_table_price_style_sale_price_color']     = [
			'label'     => __( 'Sale Price Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['sale_price_color'],
			'condition' => [
				'sale_price' => 'yes',
			],
		];
		$extra_controls2['pricing_table_price_style_price_unit_color']     = [
			'label'     => __( 'Price Unit Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['price_unit_color'],
		];
		$extra_controls2['pricing_table_price_style_price_offer_color']    = [
			'label'     => __( 'Offer Text Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['offer_color'],
			'condition' => [
				'sale_price'   => 'yes',
				'layout_style' => [ 'rtsb-pricing-table-layout3' ],
			],
		];
		$extra_controls2['pricing_table_price_style_price_offer_bg_color'] = [
			'label'     => __( 'Offer Text Background Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['offer_bg_color'],
			'condition' => [
				'sale_price'   => 'yes',
				'layout_style' => [ 'rtsb-pricing-table-layout3' ],
			],
		];
		$fields = Fns::insert_controls( 'pricing_table_price_style_color', $fields, $extra_controls2, true );
		return $fields;
	}
	/**
	 * Feature style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function pricing_table_feature_style( $obj ) {

		$css_selectors = $obj->selectors['feature_item_style'];
		$title         = esc_html__( 'Feature Item Style', 'shopbuilder' );
		$selectors     = [
			'typography'      => $css_selectors['typography'],
			'icon_size'       => [
				$css_selectors['icon_size']['font_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				$css_selectors['icon_size']['svg']       => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
			],
			'color'           => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'list_icon_color' => [ $css_selectors['list_icon_color'] => 'color: {{VALUE}};' ],
			'list_dot_color'  => [ $css_selectors['list_dot_color'] => 'background-color: {{VALUE}};' ],
			'border'          => $css_selectors['border'],
			'padding'         => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'          => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'pricing_table_feature_item_style', $title, $obj, [], $selectors );

		unset(
			$fields['pricing_table_feature_item_style_alignment'],
			$fields['pricing_table_feature_item_style_color_tabs'],
			$fields['pricing_table_feature_item_style_color_tab'],
			$fields['pricing_table_feature_item_style_bg_color'],
			$fields['pricing_table_feature_item_style_color_tab_end'],
			$fields['pricing_table_feature_item_style_hover_color_tab'],
			$fields['pricing_table_feature_item_style_hover_color'],
			$fields['pricing_table_feature_item_style_hover_bg_color'],
			$fields['pricing_table_feature_item_style_hover_color_tab_end'],
			$fields['pricing_table_feature_item_style_color_tabs_end'],
			$fields['pricing_table_feature_item_style_border_hover_color']
		);
		$extra_controls['pricing_table_feature_item_style_icon_size'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 100,
				],

			],
			'selectors'  => $selectors['icon_size'],
		];
		$fields = Fns::insert_controls( 'rtsb_el_pricing_table_feature_item_style_typography', $fields, $extra_controls, true );
		$extra_controls2['pricing_table_feature_item_style_list_icon_color'] = [
			'label'     => __( 'Icon Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['list_icon_color'],
		];
		$extra_controls2['pricing_table_feature_item_style_list_dot_color']  = [
			'label'     => __( 'Dot Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => $selectors['list_dot_color'],
		];
		$extra_controls2['pricing_table_feature_item_cross_color']           = [
			'label'     => __( 'Cross Out Text Color', 'shopbuilder' ),
			'type'      => 'color',
			'selectors' => [ $css_selectors['cross_color'] => 'color: {{VALUE}};' ],
		];
		$fields = Fns::insert_controls( 'pricing_table_feature_item_style_color', $fields, $extra_controls2, true );
		return $fields;
	}
	/**
	 * Badge style section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	private static function pricing_table_badge_style( $obj ) {
		$condition     = [
			'sb_pricing_table_badge' => 'yes',
		];
		$css_selectors = $obj->selectors['pricing_table_badge_style'];
		$title         = esc_html__( 'Badge Style', 'shopbuilder' );
		$selectors     = [
			'typography'    => $css_selectors['typography'],
			'color'         => [
				$css_selectors['color']['color'] => 'color: {{VALUE}};' ,
				$css_selectors['color']['svg']   => 'fill: {{VALUE}};',
			],
			'box_shadow'    => $css_selectors['box_shadow'],
			'gradient_bg'   => $css_selectors['gradient_bg'],
			'border'        => $css_selectors['border'],
			'padding'       => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'border_radius' => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'pricing_table_badge_style', $title, $obj, $condition, $selectors );

		unset(
			$fields['pricing_table_badge_style_bg_color'],
			$fields['pricing_table_badge_style_alignment'],
			$fields['pricing_table_badge_style_color_tabs'],
			$fields['pricing_table_badge_style_color_tab'],
			$fields['pricing_table_badge_style_color_tab_end'],
			$fields['pricing_table_badge_style_hover_color_tab'],
			$fields['pricing_table_badge_style_hover_color'],
			$fields['pricing_table_badge_style_hover_bg_color'],
			$fields['pricing_table_badge_style_hover_color_tab_end'],
			$fields['pricing_table_badge_style_color_tabs_end'],
			$fields['pricing_table_badge_style_border_hover_color'],
			$fields['pricing_table_badge_style_margin']
		);
		$fields['pricing_table_badge_style_border_note']['separator']    = 'default';
		$fields['pricing_table_badge_style_color_note']['separator']     = 'default';
		$fields['pricing_table_badge_style_border_note']['condition']    = [
			'sb_pricing_table_badge_style' => [ 'rtsb-pricing-table-badge-preset1' ],
		];
		$fields['rtsb_el_pricing_table_badge_style_border']['condition'] = [
			'sb_pricing_table_badge_style' => [ 'rtsb-pricing-table-badge-preset1' ],
		];

		$fields['pricing_table_badge_style_typo_note']['condition']          = [
			'sb_pricing_table_badge_style!' => [ 'rtsb-pricing-table-badge-preset4' ],
		];
		$fields['rtsb_el_pricing_table_badge_style_typography']['condition'] = [
			'sb_pricing_table_badge_style!' => [ 'rtsb-pricing-table-badge-preset4' ],
		];

		$fields['pricing_table_badge_style_spacing_note']['condition'] = [
			'sb_pricing_table_badge_style!' => [ 'rtsb-pricing-table-badge-preset4' ],
		];
		$fields['pricing_table_badge_style_padding']['condition']      = [
			'sb_pricing_table_badge_style!' => [ 'rtsb-pricing-table-badge-preset4' ],
		];
		$extra_controls['pricing_table_badge_style_border_radius']     = [
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'type'       => 'dimensions',
			'mode'       => 'responsive',
			'size_units' => [ 'px' ],
			'selectors'  => $selectors['border_radius'],
			'condition'  => [
				'sb_pricing_table_badge_style' => [ 'rtsb-pricing-table-badge-preset1' ],
			],
		];
		$fields = Fns::insert_controls( 'pricing_table_badge_style_spacing_note', $fields, $extra_controls );

		$extra_controls2['pricing_table_badge_style_gradient_bg'] = [
			'label'    => esc_html__( 'Background', 'shopbuilder' ),
			'type'     => 'background',
			'mode'     => 'group',
			'selector' => $selectors['gradient_bg'],
		];
		$extra_controls2['pricing_table_badge_style_box_shadow']  = [
			'type'     => 'box-shadow',
			'mode'     => 'group',
			'label'    => esc_html__( 'Box Shadow', 'shopbuilder' ),
			'selector' => $selectors['box_shadow'],
		];
		$fields = Fns::insert_controls( 'pricing_table_badge_style_color', $fields, $extra_controls2, true );

		return $fields;
	}
}

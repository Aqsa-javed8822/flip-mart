<?php

/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductTabsSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$selectors = $widget->selectors;

		return self::general( $widget ) +
			   self::nav_style( $widget ) +
			   self::tab_content( $widget ) +
			   self::additional_info( $widget ) +
			   ReviewsSettings::widget_fields( $widget );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general( $widget ) {
		$fields = [
			'general_section'                   => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'layout_style'                      => [
				'label'     => esc_html__( 'Layout', 'shopbuilder' ),
				'type'      => 'rtsb-image-selector',
				'options'   => ControlHelper::single_product_tab_layouts(),
				'default'   => 'default',
				'separator' => 'default',
			],
			'description_note'                  => $widget->el_heading( esc_html__( 'Description Tab', 'shopbuilder' ), 'before' ),
			'description'                       => [
				'label'       => esc_html__( 'Description', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show description.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'description_nav_text'              => [
				'label'       => esc_html__( 'Custom Nav Text', 'shopbuilder' ),
				'type'        => 'text',
				'label_block' => true,
				'default'     => esc_html__( 'Description', 'shopbuilder' ),
				'description' => esc_html__( 'Leave empty for default text.', 'shopbuilder' ),
				'condition'   => [
					'description' => 'yes',
				],
			],
			'description_title_text'            => [
				'label'       => esc_html__( 'Custom Title', 'shopbuilder' ),
				'type'        => 'text',
				'default'     => __( 'Description', 'shopbuilder' ),
				'description' => esc_html__( 'Leave empty for hide the title.', 'shopbuilder' ),
				'label_block' => true,
				'condition'   => [
					'description' => 'yes',
				],
			],
			'additional_information_note'       => $widget->el_heading( esc_html__( 'Additional information Tab', 'shopbuilder' ), 'before' ),
			'additional_information'            => [
				'label'       => esc_html__( 'Additional information', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Additional information.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'additional_information_nav_text'   => [
				'label'       => esc_html__( 'Custom Nav Text', 'shopbuilder' ),
				'type'        => 'text',
				'label_block' => true,
				'default'     => esc_html__( 'Additional information', 'shopbuilder' ),
				'description' => esc_html__( 'Leave empty for default text.', 'shopbuilder' ),
				'condition'   => [
					'additional_information' => 'yes',
				],
			],
			'additional_information_title_text' => [
				'label'       => esc_html__( 'Custom Title', 'shopbuilder' ),
				'type'        => 'text',
				'default'     => __( 'Additional information', 'shopbuilder' ),
				'label_block' => true,
				'description' => esc_html__( 'Leave empty for hide the title.', 'shopbuilder' ),
				'condition'   => [
					'additional_information' => 'yes',
				],
			],
			'reviews_note'                      => $widget->el_heading( esc_html__( 'Reviews Tab', 'shopbuilder' ), 'before' ),
			'reviews'                           => [
				'label'       => esc_html__( 'Reviews', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Reviews.', 'shopbuilder' ),
				'default'     => '',
			],
			'reviews_nav_text'                  => [
				'label'       => esc_html__( 'Custom Nav Text', 'shopbuilder' ),
				'type'        => 'text',
				'label_block' => true,
				'default'     => __( 'Reviews', 'shopbuilder' ),
				'description' => esc_html__( 'Leave empty for default text.', 'shopbuilder' ),
				'condition'   => [
					'reviews' => 'yes',
				],
			],
			// No Option Found for remove title Check: /woocommerce/templates/single-product-reviews.php
			// 'reviews_title_text' => [
			// 'label'     => esc_html__( 'Reviews title text', 'shopbuilder' ),
			// 'type'      => 'text',
			// 'default'   => __( 'Reviews', 'shopbuilder' ),
			// 'label_block'      => true,
			// 'condition' => [
			// 'reviews' => 'yes',
			// ],
			// ],
			'general_section_end'               => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function nav_style( $widget ) {
		$fields = [
			'nav_style'                     => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Nav Style', 'shopbuilder' ),
				'condition' => [
					'layout_style!' => [ 'custom-layout1' ],
				],
			],
			'nav_typography'                => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['nav_typography'],
			],
			'nav_position'                  => [
				'label'     => esc_html__( 'Position', 'shopbuilder' ),
				'mode'      => 'responsive',
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'condition' => [
					'layout_style' => [ 'custom-layout2' ],
				],
				'selectors' => [
					$widget->selectors['nav_position'] => 'top: -{{SIZE}}{{UNIT}};',
				],
			],
			'nav_tabs_start'                => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'nav_normal'                    => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'nav_color'                     => [
				'label'     => esc_html__( 'Menu color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['nav_color'] => 'color: {{VALUE}}',
				],
			],
			'nav_bg_color'                  => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'alpha'     => true,
				'selectors' => [
					$widget->selectors['nav_bg_color'] => 'background: {{VALUE}};',
				],
			],

			'nav_border'                    => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $widget->selectors['nav_border'],
				'size_units' => [ 'px' ],
			],

			'nav_active_color'              => [
				'label'     => esc_html__( 'Active Menu color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['nav_active_color'] => 'color: {{VALUE}}',
				],
			],

			'nav_active_bg_color'           => [
				'label'     => esc_html__( 'Active Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'alpha'     => true,
				'selectors' => [
					$widget->selectors['nav_active_bg_color'] => 'background: {{VALUE}};',
				],
			],

			'nav_active_border_color'       => [
				'label'     => esc_html__( 'Active Border color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['nav_active_border_color'] => 'border-color: {{VALUE}}',
				],
			],

			'nav_normal_end'                => [
				'mode' => 'tab_end',
			],
			'nav_hover'                     => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'nav_hover_color'               => [
				'label'     => esc_html__( 'Menu color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$widget->selectors['nav_hover_color'] => 'color: {{VALUE}}',
				],
			],
			'nav_hover_bg_color'            => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',

				'selectors' => [
					$widget->selectors['nav_hover_bg_color'] => 'background: {{VALUE}};',
				],
			],
			'nav_active_hover_color'        => [
				'label'     => esc_html__( 'Active Menu color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['nav_active_hover_color'] => 'color: {{VALUE}}',
				],
			],
			'nav_active_hover_bg_color'     => [
				'label'     => esc_html__( 'Active Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',

				'selectors' => [
					$widget->selectors['nav_active_hover_bg_color'] => 'background: {{VALUE}};',
				],
			],
			'nav_active_hover_border_color' => [
				'label'     => esc_html__( 'Active Border color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['nav_active_hover_border_color'] => 'border-bottom-color: {{VALUE}}',
				],
			],
			'nav_hover_end'                 => [
				'mode' => 'tab_end',
			],
			'nav_tabs_end'                  => [
				'mode' => 'tabs_end',
			],
			'nav_padding'                   => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['nav_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'nav_style_end'                 => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}


	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function tab_content( $widget ) {
		$fields = [
			'tab_content_style'       => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Tab Content', 'shopbuilder' ),
			],
			'content_padding'         => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'separator'  => 'default',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['content_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'show_title'              => [
				'label'     => esc_html__( 'Show title', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'block' => [
						'title' => esc_html__( 'Show', 'shopbuilder' ),
						'icon'  => 'eicon-check-circle',
					],
					'none'  => [
						'title' => esc_html__( 'Hide', 'shopbuilder' ),
						'icon'  => 'eicon-editor-close',
					],
				],
				'default'   => 'block',
				'selectors' => [
					$widget->selectors['show_title'] => 'display: {{VALUE}} !important;',
				],
			],
			'tab_title_typography'    => [
				'mode'      => 'group',
				'type'      => 'typography',
				'separator' => 'before',
				'label'     => esc_html__( 'Title Typography', 'shopbuilder' ),
				'condition' => [
					'show_title' => 'block',
				],
				'selector'  => $widget->selectors['tab_title_typography'],
			],
			'tab_title_gap'           => [
				'label'      => esc_html__( 'Title Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'condition'  => [
					'show_title' => 'block',
				],
				'selectors'  => [
					$widget->selectors['tab_title_gap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'tab_content_title_color' => [
				'label'     => esc_html__( 'Title Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$widget->selectors['tab_content_title_color'] => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_title' => 'block',
				],
			],
			'tab_content_style_end'   => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function additional_info( $widget ) {
		$fields = [
			'additional_info_style'      => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Additional info', 'shopbuilder' ),
				'condition' => [
					'additional_information' => 'yes',
				],
			],
			'additional_info_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['additional_info_typography'],
			],
			'attributes_gap'             => [
				'label'      => esc_html__( 'Cell Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['attributes_gap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'additional_info_style_end'  => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

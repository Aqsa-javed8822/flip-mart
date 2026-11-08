<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ModuleBtnControls {
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget Object.
	 *
	 * @return array
	 */
	public static function module_button_style( $widget ) {
		$selector          = $widget->selectors;
		$module_btn_active = Fns::is_module_active( 'wishlist' ) || Fns::is_module_active( 'compare' ) || Fns::is_module_active( 'quick_view' );
		$fields            = [];
		if ( ! $module_btn_active ) {
			return $fields;
		}

		$fields = [
			'module_section'            => [
				'mode'       => 'section_start',
				'tab'        => 'style',
				'label'      => esc_html__( 'Wishlist, Compare & Quick view', 'shopbuilder' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [],
				],
			],
			'module_width'              => [
				'label'     => esc_html__( 'Width', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => 20,
				],
				'selectors' => [
					$selector['module_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'module_height'             => [
				'label'     => esc_html__( 'Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => 20,
				],
				'selectors' => [
					$selector['module_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'module_item_gap'           => [
				'label'     => esc_html__( 'Item Gap (px)', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 5,
				],
				'selectors' => [
					$selector['module_item_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'module_item_alignment'     => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Elements Alignment', 'shopbuilder' ),
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					$selector['module_item_alignment'] => 'justify-content: {{VALUE}}!important;',
				],
			],
			'module_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'module_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'module_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$selector['module_text_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'module_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['module_bg_color_normal'] => 'background-color: {{VALUE}};',
				],
			],

			'module_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $selector['module_border'],
				'size_units' => [ 'px' ],
			],
			'module_normal_end'         => [
				'mode' => 'tab_end',
			],
			'module_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'module_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$selector['module_text_color_hover'] => 'color: {{VALUE}};',
				],
			],
			'module_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['module_bg_color_hover'] => 'background-color: {{VALUE}};',
				],
			],
			'module_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['module_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'module_hover_end'          => [
				'mode' => 'tab_end',
			],
			'module_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'module_border_radius'      => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'separator'  => 'before',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['module_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'icon_size'                 => [
				'label'     => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'before',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => 15,
				],
				'selectors' => [
					$selector['icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$selector['icon_size']['svg']  => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			],
			'module_wrapper_margin'     => [
				'label'      => esc_html__( 'Module Wrapper Gap (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['module_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'module_section_end'        => [
				'mode' => 'section_end',
			],
		];

		if ( Fns::is_module_active( 'wishlist' ) ) {
			$fields['module_section']['conditions']['terms'][] = [
				'name'     => 'wishlist_button',
				'operator' => '===',
				'value'    => 'yes',
			];
		}
		if ( Fns::is_module_active( 'compare' ) ) {
			$fields['module_section']['conditions']['terms'][] = [
				'name'     => 'comparison_button',
				'operator' => '===',
				'value'    => 'yes',
			];
		}
		if ( Fns::is_module_active( 'quick_view' ) && isset( $fields['quick_view_button'] ) ) {
			$fields['module_section']['conditions']['terms'][] = [
				'name'     => 'quick_view_button',
				'operator' => '===',
				'value'    => 'yes',
			];
		}

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function module_switch() {
		$fields = [];
		if ( Fns::is_module_active( 'wishlist' ) ) {
			$fields['wishlist_button'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Wishlist Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product wishlist. Ensure "Showcase Button Position" under Wishlist module\'s "Product Showcase Settings" is not set to "Custom Position".', 'shopbuilder' ),
				'default'     => 'yes',
			];
		}
		if ( Fns::is_module_active( 'compare' ) ) {
			$fields['comparison_button'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Compare Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product comparison. Ensure "Showcase Button Position" under Compare module\'s "Product Showcase Settings" is not set to "Custom Position".', 'shopbuilder' ),
				'default'     => 'yes',
			];
		}
		if ( Fns::is_module_active( 'quick_view' ) ) {
			$fields['quick_view_button'] = [
				'type'        => 'switch',
				'label'       => esc_html__( 'Show Quick View Button?', 'shopbuilder' ),
				'description' => esc_html__( 'Switch on to show product quick view. Ensure "Showcase Button Position" under Quick View module\'s "Product Showcase Settings" is not set to "Custom Position".', 'shopbuilder' ),
				'default'     => 'yes',
			];
		}

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function module_icons() {
		$module_btn_active = Fns::is_module_active( 'wishlist' ) || Fns::is_module_active( 'compare' ) || Fns::is_module_active( 'quick_view' );
		$fields            = [];
		if ( $module_btn_active ) {
			if ( Fns::is_module_active( 'wishlist' ) ) {
				$fields['wishlist_icon']       = [
					'label'     => esc_html__( 'Wishlist Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'separator' => 'default',
					'default'   => [
						'value'   => 'rtsb-icon rtsb-icon-heart-empty',
						'library' => 'rtsb-fonts',
					],
					'condition' => [
						'wishlist_button' => 'yes',
					],
				];
				$fields['wishlist_icon_added'] = [
					'label'     => esc_html__( 'After Added Wishlist Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'rtsb-icon rtsb-icon-heart',
						'library' => 'rtsb-fonts',
					],
					'condition' => [
						'wishlist_button' => 'yes',
					],
				];
			}
			if ( Fns::is_module_active( 'compare' ) ) {
				$fields['comparison_icon']       = [
					'label'     => esc_html__( 'Compare Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'rtsb-icon rtsb-icon-exchange',
						'library' => 'rtsb-fonts',
					],
					'condition' => [
						'comparison_button' => 'yes',
					],
				];
				$fields['comparison_icon_added'] = [
					'label'     => esc_html__( 'After Added Compare Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'rtsb-icon rtsb-icon-check',
						'library' => 'rtsb-fonts',
					],
					'condition' => [
						'comparison_button' => 'yes',
					],
				];
			}
			if ( Fns::is_module_active( 'quick_view' ) ) {
				$fields['quick_view_icon'] = [
					'label'     => esc_html__( 'Quick View Icon', 'shopbuilder' ),
					'type'      => 'icons',
					'default'   => [
						'value'   => 'rtsb-icon rtsb-icon-eye',
						'library' => 'rtsb-fonts',
					],
					'condition' => [
						'quick_view_button' => 'yes',
					],
				];
			}
		}

		return $fields;
	}
}

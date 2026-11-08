<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ActionsBtnSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		return self::general_section() +
		       self::module_icons() +
		       self::module_button( $widget ) +
		       self::button_separator( $widget );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_section() {
		$fields        = [
			'sec_general'       => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'view_mode'         => [
				'label'   => esc_html__( 'Default View', 'shopbuilder' ),
				'type'    => 'choose',
				'options' => [
					'inline'   => [
						'title' => esc_html__( 'Inline', 'shopbuilder' ),
						'icon'  => 'eicon-ellipsis-h',
					],
					'new-line' => [
						'title' => esc_html__( 'New Line', 'shopbuilder' ),
						'icon'  => 'eicon-post-list',
					],
				],
				'default' => 'inline',
			],
			'button_separator'  => [
				'label'     => esc_html__( 'Button Separator', 'shopbuilder' ),
				'type'      => 'text',
				'condition' => [
					'view_mode' => 'inline',
				],
			],
			'general_style_end' => [
				'mode' => 'section_end',
			],
		];
		$module_switch = ModuleBtnControls::module_switch();
		unset( $module_switch['quick_view_button'] );
		$fields = Fns::insert_controls( 'button_separator', $fields, $module_switch );

		if ( Fns::is_module_active( 'wishlist' ) ) {
			$wishlist_button = [
				'wishlist_button_text' => [
					'label'     => esc_html__( 'Wishlist Text', 'shopbuilder' ),
					'type'      => 'text',
					'condition' => [
						'wishlist_button' => 'yes',
					],
				],
			];
			$fields          = Fns::insert_controls( 'wishlist_button', $fields, $wishlist_button, true );
		}
		if ( Fns::is_module_active( 'compare' ) ) {
			$comparison_button = [
				'comparison_button_text' => [
					'label'     => esc_html__( 'Comparison Text', 'shopbuilder' ),
					'type'      => 'text',
					'condition' => [
						'comparison_button' => 'yes',
					],
				],
			];
			$fields            = Fns::insert_controls( 'comparison_button', $fields, $comparison_button, true );
		}

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function module_button( $widget ) {
		$fields = ModuleBtnControls::module_button_style( $widget );
		if ( ! empty( $fields ) ) {
			$fields['module_section']['label'] = esc_html__( 'Wishlist & Compare', 'shopbuilder' );
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
		}

		$textSetting = TextStyleSettings::widget_fields( $widget );

		unset( $textSetting['align'] );
		$textSetting['section_style']['label'] = esc_html__( 'Text Style', 'shopbuilder' );
		$textSetting['section_style']['tab']   = 'style';

		$text_style                            = [
			'text_hover_color' => [
				'label'     => esc_html__( 'Text Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['text_hover_color'] => 'color: {{VALUE}};',
				],
			],
		];
		$textSetting                           = Fns::insert_controls( 'text_shadow', $textSetting, $text_style );

		$text_style                            = [
			'text_margin'      => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['text_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

		];
		$textSetting                           = Fns::insert_controls( 'text_shadow', $textSetting, $text_style, true );

		return $fields + $textSetting;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function button_separator( $widget ) {
		$fields = [
			'separator_section'      => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Separator Style', 'shopbuilder' ),
				'condition' => [
					'view_mode' => 'inline',
				],
			],
			'separator_typography'   => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['separator_typography'],
			],
			'separator_color_normal' => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['separator_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'separator_section_end'  => [
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
	public static function module_icons() {
		if ( ! Fns::is_module_active( 'wishlist' ) && ! Fns::is_module_active( 'compare' ) ) {
			return [];
		}

		$fields['module_icon_style_section'] = [
			'mode'  => 'section_start',
			'label' => esc_html__( 'Icons', 'shopbuilder' ),
		];

		if ( Fns::is_module_active( 'wishlist' ) ) {
			$fields['module_icon_style_section']['conditions']['terms'][] = [
				'name'     => 'wishlist_button',
				'operator' => '===',
				'value'    => 'yes',
			];
		}
		if ( Fns::is_module_active( 'compare' ) ) {
			$fields['module_icon_style_section']['conditions']['terms'][] = [
				'name'     => 'comparison_button',
				'operator' => '===',
				'value'    => 'yes',
			];
		}

		$fields = $fields + ModuleBtnControls::module_icons();

		unset( $fields['quick_view_icon'] );

		$fields['module_icon_style_section_end'] = [
			'mode' => 'section_end',
		];

		return $fields;
	}

}

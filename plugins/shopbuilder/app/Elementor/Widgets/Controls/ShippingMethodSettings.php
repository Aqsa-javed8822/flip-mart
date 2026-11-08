<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ShippingMethodSettings {

	/**
	 * Widget Control Selectors
	 *
	 * @var object
	 */
	private static $widget = [];
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
		self::$widget    = $widget;
		self::$selectors = $widget->selectors;
		return self::title_settings() +
			self::general_style_settings( self::$widget ) +
			TitleSettings::title_settings( self::$widget ) +
			self::label_settings( $widget );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function title_settings() {
		$title_settings = TitleSettings::general_settings();

		$title_settings['general_style']['label'] = esc_html__( 'General', 'shopbuilder' );

		$new_fields = [
			'title_html_tag' => [
				'label'     => esc_html__( 'Title HTML Tag', 'shopbuilder' ),
				'type'      => 'select',
				'options'   => ControlHelper::heading_tags(),
				'default'   => 'h2',
				'condition' => [
					'show_title!' => '',
				],
			],
			'title_text'     => [
				'label'     => esc_html__( 'Title Text', 'shopbuilder' ),
				'type'      => 'text',
				'default'   => esc_html__( 'Shipping', 'shopbuilder' ),
				'condition' => [
					'show_title!' => '',
				],
			],
		];

		$fields = Fns::insert_controls( 'show_title', $title_settings, $new_fields, true );

		return $fields;
	}

	public static function general_style_settings( $widget ) {
		return [
			'general_table_style_start' => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'general_cart_table_border' => [
				'mode'       => 'group',
				'type'       => 'border',
				'separator'  => 'default',
				'label'      => esc_html__( 'Border', 'shopbuilder' ),
				'selector'   => $widget->selectors['general_cart_table_border'],
				'size_units' => [ 'px' ],
			],
			'general_cart_table_bg'     => [
				'label'     => esc_html__( 'Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['general_cart_table_bg'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'general_table_padding'     => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['general_table_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'general_table_style_end'   => [
				'mode' => 'section_end',
			],
		];
	}
	public static function label_settings( $widget ) {
		return [
			'label_title_style_start' => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Label', 'shopbuilder' ),
				'condition' => [
					'show_title!' => '',
				],
			],
			'label_title_typo'        => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['label_title_typo'],
			],
			'label_title_color'       => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['label_title_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'label_space_between'     => [
				'label'      => esc_html__( 'Space Between (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 4,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					$widget->selectors['label_space_between'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'label_item_space'        => [
				'label'      => esc_html__( 'Label Gap (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 5,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					$widget->selectors['label_item_space'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'label_title_style_end'   => [
				'mode' => 'section_end',
			],
		];
	}
}

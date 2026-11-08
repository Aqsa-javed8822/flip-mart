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
class PriceSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'sec_general'        => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'price_align'        => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['price_align'] => 'text-align: {{VALUE}};',
				],
			],
			'space_between'      => [
				'label'      => esc_html__( 'Space for sale price (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 8,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					$widget->selectors['space_between']['del_price']     => 'margin-right: {{SIZE}}{{UNIT}};',
					$widget->selectors['space_between']['del_price_rtl'] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right:0px;',
				],

			],
			'sec_general_end'    => [
				'mode' => 'section_end',
			],
			'general_style'      => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Style', 'shopbuilder' ),
			],
			'price_heading'      => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Price', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'price_typo'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'selector' => $widget->selectors['price_typo'],
			],
			'price_color'        => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['price_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'sale_price_heading' => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Sale Price', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'sale_price_typo'    => [
				'mode'     => 'group',
				'type'     => 'typography',
				'selector' => $widget->selectors['sale_price_typo'],
			],
			'sale_price_color'   => [
				'label'     => esc_html__( 'Sale Price Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['sale_price_color'] => ' background: transparent; color: {{VALUE}} !important;',
				],
			],
			'general_style_end'  => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}


}

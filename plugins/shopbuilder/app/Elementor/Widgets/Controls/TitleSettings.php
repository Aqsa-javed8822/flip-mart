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
class TitleSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings() {
		return [
			'general_style'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Visibility', 'shopbuilder' ),
			],
			'show_title'        => [
				'label'       => esc_html__( 'Show Title?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show title.', 'shopbuilder' ),
				'separator'   => 'default',
				'default'     => 'yes',
			],
			'general_style_end' => [
				'mode' => 'section_end',
			],
		];
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function title_settings( $widget ) {
		return [
			'title_style_start' => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Title', 'shopbuilder' ),
				'condition' => [
					'show_title!' => '',
				],
			],

			'title_typo'        => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['title_typo'],
			],
			'title_align'       => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$widget->selectors['title_align'] => 'text-align: {{VALUE}};',
				],
			],
			'title_color'       => [
				'label'     => esc_html__( 'Title Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['title_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'title_text_stroke' => [
				'mode'     => 'group',
				'type'     => 'text-stroke',
				'selector' => $widget->selectors['title_text_stroke'],
			],
			'title_text_shadow' => [
				'mode'     => 'group',
				'type'     => 'text-shadow',
				'selector' => $widget->selectors['title_text_shadow'],
			],
			'title_border'      => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $widget->selectors['title_border'],
				'size_units' => [ 'px' ],
			],
			'title_margin'      => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['title_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'title_padding'     => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['title_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'title_style_end'   => [
				'mode' => 'section_end',
			],
		];
	}
}

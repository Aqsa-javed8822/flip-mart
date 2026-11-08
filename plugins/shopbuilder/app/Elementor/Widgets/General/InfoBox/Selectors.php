<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\InfoBox;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */
class Selectors {
	/**
	 * Advanced Heading CSS Selectors.
	 *
	 * @return array
	 */
	public static function get_selectors() {
		return [
			'content_box_style'    => [
				'typography'       => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-layout4 .info-box-counter',
				'count_color'      => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-layout4 .info-box-counter',
				'border'           => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
				'padding'          => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
				'border_radius'    => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap,{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap:before',
				'box_shadow'       => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
				'hover_box_shadow' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap:hover',
				'gradient_bg'      => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
				'bg_overlay'       => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap:before',
			],
			'layout'               => [
				'alignment' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
			],
			'icon'                 => [
				'position' => '{{WRAPPER}} .rtsb-info-box .info-box-icon-holder',
			],
			'title_style'          => [
				'typography' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-title',
				'color'      => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-title',
				'border'     => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-title',
				'padding'    => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-title',
				'margin'     => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-title',
			],
			'content_style'        => [
				'typography' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-description',
				'color'      => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-description',
				'border'     => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-description',
				'padding'    => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-description',
				'margin'     => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-description',
			],
			'primary_button_style' => [
				'typography'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn .icon',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:after,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border'             => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'border_hover_color' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:after',
				'padding'            => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn',
				'margin'             => '{{WRAPPER}} .rtsb-shopbuilder-button',
				'icon_gap'           => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn .icon svg',
				],
			],
			'info_box_icon_style'  => [
				'box_shadow'         => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'color'              => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap .icon',
				'hover_color'        => '{{WRAPPER}} .rtsb-info-box:hover .rtsb-info-box-wrap .icon-wrap .icon',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'border_top_color'   => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap:hover .icon-wrap',
				'border_hover_color' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap:hover .icon-wrap',
				'border'             => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'icon_width'         => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'icon_height'        => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'border_radius'      => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'margin'             => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap .icon',
					'svg'       => '{{WRAPPER}} .rtsb-info-box .rtsb-info-box-wrap .icon-wrap .icon svg',
				],
			],
		];
	}
}

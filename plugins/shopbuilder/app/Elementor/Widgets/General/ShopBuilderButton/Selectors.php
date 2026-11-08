<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderButton;

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
			'primary_button_style'   => [
				'alignment'          => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap',
				'typography'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'color'              => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn .icon',
				'hover_color'        => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:hover,{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:hover .icon',
				'bg_color'           => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:before',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:after',
				'border'             => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'hover_box_shadow'   => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border_hover_color' => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'padding'            => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'margin'             => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn',
				'icon_gap'           => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn .text-wrap',
				'button_gap'         => '{{WRAPPER}} .rtsb-sb-button-layout2 .rtsb-shopbuilder-button',
				'connector_position' => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn .icon svg',
				],
			],
			'secondary_button_style' => [
				'alignment'          => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap',
				'typography'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'color'              => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn,{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn .icon',
				'hover_color'        => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:hover,{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:hover .icon',
				'bg_color'           => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:before',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:after',
				'border'             => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'hover_box_shadow'   => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:hover',
				'border_hover_color' => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'padding'            => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'margin'             => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn',
				'icon_gap'           => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-shopbuilder-button-wrap .rtsb-shopbuilder-button .rtsb-btn.rtsb-secondary-btn .icon svg',
				],
			],
			'connector_style'        => [
				'typography'         => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'color'              => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'bg_color'           => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'border'             => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'box_shadow'         => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'border_radius'      => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner i',
					'svg'       => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner svg',
				],
				'connector_width'    => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'connector_height'   => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
				'button_gap'         => '{{WRAPPER}} .rtsb-sb-button-layout2 .rtsb-shopbuilder-button',
				'connector_position' => '{{WRAPPER}} .rtsb-sb-button-layout2 .connector-inner',
			],
		];
	}
}

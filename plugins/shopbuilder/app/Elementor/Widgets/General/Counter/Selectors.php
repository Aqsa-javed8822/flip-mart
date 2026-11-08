<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Counter;

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
			'content_box_style'   => [
				'border'             => '{{WRAPPER}} .rtsb-counter .counter-inner',
				'border_hover_color' => '{{WRAPPER}} .rtsb-counter .counter-inner:hover',
				'padding'            => '{{WRAPPER}} .rtsb-counter .counter-inner',
				'border_radius'      => '{{WRAPPER}} .rtsb-counter .counter-inner,{{WRAPPER}} .rtsb-counter .counter-inner:before',
				'box_shadow'         => '{{WRAPPER}} .rtsb-counter .counter-inner',
				'hover_box_shadow'   => '{{WRAPPER}} .rtsb-counter .counter-inner:hover',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-counter .counter-inner',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-counter .counter-inner:hover',
				'bg_overlay'         => '{{WRAPPER}} .rtsb-counter .counter-inner:before',
				'hover_bg_overlay'   => '{{WRAPPER}} .rtsb-counter .counter-inner:hover:before',
			],
			'layout'              => [
				'alignment'          => '{{WRAPPER}} .rtsb-counter .counter-inner,{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-content',
				'vertical_alignment' => '{{WRAPPER}} .rtsb-counter .counter-inner',
			],
			'icon'                => [
				'position' => '{{WRAPPER}} .rtsb-counter .counter-inner .counter-icon-holder',
			],
			'title_style'         => [
				'typography' => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-title',
				'color'      => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-title',
				'border'     => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-title',
				'padding'    => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-title',
				'margin'     => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-title',
			],
			'counter_icon_style'  => [
				'box_shadow'    => '{{WRAPPER}} .rtsb-counter .icon-wrap',
				'color'         => '{{WRAPPER}} .rtsb-counter .icon-wrap .icon',
				'gradient_bg'   => '{{WRAPPER}} .rtsb-counter  .icon-wrap',
				'border'        => '{{WRAPPER}} .rtsb-counter  .icon-wrap',
				'icon_width'    => '{{WRAPPER}} .rtsb-counter  .icon-wrap',
				'icon_height'   => '{{WRAPPER}} .rtsb-counter  .icon-wrap',
				'border_radius' => '{{WRAPPER}} .rtsb-counter  .icon-wrap',
				'margin'        => '{{WRAPPER}} .rtsb-counter  .counter-icon-holder',
				'icon_size'     => [
					'font_size' => '{{WRAPPER}} .rtsb-counter .icon-wrap .icon',
					'svg'       => '{{WRAPPER}} .rtsb-counter  .icon-wrap .icon svg',
				],
			],
			'counter_count_style' => [
				'typography'   => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap',
				'color'        => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap',
				'margin'       => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap',
				'suffix_color' => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-prefix',
				'prefix_color' => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-suffix',
				'decoration'   => '{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap .rtsb-counter-prefix,' .
					'{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap .rtsb-counter-suffix,' .
					'{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap .rtsb-count-number,' .
					'{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap .rtsb-count-number .odometer-value,' .
					'{{WRAPPER}} .rtsb-counter .counter-inner .rtsb-counter-number-wrap .rtsb-count-number .odometer-formatting-mark',
			],
		];
	}
}

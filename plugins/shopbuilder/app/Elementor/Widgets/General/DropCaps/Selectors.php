<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\DropCaps;

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
			'columns'              => [
				'cols' => '{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul',
			],
			'content_style'        => [
				'typography'    => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'color'         => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'bg_color'      => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'border'        => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'border_radius' => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'box_shadow'    => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'padding'       => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'margin'        => '{{WRAPPER}} .rtsb-dropcaps-des-wrap,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count .rtsb-dropcaps-des-wrap li',
				'list_gap'      => '{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol',
			],
			'dropcap_letter_style' => [
				'typography'    => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}} .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'text_shadow'   => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}}  .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'color'         => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}}  .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'gradient_bg'   => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'border'        => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'border_radius' => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'padding'       => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}}  .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
				'margin'        => '{{WRAPPER}} .rtsb-dropcaps-des-wrap p:first-of-type:first-letter,{{WRAPPER}}  .rtsb-dropcaps-layout2 .rtsb-dropcaps-des-wrap p:first-letter,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ol li:before,{{WRAPPER}} .rtsb-dropcaps.enable-list-content-count ul li:before',
			],
		];
	}
}

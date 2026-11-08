<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderFaq;

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
			'accordion_style'    => [
				'border'             => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-item .rtsb-accordion-header,{{WRAPPER}} .rtsb-sb-faq-layout3 .rtsb-faq,{{WRAPPER}} .rtsb-sb-faq-layout5 .rtsb-faq',
				'padding'            => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-item .rtsb-accordion-header,{{WRAPPER}} .rtsb-sb-faq-layout3 .rtsb-faq,{{WRAPPER}} .rtsb-sb-faq-layout5 .rtsb-faq',
				'border_radius'      => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-item .rtsb-accordion-header,{{WRAPPER}} .rtsb-sb-faq-layout3 .rtsb-faq,{{WRAPPER}} .rtsb-sb-faq-layout5 .rtsb-faq',
				'box_shadow'         => '{{WRAPPER}} .rtsb-sb-accordion-wrapper,{{WRAPPER}} .rtsb-sb-faq-layout3 .rtsb-faq,{{WRAPPER}} .rtsb-sb-faq-layout5 .rtsb-faq',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-item .rtsb-accordion-header,{{WRAPPER}} .rtsb-sb-faq-layout3 .rtsb-faq,{{WRAPPER}} .rtsb-sb-faq-layout5 .rtsb-faq',
				'active_gradient_bg' => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-item.rtsb-expand-tab .rtsb-accordion-header',
			],
			'title_style'        => [
				'typography'  => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-faq-title',
				'color'       => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-faq-title',
				'hover_color' => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-accordion-item.rtsb-expand-tab .rtsb-faq-title',
				'margin'      => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-faq-title',
			],
			'tab_icon_style'     => [
				'tab_icon_size'       => [
					'font_size' => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
					'svg'       => '{{WRAPPER}} .rtsb-sb-accordion-wrapper .rtsb-accordion-icon svg',
				],
				'color'               => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
				'bg_color'            => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
				'hover_bg_color'      => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-item.rtsb-expand-tab .rtsb-accordion-icon',
				'hover_color'         => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-item.rtsb-expand-tab .rtsb-accordion-icon',
				'tab_icon_gap'        => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-header',
				'border'              => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
				'active_border_color' => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-item.rtsb-expand-tab .rtsb-accordion-icon',
				'icon_width'          => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
				'icon_height'         => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
				'border_radius'       => '{{WRAPPER}}  .rtsb-sb-accordion-wrapper .rtsb-accordion-icon',
			],
			'content_style'      => [
				'typography'  => '{{WRAPPER}}  .rtsb-faq-wrapper  p',
				'color'       => '{{WRAPPER}}  .rtsb-faq-wrapper  p',
				'gradient_bg' => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-widget',
				'margin'      => '{{WRAPPER}}  .rtsb-faq-wrapper  p',
				'padding'     => '{{WRAPPER}}  .rtsb-faq-wrapper .rtsb-widget',
			],
			'count_number_style' => [
				'typography'         => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'color'              => '{{WRAPPER}}   .rtsb-faq .rtsb-faq-count',
				'hover_color'        => '{{WRAPPER}}   .rtsb-faq:hover .rtsb-faq-count',
				'border_hover_color' => '{{WRAPPER}}   .rtsb-faq:hover .rtsb-faq-count',
				'bg_color'           => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'hover_bg_color'     => '{{WRAPPER}}  .rtsb-faq  .rtsb-faq-count::after',
				'border'             => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'margin'             => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'padding'            => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'border_radius'      => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
				'box_shadow'         => '{{WRAPPER}}  .rtsb-faq .rtsb-faq-count',
			],
		];
	}
}

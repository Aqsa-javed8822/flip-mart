<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CallToAction;

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
			'content_box_style'      => [
				'border'          => '{{WRAPPER}} .rtsb-cta-wrapper',
				'padding'         => '{{WRAPPER}} .rtsb-cta-wrapper',
				'border_radius'   => '{{WRAPPER}} .rtsb-cta-wrapper,{{WRAPPER}} .rtsb-cta-wrapper:before',
				'box_shadow'      => '{{WRAPPER}} .rtsb-cta-wrapper',
				'gradient_bg'     => '{{WRAPPER}} .rtsb-cta-wrapper',
				'bg_overlay'      => '{{WRAPPER}} .rtsb-cta-wrapper:before',
				'container_width' => '{{WRAPPER}} .rtsb-cta-wrapper.has-rtsb-parallax .cta-container',
			],
			'image_style'            => [
				'border'          => '{{WRAPPER}} .rtsb-cta-wrapper .cta-img-wrapper img',
				'margin'          => '{{WRAPPER}} .rtsb-cta-wrapper .cta-img-wrapper',
				'border_radius'   => '{{WRAPPER}} .rtsb-cta-wrapper .cta-img-wrapper img,{{WRAPPER}} .rtsb-cta-wrapper .cta-img-wrapper',
				'box_shadow'      => '{{WRAPPER}} .rtsb-cta-wrapper .cta-img-wrapper img',
				'image_width'     => '{{WRAPPER}} .rtsb-call-to-action .rtsb-cta-layout3  .cta-content-left',
				'image_max_width' => '{{WRAPPER}} .rtsb-call-to-action .rtsb-cta-layout3  .cta-content-left',
				'column_gap'      => '{{WRAPPER}} .rtsb-call-to-action .rtsb-cta-layout3 .cta-content-wrap',
			],
			'layout'                 => [
				'alignment' => '{{WRAPPER}} .rtsb-cta-wrapper',
			],
			'title_style'            => [
				'typography' => '{{WRAPPER}} .rtsb-cta-title',
				'color'      => '{{WRAPPER}} .rtsb-cta-title',
				'border'     => '{{WRAPPER}} .rtsb-cta-title',
				'padding'    => '{{WRAPPER}} .rtsb-cta-title',
				'margin'     => '{{WRAPPER}} .rtsb-cta-title',
			],
			'content_style'          => [
				'typography' => '{{WRAPPER}} .rtsb-cta-description, {{WRAPPER}} .rtsb-cta-description p',
				'color'      => '{{WRAPPER}} .rtsb-cta-description',
				'border'     => '{{WRAPPER}} .rtsb-cta-description',
				'padding'    => '{{WRAPPER}} .rtsb-cta-description',
				'margin'     => '{{WRAPPER}} .rtsb-cta-description',
			],
			'primary_button_style'   => [
				'typography'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn .icon',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn:before',
				'hover_gradient_bg'  => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn:after',
				'border'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'btn_width'          => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'btn_height'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'box_shadow'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'border_hover_color' => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn:hover',
				'border_radius'      => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'padding'            => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'margin'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn',
				'icon_gap'           => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn .icon',
					'svg'       => '{{WRAPPER}}  .rtsb-shopbuilder-button .primary-btn .icon svg',
				],
			],
			'secondary_button_style' => [
				'typography'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn .icon',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn:before',
				'hover_gradient_bg'  => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn:after',
				'border'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'btn_width'          => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'btn_height'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'box_shadow'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'border_hover_color' => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn:hover',
				'border_radius'      => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'padding'            => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'margin'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn',
				'icon_gap'           => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn .icon',
					'svg'       => '{{WRAPPER}}  .rtsb-shopbuilder-button .secondary-btn .icon svg',
				],
			],

		];
	}
}

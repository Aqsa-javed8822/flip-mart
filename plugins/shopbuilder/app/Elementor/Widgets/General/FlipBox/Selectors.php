<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\FlipBox;

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
	 * FlipBox CSS Selectors.
	 *
	 * @return array
	 */
	public static function get_selectors() {
		return [
			'content_box_style'   => [
				'front_border'        => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front',
				'front_padding'       => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front',
				'front_border_radius' => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front,{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front:before',
				'front_box_shadow'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front',
				'front_gradient_bg'   => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front',
				'front_bg_overlay'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-front:before',
				'back_border'         => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back',
				'back_padding'        => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back',
				'back_border_radius'  => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back,{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back:before',
				'back_box_shadow'     => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back',
				'back_gradient_bg'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back',
				'back_bg_overlay'     => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper .rtsb-flip-back:before',
			],
			'flip_box'            => [
				'transition'      => '{{WRAPPER}} .rtsb-fliper:not(.sb-zoom-in, .sb-zoom-out, .sb-fade-in, .sb-flip-3d)>.rtsb-fliper-wrapper',
				'flip_box_width'  => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper',
				'flip_box_height' => '{{WRAPPER}} .rtsb-flip-box .rtsb-flipbox-wrapper .rtsb-fliper',
			],
			'layout'              => [
				'alignment' => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flipbox',
			],
			'title_style'         => [
				'front_typography'       => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_color'            => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_bg_color'         => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_title_box_shadow' => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_border'           => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_border_radius'    => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_padding'          => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'front_margin'           => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-title',
				'back_typography'        => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_color'             => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_bg_color'          => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_title_box_shadow'  => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_border'            => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_border_radius'     => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_padding'           => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
				'back_margin'            => '{{WRAPPER}} .rtsb-flip-box  .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-title',
			],
			'content_style'       => [
				'front_typography'      => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description',
				'front_color'           => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description',
				'front_list_icon_color' => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description li:before',
				'front_border'          => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description',
				'front_padding'         => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description',
				'front_margin'          => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .rtsb-flip-box-description',
				'back_typography'       => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description',
				'back_color'            => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description',
				'back_list_icon_color'  => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description li:before',
				'back_border'           => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description',
				'back_padding'          => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description',
				'back_margin'           => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .rtsb-flip-box-description',
			],
			'front_button_style'  => [
				'typography'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn .icon',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:after,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:hover',
				'border'             => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'border_hover_color' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn:after',
				'padding'            => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn',
				'margin'             => '{{WRAPPER}} .rtsb-flip-front .rtsb-shopbuilder-button',
				'icon_gap'           => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.front-primary-btn .icon svg',
				],
			],
			'back_button_style'   => [
				'typography'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn .icon',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:after,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:hover',
				'border'             => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'border_hover_color' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:before,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn:after',
				'padding'            => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn',
				'margin'             => '{{WRAPPER}} .rtsb-flip-back .rtsb-shopbuilder-button ',
				'icon_gap'           => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn.back-primary-btn .icon svg',
				],
			],
			'flip_box_icon_style' => [
				'front_box_shadow'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_color'         => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_gradient_bg'   => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_border'        => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_icon_width'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_icon_height'   => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_border_radius' => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap',
				'front_margin'        => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder',
				'front_icon_size'     => [
					'font_size' => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap .icon',
					'svg'       => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-front .flip-box-icon-holder .icon-wrap .icon svg',
				],
				'back_box_shadow'     => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_color'          => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_gradient_bg'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_border'         => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_icon_width'     => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_icon_height'    => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_border_radius'  => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap',
				'back_margin'         => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder',
				'back_icon_size'      => [
					'font_size' => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap .icon',
					'svg'       => '{{WRAPPER}} .rtsb-flip-box .rtsb-fliper .rtsb-flip-back .flip-box-icon-holder .icon-wrap .icon svg',
				],

			],

		];
	}
}

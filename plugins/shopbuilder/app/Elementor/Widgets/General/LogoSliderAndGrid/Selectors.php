<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\LogoSliderAndGrid;

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
			'logo_item_style' => [
				'border'           => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
				'padding'          => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
				'bg_color'         => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
				'border_radius'    => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
				'box_shadow'       => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
				'logo_bg'          => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-layout2 .rtsb-logo-img',
				'brand_name_bg'    => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-layout2 .rtsb-brand-name',
				'brand_name_hover' => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-layout2 .rtsb-logo-item:hover .rtsb-brand-name',
			],
			'columns'         => [
				'cols'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
				'grid_gap' => [
					'padding'       => '{{WRAPPER}} .rtsb-elementor-container [class*=rtsb-col-]',
					'margin'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
					'slider_layout' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-slider-layout',
					'bottom'        => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-col-grid',
				],
			],
			'element'         => [
				'element_height' => '{{WRAPPER}} .rtsb-logo-slider-and-grid .logo-content-wrapper',
			],
			'title_style'     => [
				'typography'  => '{{WRAPPER}}  .rtsb-logo-slider-and-grid .rtsb-brand-name',
				'color'       => '{{WRAPPER}}  .rtsb-logo-slider-and-grid .rtsb-brand-name',
				'hover_color' => '{{WRAPPER}}  .rtsb-logo-slider-and-grid .rtsb-brand-name',
				'margin'      => '{{WRAPPER}}  .rtsb-logo-slider-and-grid .rtsb-brand-name',
			],
			'image_style'     => [
				'width'         => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-img img',
				'max_width'     => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-img',
				'border'        => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-img img',
				'border_radius' => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-img img',
				'margin'        => '{{WRAPPER}} .rtsb-logo-slider-and-grid .rtsb-logo-img',
			],
			'slider_buttons'  => [
				'arrow_size'          => [
					'icon' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow i',
					'svg'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow svg',
				],
				'arrow_width'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'arrow_height'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'arrow_line_height'   => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow i',
				'dot_width'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_height'          => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_spacing'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'color'               => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'bg_color'            => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow',
				'hover_color'         => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover',
				'hover_bg_color'      => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover',
				'dot_color'           => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'dot_active_color'    => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet-active',
				'border'              => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'border_hover_color'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow:hover, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet:hover',
				'active_border_color' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet-active',
				'border_radius'       => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-arrow, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination-bullet',
				'wrapper_padding'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-nav, {{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-pagination',
			],
		];
	}
}

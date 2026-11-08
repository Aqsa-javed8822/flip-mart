<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Testimonial;

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
			'testimonial_item_style' => [
				'border'        => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
				'padding'       => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
				'bg_color'      => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
				'border_radius' => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
				'box_shadow'    => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
			],
			'columns'                => [
				'cols'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
				'grid_gap' => [
					'padding'       => '{{WRAPPER}} .rtsb-elementor-container [class*=rtsb-col-]',
					'margin'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
					'slider_layout' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-slider-layout',
					'bottom'        => '{{WRAPPER}} .rtsb-testimonial .rtsb-col-grid',
				],
			],
			'element'                => [
				'element_height' => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-item',
			],
			'image_style'            => [
				'width'         => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-author-img img',
				'max_width'     => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-author-img img',
				'border'        => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-author-img img,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout1 .rtsb-testimonial-author-img:after',
				'border_radius' => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-author-img img,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout1 .rtsb-testimonial-author-img:after',
			],
			'title_style'            => [
				'typography'  => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-name',
				'color'       => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-name',
				'hover_color' => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-name',
				'margin'      => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-name',
			],
			'rating_style'           => [
				'typography'         => '{{WRAPPER}}  .rtsb-testimonial .rtsb-rating .rtsb-star-rating',
				'color'              => '{{WRAPPER}}  .rtsb-testimonial .rtsb-rating .rtsb-star-rating',
				'empty_rating_color' => '{{WRAPPER}}  .rtsb-testimonial .rtsb-rating .rtsb-star-rating::before',
				'margin'             => '{{WRAPPER}}  .rtsb-testimonial .rtsb-rating',
			],
			'content_style'          => [
				'typography'       => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-description p,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-description',
				'quote_icon_size'  => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-description .rtsb-quote-icon,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout1 .rtsb-quote-icon,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout5 .rtsb-quote-icon',
				'quote_icon_color' => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-description .rtsb-quote-icon,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout1 .rtsb-quote-icon,{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout5 .rtsb-quote-icon',
				'quote_icon_bg'    => '{{WRAPPER}} .rtsb-testimonial .rtsb-testimonial-layout5 .rtsb-quote-icon',
				'color'            => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-description p,{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-description ',
				'hover_color'      => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-description p',
				'margin'           => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-description',
			],
			'designation_style'      => [
				'typography'  => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-designation',
				'color'       => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-designation',
				'hover_color' => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-designation',
				'margin'      => '{{WRAPPER}}  .rtsb-testimonial .rtsb-testimonial-author-designation',
			],
			'slider_buttons'         => [
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

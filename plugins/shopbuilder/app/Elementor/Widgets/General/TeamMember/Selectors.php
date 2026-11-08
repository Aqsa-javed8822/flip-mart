<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\TeamMember;

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
			'team_item_style'   => [
				'border'        => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
				'padding'       => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
				'bg_color'      => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
				'border_radius' => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
				'box_shadow'    => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
			],
			'columns'           => [
				'cols'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
				'grid_gap' => [
					'padding'       => '{{WRAPPER}} .rtsb-elementor-container [class*=rtsb-col-]',
					'margin'        => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
					'slider_layout' => '{{WRAPPER}} .rtsb-elementor-container .rtsb-slider-layout',
					'bottom'        => '{{WRAPPER}} .rtsb-team-member .rtsb-col-grid',
				],
			],
			'element'           => [
				'element_height' => '{{WRAPPER}} .rtsb-team-member .rtsb-team-member-item',
			],
			'image_style'       => [
				'width'         => '{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout3 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img img',
				'flex_width'    => '{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img',
				'height'        => '{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout3 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img img',
				'max_width'     => '{{WRAPPER}} .rtsb-team-member .rtsb-team-img',
				'border'        => '{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout3 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img',
				'border_radius' => '{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout1 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout2 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout3 .rtsb-team-img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img img,{{WRAPPER}} .rtsb-team-member .rtsb-team-layout4 .rtsb-team-img',
				'margin'        => '{{WRAPPER}} .rtsb-team-member .rtsb-team-img',
				'alignment'     => '{{WRAPPER}} .rtsb-team-member .rtsb-team-inner',
			],
			'title_style'       => [
				'typography'      => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-name',
				'color'           => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-name,{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-name a',
				'hover_color'     => '{{WRAPPER}}  .rtsb-team .rtsb-team-author-name',
				'separator_color' => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-layout4 .rtsb-name-seperator',
				'margin'          => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-name',
			],
			'content_style'     => [
				'typography' => '{{WRAPPER}} .rtsb-team-member .rtsb-content',
				'color'      => '{{WRAPPER}}  .rtsb-team-member .rtsb-content',
				'margin'     => '{{WRAPPER}}  .rtsb-team-member .rtsb-content',
			],
			'designation_style' => [
				'typography'  => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-designation',
				'color'       => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-designation',
				'hover_color' => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-designation',
				'margin'      => '{{WRAPPER}}  .rtsb-team-member .rtsb-team-member-designation',
			],
			'social_icon_style' => [
				'color'              => '{{WRAPPER}} .rtsb-team-member  .rtsb-social li a svg',
				'hover_color'        => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a:hover svg',
				'bg_color'           => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a:hover',
				'icon_width'         => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a',
				'icon_height'        => '{{WRAPPER}} .rtsb-team-member  .rtsb-social li a',
				'border'             => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a',
				'border_hover_color' => '{{WRAPPER}} .rtsb-team-member .rtsb-social li a:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-team-member  .rtsb-social li a',
				'margin'             => '{{WRAPPER}} .rtsb-team-member .rtsb-team-social-area',
				'icon_gap'           => '{{WRAPPER}} .rtsb-team-member .rtsb-team-social-area .rtsb-social',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-team-member  .rtsb-social li a',
					'svg'       => '{{WRAPPER}} .rtsb-team-member  .rtsb-social li a svg',
				],
			],
			'slider_buttons'    => [
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
				'wrapper_padding'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-carousel-slider .swiper-nav',
			],
		];
	}
}

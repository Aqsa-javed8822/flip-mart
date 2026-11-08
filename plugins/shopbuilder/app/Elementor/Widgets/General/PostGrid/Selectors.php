<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PostGrid;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Helper\PostHelpers;

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
			'post_list_item_style'  => [
				'border'               => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'padding'              => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'bg_color'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'border_radius'        => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'box_shadow'           => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'hover_box_shadow'     => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article:hover',
				'content_alignment'    => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article',
				'content_hr_alignment' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item article,{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta,{{WRAPPER}} .rtsb-post-grid-layout1 .rtsb-post-grid-item .rtsb-post-taxonomy-list',
			],
			'columns'               => [
				'cols'     => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
				'grid_gap' => [
					'padding' => '{{WRAPPER}} .rtsb-elementor-container [class*=rtsb-col-]',
					'margin'  => '{{WRAPPER}} .rtsb-elementor-container .rtsb-row',
					'bottom'  => '{{WRAPPER}} .rtsb-post-grid .rtsb-col-grid',
				],
			],
			'image_style'           => [
				'width'         => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-img img',
				'max_width'     => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-img',
				'border'        => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-img',
				'border_radius' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-img',
				'margin'        => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-img',
			],
			'title_style'           => [
				'typography'  => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-title',
				'color'       => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-title,{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-title a',
				'hover_color' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-title a:hover',
				'margin'      => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-title',
			],
			'content_style'         => [
				'typography' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-excerpt',
				'color'      => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-excerpt',
				'margin'     => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-excerpt',
				'padding'    => '{{WRAPPER}}  .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-content',
			],
			'post_meta_style'       => [
				'typography'           => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li',
				'color'                => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li',
				'meta_link_color'      => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li a',
				'meta_separator_color' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta  .rtsb-meta-separator',
				'meta_icon_color'      => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li svg,{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li .icon',
				'hover_color'          => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta li a:hover',
				'margin'               => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta',
				'meta_gap'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-meta',
			],
			'post_taxonomy_style'   => [
				'typography'         => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'border'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'color'              => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'bg_color'           => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'hover_color'        => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a:hover',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a:hover',
				'border_hover_color' => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'padding'            => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list a',
				'margin'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list',
				'taxonomy_gap'       => '{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list,{{WRAPPER}} .rtsb-post-grid .rtsb-post-grid-item .rtsb-post-taxonomy-list li :is(.category-links, .tag-links)',
			],
			'readmore_button_style' => [
				'typography'         => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'text_decoration'    => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn .text-wrap',
				'color'              => '{{WRAPPER}}  .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn,{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn .icon',
				'bg_color'           => '{{WRAPPER}}  .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'hover_color'        => '{{WRAPPER}}  .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn:hover,{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn:hover .icon',
				'hover_bg_color'     => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn:hover',
				'border'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'btn_width'          => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'btn_height'         => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'box_shadow'         => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'border_hover_color' => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn:hover',
				'border_radius'      => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'padding'            => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn',
				'margin'             => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper',
				'icon_gap'           => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn .icon',
					'svg'       => '{{WRAPPER}} .rtsb-post-grid .rtsb-button-wrapper .rtsb-readmore-btn .icon svg',
				],
			],
			'pagination'            => PostHelpers::post_pagination_selectors(),

		];
	}
}

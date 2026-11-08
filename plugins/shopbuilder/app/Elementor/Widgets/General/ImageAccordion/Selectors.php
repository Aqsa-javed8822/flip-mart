<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion;

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
			'accordion_style' => [
				'border'               => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item',
				'border_radius'        => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item',
				'accordion_gap'        => [
					'gap'    => '{{WRAPPER}} .rtsb-image-accordion-wrapper',
					'margin' => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item',
				],
				'accordion_height'     => '{{WRAPPER}} .rtsb-image-accordion-wrapper',
				'overlay_color'        => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item::before',
				'active_overlay_color' => '{{WRAPPER}} .rtsb-image-accordion-hover .item:hover::before,{{WRAPPER}} .rtsb-image-accordion-wrapper .item.checkedItem::before,{{WRAPPER}} .rtsb-image-accordion-wrapper .item.item-active:before',
			],
			'title_style'     => [
				'typography'      => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .title,{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title',
				'number_typo'     => '{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .number',
				'title_icon_size' => [
					'font_size' => '{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title i',
					'svg'       => '{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title i svg',
				],
				'color'           => '{{WRAPPER}}  .rtsb-image-accordion-wrapper .item .content .inner .title,{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title',
				'number_color'    => '{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .number',
				'icon_color'      => '{{WRAPPER}}  .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title i',
				'margin'          => '{{WRAPPER}}  .rtsb-image-accordion-wrapper .item .content .inner .title,{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title',
				'title_icon_gap'  => '{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title i,{{WRAPPER}} .rtsb-image-accordion-wrapper.rtsb-image-accordion-style-2 .item .title-wrap .title svg',
			],
			'link_style'      => [
				'typography'     => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'icon_size'      => [
					'font_size' => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link i',
					'svg'       => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link svg',
				],
				'border'         => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'border_radius'  => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'color'          => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'hover_color'    => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link:hover',
				'bg_color'       => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'hover_bg_color' => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link:hover',
				'margin'         => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
				'padding'        => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .link-list li .link',
			],
			'content_style'   => [
				'typography' => '{{WRAPPER}} .rtsb-image-accordion-wrapper .item .content .inner .desc',
				'color'      => '{{WRAPPER}}  .rtsb-image-accordion-wrapper .item .content .inner .desc',
				'margin'     => '{{WRAPPER}}  .rtsb-image-accordion-wrapper .item .content .inner .desc',
			],
		];
	}
}

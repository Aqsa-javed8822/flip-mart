<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PricingTable;

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
			'content_box_style'         => [
				'typography'         => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box',
				'border'             => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box',
				'border_hover_color' => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:hover',
				'padding'            => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box',
				'border_radius'      => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box,{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:before',
				'box_shadow'         => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box',
				'hover_box_shadow'   => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:hover',
				'gradient_bg'        => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box',
				'hover_gradient_bg'  => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:hover',
				'bg_overlay'         => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:before',
				'hover_bg_overlay'   => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box:hover:before',
			],
			'pricing_table_badge_style' => [
				'typography'                   => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span',
				'color'                        => [
					'color' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span',
					'svg'   => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span .badge-icon svg path',
				],
				'border'                       => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset1',
				'padding'                      => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span',
				'border_radius'                => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset1',
				'box_shadow'                   => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span',
				'vertical_horizontal_position' => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box,{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset3,{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset4,{{WRAPPER}}  .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset5',
				'gradient_bg'                  => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span,{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span:before,{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset3 .triangle-bar,{{WRAPPER}} .rtsb-pricing-table-box .rtsb-ribbon-span.rtsb-pricing-table-badge-preset3:before',
			],
			'layout'                    => [
				'alignment' => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box, {{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box .rtsb-feature-list li, {{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-layout3 .pricing-table-footer',
			],
			'title_style'               => [
				'typography' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-title',
				'color'      => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-title',
				'border'     => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-title',
				'padding'    => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-title',
				'margin'     => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-title',
			],
			'content_style'             => [
				'typography' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-table-description',
				'color'      => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-table-description',
				'border'     => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-table-description',
				'padding'    => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-table-description',
				'margin'     => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-table-description',
			],
			'price_style'               => [
				'typography'       => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap .rtsb-price-wrap',
				'offer_typo'       => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-layout3 .rtsb-offer-text',
				'unit_typo'        => '{{WRAPPER}} .rtsb-pricing-table-box  .rtsb-pricing-wrap .rtsb-price-period',
				'color'            => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap .rtsb-price-wrap,{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap .rtsb-orginal-price.line-through',
				'sale_price_color' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap .rtsb-price-wrap .rtsb-sale-price',
				'price_unit_color' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap  .rtsb-price-period',
				'offer_color'      => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-layout3 .rtsb-offer-text',
				'offer_bg_color'   => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-layout3 .rtsb-offer-text',
				'border'           => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap',
				'padding'          => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap',
				'margin'           => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-pricing-wrap',
			],
			'feature_item_style'        => [
				'typography'      => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li',
				'color'           => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li',
				'list_icon_color' => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li .list-icon',
				'list_dot_color'  => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li.list-type-bullet:before',
				'cross_color'     => '{{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box .rtsb-feature-list li.has-cross-text .list-text, {{WRAPPER}} .rtsb-pricing-table .rtsb-pricing-table-box .rtsb-feature-list li.has-cross-text .list-icon',
				'border'          => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li',
				'padding'         => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list li',
				'margin'          => '{{WRAPPER}} .rtsb-pricing-table-box .rtsb-feature-list',
				'icon_size'       => [
					'font_size' => '{{WRAPPER}}  .rtsb-pricing-table-box .rtsb-feature-list li .list-icon',
					'svg'       => '{{WRAPPER}}  .rtsb-pricing-table-box .rtsb-feature-list li .list-icon svg',
				],
			],
			'primary_button_style'      => [
				'typography'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'color'              => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn .icon',
				'line_color'         => '{{WRAPPER}}  .rtsb-pricing-table .rtsb-pricing-table-layout3 .rtsb-pricing-button-separator',
				'hover_color'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover .icon',
				'gradient_bg'        => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:before',
				'hover_gradient_bg'  => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover,{{WRAPPER}} .rtsb-shopbuilder-button .rtsb-btn:after',
				'border'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'btn_width'          => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'btn_height'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'box_shadow'         => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'hover_box_shadow'   => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border_hover_color' => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:hover',
				'border_radius'      => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:before,{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn:after',
				'padding'            => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'margin'             => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn',
				'icon_gap'           => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn .text-wrap',
				'icon_size'          => [
					'font_size' => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn .icon',
					'svg'       => '{{WRAPPER}}  .rtsb-shopbuilder-button .rtsb-btn .icon svg',
				],
			],
			'pricing_table_icon_style'  => [
				'box_shadow'    => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'color'         => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon',
				'gradient_bg'   => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'border'        => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'icon_width'    => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'icon_height'   => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'border_radius' => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon-wrap',
				'margin'        => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder',
				'icon_size'     => [
					'font_size' => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon',
					'svg'       => '{{WRAPPER}}  .rtsb-pricing-table-box .pricing-table-icon-holder .icon svg',
				],
			],
		];
	}
}

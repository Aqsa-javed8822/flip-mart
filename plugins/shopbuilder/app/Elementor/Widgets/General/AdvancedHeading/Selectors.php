<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\AdvancedHeading;

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
			'layout'                        => [
				'alignment' => '{{WRAPPER}} .rtsb-advanced-heading',
			],
			'heading_style'                 => [
				'typography' => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'alignment'  => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'color'      => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'bg_color'   => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'border'     => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'padding'    => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text',
				'margin'     => '{{WRAPPER}} .rtsb-advanced-heading .advanced-heading-text,{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-wrap',
			],
			'separator_style'               => [
				'width'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-separator-line',
				'height' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-separator-line',
				'color'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-separator-line',
				'margin' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-separator-line',
			],
			'sub_heading_style'             => [
				'typography' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title .sub-heading-text',
				'alignment'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title-wrap',
				'color'      => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
				'bg_color'   => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
				'border'     => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
				'bar_width'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
				'bar_height' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
				'bar_color'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
				'bar_radius' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
				'bar_gap'    => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
				'padding'    => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
				'margin'     => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			],
			'description_style'             => [
				'typography' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'alignment'  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'color'      => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'bg_color'   => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'border'     => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'padding'    => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
				'margin'     => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
			],
			'heading_content_align'         => '{{WRAPPER}} .rtsb-advanced-heading',
			'sub_heading_typo'              => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'sub_heading_color'             => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'sub_heading_border'            => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'sub_heading_margin'            => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'sub_heading_padding'           => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'sub_heading_bar_width'         => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
			'sub_heading_bar_height'        => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
			'sub_heading_bar_color'         => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
			'sub_heading_bar_border_radius' => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-sub-title-bar',
			'sub_heading_bar_gap'           => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-sub-title',
			'content_typo'                  => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
			'content_color'                 => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
			'content_margin'                => '{{WRAPPER}} .rtsb-advanced-heading .rtsb-advanced-heading-desc',
		];
	}
}

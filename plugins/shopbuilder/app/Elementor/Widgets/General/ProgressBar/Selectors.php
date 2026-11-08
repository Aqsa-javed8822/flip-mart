<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ProgressBar;

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
			'progress_bar_style' => [
				'bg_color'          => '{{WRAPPER}}  .rtsb-progressbar-wrapper .rtsb-pb-circle .rtsb-pb-circle-inner',
				'border_radius'     => '{{WRAPPER}}  .rtsb-progressbar-wrapper .rtsb-pb-line',
				'padding'           => '{{WRAPPER}}  .rtsb-progress-bar-layout4 .rtsb-prgressbar-style-line .rtsb-pb-line',
				'fill_color'        => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-line .rtsb-pb-line-fill',
				'fill_border_color' => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-circle .rtsb-pb-circle-pie .rtsb-pb-circle-half',
				'stroke_color'      => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-line',
				'stroke_fill_color' => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-circle .rtsb-pb-circle-inner',
			],
			'progress_bar'       => [
				'height'           => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-line-fill,{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-line',
				'left_position'    => '{{WRAPPER}} .rtsb-progress-bar-layout4 .rtsb-prgressbar-style-line .rtsb-pb-line-fill',
				'progress_size'    => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-circle',
				'progress_stroke'  => '{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-circle-half,{{WRAPPER}} .rtsb-progressbar-wrapper .rtsb-pb-circle .rtsb-pb-circle-inner',
				'half_circle_size' => [
					'full' => '{{WRAPPER}} .rtsb-progress-bar-layout3 .rtsb-progressbar-wrapper .rtsb-pb-half_circle',
					'half' => '{{WRAPPER}} .rtsb-progress-bar-layout3 .rtsb-progressbar-wrapper .rtsb-pb-circle',
				],
			],
			'labels_style'       => [
				'typography' => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-progressbar-label-wrap .rtsb-pb-label',
				'color'      => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-progressbar-label-wrap .rtsb-pb-label',
				'margin'     => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-progressbar-label-wrap .rtsb-pb-label',
			],
			'count_number_style' => [
				'typography'              => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-pb-count-wrap',
				'color'                   => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-pb-count-wrap',
				'progress_count_bg_color' => [
					'bg_color'     => '{{WRAPPER}}  .rtsb-progress-bar-layout4 .rtsb-prgressbar-style-line .rtsb-pb-count-wrap,{{WRAPPER}}  .rtsb-progress-bar-layout5 .rtsb-prgressbar-style-line .rtsb-pb-count-wrap',
					'border_color' => '{{WRAPPER}} .rtsb-progress-bar-layout4 .rtsb-prgressbar-style-line .rtsb-pb-count-wrap:after',
				],
				'margin'                  => '{{WRAPPER}}  .rtsb-progressbar-wrapper  .rtsb-pb-count-wrap',
				'padding'                 => '{{WRAPPER}}  .rtsb-progress-bar-layout4 .rtsb-prgressbar-style-line .rtsb-pb-count-wrap,{{WRAPPER}}  .rtsb-progress-bar-layout5 .rtsb-prgressbar-style-line .rtsb-pb-count-wrap',
			],
		];
	}
}

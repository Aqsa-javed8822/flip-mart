<?php
/**
 * Selectors class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CountDown;

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
			'content_box_style'     => [
				'border'        => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item',
				'padding'       => '{{WRAPPER}} .rtsb-countdown .countdown-inner',
				'border_radius' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item,{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item:after',
				'box_shadow'    => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item',
				'countdown_gap' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown',
				'gradient_bg'   => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item',
				'circle_width'  => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item',
				'circle_height' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item',
				'bg_overlay'    => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-item:before',
			],
			'countdown_circle'      => [
				'stroke'           => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout5 .rtsbCircleTrack',
				'gap'              => '{{WRAPPER}} .rtsb-countdown-layout5 .rtsb-countdown',
				'down_track_color' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout5 .rtsbCircleTrack',
				'up_track_color'   => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout5 .rtsbCircleTrack.rtsbCircleTrackUp',
			],
			'layout'                => [
				'alignment' => '{{WRAPPER}} .rtsb-countdown .countdown-inner,{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-content',
			],
			'countdown_count_style' => [
				'typography'   => '{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-number-wrap',
				'color'        => '{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-number-wrap',
				'margin'       => '{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-number-wrap',
				'suffix_color' => '{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-prefix',
				'prefix_color' => '{{WRAPPER}} .rtsb-countdown .countdown-inner .rtsb-countdown-suffix',
			],
			'digits_style'          => [
				'typography'         => '{{WRAPPER}}  .rtsb-countdown-addon .rtsb-countdown-count',
				'color'              => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count',
				'bg_color'           => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-single-digit',
				'margin'             => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count',
				'padding'            => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-single-digit',
				'border_radius'      => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-single-digit',
				'digits_gap'         => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-count',
				'digits_wrapper_gap' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown',
				'digits_width'       => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-single-digit',
				'digits_height'      => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-layout6 .rtsb-countdown-single-digit',
			],
			'labels_style'          => [
				'typography'    => '{{WRAPPER}}  .rtsb-countdown-addon .rtsb-countdown-count-text',
				'color'         => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count-text',
				'bg_color'      => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count-text',
				'margin'        => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count-text',
				'padding'       => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count-text',
				'border_radius' => '{{WRAPPER}} .rtsb-countdown-addon .rtsb-countdown-count-text',
			],
		];
	}
}

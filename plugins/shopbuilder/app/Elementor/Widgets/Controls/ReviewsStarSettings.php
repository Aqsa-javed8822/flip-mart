<?php
/**
 * Main ReviewsSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Review Settings class
 */
class ReviewsStarSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget object.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$selectors = $widget->selectors;
		$fields          = [
			'review_star_icon_style_start'   => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Star Icon', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'review_star_icon_default_color' => [
				'label'     => esc_html__( 'Default color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['review_star_icon_default_color'] => 'color: {{VALUE}};',
				],
			],
			'review_star_icon_color'         => [
				'label'     => esc_html__( 'Star icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['review_star_icon_color'] => 'color: {{VALUE}};',
				],
			],
			'review_star_icon_size'          => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Star icon size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					self::$selectors['review_star_icon_size'] => 'font-size: {{SIZE}}{{UNIT}}!important;',
				],
			],
			'review_star_icon_specing'       => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Star icon spacing (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],

				'selectors'  => [
					self::$selectors['review_star_icon_specing'] => 'letter-spacing: {{SIZE}}{{UNIT}};width: initial;display: inline-flex;',
					self::$selectors['review_star_icon_specing'] . ':before' => 'position: static;',
				],
			],
			'review_star_icon_margin'        => [
				'label'      => esc_html__( 'Rating Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['review_star_icon_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; padding: 0;',
				],
			],
			'review_star_icon_style_end'     => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CheckoutLoginFormSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var object
	 */
	private static $widget = [];
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];
	/**
	 * Controls Settings
	 *
	 * @param object $widget widget object.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = self::$widget->selectors;
		$fields          = CheckoutInfoBoxSettings::widget_fields( $widget );
		$fields          = self::form_fields_others_control( $fields );
		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function form_fields_others_control( $fields ) {
		$insert_array = [
			'others_heading' => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Others Settings', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'label_gap'      => [
				'label'      => esc_html__( 'Fields & Label Gap', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['label_gap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
		];
		$fields       = Fns::insert_controls( 'form_margin', $fields, $insert_array );
		return $fields;
	}
}

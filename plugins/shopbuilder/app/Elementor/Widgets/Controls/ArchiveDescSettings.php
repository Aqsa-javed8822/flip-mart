<?php
/**
 * Main ProductDescription class.
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
 * Product Description class
 */
class ArchiveDescSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$fields = [
			'section_style'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Style', 'shopbuilder' ),
			],
			'typo'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['typo'],
			],
			'align'             => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'default'   => '',
				'selectors' => [
					$widget->selectors['align'] => 'text-align: {{VALUE}};',
				],
			],
			'text_color'        => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$widget->selectors['text_color'] => 'color: {{VALUE}};',
				],
			],
			'text_shadow'       => [
				'mode'     => 'group',
				'type'     => 'text-shadow',
				'selector' => $widget->selectors['text_shadow'],
			],
			'section_style_end' => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}


}

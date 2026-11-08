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
class ArchiveTitleSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$fields = [
			'sec_general'            => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'archive_title_html_tag' => [
				'label'     => esc_html__( 'Title HTML Tag', 'shopbuilder' ),
				'type'      => 'select',
				'options'   => ControlHelper::heading_tags(),
				'default'   => 'h2',
				'separator' => 'default',
			],
			'archive_title_align'    => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'default'   => '',
				'selectors' => [
					$widget->selectors['archive_title_align'] => 'text-align: {{VALUE}};',
				],
			],
			'sec_general_end'        => [
				'mode' => 'section_end',
			],
			'general_style'          => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Style', 'shopbuilder' ),
			],

			'title_typo'             => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['title_typo'],
			],

			'archive_title_color'    => [
				'label'     => esc_html__( 'Title Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['archive_title_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'text_stroke'            => [
				'mode'     => 'group',
				'type'     => 'text-stroke',
				'selector' => $widget->selectors['text_stroke'],
			],
			'text_shadow'            => [
				'mode'     => 'group',
				'type'     => 'text-shadow',
				'selector' => $widget->selectors['text_shadow'],
			],
			'general_style_end'      => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

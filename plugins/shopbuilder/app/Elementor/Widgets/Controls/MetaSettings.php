<?php
/**
 * Main MetaSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * MetaSettings class
 */
class MetaSettings {
	/**
	 * @param object $widget widget.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::flex_alignment();
		$fields    = [
			'meta_settings_content'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Settings', 'shopbuilder' ),
				'tab'   => 'content',
			],

			'meta_layout'               => [
				'label'        => esc_html__( 'Layout', 'shopbuilder' ),
				'type'         => 'choose',
				'default'      => 'column',
				'options'      => [
					'row'    => [
						'title' => esc_html__( 'Inline', 'shopbuilder' ),
						'icon'  => 'eicon-ellipsis-h',
					],
					'column' => [
						'title' => esc_html__( 'New Line', 'shopbuilder' ),
						'icon'  => 'eicon-editor-list-ul',
					],
				],

				'prefix_class' => 'shopbuilder-layout-',
				'separator'    => 'default',
				'selectors'    => [
					$widget->selectors['meta_layout'] => 'flex-direction: {{VALUE}};',
				],
			],

			'align'                     => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'default'   => '',
				'selectors' => [
					$widget->selectors['align'] => 'align-items: {{VALUE}};justify-content: {{VALUE}};',
				],
			],
			'row_gap'                   => [
				'label'       => esc_html__( 'Meta Row gap(px)', 'shopbuilder' ),
				'description' => esc_html__( 'Set the vertical spacing between meta rows.', 'shopbuilder' ),
				'type'        => 'slider',
				'mode'        => 'responsive',
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'     => [
					'size' => 20,
				],
				'selectors'   => [
					$widget->selectors['row_gap'] => 'row-gap: {{SIZE}}{{UNIT}}',
				],
			],
			'column_gap'                => [
				'label'       => esc_html__( 'Meta Column Gap (px)', 'shopbuilder' ),
				'description' => esc_html__( 'Set the horizontal spacing between meta columns.', 'shopbuilder' ),
				'type'        => 'slider',
				'mode'        => 'responsive',
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'     => [
					'size' => 15,
				],
				'selectors'   => [
					$widget->selectors['column_gap'] => 'column-gap: {{SIZE}}{{UNIT}}',
				],
			],
			'show_sku'                  => [
				'label'       => esc_html__( 'Show SKU?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show SKU.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_cat'                  => [
				'label'       => esc_html__( 'Show Category?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show category.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_tag'                  => [
				'label'       => esc_html__( 'Show Tag?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show tag.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_brand'                => [
				'label'       => esc_html__( 'Show Brand?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show brand.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'meta_settings_content_end' => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

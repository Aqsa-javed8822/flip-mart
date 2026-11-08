<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class BreadcrumbsSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget object.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$fields = [
			'section_general'        => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'breadcrumbs_icon'       => [
				'label'            => esc_html__( 'Separator Icon', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'breadcrumbsicon',
				'separator'        => 'default',
				'default'          => [
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
			],
			'section_general_end'    => [
				'mode' => 'section_end',
			],
			'section_style'          => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Style', 'shopbuilder' ),
			],
			'breadcrumbs_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['breadcrumbs_typography'],
			],
			'breadcrumbs_align'      => [
				'label'                => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'                 => 'choose',
				'separator'            => 'default',
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class'         => 'elementor-align-%s',
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors'            => [
					$widget->selectors['breadcrumbs_align'] => 'justify-content: {{VALUE}};',
				],
			],
			'text_color'             => [
				'label'     => esc_html__( 'Active Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .rtsb-breadcrumb .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				],
			],
			'link_color'             => [
				'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['link_color'] => 'color: {{VALUE}}',
				],
			],

			'link_hover_color'       =>
				[
					'label'     => esc_html__( 'Link Hover Color', 'shopbuilder' ),
					'type'      => 'color',
					'selectors' => [
						$widget->selectors['link_hover_color'] => 'color: {{VALUE}}',
					],
				],
			'item_spacing'           => [
				'label'     => esc_html__( 'Space between', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'before',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					$widget->selectors['item_spacing'] => 'margin:0 {{SIZE}}{{UNIT}};',
				],
			],
			'icon_size'              => [
				'label'     => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default'   => [
					'size' => 16,
				],
				'selectors' => [
					$widget->selectors['icon_size']['icon'] => 'font-size:{{SIZE}}{{UNIT}};',
					$widget->selectors['icon_size']['svg'] => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				],
			],
			'icon_color'             => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['icon_color'] => 'color: {{VALUE}}',
				],
			],
			'section_style_end'      => [
				'mode' => 'section_end',
			],

		];
		return $fields;
	}
}

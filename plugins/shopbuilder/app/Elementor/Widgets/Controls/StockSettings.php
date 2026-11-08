<?php
/**
 * Main StockSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Stock Settings class
 */
class StockSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget widget object.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'stock_content'             => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Settings', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'stock_text_typography'     => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['stock_text_typography'],
			],
			'in_stock_color'            => [
				'label'       => esc_html__( 'In Stock Text Color', 'shopbuilder' ),
				'type'        => 'color',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'In stork', 'shopbuilder' ) . '</b>'
				),
				'selectors'   => [
					$widget->selectors['in_stock_color'] => 'color: {{VALUE}} !important',
				],
			],
			'outof_stock_color'         => [
				'label'       => esc_html__( 'Out Of Stock Text Color', 'shopbuilder' ),
				'type'        => 'color',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'Out of stock', 'shopbuilder' ) . '</b>'
				),
				'selectors'   => [
					$widget->selectors['outof_stock_color'] => 'color: {{VALUE}} !important',
				],
			],
			'backorder_stock_color'     => [
				'label'       => esc_html__( 'Backorder Stock Text Color', 'shopbuilder' ),
				'type'        => 'color',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'Backorder stock', 'shopbuilder' ) . '</b>'
				),
				'selectors'   => [
					$widget->selectors['backorder_stock_color'] => 'color: {{VALUE}} !important',
				],
			],
			'icon_size'                 => [
				'label'      => esc_html__( 'Icon Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors'  => [
					$widget->selectors['icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
					$widget->selectors['icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}}',
				],
			],
			'icon_gap'                  => [
				'label'      => esc_html__( 'Icon Gap (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors'  => [
					$widget->selectors['icon_gap'] => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			],
			'stock_alignment'           => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'options'   => [
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
				'selectors' => [
					$widget->selectors['stock_alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
				],
			],
			'stock_padding'             => [
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['stock_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'stock_margin'              => [
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'label'      => esc_html__( 'Margin', 'shopbuilder' ),
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['stock_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'meta_content_end'          => [
				'mode' => 'section_end',
			],
			'in_stock_icon_section'     => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'In Stock Icon', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'in_stock_icon'             => [
				'label'       => esc_html__( 'Icon', 'shopbuilder' ),
				'type'        => 'icons',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'In stork', 'shopbuilder' ) . '</b>'
				),
				'default'     => [
					'value'   => 'rtsb-icon rtsb-icon-check-alt',
					'library' => 'rtsb-fonts',
				],
				'separator'   => 'default',
			],
			'in_stock_icon_end'         => [
				'mode' => 'section_end',
			],
			'out_of_stock_icon_section' => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Out Of Stock Icon', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'out_of_stock_icon'         => [
				'label'       => esc_html__( 'Icon', 'shopbuilder' ),
				'type'        => 'icons',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'Out of stork', 'shopbuilder' ) . '</b>'
				),
				'default'     => [
					'value'   => 'fas fa-times-circle',
					'library' => 'fa-solid',
				],
				'separator'   => 'default',
			],
			'out_of_stock_icon_end'     => [
				'mode' => 'section_end',
			],
			'backorder_icon_section'    => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Backorder Stock Icon', 'shopbuilder' ),
				'tab'   => 'content',
			],
			'stock_backorder_icon'      => [
				'label'       => esc_html__( 'Icon', 'shopbuilder' ),
				'type'        => 'icons',
				'description' => sprintf(
					/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'Backorder stock', 'shopbuilder' ) . '</b>'
				),
				'default'     => [
					'value'   => 'rtsb-icon rtsb-icon-cart',
					'library' => 'rtsb-fonts',
				],
				'separator'   => 'default',
			],
		];

		$fields['backorder_icon_end'] = [
			'mode' => 'section_end',
		];

		if ( Fns::is_module_active( 'pre_order' ) ) {
			$fields['pre_order_icon_section'] = [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Pre-Order Stock Icon', 'shopbuilder' ),
				'tab'   => 'content',
			];

			$fields['stock_pre_order_icon'] = [
				'label'       => esc_html__( 'Icon', 'shopbuilder' ),
				'type'        => 'icons',
				'description' => sprintf(
				/* translators: text*/
					esc_html__( 'Settings will work for %s product', 'shopbuilder' ),
					'<b>' . esc_html__( 'Pre-Order stock', 'shopbuilder' ) . '</b>'
				),
				'default'     => [
					'value'   => 'rtsb-icon rtsb-icon-cart',
					'library' => 'rtsb-fonts',
				],
				'separator'   => 'default',
			];

			$fields['pre_order_icon_end'] = [
				'mode' => 'section_end',
			];
		}

		return $fields;
	}
}

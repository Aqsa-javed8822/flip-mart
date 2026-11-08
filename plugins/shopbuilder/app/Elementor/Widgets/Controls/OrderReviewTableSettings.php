<?php
/**
 * Main OrderReviewTableSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * OrderReviewTableSettings
 */
class OrderReviewTableSettings {
	/**
	 * @param object $widget widget object.
	 *
	 * @return array[]
	 */
	public static function general_settings( $widget ) {
		$fields = TitleSettings::general_settings();
		if ( ! empty( $fields['show_title']['selectors'] ) ) {
			$fields['show_title']['selectors'] = [
				$widget->selectors['show_title'] => 'display:block;',
			];
		}
		$new_fields['table_horizontal_scroll_on_mobile'] = [
			'label'       => esc_html__( 'Show default Table View On Mobile?', 'shopbuilder' ),
			'type'        => 'switch',
			'description' => esc_html__( 'Show default Table View On Mobile?', 'shopbuilder' ),
			'default'     => '',
			'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
		];
		$new_fields['table_min_width']                   = [
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Table Minimum Width', 'shopbuilder' ),
			'description' => esc_html__( 'Enter table min-width (in px),', 'shopbuilder' ),
			'type'        => 'slider',
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
			],
			'condition'   => [
				'table_horizontal_scroll_on_mobile' => 'yes',
			],
			'selectors'   => [
				$widget->selectors['table_min_width'] => 'min-width: {{SIZE}}{{UNIT}};',
			],
		];

		if ( 'rtsb-order-details-table' === $widget->rtsb_base ) {
			$new_fields['table_horizontal_scroll_on_mobile']['condition']['layout'] = ['layout1'];
			$new_fields['table_min_width']['condition']['layout'] = ['layout1'];
		}

		return Fns::insert_controls( 'show_title', $fields, $new_fields, true );
	}
	/**
	 * @param object $widget widget object.
	 *
	 * @return array
	 */
	public static function table_settings( $widget ) {
		$fields = [
			'table_heading_section_start'    => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Table', 'shopbuilder' ),
			],

			'table_cell'                     => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Table Cell', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'table_heading_cell_border'      => [
				'mode'      => 'group',
				'type'      => 'border',
				'selector'  => $widget->selectors['table_heading_cell_border'],
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Table Cell Border', 'shopbuilder' ),
						'label_block' => true,
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
			],

			'table_heading_cell_padding'     => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ '%' ],
				'selectors'  => [
					$widget->selectors['table_heading_cell_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'total_heading_width'            => [
				'label'      => __( 'Max Width (Product) ', 'shopbuilder' ),
				'type'       => 'slider',
				'mode'       => 'responsive',
				'size_units' => [ '%' ],
				'range'      => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors'  => [
					$widget->selectors['total_heading_width'] => 'width: {{SIZE}}{{UNIT}}',
				],
			],

			'total_cell_heading_width'       => [
				'label'      => __( 'Max Width (Total)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'slider',
				'size_units' => [ '%' ],
				'range'      => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors'  => [
					$widget->selectors['total_cell_heading_width'] => 'width: {{SIZE}}{{UNIT}}',
				],
			],

			'table_product_title'                  => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Product Title', 'shopbuilder' )
				),

				'content_classes' => 'elementor-panel-heading-title',
			],
			'table_heading_typography'       => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['table_heading_typography'],
			],
			'table_heading_color'            => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_heading_color'] => 'color: {{VALUE}}',
				],
			],
			'table_heading_background_color' => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_heading_background_color'] => 'background-color: {{VALUE}}',
				],
			],
			'table_heading_align'            => [
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$widget->selectors['table_heading_align'] => 'text-align: {{VALUE}}!important',
				],
				'classes'   => 'elementor-control-align',
			],
			'table_product_total'                  => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Product Total', 'shopbuilder' )
				),

				'content_classes' => 'elementor-panel-heading-title',
			],
			'table_cell_typography'          => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Cell Typography', 'shopbuilder' ),
				'selector' => $widget->selectors['table_cell_typography'],
			],
			'table_cell_color'               => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_cell_color'] => 'color: {{VALUE}}',
				],
			],
			'table_cell_background_color'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['table_cell_background_color'] => 'background-color: {{VALUE}}',
				],
			],

			'table_cell_align'               => [
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$widget->selectors['table_cell_align'] => 'text-align: {{VALUE}}!important; justify-content: {{VALUE}};',
				],
				'classes'   => 'elementor-control-align',
			],

			'table_heading_end'              => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

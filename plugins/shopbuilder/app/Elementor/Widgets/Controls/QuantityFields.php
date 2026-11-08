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
class QuantityFields {

	/**
	 * Widget Field
	 *
	 * @param object $widget Widget object.
	 *
	 * @return array
	 */
	public static function quantity_fields( $widget ) {
		$fields = [
			'sec_quantity'          => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Quantity', 'shopbuilder' ),
			],
			// Note: Control Type may become a select-image control in a future iteration.
			'quantity_style'        => [
				'label'     => esc_html__( 'Style', 'shopbuilder' ),
				'type'      => 'select',
				'options'   => [
					'default' => esc_html__( 'Default', 'shopbuilder' ),
					'style-2' => esc_html__( 'Style 1', 'shopbuilder' ),
					'style-3' => esc_html__( 'Style 2', 'shopbuilder' ),
					'style-4' => esc_html__( 'Style 3', 'shopbuilder' ),
				],
				'separator' => 'default',
				'default'   => 'default',
			],
			'show_inner_border'     => [
				'label'       => esc_html__( 'Show Inner Border?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to Show inner border.', 'shopbuilder' ),
				'default'     => 'yes',
				'condition'   => [
					'quantity_style' => [ 'style-3', 'style-4' ],
				],
			],
			'quantity_input_width'  => [
				'label'     => esc_html__( 'Quantity Field Width', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'selectors' => [
					$widget->selectors['quantity_input_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'quantity_button_width' => [
				'label'     => esc_html__( 'Quantity Button Width', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					$widget->selectors['quantity_button_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'quantity_height'       => [
				'label'     => esc_html__( 'Quantity Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => 50,
				],
				'selectors' => [
					$widget->selectors['quantity_height']['full'] => 'height: {{SIZE}}{{UNIT}};',
					$widget->selectors['quantity_height']['half'] => 'height: calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			],
			'increment_icon'        => [
				'label'     => esc_html__( 'Increment Icon', 'shopbuilder' ),
				'type'      => 'icons',
				'default'   => [
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'quantity_style!' => 'default',
				],
			],
			'decrement_icon'        => [
				'label'     => esc_html__( 'Decrement Icon', 'shopbuilder' ),
				'type'      => 'icons',
				'default'   => [
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'quantity_style!' => 'default',
				],
			],
			'quantity_style_end'    => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @param object $widget Widget object.
	 *
	 * @return array
	 */
	public static function quantity_style_fields( $widget ) {
		$fields = [
			'quantity_section'                  => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Quantity', 'shopbuilder' ),
			],
			'quantity_style_heading'            => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Quantity Field Style', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'condition'       => [
					'quantity_style' => [ 'style-1', 'style-2' ],
				],
			],
			'icon_size'                         => [
				'label'     => esc_html__( 'Icon size', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					$widget->selectors['icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$widget->selectors['icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
			'quantitybox_border_color'          => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .rtsb-quantity-box-group' => '--rtsb-quantity-border-color: {{VALUE}}',
				],
			],

			'quantity_icon_tabs_start'          => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'quantity_icon_normal'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'quantity_icon_color'               => [
				'label'     => esc_html__( 'Quantity icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['quantity_icon_color'] => 'color: {{VALUE}}',
				],
			],
			'quantity_icon_normal_end'          => [
				'mode' => 'tab_end',
			],
			'quantity_icon_hover'               => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'quantity_icon_hover_color'         => [
				'label'     => esc_html__( 'Quantity icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$widget->selectors['quantity_icon_hover_color'] => 'color: {{VALUE}}',
				],
			],
			'quantity_icon_hover_end'           => [
				'mode' => 'tab_end',
			],
			'quantity_icon_tabs_end'            => [
				'mode' => 'tabs_end',
			],

			'text_typography'                   => [
				'type'        => 'typography',
				'separator'   => 'before',
				'mode'        => 'group',
				'label'       => esc_html__( 'Typography', 'shopbuilder' ),
				'description' => esc_html__( 'Only for Quantity Label.', 'shopbuilder' ),
				'selector'    => $widget->selectors['text_typography'],
			],
			'quantity_number_color'             => [
				'label'     => esc_html__( 'Quantity Number', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['quantity_number_color'] => 'color: {{VALUE}}',
				],
			],
			'quantity_background_color'         => [
				'label'     => esc_html__( 'Quantity Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['quantity_background_color'] => 'background: {{VALUE}}',
				],
			],
			'quantity_border'                   => [
				'mode'           => 'group',
				'type'           => 'border',
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Quantity Field Border', 'shopbuilder' ),
						'label_block' => true,
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
				'selector'       => $widget->selectors['quantity_border'],
			],
			'quantity_radius'                   => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'dimensions',
				'selectors'  => [
					$widget->selectors['quantity_radius'] => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			],
			'qunatity_padding'                  => [
				'label'      => esc_html__( 'Field Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['qunatity_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'quantity_increment_button_padding' => [
				'label'      => esc_html__( 'Quantity Increment Button Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['quantity_increment_button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'quantity_decrement_button_padding' => [
				'label'      => esc_html__( 'Quantity Decrement Button Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$widget->selectors['quantity_decrement_button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'quantity_style_wrapper_heading'    => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Quantity Wrapper Style', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'quantity_wrapper_background_color' => [
				'label'     => esc_html__( 'Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$widget->selectors['quantity_wrapper_background_color'] => 'background: {{VALUE}}',
				],

			],
			'quantity_wrapper_radius'           => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'dimensions',
				'selectors'  => [
					$widget->selectors['quantity_wrapper_radius'] => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			],
			'qunatity_wrapper_padding'          => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					$widget->selectors['qunatity_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'quantity_section_end'              => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}

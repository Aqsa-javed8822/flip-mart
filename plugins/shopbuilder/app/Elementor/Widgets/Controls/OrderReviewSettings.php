<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class OrderReviewSettings {
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
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = self::$widget->selectors;

		return self::general_settings() + TitleSettings::title_settings( self::$widget ) + self::review_tables() + self::quantity_style();
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings() {
		// The function will Overwrite.
		$fields = TitleSettings::general_settings();

		$new_fields['show_quantity']                     = [
			'label'       => esc_html__( 'Show Quantity Selector?', 'shopbuilder' ),
			'type'        => 'switch',
			'description' => esc_html__( 'Switch on to let customers change the item quantity from the order review table.', 'shopbuilder' ),
			'default'     => '',
			'separator'   => 'default',
		];
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
				self::$selectors['table_min_width'] => 'min-width: {{SIZE}}{{UNIT}};',
			],
		];

		return Fns::insert_controls( 'show_title', $fields, $new_fields, true );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function review_tables(): array {
		// The function will Overwrite.
		return [
			'review_table_start'          => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Order Review Table', 'shopbuilder' ),
			],

			'table_wrapper_border'        => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => self::$selectors['table_wrapper_border'],
				'size_units'     => [ 'px' ],
				'separator'      => 'default',
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Table Wrapper Border', 'shopbuilder' ),
						'label_block' => true,
						'default'     => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => true,
						],
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
			],

			'table_border'                => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => self::$selectors['table_border'],
				'size_units'     => [ 'px' ],
				'separator'      => 'default',
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

			'table_wrapper_padding'       => [
				'label'      => esc_html__( 'Wrapper Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					self::$selectors['table_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'table_wrapper_margin'        => [
				'label'      => esc_html__( 'Wrapper Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['table_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],

			'table_label'                 => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Label', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'label_typo'                  => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Label Typography', 'shopbuilder' ),
				'selector' => self::$selectors['label_typo'],
			],
			'label_color'                 => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['label_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'label_item_padding'          => [
				'label'      => esc_html__( 'Item Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['label_item_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],

			'label_bg_color'              => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['label_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'table_body'                  => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Table Body', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'body_text_typo'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['body_text_typo'],
			],
			'table_text_color'            => [
				'label'     => esc_html__( 'Body Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['body_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'table_body_bg_color'         => [
				'label'     => esc_html__( 'Table Body Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['table_body_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'body_item_padding'           => [
				'label'      => esc_html__( 'Item Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['body_item_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'counter_text_color'          => [
				'label'     => esc_html__( 'Counter Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['counter_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'counter_style'               => [
				'label'     => esc_html__( 'Counter Style', 'shopbuilder' ),
				'type'      => 'choose',
				'default'   => 'column',
				'options'   => [
					'auto' => [
						'title' => esc_html__( 'Inline', 'shopbuilder' ),
						'icon'  => 'eicon-ellipsis-h',
					],
					'100%' => [
						'title' => esc_html__( 'New Line', 'shopbuilder' ),
						'icon'  => 'eicon-editor-list-ul',
					],
				],
				'selectors' => [
					self::$selectors['counter_style'] => 'width:{{VALUE}};',
				],
			],
			'table_footer'                => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Table Footer', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'table_footer_label_typo'     => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Footer Typography', 'shopbuilder' ),
				'selector' => self::$selectors['table_footer_label_typo'],
			],
			'table_footer_label_color'    => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['table_footer_label_color'] => 'color: {{VALUE}} !important;',
				],
			],

			'table_footer_text_color'     => [
				'label'     => esc_html__( 'Value Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['table_footer_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'table_footer_label_bg_color' => [
				'label'     => esc_html__( 'Footer Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['table_footer_label_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'table_footer_item_padding'   => [
				'label'      => esc_html__( 'Item Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['table_footer_item_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'review_table_end'            => [
				'mode' => 'section_end',
			],
		];
	}

	/**
	 * Quantity selector style fields.
	 *
	 * @return array
	 */
	public static function quantity_style(): array {
		return [
			'qty_section_start'      => [
				'mode'      => 'section_start',
				'tab'       => 'style',
				'label'     => esc_html__( 'Quantity Selector', 'shopbuilder' ),
				'condition' => [
					'show_quantity' => 'yes',
				],
			],

			'qty_align'              => [
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'mode'      => 'responsive',
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					self::$selectors['qty_align'] => 'justify-content: {{VALUE}};',
				],
			],
			'qty_wrapper_margin'     => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['qty_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'qty_box_label'          => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Box', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'qty_box_height'         => [
				'label'      => esc_html__( 'Height', 'shopbuilder' ),
				'type'       => 'slider',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'selectors'  => [
					self::$selectors['qty_box_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'qty_box_border'         => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => self::$selectors['qty_box_border'],
				'size_units'     => [ 'px' ],
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Border', 'shopbuilder' ),
						'label_block' => true,
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
			],
			'qty_box_radius'         => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					self::$selectors['qty_box_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'qty_box_bg_color'       => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['qty_box_bg_color'] => 'background-color: {{VALUE}};',
				],
			],

			'qty_input_label'        => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Quantity Field', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'qty_input_width'        => [
				'label'      => esc_html__( 'Width', 'shopbuilder' ),
				'type'       => 'slider',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 120,
					],
				],
				'selectors'  => [
					self::$selectors['qty_input_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'qty_input_typo'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['qty_input_typo'],
			],
			'qty_input_color'        => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['qty_input_color'] => 'color: {{VALUE}};',
				],
			],
			'qty_input_bg_color'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['qty_input_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'qty_input_border_color' => [
				'label'     => esc_html__( 'Separator Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['qty_input_border_color'] => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			],

			'qty_btn_label'          => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Buttons', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'qty_btn_width'          => [
				'label'      => esc_html__( 'Width', 'shopbuilder' ),
				'type'       => 'slider',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'selectors'  => [
					self::$selectors['qty_btn_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'qty_icon_size'          => [
				'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'selectors'  => [
					self::$selectors['qty_icon_size'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
			'qty_btn_tabs_start'     => [
				'mode' => 'tabs_start',
			],
			'qty_btn_normal_tab'     => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'qty_btn_color'          => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['qty_btn_color'] => 'color: {{VALUE}};',
				],
			],
			'qty_btn_bg_color'       => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['qty_btn_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'qty_btn_normal_tab_end' => [
				'mode' => 'tab_end',
			],
			'qty_btn_hover_tab'      => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'qty_btn_hover_color'    => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['qty_btn_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'qty_btn_hover_bg_color' => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['qty_btn_hover_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'qty_btn_hover_tab_end'  => [
				'mode' => 'tab_end',
			],
			'qty_btn_tabs_end'       => [
				'mode' => 'tabs_end',
			],

			'qty_section_end'        => [
				'mode' => 'section_end',
			],
		];
	}
}

<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Elementor\Widgets\Controls\ButtonSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CartTableSettings {
	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];

	/**
	 * @param object $widget widget.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$selectors = $widget->selectors;

		return self::general_settings( $widget ) +
			   self::cart_table() +
			   self::quantity_fields( $widget ) +
			   self::action_control() +
			   self::image_settings() +
			   self::table_button( $widget ) +
			   self::input_style() +
			   QuantityFields::quantity_style_fields( $widget ) +
			   self::return_shop_button_style() +
			   self::notice_style();
	}

	/**
	 * @param object $widget widget.
	 *
	 * @return array
	 */
	public static function quantity_fields( $widget ) {
		$fields = QuantityFields::quantity_fields( $widget );
		return $fields;
	}

	/**
	 * @param object $widget widget.
	 *
	 * @return array
	 */
	public static function table_button( $widget ) {
		$fields = ButtonSettings::style_settings( $widget );

		$extra_controls = [
			'clear_cart_button_heading'            => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Clear Cart Button', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'clear_cart_button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			'clear_cart_button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'clear_cart_button_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					self::$selectors['clear_cart_button_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_text_color'         => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['clear_cart_button_text_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_border_color'       => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['clear_cart_button_border_color'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_normal_end'         => [
				'mode' => 'tab_end',
			],
			'clear_cart_button_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'clear_cart_button_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',

				'selectors' => [
					self::$selectors['clear_cart_button_bg_color_hover'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['clear_cart_button_text_color_hover'] => 'color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_border_color_hover' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['clear_cart_button_border_color_hover'] => 'border-color: {{VALUE}} !important;',
				],
			],
			'clear_cart_button_hover_end'          => [
				'mode' => 'tab_end',
			],
			'clear_cart_button_tabs_end'           => [
				'mode' => 'tabs_end',
			],
		];
		$fields         = Fns::insert_controls( 'button_section_end', $fields, $extra_controls, false );

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function cart_table() {
		$fields = [
			'cart_table_settings'                  => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Cart Table', 'shopbuilder' ),
			],

			'cart_element_alignment'               => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
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
					self::$selectors['cart_element_alignment'] => 'text-align: {{VALUE}}; justify-content: {{VALUE}} !important;',
				],
			],
			'cart_table_border'                    => [
				'mode'       => 'group',
				'type'       => 'border',
				'label'      => esc_html__( 'Table Border', 'shopbuilder' ),
				'selector'   => self::$selectors['cart_table_border'],
				'size_units' => [ 'px' ],
			],
			'table_column_header_padding'          => [
				'label'      => esc_html__( 'Column Header Padding(px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['table_column_header_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_table_col_padding'               => [
				'label'      => esc_html__( 'Column Padding(px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_table_col_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_table_row_padding'               => [
				'label'       => esc_html__( 'Row Padding on mobile devices (px)', 'shopbuilder' ),
				'description' => esc_html__( 'It will work only for mobile devices.', 'shopbuilder' ),
				'type'        => 'dimensions',
				'mode'        => 'responsive',
				'size_units'  => [ 'px' ],
				'selectors'   => [
					self::$selectors['cart_table_row_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'table_row_border'                     => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => self::$selectors['table_row_border'],
				'size_units'     => [ 'px' ],
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Row Border', 'shopbuilder' ),
						'label_block' => true,
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
			],
			'table_wrapper_margin'                 => [
				'label'      => esc_html__( 'Table Wrapper Margin(px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['table_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'cart_table_link'                      => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Link Settings', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_link_color'                      => [
				'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_link_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_link_hover_color'                => [
				'label'     => esc_html__( 'Link Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_link_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_price_settings'                  => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Price Settings', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_subtotal_price_typo'             => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Price Typography', 'shopbuilder' ),
				'selector' => self::$selectors['cart_subtotal_price_typo'],
			],

			'cart_price_color'                     => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Price Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_price_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_table_heading'                   => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Cart Table Heading', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_heading_element_alignment'       => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
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
					self::$selectors['cart_heading_element_alignment'] => 'text-align: {{VALUE}};',
				],
			],
			'cart_table_header_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['cart_table_header_typography'],
			],
			'cart_table_header_bg_color'           => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Table Header Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_table_header_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'cart_table_header_text_color'         => [
				'label'     => esc_html__( 'Table Header Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'mode'      => 'responsive',
				'selectors' => [
					self::$selectors['cart_table_header_text_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_table_odd_row'                   => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Cart Table Odd Row', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_table_odd_row_bg_color'          => [
				'label'     => esc_html__( 'Table Odd Row Background Color', 'shopbuilder' ),
				'mode'      => 'responsive',
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_table_odd_row_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'cart_table_odd_row_text_color'        => [
				'label'     => esc_html__( 'Table Odd Row Text Color', 'shopbuilder' ),
				'mode'      => 'responsive',
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_table_odd_row_text_color'] => 'color: {{VALUE}};',
				],
			],

			'cart_table_even_row'                  => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Cart Table Even Row', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_table_even_row_bg_color'         => [
				'label'     => esc_html__( 'Table Even Row Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'mode'      => 'responsive',
				'selectors' => [
					self::$selectors['cart_table_even_row_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'cart_table_even_row_text_color'       => [
				'label'     => esc_html__( 'Table Even Row Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'mode'      => 'responsive',
				'selectors' => [
					self::$selectors['cart_table_even_row_text_color'] => 'color: {{VALUE}};',
				],
			],

			'cart_remove_icon_heading'             => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Remove Cart Column', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'cart_remove_button_size'              => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Remove Button Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['cart_remove_button_size'] => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			],
			'cart_remove_icon_size'                => [
				'label'     => esc_html__( 'Remove Icon Font Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['cart_remove_icon_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				],
			],
			'cart_icon_color'                      => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Cart Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_icon_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'cart_icon_bg_color'                   => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Cart Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_icon_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],
			'cart_icon_hover_color'                => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Cart Icon Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_icon_hover_color'] => 'color: {{VALUE}} !important;',
				],
			],
			'cart_icon_hover_bg_color'             => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Cart Icon Hover Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['cart_icon_hover_bg_color'] => 'background-color: {{VALUE}} !important;',
				],
			],

			'cart_product_title_area'              => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Product Title Column', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],

			'cart_title_element_alignment'         => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Title Alignment', 'shopbuilder' ),
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
					self::$selectors['cart_title_element_alignment'] => 'text-align: {{VALUE}} !important; justify-content: {{VALUE}} !important;',
				],
			],

			'cart_product_title_color'             => [
				'label'     => esc_html__( 'Title Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_product_title_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_product_title_typo'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Title Typography', 'shopbuilder' ),
				'selector' => self::$selectors['cart_product_title_typo'],
			],

			'cart_product_title_meta_color'        => [
				'label'     => esc_html__( 'Meta Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['cart_product_title_meta_color'] => 'color: {{VALUE}};',
				],
			],
			'cart_product_title_meta_typo'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Title Meta Typography', 'shopbuilder' ),
				'selector' => self::$selectors['cart_product_title_meta_typo'],
			],
			'cart_product_title_padding'           => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_product_title_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_table_col_attributes_padding'    => [
				'label'      => esc_html__( 'Attributes Padding(px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_table_col_attributes_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_subtotal_heading'                => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Subtotal Column', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'element_alignment'                    => [
				'mode'      => 'responsive',
				'type'      => 'choose',
				'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
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
					self::$selectors['element_alignment'] => 'text-align: {{VALUE}} !important;',
				],
			],

			'cart_subtotal_button_typo'            => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Button Typography', 'shopbuilder' ),
				'selector' => self::$selectors['cart_subtotal_button_typo'],
			],

			'cart_subtotal_price_padding'          => [
				'label'      => esc_html__( 'Price Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_subtotal_price_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_subtotal_price_margin'           => [
				'label'      => esc_html__( 'Price Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_subtotal_price_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_subtotal_button_wrapper_padding' => [
				'label'      => esc_html__( 'Button Wrapper Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_subtotal_button_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_subtotal_button_gap'             => [
				'label'     => esc_html__( 'Button Gap', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['cart_subtotal_button_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],

			'cart_subtotal_col_wrapper_padding'    => [
				'label'      => esc_html__( 'Column Wrapper Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['cart_subtotal_col_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'action_button_section'                => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Action Button', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'action_button_padding'                => [
				'label'      => esc_html__( 'Action Button Area padding(px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['action_button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'cart_table_settings_end'              => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function image_settings() {
		$fields = [
			'table_image_section'           => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Image ', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'table_thumbnail_width'         => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Thumbnail Image Width', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'separator'  => 'default',
				'range'      => [
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'px' => [
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '130',
				],
				'selectors'  => [
					self::$selectors['table_thumbnail_width'] => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'table_thumbnail_padding'       => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['table_thumbnail_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'table_thumbnail_border_radius' => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'separator'  => 'before',
				'selectors'  => [
					self::$selectors['table_thumbnail_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'table_image_section_end'       => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * @param object $widget widget.
	 * @return array
	 */
	public static function general_settings( $widget ) {
		$fields = [
			'general_section'                   => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General ', 'shopbuilder' ),
			],
			'show_coupon_field'                 => [
				'label'       => esc_html__( 'Show Coupon field?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Coupon.', 'shopbuilder' ),
				'separator'   => rtsb()->has_pro() ? 'default' : 'after',
				'default'     => 'yes',
			],
			'show_clear_cart_button'            => [
				'label'       => esc_html__( 'Show Clear Cart Button?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show cart clear button.', 'shopbuilder' ),
				'classes'     => $widget->pro_class(),
			],
			'table_horizontal_scroll_on_mobile' => [
				'label'       => esc_html__( 'Show default Table View On Mobile?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Show default Table View On Mobile?', 'shopbuilder' ),
				'default'     => '',
				'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
			],
			'table_min_width'                   => [
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
			],
			'show_image_on_mobile'              => [
				'label'       => esc_html__( 'Show Image On Mobile?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Image on mobile Devices.', 'shopbuilder' ),
				'default'     => '',
				'separator'   => rtsb()->has_pro() ? 'default' : 'after',
				'condition'   => [
					'table_horizontal_scroll_on_mobile' => '',
				],
			],
			'ajax_on_qty_change'                => [
				'label'       => esc_html__( 'Auto Update Cart?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to auto update cart with Ajax when quantity changes.', 'shopbuilder' ),
				'classes'     => $widget->pro_class(),
			],
			'cart_empty_message'                => [
				'type'        => 'text',
				'label'       => esc_html__( 'Cart Empty Text', 'shopbuilder' ),
				'description' => esc_html__( 'Leave Empty For default.', 'shopbuilder' ),
				'label_block' => true,
				'separator'   => rtsb()->has_pro() ? 'default' : 'before-short',
			],
			'cart_table'                        => [
				'type'        => 'repeater',
				'mode'        => 'repeater',
				'label'       => esc_html__( 'Cart Table', 'shopbuilder' ),
				'fields'      => [
					'cart_table_items'                 => [
						'label'     => esc_html__( 'Table Item', 'shopbuilder' ),
						'type'      => 'select',
						'separator' => 'default',
						'default'   => 'remove',
						'options'   => [
							'remove'       => esc_html__( 'Remove', 'shopbuilder' ),
							'thumbnail'    => esc_html__( 'Thumbnail', 'shopbuilder' ),
							'name'         => esc_html__( 'Product Title', 'shopbuilder' ),
							'price'        => esc_html__( 'Price', 'shopbuilder' ),
							'quantity'     => esc_html__( 'Quantity', 'shopbuilder' ),
							'subtotal'     => esc_html__( 'Total', 'shopbuilder' ),
							'custom_field' => esc_html__( 'Custom Field', 'shopbuilder' ),
						],
					],
					'cart_table_heading_title'         => [
						'label'     => esc_html__( 'Heading', 'shopbuilder' ),
						'type'      => 'text',
						'separator' => 'default',
					],
					// Thumbnail.
					'cart_table_thumbnail_size'        => [
						'type'      => 'select',
						'label'     => esc_html__( 'Thumbnail Size', 'shopbuilder' ),
						'default'   => 'woocommerce_thumbnail',
						'options'   => Fns::get_image_sizes(),
						'condition' => [
							'cart_table_items' => [ 'thumbnail', 'products' ],
						],
					],
					// Remove Icon.
					'cart_table_remove_icon'           => [
						'label'            => esc_html__( 'Icon', 'shopbuilder' ),
						'type'             => 'icons',
						'fa4compatibility' => 'breadcrumbsicon',
						'default'          => [
							'value'   => 'fas fa-times-circle',
							'library' => 'fa-solid',
						],
						'condition'        => [
							'cart_table_items' => 'remove',
						],
					],
					'cart_table_custom_field'          => [
						'label'     => esc_html__( 'Meta Field Key', 'shopbuilder' ),
						'type'      => 'text',
						'condition' => [
							'cart_table_items' => 'custom_field',
						],
					],
					'cart_table_custom_field_fallback' => [
						'label'       => esc_html__( 'Fallback', 'shopbuilder' ),
						'type'        => 'text',
						'default'     => '',
						'description' => esc_html__( 'Show this if field value is empty', 'shopbuilder' ),
						'condition'   => [
							'cart_table_items' => 'custom_field',
						],
					],

					// Product Title.
					'show_sku'                         => [
						'label'       => esc_html__( 'Show SKU?', 'shopbuilder' ),
						'type'        => 'switch',
						'description' => esc_html__( 'Switch on to show Show SKU.', 'shopbuilder' ),
						'separator'   => rtsb()->has_pro() ? 'default' : 'after',
						'classes'     => $widget->pro_class(),
						'default'     => 'yes',
						'condition'   => [
							'cart_table_items' => [ 'name', 'products' ],
						],
					],
					'show_variation_data'              => [
						'label'       => esc_html__( 'Show Custom Data?', 'shopbuilder' ),
						'type'        => 'switch',
						'description' => esc_html__( 'Enable to display any custom data, such as variation details and extra options.', 'shopbuilder' ),
						'separator'   => rtsb()->has_pro() ? 'default' : 'after',
						'classes'     => $widget->pro_class(),
						'default'     => 'yes',
						'condition'   => [
							'cart_table_items' => [ 'name', 'products' ],
						],
					],
					// Sub Total.
					'show_remove_button'               => [
						'label'       => esc_html__( 'Show Remove Button?', 'shopbuilder' ),
						'type'        => 'switch',
						'description' => esc_html__( 'Switch on to show remove button below subtotal.', 'shopbuilder' ),
						'default'     => '',
						'classes'     => $widget->pro_class(),
						'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
						'condition'   => [
							'cart_table_items' => 'subtotal',
						],
					],
					'remove_button_text'               => [
						'label'       => esc_html__( 'Remove Button Text', 'shopbuilder' ),
						'type'        => 'text',
						'default'     => __( 'Remove', 'shopbuilder' ),
						'classes'     => $widget->pro_class(),
						'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
						'label_block' => true,
						'condition'   => [
							'show_remove_button' => 'yes',
						],
					],
					'show_wishlist_button'             => [
						'label'       => esc_html__( 'Show Wishlist Button?', 'shopbuilder' ),
						'type'        => 'switch',
						'description' => esc_html__( 'Switch on to show wishlist button below subtotal.', 'shopbuilder' ),
						'default'     => '',
						'classes'     => $widget->pro_class(),
						'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
						'condition'   => [
							'cart_table_items' => 'subtotal',
						],
					],
					'wishlist_button_text'             => [
						'label'       => esc_html__( 'Wishlist Button Text', 'shopbuilder' ),
						'type'        => 'text',
						'default'     => __( 'Save for Later', 'shopbuilder' ),
						'classes'     => $widget->pro_class(),
						'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
						'label_block' => true,
						'condition'   => [
							'show_wishlist_button' => 'yes',
						],
					],
					'cart_table_cell_width'            => [
						'mode'       => 'responsive',
						'label'      => esc_html__( 'Column Width', 'shopbuilder' ),
						'type'       => 'slider',
						'size_units' => [ 'px', '%' ],
						'range'      => [
							'px' => [
								'min'  => 0,
								'max'  => 1000,
								'step' => 5,
							],
							'%'  => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors'  => [
							self::$selectors['cart_table_cell_width']['th'] . '{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
							self::$selectors['cart_table_cell_width']['td'] . '{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
						],
					],
				],
				'default'     => [
					[
						'cart_table_items'         => 'remove',
						'cart_table_heading_title' => esc_html__( 'Remove', 'shopbuilder' ),
					],
					[
						'cart_table_items'         => 'thumbnail',
						'cart_table_heading_title' => esc_html__( 'Thumbnail', 'shopbuilder' ),
					],
					[
						'cart_table_items'         => 'name',
						'cart_table_heading_title' => esc_html__( 'Product Title', 'shopbuilder' ),
					],
					[
						'cart_table_items'         => 'price',
						'cart_table_heading_title' => esc_html__( 'Price', 'shopbuilder' ),
					],
					[
						'cart_table_items'         => 'quantity',
						'cart_table_heading_title' => esc_html__( 'Quantity', 'shopbuilder' ),
					],
					[
						'cart_table_items'         => 'subtotal',
						'cart_table_heading_title' => esc_html__( 'Total', 'shopbuilder' ),
					],
				],
				'title_field' => '{{{ cart_table_heading_title }}}',
			],

			'general_section_end'               => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function action_control() {
		$fields = [
			'action_control_section'             => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Button Text ', 'shopbuilder' ),
			],
			'cart_table_update_button_heading'   => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Button', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'return_to_shop_text'                => [
				'type'        => 'text',
				'label'       => esc_html__( 'Return to Shop Button Label', 'shopbuilder' ),
				'description' => esc_html__( 'Return to shop button text. Leave Empty For default.', 'shopbuilder' ),
				'separator'   => 'default',
				'label_block' => true,
			],
			'cart_table_update_button_text'      => [
				'label'       => esc_html__( 'Update Cart Button Label', 'shopbuilder' ),
				'type'        => 'text',
				'default'     => esc_html__( 'Update Cart', 'shopbuilder' ),
				'placeholder' => esc_html__( 'Update Cart', 'shopbuilder' ),
				'label_block' => true,
			],
			'cart_table_clear_cart_button_text'  => [
				'label'       => esc_html__( 'Clear Cart Button Label', 'shopbuilder' ),
				'type'        => 'text',
				'default'     => esc_html__( 'Clear All', 'shopbuilder' ),
				'placeholder' => esc_html__( 'Clear All', 'shopbuilder' ),
				'label_block' => true,
				'condition'   => [
					'show_clear_cart_button' => 'yes',
				],
			],
			'cart_table_coupon_form_heading'     => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Coupon Form', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
				'condition'       => [
					'show_coupon_field' => 'yes',
				],
			],
			'cart_table_coupon_button_text'      => [
				'label'       => esc_html__( 'Button Label', 'shopbuilder' ),
				'type'        => 'text',
				'separator'   => 'default',
				'default'     => esc_html__( 'Apply Coupon', 'shopbuilder' ),
				'placeholder' => esc_html__( 'Apply Coupon', 'shopbuilder' ),
				'label_block' => true,
				'condition'   => [
					'show_coupon_field' => 'yes',
				],
			],
			'cart_table_coupon_placeholder_text' => [
				'label'       => esc_html__( 'Placeholder Text', 'shopbuilder' ),
				'type'        => 'text',
				'default'     => esc_html__( 'Coupon Code', 'shopbuilder' ),
				'placeholder' => esc_html__( 'Coupon Code', 'shopbuilder' ),
				'label_block' => true,
				'condition'   => [
					'show_coupon_field' => 'yes',
				],
			],

			'action_control_section_end'         => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function notice_style() {
		$fields = [
			'notice_section'      => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Cart Empty Notice ', 'shopbuilder' ),
			],
			'notice_typography'   => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['notice_typography'],
			],
			'notice_padding'      => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['notice_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator'  => 'before',
			],

			// Tab For normal view.
			'notice_bg_color'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					self::$selectors['notice_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'notice_text_color'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['notice_text_color'] => 'color: {{VALUE}};',
				],
			],
			'notice_border_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['notice_border_color'] => 'border-color: {{VALUE}};',
				],
			],
			'icon_heading'        => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Icon', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'default',
			],
			'notice_icon_size'    => [
				'label'     => esc_html__( 'Notice Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'mode'      => 'responsive',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['notice_icon_size'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'icon_color'          => [
				'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'before',
				'selectors' => [
					self::$selectors['icon_color'] => 'color: {{VALUE}};fill: currentColor;',
				],
			],
			'icon_bg_color'       => [
				'label'     => esc_html__( 'Icon Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['icon_bg_color'] => 'background-color: {{VALUE}};fill: currentColor;',
				],
			],
			'icon_margin'         => [
				'label'      => esc_html__( 'Icon Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['icon_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'notice_section_end'  => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function input_style() {
		$fields = [
			'input_section_start'      => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Coupon Input Field', 'shopbuilder' ),
				'tab'   => 'style',
			],

			'coupon_typography'        => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['coupon_typography'],
			],

			'coupon_input_width'       => [
				'label'     => esc_html__( 'Coupon Width', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 80,
						'max' => 500,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => '180',
				],
				'selectors' => [
					self::$selectors['coupon_input_width'] => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'coupon_input_height'      => [
				'label'     => esc_html__( 'Coupon Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['coupon_input_height'] => 'height: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'coupon_input_border'      => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['coupon_input_border'],
				'size_units' => [ 'px' ],
			],
			'coupon_border_radius'     => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'separator'  => 'before',
				'selectors'  => [
					self::$selectors['coupon_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'input_text_color'         => [
				'label'     => esc_html__( 'Text Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['input_text_color'] => 'color: {{VALUE}};',
				],
			],
			'input_bg_color'           => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					self::$selectors['input_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'coupon_padding'           => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['coupon_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator'  => 'before',
			],
			'coupon_style_section_end' => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function return_shop_button_style() {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );
		$fields = [
			'return_shop_button_section_start'      => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Return Shop Button', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'return_shop_button_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => self::$selectors['return_shop_button_typography'],
			],
			'return_shop_button_height'             => [
				'label'     => esc_html__( 'Button Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['return_shop_button_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],
			'return_shop_button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'return_shop_button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'return_shop_button_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					self::$selectors['return_shop_button_text_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'return_shop_button_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['return_shop_button_bg_color_normal'] => 'background-color: {{VALUE}};',
				],
			],

			'return_shop_button_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['return_shop_button_border'],
				'size_units' => [ 'px' ],
			],
			'return_shop_button_normal_end'         => [
				'mode' => 'tab_end',
			],
			'return_shop_button_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'return_shop_button_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					self::$selectors['return_shop_button_text_color_hover'] => 'color: {{VALUE}};',
				],
			],
			'return_shop_button_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['return_shop_button_bg_color_hover'] => 'background-color: {{VALUE}};',
				],
			],
			'return_shop_button_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					self::$selectors['return_shop_button_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'return_shop_button_hover_end'          => [
				'mode' => 'tab_end',
			],
			'return_shop_button_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'return_shop_button_border_radius'      => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'separator'  => 'before',
				'selectors'  => [
					self::$selectors['return_shop_button_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'return_shop_button_padding'            => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['return_shop_button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'return_shop_button_margin'             => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'selectors'  => [
					self::$selectors['return_shop_button_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'return_shop_button_section_end'        => [
				'mode' => 'section_end',
			],
		];

		return $fields;
	}
}

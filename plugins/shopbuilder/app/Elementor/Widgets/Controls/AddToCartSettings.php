<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Widgets\Controls\ButtonSettings;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class AddToCartSettings {
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
	 * Widget Field
	 *
	 * @param object $widget object.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget    = $widget;
		self::$selectors = $widget->selectors;
		return QuantityFields::quantity_fields( $widget ) +
			self::add_to_cart_fields() +
			self::add_to_cart_style() +
			self::product_price_style_fields() +
			QuantityFields::quantity_style_fields( $widget ) +
			self::variation_style_fields();
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function add_to_cart_fields() {
		$fields = [
			'sec_add_to_cart' => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Add to cart', 'shopbuilder' ),
			],

			'cart_icon'       => [
				'label'     => esc_html__( 'Cart Icon', 'shopbuilder' ),
				'type'      => 'icons',
				'separator' => 'default',
			],
			'cart_icon_align' => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Icon Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'0' => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'fas fa-arrow-left',
					],
					'1' => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'fas fa-arrow-right',
					],
				],
				'default'   => '0',
				'condition' => [
					'quantity_style!' => 'default',
				],
				'selectors' => [
					self::$selectors['cart_icon_align']['icon'] => 'order: {{VALUE}};',
					self::$selectors['cart_icon_align']['svg'] => 'order: {{VALUE}};',
				],
			],
			'cart_icon_gap'   => [
				'label'     => esc_html__( 'Gap', 'shopbuilder' ),
				'type'      => 'slider',
				'mode'      => 'responsive',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					self::$selectors['cart_icon_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'add_to_cart_end' => [
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
	public static function add_to_cart_style() {
		$fields       = ButtonSettings::style_settings( self::$widget );
		$insert_array = [
			'cart_icon_size' => [
				'label'     => esc_html__( 'Icon size', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					self::$selectors['cart_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					self::$selectors['cart_icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
		];
		$fields       = Fns::insert_controls( 'button_padding', $fields, $insert_array );
		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function quantity_style_fields() {
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
					esc_html__( 'Quantity Style', 'shopbuilder' )
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
					self::$selectors['icon_size'] => 'font-size: {{SIZE}}{{UNIT}};',
				],
			],
			'quantitybox_border_color'          => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['quantitybox_border_color'] => 'border-color: {{VALUE}}',
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
					self::$selectors['quantity_icon_color'] => 'color: {{VALUE}}',
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
					self::$selectors['quantity_icon_hover_color'] => 'color: {{VALUE}}',
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
				'selector'    => self::$selectors['text_typography'],
			],
			'quantity_number_color'             => [
				'label'     => esc_html__( 'Quantity Number', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['quantity_number_color'] => 'color: {{VALUE}}',
				],
			],
			'quantity_background_color'         => [
				'label'     => esc_html__( 'Quantity Backgeound', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['quantity_background_color'] => 'background: {{VALUE}}',
				],
			],
			'quantity_border'                   => [
				'mode'     => 'group',
				'type'     => 'border',
				'label'    => esc_html__( 'Quantity Border', 'shopbuilder' ),
				'selector' => self::$selectors['quantity_border'],
			],
			'quantity_radius'                   => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'dimensions',
				'selectors'  => [
					self::$selectors['quantity_radius'] => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			],
			'qunatity_padding'                  => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['qunatity_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'quantity_style_wrapper_heading'    => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Quantity Wrapper Style', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'condition'       => [
					'quantity_style' => [ 'style-1', 'style-2' ],
				],
			],
			'quantity_wrapper_background_color' => [
				'label'     => esc_html__( 'Background', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['quantity_wrapper_background_color'] => 'background: {{VALUE}}',
				],
				'condition' => [
					'quantity_style' => [ 'style-1', 'style-2' ],
				],
			],
			'quantity_wrapper_radius'           => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'dimensions',
				'selectors'  => [
					self::$selectors['quantity_wrapper_radius'] => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition'  => [
					'quantity_style' => [ 'style-1', 'style-2' ],
				],
			],
			'qunatity_wrapper_padding'          => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['qunatity_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'quantity_section_end'              => [
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
	public static function variation_style_fields() {
		$fields = [
			'variation_section'               => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Variations', 'shopbuilder' ),
			],
			'variation_label_heading'         => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Label', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'variation_label_typography'      => [
				'type'        => 'typography',
				'mode'        => 'group',
				'label'       => esc_html__( 'Label Typography', 'shopbuilder' ),
				'description' => esc_html__( 'Only for Variation Label.', 'shopbuilder' ),
				'selector'    => self::$selectors['variation_label_typography'],
			],
			'variation_label_color'           => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['variation_label_color'] => 'color: {{VALUE}}',
				],
			],
			'variation_label_margin'          => [
				'label'      => esc_html__( 'Label Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['variation_label_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'variation_stock_heading'         => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Stock', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'variation_stock_typography'      => [
				'type'        => 'typography',
				'mode'        => 'group',
				'label'       => esc_html__( 'Stock Typography', 'shopbuilder' ),
				'description' => esc_html__( 'Only for variation stock.', 'shopbuilder' ),
				'selector'    => self::$selectors['variation_stock_typography'],
			],
			'variation_stock_color'           => [
				'label'     => esc_html__( 'Stock Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['variation_stock_color'] => 'color: {{VALUE}}',
				],
			],
			'variation_outofstock_color'      => [
				'label'     => esc_html__( 'Out of Stock Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['variation_outofstock_color'] => 'color: {{VALUE}}',
				],
			],
			'variation_swatch_heading'        => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Variation Items', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'variation_height'                => [
				'label'     => esc_html__( 'Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				// 40px gives the label room to sit on its own line-height. The old 30px
				// was fine on its own, but this widget also applies variation_padding to
				// the same elements, and with border-box that left ~10px of content
				// height — enough to squash/clip the term and select labels.
				'default'   => [
					'unit' => 'px',
					'size' => '40',
				],
				'selectors' => [
					self::$selectors['variation_height'] => 'height: {{SIZE}}{{UNIT}};',
					':root'                              => '--details-page-attr-height:{{SIZE}}{{UNIT}};',
				],
			],
			'variation_width'                 => [
				'label'      => esc_html__( 'Min Width', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'slider',
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 900,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				// Matches the height so single-character terms (S, M, L) stay square.
				'default'    => [
					'unit' => 'px',
					'size' => '40',
				],
				'selectors'  => [
					self::$selectors['variation_width'] => 'min-width: {{SIZE}}{{UNIT}};',
					':root'                             => '--details-page-attr-width:{{SIZE}}{{UNIT}};',
				],
			],
			'variation_padding'               => [
				'label'       => esc_html__( 'Padding ', 'shopbuilder' ),
				'description' => sprintf( '<span style="color:#e01e1e;">%s</span>', esc_html__( 'Note: Only for Select And Button Field.', 'shopbuilder' ) ),
				'type'        => 'dimensions',
				'size_units'  => [ 'px' ],
				// Horizontal only. These elements already have a fixed height, so vertical
				// padding fights it rather than adding breathing room — the label ends up
				// squashed against the top/bottom edge (or clipped) instead of centred.
				'default'     => [
					'top'      => '0',
					'right'    => '10',
					'bottom'   => '0',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'separator'   => 'default',
				'selectors'   => [
					self::$selectors['variation_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					':root'                               => '--variation-padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'variation_border'                => [
				'mode'     => 'group',
				'type'     => 'border',
				'label'    => esc_html__( 'Border', 'shopbuilder' ),
				'selector' => self::$selectors['variation_border'],
			],
			'variation_border_radius'         => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'size_units' => [ 'px', '%' ],
				'type'       => 'slider',
				'selectors'  => [
					self::$selectors['variation_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}};',
					':root' => '--variation-attr-border-radius:{{SIZE}}{{UNIT}};',
				],
			],
			'variation_swatch_border_color'   => [
				'label'       => esc_html__( 'Swatch Border Color', 'shopbuilder' ),
				'type'        => 'color',
				'description' => esc_html__( 'Border color of the swatch items (Variation Swatches module).', 'shopbuilder' ),
				'selectors'   => [
					self::$selectors['variation_swatch_border_color'] => '--attribute-border-color: {{VALUE}};',
				],
			],
			'variation_swatch_selected_color' => [
				'label'       => esc_html__( 'Selected Swatch Border Color', 'shopbuilder' ),
				'type'        => 'color',
				'description' => esc_html__( 'Border color of the hovered/selected swatch item.', 'shopbuilder' ),
				'selectors'   => [
					self::$selectors['variation_swatch_selected_color'] => '--attribute-border-color-hover: {{VALUE}};',
				],
			],
			'variation_item_margin'           => [
				'label'      => esc_html__( 'Variation Item Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['variation_item_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'variation_checkmark_heading'     => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Checkmark', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'variation_checkmark_bg_color'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['variation_checkmark_bg_color'] => '--checkmark-bg-color: {{VALUE}};',
				],
			],
			'variation_checkmark_size'        => [
				'label'      => esc_html__( 'Size', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 50,
					],
				],
				'selectors'  => [
					self::$selectors['variation_checkmark_size'] => '--checkmark-width: {{SIZE}}{{UNIT}}; --checkmark-height: {{SIZE}}{{UNIT}};',
				],
			],
			'variation_checkmark_icon_size'   => [
				'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 40,
					],
				],
				'selectors'  => [
					self::$selectors['variation_checkmark_icon_size'] => '--checkmark-font-size: {{SIZE}}{{UNIT}};',
				],
			],
			'variation_clear_heading'         => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Clear Button', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
				'separator'       => 'before',
			],
			'variation_clear_margin'          => [
				'label'      => esc_html__( 'Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['variation_clear_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'variation_clear_padding'         => [
				'label'      => esc_html__( 'Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					self::$selectors['variation_clear_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'variation_section_end'           => [
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
	public static function product_price_style_fields() {
		return [
			'price_section_style_start' => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Price', 'shopbuilder' ),
			],
			'price_typography'          => [
				'type'        => 'typography',
				'separator'   => 'before',
				'mode'        => 'group',
				'label'       => esc_html__( 'Typography', 'shopbuilder' ),
				'description' => esc_html__( 'Only for Variation Price', 'shopbuilder' ),
				'selector'    => self::$selectors['price_typography'],
			],
			'price_color'               => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					self::$selectors['price_color'] => 'color: {{VALUE}}',
				],
			],
			'price_margin'              => [
				'label'      => esc_html__( 'Margin', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'separator'  => 'default',
				'selectors'  => [
					self::$selectors['price_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'price_section_style_end'   => [
				'mode' => 'section_end',
			],
		];
	}
}

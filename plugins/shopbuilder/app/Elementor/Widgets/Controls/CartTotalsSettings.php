<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CartTotalsSettings {
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
	private static $selector = [];

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$widget   = $widget;
		self::$selector = $widget->selectors;
		$fields         = self::general_settings();
		$fields        += TitleSettings::title_settings( $widget );
		$fields        += self::order_review_table_settings( $widget );
		$fields        += ButtonSettings::style_settings( $widget );
		$new_settings   = [
			'table_wrapper_border'  => [
				'mode'           => 'group',
				'type'           => 'border',
				'selector'       => $widget->selectors['table_wrapper_border'],
				'size_units'     => [ 'px' ],
				'fields_options' => [
					'border' => [
						'label'       => esc_html__( 'Table Wrapper Border', 'shopbuilder' ),
						'label_block' => true,
					],
					'color'  => [
						'label' => esc_html__( 'Border Color', 'shopbuilder' ),
					],
				],
			],
			'table_wrapper_padding' => [
				'label'      => esc_html__( 'Table Wrapper Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$widget->selectors['table_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
		];
		$fields         = Fns::insert_controls( 'table_cell_heading', $fields, $new_settings );

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function order_review_table_settings( $widget ) {
		$fields                                      = OrderReviewTableSettings::table_settings( $widget );
		$fields['total_heading_width']['label']      = __( 'Label Max Width', 'shopbuilder' );
		$fields['total_cell_heading_width']['label'] = __( 'Value Max Width', 'shopbuilder' );
		$fields['table_product_title']['raw']        = sprintf(
			'<h3 class="rtsb-elementor-group-heading">%s</h3>',
			esc_html__( 'Heading', 'shopbuilder' )
		);
		$fields['table_product_total']['raw']        = sprintf(
			'<h3 class="rtsb-elementor-group-heading">%s</h3>',
			esc_html__( 'Value', 'shopbuilder' )
		);

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings() {
		$fields                            = TitleSettings::general_settings();
		$fields['show_title']['selectors'] = [
			self::$selector['show_title'] => 'display:block;',
		];
		$new_fields                        = [
			'show_shipping_address' => [
				'label'       => esc_html__( 'Show Shipping Information?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show shipping information.', 'shopbuilder' ),
				'separator'   => 'default',
				'default'     => 'yes',
			],
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
				self::$selector['table_min_width'] => 'min-width: {{SIZE}}{{UNIT}};',
			],
		];

		return Fns::insert_controls( 'show_title', $fields, $new_fields, true );
	}
}

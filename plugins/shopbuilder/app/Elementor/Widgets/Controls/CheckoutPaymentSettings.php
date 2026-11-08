<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CheckoutPaymentSettings {

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		return self::general_settings( $widget ) + ButtonSettings::style_settings( $widget );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_settings( $widget ) {
		$selector = $widget->selectors;
		return [
			'payment_method_start'                  => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Payment Method', 'shopbuilder' ),
				'tab'   => 'style',
			],

			'payment_method_wrapper'                => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Wrapper', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'payment_method_wrapper_bg_color'       => [
				'label'     => esc_html__( 'Wrapper Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['payment_method_wrapper_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'payment_tabs_start'                    => [
				'mode' => 'tabs_start',
			],
			'payment_normal'                        => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],

			'payment_link_color'                    => [
				'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_link_color'] => 'color: {{VALUE}};',
				],
			],
			'payment_normal_end'                    => [
				'mode' => 'tab_end',
			],
			'payment_hover'                         => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],

			'payment_link_hover_color'              => [
				'label'     => esc_html__( 'Link Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_link_hover_color'] => 'color: {{VALUE}};',
				],
			],
			'payment_hover_end'                     => [
				'mode' => 'tab_end',
			],
			'payment_tabs_end'                      => [
				'mode' => 'tabs_end',
			],

			'payment_label_heading'                 => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Payment Label Text', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],

			'payment_label_typography'              => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Label Typography', 'shopbuilder' ),
				'selector' => $selector['payment_label_typography'],
			],
			'payment_label_color'                   => [
				'label'     => esc_html__( 'Label Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_label_color'] => 'color: {{VALUE}};',
				],
			],
			'payment_label_active_hover_color'      => [
				'label'     => esc_html__( 'Label Active/Hover Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_label_active_hover_color'] => 'color: {{VALUE}};',
				],
			],
			// 'payment_label_bg_color'    => [
			// 'label'     => esc_html__( 'Label Background Color', 'shopbuilder' ),
			// 'type'      => 'color',
			//
			// 'selectors' => [
			// $selector['payment_label_bg_color'] => 'background-color: {{VALUE}};',
			// ],
			// ],
			'payment_method_space_between'          => [
				'label'      => esc_html__( 'Space Between (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					$selector['payment_method_space_between'] => 'margin-right: {{SIZE}}{{UNIT}} !important',
				],
			],
			'payment_method_bacs_padding'           => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_method_bacs_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'payment_method_wrap_padding'           => [
				'label'      => esc_html__( 'Wrapper Padding (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_method_wrap_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			],
			'payment_method_bacs_margin'            => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_method_bacs_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'payment_description_heading'           => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Payment Description', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'payment_description_typography'        => [
				'mode'      => 'group',
				'type'      => 'typography',
				'separator' => 'before',
				'label'     => esc_html__( 'Description Typography', 'shopbuilder' ),
				'selector'  => $selector['payment_description_typography'],
			],
			'payment_description_text_color'        => [
				'label'     => esc_html__( 'Description Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_description_text_color'] => 'color: {{VALUE}};',
				],
			],
			'payment_description_bg_color'          => [
				'label'     => esc_html__( 'Description Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['payment_description_bg_color'] => 'background-color: {{VALUE}};',
					$selector['payment_description_bg_color'] . ':before' => 'border-bottom-color: {{VALUE}};',
				],
			],
			'payment_description_border'            => [
				'mode'       => 'group',
				'type'       => 'border',
				'label'      => esc_html__( 'Border', 'shopbuilder' ),
				'selector'   => $selector['payment_description_border'],
				'size_units' => [ 'px' ],
			],
			'payment_method_desc_padding'           => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_method_desc_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'payment_method_desc_margin'            => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_method_desc_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'payment_button_area'                   => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Payment Button Area', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],
			'payment_button_description_typography' => [
				'mode'      => 'group',
				'type'      => 'typography',
				'separator' => 'before',
				'label'     => esc_html__( 'Description Typography', 'shopbuilder' ),
				'selector'  => $selector['payment_button_description_typography'],
			],
			'payment_button_description_text_color' => [
				'label'     => esc_html__( 'Description Text Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$selector['payment_button_description_text_color'] => 'color: {{VALUE}};',
				],
			],
			'payment_button_description_bg_color'   => [
				'label'     => esc_html__( 'Description Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$selector['payment_button_description_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'payment_button_wrapper_padding'        => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_button_wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'payment_button_wrapper_margin'         => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'mode'       => 'responsive',
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$selector['payment_button_wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'paymeny_method_end'                    => [
				'mode' => 'section_end',
			],
		];
	}
}

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
class ProductFlashSale {
    /**
     * Widget Field
     *
     * @return array
     */

    /**
     * Widget Field
     *
     * @return array
     */
    public static function flash_sale($widget) {
        return [
            'flash_sale_section_start'       => [
                'mode'      => 'section_start',
                'label'     => esc_html__( 'Flash Sale', 'shopbuilder' ),
                'tab'       => 'style',
            ],
            'flash_sale_typography'          => [
                'mode'     => 'group',
                'type'     => 'typography',
                'label'    => esc_html__( 'Typography', 'shopbuilder' ),
                'selector' => $widget->selectors['flash_sale_typography'],
            ],
            'product_flash_sale_color'       => [
                'label'     => esc_html__( 'Color', 'shopbuilder' ),
                'type'      => 'color',
                'selectors' => [
                    $widget->selectors['product_flash_sale_color'] => 'color: {{VALUE}};',
                ],
            ],
            'flash_sale_bg_color'            => [
                'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
                'type'      => 'color',
                'selectors' => [
                    $widget->selectors['flash_sale_bg_color'] => 'background-color: {{VALUE}};',
                ],
            ],
            'flash_sale_badge_width'         => [
                'label'      => esc_html__( 'Badge Width (px)', 'shopbuilder' ),
                'type'       => 'slider',
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 500,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    $widget->selectors['flash_sale_badge_width'] => 'width: {{SIZE}}{{UNIT}};min-width: initial;',
                ],
                'separator'  => 'before',
            ],
            'flash_sale_badge_height'        => [
                'label'      => esc_html__( 'Badge Height (px)', 'shopbuilder' ),
                'type'       => 'slider',
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 200,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    $widget->selectors['flash_sale_badge_height'] => 'height: {{SIZE}}{{UNIT}};min-height: initial;',
                ],
            ],
            'flash_sale_badge_border_radius' => [
                'label'      => esc_html__( 'Badge Border Radius (px)', 'shopbuilder' ),
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
                    $widget->selectors['flash_sale_badge_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ],

            'flash_sale_section_end'         => [
                'mode' => 'section_end',
            ],
        ];
    }

}

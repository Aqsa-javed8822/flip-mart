<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductsSettings {
	/**
	 * Selectors
	 *
	 * @var array
	 */
	public $selectors = [];
	/**
	 * Selectors
	 *
	 * @var object
	 */
	public $widget;
	/**
	 * Set Selectors With new Instance
	 *
	 * @param object $widget Widget Object.
	 */
	public function __construct( $widget ) {
		$this->widget    = $widget;
		$this->selectors = $widget->selectors;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function general_section() {
		$fields = [
			'sec_general'       => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'show_flash_sale'   => [
				'label'       => esc_html__( 'Flash Sale Badge', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Enable to display the flash sale badge. This control will apply if the module doesn\'t hide default flash sale badges.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_rating'       => [
				'label'       => esc_html__( 'Rating', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show rating.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'posts_per_page'    => [
				'label'   => esc_html__( 'Products Per Page', 'shopbuilder' ),
				'type'    => 'number',
				'default' => 4,
				'range'   => [
					'px' => [
						'max' => 30,
					],
				],
			],
			'columns'           => [
				'label'   => esc_html__( 'Columns', 'shopbuilder' ),
				'type'    => 'number',
				'default' => 4,
				'min'     => 1,
				'max'     => 6,
			],
			'orderby'           => [
				'label'   => esc_html__( 'Order By', 'shopbuilder' ),
				'type'    => 'select',
				'default' => 'date',
				'options' => [
					'date'       => esc_html__( 'Date', 'shopbuilder' ),
					'title'      => esc_html__( 'Title', 'shopbuilder' ),
					'price'      => esc_html__( 'Price', 'shopbuilder' ),
					'rand'       => esc_html__( 'Random', 'shopbuilder' ),
					'menu_order' => esc_html__( 'Menu Order', 'shopbuilder' ),
				],
			],
			'order'             => [
				'label'   => esc_html__( 'Order', 'shopbuilder' ),
				'type'    => 'select',
				'default' => 'desc',
				'options' => [
					'asc'  => esc_html__( 'ASC', 'shopbuilder' ),
					'desc' => esc_html__( 'DESC', 'shopbuilder' ),
				],
			],
			'general_style_end' => [
				'mode' => 'section_end',
			],
		];
		$args   = [];
		if ( $this->widget->has_title ) {
			$args['show_title']      = [
				'label'       => esc_html__( 'Show Section Title?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Title.', 'shopbuilder' ),
				'default'     => 'yes',
				'separator'   => 'default',
			];
			$args['loop_title_text'] = [
				'label'       => esc_html__( 'Section Title Text', 'shopbuilder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Write section title.', 'shopbuilder' ),
				'condition'   => [
					'show_title' => 'yes',
				],
			];
		}

		if ( $this->widget->has_slider ) {
			$args['slider_activate'] = [
				'label'       => esc_html__( 'Active Slider', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to activate slider.', 'shopbuilder' ),
				'default'     => '',
			];
		}

		if ( ! empty( $args ) ) {
			$fields = Fns::insert_controls( 'sec_general', $fields, $args, true );
		}

		$module_switch = ModuleBtnControls::module_switch();
		$fields        = Fns::insert_controls( 'show_flash_sale', $fields, $module_switch, true );

		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_icons() {

		$fields['widget_icon_style_section'] = [
			'mode'  => 'section_start',
			'label' => esc_html__( 'Icons', 'shopbuilder' ),
		];

		$fields['cart_icon'] = [
			'label'     => esc_html__( 'Cart Icon', 'shopbuilder' ),
			'type'      => 'icons',
			'separator' => 'default',
		];

		$fields = $fields + ModuleBtnControls::module_icons();

		if ( $this->widget->has_pagination ) {
			$fields['prev_icon'] = [
				'label'     => esc_html__( 'Pagination Prev Icon', 'shopbuilder' ),
				'type'      => 'icons',
				'separator' => 'default',
				'condition' => [
					'show_pagination' => 'yes',
				],
			];
			$fields['next_icon'] = [
				'label'     => esc_html__( 'Pagination Next Icon', 'shopbuilder' ),
				'type'      => 'icons',
				'condition' => [
					'show_pagination' => 'yes',
				],
			];
		}

		$fields['widget_icon_style_section_end'] = [
			'mode' => 'section_end',
		];

		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function heading_controls() {
		return [
			'heading_section_start'      => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Heading', 'shopbuilder' ),
				'tab'       => 'style',
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'section_heading_typography' => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $this->selectors['section_heading_typography'],
			],
			'section_heading_color'      => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['section_heading_color'] => 'color: {{VALUE}};',
				],
			],
			'section_title_align'        => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => ControlHelper::alignment(),
				'selectors' => [
					$this->selectors['section_title_align'] => 'text-align: {{VALUE}};',
				],
			],
			'section_title_margin'       => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['section_title_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; padding: 0;',
				],
			],
			'heading_section_end'        => [
				'mode' => 'section_end',
			],
		];
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function image_section() {
		$fields = [
			'image_section_start'    => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Image', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'product_image_bg_color' => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_image_bg_color'] => 'background-color: {{VALUE}};',
				],
				'separator' => 'none',
			],
			'product_image_height'   => [
				'label'       => esc_html__( 'Height (px)', 'shopbuilder' ),
				'description' => esc_html__( 'Leave empty for auto height', 'shopbuilder' ),
				'type'        => 'slider',
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
				],
				'default'     => [
					'size' => '',
				],
				'selectors'   => [
					$this->selectors['product_image_height'] => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'product_image_auto_fit' => [
				'label'     => esc_html__( 'Auto Image Fit', 'shopbuilder' ),
				'type'      => 'switch',
				'default'   => 'yes',
				'selectors' => [
					$this->selectors['product_image_auto_fit'] => 'object-position:center center; object-fit: cover; ',
				],
			],
			'product_image_padding'  => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['product_image_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'image_section_end'      => [
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
	public function product_title() {
		$fields = [
			'product_title_section_start' => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Product Title', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'product_title_typography'    => [
				'mode'           => 'group',
				'type'           => 'typography',
				'label'          => esc_html__( 'Typography', 'shopbuilder' ),
				'fields_options' => [
					'font_size' => [
						'label'      => esc_html__( 'Font Size (px)', 'shopbuilder' ),
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
						'size_units' => [ 'px' ],
					],
				],
				'selector'       => $this->selectors['product_title_typography'],
			],
			'product_title_color'         => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_title_color'] => 'color: {{VALUE}};',
				],
			],

			'product_title_hover_color'   => [
				'label'     => esc_html__( 'Hover Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_title_hover_color'] => 'color: {{VALUE}};',
				],
			],

			'product_title_padding'       => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['product_title_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'product_title_section_end'   => [
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
	public function product_price() {
		$fields = [
			'price_section_start'         => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Product Price', 'shopbuilder' ),
				'tab'   => 'style',
			],
			'product_price_typography'    => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $this->selectors['product_price_typography'],
			],
			'product_price_color'         => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_price_color'] => 'color: {{VALUE}};',
				],
			],

			'product_reguler_price_color' => [
				'label'     => esc_html__( 'Regular Price Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_reguler_price_color'] => 'color: {{VALUE}};',
				],
			],

			'product_price_padding'       => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['product_price_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'price_section_end'           => [
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
	public function flash_sale() {
		$fields = [
			'flash_sale_section_start'       => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Flash Sale ( Default Badges )', 'shopbuilder' ),
				'tab'       => 'style',
				'condition' => [
					'show_flash_sale' => 'yes',
				],
			],
			'flash_sale_typography'          => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $this->selectors['flash_sale_typography'],
			],
			'product_flash_sale_color'       => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['product_flash_sale_color'] => 'color: {{VALUE}};',
				],
			],
			'flash_sale_bg_color'            => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'alpha'     => true,
				'selectors' => [
					$this->selectors['flash_sale_bg_color'] => 'background-color: {{VALUE}};',
				],
			],

			'flash_sale_badge_width'         => [
				'label'      => esc_html__( 'Badge Width (px)', 'shopbuilder' ),
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
					$this->selectors['flash_sale_badge_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
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
					$this->selectors['flash_sale_badge_height'] => 'height: {{SIZE}}{{UNIT}};',
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
					$this->selectors['flash_sale_badge_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			],

			'flash_sale_section_end'         => [
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
	public function add_to_cart() {
		$fields = [
			'cart_button_style_section'      => [
				'mode'  => 'section_start',
				'tab'   => 'style',
				'label' => esc_html__( 'Add to cart button', 'shopbuilder' ),
			],
			'cart_button_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $this->selectors['cart_button_typography'],
				'exclude'  => [ 'text_decoration' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			],
			'cart_button_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'cart_button_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'cart_button_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$this->selectors['cart_button_text_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'cart_button_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$this->selectors['cart_button_bg_color_normal'] => 'background-color: {{VALUE}};',
				],
			],

			'cart_button_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $this->selectors['cart_button_border'],
				'size_units' => [ 'px' ],
			],
			'cart_button_normal_end'         => [
				'mode' => 'tab_end',
			],
			'cart_button_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'cart_button_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					$this->selectors['cart_button_text_color_hover'] => 'color: {{VALUE}};',
				],
			],
			'cart_button_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					$this->selectors['cart_button_bg_color_hover'] => 'background-color: {{VALUE}};',
				],
			],
			'cart_button_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['cart_button_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'cart_button_hover_end'          => [
				'mode' => 'tab_end',
			],
			'cart_button_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'cart_button_border_radius'      => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['cart_button_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'add_cart_button_padding'        => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['add_cart_button_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'add_cart_button_margin'         => [
				'label'      => esc_html__( 'Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['add_cart_button_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'cart_height'                    => [
				'label'     => esc_html__( 'Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => '',
				],
				'selectors' => [
					$this->selectors['cart_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],

			'icon_align'                     => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Icon Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'unset'       => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'fas fa-arrow-left',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'fas fa-arrow-right',
					],
				],
				'selectors' => [
					$this->selectors['icon_align']['align']        => 'display: inline-flex;align-items: center;',
					$this->selectors['icon_align']['flex-direction'] => 'flex-direction: {{VALUE}};',
				],
			],
			'cart_icon_gap'                  => [
				'label'     => esc_html__( 'Icon Gap', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					$this->selectors['cart_icon_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],
			'cart_icon_size'                 => [
				'label'     => esc_html__( 'Cart Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => 15,
				],
				'selectors' => [
					$this->selectors['cart_icon_size'] => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			],

			'cart_button_style_section_end'  => [
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
	public function pagination() {
		$alignment = ControlHelper::alignment();
		unset( $alignment['justify'] );

		$fields = [
			'pagination_style_start'        => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Pagination Style', 'shopbuilder' ),
				'tab'       => 'style',
				'condition' => [
					'show_pagination' => 'yes',
				],
			],
			'pagination_typography'         => [
				'mode'     => 'group',
				'type'     => 'typography',
				'label'    => esc_html__( 'Typography', 'shopbuilder' ),
				'selector' => $this->selectors['pagination_typography'],
				'exclude'  => [ 'text_decoration' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			],
			'align'                         => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => $alignment,
				'selectors' => [
					$this->selectors['align'] => 'justify-content:{{VALUE}};',
				],
			],
			'pagination_gap'                => [
				'label'     => esc_html__( 'Pagination Item Gap', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => '',
				],
				'selectors' => [
					$this->selectors['pagination_gap'] => 'gap: {{SIZE}}{{UNIT}};',
				],
			],

			'pagination_tabs_start'         => [
				'mode' => 'tabs_start',
			],
			// Tab For normal view.
			'pagination_normal'             => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Normal', 'shopbuilder' ),
			],
			'pagination_text_color_normal'  => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',

				'selectors' => [
					$this->selectors['pagination_text_color_normal'] => 'color: {{VALUE}};',
				],
			],
			'pagination_bg_color_normal'    => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['pagination_bg_color_normal'] => 'background-color: {{VALUE}};',
				],
			],

			'pagination_border'             => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => $this->selectors['pagination_border'],
				'size_units' => [ 'px' ],
			],
			'pagination_normal_end'         => [
				'mode' => 'tab_end',
			],
			'pagination_hover'              => [
				'mode'  => 'tab_start',
				'label' => esc_html__( 'Hover', 'shopbuilder' ),
			],
			'pagination_text_color_hover'   => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',
				'separator' => 'default',
				'selectors' => [
					$this->selectors['pagination_text_color_hover'] => 'color: {{VALUE}};',
				],
			],

			'pagination_bg_color_hover'     => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['pagination_bg_color_hover'] => 'background-color: {{VALUE}};',
				],
			],
			'pagination_border_hover_color' => [
				'label'     => esc_html__( 'Border Color', 'shopbuilder' ),
				'type'      => 'color',

				'selectors' => [
					$this->selectors['pagination_border_hover_color'] => 'border-color: {{VALUE}};',
				],
			],
			'pagination_hover_end'          => [
				'mode' => 'tab_end',
			],
			'pagination_tabs_end'           => [
				'mode' => 'tabs_end',
			],
			'pagination_border_radius'      => [
				'label'      => esc_html__( 'Border Radius (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['pagination_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'pagination_padding'            => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['pagination_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			],

			'pagination_width'              => [
				'label'     => esc_html__( 'Width', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => '',
				],
				'selectors' => [
					$this->selectors['pagination_width'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],
			'pagination_height'             => [
				'label'     => esc_html__( 'Height', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => '',
				],
				'selectors' => [
					$this->selectors['pagination_height'] => 'height: {{SIZE}}{{UNIT}};',
				],
			],

			'pagination_icon_size'          => [
				'label'     => esc_html__( 'Icon Size', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'   => [
					'size' => '',
				],
				'selectors' => [
					$this->selectors['pagination_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					$this->selectors['pagination_icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}};',
				],
			],

			'pagination_margin'             => [
				'label'      => esc_html__( 'Pagination Margin (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px' ],
				'selectors'  => [
					$this->selectors['pagination_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],

			'pagination_style_end'          => [
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
	public function module_button_style() {
		$fields = ModuleBtnControls::module_button_style( $this->widget );
		return $fields;
	}
}
